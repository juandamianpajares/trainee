<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleProgress;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::with(['lessons' => function ($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return response()->json([
            'data' => $modules
        ]);
    }

    public function show(Module $module)
    {
        $module->load([
            'lessons' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            },
            'lessons.exercises',
            'quizzes.questions'
        ]);

        return response()->json([
            'data' => $module
        ]);
    }

    public function progress(Request $request, Module $module)
    {
        $traineeId = $request->input('trainee_id');

        if (!$traineeId) {
            return response()->json(['error' => 'trainee_id is required'], 400);
        }

        $progress = ModuleProgress::firstOrCreate(
            [
                'trainee_id' => $traineeId,
                'module_id' => $module->id
            ],
            [
                'started_at' => now(),
                'completion_percentage' => 0
            ]
        );

        $progress->updateProgress();

        return response()->json([
            'data' => $progress->fresh()
        ]);
    }
}
