<?php

namespace App\Http\Controllers;

use App\Models\HeathFacility;
use App\Models\Province;
use App\Models\District;
use App\Models\Tehsil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HealthFacilityController extends Controller
{

    public function index()
    {
        // $records= HeathFacility::get();
     
        $records = DB::table('health_facilities')
        ->join('province', 'health_facilities.province_id', '=', 'province.province_id')
        ->join('division', 'health_facilities.division_id', '=', 'division.division_id')
        ->join('district', 'health_facilities.district_id', '=', 'district.district_id')
        ->join('tehsil', 'health_facilities.tehsil_id', '=', 'tehsil.tehsil_id')
        ->orderBy('health_facility_name', 'asc')
        ->select('health_facilities.*', 'province.province_name', 'division.division_name', 'district.district_name', 'tehsil.tehsil_name')
        ->get();


        return view('health-facilities.index', compact('records'));
    }

    public function create()
    {   
        $province = Province::get();
        $district = District::get();

        return view('health-facilities.create', compact('province', 'district'));
    }


    public function store(Request $request)
    {       
    
        $teshil = Tehsil::where('tehsil_id', $request->tehsil_id)
        ->first();
   
        
        $HeathFacility = new HeathFacility();
        $HeathFacility->province_id = $teshil->province_id;
        $HeathFacility->division_id = $teshil->division_id;
        $HeathFacility->district_id = $teshil->district_id;
        $HeathFacility->tehsil_id = $teshil->tehsil_id;
        $HeathFacility->health_facility_name = $request->health_facility_name;
        $HeathFacility->save();

        return redirect()->route('health-facilities.index')
                        ->with('success','Health facility created successfully.');
              
    }


    public function show(HeathFacility $heathFacility)
    {
        //
    }


    public function edit(HeathFacility $heathFacility)
    {
        //
    }


    public function update(Request $request, HeathFacility $heathFacility)
    {
        //
    }


    public function destroy(HeathFacility $heathFacility)
    {
        //
    }
}
