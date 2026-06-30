<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Division;
use App\Models\District;
use App\Models\Tehsil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class TehsilController extends Controller
{

    public function index()
    {

        $records = DB::table('tehsil')
            ->join('district', 'tehsil.district_id', '=', 'district.district_id')
            ->join('division', 'tehsil.division_id', '=', 'division.division_id')
            ->join('province', 'tehsil.province_id', '=', 'province.province_id')
            ->orderBy('tehsil.tehsil_name', 'asc')
            ->select('tehsil.*', 'district.district_name' , 'division.division_name', 'province.province_name')
            ->get();

        return view('tehsils.index', compact('records'));
    }


    public function create()
    {
        $district = District::orderBy('district.district_name', 'asc')->get();

        return view('tehsils.create', compact('district'));
    }


    public function store(Request $request)
    {

        $district = District::where('district_id', $request->district_id)
                        ->first();


        $tehsil = new Tehsil();
        $tehsil->province_id = $district->province_id;
        $tehsil->division_id = $district->division_id;
        $tehsil->district_id = $request->district_id;
        $tehsil->tehsil_name = $request->tehsil_name;
        $tehsil->save();

        return redirect()->route('tehsils.index')
                        ->with('success','Tehsil created successfully.');
    }


    public function show(Province $province)
    {
        //
    }


    public function edit(Tehsil $tehsil)
    {

        $record = $tehsil;
        $district = District::get();

        return view('tehsils.edit',compact('record', 'district'));
    }


    public function update(Request $request, Tehsil $tehsil)
    {
        $tehsil->update($request->all());

        return redirect()->route('tehsils.index')
                        ->with('success','Tehsil updated successfully');
    }


    public function destroy(Tehsil $tehsil)
    {
        $tehsil->delete();

        return redirect()->route('tehsils.index')
                        ->with('success','Province deleted successfully');
    }

    public function get_province_tehsils(Request $request)
    {
        // $request->province_id =  12;
        $tehsils = Tehsil::where('province_id', $request->province_id)
                    ->orderBy('tehsil_name')
                    ->get()
                    ->toArray();


        if($tehsils)
        {
            foreach($tehsils as $tehsil)
            {

            $check_tehsil_center = DB::table('pec_center')
                ->where('tehsil_id', '=', $tehsil['tehsil_id'])
                ->get()
                ->toArray();

            if(empty($check_tehsil_center))
                {
                    continue;
                }

                $tehsil_unique_id = uniqid();
                $get_center_url =  URL::to("/centers/get_tehsil_centers");


                echo "<div class='row'><div class='col-md-4'><div class='user-tehsil-container'>";
                echo "<input class='select_tehsil' name='user_tehsils[".$request->province_id."][]' data-cid='".$tehsil_unique_id."' type='checkbox'  value='".$tehsil['tehsil_id']."'  data-url='".$get_center_url."' /> ".$tehsil['tehsil_name']."</div></div>";
                echo "<div class='col-md-8' id='cid-".$tehsil_unique_id."'></div>";
                echo "</div><div class='col-md-12'><hr></div>";
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


    public function get_province_hf_tehsils(Request $request)
    {

        $tehsils = Tehsil::where('province_id', $request->province_id)
                    ->orderBy('tehsil_name')
                    ->get()
                    ->toArray();

        if($tehsils)
        {
            echo "<option value=''>Select Tehsil</option>";
            foreach($tehsils as $tehsil)
            {
                echo "<option value='".$tehsil['tehsil_id']."'>".$tehsil['tehsil_name']."</option>";
            }
        }
        else
        {

        }
        return;
    }

}
