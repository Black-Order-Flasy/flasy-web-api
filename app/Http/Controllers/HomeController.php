<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (auth()->user()->hasRole('Volunteer')) {
                return redirect('/evacuation');
            }
        } else {
            // Handle the case when the user is not authenticated
            return redirect('/dashboard');
        }
    }
}
