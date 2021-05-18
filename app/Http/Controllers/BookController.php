<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookfieds = $request->validate([
            'isbn' => 'required',
            'bookname' => 'required',
            'author' => 'required',
            'category' => 'required',
            'publisher' => 'required',
            'publish_date' => 'required',
            'image' => ''
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('books', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"));
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        $book = Book::create(array_merge(
            $bookfieds,
            $imageArray ?? []
        ));

        return $book;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Book::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        $book->update($request->all());
        return $book;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Book::destroy($id);
    }

    /**
     * Search for a name.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Book::where('bookname', 'like', '%'.$name.'%')->get();
    }
}
