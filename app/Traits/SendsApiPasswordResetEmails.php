<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\AESCrypt;

trait SendsApiPasswordResetEmails {

    public function sendResetApiLinkEmail(Request $request) {
        $u_email = $request->email;
        $user = User::where('email', $u_email)->where('u_status', '!=', 9)->first();
        //print_r($user);die;
        if (!$user) {
            return $this->sendResetApiLinkResponse("User not found.");
        }
        //print_r();die;
//        $this->validateRequestEmail($request);
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
                $request->only('email')
        );
        
        return $response == Password::RESET_LINK_SENT ? $this->sendResetApiLinkResponse("Forgot password link sent successfully") : $this->sendResetApiLinkFailedResponse($request, __("messages.forgot_password.error"));
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return json
     */
    protected function sendResetApiLinkResponse($response) {
        $message = ["result" => (object) null, "message" => ($response), "status" => true, "code" => 0];
        return response()->json($message, 200);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  string  $response
     * @return json
     */
    protected function sendResetApiLinkFailedResponse($response) {
        $message = ["result" => (object) null, "message" => $response, "status" => false, "code" => 60];
        return response()->json($message, 200);
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return Auth::guard('api');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker() {
        return Password::broker('users');
    }

}
