<?php

namespace App\Http\Controllers;

use App\Models\RefferalForm;
use Illuminate\Http\Request;
use App\Http\Controllers\View;
use App\Http\Traits\GlobalTrait;
use App\Models\Diabetic;
use App\Models\FormCategories;
use App\Models\HeathFacility;
use App\Models\PatientEntry;
use App\Models\ReferBy;
use App\Models\Referral;
use App\Models\RefferalFormMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class RefferalsFormController extends Controller
{
    
    public function __construct()
    {
            $this->middleware('auth');
    }




    public function index()
    {

        $user = Auth::user();

        if($user->hasRole('pec-user'))
        {
        $patients = RefferalForm::where('user_id', '=',  $user->id)
                    ->orderBy('entry_at', 'desc')    
                    ->get();

        }
        else
        {
            $patients = RefferalForm::get();
        }

        return view('refferal-forms.index',compact('patients'));
    }


    public function create()
    {   

        $referral = FormCategories::where('parent_slug', 'referrals')
                    ->get();
          
        $referby = FormCategories::where('parent_slug', 'refer-by')
                    ->get();

        return view('refferal-forms.create', compact('referral', 'referby'));
    }

    public function add_old_refferal_patient()
    {   

        $referral = FormCategories::where('parent_slug', 'referrals')
                    ->get();
          
        $referby = FormCategories::where('parent_slug', 'refer-by')
                    ->get();

        return view('refferal-forms.add-old-refferal-patient', compact('referral', 'referby'));
    }

   
    public function store(Request $request)
    {

        $center_session = session()->get('center_info');

        $user = Auth::user();
          
        $gender_loc = $request->gender;
        
        $RefferalForm = new RefferalForm();
        $RefferalForm->user_id = $user->id;
        $RefferalForm->activity_location_id = $center_session['activity_location_id'];
        $RefferalForm->province_id = $center_session['province_id'];
        $RefferalForm->division_id = $center_session['division_id'];
        $RefferalForm->district_id = $center_session['district_id'];
        $RefferalForm->tehsil_id = $center_session['tehsil_id'];
        $RefferalForm->center_id = $center_session['center_id'];
        $RefferalForm->full_name = $request->full_name;
        $RefferalForm->relation = $request->relation;
        $RefferalForm->relative_name = $request->relative_name;
        $RefferalForm->cnic_number = $request->cnic_number;
        $RefferalForm->contact_number = $request->contact_number;
        $RefferalForm->gender = $request->gender;
        $RefferalForm->age = $request->age;
        $RefferalForm->address = $request->address;
        $RefferalForm->cat_book_number = $request->cat_book_number;
        $RefferalForm->refer_by = $request->refer_by;
        $RefferalForm->referrals = $request->referrals;
        $RefferalForm->status = '1';
        $RefferalForm->entry_at = $center_session['entry_date'];
        $RefferalForm->save();

        $referal_form  = FormCategories::where('pec_form_cat_id', $request->referrals)
                        ->first();

                            
        $RefferalFormMeta = new RefferalFormMeta();

        print_r($RefferalFormMeta);
        
        

        $RefferalFormMeta->refferal_forms_id = $RefferalForm->refferal_forms_id;;
        $RefferalFormMeta->user_id = $user->id;
        $RefferalFormMeta->activity_location_id = $center_session['activity_location_id'];
        $RefferalFormMeta->province_id =$center_session['province_id'];
        $RefferalFormMeta->division_id = $center_session['division_id'];
        $RefferalFormMeta->district_id = $center_session['district_id'];
        $RefferalFormMeta->tehsil_id = $center_session['tehsil_id'];
        $RefferalFormMeta->center_id = $center_session['center_id'];
        $RefferalFormMeta->patient_type = $referal_form->slug;
        $RefferalFormMeta->cataract_op_slug = $request->cataract_op_slug;
        $RefferalFormMeta->entry_at = $center_session['entry_date'];
        $RefferalFormMeta->remark = $request->remark;

        if($referal_form->slug == 'cataract-surgery')
        {
            $RefferalFormMeta->diabetic = $request->diabetic;
            $RefferalFormMeta->diabetic_value = $request->diabetic_value;
        }
        
        if(isset($request->operation_private) && $request->operation_private == 'private')
            {
                $RefferalFormMeta->private = 1;
            }
            else
            {
                $RefferalFormMeta->doctor_id = $request->doctor_id;
                $RefferalFormMeta->health_facility_id = $request->health_facility_id;
            }
        
        $RefferalFormMeta->refer_to = 1;
        $RefferalFormMeta->save();

        

        $refer_form  = FormCategories::where('pec_form_cat_id', $request->refer_by)
                        ->first();

        $key_exist = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                        ->where('records_set_unique_id', '!=', '')
                        ->where('user_id', '=',  $user->id)
                        ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                        ->where('province_id', '=',  $center_session['province_id'])
                        ->where('division_id', '=',  $center_session['division_id'])
                        ->where('district_id', '=',  $center_session['district_id'])
                        ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                        ->where('center_id', '=',  $center_session['center_id'])
                        ->first();


        if(!$key_exist)
        {
            $unique_id = hexdec(uniqid().rand(10,100));
            $PatientEntry = new PatientEntry();
            $PatientEntry->user_id = $user->id;
            $PatientEntry->activity_location_id = $center_session['activity_location_id'];
            $PatientEntry->province_id = $center_session['province_id'];
            $PatientEntry->division_id = $center_session['division_id'];
            $PatientEntry->district_id = $center_session['district_id'];
            $PatientEntry->tehsil_id = $center_session['tehsil_id'];
            $PatientEntry->center_id = $center_session['center_id'];

            $PatientEntry->records_set_unique_id = $unique_id;

            $PatientEntry->patient_type = $refer_form->slug;

            $PatientEntry->ma = $request->gender=='ma'?1:0;
            $PatientEntry->fa = $request->gender=='fa'?1:0;
            $PatientEntry->mc = $request->gender=='mc'?1:0;
            $PatientEntry->fc = $request->gender=='fc'?1:0;
    
            $PatientEntry->entry_at = date('Y-m-d');
            $PatientEntry->save();

            
        }
        else
        {
            $refer_by_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  $refer_form->slug)
                ->first();
            // echo "<pre>";
            // print_r($refer_by_rec);
            // echo "</pre>";
            if(!$refer_by_rec)
            {
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->division_id = $center_session['division_id'];
                $PatientEntry->district_id = $center_session['district_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                $PatientEntry->center_id = $center_session['center_id'];
                
                $PatientEntry->records_set_unique_id = '';
    
                $PatientEntry->patient_type = $refer_form->slug;

                $PatientEntry->ma = $request->gender=='ma'?1:0;
                $PatientEntry->fa = $request->gender=='fa'?1:0;
                $PatientEntry->mc = $request->gender=='mc'?1:0;
                $PatientEntry->fc = $request->gender=='fc'?1:0;

        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {                   
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $refer_by_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }

        }


        if($referal_form->slug == 'cataract-surgery' || $referal_form->slug == 'trichiasis-surgery' || $referal_form->slug == 'glaucoma-surgery')
        {
            $referal_form_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
            ->where('user_id', '=',  $user->id)
            ->where('activity_location_id', '=',  $center_session['activity_location_id'])
            ->where('province_id', '=',  $center_session['province_id'])
            ->where('division_id', '=',  $center_session['division_id'])
            ->where('district_id', '=',  $center_session['district_id'])
            ->where('tehsil_id', '=',  $center_session['tehsil_id'])
            ->where('center_id', '=',  $center_session['center_id'])
            ->where('patient_type', '=',  $referal_form->slug)
            ->first();
            if(!$referal_form_rec)
            {
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->division_id = $center_session['division_id'];
                $PatientEntry->district_id = $center_session['district_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                $PatientEntry->center_id = $center_session['center_id'];
    
                $PatientEntry->records_set_unique_id = '';
    
                $PatientEntry->patient_type = $referal_form->slug;

                $PatientEntry->ma = $request->gender=='ma'?1:0;
                $PatientEntry->fa = $request->gender=='fa'?1:0;
                $PatientEntry->mc = $request->gender=='mc'?1:0;
                $PatientEntry->fc = $request->gender=='fc'?1:0;
        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $referal_form_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }


        }

        if(isset($request->diabetic))
        {
            if($request->diabetic == 1)
            {
                $diabetic_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'diabetic')
                ->first();
                if(!$diabetic_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
        
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'diabetic';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;

            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $diabetic_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
            }
            else
            {

                $non_diabetic_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'non-diabetic')
                ->first();

                if(!$non_diabetic_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
        
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'non-diabetic';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $non_diabetic_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
                
            }

        }
        
        echo $request->cataract_op_slug;

        if(isset($request->cataract_op_slug))
        {
            if($request->cataract_op_slug == 'cataract-left-eye')
            {
                $cataract_one_left_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'cataract-left-eye')
                ->first();

                if(!$cataract_one_left_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
        
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'cataract-left-eye';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_one_left_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
            }
            elseif ($request->cataract_op_slug == 'cataract-right-eye')
            {

                $cataract_right_eye_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'cataract-right-eye')
                ->first();

                if(!$cataract_right_eye_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'cataract-right-eye';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_right_eye_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
                
            }
            else
            {

                $cataract_both_eyes_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'cataract-both-eyes')
                ->first();

                if(!$cataract_both_eyes_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'cataract-both-eyes';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_both_eyes_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
                
            }

        }
        
        
        if($referal_form->slug == 'glaucoma-surgery')
        {
            $glaucoma_patients_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
            ->where('user_id', '=',  $user->id)
            ->where('activity_location_id', '=',  $center_session['activity_location_id'])
            ->where('province_id', '=',  $center_session['province_id'])
            ->where('division_id', '=',  $center_session['division_id'])
            ->where('district_id', '=',  $center_session['district_id'])
            ->where('tehsil_id', '=',  $center_session['tehsil_id'])
            ->where('center_id', '=',  $center_session['center_id'])
            ->where('patient_type', '=',  'glaucoma-patients')
            ->first();

            if(!$glaucoma_patients_rec)
            {
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->division_id = $center_session['division_id'];
                $PatientEntry->district_id = $center_session['district_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                $PatientEntry->center_id = $center_session['center_id'];
    
                $PatientEntry->records_set_unique_id = '';
    
                $PatientEntry->patient_type = 'glaucoma-patients';

                $PatientEntry->ma = $request->gender=='ma'?1:0;
                $PatientEntry->fa = $request->gender=='fa'?1:0;
                $PatientEntry->mc = $request->gender=='mc'?1:0;
                $PatientEntry->fc = $request->gender=='fc'?1:0;
    
        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $$glaucoma_patients_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }


        }
        
  
   
        return redirect()->route('refferal-forms.index')
                        ->with('success','Cataract Patient created successfully.');
    }


    public function save_old_refferal_patient(Request $request)
    {
 
        $user = Auth::user();
    
        $center_info  = DB::table('pec_center')
            ->where('center_id', $request->center_id)
            ->first();
        
        $center_session['activity_location_id'] = $center_info->activity_location_id;
        $center_session['province_id'] = $center_info->province_id;
        $center_session['division_id'] = $center_info->division_id;
        $center_session['district_id'] = $center_info->district_id;
        $center_session['tehsil_id'] = $center_info->tehsil_id;
        $center_session['center_id'] = $center_info->center_id;
    
        $gender_loc = $request->gender;
        
        $RefferalForm = new RefferalForm();
        $RefferalForm->user_id = $user->id;
        $RefferalForm->activity_location_id = $center_session['activity_location_id'];
        $RefferalForm->province_id = $center_session['province_id'];
        $RefferalForm->division_id = $center_session['division_id'];
        $RefferalForm->district_id = $center_session['district_id'];
        $RefferalForm->tehsil_id = $center_session['tehsil_id'];
        $RefferalForm->center_id = $center_session['center_id'];
        $RefferalForm->full_name = $request->full_name;
        $RefferalForm->relation = $request->relation;
        $RefferalForm->relative_name = $request->relative_name;
        $RefferalForm->cnic_number = $request->cnic_number;
        $RefferalForm->contact_number = $request->contact_number;
        $RefferalForm->gender = $request->gender;
        $RefferalForm->age = $request->age;
        $RefferalForm->address = $request->address;
        $RefferalForm->cat_book_number = $request->cat_book_number;
        $RefferalForm->refer_by = $request->refer_by;
        $RefferalForm->referrals = $request->referrals;
        $RefferalForm->status = '1';
        $RefferalForm->entry_at = $request->entry_date;
        $RefferalForm->save();

        $referal_form  = FormCategories::where('pec_form_cat_id', $request->referrals)
                        ->first();


        $RefferalFormMeta = new RefferalFormMeta();
        $RefferalFormMeta->refferal_forms_id = $RefferalForm->refferal_forms_id;;
        $RefferalFormMeta->user_id = $user->id;
        $RefferalFormMeta->activity_location_id = $center_session['activity_location_id'];
        $RefferalFormMeta->province_id =$center_session['province_id'];
        $RefferalFormMeta->division_id = $center_session['division_id'];
        $RefferalFormMeta->district_id = $center_session['district_id'];
        $RefferalFormMeta->tehsil_id = $center_session['tehsil_id'];
        $RefferalFormMeta->center_id = $center_session['center_id'];
        $RefferalFormMeta->patient_type = $referal_form->slug;
        $RefferalFormMeta->cataract_op_slug = $request->cataract_op_slug;
        $RefferalFormMeta->remark = $request->remark;
        

        if($referal_form->slug == 'cataract-surgery')
        {
            $RefferalFormMeta->diabetic = $request->diabetic;
            $RefferalFormMeta->diabetic_value = $request->diabetic_value;
        }
        
        if(isset($request->operation_private) && $request->operation_private == 'private')
            {
                $RefferalFormMeta->private = 1;
            }
            else
            {
                $RefferalFormMeta->doctor_id = $request->doctor_id;
                $RefferalFormMeta->health_facility_id = $request->health_facility_id;
            }
        
        $RefferalFormMeta->refer_to = 1;
        $RefferalFormMeta->save();

        

        $refer_form  = FormCategories::where('pec_form_cat_id', $request->refer_by)
                        ->first();

        $key_exist = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                        ->where('records_set_unique_id', '!=', '')
                        ->where('user_id', '=',  $user->id)
                        ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                        ->where('province_id', '=',  $center_session['province_id'])
                        ->where('division_id', '=',  $center_session['division_id'])
                        ->where('district_id', '=',  $center_session['district_id'])
                        ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                        ->where('center_id', '=',  $center_session['center_id'])
                        ->first();


        if(!$key_exist)
        {
            $unique_id = hexdec(uniqid().rand(10,100));
            $PatientEntry = new PatientEntry();
            $PatientEntry->user_id = $user->id;
            $PatientEntry->activity_location_id = $center_session['activity_location_id'];
            $PatientEntry->province_id = $center_session['province_id'];
            $PatientEntry->division_id = $center_session['division_id'];
            $PatientEntry->district_id = $center_session['district_id'];
            $PatientEntry->tehsil_id = $center_session['tehsil_id'];
            $PatientEntry->center_id = $center_session['center_id'];

            $PatientEntry->records_set_unique_id = $unique_id;

            $PatientEntry->patient_type = $refer_form->slug;

            $PatientEntry->ma = $request->gender=='ma'?1:0;
            $PatientEntry->fa = $request->gender=='fa'?1:0;
            $PatientEntry->mc = $request->gender=='mc'?1:0;
            $PatientEntry->fc = $request->gender=='fc'?1:0;
    
            $PatientEntry->entry_at = date('Y-m-d');
            $PatientEntry->save();

            
        }
        else
        {
            $refer_by_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  $refer_form->slug)
                ->first();
            // echo "<pre>";
            // print_r($refer_by_rec);
            // echo "</pre>";
            if(!$refer_by_rec)
            {
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->division_id = $center_session['division_id'];
                $PatientEntry->district_id = $center_session['district_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                $PatientEntry->center_id = $center_session['center_id'];
                
                $PatientEntry->records_set_unique_id = '';
    
                $PatientEntry->patient_type = $refer_form->slug;

                $PatientEntry->ma = $request->gender=='ma'?1:0;
                $PatientEntry->fa = $request->gender=='fa'?1:0;
                $PatientEntry->mc = $request->gender=='mc'?1:0;
                $PatientEntry->fc = $request->gender=='fc'?1:0;

        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {                   
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $refer_by_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }

        }


        if($referal_form->slug == 'cataract-surgery' || $referal_form->slug == 'trichiasis-surgery' || $referal_form->slug == 'glaucoma-surgery')
        {
            $referal_form_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
            ->where('user_id', '=',  $user->id)
            ->where('activity_location_id', '=',  $center_session['activity_location_id'])
            ->where('province_id', '=',  $center_session['province_id'])
            ->where('division_id', '=',  $center_session['division_id'])
            ->where('district_id', '=',  $center_session['district_id'])
            ->where('tehsil_id', '=',  $center_session['tehsil_id'])
            ->where('center_id', '=',  $center_session['center_id'])
            ->where('patient_type', '=',  $referal_form->slug)
            ->first();
            if(!$referal_form_rec)
            {
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->division_id = $center_session['division_id'];
                $PatientEntry->district_id = $center_session['district_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                $PatientEntry->center_id = $center_session['center_id'];
    
                $PatientEntry->records_set_unique_id = '';
    
                $PatientEntry->patient_type = $referal_form->slug;

                $PatientEntry->ma = $request->gender=='ma'?1:0;
                $PatientEntry->fa = $request->gender=='fa'?1:0;
                $PatientEntry->mc = $request->gender=='mc'?1:0;
                $PatientEntry->fc = $request->gender=='fc'?1:0;
        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $referal_form_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }


        }

        if(isset($request->diabetic))
        {
            if($request->diabetic == 1)
            {
                $diabetic_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'diabetic')
                ->first();
                if(!$diabetic_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
        
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'diabetic';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;

            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $diabetic_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
            }
            else
            {

                $non_diabetic_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'non-diabetic')
                ->first();

                if(!$non_diabetic_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
        
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'non-diabetic';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $non_diabetic_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
                
            }

        }
        
        echo $request->cataract_op_slug;

        if(isset($request->cataract_op_slug))
        {
            if($request->cataract_op_slug == 'cataract-left-eye')
            {
                $cataract_one_left_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'cataract-left-eye')
                ->first();

                if(!$cataract_one_left_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
        
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'cataract-left-eye';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_one_left_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
            }
            elseif ($request->cataract_op_slug == 'cataract-right-eye')
            {

                $cataract_right_eye_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'cataract-right-eye')
                ->first();

                if(!$cataract_right_eye_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'cataract-right-eye';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_right_eye_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
                
            }
            else
            {

                $cataract_both_eyes_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
                ->where('user_id', '=',  $user->id)
                ->where('activity_location_id', '=',  $center_session['activity_location_id'])
                ->where('province_id', '=',  $center_session['province_id'])
                ->where('division_id', '=',  $center_session['division_id'])
                ->where('district_id', '=',  $center_session['district_id'])
                ->where('tehsil_id', '=',  $center_session['tehsil_id'])
                ->where('center_id', '=',  $center_session['center_id'])
                ->where('patient_type', '=',  'cataract-both-eyes')
                ->first();

                if(!$cataract_both_eyes_rec)
                {
                    $PatientEntry = new PatientEntry();
                    $PatientEntry->user_id = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id = $center_session['province_id'];
                    $PatientEntry->division_id = $center_session['division_id'];
                    $PatientEntry->district_id = $center_session['district_id'];
                    $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                    $PatientEntry->center_id = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';
        
                    $PatientEntry->patient_type = 'cataract-both-eyes';

                    $PatientEntry->ma = $request->gender=='ma'?1:0;
                    $PatientEntry->fa = $request->gender=='fa'?1:0;
                    $PatientEntry->mc = $request->gender=='fc'?1:0;
                    $PatientEntry->fc = $request->gender=='mc'?1:0;
            
                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                }
                else
                {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_both_eyes_rec->center_patient_record_id)->increment( $gender_loc, 1);
                }
                
            }

        }
        
        
        if($referal_form->slug == 'glaucoma-surgery')
        {
            $glaucoma_patients_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
            ->where('user_id', '=',  $user->id)
            ->where('activity_location_id', '=',  $center_session['activity_location_id'])
            ->where('province_id', '=',  $center_session['province_id'])
            ->where('division_id', '=',  $center_session['division_id'])
            ->where('district_id', '=',  $center_session['district_id'])
            ->where('tehsil_id', '=',  $center_session['tehsil_id'])
            ->where('center_id', '=',  $center_session['center_id'])
            ->where('patient_type', '=',  'glaucoma-patients')
            ->first();

            if(!$glaucoma_patients_rec)
            {
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->division_id = $center_session['division_id'];
                $PatientEntry->district_id = $center_session['district_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];
                $PatientEntry->center_id = $center_session['center_id'];
    
                $PatientEntry->records_set_unique_id = '';
    
                $PatientEntry->patient_type = 'glaucoma-patients';

                $PatientEntry->ma = $request->gender=='ma'?1:0;
                $PatientEntry->fa = $request->gender=='fa'?1:0;
                $PatientEntry->mc = $request->gender=='mc'?1:0;
                $PatientEntry->fc = $request->gender=='fc'?1:0;
    
        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $$glaucoma_patients_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }


        }
        
  
   
        return redirect()->route('refferal-forms.index')
                        ->with('success','Cataract Patient created successfully.');
    }


    public function show(RefferalForm $RefferalForm)
    {

        return view('refferal-forms.show', compact('RefferalForm'));
    }

  
    public function edit(RefferalForm $RefferalForm)
    {
        $referral = FormCategories::where('parent_slug', 'referrals')
        ->get();

        $referby = FormCategories::where('parent_slug', 'refer-by')
        ->get();
    

        $RefferalFormReferTo = RefferalFormMeta::where('refferal_forms_id', $RefferalForm->refferal_forms_id)
            ->where('refer_to', 1)
            ->first();
            
        // echo "<pre>";
        //     print_r($RefferalForm);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($RefferalFormMeta);
        // echo "</pre>";
        // die();

        return view('refferal-forms.edit', compact('RefferalForm','RefferalFormReferTo', 'referral', 'referby'));
    }

   
    public function update(Request $request, RefferalForm $RefferalForm)
    {
    
        $RefferalForm->update([
            'full_name' => $request->full_name,
            'relation' => $request->relation,
            'relative_name' => $request->relative_name,
            'cnic_number' => $request->cnic_number,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'age' => $request->age,
            'address' => $request->address,
            'cat_book_number' => $request->cat_book_number,
            'refer_by' => $request->refer_by,
            'referrals' => $request->referrals,
            'email' => $request->input('username').'@pec.com',
        ]);


            $RefferalFormMeta['diabetic'] = $request->diabetic;
            $RefferalFormMeta['diabetic_value'] = $request->diabetic_value;
            $RefferalFormMeta['cataract_op_slug'] = $request->cataract_op_slug;
            

        if(isset($request->operation_private) && $request->operation_private == 'private')
            {
                $RefferalFormMeta['private'] = 1;
                $RefferalFormMeta['doctor_id'] = '';
                $RefferalFormMeta['health_facility_id'] = '';
            }
            else
            {
                $RefferalFormMeta['private'] = '';
                $RefferalFormMeta['doctor_id'] = $request->doctor_id;
                $RefferalFormMeta['health_facility_id'] = $request->health_facility_id;
            }


        DB::table('pec_refferal_form_meta')
                ->where('refferal_forms_id', $RefferalForm->refferal_forms_id)
                ->update($RefferalFormMeta);

      
        return redirect()->route('refferal-forms.index')
                ->with('success','Cataract Patient Updated Successfully.');
    }

 
    public function destroy(RefferalForm $RefferalForm)
    {
 
        $cataract->delete();
       
        return redirect()->route('RefferalForm.index')
                        ->with('success','Cataract deleted successfully');
    }

    public function operated_by(Request $request, $id)
    {
        $refferal_forms_id = $id;

        $patient_info = RefferalForm::where('refferal_forms_id', $refferal_forms_id)
        ->first();

        $patient_type = RefferalFormMeta::where('refferal_forms_id', $refferal_forms_id)
        ->first();
        
        return view('refferal-forms.operated-by', compact('refferal_forms_id', 'patient_type', 'patient_info'));
    }


    public function add_operated_by(Request $request)
    {   
     
        $patient_info = RefferalForm::where('refferal_forms_id', $request->refferal_forms_id)
            ->first();
     
        $gender_loc = $patient_info->gender;
 
        $patient_type = RefferalFormMeta::where('refferal_forms_id', $request->refferal_forms_id)
            ->first();
        
        // echo "<pre>";    
        // print_r($patient_type);
        // echo "</pre>"; 
            
        $user = Auth::user();
        $center_session = session()->get('center_info');
        
        $RefferalFormMeta = new RefferalFormMeta();
        $RefferalFormMeta->refferal_forms_id = $request->refferal_forms_id;
        $RefferalFormMeta->activity_location_id = $patient_type->activity_location_id;
        $RefferalFormMeta->user_id = $patient_type->user_id;
        $RefferalFormMeta->province_id = $patient_type->province_id;
        $RefferalFormMeta->division_id = $patient_type->division_id;
        $RefferalFormMeta->district_id = $patient_type->district_id;
        $RefferalFormMeta->tehsil_id = $patient_type->tehsil_id;
        $RefferalFormMeta->center_id = $patient_type->center_id;
        $RefferalFormMeta->cataract_op_slug = $patient_type->cataract_op_slug;
        $RefferalFormMeta->patient_type = $patient_type->patient_type;
        $RefferalFormMeta->remark = $request->remark;
        $RefferalFormMeta->operation_date = $request->operation_date;

        if($request->post_op == 'number-of-patients-66618')
            {
                $RefferalFormMeta->post_op = '6/6-6/18';
            }
        else if($request->post_op == 'number-of-patients-618660')
            {
                $RefferalFormMeta->post_op = '6/18-6/60';
            }
        else
            {
                $RefferalFormMeta->post_op = '6/60 or below';
            }

        if(isset($request->operation_private) && $request->operation_private == 'private')
        {
            $RefferalFormMeta->private = 1;
        }
        else
        {
            $RefferalFormMeta->doctor_id = $request->doctor_id;
            $RefferalFormMeta->health_facility_id = $request->health_facility_id;
        }

        $RefferalFormMeta->confirmed_cataract_surgery = $request->confirmed_cataract_surgery;
        $RefferalFormMeta->operated_by = 1;
        $RefferalFormMeta->save();
   
        if(isset($request->confirmed_cataract_surgery))
        {

            $confirmed_cataract_surgery_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
            ->where('user_id', '=',  $user->id)
            ->where('province_id', '=',  $center_session['province_id'])
            ->where('tehsil_id', '=',  $center_session['tehsil_id'])
            ->where('center_id', '=',  $center_session['center_id'])
            ->where('patient_type', '=',  $request->confirmed_cataract_surgery)
            ->first();


            if(!$confirmed_cataract_surgery_rec)
            {
       
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->center_id = $center_session['center_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $request->confirmed_cataract_surgery;

                $PatientEntry->ma = $patient_info->gender=='ma'?1:0;
                $PatientEntry->fa = $patient_info->gender=='fa'?1:0;
                $PatientEntry->mc = $patient_info->gender=='fc'?1:0;
                $PatientEntry->fc = $patient_info->gender=='mc'?1:0;
        
        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $confirmed_cataract_surgery_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }
 

        }


        if(isset($request->post_op))
        {

            $post_op_rec = PatientEntry::whereDate('entry_at', '=' , date('Y-m-d'))
            ->where('user_id', '=',  $user->id)
            ->where('province_id', '=',  $center_session['province_id'])
            ->where('tehsil_id', '=',  $center_session['tehsil_id'])
            ->where('center_id', '=',  $center_session['center_id'])
            ->where('patient_type', '=',  $request->post_op)
            ->first();


            if(!$post_op_rec)
            {
       
                $PatientEntry = new PatientEntry();
                $PatientEntry->user_id = $user->id;
                $PatientEntry->center_id = $center_session['center_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->tehsil_id = $center_session['tehsil_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $request->post_op;
                
                $PatientEntry->ma = $patient_info->gender=='ma'?1:0;
                $PatientEntry->fa = $patient_info->gender=='fa'?1:0;
                $PatientEntry->mc = $patient_info->gender=='fc'?1:0;
                $PatientEntry->fc = $patient_info->gender=='mc'?1:0;
        
                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            }
            else
            {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $post_op_rec->center_patient_record_id)->increment( $gender_loc, 1);
            }
 

        }        
        
        return redirect()->route('refferal-forms.index')
                        ->with('success','Patient operated by Updated.');
    }
}
