<?php
namespace App\Imports;

use App\Task;
use App\TaskUser;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaskWithAssigneeImport implements ToCollection, WithHeadingRow{
    public function collection(Collection $rows){
        // print("hii");
        // dd($rows);
        foreach($rows as $row){
            // dd($row);
            $emails = explode(',',$row['assignees']);
            // dd(explode(',',$row['assignees']));
            $task = Task::create([
                'title' => $row['title'],
                'body' => $row['body'],
                'due_at' => Carbon::parse($row['due_at'])->format('Y-m-d H:i:s'),
                'status' => $row['status'],
                'tag_id' => $row['tag_id'],
                'created_by' => $row['created_by'] 
            ]);
            $task->save();
            foreach($emails as $email){
                $user = User::getUserByEmail($email);
                $task_user = TaskUser::create([
                    'task_id' => $task->id,
                    'user_id' => $user->id
                ]);
                $task_user->save();
            }
        }
        print_r("Task and Assinees added successfullt\n");
    }
}