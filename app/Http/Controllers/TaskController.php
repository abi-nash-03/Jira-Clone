<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;
use App\TaskUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{

    //To get all tasks assigned to a user
    public function showAllTask(){
        $tasks_ = auth()->user()->tasks;
        $task_arr = [];
        foreach($tasks_ as $task){
            $temp  = [];
            $temp["task"] = $task->attributesToArray();
            $temp["tag"] = null;
            if(isset($task["tag"]->name)){
                $temp["tag"] = $task->tag->name;
            }
            array_push($task_arr, $temp);
        }
        return response()->json($task_arr);
    }

    //To save a new Task
    public function store(Request $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->body = $request->body;
        $task->due_at = $request->due_at;
        $task->status = $request->status;
        $task->save();
        $user = auth()->user();
        $user->tasks()->syncWithoutDetaching($task->id);

        Mail::send("emails.taskCreated", $task->toArray(), 
        function($message) use ($user) {
            $message
            ->to($user->email, $user->name)
            ->subject("Task Successfully Created");
        });
        
        return response()->json([
            "message"=> "Task added successfully"
        ],201);
    }

    //To delete a Task
    public function delete(Request $request, $id){
        $task = Task::find($id);

        //Authorization
        if($task->users->get('0')->id != auth()->user()->id){
            return response()->json([
                'message'=> 'Unauthorized to Delete the task'
            ],401);
        }

        DB::table('task_user')
        ->where('task_id', $id)
        ->where('user_id', auth()->user()->id)
        ->update(array('deleted_at' => DB::raw('NOW()')));
        $user = auth()->user();

        Mail::send("emails.taskDeleted", $task->toArray(), 
        function($message) use ($user) {
            $message
            ->to($user->email, $user->name)
            ->subject("OOPs Task has been Deleted");
        });

        return response()->json([
            "message"=> "Task  deleted successfully"
        ], 200);
    }

    //To upddate the Task Details
    public function update(Request $request, $id){
        $task = Task::find($id);
        $user = auth()->user();

        //Authorization
        if($task->users->get('0')->id != auth()->user()->id){
            return response()->json([
                'message'=> 'Unauthorized to Edit the task'
            ],401);
        }

        if(isset($request->title)){
            $task->title = $request->title;
        }
        if(isset($request->body)){
            $task->body = $request->body;
        }
        if(isset($request->due_at)){
            $task->due_at = $request->due_at;
        }
        if(isset($request->status)){
            $task->status = $request->status;
        }
        if(isset($request->tag_id)){
            $task->tag_id = $request->tag_id;
        }
        $task->save();  
        

        Mail::send("emails.taskUpdated", $task->toArray(), 
        function($message) use ($user) {
            $message
            ->to($user->email, $user->name)
            ->subject("Review your updated task");
        });
        
        
        return response()->json([
            "message"=> "Task Updated successfully"
        ],200);
    }

    //To get task according to the status of the task
    public function getByStatus(Request $request){
        $status = $request->status;
        $task = auth()->user()->tasks()->where("status", $status)->get()->get('0')->attributesToArray();
        return response()->json($task);

    }

    //To assign a task to other user
    public function assign(Request $request){
        $task = Task::find($request->task_id);
        $user = User::find($request->user_id);

        $current_user = auth()->user();
        $users_for_task = $task->users;
        $has_access = false;
        foreach($users_for_task as $user){
            if($user->id == $current_user->id){
                $has_access = true;
                break;
            }
        }

        //Authorization
        if(!$has_access){
            return response()->json([
                'message'=> 'Unauthorized'
                ],401);
        }

        $user->tasks()->syncWithoutDetaching($task->id);

        Mail::send("emails.taskAssigned", $task->toArray(), 
        function($message) use ($user) {
            $message
            ->to($user->email, $user->name)
            ->subject("New Task for you");
        });
        return response()->json([
            'message'=> 'Task Assigned Successfully'
        ],200);
    }

    public function edit(Request $request){
        dd($request);
    }

    public function getUserDetails(Request $request, $id){
        // dd("getUSerDetails in taskcontroller".$id);
        $user  = User::find($id);
        return response()->json($user,200);
    }

    public function getTaskDetails(Request $request, $id){
        // dd("getUSerDetails in taskcontroller".$id);
        // dd(auth()->user()->id);
        // dd(auth('api')->validate());
        $task = Task::find($id);
        return response()->json($task,200);
    }

    public function getTagName(Request $request, $id){
        // dd("tag name");
        $tag = Tag::find($id);
        return response()->json($tag,200);
    }

    public function getAssigneeProfiles(request $request, $id){
        // dd("get profiles");
        $assine_id = TaskUser::getAssignees($id);
        return response()->json($assine_id,200);
    }

    public function getAssigneesEmailOfTask(Request $request, $id){
        $assinees = $this->getAssigneeProfiles($request,$id)->getData();
        $task_with_email = array();
        // dd($assinees);
        foreach($assinees as $assine){
            array_push($task_with_email,User::getUserEmailById($assine->user_id)->attributesToArray());
        }
        // dd($task_with_email);
        return response()->json($task_with_email,200);
    }

    public function getUsers(Request $request){
        // dd(User::all());
        return response()->json(User::all(), 200);
    }


}
