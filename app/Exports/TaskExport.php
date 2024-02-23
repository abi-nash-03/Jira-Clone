<?php

namespace App\Exports;

use App\Task;
use App\TaskUser;
use App\Traits\Helpers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TaskExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $user_id = auth()->user()->id;
        $tasks = Helpers::getTasksByUserID($user_id);
        $assignees = TaskUser::getTaskIdWithAssigneesEmail($user_id);
        $result_arr = [];
        foreach($tasks as $task){
            $task['assignees'] = $assignees[$task['id']];
            array_push($result_arr, $task);
        }
        return new Collection([
            $result_arr
        ]);
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'due_at',
            'status',
            'created_at',
            'updated_at',
            'tag_id',
            'created by',
            'assignees',
        ];
    }
}
