<?php

namespace App\Http\Controllers;

use App\Models\TourDairy;
use App\Models\User;
use App\Models\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TourDairyController extends Controller
{

    public function index()
    {

        $user = Auth::user();

        if ($user->hasRole('pec-user'))
        {
            $records = TourDairy::orderBy('entry_month', 'desc')
                    ->where('user_id', $user->id)->get();
        }
        elseif($user->hasRole('pec-data-verifier'))
        {
            $records = TourDairy::where('submit_status', 1)
                    ->orderBy('entry_month', 'desc')->get();
        }
        else
        {
            $records = TourDairy::where('verify_status', 1)
            ->orderBy('entry_month', 'desc')->get();
        }

        return view('tour-dairy.index', compact('records'));
    }


    public function create()
    {
        $user = Auth::user();

        $monthly_detail = TourDairy::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first();
        // print_r($monthly_detail);
        if(!$monthly_detail)
        {
            // echo "not";
            $TourDairy = new TourDairy();
            $TourDairy->user_id = $user->id;
            $TourDairy->entry_month = date('Y-m-d', strtotime(Carbon::today()));
            $TourDairy->save();
        }
        $monthly_detail = TourDairy::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first()->toArray();



        return view('tour-dairy.create', compact('monthly_detail'));

    }


    public function store(Request $request)
    {

        $user = Auth::user();

        if($request->submit_da_bill)
        {
            TourDairy::where('td_form_id', $request->td_form_id)
                    ->update(['submit_status_date' => Carbon::today(), 'submit_status' => 1]);

            return redirect()->route('tour-dairy.index')
                    ->with('success','Tour Dairy sent for verification successfully');
        }
        elseif($request->verify_da_bill)
        {
            TourDairy::where('td_form_id', $request->td_form_id)
                    ->update(['verify_status_date' => Carbon::today(), 'verify_status' => 1]);

            return redirect()->route('tour-dairy.index')
                    ->with('success','Tour Dairy send for verification successfully');
        }
        elseif($request->unsubmit_da_bill)
        {
            TourDairy::where('td_form_id', $request->td_form_id)
                    ->update(['submit_status' => 0, 'verify_status' => 0]);

            return redirect()->route('tour-dairy.index')
                    ->with('success','Tour Dairy send for verification successfully');
        }
        elseif($request->approve_da_bill)
        {
            TourDairy::where('td_form_id', $request->td_form_id)
                    ->update(['approve_status_date' => Carbon::today(), 'approve_status' => 1]);

            return redirect()->route('tour-dairy.index')
                    ->with('success','Tour Dairy send for verification successfully');
        }
        elseif($request->unapprove_da_bill)
        {
            TourDairy::where('td_form_id', $request->td_form_id)
                    ->update(['approve_status' => 0, 'verify_status' => 0]);

            return redirect()->route('tour-dairy.index')
                    ->with('success','Tour Dairy send for verification successfully');
        }
        else
        {
            TourDairy::where('user_id', $user->id)
            ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
            ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
            ->update(['td_detail' => json_encode($request->detail)]);

            return redirect()->route('tour-dairy.create')
            ->with('success','Tour Dairy information Updated successfully');
        }

    }


    public function show(TourDairy $TourDairy)
    {
        $monthly_detail = $TourDairy;

        return view('tour-dairy.show',compact('monthly_detail'));
    }


    public function edit(TourDairy $TourDairy)
    {
        $monthly_detail = $TourDairy;

        return view('tour-dairy.edit',compact('monthly_detail'));
    }


    public function update(Request $request, TourDairy $TourDairy)
    {
        // print_r($request->all());
        // die();
        // $TourDairy->update($request->all());
        TourDairy::where('td_form_id', $request->bill_id)
            ->update([
                'td_detail' => json_encode($request->detail),
                'signature' => $request->signature,
            ]);

        return redirect()->back()->with('success', 'DA information Updated successfull');
    }


    public function destroy(TourDairy $TourDairy)
    {
        //
    }

    public function generate_pdf(Request $request)
    {

        $monthly_detail = TourDairy::where('td_form_id', $request->da_id)->first();

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


        $pdf = PDF::loadview('tour-dairy.pdf', compact('monthly_detail', 'user', 'area_info'));

        $pdf_name = 'tour-dairy-'.Str::slug($user->name).'-'.date('F-Y', strtotime($monthly_detail['entry_month']));

        // return view('tour-dairy.pdf',compact('monthly_detail', 'user', 'area_info'));
        return $pdf->download($pdf_name.'.pdf');
        die();

    }
}
