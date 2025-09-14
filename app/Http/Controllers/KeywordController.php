<?php
namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index()
    {
        return response()->json(Keyword::all());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:keywords,name']);
        $keyword = Keyword::create($request->only('name'));
        return response()->json($keyword, 201);
    }
}
