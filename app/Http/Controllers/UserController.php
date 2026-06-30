<?php

namespace App\Http\Controllers;

use App\Models\ATPModel;
use App\Models\User;
use App\Models\Center;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {

        $users = User::orderBy('name', 'asc')->get();
        // echo "<pre>";
        // print_r($users);
        // // echo $users->name;
        // echo "</pre>";
        // die();
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $provinces = Province::get();

        return view('users.create', compact('provinces'));
    }

    public function store(Request $request, User $user)
    {
        
        $user_provinces = isset($request->user_provinces) && $request->user_provinces != '' ? $request->user_provinces : '';
        $user_tehsils = isset($request->user_tehsils) && $request->user_tehsils != '' ? $request->user_tehsils : '';
        $user_centers = isset($request->user_centers) && $request->user_centers != '' ? $request->user_centers : '';
 
        
        $user = User::where('username', $request->input('username'))->first();
        if($user)        
        {
            return redirect()
            ->route('users.create')
            ->with('error', 'Username already exist.');
        }

       

        $user = User::Create(
            [
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('username').'@pec.com',
                'password_visible' => $request->input('password'),
                'status' => 1,
                'password' => Hash::make($request->input('password')),
            ]
        );
  
        DB::table('users_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 5,
        ]);


    
        //   DB::table('usermeta')->where('meta_key', '=', 'province_id')->where('user_id', '=', '1')->delete();
        //   DB::table('usermeta')->where('meta_key', '=', 'tehsil_id')->where('user_id', '=', '1')->delete();
        //   DB::table('usermeta')->where('meta_key', '=', 'center_id')->where('user_id', '=', '1')->delete();
        if ($user_provinces) {
            foreach ($user_provinces as $province_id) {
                DB::table('usermeta')->insert([
                    'user_id' => $user->id,
                    'meta_key' => 'province_id',
                    'meta_value' => $province_id,
                ]);
                if ($user_tehsils) {
                    foreach ($user_tehsils[$province_id] as $tehsil_id) {
                        DB::table('usermeta')->insert([
                            'user_id' => $user->id,
                            'parent_id' => $province_id,
                            'meta_key' => 'tehsil_id',
                            'meta_value' => $tehsil_id,
                        ]);
                        if ($user_centers) {
                            foreach ($user_centers[$tehsil_id] as $center_id) {
                                DB::table('usermeta')->insert([
                                    'user_id' => $user->id,
                                    'parent_id' => $tehsil_id,
                                    'meta_key' => 'center_id',
                                    'meta_value' => $center_id,
                                ]);
                                echo $center_id;
                            }
                        }
                    }
                }
            }
        }
    
        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        $record = $user;

        $user_centers = DB::table('usermeta')
            ->where('user_id', '=', $user->id)
            ->where('meta_key', '=', 'user_center')
            ->get()
            ->pluck('meta_value')
            ->ToArray();

        // get province ID
        $user_province_id = DB::table('usermeta')
            ->where('user_id', '=', $user->id)
            ->where('meta_key', '=', 'province_id')
            ->get()
            ->pluck('meta_value')
            ->ToArray();

        $provinces = Province::get();

        // $centers = Center::where('cityID', $user_city_ID[0])
        // ->orderBy('center_name')
        // ->get();

        // $province_cities = City::where('provinceID', $user_province_id[0])
        // ->orderBy('city_name')
        // ->get();

        return view('users.edit', compact('provinces', 'record', 'user_province_id'));
    }

    public function update(Request $request, User $user)
    {
        $user_provinces = isset($request->user_provinces) && $request->user_provinces != '' ? $request->user_provinces : '';
        $user_tehsils = isset($request->user_tehsils) && $request->user_tehsils != '' ? $request->user_tehsils : '';
        $user_centers = isset($request->user_centers) && $request->user_centers != '' ? $request->user_centers : '';
        
        if ($request->input('password') != '') {
            $user->update([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('username').'@pec.com',
                'password_visible' => $request->input('password'),
                'password' => Hash::make($request->input('password')),
            ]);
        } else {
            $user->update([
                'name' => $request->input('name'),
                'username' => $request->input('username'),
                'email' => $request->input('username').'@pec.com',
            ]);
        }

        DB::table('usermeta')
            ->where('meta_key', '=', 'province_id')
            ->where('user_id', '=', $user->id)
            ->delete();
        DB::table('usermeta')
            ->where('meta_key', '=', 'tehsil_id')
            ->where('user_id', '=', $user->id)
            ->delete();
        DB::table('usermeta')
            ->where('meta_key', '=', 'center_id')
            ->where('user_id', '=', $user->id)
            ->delete();

        if ($user_provinces) {
            foreach ($user_provinces as $province_id) {
                echo $province_id;
                DB::table('usermeta')->insert([
                    'user_id' => $user->id,
                    'meta_key' => 'province_id',
                    'meta_value' => $province_id,
                ]);

                if ($user_tehsils) {
                    foreach ($user_tehsils[$province_id] as $tehsil_id) {

                        if(isset($user_centers[$tehsil_id]))
                        {
                        
                            DB::table('usermeta')->insert([
                                'user_id' => $user->id,
                                'parent_id' => $province_id,
                                'meta_key' => 'tehsil_id',
                                'meta_value' => $tehsil_id,
                            ]);

                        }

                        if ($user_centers) {

                  
                            if(isset($user_centers[$tehsil_id]))
                            {
                                foreach ($user_centers[$tehsil_id] as $center_id) {
                                    DB::table('usermeta')->insert([
                                        'user_id' => $user->id,
                                        'parent_id' => $tehsil_id,
                                        'meta_key' => 'center_id',
                                        'meta_value' => $center_id,
                                    ]);

                                }
                            }
                        }
                    }
                }
            }
        }

     
        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function destroy(User $user)
    {
        //
    }

    function set_user_center(Request $request)
    {
        $tehsil_id = DB::table('pec_center')
            ->where('center_id', '=', $request->center_id)
            ->get()
            ->pluck('tehsil_id')
            ->ToArray();

            

        $p_id = DB::table('tehsil')
            ->where('tehsil_id', '=', $tehsil_id[0])
            ->first();

        $center_info = [
            'activity_location_id' => $request->activity_location_id,
            'activity_location_name' => $request->activity_location_name,
            'center_id' => $request->center_id,
            'center_name' => $request->center_name,
            'tehsil_id' => $tehsil_id[0],
            'district_id' => $p_id->district_id,
            'division_id' => $p_id->division_id,
            'province_id' => $p_id->province_id,
            'entry_date' => $request->entry_date,
        ];

        $center_info_session = $request->session()->put('center_info', $center_info);

        // echo '<div class="alert alert-success mb-0 mx-3 text-center" role="alert"><small class="text-white">Center Name : </small><br><b class="text-white">'.$request->center_name.'</b></div> <div class="alert alert-warning mb-0 mx-3 text-center" role="alert"><small class="text-white">Date : </small><br><b class="text-white">'.date('jS M, Y',strtotime($request->entry_date)).'</b></div>';
        echo '<div class="col-md-12"><div class="row"><div class="alert col-md-4 alert-primary mb-0 text-center" role="alert"><small class="text-white">Location : </small><b class="text-white">' . $request->activity_location_name . '</b></div><div class="alert col-md-4 alert-success mb-0 text-center" role="alert"><small class="text-white">Center  : </small><b class="text-white">' . $request->center_name . '</b></div><div class="alert col-md-4 alert-warning mb-0 text-center" role="alert"><small class="text-white">Date : </small><b class="text-white">' . date('jS M, Y', strtotime($request->entry_date)) . '</b></div></div></div>';
    }

    function change_user_status(Request $request)
    {
        $user_id = $request->user_id;
        $user_status = $request->user_status;
        print_r($request->all());
        User::where('id', $user_id)
        ->update(['status' => $user_status]);
    }

    function check_username()
    {
        $user_exist =  User::where('username', $_GET['user_name'])->first();
        if($user_exist)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Username Already Exist!',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Username Available!',
            ]);
        }


    }

}
