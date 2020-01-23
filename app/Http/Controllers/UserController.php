<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('user', ['user' => Auth::user()]);
    }

    /**
     * @return string
     */
    public function destroy()
    {
        $user = User::find(Auth::user()->id);
        Auth::logout();

        if ($user->delete()) {
            return redirect()->to('/')->with('status', 'Your account has been deleted!');
        }
        return redirect()->route('home')->with('status', 'Your account hasn\'t been deleted!');
    }

    /**
     * @param Request $request
     * @return
     */
    public function update(Request $request)
    {
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        // Validate the data submitted by user
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:225|'. Rule::unique('users')->ignore($user->id),
            'password' => 'required|string|min:8|confirmed',
            'country' => 'required|max:255',
            'phone' => 'required|digits_between:9,13',
            'postal_code' => 'required|numeric'
        ]);

        // if fails redirects back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Fill user model
        $user->fill([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request['password']),
            'country' => $request->country,
            'phone' => $request->phone,
            'postal_code' => $request->postal_code
        ]);

        // Save user to database
        $user->save();

        // Redirect to route
        return redirect()->route('home')->with('status', 'User updated!');
    }
}
