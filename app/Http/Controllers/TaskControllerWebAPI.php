<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskControllerWebAPI extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    
}
