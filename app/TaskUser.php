<?php

namespace App;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class   TaskUser extends Pivot
{
    use SoftDeletes;


    public static function createJunctionEntry($user_id, $task_id){
        $isExist = TaskUser::where('user_id', $user_id)
        ->where('task_id', $task_id)
        ->first();
        if($isExist == null){
            $task_user = new TaskUser();
            $task_user->user_id = $user_id;
            $task_user->task_id = $task_id;
            $task_user->created_at = now();
            $task_user->updated_at = now();
            $task_user->save();
            return true;
        }

        return false;
        // dd($task_user);
    }   

    public static function deleteJunctionEntry($user_id, $task_id){
        // dd("".$user_id." ".$task_id);
        $task_user = TaskUser::where("user_id", $user_id)
        ->where("task_id", $task_id)
        ->update(array('deleted_at' => DB::raw('NOW()')));
        // dd($task_user); 
        // ->update(array('deleted_at' => DB::raw('NOW()')));
    }

    public static function getUser( $user_id, $task_id ){
        return self::where("user_id", $user_id)
        ->where("task_id", $task_id)
        ->first();
    }

    public static function getTaskIdFromJn(){
        $entities =  self::all();
        $task_ids = [];
        foreach($entities as $entity){
            $task_ids[$entity['task_id']] = $entity['task_id'];
        }
        return $task_ids;
    }

    //get the users assigned to a particular task
    public static function getAssignees($task_id ){
        return self::where("task_id", $task_id)->get();
    }
    

    //to get the tasks of a particular user
    public static function getTasks($user_id){
        return self::where('user_id', $user_id)->get();
    }

    
}
