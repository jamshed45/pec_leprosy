<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\AdvanceTourProgramme;
use App\Models\User;
use App\Models\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;

class AdvanvceTourProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('pec-user'))
        {
            // $records = AdvanceTourProgramme::whereMonth('entry_month', '>=', date('m', strtotime(Carbon::now()->addMonth())))
            //     ->orderBy('entry_month', 'desc')
            //     ->where('user_id', $user->id)->get();
            $records = AdvanceTourProgramme::orderBy('entry_month', 'desc')
                ->where('user_id', $user->id)->get();         
        }
        elseif($user->hasRole('pec-data-verifier'))
        {
            $records = AdvanceTourProgramme::where('submit_status', 1)
                    ->orderBy('entry_month', 'DESC')->get();
        }
        else
        {
            $records = AdvanceTourProgramme::where('verify_status', 1)
            ->orderBy('entry_month', 'desc')->get();  
        }


        return view('advance-tour-programme.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $monthly_detail = AdvanceTourProgramme::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::now()->addMonth())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first();
        // print_r($monthly_detail);
        if(!$monthly_detail)
        {
            // echo "not";
            $AdvanceTourProgramme = new AdvanceTourProgramme();
            $AdvanceTourProgramme->user_id = $user->id;
            $AdvanceTourProgramme->entry_month = date('Y-m-d', strtotime(Carbon::now()->addMonth()));
            $AdvanceTourProgramme->save();
        }
        $monthly_detail = AdvanceTourProgramme::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::now()->addMonth())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first()->toArray();    

      
        return view('advance-tour-programme.create', compact('monthly_detail'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();


        if($request->submit_atp_bill)
        {
            AdvanceTourProgramme::where('atp_form_id', $request->atp_form_id)
                    ->update(['submit_status_date' => Carbon::today(), 'submit_status' => 1]);

            return redirect()->route('advance-tour-programme.index')
                    ->with('success','ATP form send for verification successfully');                    
        }
        elseif($request->verify_atp_bill)
        {
            AdvanceTourProgramme::where('atp_form_id', $request->atp_form_id)
                    ->update(['verify_status_date' => Carbon::today(), 'verify_status' => 1]);

            return redirect()->route('advance-tour-programme.index')
                    ->with('success','ATP Form send for verification successfully');                    
        }
        elseif($request->unsubmit_atp_bill)
        {
            AdvanceTourProgramme::where('atp_form_id', $request->atp_form_id)
                    ->update(['submit_status' => 0, 'verify_status' => 0]);

            return redirect()->route('advance-tour-programme.index')
                    ->with('success','ATP Form send for verification successfully');                    
        }
        elseif($request->approve_atp_bill)
        {
            AdvanceTourProgramme::where('atp_form_id', $request->atp_form_id)
                    ->update(['approve_status_date' => Carbon::today(), 'approve_status' => 1]);

            return redirect()->route('advance-tour-programme.index')
                    ->with('success','ATP Form send for verification successfully');                    
        }
        elseif($request->unapprove_atp_bill)
        {
            AdvanceTourProgramme::where('atp_form_id', $request->atp_form_id)
                    ->update(['approve_status' => 0, 'verify_status' => 0]);
                    

            return redirect()->route('advance-tour-programme.index')
                    ->with('success','ATP Form send for verification successfully');                    
        }
        else
        {
            AdvanceTourProgramme::where('user_id', $user->id)
            ->whereMonth('entry_month', date('m', strtotime(Carbon::now()->addMonth())))
            ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
            ->update(['atp_detail' => json_encode($request->atp_detail)]);


            if($request->update_atp_from)
                {
                    return redirect()->route('advance-tour-programme.edit', $request->update_atp_from)
                    ->with('success','ATP information Updated successfully');
                }
                else
                {
                    return redirect()->route('advance-tour-programme.create')
                    ->with('success','ATP information Added successfully');
                }


        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdvanceTourProgramme  $advanceTourProgramme
     * @return \Illuminate\Http\Response
     */
    public function show(AdvanceTourProgramme $advanceTourProgramme)
    {
        $monthly_detail = $advanceTourProgramme;
        return view('advance-tour-programme.show', compact('monthly_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdvanceTourProgramme  $advanceTourProgramme
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvanceTourProgramme $advanceTourProgramme)
    {
        $monthly_detail = $advanceTourProgramme;

        return view('advance-tour-programme.edit', compact('monthly_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdvanceTourProgramme  $advanceTourProgramme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvanceTourProgramme $advanceTourProgramme)
    {

        AdvanceTourProgramme::where('atp_form_id', $request->atp_form_id)
            ->update([
                'atp_detail' => json_encode($request->atp_detail),
                'signature' => $request->signature,
            ]);


        return redirect()->back()->with('success', 'ATP updated successfully');                
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdvanceTourProgramme  $advanceTourProgramme
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvanceTourProgramme $advanceTourProgramme)
    {
        $advanceTourProgramme->delete();

        return redirect()->route('advance-tour-programme.index')
                        ->with('success','Province deleted successfully');
    }


    public function get_reserved_apt_date(Request $request)
    {

        $user_id = $_GET['user_id'];
        $apt_date = $_GET['apt_date'];
        
        $reserved_dates = AdvanceTourProgramme::select('entry_date')
            ->where('user_id', '=', $user_id)
            ->whereMonth('entry_date', '=', date('m', strtotime($apt_date)))
            ->orderBy('entry_date', 'ASC')
            ->get()
            ->toArray();

        echo '<div class="row apt_reserved_date mt-2"><h1 class="col-md-12">Already Added Information Dates</h1>';

            foreach($reserved_dates as $date)
                {
                    echo '<div class="col-md-2"><span>'.$date['entry_date'].'</span></div>';
                }
        
        echo '</div>';    

    }


    public function atp_submit(Request $request)
    {   
        $apt_submit_id = $request->apt_id;
        if($request->apt_submit)
        {
            AdvanceTourProgramme::where('atp_form_id', '=', $apt_submit_id)
                ->update([
                    'submit_status' => 1,
                    'submit_status_date' => date('Y-m-d'),
                ]);
        }

        

    }

    public function generate_pdf(Request $request)
    {

        $monthly_detail = AdvanceTourProgramme::where('atp_form_id', $request->record_id)->first();

        

        $user = User::where('id', $monthly_detail->user_id)->first();
        
        $user_meta = UserMeta::where('user_id', $monthly_detail->user_id)
        ->where('meta_key', 'center_id')
        ->first()->toArray();

        $area_info = DB::table('pec_center')
        ->where('center_id', $user_meta['meta_value'])
        ->join('tehsil', 'pec_center.tehsil_id', '=', 'tehsil.tehsil_id')
        ->join('district', 'pec_center.district_id', '=', 'district.district_id')
        ->join('division', 'pec_center.division_id', '=', 'division.division_id')
        ->join('province', 'pec_center.province_id', '=', 'province.province_id')
        ->orderBy('pec_center.center_name', 'asc')
        ->select('pec_center.*','tehsil.tehsil_name', 'district.district_name', 'division.division_name', 'province.province_name')
        ->first();

        $pdf = PDF::loadView('advance-tour-programme.pdf', compact('monthly_detail', 'user', 'area_info'));

        $pdf_name = 'daily-allowance-'.Str::slug($user->name).'-'.date('F-Y', strtotime($monthly_detail['entry_month']));

        // return view('advance-tour-programme.pdf',compact('monthly_detail', 'user', 'area_info'));
        return $pdf->download($pdf_name.'.pdf');
        die();
        
    }
    
    
}
