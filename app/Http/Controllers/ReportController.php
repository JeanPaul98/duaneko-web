<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index()
    {
        $reports = Report::all();
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $zones = Zone::all();
        } else {
            $zones = Zone::where('company_id', $user->company_id)->get();
        }

        return view('pages.reports.index', ['reports' => $reports, 'zones' => $zones]);
    }

    public function show(Report $report)
    {
        return view('pages.reports.show', compact('report',));
    }

    public function edit(Report $report)
    {
        return view('pages.reports.edit',compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required',
        ]);
        $report->update($request->all());
        return redirect()->route('reports.show',compact('report'))->with('success','Report modifier avec success');
    }
}
