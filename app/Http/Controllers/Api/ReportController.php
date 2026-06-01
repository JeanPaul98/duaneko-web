<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReportStoreRequest;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;



class ReportController extends Controller
{
// TODO: Ici c'est l'api pour l'utilisateur qui est connecter.
public function reportStore(ReportStoreRequest $request)
{
    $file = $request->file('image');
    $imageName =  time().".".$file->getClientOriginalExtension(); 
   
    $report=Report::create(
        array_merge(
            $request->validated() + ['user_id'=>auth('api')->id()],
            [
                'image' => $imageName,
                ]
            ),
                
                
    );
      // Save Image in Storage folder
      Storage::disk('public')->put($imageName, file_get_contents($request->image));

   
    return response()->json(
        [
            'message' => "signalement inserer avec succes",
            'report'=>$report
        ],
        201
    );

}

// TODO: Ici c'est l'api pour l'utilisateur qui n'est pas connecter.
public function reportPublicStore(ReportStoreRequest $request)
{
    $file = $request->file('image');
    $imageName =  time().".".$file->getClientOriginalExtension(); 

     
    $report=Report::create(
        array_merge(
        $request->validated() ,
           [
                'image' =>   $imageName,
           ]
           )
    );
        // Save Image in Storage folder
        Storage::disk('public')->put($imageName, file_get_contents($request->image));
    return response()->json(
        [
            'message' => "signalement inserer avec succes",
            'report'=>$report
        ],
        201
    );

}

public function showReports()
{
    $reports=Report::all();
    return response()->json(
        [
            'reports'=>$reports
        ],200
    );

}
public function getImage($filename)
{
 $path = storage_path('app/public/' . $filename);
 
        if (file_exists($path)) {
            return response()->file($path);
        }
    
        abort(404);  
}

public function showUsers()
{
    $users=User::all();
    return response()->json(
        [
            'users'=> $users
        ],200
    );

}

}
