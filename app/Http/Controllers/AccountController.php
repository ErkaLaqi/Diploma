<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class AccountController extends Controller
{
    //This method will show user registration page
    public function registration(){
        return view('front.account.registration');
    }
    //This method will save a new  user

    public function processRegistration(Request $request){
        $validator= Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'lastname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'birthday' => ['required', 'date', 'max:255', 'before:'.now()->subYears(18)->toDateString() ],
            'password' => ['required', Rules\Password::defaults()], /*confirmed*/
            'confirm_password' => ['required'],
        ],[
            'name.regex' => 'Name must contain only letters',
            'lastname.regex' => 'Lastname must contain only letters',
            'birthday.before' => trans('You must be at least 18 years old'),
        ]);
        if ($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->birthday = $request->birthday;
            $user->password = Hash::make($request->password);
            $user->save();
            session()->flash('success', 'You have registered successfully.');

            return response()->json([
                'status'=> true,
                'errors'=> []
            ]);
        }else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);
        }
    }


    //This method will show user login page
    public function login(){
        return view('front.account.login');
    }
    public function authenticate(Request $request){
        $validator =Validator::make($request->all(),[
            'email' => 'required|email',
            'password'=> 'required',
        ]);
        if($validator->passes()){
            if (Auth::attempt([ 'email'=>$request->email, 'password'=>$request->password ]))
            {
                /* redirect to profile*/
                return redirect()->route('account.profile');
            }
                else{
                    return redirect()->route('account.login')->with('error', 'Either email or password is incorrect!');
                }
        }else{
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile(){
        $id = Auth::user()->id;
/*        $user = User::where('id', $id)->first();*/
        $user = User::find($id);
/*        dd($user);*/
        return view('front.account.profile', [
            'user'=> $user
        ]);
    }

    public function profileUpdate(Request $request){
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'birthday' => 'required|date|before:'.now()->subYears(18)->toDateString(),
        ]);
        if ($validator->passes()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->birthday = $request->birthday;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();
            session()->flash('success', 'Your profile information was updated successfully ');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePhoto(Request $request){
/*        dd($request->all());*/
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image'],
        ],[
            'image.image' => 'Could not upload image. Please upload a valid profile picture.',
        ]);

        if($validator->passes()){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName= $id.'-'.time().'.'.$ext;  //1-12312312.png
            $image->move(public_path('/profile_photo'), $imageName);

            // create a small thumbnail
            $sourcePath=public_path('/profile_photo/'.$imageName);
            // create new image instance (800 x 600)
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);
            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(600, 360);
            $image->toPng()->save(public_path('/profile_photo/thumb/'.$imageName));

            //Delete old profile picture
            File::delete(public_path('/profile_photo/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile_photo/'.Auth::user()->image));

            User::where('id', $id)->update(['image' => $imageName]);
            Session::flash('success', 'Profile photo updated successfully.');

            return response()->json([
                'status' => true,
                'errors'=> []
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'errors'=> $validator->errors()
            ]);
        }
    }

    public function createJob(){
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.account.job.create',[
            'categories'=>$categories,
            'jobTypes'=>$jobTypes
        ]);
    }

    public function saveJob(Request $request){

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
            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
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

            $job->save();

            session()->flash('success', 'Job notification listed successfully!');
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

    public function myJobs(){

        $jobs= Job::where('user_id',Auth::user()->id)->with('jobType', 'category')->paginate(10);
/*        dd($jobs);*/
        return view('front.account.job.my-jobs',[
            'jobs'=>$jobs
        ]);
    }

    public function editJobs(Request $request, $id){
/*        dd($id);*/
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id'=> $id
        ])->first();
        if($job == null){
            abort(404);
        }
        return view('front.account.job.edit', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job
        ]);

    }

    public function updateJob(Request $request, $id){

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
            $job->user_id = Auth::user()->id;
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
    public function deleteJob(Request $request){
        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();

        if($job == null){
            session()->flash("error", "Either job deleted or not found.");
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id', $request->jobId)->delete();
        session()->flash("succes", "Job deleted successfully.");
        return response()->json([
            'status' => true
        ]);
    }

}
