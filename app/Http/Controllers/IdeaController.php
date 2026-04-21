<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IdeaController extends Controller
{
    public function index(){

        $ideas = DB::table('ideas')->get();
        return view('idea.index', ['ideas'=> $ideas]);
    }

    public function create() : View
    {
        return view('idea.create');
    }

    public function store(Request $request)
    {
        $validated=$request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:300'
        ]);

        Idea::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],           
        ]);

        return redirect()->route('idea.index');
    }
}
