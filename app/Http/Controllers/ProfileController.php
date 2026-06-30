<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        echo "index";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('profile.edit');
    }

    public function edit_profile($id, User $User)
    {
        $user_info  = User::where('id', $id)->first();

        return view('profile.edit', compact('user_info'));   
    }

    public function update_profile(Request $request, User $user)
    {

        if($request->file('image'))
        {
            $user_img_name = time().'.'.$request->file('image')->extension();
            
            $request->file('image')->move(public_path('uploads'), $user_img_name);

            User::where('id', '=', $request->user_id)->update([
                'img' => $user_img_name,
            ]);
        }

        if($request->name)
        {
            User::where('id', '=', $request->user_id)->update([
                'name' => $request->name,
            ]);
        }

        if($request->new_user_name)
        {
            User::where('id', '=', $request->user_id)->update([
                'username' => $request->new_user_name,
            ]);
        }
        
        if($request->input('new_password'))
        {
            User::where('id', '=', $request->user_id)->update([
                'password_visible' => $request->input('new_password'), 
                'password' => Hash::make($request->input('new_password')) 
            ]);
        }


        return redirect()->back()->with('message', ''.$request->name.' Profile updated successfully!');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo "index";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        echo "index";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
