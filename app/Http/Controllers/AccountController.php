<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            'confirm_password' => ['required','same:password'],
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
            session()->flash('success', 'Regjistrimi u krye me sukses!');

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
                    return redirect()->route('account.login')->with('error', 'E-maili ose fjalëkalimi janë të pasaktë!');
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
            session()->flash('success', 'Informacioni i përdoruesit u përditësua me sukses! ');
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
            'image.image' => 'Nuk mund të ngarkohej imazhi. Ju lutem ngarkoni një foto profili të vlefshme!',
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
            Session::flash('success', 'Fotoja e profilit u përditësua me sukses!');

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

            session()->flash('success', 'Njoftimi për punë u listua me sukses!');
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

        $jobs= Job::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);
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

            session()->flash('success', 'Detajet e punës u përditësuan me sukses!');
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
            session()->flash("error", "Njoftimi për punë është fshirë ose nuk u gjet!");
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id', $request->jobId)->delete();
        session()->flash("succes", "Njoftimi për punë u fshi me sukses!");
        return response()->json([
            'status' => true
        ]);
    }

    public function myJobApplications(){
        $jobApplications = JobApplication::where('user_id',Auth::user()->id)
                                           ->with(['job','job.jobType','job.applications'])
                                           ->orderBy('created_at' , 'DESC')
                                           ->paginate(10);
        return view('front.account.job.my-job-applications',[
            'jobApplications' => $jobApplications
        ]);
    }

    public function removeJobs(Request $request){
        $jobApplication =JobApplication::where([
                                        'id' => $request->id,
                                        'user_id' => Auth::user()->id]
                                         )->first();
        if($jobApplication == null){
            session()->flash("error", "Aplikimi për punë nuk u gjet!");
            return response()->json([
                'status' => false
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash("success", "Aplikimi për punë u hoq me sukses.");
        return response()->json([
            'status' => true
        ]);
    }

    public function fetchSavedJobs(){
        /*$jobApplications = JobApplication::where('user_id',Auth::user()->id)
            ->with(['job','job.jobType','job.applications'])
            ->paginate(10);*/

        $fetchSavedJobs = SavedJob::where([
            'user_id' => Auth::user()->id,
        ])->with(['job','job.jobType','job.applications'])
          ->orderBy('created_at' , 'DESC')
          ->paginate(10);

        return view('front.account.job.fetch-saved-jobs',[
            'fetchSavedJobs' => $fetchSavedJobs,
        ]);
    }

    public function removeSavedJob(Request $request){
        $savedJob =SavedJob::where([
                'id' => $request->id,
                'user_id' => Auth::user()->id]
        )->first();
        if($savedJob == null){
            session()->flash("error", "Njoftimi për punë nuk u gjet!");
            return response()->json([
                'status' => false
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash("success", "Puna e ruajtur u hoq me sukses!");
        return response()->json([
            'status' => true
        ]);
    }

    public function updatePassword(Request $request){

        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
         ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);

        }
        if(!Hash::check($request->old_password, Auth::user()->password)){
            session()->flash('error','Fjalëkalimi juaj i vjetër është i pasaktë!');
            return response()->json([
                'status' => true
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();
        session()->flash('success' , 'Fjalëkalimi u përditësua me sukses!');
        return response()->json([
            'status' => true
        ]);
    }

    public function forgotPassword(){
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);
        if($validator->fails()){
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        //Send Email here
        $user = User::where('email', $request->email)->first();
        $mailData =[
            'token' => $token,
            'user' => $user,
            'subject' => 'Ju keni kërkuar të ndryshoni fjalëkalimin e llogarisë tuaj.'
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));
        return redirect()->route('account.forgotPassword')->with('success', 'Linku për rivendosjen e fjalëkalimit është dërguar në llogarinë tuaj të emailit. Ju lutem kontrolloni llogarinë tuaj të e-mail për të rivendosur fjalëkalimin!');
    }

    public function resetPassword($tokenString){
        $tokenRecord = DB::table('password_reset_tokens')->where('token', $tokenString)->first();
        if ($tokenRecord == null) {
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token!');
        }
        return view('front.account.reset-password', [
            'tokenString' => $tokenRecord->token
        ]);
    }


    public function processResetPassword(Request $request){

        $token = DB::table('password_reset_tokens')->where('token',$request->token)->first();
        if($token == null){
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token!');
        }
        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);
        if($validator->fails()){
            return redirect()->route('account.resetPassword', $request->token)->withErrors($validator);
        }
        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password)
        ]);
        return redirect()->route('account.login')->with('success', 'Fjalëkalimin tuaj u ndryshua me sukses!');

    }
}
