<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Agent;
use App\Models\Report;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    
    public function index()
    {

        $reports = Report::all(); 
        if(Auth::guard('admin')->check()){
            $zones=Zone::all();
            return view('pages.reports.index',['reports'=>$reports,'zones'=>$zones]); 
        }elseif(Auth::guard('manager')->check()){
            $id_manager = Auth::guard('manager')->id();
            $id_company = Manager::find($id_manager);
            $zones = Zone::where('company_id',$id_company->company_id)->get();
            return view('pages.reports.index',['reports'=>$reports,'zones'=>$zones]);
        }else{
            $id_agent = Auth::guard('agent')->id();
            $id_company = Agent::find($id_agent);
            $zones = Zone::where('company_id',$id_company->company_id)->get();
            return view('pages.reports.index',['reports'=>$reports,'zones'=>$zones]);
        }
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
