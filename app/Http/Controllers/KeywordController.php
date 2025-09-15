<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Exception;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    // Get all keywords
    public function index()
    {
        try {
            return response()->json(Keyword::all());
        } catch (Exception $error) {
            return response()->json([
                'error' => 'Failed to fetch keywords',
                'details' => $error->getMessage()
            ], 500);
        }
    }

    // Store one keyword
    public function store(Request $request)
    {
        try{
            $request->validate(['name' => 'required|string|unique:keywords,name']);
            $keyword = Keyword::create($request->only('name'));
            return response()->json($keyword, 201);
        }catch(Exception $error){
            return response()->json([
                'error' => 'Failed to create keyword',
                'details' => $error->getMessage()
            ], 500);
        }
    }
}
