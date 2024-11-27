<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\MyUtil;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return MyUtil::sendResponse(Buku::all(), 'OK');
        try {
            $buku = Buku::all();
            return MyUtil::sendResponse($buku, 'OK');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required',
            'judul' => 'required',
            'tahun' => 'required',
        ]);
        if ($validator->fails()) {
            return MyUtil::sendError('Validation Error.', $validator->errors(), 400);
        } else {
            if (Buku::where('isbn', '=', $request->isbn)->exists()) {
                return MyUtil::sendError('Duplicate isbn', 'Duplicate on book', 400);
            } else {
                $buku = new Buku();
                $buku->isbn = $request->isbn;
                $buku->judul = $request->judul;
                $buku->pengarang = $request->pengarang;
                $buku->tahun = $request->tahun;
                $buku->save();
                return $this->index();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($isbn)
    {
        try {
            return MyUtil::sendResponse(Buku::findOrFail($isbn), 'OK');
        } catch (ModelNotFoundException $ex) {
            return MyUtil::sendError('Book not found', 'Book not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateBukuRequest $request, Buku $buku)
    // {
    //     //
    // }

    public function update(Request $request)
    {
        try {
            $buku = Buku::findOrFail($request->isbn);
            $buku->judul = $request->judul;
            $buku->pengarang = $request->pengarang;
            $buku->tahun = $request->tahun;
            $buku->save();
            return $this->index();
        } catch (ModelNotFoundException $ex) {
            return MyUtil::sendError('Book not found', 'Book not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Buku $buku)
    // {
    //     //
    // }
    public function destroy($isbn)
    {
        Buku::where('isbn', $isbn)->delete();
        return $this->index();
    }
}
