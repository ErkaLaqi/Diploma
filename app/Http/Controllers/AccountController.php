<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use function Laravel\Prompts\password;

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
        
        return view('front.account.profile');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

}
