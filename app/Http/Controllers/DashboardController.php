<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.home');
    }

    public function reports(): View
    {
        return view('dashboard.reports');
    }

    public function agents(): View
    {
        return view('dashboard.agents');
    }

    public function managers(): View
    {
        return view('dashboard.managers');
    }

    public function companies(): View
    {
        return view('dashboard.companies');
    }

    public function zones(): View
    {
        return view('zones.index');
    }

    public function ramassages(): View
    {
        return view('dashboard.ramassages');
    }
}
