<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Volunteer')) {
            return redirect('/evacuation');
        } 
        return redirect('/dashboard');
    }
}
