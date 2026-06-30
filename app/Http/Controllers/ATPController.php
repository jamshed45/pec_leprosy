<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Center;
use App\Models\ATPModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ATPController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $records = ATPModel::where('user_id', '=', $user->id)
            ->where('entry_month', '!=', '')
            ->orderBy('entry_month', 'DESC')
            ->get();

        return view('advance-tour-programme.index', compact('records'));
    }


    public function create()
    {
        $user = Auth::user();

        $my_centers = DB::table('usermeta')
        ->where('user_id', $user->id)
        ->where('meta_key','center_id')
        ->pluck('meta_value')
        ->toArray();

        $centers = DB::table('pec_center')
        ->select('pec_center.*')
        ->wherein('pec_center.center_id', $my_centers)
        ->get();

        $monthly_detail = ATPModel::where('user_id', $user->id)
                ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
                ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
                ->first();
        // print_r($monthly_detail);
        if(!$monthly_detail)
        {
            // echo "not";
            $DailyAllowance = new ATPModel();
            $DailyAllowance->user_id = $user->id;
            $DailyAllowance->entry_month = date('Y-m-d', strtotime(Carbon::today()));
            $DailyAllowance->save();
        }

        $monthly_detail = ATPModel::where('user_id', $user->id)
        ->whereMonth('entry_month', date('m', strtotime(Carbon::today())))
        ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))
        ->first()->toArray();

        die();
        
        return view('advance-tour-programme.create', compact('centers', 'monthly_detail'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $center = Center::where('center_id', $request->center_id)
        ->first();

        $get_month = ATPModel::where('entry_month', date('Y-m', strtotime($request->entry_date)))
                ->get()
                ->toArray();


        if(empty($get_month))
        {
            $apt = new ATPModel();
            $apt->user_id = $user->id;
            // $apt->activity_location_id = $center->activity_location_id;
            $apt->center_id = $center->center_id;
            $apt->province_id = $center->province_id;
            $apt->division_id = $center->division_id;
            $apt->district_id = $center->district_id;
            $apt->tehsil_id = $center->tehsil_id;
            $apt->entry_month = date('Y-m', strtotime($request->entry_date));
            $apt->save();
 
            echo $month_id = $apt->atp_form_id;

        }
        else
        {
            echo $month_id = $get_month[0]['atp_form_id'];
        }

            $apt = new ATP();
            $apt->user_id = $user->id;
            $apt->month_id = $month_id;
            // $apt->activity_location_id = $center->activity_location_id;
            $apt->center_id = $center->center_id;
            $apt->province_id = $center->province_id;
            $apt->division_id = $center->division_id;
            $apt->district_id = $center->district_id;
            $apt->tehsil_id = $center->tehsil_id;
            $apt->atp_detail = json_encode($request->atp_detail);
            $apt->entry_date = $request->entry_date;
            $apt->save();



        return redirect()->route('advance-tour-programme.create')
                        ->with('success','Center updated successfully');

    }


    public function show(ATPModel $atpmodel)
    {
        $monthly_detail = $dailyAllowance;

        return view('advance-tour-programme.show',compact('monthly_detail'));
    }


    public function edit(Request $request, ATPModel $atpmodel)
    {
        print_r($request->all());
        die();
        // $apt_daily = ATP::where('atp_form', );
  

        return view('advance-tour-programme.edit', compact('apt_daily'));
    }


    public function update(Request $request, ATPModel $atpmodel)
    {


        // $dailyAllowance->update($request->all());

        // DailyAllowance::where('da_form_id', $request->bill_id)
        //     ->update(['da_detail' => json_encode($request->detail)]);

        return redirect()->route('advance-tour-programme.edit', $request->bill_id)
                        ->with('success','Center updated successfully');
    }


    public function destroy(ATPModel $atpmodel)
    {
        //
    }

    public function get_reserved_apt_date(Request $request)
    {

        $user_id = $_GET['user_id'];
        $apt_date = $_GET['apt_date'];

        $reserved_dates = ATP::select('entry_date')
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

    
}
