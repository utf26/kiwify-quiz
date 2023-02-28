<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $quizzes = Quiz::with('questions.options')->get();

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data'    => $quizzes,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'title'        => 'required|string',
            'description'  => 'required|string',
            'is_published' => 'required|boolean',
        ]);

        $quiz = Quiz::create([
            'title'        => $validatedData['title'],
            'description'  => $validatedData['description'],
            'is_published' => $validatedData['is_published'],
        ]);

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data'    => $quiz->load('questions.options'),
        ], 201);
    }

    /**
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function show(Quiz $quiz): JsonResponse
    {
        return response()->json([
            'success' => true,
            'errors'  => [],
            'data'    => $quiz->load('questions.options'),
        ]);
    }

    /**
     * @param Request $request
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function update(Request $request, Quiz $quiz): JsonResponse
    {
        $validatedData = $request->validate([
            'title'        => 'string|max:255',
            'description'  => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $quiz->update([
            'title'        => $validatedData['title'] ?? $quiz->title,
            'description'  => $validatedData['description'] ?? $quiz->description,
            'is_published' => $validatedData['is_published'] ?? $quiz->is_published,
        ]);

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data'    => $quiz->load('questions.options'),
        ]);
    }

    /**
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function destroy(Quiz $quiz): JsonResponse
    {
        $quiz->delete();

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data' => ['message' => 'Quiz deleted successfully!']
        ], 204);
    }
}
