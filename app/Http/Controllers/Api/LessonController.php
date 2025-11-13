<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Trainee;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function show(Lesson $lesson)
    {
        $lesson->load(['module', 'exercises']);

        return response()->json([
            'data' => $lesson
        ]);
    }

    public function start(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id'
        ]);

        $progress = LessonProgress::firstOrCreate(
            [
                'trainee_id' => $validated['trainee_id'],
                'lesson_id' => $lesson->id
            ],
            [
                'started_at' => now()
            ]
        );

        return response()->json([
            'message' => 'Lesson started',
            'data' => $progress
        ]);
    }

    public function complete(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'time_spent_minutes' => 'sometimes|integer|min:0'
        ]);

        $progress = LessonProgress::where([
            'trainee_id' => $validated['trainee_id'],
            'lesson_id' => $lesson->id
        ])->firstOrFail();

        $progress->markCompleted();

        if (isset($validated['time_spent_minutes'])) {
            $progress->addTimeSpent($validated['time_spent_minutes']);
        }

        // Actualizar progreso del módulo
        $trainee = Trainee::find($validated['trainee_id']);
        $moduleProgress = $trainee->moduleProgress()
            ->where('module_id', $lesson->module_id)
            ->first();

        if ($moduleProgress) {
            $moduleProgress->updateProgress();
        }

        // Actualizar progreso general
        $trainee->updateOverallProgress();

        return response()->json([
            'message' => 'Lesson completed',
            'data' => $progress
        ]);
    }

    public function progress(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'trainee_id' => 'required|exists:trainees,id'
        ]);

        $progress = LessonProgress::where([
            'trainee_id' => $validated['trainee_id'],
            'lesson_id' => $lesson->id
        ])->first();

        return response()->json([
            'data' => $progress
        ]);
    }
}
