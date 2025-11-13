<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Trainee;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $quiz->load(['module', 'questions']);

        // No exponer respuestas correctas
        $quiz->questions->makeHidden(['correct_answer', 'explanation']);

        return response()->json([
            'data' => $quiz
        ]);
    }

    public function start(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id'
        ]);

        $attempt = QuizAttempt::create([
            'trainee_id' => $validated['trainee_id'],
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'max_score' => $quiz->questions->sum('points')
        ]);

        // Obtener preguntas (aleatorizadas si está configurado)
        $questions = $quiz->getQuestionsForAttempt();
        $questions->makeHidden(['correct_answer', 'explanation']);

        return response()->json([
            'message' => 'Quiz started',
            'data' => [
                'attempt' => $attempt,
                'questions' => $questions
            ]
        ]);
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.answer' => 'required|string'
        ]);

        $attempt = QuizAttempt::findOrFail($validated['attempt_id']);

        // Verificar que el attempt pertenezca al trainee y al quiz
        if ($attempt->trainee_id != $validated['trainee_id'] || $attempt->quiz_id != $quiz->id) {
            return response()->json(['error' => 'Invalid attempt'], 403);
        }

        // Calcular tiempo transcurrido
        $timeTaken = now()->diffInMinutes($attempt->started_at);

        // Evaluar respuestas
        $results = $this->evaluateQuiz($quiz, $validated['answers']);

        // Actualizar intento
        $attempt->update([
            'answers' => $validated['answers'],
            'score' => $results['score'],
            'passed' => $results['passed'],
            'time_taken_minutes' => $timeTaken,
            'completed_at' => now()
        ]);

        // Si pasó el quiz, actualizar progreso del módulo
        if ($results['passed']) {
            $trainee = Trainee::find($validated['trainee_id']);
            $moduleProgress = $trainee->moduleProgress()
                ->where('module_id', $quiz->module_id)
                ->first();

            if ($moduleProgress) {
                $moduleProgress->update(['quiz_passed' => true]);
                $moduleProgress->updateProgress();
            }

            $trainee->updateOverallProgress();
        }

        return response()->json([
            'message' => $results['passed'] ? 'Quiz passed!' : 'Quiz failed. Try again.',
            'data' => [
                'attempt' => $attempt,
                'results' => $results['details'],
                'passed' => $results['passed'],
                'score' => $results['score'],
                'percentage' => $results['percentage']
            ]
        ]);
    }

    public function attempts(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id'
        ]);

        $attempts = $quiz->attempts()
            ->where('trainee_id', $validated['trainee_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $attempts
        ]);
    }

    private function evaluateQuiz(Quiz $quiz, array $answers): array
    {
        $questions = $quiz->questions;
        $totalScore = 0;
        $maxScore = $questions->sum('points');
        $results = [];

        foreach ($answers as $answerData) {
            $question = $questions->firstWhere('id', $answerData['question_id']);

            if (!$question) {
                continue;
            }

            $isCorrect = $question->checkAnswer($answerData['answer']);
            $points = $isCorrect ? $question->points : 0;
            $totalScore += $points;

            $results[] = [
                'question_id' => $question->id,
                'question' => $question->question,
                'your_answer' => $answerData['answer'],
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'points_earned' => $points,
                'points_possible' => $question->points,
                'explanation' => $question->explanation
            ];
        }

        $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
        $passed = $percentage >= $quiz->passing_score;

        return [
            'score' => $totalScore,
            'max_score' => $maxScore,
            'percentage' => round($percentage, 2),
            'passed' => $passed,
            'passing_score' => $quiz->passing_score,
            'details' => $results
        ];
    }
}
