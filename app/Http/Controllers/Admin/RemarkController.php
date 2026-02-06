<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemarkController extends Controller
{
    /**
     * Store a newly created remark in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
            'commentable_id' => 'required|string',
            'commentable_type' => 'required|string',
            'type' => 'nullable|string'
        ]);

        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'commentable_id' => $request->commentable_id,
            'commentable_type' => $request->commentable_type,
            'type' => $request->input('type', 'internal')
        ]);

        return redirect()->back()->with('success', 'Remark added successfully.');
    }

    /**
     * Remove the specified remark from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Basic authorization check
        if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Remark deleted successfully.']);
    }
}
