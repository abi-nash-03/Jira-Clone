<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatusEnum;
use App\Exports\TaskExport;
use App\Tag;
use App\Task;
use App\TaskUser;
use App\Traits\Helpers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\Traits\TasksWithTaskId;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class TaskControllerWeb extends BaseController
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    public static $users_id = [];

    
    //Method for home page board
    public function getTaskDashboards(Request $request)
    {
        //getting task id's of users from jn table

        //checking whether auth user is not null
        $user = auth()->user();
        if($user == null){
            return redirect('auth.login')->with('error', 'Unauthorized');
        }
        $user_id = $user->id;
        $tasks = Helpers::getTasksByUserID($user_id);
        $all_tasks = Task::getAllTasksWithPagination();
        $tags = Tag::orderBy("updated_at")->get();
        $task_id_with_assignees = Task::taskIdWithAssignees(Task::all()->toArray());
        $users = User::orderBy("updated_at")->get();
        $todo = 0;
        $inprogress = 0;
        $completed = 0;
        foreach($tasks as $task){
            if($task['status'] == TaskStatusEnum::TODO) $todo++;
            else if($task['status'] == TaskStatusEnum::INPROGRESS) $inprogress++;
            else if($task['status'] == TaskStatusEnum::COMPLETED) $completed++;
        }
        $user_id_with_profile = Helpers::getUserIdWithProfile($users);
        $user_id_with_names = Helpers::getUserIdWithName($users);
        // $demo = TaskUser::getTaskUserWithTaskID();
        return view('home', [
            "todo"=>$todo,
            "inprogress" => $inprogress,
            "completed" => $completed,
            "tasks" => $tasks,
            "tags" => $tags,
            "all_tasks" => $all_tasks,
            "task_id_with_assignees" => $task_id_with_assignees,
            'user_with_profile' => $user_id_with_profile,
            'user_id_with_name' => $user_id_with_names,
        ]);
    }
    
    //Method for board page 
    public function getBoard()
    {
        // dd("Hii");
        $tags = Tag::orderBy("updated_at")->get();
        $users = User::all()->toArray();
        $tasks = TasksWithTaskId::getAllAssignedTasks();
        // dd($tasks); 
        $users = User::orderBy("updated_at")->get();
        //map taskId with its assignees id
        $assignee_ids = [];
        foreach($tasks as $task){
            $assignee_ids[$task['id']] = Helpers::getAssigneeProfiles('RAW',$task['id']);
        }
        $user_id_with_profile = Helpers::getUserIdWithProfile($users);
        $user_id_with_names = Helpers::getUserIdWithName($users);

        // dd($tasks);
        return view("tasks.board",[
            "tasks"=>$tasks,
            "tags"=> $tags,
            "assignees"=> $assignee_ids,
            'user_with_profile' => $user_id_with_profile,
            'user_id_with_name' => $user_id_with_names,
            'users' => $users,
        ]);

    }
    
    
    public function getTaskForHomepage()
    {
        $user = auth()->user();
        if($user == null){
            return redirect('auth.login')->with('error', 'Unauthorized');
        }
        $user_id = $user->id;

        $task_user_id = TaskUser::orderBy('updated_at','desc')
        ->where("user_id",$user_id)
        ->paginate(5);
        return $task_user_id;
    }

    //get Tasks based on user
    // public function getTasks($user_id)
    // {
    //     $task_ids = TaskUser::getTasksByuserId($user_id);
    //     $tasks = Task::getTasksWithIds($task_ids);
    //     return $tasks;
    // }

    public function createTask()
    {
        $tags = Tag::orderBy("updated_at")->get();        
        return view("tasks.create")->with("tags", $tags);
    }

    public function storeTask(Request $request)
    {
        $this->validate($request, [
            "title"=> "required",
            "body" => "nullable",
            "due_at"=> "date|required",
            "status"=> "required",
            "tag_id"=> "required"
        ]);

        // validating whether the due date is valid or not
        if($request->due_at < Carbon::now()){
            return redirect('/task/create')->with('error', "Invalid Due date");
        }
        $task_id = Task::createTask($request);
        return redirect("/home")->with("success","Your Task has been created");
    }

    public function updateTask(Request $request, $task_id)
    {
        $this->validate($request, [
            "title"=> "required",
            "body" => "nullable",
            "due_at"=> "date|required",
            "status"=> "required",
            "tag_id"=> "required"
            ]);
        $task = Task::findOrFail($task_id);
        if($task == null){
            return redirect("/board")->with("error","Invalid request");
        }

        //Authorizing the user
        $users = TaskUser::getUser(auth()->user()->id, $task_id);
        if($users == null && auth()->user()->id != $task->created_by){
            return redirect('/board')->with('error','Your\'e Unauthorized');
        }


        Task::updateTask($request,$task_id);

        //redirecting back to the same page from where the request originated
        return back()->with("success","Task Updated Successfully");
    }

    public function destroyTask($id)
    {
        $user = auth()->user();
        if($user == null){
            return redirect('auth.login')->with('error', 'Unauthorized');
        }
        $user_id = $user->id;
        $task = Task::find($id);
        if($task == null){
            return redirect("/home")->with("error","Invalid request");
        }
        $users = TaskUser::getUser($user_id, $id);
        if($users == null){
            return redirect('/board')->with('error','Your\'e Unauthorized to do this action');
        }
        
        TaskUser::deleteJunctionEntry($user_id, $id);
        
        return redirect('/board')->with('success','Task Deleted Successfully');
    }

    public function share(Request $request)
    {
        $email = $request->email;
        $task_id = $request->task_id;
        $user = User::getUserByEmail($email);
        if($user == null){
            return redirect('/board')->with('error','Invalid email');
        }
        $status = TaskUser::createJunctionEntry($user->id, $task_id);
        if($status){
            return redirect("/board")->with("success","Task has been shared successfully");
        }
        return redirect('/board')->with("error","Task Previously assigned");
    }


    public function search(Request $request)
    {
        $tasks = Task::searchWithFilter($request)->toArray();

        return view('tasks.search',[
            'tasks' => $tasks,
            'search_key' => $request->search_key,
        ]);
    }



    //for API

    //Get the users by user filter
    public function getTasksOfUsers(Request $request)
    {
        $users_id = $request->users_id;
        $jn_entries = TaskUser::all()->toArray();
        $tasks = [];
        $all_tasks = TasksWithTaskId::getTasksWithTaskId();
        $all_users = User::all()->toArray();

        //user object mapped with user id
        $user_with_id = [];
        foreach($all_users as $user){
            $user_with_id[$user['id']] = $user;
        }
        $users = [];
        $tags = Tag::orderBy("updated_at")->get();
        $user_id_with_profile = [];
        $user_id_with_name = [];

        //Getting tasks of filtered users 
        foreach($users_id as $user_id){
            foreach($jn_entries as $jn_entrie){
                if($jn_entrie['user_id'] == $user_id){
                    $tasks[$jn_entrie['task_id']] = $all_tasks[$jn_entrie['task_id']];
                }
            }
            $users[$user_id] = $user_with_id[$user_id];
        }

        //assigning userid with profile and name
        foreach($all_users as $user){
            $user_id_with_profile[$user['id']] = $user['profile'];
            $user_id_with_name[$user['id']] = $user['name'];
        }

        $assignee_ids = [];
        foreach($tasks as $task){
            $assignee_ids[$task['id']] = Helpers::getAssigneeProfiles('RAW',$task['id']);
        }
        $response = [
            'users' => $users,
            'tags' => $tags,
            'tasks' => $tasks,
            'user_id_with_profile' => $user_id_with_profile,
            'user_id_with_name' => $user_id_with_name,
            'assignees' => $assignee_ids
        ];
        return response()->json($response,200);
    }

    public function getTagName(Request $request, $id)
    {
        $tag = Tag::find($id);
        return response()->json($tag,200);
    }

    public function getAssigneesEmailOfTask(Request $request, $id)
    {
        $assines = Helpers::getAssigneeProfiles('JSON',$id)->getData();
        // dd($assines);F
        $task_with_email = array();
        foreach($assines as $assine){
            array_push($task_with_email,User::getUserEmailById($assine->user_id)->attributesToArray());
        }
        return response()->json($task_with_email,200);
    }

    public function getUsers(Request $request)
    {
        return response()->json(User::all(), 200);
    }

    public function exportTasks(){
        return FacadesExcel::download(new TaskExport, 'tasks.xlsx');
    }

}
