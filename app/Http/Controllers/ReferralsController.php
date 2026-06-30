<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use App\Models\Division;
use App\Models\FormCategories;
use App\Models\Province;
use App\Models\RefferalForm;
use App\Models\Tehsil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReferralsController extends Controller
{
    
    public function index()
    {
        DB::enableQueryLog();
        $all_refferals = DB::table('pec_refferal_forms')
                    ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name')
                    ->join('pec_refferal_form_meta', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                    ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                    ->leftJoin('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                    ->leftJoin('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                    ->where('pec_refferal_form_meta.refer_to', 1)
                    ->orderBy('pec_refferal_forms.created_at', 'desc')
                    ->get();
        // $query = DB::getQueryLog();
        // dd($query);            


        $all_count = DB::table('pec_refferal_forms')
                    // ->where('gender', 'fa')
                    ->count();




        return view('refferals-reports.index', compact('all_refferals'));

    }


    public function all_refferals_by_report(Request $request)
    {
        DB::enableQueryLog();
        $user = Auth::user();

        $referrals = FormCategories::where('parent_slug', 'referrals')
                ->get();

        $activity_location = DB::table('activity_locations')
                ->get();

        $centers = Center::orderBy('center_name', 'asc')->where('user_id', $user->id)->get();
        
        $centers = DB::table('users')
        ->select('pec_center.center_id', 'pec_center.center_name')
        ->join('usermeta', 'users.id', '=', 'usermeta.user_id')
        ->join('pec_center', 'usermeta.meta_value', '=', 'pec_center.center_id')
        ->where('users.id', $user->id)
        ->get();
        
        if($request->all())
        {
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();

            // $query = DB::table('pec_refferal_form_meta')
            // ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
            // ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
            // ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
            // ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
            // ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
            // ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
            // ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
            // ->where('pec_refferal_form_meta.refer_to', 1)
            // ->where('pec_refferal_form_meta.user_id', '=',  $user->id)
            // ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

            $query = DB::table('pec_refferal_forms')
                    ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'pec_refferal_forms.refferal_forms_id as rfid',  'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                    ->join('pec_refferal_form_meta', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                    ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                    ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                    ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                    ->where('pec_refferal_form_meta.refer_to', 1)
                    ->where('pec_refferal_forms.user_id', $user->id)
                    ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

            // echo "<pre>";
            //     print_r($request->all());
            // echo "</pre>";
            // $query = DB::getQueryLog();
            // dd($query); 
            // die();

            if($request->filter_date)
            {
                $query->whereDate('pec_refferal_forms.entry_at', '=' , $request->filter_date);
            }
     
            if($request->searh_month)
            {
                $query->whereMonth('pec_refferal_forms.entry_at', '=' , date('m', strtotime($request->searh_month)));
                $query->whereYear('pec_refferal_forms.entry_at', '=' , date('Y', strtotime($request->searh_month)));
            }

            if($request->activity_location_id)
            {
                $query->where('pec_refferal_forms.activity_location_id', '=' , $request->activity_location_id);
            }     
        
            if($request->center_id)
            {
                $query->where('pec_refferal_forms.center_id', '=' , $request->center_id);
            }

            if($request->year_qauterly && $request->searh_qauterly)
            {
                if($request->searh_qauterly == '1')
                {
                    $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 1);
                    $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 3);
                    $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
                }
                elseif($request->searh_qauterly == '2')
                {
                    $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 4);
                    $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 6);
                    $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
                }
                elseif($request->searh_qauterly == '3')
                {
                    $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 7);
                    $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 9);
                    $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
                }
                elseif($request->searh_qauterly == '4')
                {
                    $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 10);
                    $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 12);
                    $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
                }
            }

            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['user_id'] = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id'] = $request->center_id;
            $search_field_info['filter_date'] = $request->filter_date;
            $search_field_info['searh_month'] = $request->searh_month;
            $search_field_info['searh_qauterly'] = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly'] = $request->year_qauterly;
            $search_field_info['search_status'] = true;

            $all_refferals = $query->get();

            // echo $user->id;
            // print_r($request->all());
            // echo "<pre>";
            //     print_r($all_refferals);
            // echo "</pre>";
            // $query = DB::getQueryLog();
            // dd($query); 
            // die();

        }
        else
        {
            $search_field_info = array();
            $search_field_info['user_id'] = $user->id;
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id'] = '';
            $search_field_info['filter_date'] = '';
            $search_field_info['monthly'] = '';
            $search_field_info['filter_cat_slug'] = '';
            $search_field_info['searh_year_qauterly'] = '';
            $search_field_info['searh_qauterly'] = '';
            $search_field_info['searh_month'] = '';
            $search_field_info['referral_id'] = '';
            $search_field_info['search_status'] = false;
            $all_refferals = array();


        }
        

        return view('refferals-reports.all-refferals', compact( 'referrals','all_refferals' , 'activity_location', 'search_field_info', 'centers'));
    }


    public function all_refferals_by_report_pdf(Request $request)
    {
        $user = Auth::user();
        
        $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->cat_slug)
        ->first()->toArray();

        $query = DB::table('pec_refferal_forms')
        ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'pec_refferal_forms.refferal_forms_id as rfid',  'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
        ->join('pec_refferal_form_meta', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
        ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
        ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
        ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
        ->where('pec_refferal_form_meta.refer_to', 1)
        ->where('pec_refferal_forms.user_id', $user->id)
        ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

        if($request->filter_date)
        {
            $query->whereDate('pec_refferal_forms.entry_at', '=' , $request->filter_date);
        }
 
        if($request->searh_month)
        {
            $query->whereMonth('pec_refferal_forms.entry_at', '=' , date('m', strtotime($request->searh_month)));
            $query->whereYear('pec_refferal_forms.entry_at', '=' , date('Y', strtotime($request->searh_month)));
        }

        if($request->activity_location_id)
        {
            $query->where('pec_refferal_forms.activity_location_id', '=' , $request->activity_location_id);
        }     
    
        if($request->center_id)
        {
            $query->where('pec_refferal_forms.center_id', '=' , $request->center_id);
        }

        if($request->year_qauterly && $request->searh_qauterly)
        {
            if($request->searh_qauterly == '1')
            {
                $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 1);
                $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 3);
                $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
            }
            elseif($request->searh_qauterly == '2')
            {
                $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 4);
                $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 6);
                $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
            }
            elseif($request->searh_qauterly == '3')
            {
                $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 7);
                $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 9);
                $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
            }
            elseif($request->searh_qauterly == '4')
            {
                $query->whereMonth('pec_refferal_forms.entry_at', '>=' , 10);
                $query->whereMonth('pec_refferal_forms.entry_at', '<=' , 12);
                $query->whereYear('pec_refferal_forms.entry_at', '=' , $request->year_qauterly);
            }
        }

        $all_refferals = $query->get();

        
        // return view('refferals-reports.refferals_patients_report_pdf', compact('all_refferals'));

        
        $pdf = PDF::loadView('refferals-reports.refferals_patients_report_pdf', compact('all_refferals'));

        $pdf_name = 'refferal-patients';

        // if($search_field_info['user_name'])
        // {
        //     $pdf_name = $pdf_name.'-'.Str::slug($search_field_info['user_name']);
        // }

        // if($search_field_info['activity_name'])
        // {
        //     $pdf_name = $pdf_name.'-'.Str::slug($search_field_info['activity_name']);
        // }

        // if($search_field_info['center_name'])
        // {
        //     $pdf_name = $pdf_name.'-'.Str::slug($search_field_info['center_name']);
        // }

        // if($search_field_info['filter_date'])
        // {
        //     $pdf_name = $pdf_name.'-'.$search_field_info['filter_date'];
        // }

        // if($search_field_info['filter_cat_slug'])
        // {
        //     $pdf_name = $pdf_name.'-'.$search_field_info['filter_cat_slug'];
        //}

        $pdf_name = $pdf_name.'-'.'date-'.date('Y-m-d-h-i-s-A');

        // return view('patient-entries.all_patients_entries_by_report_pdf',compact('patient_entries', 'form_categories', 'search_field_info'));
        return $pdf->download($pdf_name.'.pdf');
        die();


    }


    public function referrals_reports_by_user(Request $request)
    {
        $users = User::orderBy('name', 'asc')->get();

        $referral = FormCategories::where('parent_slug', 'referrals')
                    ->get();

        $activity_location = DB::table('activity_locations')
              ->get();

        
        if($request->all())
        {
            
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();
                DB::enableQueryLog();   
                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.user_id', '=',  $request->user_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['user_id'] = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id'] = $request->center_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();
            // echo "<pre>";
            // print_r($all_refferals);
            // echo "</pre>";
            $query = DB::getQueryLog();
            // dd($query);
            // die();
  
    
        }     
        else
        {
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['user_id'] = '';
        $search_field_info['activity_location_id'] = '';
        $search_field_info['center_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();

        }

        
        return view('refferals-reports.refferal_by_user', compact('users', 'referral','all_refferals' , 'activity_location', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }


    public function referrals_reports_by_center(Request $request)
    {
        $centers = Center::orderBy('center_name', 'asc')->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
                    ->get();


        
        if($request->all())
        {
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();

                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.center_id', '=',  $request->center_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['center_id'] = $request->center_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();

    
        }     
        else
        {
 
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['center_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();
        }

        
        return view('refferals-reports.refferal_by_center', compact('centers', 'referral','all_refferals', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }


    public function referrals_reports_by_location(Request $request)
    {
        $activity_location = DB::table('activity_locations')
              ->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
                    ->get();


        
        if($request->all())
        {
            
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();

                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.activity_location_id', '=',  $request->activity_location_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['center_id'] = $request->center_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();

    
        }     
        else
        {
 
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['activity_location_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();
        }

        
        return view('refferals-reports.refferal_by_location', compact('activity_location', 'referral','all_refferals', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }


    public function referrals_reports_by_tehsil(Request $request)
    {
        $teshils = Tehsil::orderBy('tehsil_name', 'asc')->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
                    ->get();

        
        if($request->all())
        {
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();
     
                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.tehsil_id', '=',  $request->tehsil_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['tehsil_id'] = $request->tehsil_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();

    
        }     
        else
        {
 
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['tehsil_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();
        }

        
        return view('refferals-reports.refferal_by_tehsil', compact('teshils', 'referral','all_refferals', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }


    public function referrals_reports_by_district(Request $request)
    {
        $districts = District::orderBy('district_name', 'asc')->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
                    ->get();


        if($request->all())
        {
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();

                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.district_id', '=',  $request->district_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['district_id'] = $request->district_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();

    
        }     
        else
        {
 
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['district_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();
        }

        
        return view('refferals-reports.refferal_by_district', compact('districts', 'referral','all_refferals', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }


    public function referrals_reports_by_division(Request $request)
    {
        $divisions = Division::orderBy('division_name', 'asc')->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
                    ->get();
        
        if($request->all())
        {
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();

                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.division_id', '=',  $request->division_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['division_id'] = $request->division_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();

    
        }     
        else
        {
 
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['division_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();
        }

        
        return view('refferals-reports.refferal_by_division', compact('divisions', 'referral','all_refferals', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }



    public function referrals_reports_by_province(Request $request)
    {
        $provinces = Province::orderBy('province_name', 'asc')->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
                    ->get();
   
        
        if($request->all())
        {
            $get_refferal_slug = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first()->toArray();

                $query = DB::table('pec_refferal_form_meta')
                ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name', 'users.name as user_name', 'pec_center.center_name as center_name', 'activity_locations.activity_name as activity_name')
                ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->join('activity_locations', 'activity_locations.activity_location_id', '=', 'pec_refferal_forms.activity_location_id')
                ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                ->where('pec_refferal_form_meta.refer_to', 1)
                ->where('pec_refferal_form_meta.province_id', '=',  $request->province_id)
                ->where('pec_refferal_form_meta.patient_type', '=',  $get_refferal_slug['slug']);

                if(isset($request->monthly_or_yearly))
                {
                    $get_month_year =  date('m', strtotime($request->monthly_yearly));
                    $query->whereMonth('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                    
                }
                else
                {
                    $get_month_year =  date('Y', strtotime($request->monthly_yearly));
                    $query->whereYear('pec_refferal_form_meta.entry_at', '=' , $get_month_year);
                }


            if($request->activity_location_id)
                {
                    $query->where('pec_refferal_form_meta.activity_location_id', '=' , $request->activity_location_id);
                }     
            
            if($request->center_id)
                {
                    $query->where('pec_refferal_form_meta.center_id', '=' , $request->center_id);
                } 

            
            // die();
            
            $search_field_info['referral_id'] = $request->referrals;    
            $search_field_info['province_id'] = $request->province_id;
            $search_field_info['select_date'] = $request->monthly_yearly;
            $search_field_info['monthly'] = $request->monthly_or_yearly;
            $search_field_info['search_status'] = true;

            $sub_filter_info = $request->sub_filter_info;

            $my_centers = DB::table('usermeta')
                        ->where('user_id', $request->user_id)
                        ->where('meta_key','center_id')
                        ->pluck('meta_value')
                        ->toArray();

            if(isset($request->activity_location_id))
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->where('activity_location_id', $request->activity_location_id)
                            ->orderBy('center_name')
                            ->get();
            }
            else
            {
                $filter_centers = Center::wherein('center_id', $my_centers)
                            ->orderBy('center_name')
                            ->get();
            }

            $all_refferals = $query->get();

    
        }     
        else
        {
 
        
        $search_field_info['referral_id'] = '';    
        $search_field_info['province_id'] = '';
        $search_field_info['select_date'] = '';
        $search_field_info['monthly'] = '';
        $search_field_info['search_status'] = false;

        $sub_filter_info = '';

        $filter_centers = array();
        $all_refferals = array();
        }

        
        return view('refferals-reports.refferal_by_province', compact('provinces', 'referral','all_refferals', 'search_field_info', 'filter_centers', 'sub_filter_info'));
    }


}
