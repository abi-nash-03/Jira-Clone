<?php

namespace App\Http\Controllers;

use App\Enum\TaskStatusEnum;
use App\Tag;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class ReportController extends BaseController
{

    public function reportPage(){
        // dd("report request");
        $all_tasks = Task::all()->toArray();
        $tags = Tag::all()->toArray();
        $tag_with_tagId = [];
        $tasks = [
            TaskStatusEnum::TODO => [],
            TaskStatusEnum::INPROGRESS => [],
            TaskStatusEnum::COMPLETED => []
        ];

        foreach($tags as $tag){
            $tag_with_tagId[$tag['id']] = $tag['name'];
        }
        foreach($all_tasks as $task){
            array_push($tasks[$task['status']], $task);
        }
        // dd($tasks);
        return view('report.reportHome') ->with([
            'tasks' => $tasks,
            'tag_with_id' => $tag_with_tagId,
        ]);
    }

    public function downloadReport(Request $request){
        $all_tasks = Task::all()->toArray();
        $tags = Tag::all()->toArray();
        $download_timestamp = Carbon::now();
        $from = $request->from;
        $to = $request->to;
        $tag_with_tagId = [];
        $filtered_tasks = [
            TaskStatusEnum::TODO => [],
            TaskStatusEnum::INPROGRESS => [],
            TaskStatusEnum::COMPLETED => []
        ];

        foreach($tags as $tag){
            $tag_with_tagId[$tag['id']] = $tag['name'];
        }
        foreach($all_tasks as $task){
            if($task['created_at']>=$from && $task['created_at']<=$to){
                array_push($filtered_tasks[$task['status']], $task);
            }
        }
        // dd($filtered_tasks); 
        $data = [
            'tasks' => $filtered_tasks,
            'tag_with_id' => $tag_with_tagId,
            'date' => $download_timestamp,
        ];
        $pdf = PDF::loadView('report.report', $data);
        return $pdf->download('RoomsPDF.pdf');
    }
}
