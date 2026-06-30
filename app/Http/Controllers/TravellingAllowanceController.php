<?php

namespace App\Http\Controllers;

use App\Models\TravellingAllowance;
use App\Models\User;
use App\Models\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TravellingAllowanceController extends Controller
{
   

    public function index()
    {
  
        $user = Auth::user();

        if ($user->hasRole('pec-user'))
        {
            $records = TravellingAllowance::orderBy('entry_month', 'desc')
                    ->where('user_id', $user->id)->get();
        }
        elseif($user->hasRole('pec-data-verifier'))
        {
            $records = TravellingAllowance::where('submit_status', 1)
                    ->orderBy('entry_month', 'desc')->get();
        }
        else
        {
            $records = TravellingAllowance::where('verify_status', 1)
            ->orderBy('entry_month', 'desc')->get();  
        }
        
        return view('travelling-allowance.index', compact('records'));
    }


    public function create()
    {
        $user = Auth::user();

        $monthly_detail = TravellingAllowance::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first();
        // print_r($monthly_detail);
        if(!$monthly_detail)
        {
            // echo "not";
            $TravellingAllowance = new TravellingAllowance();
            $TravellingAllowance->user_id = $user->id;
            $TravellingAllowance->entry_month = date('Y-m-d', strtotime(Carbon::today()));
            $TravellingAllowance->save();
        }
        $monthly_detail = TravellingAllowance::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first()->toArray();    

      
        return view('travelling-allowance.create', compact('monthly_detail'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();



        if($request->submit_ta_bill)
        {
            TravellingAllowance::where('ta_form_id', $request->ta_form_id)
                    ->update(['submit_status_date' => Carbon::today(), 'submit_status' => 1]);

            return redirect()->route('travelling-allowance.index')
                    ->with('success','TA Bill send for verification successfully');                    
        }
        elseif($request->verify_ta_bill)
        {
            TravellingAllowance::where('ta_form_id', $request->ta_form_id)
                    ->update(['verify_status_date' => Carbon::today(), 'verify_status' => 1]);

            return redirect()->route('travelling-allowance.index')
                    ->with('success','TA Bill send for verification successfully');                    
        }
        elseif($request->unsubmit_ta_bill)
        {
            TravellingAllowance::where('ta_form_id', $request->ta_form_id)
                    ->update(['send_status' => 0, 'verify_status' => 0]);

            return redirect()->route('travelling-allowance.index')
                    ->with('success','TA Bill send for verification successfully');                    
        }
        elseif($request->approve_ta_bill)
        {
            TravellingAllowance::where('ta_form_id', $request->ta_form_id)
                    ->update(['approve_status_date' => Carbon::today(), 'approve_status' => 1]);

            return redirect()->route('travelling-allowance.index')
                    ->with('success','TA Bill send for verification successfully');                    
        }
        elseif($request->unapprove_ta_bill)
        {
            TravellingAllowance::where('ta_form_id', $request->ta_form_id)
                    ->update(['approve_status' => 0, 'verify_status' => 0]);

            return redirect()->route('travelling-allowance.index')
                    ->with('success','TA Bill send for verification successfully');                    
        }
        else
        {

            $ta_dates = $request->detail['date'];
    
            asort($ta_dates);

            foreach($ta_dates as $key => $value)
            {
                $unique_id = uniqid();

                $detail['date'][$unique_id] = $value;
                $detail['description'][$unique_id] = $request->detail['description'][$key];
                $detail['amount'][$unique_id] = $request->detail['amount'][$key];
            }
       
            TravellingAllowance::where('user_id', $user->id)
            ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
            ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
            ->update(['ta_detail' => json_encode($detail)]);
  
            return redirect()->route('travelling-allowance.create')
            ->with('success','TA information Updated successfully');
        }
        

        die();
    }


    public function show(TravellingAllowance $travellingAllowance)
    {
        $monthly_detail = $travellingAllowance;
        
        return view('travelling-allowance.show',compact('monthly_detail'));
    }


    public function edit(TravellingAllowance $travellingAllowance)
    {
        $monthly_detail = $travellingAllowance;

        return view('travelling-allowance.edit',compact('monthly_detail'));
    }


    public function update(Request $request, TravellingAllowance $travellingAllowance)
    {

        $travellingAllowance->update($request->all());

        TravellingAllowance::where('ta_form_id', $request->bill_id)
            ->update([
                'ta_detail' => json_encode($request->detail),
                'signature' => $request->signature,
            ]);

        return redirect()->back()->with('success', 'TA information Updated successfull');                
    }


    public function destroy(TravellingAllowance $travellingAllowance)
    {
        //
    }

    public function generate_pdf(Request $request)
    {
        $monthly_detail = TravellingAllowance::where('ta_form_id', $request->record_id)->first();

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


        $pdf = PDF::loadView('travelling-allowance.pdf', compact('monthly_detail', 'user', 'area_info'));

        $user = User::where('id', $monthly_detail->user_id)->first();

        $pdf_name = 'daily-allowance-'.Str::slug($user->name).'-'.date('F-Y', strtotime($monthly_detail['entry_month']));

        // return view('travelling-allowance.pdf',compact('monthly_detail', 'user', 'area_info'));
        return $pdf->download($pdf_name.'.pdf');
        die();
        
    }
}
