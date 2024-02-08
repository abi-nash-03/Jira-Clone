<?php

namespace App\Traits;

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
}