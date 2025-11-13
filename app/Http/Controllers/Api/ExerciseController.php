<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Trainee;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function show(Exercise $exercise)
    {
        $exercise->load('lesson.module');

        return response()->json([
            'data' => $exercise
        ]);
    }

    public function submit(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'submitted_code' => 'required|string'
        ]);

        // Evaluar el código (simulado - en producción usar sandbox)
        $testResults = $this->evaluateCode(
            $validated['submitted_code'],
            $exercise->test_cases ?? []
        );

        $submission = ExerciseSubmission::create([
            'trainee_id' => $validated['trainee_id'],
            'exercise_id' => $exercise->id,
            'submitted_code' => $validated['submitted_code'],
            'is_correct' => $testResults['passed'],
            'score' => $testResults['passed'] ? $exercise->points : 0,
            'test_results' => $testResults,
            'feedback' => $testResults['feedback']
        ]);

        // Si el ejercicio es correcto, actualizar progreso
        if ($testResults['passed']) {
            $trainee = Trainee::find($validated['trainee_id']);
            $lesson = $exercise->lesson;

            $moduleProgress = $trainee->moduleProgress()
                ->where('module_id', $lesson->module_id)
                ->first();

            if ($moduleProgress) {
                $moduleProgress->updateProgress();
            }

            $trainee->updateOverallProgress();
        }

        return response()->json([
            'message' => $testResults['passed'] ? 'Exercise completed successfully!' : 'Exercise failed. Try again.',
            'data' => $submission
        ], $testResults['passed'] ? 200 : 422);
    }

    public function submissions(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id'
        ]);

        $submissions = $exercise->submissions()
            ->where('trainee_id', $validated['trainee_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $submissions
        ]);
    }

    private function evaluateCode(string $code, array $testCases): array
    {
        // Esta es una evaluación simulada
        // En producción, deberías usar un sandbox seguro como:
        // - Docker containers
        // - VM aisladas
        // - Servicios como Judge0, etc.

        $feedback = [];
        $passedTests = 0;
        $totalTests = count($testCases);

        if ($totalTests === 0) {
            // Sin test cases, asumimos que pasa si hay código
            return [
                'passed' => strlen(trim($code)) > 10,
                'tests' => [],
                'feedback' => 'Exercise submitted for review. No automated tests available.'
            ];
        }

        foreach ($testCases as $index => $test) {
            $passed = $this->runTestCase($code, $test);
            $feedback[] = [
                'test' => $test['description'] ?? "Test " . ($index + 1),
                'passed' => $passed,
                'expected' => $test['expected'] ?? null,
                'input' => $test['input'] ?? null
            ];

            if ($passed) {
                $passedTests++;
            }
        }

        $allPassed = $passedTests === $totalTests;

        return [
            'passed' => $allPassed,
            'tests' => $feedback,
            'score' => $totalTests > 0 ? round(($passedTests / $totalTests) * 100) : 0,
            'feedback' => $allPassed
                ? "All tests passed! ({$passedTests}/{$totalTests})"
                : "Some tests failed. Passed: {$passedTests}/{$totalTests}"
        ];
    }

    private function runTestCase(string $code, array $test): bool
    {
        // Simulación básica - NO USAR EN PRODUCCIÓN
        // En producción: ejecutar en sandbox aislado

        try {
            // Verificar patrones básicos en el código
            if (isset($test['pattern'])) {
                return preg_match($test['pattern'], $code) === 1;
            }

            // Por defecto, asumir que pasa si cumple requisitos mínimos
            return strlen(trim($code)) > 20;
        } catch (\Exception $e) {
            return false;
        }
    }
}
