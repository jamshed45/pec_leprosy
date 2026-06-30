<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Province;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    public function index()
    {

        $records = Division::orderBy('division_name', 'asc')->get();
   
        return view('divisions.index', compact('records'));
    }

 
    public function create()
    {
        
        $province = Province::get();

        return view('divisions.create', compact('province'));
    }

 
    public function store(Request $request)
    {
        
        $division = Division::where('division_name', $request->input('division_name'))
                            ->where('province_id', $request->input('province_id'))                        
                            ->first();

        if($division)        
        {
            return redirect()
            ->route('divisions.create')
            ->with('error', 'Division already exist.');
        }


        Division::create($request->all());
       
        return redirect()->route('divisions.index')
                        ->with('success','Division created successfully.');
    }

  
    public function show(Division $division)
    {
        //
    }

  
    public function edit(Division $division)
    {
        $record = $division;
        $province = Province::get();
     
        return view('divisions.edit', compact('record', 'province'));
    }

  
    public function update(Request $request, Division $division)
    {

        $division->update($request->all());

        return redirect()->route('divisions.index')
                        ->with('success','Division updated successfully');
    }

 
    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('divisions.index')
                        ->with('error','Division deleted successfully');
    }

}
