<?php

namespace App\Console\Commands;

use App\Imports\TaskWithAssigneeImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportTaskAndAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tasks_and_assignees {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('path');
        // dd($path);
        Excel::import(new TaskWithAssigneeImport, storage_path('app')."/".$path);
        // Excel::toCollection(new TaskWithAssigneeImport, storage_path('app')."/".$path);
    }
}
