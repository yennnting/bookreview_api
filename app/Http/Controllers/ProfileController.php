<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    CONST BASE_STORAGE_URL = "http://52.196.162.105/storage/";

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
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $comments = $user->comments;
        $count = 0;
        foreach ($comments as $comment) {
            $comment->book;
            $count++;
        }
        return response()->json(['user' => $user, 'countOfComments' => $count], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        //
    }

    public function imageUpload(Request $request, $id)
    {
        request()->validate([
            'image' => 'image',
        ]);

        if ($request->has('image')) {
            $imagePath = request('image')->store('profile', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"));
            $image->save();
        }

        $user = User::find($id);
        $user->update([
            'image' => self::BASE_STORAGE_URL . $imagePath,
        ]);

        return $user;
    }
}
