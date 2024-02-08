<?php

namespace App\Imports;

use App\Task;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaskImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
            
        return new Task([
            'title' => $row['title'],
            'body' => $row['body'],
            'due_at' => Carbon::parse($row['due_at'])->format('Y-m-d H:i:s'),
            'status' => $row['status'],
            'tag_id' => $row['tag_id'],
            'created_by' => $row['created_by']
        ]);
    }
}
