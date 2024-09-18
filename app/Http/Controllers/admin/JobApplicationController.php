<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public  function index(){
        $applications = JobApplication::orderBy('created_at', 'DESC')
                         ->with('job','user','employer')
                         ->paginate(10);
        return view('admin.job-applications.list',[
           'applications'  => $applications,
        ]);
    }

    public function destroy(Request $request){
        $id = $request->id;
        $jobApplication = JobApplication::find($id);

        if($jobApplication == null){
            session()->flash('error', 'Aplikimi për punë është fshirë ose nuk u gjet');
            return response()->json([
                'status'=>false,
            ]);
        }
        $jobApplication->delete();
        session()->flash('success', 'Aplikimi për punë u fshi me sukses!');
        return response()->json([
            'status'=>true,
        ]);
    }
}
