<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function password()
    {

        return view('updatePassword');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = Auth::user();

        $input = $request->except(['avatar', 'password']);

        $user->update($input);

        session()->flash('message', 'تم التعديل بنجاح');

        return redirect()->back();
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            "old_password" => "required|min:6|max:100",
            "new_password" => "required|min:6|max:100",
            "confirm_password" => "required|same:new_password",
        ]);
        $current_user = auth()->user();
        if (Hash::check($request->old_password, $current_user->password)) {

            $current_user->update([
                'password' => Hash::make($request->new_password),
            ]);
            return redirect()->back()->with('message', 'تم تغيير الباسورد بنجاح');
        } else {
            return redirect()->back()->with('error', 'الباسورد الحالى غير مطابق');
        }

    }

}
