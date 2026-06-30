<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use App\Models\Division;
use App\Models\FormCategories;
use App\Models\Province;
use App\Models\Tehsil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfirmedReferralsController extends Controller
{
    
    public function index()
    {
        $all_refferals = DB::table('pec_refferal_form_meta')
                    ->select('pec_refferal_form_meta.*', 'pec_refferal_forms.*', 'doctors.doctor_name as doc_name', 'health_facilities.health_facility_name as health_facility_name')
                    ->join('pec_refferal_forms', 'pec_refferal_form_meta.refferal_forms_id', '=', 'pec_refferal_forms.refferal_forms_id')
                    ->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                    ->join('doctors', 'doctors.doctor_id', '=', 'pec_refferal_form_meta.doctor_id')
                    ->join('health_facilities', 'health_facilities.health_facility_id', '=', 'pec_refferal_form_meta.health_facility_id')
                    ->where('pec_refferal_form_meta.operated_by', 1)
                    ->get();
        

        return view('refferals-reports.index', compact('all_refferals'));

    }

    public function confirmed_referrals_reports_by_user(Request $request)
    {
        $users = User::orderBy('name', 'asc')->get();

        $referral =  FormCategories::where('parent_slug', 'referrals')
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


    public function confirmed_referrals_reports_by_center(Request $request)
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


    public function confirmed_referrals_reports_by_location(Request $request)
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


    public function confirmed_referrals_reports_by_tehsil(Request $request)
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


    public function confirmed_referrals_reports_by_district(Request $request)
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


    public function confirmed_referrals_reports_by_division(Request $request)
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



    public function confirmed_referrals_reports_by_province(Request $request)
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
