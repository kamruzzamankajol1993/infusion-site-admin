<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Hash;
use Mail;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;
use Exception;

class SettingController extends Controller
{
    public function error_500()
    {
        return view('admin.error_500');
    }

    public function profileView()
    {
        try {
            return view('admin.profile.profileView');
        } catch (Exception $e) {
            Log::error('Failed to load profile view page: ' . $e);
            return redirect()->route('error_500');
        }
    }

    public function profileSetting()
    {
        try {
            return view('admin.profile.profileSetting');
        } catch (Exception $e) {
            Log::error('Failed to load profile setting page: ' . $e);
            return redirect()->route('error_500');
        }
    }

    public function profileSettingUpdate(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|same:confirm-password',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $input = $request->except(['_token', 'password', 'image', 'confirm-password']);

            if (!empty($request->password)) { 
                $input['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                $time_dy = time() . date("Ymd");
                $productImage = $request->file('image');
                $imageName = 'profileImage' . $time_dy . $productImage->getClientOriginalName();
                $directory = 'public/uploads/';
                Image::read($productImage)->resize(100, 100)->save(public_path($directory . $imageName));
                $input['image'] = $directory . $imageName;
            }
            
            $user->update($input);
            DB::commit();

            // CommonController::addToLog('profile update');
            Log::info('Profile updated successfully.', ['user_id' => $id]);
            return redirect()->back()->with('success','User updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update profile for user ID {$id}: " . $e);
            return redirect()->back()->with('error', 'Failed to update profile. Please check logs.');
        }
    }

    public function checkMailForPassword(Request $request)
    {
        try {
            $email = $request->mainId;
            $checkMail = User::where('email', $email)->count();
            return $checkMail;
        } catch (Exception $e) {
            Log::error('Failed to check mail for password reset: ' . $e);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function checkMailPost(Request $request)
    {
        try {
            Mail::send('emails.passwordChangeEmail', ['id' => $request->email], function($message) use($request){
                $message->to($request->email);
                $message->subject('Password Change Link');
            });
            Log::info('Password change email sent.', ['email' => $request->email]);
            return redirect()->route('newEmailNotify')->with('success','Email sent successfully!');
        } catch (Exception $e) {
            Log::error('Failed to send password change email: ' . $e);
            return redirect()->back()->with('error', 'Could not send email. Please check mail configuration and logs.');
        }
    }

    public function newEmailNotify()
    {
        try {
            return view('admin.setting.newEmailNotify');
        } catch (Exception $e) {
            Log::error('Failed to load new email notification page: ' . $e);
            return redirect()->route('error_500');
        }
    }

    public function accountPasswordChange($id)
    {
        try {
            // \LogActivity::addToLog('accountPasswordChange');
            $email = $id;
            return view('admin.setting.accountPasswordChange', compact('email'));
        } catch (Exception $e) {
            Log::error('Failed to load account password change page: ' . $e);
            return redirect()->route('error_500');
        }
    }

    public function postPasswordChange(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password is required',
        ]);

        try {
            DB::beginTransaction();
            // CommonController::addToLog('password update');

            $user = User::where('email', $request->mainEmail)->firstOrFail();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();
            Log::info('User password changed successfully via email link.', ['user_id' => $user->id, 'email' => $user->email]);
            return redirect()->route('login')->with('success','Password changed successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to post password change: ' . $e);
            return redirect()->route('error_500');
        }
    }
}