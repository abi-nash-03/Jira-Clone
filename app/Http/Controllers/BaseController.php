<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct(){
        $user = auth()->user();
        View::share('user',$user);
    }
}
