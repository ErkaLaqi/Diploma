<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    //This method will show jobs page
    public function index(Request $request){
        $categories = Category::where('status', 1)->get();
        $jobTypes = JobType::where('status', 1)->get();

        $jobs = Job::where('status', 1);

        //Search job using keywords
        if (!empty($request->keyword))
        {
            /*$jobs = $jobs->orWhere('title','like','%'.$request->keyword.'%');
            $jobs = $jobs->orWhere('keywords','like','%'.$request->keyword.'%');*/

            //Group queries instead of writing them like above
            $jobs = $jobs->where(function ($query) use ($request){
                $query->orWhere('title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');

            });
        }

        //Search job using location
        if(!empty($request->location))
        {
            $jobs = $jobs->where('location',$request->location);
        }

        //Search job using category
        if(!empty($request->category))
        {
            $jobs = $jobs->where('category_id',$request->category);
        }

        $jobTypeArray = [];
        //Search job using job type
        if(!empty($request->jobType))
        {
            $jobTypeArray = explode(',',$request->jobType);
            $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
        }
        if(!empty($request->experience))
        {
            $jobs = $jobs->where('experience',$request->experience);
        }

        $jobs = $jobs->with(['jobType', 'category']);

        $jobs = $jobs->paginate(9);

        return view ("front.jobs", [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }

    //This method will show job detail page
    public function detail($id){
        $job = Job::where([
                            'id'=> $id,
                            'status' => 1
                          ])->with(['jobType', 'category'])->first();
        if($job == null){
            abort(404);
        }
        $savedJobCount = 0;
        if(Auth::user()){
            $savedJobCount = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id'=> $id
            ])->count();
        }

        //fetch Applicants
        $applications = JobApplication::where('job_id', $id)->with('user')->get();

        return view('front.jobDetail', [
              'job'=> $job,
              'savedJobCount' => $savedJobCount,
              'applications' => $applications
        ]);
    }

    public function applyJob(Request $request){
        // Check if user is authenticated
        if (!Auth::check()) {
            $message = 'Ju duhet të hyni në llogarinë tuaj për të aplikuar për një punë!';

            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        $id = $request->id;
        $job = Job::where('id', $id)->first();

        // If job is not found in the database
        if($job == null){
            $message = 'Njoftimi për punë nuk ekziston!';

            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // You cannot apply to your own job
        $employer_id = $job->user_id;
        if($employer_id == Auth::user()->id){
            $message = 'Ju nuk mund të aplikoni në punën tuaj!';

            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        //You cannot apply twice in a job
        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id'=> $id
        ])->count();

        if($jobApplicationCount > 0){
            $message = 'Ju keni aplikuar tashmë për këtë punë!';

            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }

        // Create a new job application
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        //Send notification email to employer
        $employer = User::where('id', $employer_id)->first();

        $mailData= [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];

        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        $message = 'Aplikimi juaj u dërgua me sukses!';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function savedJob (Request $request){
        $id = $request->id;
        $job = Job::find($id);

        if($job == null){
            session()->flash('error', 'Njoftimi për punë nuk u gjet!');
            return response()->json([
                'status' => false,
            ]);
        }
        //Check if user already saved the job

        $savedJobCount = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id'=> $id
        ])->count();

        if($savedJobCount > 0) {
            session()->flash('error', 'Ju e keni ruajtur tashmë këtë punë!');
            return response()->json([
                'status' => false,
            ]);
        }
        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success', 'Puna u ruajt me sukses!');
        return response()->json([
            'status' => true,
        ]);
    }

}
