<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\strtotime;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;


class ForgotPasswordController extends Controller
{
    private $user= null;


    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
              'email' => 'required|email|exists:users',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
             return response('Reset password mail sent to the user successfully', 200);

            }
            else {
            return response('Too many requests, please try again later !', 429);
        }

    }

    public function submitResetPasswordForm(Request $request)
    {
                      $request->validate([
                        'token' => 'required',
                        'email' => 'required|email',
                        'password' => 'required|min:8|confirmed',
                    ]);

                    $status = Password::reset(
                        $request->only('email', 'password', 'password_confirmation', 'token'),
                        function ($user, $password) {
                            $user->forceFill([
                                'password' => Hash::make($password)
                            ])->setRememberToken(Str::random(60));

                            $user->save();

                            event(new PasswordReset($user));
                        }
                    );

                    // return $status === Password::PASSWORD_RESET
                    //             ? redirect('http://192.168.2.121:8080/')
                    //              : back()->withErrors(['email' => [__($status)]]);
                    return response()->json(['message'=>$status === Password::PASSWORD_RESET]);

    }

    public function AdminResetPassword($id){
        $this->user  = User::findOrFail($id);
        $this->new_password = Str::random(8);
        $this->user->password =  Hash::make($this->new_password);
        $this->user->pwd_reset_admin = 1;
        $this->user->save();

        Mail::send('email.ResetPassword', ['user' =>$this->user,'New_password'=> $this->new_password], function($message) {
            $message->to( $this->user->email);
            $message->subject('Reset Password');
        });
    }

    public function ResetPassword(Request $request, $id){
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));

                // if (isset($user->last_token)) {
                //     JWTAuth::manager()->invalidate(new Token($user->last_token), true);
                // }
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->successResponse('Password has been reset successfully');
        } else {
            return $this->errorResponse('An error occured when changing the password, please try again later !', 400);
        }
    }

}

