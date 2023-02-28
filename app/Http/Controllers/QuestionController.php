<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    /**
     * @param Request $request
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function store(Request $request, Quiz $quiz): JsonResponse
    {
        $validatedData = $request->validate([
            'text'                 => 'required|string',
            'is_mandatory'         => 'required|boolean',
            'options'              => 'array|required|min:2',
            'options.*.text'       => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question = $quiz->questions()->create([
            'text'         => $validatedData['text'],
            'is_mandatory' => $validatedData['is_mandatory'],
        ]);

        $question->options()->createMany($validatedData['options']);

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data' => $question,
        ], 201);
    }

    /**
     * @param Request $request
     * @param Quiz $quiz
     * @param Question $question
     * @return JsonResponse
     */
    public function update(Request $request, Quiz $quiz, Question $question): JsonResponse
    {
        $validatedData = $request->validate([
            'text'                 => 'string',
            'is_mandatory'         => 'boolean',
            'options'              => 'array|required|min:2',
            'options.*.id'         => 'required|integer|exists:question_options,id',
            'options.*.text'       => 'required|string',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $question->update([
            'text'         => $validatedData['text'] ?? $question->text,
            'is_mandatory' => $validatedData['is_mandatory'] ?? $question->is_mandatory,
        ]);

        $question->options()->delete();
        $question->options()->createMany($validatedData['options']);

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data' => $question->load('options'),
        ], 201);
    }

    /**
     * @param Quiz $quiz
     * @param Question $question
     * @return JsonResponse
     */
    public function destroy(Quiz $quiz, Question $question): JsonResponse
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data' => ['message' => 'Question deleted successfully!']
        ], 204);
    }
}
