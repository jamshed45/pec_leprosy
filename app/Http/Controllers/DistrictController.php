<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Division;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class DistrictController extends Controller
{

    public function index()
    {

        $records = DB::table('district')
            ->join('division', 'district.division_id', '=', 'division.division_id')
            ->join('province', 'district.province_id', '=', 'province.province_id')
            ->orderBy('district.district_name', 'asc')
            ->select('district.*', 'division.division_name', 'province.province_name')
            ->get();
   
        return view('districts.index', compact('records'));
    }

 
    public function create()
    {   
        $division = Division::get();
      
        return view('districts.create', compact('division'));
    }

 
    public function store(Request $request)
    {

        $province = Division::where('division_id', $request->division_id)
                        ->first();
    
        $district = new District();
        $district->province_id = $province->province_id;
        $district->division_id = $request->division_id;
        $district->district_name = $request->district_name;
        $district->save();                
    
       
        return redirect()->route('districts.index')
                        ->with('success','District created successfully.');
    }

  
    public function show(Province $province)
    {
        //
    }

  
    public function edit(District $district)
    {
        $record = $district;
        $division = Division::get();
    
        return view('districts.edit',compact('record', 'division'));
    }

  
    public function update(Request $request, District $district)
    {
        $district->update($request->all());
      
        return redirect()->route('districts.index')
                        ->with('success','City updated successfully');
    }

 
    public function destroy(District $district)
    {
        $district->delete();

        return redirect()->route('districts.index')
                        ->with('success','Province deleted successfully');
    }

    public function get_province_cities(Request $request)
    {

        $cities = City::where('province_id', $request->province_id)
                    ->orderBy('city_name')
                    ->get()
                    ->toArray();


        if($cities)
        {
            foreach($cities as $city)
            {

            $check_city_center = DB::table('pec_center')
                ->where('tehsil_id', '=', $city['tehsil_id'])
                ->get()
                ->toArray();
            
            if(empty($check_city_center))
                {
                    continue;
                }    

                $city_unique_id = uniqid();
                $get_center_url =  URL::to("/centers/get_city_centers");
            
                echo "<div class='row'><div class='col-md-4' style='background-color:#b2cfd3; border:white 1px solid; padding:5px;'>";
                echo "<input class='select_city' name='user_cities[".$request->province_id."][]' data-cid='".$city_unique_id."' type='checkbox' class=select_city' value='".$city['tehsil_id']."'  data-url='".$get_center_url."' /> ".$city['city_name']."</div>";
                echo "<div class='col-md-8' id='cid-".$city_unique_id."'></div>";
                echo "</div>";
            }
        }
        else
        {
            echo "<div class='col-md-12' style='background-color:#b2cfd3; border:white 1px solid; padding:5px;'>";
            echo "No City Added";
            echo "</div>";
        }    
        return;
    }


    public function get_province_hf_districts(Request $request)
    {

        $cities = District::where('province_id', $request->province_id)
                    ->orderBy('district_name')
                    ->get()
                    ->toArray();

        if($cities)
        {
            echo "<option value=''>Select Districy</option>";
            foreach($cities as $city)
            {   
                echo "<option value='".$city['district_id']."'>".$city['district_id']."</option>";
            }
        }
        else
        {

        }    
        return;
    }

}
