<?php

namespace App\Console\Commands;

use App\Imports\TaskImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
class ImportTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:task {path?}';

    /**yaml
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to Import tasks from excel sheet';

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
        // $this->info("The path is ".$path."root path = ".storage_path('app'));
        Excel::import(new TaskImport, storage_path('app')."/".$path);
        // Storage::exists();
    }
}
