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
        return view('others.home');
    }

    public function reports(): View
    {
        return view('others.reports');
    }

    public function agents(): View
    {
        return view('others.agents');
    }

    public function managers(): View
    {
        return view('others.managers');
    }

    public function companies(): View
    {
        return view('others.companies');
    }

    public function zones(): View
    {
        return view('zones.index');
    }

    public function ramassages(): View
    {
        return view('others.ramassages');
    }
}
