<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    CONST BASE_STORAGE_URL = "http://52.196.162.105/storage/";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = DB::table('books')
            ->selectRaw('books.*, AVG(comments.rate) AS average_rate')
            ->join('comments','books.id','=','comments.bookID')
            ->groupBy('books.id')
            ->orderByDesc('average_rate')
            ->get();

        return $books;
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
            'description' => '',
            'image' => ''
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('books', 'public');

            $image = Image::make(public_path("storage/{$imagePath}"));
            $image->save();

            $imageArray = ['image' => self::BASE_STORAGE_URL . $imagePath];
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
        $book = Book::find($id);
        $comments = $book->comments;
        foreach ($comments as $comment) {
            $comment->user;
        }
        return response()->json($book, Response::HTTP_OK);
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
