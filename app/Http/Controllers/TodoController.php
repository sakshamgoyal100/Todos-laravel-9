<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        return view('todos.index');
    }

    public function getTodos($completed='')
    {
        $todos = ($completed != '') ? Todo::where('completed',$completed)->get() : Todo::all();
        return response()->json($todos);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:todos'
        ]);

        $todo = Todo::create([
            'title' => $request->title
        ]);

        return response()->json($todo);
    }

    public function update(Todo $todo)
    {
        $todo->update(["completed" => 1]);
        $todo->save();

        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(['message' => 'Todo deleted successfully']);
    }
}
