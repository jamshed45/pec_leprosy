<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{

    public function index()
    {

        $records = Province::orderBy('province_name', 'asc')
                    ->get();
   
        return view('provinces.index', compact('records'));
    }

 
    public function create()
    {
        return view('provinces.create');
    }

 
    public function store(Request $request)
    {

        $province = Province::where('province_name', $request->input('province_name'))->first();
       
        if($province)        
        {
            return redirect()
            ->route('provinces.create')
            ->with('error', 'Province already exist.');
        }

        Province::create($request->all());
       
        return redirect()->route('provinces.index')
                        ->with('success','Province created successfully.');
    }

  
    public function show(Province $province)
    {
        //
    }

  
    public function edit(Province $province)
    {
        $record = $province;
        return view('provinces.edit',compact('record'));
    }

  
    public function update(Request $request, Province $province)
    {
        
        $province->update($request->all());

        return redirect()->route('provinces.index')
                        ->with('success','Province updated successfully');
    }

 
    public function destroy(Province $province)
    {
        $province->delete();

        return redirect()->route('provinces.index')
                        ->with('error','Province deleted successfully');
    }

}
