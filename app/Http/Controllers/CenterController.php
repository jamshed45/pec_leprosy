<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Tehsil;
use App\Models\ActivityLocation;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CenterController extends Controller
{

    public function index()
    {

        $records = DB::table('pec_center')
            ->join('tehsil', 'pec_center.tehsil_id', '=', 'tehsil.tehsil_id')
            ->join('district', 'pec_center.district_id', '=', 'district.district_id')
            ->join('division', 'pec_center.division_id', '=', 'division.division_id')
            ->join('province', 'pec_center.province_id', '=', 'province.province_id')
            ->orderBy('pec_center.center_name', 'asc')
            ->select('pec_center.*','tehsil.tehsil_name', 'district.district_name', 'division.division_name', 'province.province_name')
            ->get();

        // echo "<pre>";    
        // print_r($records);
        // echo "</pre>";
    
        return view('centers.index', compact('records'));
    }

 
    public function create()
    {   
        $tehsils = Tehsil::orderBy('tehsil.tehsil_name', 'asc')->get();
        $activity_location = ActivityLocation::get();
      
        return view('centers.create', compact('tehsils', 'activity_location'));
    }

 
    public function store(Request $request)
    {
        $user = Auth::user();
     
        $tehsil = Tehsil::where('tehsil_id', $request->tehsil_id)
        ->first();

        // echo "<pre>";
        // print_r($tehsil);
        // echo "</pre>";
        // die();
        
        $center = new Center();
        $center->user_id = $user->id;
        $center->province_id = $tehsil->province_id;
        $center->division_id = $tehsil->division_id;
        $center->district_id = $tehsil->district_id;
        $center->tehsil_id = $tehsil->tehsil_id;
        $center->activity_location_id = $request->activity_location_id;
        $center->center_name = $request->center_name;
        $center->save();

        if($user->hasRole('pec-user'))
            {
                DB::table('usermeta')->insert([
                    'user_id' => $user->id,
                    'parent_id' => $tehsil->tehsil_id,
                    'meta_key' => 'center_id',
                    'meta_value' => $center->center_id,
                ]);

                return redirect('/centers/my-center/')
                        ->with('success','Center created successfully.');
            }
            else
            {
                return redirect()->route('centers.index')
                        ->with('success','Center created successfully.');
            }
    }

  
    public function show(Province $province)
    {
        //
    }

  
    public function edit(Center $center)
    {

        $record = $center;
        $tehsils = Tehsil::orderBy('tehsil.tehsil_name', 'asc')->get();
        $activity_location = ActivityLocation::get();
    
        return view('centers.edit',compact('record', 'activity_location', 'tehsils'));
    }

  
    public function update(Request $request, Center $center)
    {

        $center->update($request->all());
      
        return redirect()->route('centers.index')
                        ->with('success','Center updated successfully');
    }

 
    public function destroy(Center $center)
    {
        $center->delete();

        return redirect()->route('centers.index')
                        ->with('success','Province deleted successfully');
    }

    public function my_center(Center $center)
    {

        $user = Auth::user();

        $my_centers = DB::table('usermeta')
                        ->where('user_id', $user->id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

        $records = DB::table('pec_center')
            ->join('tehsil', 'pec_center.tehsil_id', '=', 'tehsil.tehsil_id')
            ->join('district', 'pec_center.district_id', '=', 'district.district_id')
            ->join('division', 'pec_center.division_id', '=', 'division.division_id')
            ->join('province', 'pec_center.province_id', '=', 'province.province_id')
            ->select('pec_center.*','tehsil.tehsil_name', 'district.district_name', 'division.division_name', 'province.province_name')
            ->wherein('pec_center.center_id', $my_centers)
            ->get();

      
        return view('centers.my-center', compact('records'));
    }

    public function add_my_center(Center $center)
    {
        $user = Auth::user();

        $tehsil_single_id = DB::table('usermeta')  
        ->where('user_id', $user->id)
        ->where('meta_key','tehsil_id')
        ->pluck('meta_value')
        ->first();

        $tehsil = DB::table('tehsil')
        ->where('tehsil_id', $tehsil_single_id)
                ->get()
                ->toArray();
   
        $tehsils = Tehsil::where('province_id', $tehsil[0]->province_id)
                ->orderBy('tehsil_name')
                ->get();



        $activity_location = ActivityLocation::get();
    
        return view('centers.add-my-center', compact('tehsils', 'activity_location'));
    }    

    public function get_tehsil_centers(Request $request)
    {
        $centers = Center::where('tehsil_id', $request->tehsil_id)
        ->orderBy('center_name')
        ->get()
        ->toArray();

        if($centers)
        {
            foreach($centers as $center)
            {   

                $activity_location = DB::table('activity_locations')
                                                                        ->where('activity_location_id', $center['activity_location_id'])
                                                                        ->first();
         
                echo '<div class="center-select-contianer">
                    <input type="checkbox" name="user_centers['.$request->tehsil_id.'][]" id="'.$center['center_id'].'" value="'.$center['center_id'].'">
                    <label for="'.$center['center_id'].'">'.$center['center_name'].' <small><b>('.$activity_location->activity_name.')</b></small></label>
                </div>';
            }
        }
        else
        {

        }

    }

    public function get_activity_location_centers(Request $request)
    {
        $user = Auth::user();

        $my_centers = DB::table('usermeta')
                        ->where('user_id', $user->id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

        $centers = Center::wherein('center_id', $my_centers)
                        ->where('activity_location_id', $request->activity_location_id)
        ->orderBy('center_name')
        ->get()
        ->toArray();

        if($centers)
        {
            echo '<option value="">Select Center</option>';
            foreach($centers as $center)
            {   
                echo '<option value="'.$center['center_id'].'">'.$center['center_name'].'</option>';
            }
        }
        else
        {
            echo '<option value="">No Center Added</option>';
        }

    }


    public function get_filter_centers(Request $request)
    {

        echo $request->user_id;
        print_r($request->all());

        $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

        if(isset($request->activity_location_id))
        {
            $centers = Center::wherein('center_id', $my_centers)
                        ->where('activity_location_id', $request->activity_location_id)
                        ->orderBy('center_name')
                        ->get()
                        ->toArray();
        }
        else
        {
            $centers = Center::wherein('center_id', $my_centers)
                        ->orderBy('center_name')
                        ->get()
                        ->toArray();
        }
        

        

        if($centers)
        {
            echo '<option value="">Select Center</option>';
            foreach($centers as $center)
            {   
                echo '<option value="'.$center['center_id'].'">'.$center['center_name'].'</option>';
            }
        }
        else
        {
            echo '<option value="">Select Center</option>';
        }

    }

}
