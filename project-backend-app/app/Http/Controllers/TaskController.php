<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getTasksByProject($projectId)
    {

        return new JsonResponse(['tasks' => Task::where('project_id', $projectId)->get()]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return new JsonResponse(['tasks' => Task::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $project = Project::find($request->project_id);

        if ($project instanceof Project) {
            $project->tasks()->create([
                'title' => $request->title,
                'description' => $request->description,
                'status' => Task::TO_DO
            ]);
        }

        return new JsonResponse(['tasks' => $project->tasks()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return new JsonResponse(['tasks' => Task::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $task = Task::find($id);

        if ($task instanceof Task) {
            $task->title =  $request->title;
            $task->description = $request->description;
            $task->status = $request->status;
            $task->save();
        }

        return new JsonResponse(['tasks' => $task]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if ($task instanceof Task) {
            $task->delete();

            return new JsonResponse(['tasks' => $task->project()->first()->tasks()->get()]);
        }

        throw new \Exception('Something went wrong');
    }
}
