<?php

namespace App\Http\Controllers;

use App\Models\AiKnowledgeBase;
use Illuminate\Http\Request;

class AiKnowledgeBaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = AiKnowledgeBase::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kb.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kb.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'keywords' => 'nullable'
        ]);

        $kb = AiKnowledgeBase::create($request->all());

        // Log the activity
        \App\Models\TrainingLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'model_type' => 'App\\Models\\AiKnowledgeBase',
            'model_id' => $kb->id,
            'description' => 'Created new knowledge base entry: ' . $kb->question,
            'metadata' => ['question' => $kb->question, 'keywords' => $kb->keywords],
        ]);

        return redirect()->route('knowledge-base.index')->with('success', 'Data saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = AiKnowledgeBase::findOrFail($id);
        return view('admin.kb.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'keywords' => 'nullable'
        ]);

        $item = AiKnowledgeBase::findOrFail($id);
        $oldQuestion = $item->question;
        $item->update($request->all());

        // Log the activity
        \App\Models\TrainingLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'model_type' => 'App\\Models\\AiKnowledgeBase',
            'model_id' => $item->id,
            'description' => 'Updated knowledge base entry: ' . $item->question,
            'metadata' => ['old_question' => $oldQuestion, 'new_question' => $item->question],
        ]);

        return redirect()->route('knowledge-base.index')->with('success', 'Data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = AiKnowledgeBase::findOrFail($id);
        $question = $item->question;
        $item->delete();

        // Log the activity
        \App\Models\TrainingLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'model_type' => 'App\\Models\\AiKnowledgeBase',
            'model_id' => $id,
            'description' => 'Deleted knowledge base entry: ' . $question,
            'metadata' => ['question' => $question],
        ]);

        return redirect()->route('knowledge-base.index')->with('success', 'Data deleted successfully.');
    }
}
