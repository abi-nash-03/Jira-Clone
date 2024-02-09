<?php

namespace App\Traits;

use App\Task;
use App\TaskUser;
use Illuminate\Http\Request;

trait Helpers{
    public static function getAssigneeProfiles($type, $id){
        switch($type){
            case 'JSON':
                $assine_id = TaskUser::getAssignees($id);
                return response()->json($assine_id,200);
                break;
            case 'RAW':
                $assine_id = TaskUser::getAssignees($id);
                return $assine_id->toArray();
                break;
        }
    }

    public static function getUserIdWithName($users)
    {
        $arr = [];
        foreach ($users as $user) {
            $arr[$user->id] = $user->name;
        }

        return $arr;
    }

    public static function getUserIdWithProfile($users)
    {
        $arr = [];
        foreach ($users as $user){
            $arr[$user['id']] = $user['profile'];
        }
        return $arr;
    }

    public static function getUserIdWithEmail($users){
        $arr = [];
        foreach ($users as $user){
            $arr[$user['id']] = $user['email'];
        }
        return $arr;

    }

    //get Tasks based on user
    public static function getTasksByUserID($user_id)
    {
        $task_ids = TaskUser::getTasksByuserId($user_id);
        $tasks = Task::getTasksWithIds($task_ids);
        return $tasks;
    }
}