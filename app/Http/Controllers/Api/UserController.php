<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUser;
use App\Http\Requests\Api\UpdatePassword;
use App\Http\Requests\Api\UserRegister;
use App\Http\Resources\Api\ProfileResource;
use App\Http\Resources\Api\UpdateProfile;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Register a user then return a token
    public function Register(UserRegister $request)
    {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->fcm_token = $request->fcm_token;
            if($request->hasFile('image')){
                $user->image = ImageService::addImage('images/user_profile', $request->image, 'ProfileImage_');
            }
            $user->save();
            $token = $user->createToken('API Token')->plainTextToken;
            return $this->success(data: [
                'token' => $token
            ], message: 'Successfully Registered');

    }
    //Login a user then return a token
    public function Login(LoginUser $request)
    {
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->email)->first();
        })->first();
        if (!$user)
            return $this->error(message: 'Incorrect Email',code: 403);
        if (!auth()->loginUsingId((password_verify($request->password, $user->password)) ? $user->id : 0))
            return $this->error(message: 'Incorrect Password',code: 403);
        $user = auth()->user();
        $token = $user->createToken('API TOKEN')->plainTextToken;
        return $this->success(data: [
            'token' => $token,
        ], message: 'Logged in successfully');
    }
    //Get Profile
    public function GetProfile(Request $request)
    {
        $user = auth()->user();
        $result = ProfileResource::make($user);
        //User Profile Activity Log
        ActivityLog::create([
            'log_name' => 'Profile',
            'causer_id' => $user['id'],
            'causer_type' => 'App\Model\User',
            'activity' => 'Added',
            'description' => 'View Profile',
            'subject_id' => 1,
            'subject_type' => 'App\Model\User',
        ]);
//        activity()->log('Look mum, I logged something');
        return $this->success(data: $result, message: 'Success');
    }
    //update profile
    public function UpdateProfile(Request $request){

            $auth_user = Auth::user();
            $auth_user->name = $request->input('name') ? $request->input('name') : Auth::user()['name'];
            if($request->image == null){
                if(basename(Auth::user()['image']) == 'avatar.png'){
                    $auth_user->image = null;
                }
                else{
                    $auth_user->image = basename(Auth::user()['image']);
                }
            }
            else{
                $auth_user->image = ImageService::updateImage('images/user_profile',$request->image, Auth::user()['image'],'ProfileImage_');
            }
            $auth_user->save();
            //update profile activity log
            ActivityLog::create([
                'log_name' => 'Profile',
                'causer_id' => $auth_user['id'],
                'causer_type' => 'App\Model\User',
                'activity' => 'Updated',
                'description' => 'Update Profile Profile',
                'subject_id' => 1,
                'subject_type' => 'App\Model\User',
            ]);
            return $this->success(data: [
                'data' => UpdateProfile::make($auth_user),
            ], message: 'Profile updated successfully');

    }
    //change password
    public function UpdatePassword(UpdatePassword $request){
        if (!Hash::check($request->old_password, Auth::user()['password'])) {
            return $this->error(message: 'Old password not matched', code: 403);
        }else {
            User::where('email', Auth::user()['email'])->update(['password' => bcrypt($request->new_password)]);
            //update Password activity log
            ActivityLog::create([
                'log_name' => 'Profile',
                'causer_id' => Auth::user()['id'],
                'causer_type' => 'App\Model\User',
                'activity' => 'Updated',
                'description' => 'Update Password',
                'subject_id' => 1,
                'subject_type' => 'App\Model\User',
            ]);
            return $this->success(data: null, message: 'Password updated successfully');
        }
    }
    //logout
    public function Logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->success(data: null, message: 'Logged out successfully');
    }
}
