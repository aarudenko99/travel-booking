<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use App\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/verify-success';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(!Auth::user()){
            return redirect($this->redirectPath());
        }
        return Auth::user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
    }
    
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\R\Illuminate\Http\Requestequest  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        if(Auth::user() && Auth::user()->id != $user->id){
            return redirect("/");
        }

        if(!$user || !$request->hasValidSignature()){
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            Auth::loginUsingId($user->id);
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) 
            event(new Verified($user));
            Auth::loginUsingId($user->id);{
        }

        return redirect($this->redirectPath())->with('verified', true);
    }
}
