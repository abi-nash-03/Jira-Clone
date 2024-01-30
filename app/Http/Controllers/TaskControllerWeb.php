<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;
use App\TaskUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class TaskControllerWeb extends Controller
{
    public function __construct(){
        $this->middleware("auth");
    }
    
    
    public function getTaskDashboards(Request $request){
        $tasks = $this->getTasks(auth()->user()->id); //task_id ot task
        $all_tasks = Task::getAllTasks();
        $tags = Tag::orderBy("updated_at")->get();
        $task_id_with_assignees = Task::taskIdWithAssignees();
        $users = User::orderBy("updated_at")->get();
        // dd($users);
        // dd($task_id_with_assignees);         
        $todo = 0;
        $inprogress = 0;
        $completed = 0;
        foreach($tasks as $task){
            if($task['status'] == 'todo') $todo++;
            else if($task['status'] == 'completed') $completed++;
            else if($task['status'] == 'inprogress') $inprogress++;
        }
        $user_id_with_profile = [];
        foreach($users as $user){
            $user_id_with_profile[$user['id']] = $user['profile'];
        }
        // dd($task_id_with_assignees);
        $user_id_with_names = $this->getUserId_with_name();
        
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
    
    public function getBoard(){
        $tags = Tag::orderBy("updated_at")->get();
        $task_ids = TaskUser::getTaskIdFromJn();
        $users = User::all()->toArray();
        $tasks = [];
        foreach($task_ids as $task_id){
            $tasks[$task_id] = Task::find($task_id)->toArray();
        }
        // dd($tasks);
        $users = User::orderBy("updated_at")->get();
        $assignee_ids = [];
        foreach($tasks as $task){
            $assignee_ids[$task['id']] = $this->getAssigneeProfiles($task['id']);
        }
        // dd($assignee_ids);
        $user_id_with_profile = [];
        foreach($users as $user){
            $user_id_with_profile[$user['id']] = $user['profile'];
        }
        // dd($user_id_with_profile);
        $user_id_with_names = $this->getUserId_with_name();


        return view("tasks.board",[
            "tasks"=>$tasks,
            "tags"=> $tags,
            "assignees"=> $assignee_ids,
            'user_with_profile' => $user_id_with_profile,
            'user_id_with_name' => $user_id_with_names,
            'users' => $users,
        ]);

    }
    
    
    public function getTaskForHomepage(){
        $user_id = auth()->user()->id;
        $task_user_id = TaskUser::orderBy('updated_at','desc')
        ->where("user_id",$user_id)
        ->paginate(5);
        // ->get();
        return $task_user_id;
    }

    //get Tasks based on user
    public function getTasks($user_id){
        // $user = auth()->user();
        $tasks_id = TaskUser::orderBy('updated_at','desc')
        ->where("user_id",$user_id)
        ->get();
        $tasks = [];
        foreach($tasks_id as $task_id){
            $tasks[$task_id['task_id']] = Task::find($task_id->task_id)->attributesToArray();
        }
        // dd($tasks);
        return $tasks;
    }

    public function createTask(){
        $tags = Tag::orderBy("updated_at")->get();
        return view("tasks.create")->with("tags", $tags);
    }

    public function storeTask(Request $request){
        $this->validate($request, [
            "title"=> "required",
            "body" => "nullable",
            "due_at"=> "date|required",
            "status"=> "required",
            "tag_id"=> "required"
            ]);
        // dd($request->due_at < Carbon::now());
        if($request->due_at < Carbon::now()){
            return redirect('/task/create')->with('error', "Invalid Due date");
        }
        $task_id = Task::createTask($request);
        // TaskUser::createJunctionEntry(auth()->user()->id, $task_id);
        return redirect("/home")->with("success","Your Task has been created");
    }

    public function editTask($id){
        dd("edit id task controller web". $id);
        $task = Task::findOrFail($id);
        if($task == null){
            return redirect("/board")->with("error","Invalid request");
        }

        //Authorizing the user
        $users = TaskUser::getUser(auth()->user()->id, $id);
        // dd($users);
        if($users->toArray() == null){
            return redirect('/board')->with('error','Your\'e Unauthorized');
        }

        $tags = Tag::orderBy("updated_at")->get();
        $curr_tag = Tag::find($task->tag_id);
        return view("modal.editTask")->with(["task" => $task,"tags" => $tags, "tag_name" => $curr_tag->name]);
    }

    public function updateTask(Request $request, $task_id){
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

        // dd($request);

        Task::updateTask($request,$task_id);

        return redirect("/board")->with("success","Task Updated Successfully");
    }

    public function destroyTask($id){
        // dd($id);
        $task = Task::find($id);
        if($task == null){
            return redirect("")->with("error","Invalid request");
        }
        // dd($id);
        $users = TaskUser::getUser(auth()->user()->id, $id);
        // dd($users);
        if($users == null){
            return redirect('/board')->with('error','Your\'e Unauthorized to do this action');
        }
        
        TaskUser::deleteJunctionEntry(auth()->user()->id, $id);
        
        return redirect('/board')->with('success','Task Deleted Successfully');
    }

    public function share(Request $request){
        $email = $request->email;
        $task_id = $request->task_id;
        $user = User::getUserByEmail($email);
        // dd($user->id);
        if($user == null){
            return redirect('/board')->with('error','Invalid email');
        }
        $status = TaskUser::createJunctionEntry($user->id, $task_id);
        if($status){
            return redirect("/board")->with("success","Task has been shared successfully");
        }
        return redirect('/board')->with("error","Task Previously assigned");
    }

    public function getAssigneeProfiles($task_id){   
        $assine_id = TaskUser::getAssignees($task_id);
        // dd($assine_id->toArray());
        return $assine_id->toArray();
    }

    public function search(Request $request){
        // dd($request);
        // $tasks = Task::searchWithKey($request->search_key);
        // dd($tasks);
        $task = Task::searchWithFilter($request)->toArray();

        // dd($task->toArray() == null);
        return view('tasks.search',[
            'tasks' => $task,
            'search_key' => $request->search_key,
        ]);
    }

    public function getUserId_with_name(){
        $arr = [];
        $users = User::all();
        foreach ($users as $user) {
            $arr[$user->id] = $user->name;
        }
        // dd($arr);

        return $arr;
    }

    public function userBoard(Request $request){
        $user_ids = $request->toArray();
        $users = User::all()->toArray();
        $tags = Tag::orderBy("updated_at")->get();
        $tasks = [];
        foreach($users as $user){
            if(isset($user_ids[$user['id']])){
                $tasks_per_user = $this->getTasks($user['id']);
                // dd($tasks_per_user);
                foreach($tasks_per_user as $task_per_user){
                    $tasks[$task_per_user['id']] = $task_per_user;
                }
            }
        }
        // dd($tasks);
        $users = User::orderBy("updated_at")->get();
        $assignee_ids = [];
        foreach($tasks as $task){
            $assignee_ids[$task['id']] = $this->getAssigneeProfiles($task['id']);
        }
        // dd($tasks);
        $user_id_with_profile = [];
        foreach($users as $user){
            $user_id_with_profile[$user['id']] = $user['profile'];
        }
        // dd($user_id_with_profile);
        $user_id_with_names = $this->getUserId_with_name();
        return view("tasks.board",[
            "tasks"=>$tasks,
            "tags"=> $tags,
            "assignees"=> $assignee_ids,
            'user_with_profile' => $user_id_with_profile,
            'user_id_with_name' => $user_id_with_names,
            'users' => $users,
        ]);

    }
    public function demo(){
        return view('tasks.demo');
    }

    //for API
    public function getTasksOfUsers(Request $request){
        $users_id = $request->users_id;
        $tasks = [];
        $users = [];
        $tags = Tag::orderBy("updated_at")->get();
        $user_id_with_profile = [];
        $user_id_with_name = [];
        $all_users = User::all()->toArray();
        foreach($users_id as $user_id){
            $tasks_id = TaskUser::getTasks($user_id)->toArray();
            foreach($tasks_id as $task_id){
                $tasks[$task_id['task_id']] = Task::find($task_id['task_id'])->toArray();
            }
            $users[$user_id] = User::find($user_id);
        }
        foreach($all_users as $user){
            $user_id_with_profile[$user['id']] = $user['profile'];
            $user_id_with_name[$user['id']] = $user['name'];
        }
        $assignee_ids = [];
        foreach($tasks as $task){
            $assignee_ids[$task['id']] = $this->getAssigneeProfiles($task['id']);
        }
        // dd($user_id_with_name);
        $response = [
            'users' => $users,
            'tags' => $tags,
            'tasks' => $tasks,
            'user_id_with_profile' => $user_id_with_profile,
            'user_id_with_name' => $user_id_with_name,
            'assignees' => $assignee_ids
        ];
        // dd($response);
        return response()->json($response,200);
    }
}
