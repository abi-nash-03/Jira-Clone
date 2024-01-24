<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{


    
    //relating Task to user
    public function users(){
        return $this->belongsToMany(User::class,'task_user','task_id','user_id')
        ->withTimestamps()
        ->wherePivot('deleted_at', null);
    }
    //relating Task to Tag
    public function tag(){
        return $this->hasOne('App\Tag','id','tag_id');
    }   
    public static function createTask(Request $request){
        $task = new Task();
        $task->title = $request->title;
        if($request->body == null){
            $task->body = "no body";
        }
        else{
            $task->body = $request->body;
        }
        $task->due_at = $request->due_at;
        $task->status = $request->status;
        $task->tag_id = $request->tag_id;
        $task->created_by = auth()->user()->id;
        $task->save();
    
        return $task->id;
    }

    public static function updateTask(Request $request, $id){
        // dd("id = ".$id);
        $task = Task::find($id);
        $task->title = $request->title;
        $task->body = $request->body;
        $task->due_at = $request->due_at;
        $task->status = $request->status;
        $task->tag_id = $request->tag_id;
        $task->save();
        // dd($task);
        // dd($task);
    }
    
    public static function getAllTasks(){
        return Task::paginate(5);
    }

    public static function taskIdWithAssignees(){
        $tasks = Task::all()->toArray();
        // dd($tasks[0]['id']);
        $taskId_with_assignees = [];
        foreach($tasks as $task){
            $assignees = TaskUser::getAssignees($task['id'])->toArray();
            $taskId_with_assignees[$task['id']] = [];
            foreach($assignees as $assignee){
                array_push($taskId_with_assignees[$task['id']],$assignee['user_id']);
            }
        }

        return $taskId_with_assignees;
    }

    public static function searchWithKey($key){
        if($key==null){
            return null;
        }
        return Task::where('title','like', '%'.$key.'%')->get();
    }

    public function scopeDate($query, $date){
        $query->where('due_at','<=' ,$date);
    }

    public function scopeStatus($query, $status){
        $query->where('status',$status);
    }

    public static function searchWithFilter($request){
        $task = Task::select('*')
        ->when($request->has('search_key'), function($q) use($request){
            $q->where('title', 'like', '%'.$request['search_key'].'%');
        })
        ->when($request->has('status'), function($q) use($request){
            $q->where('status', $request['status']);
        })
        ->when($request->has('date'), function($q) use($request){
            $q->where('due_at','=', $request['date']);
        })
        ->get();

        return $task;
    }

    // public function getTaskAssignees($task_id){
    //     return 
    // }
}
