<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::orderBy('created_at', 'ASC')->paginate(10);
        return view('admin.users.list', [
            'users' => $users,
        ]);
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('admin.users.edit', [
            'user'=>$user
        ]);
    }

    public function update($id, Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|regex:/^[a-zA-Z]+$/u',
            'lastname' => 'required|string|max:255|regex:/^[a-zA-Z]+$/u',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'birthday' => 'required|date|before:'.now()->subYears(18)->toDateString(),
        ],[
            'name.regex' => 'Emri duhet te permbaje vetem shkronja!',
            'lastname.regex' => 'Mbiemri duhet te permbaje vetem shkronja!',
            'email.unique' => 'Ky email i perket nje llogarie tjeter!',
            'birthday.before' => trans('Mosha e lejuar eshte +18!'),
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
            session()->flash('success', 'Informacioni i profilit tuaj u përditësua me sukses!');
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

    public function destroy(Request $request){
        $id = $request->id;
        $user =User::find($id);
        if($user == null){
            session()->flash('error', 'Përdoruesi nuk u gjet!');
            return response()->json([
                'status' => false,
            ]);
        }
        $user->delete();

        session()->flash('success', 'Përdoruesi u fshi me sukses!');
        return response()->json([
            'status' => true,
        ]);
    }
}
