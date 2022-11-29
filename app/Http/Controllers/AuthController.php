<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @return View of Login form
     */
    function loginView()
    {
        return view("login");
    }

    /**
     * @return View of Register form
     */
    function registerView()
    {
        return view("register");
    }

    /**
     * Login
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',   // required and email format validation
            'password' => 'required', // required and number field validation

        ]); // create the validations
        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            return response()->json($validator->errors(), 422);
            // validation failed return with 422 status

        } else {
            //validations are passed try login using laravel auth attemp
            if (\Auth::attempt($request->only(["email", "password"]))) {
                return response()->json(["status" => true, "redirect_location" => url("dashboard")]);

            } else {
                return response()->json([["Invalid credentials"]], 422);

            }
        }
    }

    /**
     * Register
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'unique:users,email',
                //Custom validator for email
                function ($attribute, $value, $fail) {
                    $response = Http::get('https://tls.bankaccountchecker.com/listener.php', [
                        'key' => '4b6c6d0237fa213ab0bac66633eb8b91',
                        'password' => 'Qw12345678!',
                        'output' => 'json',
                        'type' => 'email',
                        'email' => $value,
                    ]);
                    if ($response['resultCode'] == 02)
                        $fail($response['resultDescription']);
                }
            ],
            'phone' => 'phone:AUTO',
            'city' => 'required',
            'address' => 'required',
            'dob' => 'required|date|date_format:Y-m-d',
            'password' => 'required|min:8',
            'post_code' => 'required|regex:/^([a-zA-Z0-9]{2})-([a-zA-Z0-9]{3})$/',
            'confirm_password' => 'required|same:password',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $User = new User;
            $User->name = $request->name;
            $User->email = $request->email;
            $User->phone = $request->phone;
            $User->dob = $request->dob;
            $User->password = bcrypt($request->password);
            $User->registration_date = date('Y-m-d');
            $User->save();

            $UserAddress = new UserAddress();
            $UserAddress->user_id = $User->id;
            $UserAddress->city = $request->city;
            $UserAddress->post_code = $request->post_code;
            $UserAddress->address = $request->address;
            $UserAddress->save();

            return response()->json(["status" => true, "msg" => "You have successfully registered, Login to access your dashboard", "redirect_location" => url("login")]);

        }
    }

    /**
     * Update User Info
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                //Custom validator for email
                function ($attribute, $value, $fail) {
                    $response = Http::get('https://tls.bankaccountchecker.com/listener.php', [
                        'key' => '4b6c6d0237fa213ab0bac66633eb8b91',
                        'password' => 'Qw12345678!',
                        'output' => 'json',
                        'type' => 'email',
                        'email' => $value,
                    ]);
                    if ($response['resultCode'] == 02)
                        $fail($response['resultDescription']);
                }
            ],
            'phone' => 'phone:AUTO',
            'city' => 'required',
            'address' => 'required',
            'dob' => 'required|date|date_format:Y-m-d',
            'password' => 'nullable|min:8',
            'post_code' => 'required|regex:/^([a-zA-Z0-9]{2})-([a-zA-Z0-9]{3})$/',
            'confirm_password' => 'nullable|same:password',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $User = User::find(\Auth::user()->id);
            $User->name = $request->name;
            $User->email = $request->email;
            $User->phone = $request->phone;
            $User->dob = $request->dob;
            if ($User->password != "")
                $User->password = bcrypt($request->password);
            $User->save();

            $UserAddress = UserAddress::find(\Auth::user()->address->id);
            $UserAddress->city = $request->city;
            $UserAddress->post_code = $request->post_code;
            $UserAddress->address = $request->address;
            $UserAddress->save();

            return response()->json(["status" => true, "msg" => "Your info updated!", "redirect_location" => url("dashboard")]);

        }
    }

    /**
     * @return View of the Dashboard
     */
    function dashboard()
    {
        return view("dashboard");
    }

    /**
     * @return Logout
     */
    function logout()
    {
        \Auth::logout();
        return redirect("login")->with('success', 'Logout successfully');
    }
}
