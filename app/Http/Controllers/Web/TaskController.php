<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Category;

class TaskController extends Controller
{

    public function index()
    {
        return view('tasks.index');
    }


    public function create()
    {
        return view('tasks.create');
    }


    public function edit(Task $task)
    {
        $categories = Category::all();

        return view('tasks.edit', compact(
            'task',
            'categories'
        ));
    }


    public function publicTasks()
    {
        $tasks = Task::where('is_public', true)
            ->with([
                'user',
                'category'
            ])
            ->latest()
            ->get();


        return view(
            'tasks.public',
            compact('tasks')
        );
    }


    public function editPublic(Task $task)
    {
        $categories = Category::all();


        return view(
            'tasks.public-edit',
            compact(
                'task',
                'categories'
            )
        );
    }

}
