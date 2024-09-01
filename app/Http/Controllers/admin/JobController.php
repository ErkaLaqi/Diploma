<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::orderBy('created_at','DESC')->with('user','applications')->paginate(10);
        return view('admin.jobs.list',[
            'jobs' => $jobs,
        ]);
    }

    public function edit($id){
        $job = Job::findOrFail($id);
        $categories = Category::orderBy('name','DESC')->get();
        $jobType = JobType::orderBy('name','DESC')->get();

        return view('admin.jobs.edit',[
            'job' => $job,
            'categories'=> $categories,
            'jobTypes' => $jobType,
        ]);
    }
    public function update (Request $request, $id){

        $rules= [
            'title' => 'required|min:4|max:200',
            'category' => 'required|not_in:0',
            'jobType' => 'required|not_in:0',
            'vacancy' => 'required|integer|min:1',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:1|max:100'
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->passes()){

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->keywords = $request->keywords;
            $job->qualifications = $request->qualifications;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;
            $job->status = $request->status;
            $job->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;

            $job->save();

            session()->flash('success', 'Job details updated successfully!');
            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    public function destroy(Request $request){
        $id = $request->id;
        $job = Job::find($id);

        if($job == null){
            session()->flash('error', 'Either job deleted or not found!');
            return response()->json([
                'status'=>false,
            ]);
        }
        $job->delete();
        session()->flash('success', 'Job deleted successfully!');
        return response()->json([
            'status'=>true,
        ]);

    }

}
