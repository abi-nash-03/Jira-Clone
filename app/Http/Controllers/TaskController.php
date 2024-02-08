<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;
use App\TaskUser;
use App\User;
use Carbon\Carbon;
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
        $task = Task::createTask($request);
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

        
        TaskUser::deleteJunctionEntry(auth()->user()->id, $id);
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

        $task->save();  
        
        Task::updateTask($request,$id);
        return response()->json([
            "message"=> "Task Updated successfully"
        ],200);
    }

    //To get task according to the status of the task
    public function getByStatus(Request $request){
        $status = $request->status;
        $task = Task::getTaskByStatus(auth()->user()->id, $status);
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
        $user  = User::find($id)->toArray();
        $format = 'Y-m-d H:i:s';
        $date = Carbon::createFromFormat($format, $user['created_at'])->format('d M Y');
        $user['created_at'] = $date;

        $updated_at = Carbon::createFromFormat($format, $user['updated_at'])->format('d M Y');
        $user['updated_at'] = $updated_at;
        return response()->json($user,200);
    }

    public function getTaskDetails(Request $request, $id){
        $task = Task::find($id);
        return response()->json($task,200);
    }

    public function getTagName(Request $request, $id){
        $tag = Tag::find($id);
        return response()->json($tag,200);
    }

    public function getAssigneeProfiles(request $request, $id){
        $assine_id = TaskUser::getAssignees($id);
        return response()->json($assine_id,200);
    }

    // public function getAssigneesEmailOfTask(Request $request, $id){
    //     $assinees = $this->getAssigneeProfiles($request,$id)->getData();
    //     $task_with_email = array();
    //     foreach($assinees as $assine){
    //         array_push($task_with_email,User::getUserEmailById($assine->user_id)->attributesToArray());
    //     }
    //     return response()->json($task_with_email,200);
    // }

    // public function getUsers(Request $request){
    //     return response()->json(User::all(), 200);
    // }


}
