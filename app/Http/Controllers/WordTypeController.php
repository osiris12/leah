<?php

namespace App\Http\Controllers;

use App\Models\WordType;
use Illuminate\Http\Request;

class WordTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $wordType)
    {
        $wordType = new WordType();
        $wordType->pos = $wordType['pos'];
        $wordType->pos_full = $wordType['pos_full'];
        $wordType->save();
        return response([
            'New word type '.$wordType['pos_full'].' created successfully',
            201
        ]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
