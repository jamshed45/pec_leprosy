<?php

namespace App\Http\Controllers;

use App\Models\DailyAllowance;
use App\Models\Division;
use App\Models\Province;
use App\Models\User;
use App\Models\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DailyAllowanceController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        if ($user->hasRole('pec-user'))
        {
            $records = DailyAllowance::orderBy('entry_month', 'desc')
                    ->where('user_id', $user->id)->get();
        }
        elseif($user->hasRole('pec-data-verifier'))
        {
            $records = DailyAllowance::where('submit_status', 1)
                    ->orderBy('entry_month', 'desc')->get();
        }
        else
        {
            $records = DailyAllowance::where('verify_status', 1)
            ->orderBy('entry_month', 'desc')->get();  
        }

        return view('daily-allowance.index', compact('records'));
    }


    public function create()
    {
        $user = Auth::user();

        $monthly_detail = DailyAllowance::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first();
        // print_r($monthly_detail);
        if(!$monthly_detail)
        {
            // echo "not";
            $DailyAllowance = new DailyAllowance();
            $DailyAllowance->user_id = $user->id;
            $DailyAllowance->entry_month = date('Y-m-d', strtotime(Carbon::today()));
            $DailyAllowance->save();
        }
        $monthly_detail = DailyAllowance::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first()->toArray();    

      
        return view('daily-allowance.create', compact('monthly_detail'));

    }


    public function store(Request $request)
    {
        
        $user = Auth::user();

        if($request->submit_da_bill)
        {
            DailyAllowance::where('da_form_id', $request->da_form_id)
                    ->update(['submit_status_date' => Carbon::today(), 'submit_status' => 1]);     

            return redirect()->route('daily-allowance.index')
                    ->with('success','DA Bill sent for verification successfully');                    
        }
        elseif($request->verify_da_bill)
        {
            DailyAllowance::where('da_form_id', $request->da_form_id)
                    ->update(['verify_status_date' => Carbon::today(), 'verify_status' => 1]);

            return redirect()->route('daily-allowance.index')
                    ->with('success','DA Bill send for verification successfully');                    
        }
        elseif($request->unsubmit_da_bill)
        {
            DailyAllowance::where('da_form_id', $request->da_form_id)
                    ->update(['submit_status' => 0, 'verify_status' => 0]);

            return redirect()->route('daily-allowance.index')
                    ->with('success','DA Bill send for verification successfully');                    
        }
        elseif($request->approve_da_bill)
        {
            DailyAllowance::where('da_form_id', $request->da_form_id)
                    ->update(['approve_status_date' => Carbon::today(), 'approve_status' => 1]);

            return redirect()->route('daily-allowance.index')
                    ->with('success','DA Bill send for verification successfully');                    
        }
        elseif($request->unapprove_da_bill)
        {
            DailyAllowance::where('da_form_id', $request->da_form_id)
                    ->update(['approve_status' => 0, 'verify_status' => 0]);

            return redirect()->route('daily-allowance.index')
                    ->with('success','DA Bill send for verification successfully');                    
        }
        else
        {
            DailyAllowance::where('user_id', $user->id)
            ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
            ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
            ->update(['da_detail' => json_encode($request->detail)]);
   
            return redirect()->route('daily-allowance.create')
            ->with('success','DA information Updated successfully');
        }
    
    }


    public function show(DailyAllowance $dailyAllowance)
    {
        $monthly_detail = $dailyAllowance;

        return view('daily-allowance.show',compact('monthly_detail'));
    }


    public function edit(DailyAllowance $dailyAllowance)
    {
        $monthly_detail = $dailyAllowance;
        
        return view('daily-allowance.edit',compact('monthly_detail'));
    }


    public function update(Request $request, DailyAllowance $dailyAllowance)
    {
        // print_r($request->all());
        // die();
        // $dailyAllowance->update($request->all());
        DailyAllowance::where('da_form_id', $request->bill_id)
            ->update([
                'da_detail' => json_encode($request->detail),
                'signature' => $request->signature,
            ]);

        return redirect()->back()->with('success', 'DA information Updated successfull');     
    }


    public function destroy(DailyAllowance $dailyAllowance)
    {
        //
    }

    public function generate_pdf(Request $request)
    {

        $monthly_detail = DailyAllowance::where('da_form_id', $request->da_id)->first();

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
    

        $pdf = PDF::loadView('daily-allowance.pdf', compact('monthly_detail', 'user', 'area_info'));

        $pdf_name = 'daily-allowance-'.Str::slug($user->name).'-'.date('F-Y', strtotime($monthly_detail['entry_month']));

        // return view('daily-allowance.pdf',compact('monthly_detail', 'user', 'area_info'));
        return $pdf->download($pdf_name.'.pdf');
        die();
        
    }
}
