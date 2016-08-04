<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\User;
use App\Token;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return view('pages.home');
        }
        else
            return view('pages.welcome');
    }

    public function add_more_info(Request $request)
    {
        if (Auth::check()) {
            $user = User::where('email', Auth::user()->email)->first();
            if(!empty($request->DOB)){$user->DOB = $request->DOB;}
            if(!empty($request->country)){$user->country = $request->country;}
            if(!empty($request->contact)){$user->contact = $request->contact;}
            if(!empty($request->university)){$user->university = $request->university;}
            if(!empty($request->course)){$user->course = $request->course;}
            if(!empty($request->referred_by)){$user->referred_by = $request->referred_by;}
            $user->save();
            return redirect('/home');
        }
        else
            return view('pages.welcome');
    }

    /**
     * Get a validator for an incoming contact send mail request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'new_token_description' => 'required|min:50|max:300',
            'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    public function contact_send_mail(Request $request)
    {
        if (Auth::check()) {
            $validator = $this->validator($request->all());

            if ($validator->fails()) {
                $this->throwValidationException(
                    $request, $validator
                );
            }

            //saving the message in DB
            $token = new Token;
            $token->name=Auth::user()->name;
            $token->email=Auth::user()->email;
            $token->description=$request->input('new_token_description');
            $token->save();
            
            return redirect('/home');
        }
        else
            return view('pages.welcome');
    }
}
