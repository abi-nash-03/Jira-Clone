<?php

namespace App\Traits;

use App\Task;
use App\TaskUser;

trait TasksWithTaskId{
    public static function getTasksWithTaskId(){
        $all_tasks = Task::all()->toArray();
        $tasks = [];
        foreach($all_tasks as $task){
            $tasks[$task['id']] = $task;
        }
        return $tasks;
    }

    public static function getAllAssignedTasks(){
        $all_jn_entries = TaskUser::all()->toArray();
        // $id_arr = [];
        // foreach($all_jn_entries as $jn_entry){
        //     array_push($id_arr, $jn_entry['task_id']);
        // }
        $taskId_with_assigneeId = Task::getTasksWithIds($all_jn_entries);
        return $taskId_with_assigneeId;

    }
}