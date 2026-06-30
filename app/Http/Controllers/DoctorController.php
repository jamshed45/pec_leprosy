<?php

namespace App\Http\Controllers;

use App\Models\HeathFacility;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{

    public function index()
    {
        // $records= HeathFacility::get();
     
        $records = DB::table('doctors')
        ->join('health_facilities', 'doctors.health_facility_id', '=', 'health_facilities.health_facility_id')
        ->join('tehsil', 'health_facilities.tehsil_id', '=', 'tehsil.tehsil_id')
        ->select('doctors.*', 'health_facilities.health_facility_name')
        ->get();

        return view('doctors.index', compact('records'));
    }

    public function create()
    {   

        $heathFacility = HeathFacility::orderBy('health_facility_name','asc')->get();

        return view('doctors.create', compact('heathFacility'));
    }


    public function store(Request $request)
    {   

        $health_facility  = HeathFacility::where('health_facility_id', $_POST['health_facility_id'])
                    ->first()
                    ->toArray();

        Doctor::create([
            'health_facility_id' => $health_facility['health_facility_id'],
            // 'province_id' => $health_facility['province_id'],
            // 'tehsil_id' => $health_facility['tehsil_id'],
            'doctor_name' => $request->doctor_name,
          ]);
   
        
        return redirect()->route('doctors.index')
                        ->with('success','Doctor Added successfully!');
              
    }


    public function show(Doctor $doctor)
    {
        //
    }


    public function edit(Doctor $doctor)
    {
    
        $record = $doctor;
        $heathFacility = HeathFacility::orderBy('health_facility_name','asc')->get();

        return view('doctors.edit',compact('record', 'heathFacility'));
    }


    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($request->all());
      
        return redirect()->route('doctors.index')
                        ->with('success','Doctor updated successfully');
    }


    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('doctors.index')
                        ->with('error','Doctor deleted successfully');

    }

    public function get_doctors(Request $request)
    {
        // print_r($request->all());

        $doctors = Doctor::where('health_facility_id', $request->health_facility_id)
                ->get()->toArray();      
        if($doctors)
        {
            echo "<option value=''>Select Doctor</option>";
            foreach($doctors as $item)
            {
                echo "<option value='".$item['doctor_id']."'>".$item['doctor_name']."</option>";
            }
        }
        else
        {
            echo "";
        }
    }
}
