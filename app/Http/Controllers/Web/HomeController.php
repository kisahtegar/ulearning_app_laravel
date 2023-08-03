<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //for security
    public function index(){
        return "";
    }
    //stripe web hook needs this
    public function success()
    {
        return View("success");
    }

    //stripe web hook needs this
    public function cancel()
    {
        return "Yes";
    }
}
