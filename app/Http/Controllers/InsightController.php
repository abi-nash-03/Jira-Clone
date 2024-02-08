<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatusEnum;
use App\Task;
use App\TaskUser;
use App\Traits\TasksWithTaskId;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function demo()
    {
        return view('charts.userInsights');
    }

    public function avgDailyTaskSpecificMonth(Request $request)
    {
        // dd($request ->toArray());

    }

    public function getAvgDailyTasks()
    {
        $user = auth()->user();
        // dd($user);
        // if(!$user)
        // {
        //     // return 
        // }
        $user_id = $user->id;
        $task_ids = TaskUser::getTasks($user_id)->toArray();
        $tasks = [];
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $days = Carbon::now()->daysInMonth;
        $todo = 0;
        $inprogress = 0;
        $completed = 0;
        $tasks = TasksWithTaskId::getTasksWithTaskId();
        if(empty($task_ids))
        foreach($task_ids as $task_id){
            if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::TODO && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $todo++;
            }
            else if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::INPROGRESS && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $inprogress++;
            }
            else if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::COMPLETED && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $completed++;
            }
        }
        $todo = (bcdiv($todo, $days, 1));
        $inprogress = (bcdiv($inprogress, $days, 1));
        $completed = (bcdiv($completed, $days, 1));
        return response() -> json([
            'todo' => $todo,
            "inprogress" => $inprogress,
            'completed' => $completed,
            'days' => $days,
        ], 200);
    }

    public function getAvgMonthlyTasks(){
        $tasks_id = TaskUser::getTasks(auth()->user()->id)->toArray();
        $tasks = [];
        $start = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear();
        $todo = 0;
        $inprogress = 0;
        $completed = 0;
        $tasks = TasksWithTaskId::getTasksWithTaskId();
        foreach($tasks_id as $task_id){
            if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::TODO && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $todo++;
            }
            else if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::INPROGRESS && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $inprogress++;
            }
            else if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::COMPLETED && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $completed++;
            }
        }
        $todo = (bcdiv($todo, 12, 1));
        $inprogress = (bcdiv($inprogress, 12, 1));
        $completed = (bcdiv($completed, 12, 1));
        return response() -> json([
            'todo' => $todo,
            "inprogress" => $inprogress,
            'completed' => $completed,
            'months' => 12,
        ], 200);
    }

    function getAvgYearlyTasks(){
        $tasks_id = TaskUser::getTasks(auth()->user()->id)->toArray();
        $tasks = [];
        $start = Carbon::now()->subYears(10);
        $end = Carbon::now()->year;
        $todo = 0;
        $inprogress = 0;
        $completed = 0;
        $tasks = TasksWithTaskId::getTasksWithTaskId();
        foreach($tasks_id as $task_id){
            if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::TODO && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $todo++;
            }
            else if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::INPROGRESS && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $inprogress++;
            }
            else if($tasks[$task_id['task_id']]['status'] == TaskStatusEnum::COMPLETED && $tasks[$task_id['task_id']]['due_at']>=$start && $tasks[$task_id['task_id']]['due_at'] <= $end){
                $completed++;
            }
        }
        $todo = (bcdiv($todo, 10, 1));
        $inprogress = (bcdiv($inprogress, 10, 1));
        $completed = (bcdiv($completed, 10, 1));
        return response() -> json([
            'todo' => $todo,
            "inprogress" => $inprogress,
            'completed' => $completed,
            'months' => 12,
        ], 200);
    }

}
