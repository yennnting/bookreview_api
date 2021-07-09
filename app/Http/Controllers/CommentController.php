<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $bookid)
    {
        $request->validate([
            'rate' => 'required|numeric|between:0.5,5'
        ]);

        if (!Comment::where('userID', auth()->id())->where('bookID', $bookid)->exists()) {
            $comment = Comment::create([
                'userID' => auth()->id(),
                'bookID' => $bookid,
                'rate' => $request->rate,
                'comment' => $request->comment,
            ]);

            $user = User::find(auth()->id());
            $old_point = $user->getOriginal('point');
            $user->update([
               'point' => $old_point + 5,
            ]);

        } else {
            return response('You have already commented this book.', Response::HTTP_BAD_REQUEST);
        }

        return response()->json($comment, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('update', $comment);
        $content = $request->validate([
            'rate' => 'required|numeric|between:0.5,5',
            'comment' => '',
        ]);

        $comment->update($content);
        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);
        if (Comment::destroy($id)) {
            return response("The comment is deleted.", Response::HTTP_OK);
        } else {
            return response("The comment isn't exist.", Response::HTTP_NOT_FOUND);
        }
    }
}
