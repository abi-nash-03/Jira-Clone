<?php

namespace App;

use App\Enum\TaskStatusEnum;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class Task extends Model
{

    protected $fillable = ['title', 'body', 'due_at', 'status', 'created_at', 'updated_at', 'tag_id', 'created_by'];

    protected $casts = [
        'status' => TaskStatusEnum::class
    ];

    
    //relating Task to user
    public function users()
    {
        return $this->belongsToMany(User::class,'task_user','task_id','user_id')
        ->withTimestamps()
        ->wherePivot('deleted_at', null);
    }
    //relating Task to Tag
    public function tag()
    {
        return $this->hasOne('App\Tag','id','tag_id');
    }   

    public static function createTask(Request $request)
    {
        // $user = auth()->user();
        $user = auth()->user();
        if($user == null){
            return redirect('auth.login')->with('error', 'Unauthorized');
        }
        $user_id = $user->id;
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
        $task->created_by = $user_id;
        $task->save();
        // Mail::send('emails.taskCreated', $task->toArray(),
        // function($message) use($user){
        //     $message
        //     ->to($user->email, $user->name)
        //     ->subject("A new Task has been created");
        // });
        dispatch( new SendEmail($user, $task->toArray(), "A new Task has been created","emails.taskCreated"));
        return $task->id;
    }

    public static function updateTask(Request $request, $id)
    {
        $task = Task::find($id);
        $task->title = $request->title;
        $task->body = $request->body;
        $task->due_at = $request->due_at;
        $task->status = $request->status;
        $task->tag_id = $request->tag_id;
        $task->save();
        $task_arr = [];
        array_push($task_arr, $task->toArray());
        $task_id_with_assignees = Task::taskIdWithAssignees($task_arr);
        foreach($task_id_with_assignees as $assignees){
            foreach($assignees as $assignee){
                $user = User::find($assignee)->toArray();
                // Mail::send("emails.taskUpdated", $task->toArray(), 
                // function($message) use ($user) {
                //     $message
                //     ->to($user->email, $user->name)
                //     ->subject("Review your updated task");
                // });
                dispatch( new SendEmail($user, $task->toArray(), "Review your updated task", "emails.taskUpdated"));
            }
        }
    }
    
    public static function getAllTasksWithPagination()
    {
        return Task::paginate(5);
    }

    public static function taskIdWithAssignees($tasks)
    {
        $taskId_with_assignees_id = [];
        foreach($tasks as $task){
            $assignees = TaskUser::getAssignees($task['id'])->toArray();
            $taskId_with_assignees_id[$task['id']] = [];
            foreach($assignees as $assignee){
                array_push($taskId_with_assignees_id[$task['id']],$assignee['user_id']);
            }
        }

        return $taskId_with_assignees_id;
    }

    public static function searchWithKey($key)
    {
        if($key==null){
            return null;
        }
        return Task::where('title','like', '%'.$key.'%')->get();
    }

    public function scopeDate($query, $date)
    {
        $query->where('due_at','<=' ,$date);
    }

    public function scopeStatus($query, $status)
    {
        $query->where('status',$status);
    }

    public static function searchWithFilter($request)
    {
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

    public static function getTaskByStatus($user_id, $status)
    {
        $tasks = Task::where('status', $status)
                ->where('user_id', $user_id)
                ->get(0);
        return $tasks;
    }

    public static function getTasksWithIds($task_ids)
    {
        // dd($task_ids);
        $arr_ids = [];
        foreach($task_ids as $task_id){
            array_push($arr_ids, $task_id['task_id']);
        }
        // dd($arr_ids);
        $arr = Task::whereIn('id', $arr_ids)->get();
        $tasks = [];
        foreach($arr as $task){
            $tasks[$task['id']] = $task->toArray();
        }

        return $tasks;
    }

}
