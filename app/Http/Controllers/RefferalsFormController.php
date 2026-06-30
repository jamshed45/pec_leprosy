<?php
namespace App\Http\Controllers;

use App\Http\Controllers\View;
use App\Models\Center;
use App\Models\Diabetic;
use App\Models\District;
use App\Models\Division;
use App\Models\Doctor;
use App\Models\FormCategories;
use App\Models\PatientEntry;
use App\Models\Province;
use App\Models\RefferalForm;
use App\Models\RefferalFormMeta;
use App\Models\Tehsil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;



class RefferalsFormController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = RefferalForm::with([
            'referByCategory',
            'referralsCategory',
            'operatedBy.doctor',
            'operatedBy.healthFacility',
            'referTo.doctor',
            'referTo.healthFacility',
            'meta'
        ])->orderBy('created_at', 'desc');

        if ($user->hasRole('pec-user')) {
            $query->where('user_id', $user->id);
        }

        $patients = $query->get();

        return view('refferal-forms.index', compact('patients'));
    }




public function get_refferal_forms(Request $request)
{
    $user = auth()->user();

    $query = RefferalForm::with([
        'user',
        'referByCategory',
        'referralsCategory',
        'referTo.healthFacility',
        'referTo.doctor',
        'operatedBy.healthFacility',
        'operatedBy.doctor'
    ])
    ->orderBy('entry_at', 'desc')
    ->orderBy('refferal_forms_id', 'desc');

    // Role-based filter
    if ($user->hasRole('pec-user')) {
        $query->where('user_id', $user->id);
    }

    return DataTables::eloquent($query)

        ->filter(function ($query) use ($request) {

            $search = $request->search_custom;

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {

                    // Patient Main Fields
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('relative_name', 'like', "%{$search}%")
                      ->orWhere('cnic_number', 'like', "%{$search}%")
                      ->orWhere('contact_number', 'like', "%{$search}%")
                      ->orWhere('age', 'like', "%{$search}%")
                      ->orWhere('cat_book_number', 'like', "%{$search}%")
                      ->orWhere('refferal_forms_id', 'like', "%{$search}%");

                    // // Lot name
                    // $q->orWhereHas('user', fn($u) =>
                    //     $u->where('name', 'like', "%{$search}%")
                    // );

                    // // Refer By Category
                    // $q->orWhereHas('referByCategory', fn($c) =>
                    //     $c->where('category_name', 'like', "%{$search}%")
                    // );

                    // // Refer To Facility
                    // $q->orWhereHas('referTo.healthFacility', fn($hf) =>
                    //     $hf->where('health_facility_name', 'like', "%{$search}%")
                    // );

                    // // Refer To Doctor
                    // $q->orWhereHas('referTo.doctor', fn($doc) =>
                    //     $doc->where('doctor_name', 'like', "%{$search}%")
                    // );

                });
            }
        })

        ->addColumn('no', fn() => '')

        ->addColumn('lots_name', fn($row) => $row->user->name ?? '-')

        ->addColumn('date_record', function ($row) {
            return '<small>' . date('j M, Y', strtotime($row->entry_at)) .
                '<hr class="my-1"><b>Record ID:</b> ' . $row->refferal_forms_id . '</small>';
        })

        ->addColumn('patient_info', function ($row) {

            $gender_list = [
                'ma' => 'Male Adult',
                'fa' => 'Female Adult',
                'mc' => 'Male Child',
                'fc' => 'Female Child'
            ];

            $gender = $gender_list[$row->gender] ?? '-';

            return "<small>
                <b>Name:</b> {$row->full_name} <b>{$row->relation}</b> {$row->relative_name}
                <hr class='my-1'><b>Age:</b> {$row->age}
                <hr class='my-1'><b>Gender:</b> {$gender}
                <hr class='my-1'><b>CNIC:</b> {$row->cnic_number}
                <hr class='my-1'><b>Contact:</b> {$row->contact_number}
            </small>";
        })

        ->addColumn('information', function ($row) {

            $html = "<small><b>Refer From:</b> " . $row->referByCategory->category_name . "</small>";

            // Refer To
            if ($row->referTo) {
                if ($row->referTo->private) {
                    $html .= "<br><b>Refer To:</b> Private";
                } else {
                    $html .= "<br><b>Refer To:</b>
                             <br><b>Facility:</b> " . ($row->referTo->healthFacility->health_facility_name ?? 'N/A') . "
                             <br><b>Doctor:</b> " . ($row->referTo->doctor->doctor_name ?? 'N/A');
                }
            }

            // Operated By
            if ($row->operatedBy) {
                if ($row->operatedBy->private) {
                    $html .= "<br><b>Operated By:</b> Private";
                } else {
                    $html .= "<br><b>Operated By:</b>
                             <br><b>Facility:</b> " . ($row->operatedBy->healthFacility->health_facility_name ?? 'N/A') . "
                             <br><b>Doctor:</b> " . ($row->operatedBy->doctor->doctor_name ?? 'N/A') . "
                             <br><b>Operation Date:</b> " . $row->operatedBy->operation_date;
                }
            }

            return $html;
        })

        ->addColumn('verified', fn($row) =>
            $row->verify ?
                '<i class="fa fa-check-square text-success"></i>' :
                '<i class="fa fa-times text-danger"></i>'
        )

        ->addColumn('actions', function ($row) {
            return "
                <small>
                    <a href='" . route('refferal-forms.show', $row->refferal_forms_id) . "'>View</a>
                    <hr class='my-1'>
                    <a href='" . route('refferal-forms.edit', $row->refferal_forms_id) . "'>Update</a>
                    <hr class='my-1'>
                    <a href='" . url('refferal-forms/operated-by/' . $row->refferal_forms_id) . "'>Operation Information</a>
                </small>
            ";
        })

        ->rawColumns(['date_record', 'patient_info', 'information', 'verified', 'actions'])
        ->make(true);
}






    public function view_unverified_refferal()
    {
        $patients = RefferalForm::where('verify', 0)->get();
        return view('refferal-forms.index', compact('patients'));
    }

    public function view_verified_refferal()
    {
        $patients = RefferalForm::where('verify', 1)->get();
        return view('refferal-forms.index', compact('patients'));
    }

    public function refferal_patients_by_user(Request $request)
    {
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y'),
        //     'users' => ''
        // ];

        // $pdf = PDF::loadView('myPDF', $data);

        // return $pdf->download('itsolutionstuff.pdf');
        // die();
        DB::enableQueryLog();

        $users = User::orderBy('name', 'asc')->get();

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {
            // print_r($request->all());

            $query = DB::table('pec_refferal_forms')
                ->where('user_id', '=', $request->user_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            if ($request->activity_location_id) {
                $query->where('activity_location_id', '=', $request->activity_location_id);
            }

            if ($request->center_id) {
                $query->where('center_id', '=', $request->center_id);
            }

            // die();

            $search_field_info['user_id']              = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id']            = $request->center_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['search_status']        = true;

            $my_centers = DB::table('usermeta')
                ->where('user_id', $request->user_id)
                ->where('meta_key', 'center_id')
                ->pluck('meta_value')
                ->toArray();

            if (isset($request->activity_location_id)) {
                $filter_centers = Center::wherein('center_id', $my_centers)
                    ->where('activity_location_id', $request->activity_location_id)
                    ->orderBy('center_name')
                    ->get();
            } else {
                $filter_centers = Center::wherein('center_id', $my_centers)
                    ->orderBy('center_name')
                    ->get();
            }

            // $patients = New RefferalForm;
            // $patients->where('user_id', '=',  $request->user_id);
            // $patients->orderBy('entry_at', 'desc');
            $patients      = $query->get();
            $patient_count = $query->count();

            $query = DB::getQueryLog();
//  dd($query);

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['search_status']        = false;
            $filter_centers                            = [];
        }

        return view('refferal-forms.refferal_by_user', compact('users', 'activity_location', 'patients', 'search_field_info', 'patient_count', 'filter_centers'));
    }

    public function refferal_patients_by_center(Request $request)
    {

        $centers = Center::orderBy('center_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_refferal_forms')
                ->where('center_id', '=', $request->center_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            $search_field_info['center_id']     = $request->center_id;
            $search_field_info['from_date']     = $request->from_date;
            $search_field_info['to_date']       = $request->to_date;
            $search_field_info['searh_month']   = $request->searh_month;
            $search_field_info['search_status'] = true;

            $patients      = $query->get();
            $patient_count = $query->count();

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['center_id']     = '';
            $search_field_info['from_date']     = '';
            $search_field_info['to_date']       = '';
            $search_field_info['searh_month']   = '';
            $search_field_info['search_status'] = false;
        }

        return view('refferal-forms.refferal_by_center', compact('centers', 'patients', 'search_field_info', 'patient_count'));
    }

    public function refferal_patients_by_location(Request $request)
    {

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {

            $query = DB::table('pec_refferal_forms')
                ->where('activity_location_id', '=', $request->activity_location_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['search_status']        = true;

            $patients      = $query->get();
            $patient_count = $query->count();

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['activity_location_id'] = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['search_status']        = false;
        }

        return view('refferal-forms.refferal_by_location', compact('activity_location', 'patients', 'search_field_info', 'patient_count'));
    }

    public function refferal_patients_by_tehsil(Request $request)
    {

        $teshils = Tehsil::orderBy('tehsil_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_refferal_forms')
                ->where('tehsil_id', '=', $request->tehsil_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            $search_field_info['tehsil_id']     = $request->tehsil_id;
            $search_field_info['from_date']     = $request->from_date;
            $search_field_info['to_date']       = $request->to_date;
            $search_field_info['searh_month']   = $request->searh_month;
            $search_field_info['search_status'] = true;

            $patients      = $query->get();
            $patient_count = $query->count();

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['tehsil_id']     = '';
            $search_field_info['from_date']     = '';
            $search_field_info['to_date']       = '';
            $search_field_info['searh_month']   = '';
            $search_field_info['search_status'] = false;
        }

        return view('refferal-forms.refferal_by_tehsil', compact('teshils', 'patients', 'search_field_info', 'patient_count'));
    }

    public function refferal_patients_by_district(Request $request)
    {

        $districts = District::orderBy('district_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_refferal_forms')
                ->where('district_id', '=', $request->district_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            $search_field_info['district_id']   = $request->district_id;
            $search_field_info['from_date']     = $request->from_date;
            $search_field_info['to_date']       = $request->to_date;
            $search_field_info['searh_month']   = $request->searh_month;
            $search_field_info['search_status'] = true;

            $patients      = $query->get();
            $patient_count = $query->count();

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['district_id']   = '';
            $search_field_info['from_date']     = '';
            $search_field_info['to_date']       = '';
            $search_field_info['searh_month']   = '';
            $search_field_info['search_status'] = false;
        }

        return view('refferal-forms.refferal_by_district', compact('districts', 'patients', 'search_field_info', 'patient_count'));
    }

    public function refferal_patients_by_division(Request $request)
    {

        $divisions = Division::orderBy('division_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_refferal_forms')
                ->where('division_id', '=', $request->division_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            $search_field_info['division_id']   = $request->division_id;
            $search_field_info['from_date']     = $request->from_date;
            $search_field_info['to_date']       = $request->to_date;
            $search_field_info['searh_month']   = $request->searh_month;
            $search_field_info['search_status'] = true;

            $patients      = $query->get();
            $patient_count = $query->count();

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['division_id']   = '';
            $search_field_info['from_date']     = '';
            $search_field_info['to_date']       = '';
            $search_field_info['searh_month']   = '';
            $search_field_info['search_status'] = false;
        }

        return view('refferal-forms.refferal_by_division', compact('divisions', 'patients', 'search_field_info', 'patient_count'));
    }

    public function refferal_patients_by_province(Request $request)
    {

        $provinces = Province::orderBy('province_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_refferal_forms')
                ->where('province_id', '=', $request->province_id)
                ->orderBy('entry_at', 'desc');

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));

                $query->whereMonth('entry_at', '=', $get_month);
            } else {
                if ($request->from_date != '' && $request->to_date == '') {
                    $query->whereDate('entry_at', '=', $request->from_date);
                }
                if ($request->from_date == '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
                if ($request->from_date != '' && $request->to_date != '') {
                    $query->whereDate('entry_at', '>=', $request->from_date);
                    $query->whereDate('entry_at', '<=', $request->to_date);
                }
            }

            $search_field_info['province_id']   = $request->province_id;
            $search_field_info['from_date']     = $request->from_date;
            $search_field_info['to_date']       = $request->to_date;
            $search_field_info['searh_month']   = $request->searh_month;
            $search_field_info['search_status'] = true;

            $patients      = $query->get();
            $patient_count = $query->count();

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['province_id']   = '';
            $search_field_info['from_date']     = '';
            $search_field_info['to_date']       = '';
            $search_field_info['searh_month']   = '';
            $search_field_info['search_status'] = false;
        }

        return view('refferal-forms.refferal_by_province', compact('provinces', 'patients', 'search_field_info', 'patient_count'));
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

        $RefferalForm                       = new RefferalForm();
        $RefferalForm->user_id              = $user->id;
        $RefferalForm->activity_location_id = $center_session['activity_location_id'];
        $RefferalForm->province_id          = $center_session['province_id'];
        $RefferalForm->division_id          = $center_session['division_id'];
        $RefferalForm->district_id          = $center_session['district_id'];
        $RefferalForm->tehsil_id            = $center_session['tehsil_id'];
        $RefferalForm->center_id            = $center_session['center_id'];
        $RefferalForm->full_name            = $request->full_name;
        $RefferalForm->relation             = $request->relation;
        $RefferalForm->relative_name        = $request->relative_name;
        $RefferalForm->cnic_number          = $request->cnic_number;
        $RefferalForm->contact_number       = $request->contact_number;
        $RefferalForm->gender               = $request->gender;
        $RefferalForm->age                  = $request->age;
        $RefferalForm->address              = $request->address;
        $RefferalForm->cat_book_number      = $request->cat_book_number;
        $RefferalForm->refer_by             = $request->refer_by;
        $RefferalForm->referrals            = $request->referrals;
        $RefferalForm->status               = '1';
        $RefferalForm->entry_at             = $center_session['entry_date'];
        $RefferalForm->save();

        $referal_form = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first();

        $RefferalFormMeta = new RefferalFormMeta();

        $RefferalFormMeta->refferal_forms_id    = $RefferalForm->refferal_forms_id;
        $RefferalFormMeta->user_id              = $user->id;
        $RefferalFormMeta->activity_location_id = $center_session['activity_location_id'];
        $RefferalFormMeta->province_id          = $center_session['province_id'];
        $RefferalFormMeta->division_id          = $center_session['division_id'];
        $RefferalFormMeta->district_id          = $center_session['district_id'];
        $RefferalFormMeta->tehsil_id            = $center_session['tehsil_id'];
        $RefferalFormMeta->center_id            = $center_session['center_id'];
        $RefferalFormMeta->patient_type         = $referal_form->slug;
        $RefferalFormMeta->cataract_op_slug     = $request->cataract_op_slug;
        $RefferalFormMeta->entry_at             = $center_session['entry_date'];
        $RefferalFormMeta->remark               = $request->remark;

        if ($referal_form->slug == 'cataract-surgery') {
            $RefferalFormMeta->diabetic       = $request->diabetic;
            $RefferalFormMeta->diabetic_value = $request->diabetic_value;
        }

        if (isset($request->operation_private) && $request->operation_private == 'private') {
            $RefferalFormMeta->private = 1;
        } else {
            $RefferalFormMeta->doctor_id          = $request->doctor_id;
            $RefferalFormMeta->health_facility_id = $request->health_facility_id;
        }

        $RefferalFormMeta->refer_to = 1;
        $RefferalFormMeta->save();

        $refer_form = FormCategories::where('pec_form_cat_id', $request->refer_by)
            ->first();

        $key_exist = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
            ->where('records_set_unique_id', '!=', '')
            ->where('user_id', '=', $user->id)
            ->where('activity_location_id', '=', $center_session['activity_location_id'])
            ->where('province_id', '=', $center_session['province_id'])
            ->where('division_id', '=', $center_session['division_id'])
            ->where('district_id', '=', $center_session['district_id'])
            ->where('tehsil_id', '=', $center_session['tehsil_id'])
            ->where('center_id', '=', $center_session['center_id'])
            ->first();

        if (! $key_exist) {
            $unique_id                          = hexdec(uniqid() . rand(10, 100));
            $PatientEntry                       = new PatientEntry();
            $PatientEntry->user_id              = $user->id;
            $PatientEntry->activity_location_id = $center_session['activity_location_id'];
            $PatientEntry->province_id          = $center_session['province_id'];
            $PatientEntry->division_id          = $center_session['division_id'];
            $PatientEntry->district_id          = $center_session['district_id'];
            $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
            $PatientEntry->center_id            = $center_session['center_id'];

            $PatientEntry->records_set_unique_id = $unique_id;

            $PatientEntry->patient_type = $refer_form->slug;

            $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
            $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
            $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
            $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

            $PatientEntry->entry_at = $center_session['entry_date'];
            $PatientEntry->save();
        } else {
            $refer_by_rec = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
                ->where('user_id', '=', $user->id)
                ->where('activity_location_id', '=', $center_session['activity_location_id'])
                ->where('province_id', '=', $center_session['province_id'])
                ->where('division_id', '=', $center_session['division_id'])
                ->where('district_id', '=', $center_session['district_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', $refer_form->slug)
                ->first();
            // echo "<pre>";
            // print_r($refer_by_rec);
            // echo "</pre>";
            if (! $refer_by_rec) {
                $PatientEntry                       = new PatientEntry();
                $PatientEntry->user_id              = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id          = $center_session['province_id'];
                $PatientEntry->division_id          = $center_session['division_id'];
                $PatientEntry->district_id          = $center_session['district_id'];
                $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                $PatientEntry->center_id            = $center_session['center_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $refer_form->slug;

                $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
                $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

                $PatientEntry->entry_at = $center_session['entry_date'];
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $refer_by_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        if ($referal_form->slug == 'cataract-surgery' || $referal_form->slug == 'trichiasis-surgery' || $referal_form->slug == 'glaucoma-surgery' || $referal_form->slug == 'pterygium-surgery' || $referal_form->slug == 'minor-surgery' || $referal_form->slug == 'investigations') {
            $referal_form_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('activity_location_id', '=', $center_session['activity_location_id'])
                ->where('province_id', '=', $center_session['province_id'])
                ->where('division_id', '=', $center_session['division_id'])
                ->where('district_id', '=', $center_session['district_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', $referal_form->slug)
                ->first();
            if (! $referal_form_rec) {
                $PatientEntry                       = new PatientEntry();
                $PatientEntry->user_id              = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id          = $center_session['province_id'];
                $PatientEntry->division_id          = $center_session['division_id'];
                $PatientEntry->district_id          = $center_session['district_id'];
                $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                $PatientEntry->center_id            = $center_session['center_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $referal_form->slug;

                $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
                $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

                $PatientEntry->entry_at = $center_session['entry_date'];
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $referal_form_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        if (isset($request->diabetic)) {
            if ($request->diabetic == 1) {
                $diabetic_rec = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'diabetic')
                    ->first();
                if (! $diabetic_rec) {
                    $PatientEntry                       = new PatientEntry();
                    $PatientEntry->user_id              = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id          = $center_session['province_id'];
                    $PatientEntry->division_id          = $center_session['division_id'];
                    $PatientEntry->district_id          = $center_session['district_id'];
                    $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                    $PatientEntry->center_id            = $center_session['center_id'];

                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'diabetic';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = $center_session['entry_date'];
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $diabetic_rec->center_patient_record_id)->increment($gender_loc, 1);
                }
            } else {

                $non_diabetic_rec = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'non-diabetic')
                    ->first();

                if (! $non_diabetic_rec) {
                    $PatientEntry                       = new PatientEntry();
                    $PatientEntry->user_id              = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id          = $center_session['province_id'];
                    $PatientEntry->division_id          = $center_session['division_id'];
                    $PatientEntry->district_id          = $center_session['district_id'];
                    $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                    $PatientEntry->center_id            = $center_session['center_id'];

                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'non-diabetic';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = $center_session['entry_date'];
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $non_diabetic_rec->center_patient_record_id)->increment($gender_loc, 1);
                }

            }

        }

        echo $request->cataract_op_slug;

        if (isset($request->cataract_op_slug)) {
            if ($request->cataract_op_slug == 'cataract-left-eye') {
                $cataract_one_left_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'cataract-left-eye')
                    ->first();

                if (! $cataract_one_left_rec) {
                    $PatientEntry                       = new PatientEntry();
                    $PatientEntry->user_id              = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id          = $center_session['province_id'];
                    $PatientEntry->division_id          = $center_session['division_id'];
                    $PatientEntry->district_id          = $center_session['district_id'];
                    $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                    $PatientEntry->center_id            = $center_session['center_id'];

                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'cataract-left-eye';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_one_left_rec->center_patient_record_id)->increment($gender_loc, 1);
                }
            } elseif ($request->cataract_op_slug == 'cataract-right-eye') {

                $cataract_right_eye_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'cataract-right-eye')
                    ->first();

                if (! $cataract_right_eye_rec) {
                    $PatientEntry                        = new PatientEntry();
                    $PatientEntry->user_id               = $user->id;
                    $PatientEntry->activity_location_id  = $center_session['activity_location_id'];
                    $PatientEntry->province_id           = $center_session['province_id'];
                    $PatientEntry->division_id           = $center_session['division_id'];
                    $PatientEntry->district_id           = $center_session['district_id'];
                    $PatientEntry->tehsil_id             = $center_session['tehsil_id'];
                    $PatientEntry->center_id             = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'cataract-right-eye';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_right_eye_rec->center_patient_record_id)->increment($gender_loc, 1);
                }

            } else {

                $cataract_both_eyes_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'cataract-both-eyes')
                    ->first();

                if (! $cataract_both_eyes_rec) {
                    $PatientEntry                        = new PatientEntry();
                    $PatientEntry->user_id               = $user->id;
                    $PatientEntry->activity_location_id  = $center_session['activity_location_id'];
                    $PatientEntry->province_id           = $center_session['province_id'];
                    $PatientEntry->division_id           = $center_session['division_id'];
                    $PatientEntry->district_id           = $center_session['district_id'];
                    $PatientEntry->tehsil_id             = $center_session['tehsil_id'];
                    $PatientEntry->center_id             = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'cataract-both-eyes';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_both_eyes_rec->center_patient_record_id)->increment($gender_loc, 1);
                }

            }

        }

        if ($referal_form->slug == 'glaucoma-surgery') {
            $glaucoma_patients_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('activity_location_id', '=', $center_session['activity_location_id'])
                ->where('province_id', '=', $center_session['province_id'])
                ->where('division_id', '=', $center_session['division_id'])
                ->where('district_id', '=', $center_session['district_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', 'glaucoma-patients')
                ->first();

            if (! $glaucoma_patients_rec) {
                $PatientEntry                       = new PatientEntry();
                $PatientEntry->user_id              = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id          = $center_session['province_id'];
                $PatientEntry->division_id          = $center_session['division_id'];
                $PatientEntry->district_id          = $center_session['district_id'];
                $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                $PatientEntry->center_id            = $center_session['center_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = 'glaucoma-patients';

                $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
                $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $$glaucoma_patients_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        return redirect()->route('refferal-forms.index')
            ->with('success', 'Cataract Patient created successfully.');
    }

    public function save_old_refferal_patient(Request $request)
    {

        $user = Auth::user();

        $center_info = DB::table('pec_center')
            ->where('center_id', $request->center_id)
            ->first();

        $center_session['activity_location_id'] = $center_info->activity_location_id;
        $center_session['province_id']          = $center_info->province_id;
        $center_session['division_id']          = $center_info->division_id;
        $center_session['district_id']          = $center_info->district_id;
        $center_session['tehsil_id']            = $center_info->tehsil_id;
        $center_session['center_id']            = $center_info->center_id;

        $gender_loc = $request->gender;

        $RefferalForm                       = new RefferalForm();
        $RefferalForm->user_id              = $user->id;
        $RefferalForm->activity_location_id = $center_session['activity_location_id'];
        $RefferalForm->province_id          = $center_session['province_id'];
        $RefferalForm->division_id          = $center_session['division_id'];
        $RefferalForm->district_id          = $center_session['district_id'];
        $RefferalForm->tehsil_id            = $center_session['tehsil_id'];
        $RefferalForm->center_id            = $center_session['center_id'];
        $RefferalForm->full_name            = $request->full_name;
        $RefferalForm->relation             = $request->relation;
        $RefferalForm->relative_name        = $request->relative_name;
        $RefferalForm->cnic_number          = $request->cnic_number;
        $RefferalForm->contact_number       = $request->contact_number;
        $RefferalForm->gender               = $request->gender;
        $RefferalForm->age                  = $request->age;
        $RefferalForm->address              = $request->address;
        $RefferalForm->cat_book_number      = $request->cat_book_number;
        $RefferalForm->refer_by             = $request->refer_by;
        $RefferalForm->referrals            = $request->referrals;
        $RefferalForm->status               = '1';
        $RefferalForm->entry_at             = $request->entry_date;
        $RefferalForm->save();

        $referal_form = FormCategories::where('pec_form_cat_id', $request->referrals)
            ->first();

        $RefferalFormMeta                       = new RefferalFormMeta();
        $RefferalFormMeta->refferal_forms_id    = $RefferalForm->refferal_forms_id;
        $RefferalFormMeta->user_id              = $user->id;
        $RefferalFormMeta->activity_location_id = $center_session['activity_location_id'];
        $RefferalFormMeta->province_id          = $center_session['province_id'];
        $RefferalFormMeta->division_id          = $center_session['division_id'];
        $RefferalFormMeta->district_id          = $center_session['district_id'];
        $RefferalFormMeta->tehsil_id            = $center_session['tehsil_id'];
        $RefferalFormMeta->center_id            = $center_session['center_id'];
        $RefferalFormMeta->patient_type         = $referal_form->slug;
        $RefferalFormMeta->cataract_op_slug     = $request->cataract_op_slug;
        $RefferalFormMeta->remark               = $request->remark;

        if ($referal_form->slug == 'cataract-surgery') {
            $RefferalFormMeta->diabetic       = $request->diabetic;
            $RefferalFormMeta->diabetic_value = $request->diabetic_value;
        }

        if (isset($request->operation_private) && $request->operation_private == 'private') {
            $RefferalFormMeta->private = 1;
        } else {
            $RefferalFormMeta->doctor_id          = $request->doctor_id;
            $RefferalFormMeta->health_facility_id = $request->health_facility_id;
        }

        $RefferalFormMeta->refer_to = 1;
        $RefferalFormMeta->save();

        $refer_form = FormCategories::where('pec_form_cat_id', $request->refer_by)
            ->first();

        $key_exist = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
            ->where('records_set_unique_id', '!=', '')
            ->where('user_id', '=', $user->id)
            ->where('activity_location_id', '=', $center_session['activity_location_id'])
            ->where('province_id', '=', $center_session['province_id'])
            ->where('division_id', '=', $center_session['division_id'])
            ->where('district_id', '=', $center_session['district_id'])
            ->where('tehsil_id', '=', $center_session['tehsil_id'])
            ->where('center_id', '=', $center_session['center_id'])
            ->first();

        if (! $key_exist) {
            $unique_id                          = hexdec(uniqid() . rand(10, 100));
            $PatientEntry                       = new PatientEntry();
            $PatientEntry->user_id              = $user->id;
            $PatientEntry->activity_location_id = $center_session['activity_location_id'];
            $PatientEntry->province_id          = $center_session['province_id'];
            $PatientEntry->division_id          = $center_session['division_id'];
            $PatientEntry->district_id          = $center_session['district_id'];
            $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
            $PatientEntry->center_id            = $center_session['center_id'];

            $PatientEntry->records_set_unique_id = $unique_id;

            $PatientEntry->patient_type = $refer_form->slug;

            $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
            $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
            $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
            $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

            $PatientEntry->entry_at = date('Y-m-d');
            $PatientEntry->save();

        } else {
            $refer_by_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('activity_location_id', '=', $center_session['activity_location_id'])
                ->where('province_id', '=', $center_session['province_id'])
                ->where('division_id', '=', $center_session['division_id'])
                ->where('district_id', '=', $center_session['district_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', $refer_form->slug)
                ->first();
            // echo "<pre>";
            // print_r($refer_by_rec);
            // echo "</pre>";
            if (! $refer_by_rec) {
                $PatientEntry                       = new PatientEntry();
                $PatientEntry->user_id              = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id          = $center_session['province_id'];
                $PatientEntry->division_id          = $center_session['division_id'];
                $PatientEntry->district_id          = $center_session['district_id'];
                $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                $PatientEntry->center_id            = $center_session['center_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $refer_form->slug;

                $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
                $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $refer_by_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        if ($referal_form->slug == 'cataract-surgery' || $referal_form->slug == 'trichiasis-surgery' || $referal_form->slug == 'glaucoma-surgery') {
            $referal_form_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('activity_location_id', '=', $center_session['activity_location_id'])
                ->where('province_id', '=', $center_session['province_id'])
                ->where('division_id', '=', $center_session['division_id'])
                ->where('district_id', '=', $center_session['district_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', $referal_form->slug)
                ->first();
            if (! $referal_form_rec) {
                $PatientEntry                       = new PatientEntry();
                $PatientEntry->user_id              = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id          = $center_session['province_id'];
                $PatientEntry->division_id          = $center_session['division_id'];
                $PatientEntry->district_id          = $center_session['district_id'];
                $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                $PatientEntry->center_id            = $center_session['center_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $referal_form->slug;

                $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
                $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $referal_form_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        if (isset($request->diabetic)) {
            if ($request->diabetic == 1) {
                $diabetic_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'diabetic')
                    ->first();
                if (! $diabetic_rec) {
                    $PatientEntry                       = new PatientEntry();
                    $PatientEntry->user_id              = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id          = $center_session['province_id'];
                    $PatientEntry->division_id          = $center_session['division_id'];
                    $PatientEntry->district_id          = $center_session['district_id'];
                    $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                    $PatientEntry->center_id            = $center_session['center_id'];

                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'diabetic';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $diabetic_rec->center_patient_record_id)->increment($gender_loc, 1);
                }
            } else {

                $non_diabetic_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'non-diabetic')
                    ->first();

                if (! $non_diabetic_rec) {
                    $PatientEntry                       = new PatientEntry();
                    $PatientEntry->user_id              = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id          = $center_session['province_id'];
                    $PatientEntry->division_id          = $center_session['division_id'];
                    $PatientEntry->district_id          = $center_session['district_id'];
                    $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                    $PatientEntry->center_id            = $center_session['center_id'];

                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'non-diabetic';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $non_diabetic_rec->center_patient_record_id)->increment($gender_loc, 1);
                }

            }

        }

        echo $request->cataract_op_slug;

        if (isset($request->cataract_op_slug)) {
            if ($request->cataract_op_slug == 'cataract-left-eye') {
                $cataract_one_left_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'cataract-left-eye')
                    ->first();

                if (! $cataract_one_left_rec) {
                    $PatientEntry                       = new PatientEntry();
                    $PatientEntry->user_id              = $user->id;
                    $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                    $PatientEntry->province_id          = $center_session['province_id'];
                    $PatientEntry->division_id          = $center_session['division_id'];
                    $PatientEntry->district_id          = $center_session['district_id'];
                    $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                    $PatientEntry->center_id            = $center_session['center_id'];

                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'cataract-left-eye';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_one_left_rec->center_patient_record_id)->increment($gender_loc, 1);
                }
            } elseif ($request->cataract_op_slug == 'cataract-right-eye') {

                $cataract_right_eye_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'cataract-right-eye')
                    ->first();

                if (! $cataract_right_eye_rec) {
                    $PatientEntry                        = new PatientEntry();
                    $PatientEntry->user_id               = $user->id;
                    $PatientEntry->activity_location_id  = $center_session['activity_location_id'];
                    $PatientEntry->province_id           = $center_session['province_id'];
                    $PatientEntry->division_id           = $center_session['division_id'];
                    $PatientEntry->district_id           = $center_session['district_id'];
                    $PatientEntry->tehsil_id             = $center_session['tehsil_id'];
                    $PatientEntry->center_id             = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'cataract-right-eye';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_right_eye_rec->center_patient_record_id)->increment($gender_loc, 1);
                }

            } else {

                $cataract_both_eyes_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                    ->where('user_id', '=', $user->id)
                    ->where('activity_location_id', '=', $center_session['activity_location_id'])
                    ->where('province_id', '=', $center_session['province_id'])
                    ->where('division_id', '=', $center_session['division_id'])
                    ->where('district_id', '=', $center_session['district_id'])
                    ->where('tehsil_id', '=', $center_session['tehsil_id'])
                    ->where('center_id', '=', $center_session['center_id'])
                    ->where('patient_type', '=', 'cataract-both-eyes')
                    ->first();

                if (! $cataract_both_eyes_rec) {
                    $PatientEntry                        = new PatientEntry();
                    $PatientEntry->user_id               = $user->id;
                    $PatientEntry->activity_location_id  = $center_session['activity_location_id'];
                    $PatientEntry->province_id           = $center_session['province_id'];
                    $PatientEntry->division_id           = $center_session['division_id'];
                    $PatientEntry->district_id           = $center_session['district_id'];
                    $PatientEntry->tehsil_id             = $center_session['tehsil_id'];
                    $PatientEntry->center_id             = $center_session['center_id'];
                    $PatientEntry->records_set_unique_id = '';

                    $PatientEntry->patient_type = 'cataract-both-eyes';

                    $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                    $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                    $PatientEntry->mc = $request->gender == 'fc' ? 1 : 0;
                    $PatientEntry->fc = $request->gender == 'mc' ? 1 : 0;

                    $PatientEntry->entry_at = date('Y-m-d');
                    $PatientEntry->save();
                } else {
                    DB::table('pec_center_patients_record')->where('center_patient_record_id', $cataract_both_eyes_rec->center_patient_record_id)->increment($gender_loc, 1);
                }

            }

        }

        if ($referal_form->slug == 'glaucoma-surgery') {
            $glaucoma_patients_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('activity_location_id', '=', $center_session['activity_location_id'])
                ->where('province_id', '=', $center_session['province_id'])
                ->where('division_id', '=', $center_session['division_id'])
                ->where('district_id', '=', $center_session['district_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', 'glaucoma-patients')
                ->first();

            if (! $glaucoma_patients_rec) {
                $PatientEntry                       = new PatientEntry();
                $PatientEntry->user_id              = $user->id;
                $PatientEntry->activity_location_id = $center_session['activity_location_id'];
                $PatientEntry->province_id          = $center_session['province_id'];
                $PatientEntry->division_id          = $center_session['division_id'];
                $PatientEntry->district_id          = $center_session['district_id'];
                $PatientEntry->tehsil_id            = $center_session['tehsil_id'];
                $PatientEntry->center_id            = $center_session['center_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = 'glaucoma-patients';

                $PatientEntry->ma = $request->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $request->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $request->gender == 'mc' ? 1 : 0;
                $PatientEntry->fc = $request->gender == 'fc' ? 1 : 0;

                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $$glaucoma_patients_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        return redirect()->route('refferal-forms.index')
            ->with('success', 'Cataract Patient created successfully.');
    }

    public function verify_refferal_patient(Request $request)
    {

        if ($request->verify) {
            RefferalForm::where('refferal_forms_id', $request->refferal_forms_id)
                ->update(['verify' => $request->verify]);
            return redirect()->route('refferal-forms.index')
                ->with('success', 'Refferal Patient Record verified');
        } else {
            RefferalForm::where('refferal_forms_id', $request->refferal_forms_id)
                ->update(['verify' => $request->verify]);
            return redirect()->route('refferal-forms.index')
                ->with('success', 'Refferal Patient Record unverified');
        }
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

        return view('refferal-forms.edit', compact('RefferalForm', 'RefferalFormReferTo', 'referral', 'referby'));
    }

    public function update(Request $request, RefferalForm $RefferalForm)
    {

        $RefferalForm->update([
            'full_name'       => $request->full_name,
            'relation'        => $request->relation,
            'relative_name'   => $request->relative_name,
            'cnic_number'     => $request->cnic_number,
            'contact_number'  => $request->contact_number,
            'gender'          => $request->gender,
            'age'             => $request->age,
            'address'         => $request->address,
            'cat_book_number' => $request->cat_book_number,
            'refer_by'        => $request->refer_by,
            'referrals'       => $request->referrals,
            'email'           => $request->input('username') . '@pec.com',
        ]);

        $RefferalFormMeta['diabetic']         = $request->diabetic;
        $RefferalFormMeta['diabetic_value']   = $request->diabetic_value;
        $RefferalFormMeta['cataract_op_slug'] = $request->cataract_op_slug;

        if (isset($request->operation_private) && $request->operation_private == 'private') {
            $RefferalFormMeta['private']            = 1;
            $RefferalFormMeta['doctor_id']          = '';
            $RefferalFormMeta['health_facility_id'] = '';
        } else {
            $RefferalFormMeta['private']            = '';
            $RefferalFormMeta['doctor_id']          = $request->doctor_id;
            $RefferalFormMeta['health_facility_id'] = $request->health_facility_id;
        }

        DB::table('pec_refferal_form_meta')
            ->where('refferal_forms_id', $RefferalForm->refferal_forms_id)
            ->update($RefferalFormMeta);

        return redirect()->route('refferal-forms.index')
            ->with('success', 'Cataract Patient Updated Successfully.');
    }

    public function destroy(RefferalForm $RefferalForm)
    {

        $cataract->delete();

        return redirect()->route('RefferalForm.index')
            ->with('success', 'Cataract deleted successfully');
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

        $user           = Auth::user();
        $center_session = session()->get('center_info');

        $RefferalFormMeta                       = new RefferalFormMeta();
        $RefferalFormMeta->refferal_forms_id    = $request->refferal_forms_id;
        $RefferalFormMeta->activity_location_id = $patient_type->activity_location_id;
        $RefferalFormMeta->user_id              = $patient_type->user_id;
        $RefferalFormMeta->province_id          = $patient_type->province_id;
        $RefferalFormMeta->division_id          = $patient_type->division_id;
        $RefferalFormMeta->district_id          = $patient_type->district_id;
        $RefferalFormMeta->tehsil_id            = $patient_type->tehsil_id;
        $RefferalFormMeta->center_id            = $patient_type->center_id;
        $RefferalFormMeta->cataract_op_slug     = $patient_type->cataract_op_slug;
        $RefferalFormMeta->patient_type         = $patient_type->patient_type;
        $RefferalFormMeta->remark               = $request->remark;
        $RefferalFormMeta->operation_date       = $request->operation_date;

        if ($request->post_op == 'number-of-patients-66618') {
            $RefferalFormMeta->post_op = '6/6-6/18';
        } else if ($request->post_op == 'number-of-patients-618660') {
            $RefferalFormMeta->post_op = '6/18-6/60';
        } else {
            $RefferalFormMeta->post_op = '6/60 or below';
        }

        if (isset($request->operation_private) && $request->operation_private == 'private') {
            $RefferalFormMeta->private = 1;
        } else {
            $RefferalFormMeta->doctor_id          = $request->doctor_id;
            $RefferalFormMeta->health_facility_id = $request->health_facility_id;
        }

        $RefferalFormMeta->confirmed_cataract_surgery = $request->confirmed_cataract_surgery;
        $RefferalFormMeta->operated_by                = 1;
        $RefferalFormMeta->save();

        if (isset($request->confirmed_cataract_surgery)) {

            $confirmed_cataract_surgery_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('province_id', '=', $center_session['province_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', $request->confirmed_cataract_surgery)
                ->first();

            if (! $confirmed_cataract_surgery_rec) {

                $PatientEntry              = new PatientEntry();
                $PatientEntry->user_id     = $user->id;
                $PatientEntry->center_id   = $center_session['center_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->tehsil_id   = $center_session['tehsil_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $request->confirmed_cataract_surgery;

                $PatientEntry->ma = $patient_info->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $patient_info->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $patient_info->gender == 'fc' ? 1 : 0;
                $PatientEntry->fc = $patient_info->gender == 'mc' ? 1 : 0;

                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $confirmed_cataract_surgery_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        if (isset($request->post_op)) {

            $post_op_rec = PatientEntry::whereDate('entry_at', '=', date('Y-m-d'))
                ->where('user_id', '=', $user->id)
                ->where('province_id', '=', $center_session['province_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->where('patient_type', '=', $request->post_op)
                ->first();

            if (! $post_op_rec) {

                $PatientEntry              = new PatientEntry();
                $PatientEntry->user_id     = $user->id;
                $PatientEntry->center_id   = $center_session['center_id'];
                $PatientEntry->province_id = $center_session['province_id'];
                $PatientEntry->tehsil_id   = $center_session['tehsil_id'];

                $PatientEntry->records_set_unique_id = '';

                $PatientEntry->patient_type = $request->post_op;

                $PatientEntry->ma = $patient_info->gender == 'ma' ? 1 : 0;
                $PatientEntry->fa = $patient_info->gender == 'fa' ? 1 : 0;
                $PatientEntry->mc = $patient_info->gender == 'fc' ? 1 : 0;
                $PatientEntry->fc = $patient_info->gender == 'mc' ? 1 : 0;

                $PatientEntry->entry_at = date('Y-m-d');
                $PatientEntry->save();
            } else {
                DB::table('pec_center_patients_record')->where('center_patient_record_id', $post_op_rec->center_patient_record_id)->increment($gender_loc, 1);
            }

        }

        return redirect()->route('refferal-forms.index')
            ->with('success', 'Patient operated by Updated.');
    }

    public function update_operated_by(Request $request, $id)
    {
        $refferal_forms_id = $id;

        $patient_info = RefferalForm::where('refferal_forms_id', $refferal_forms_id)
            ->first();

        $patient_type = RefferalFormMeta::where('refferal_forms_id', $refferal_forms_id)
            ->first();

        $patient_operated_type = RefferalFormMeta::where('refferal_forms_id', $refferal_forms_id)
            ->where('operated_by', 1)
            ->first();

        $hosptal_doctors = Doctor::where('health_facility_id', $patient_operated_type->health_facility_id)->get();

        return view('refferal-forms.edit-operated-by', compact('refferal_forms_id', 'patient_type', 'patient_info', 'patient_operated_type', 'hosptal_doctors'));
    }

    public function edit_update_operated_by(Request $request)
    {

        $RefferalFormMeta['remark']           = $request->remark;
        $RefferalFormMeta['operation_date']   = $request->operation_date;
        $RefferalFormMeta['cataract_op_slug'] = $request->cataract_op_slug;

        if ($request->post_op == 'number-of-patients-66618') {
            $RefferalFormMeta['post_op'] = '6/6-6/18';
        } else if ($request->post_op == 'number-of-patients-618660') {
            $RefferalFormMeta['post_op'] = '6/18-6/60';
        } else {
            $RefferalFormMeta['post_op'] = '6/60 or below';
        }

        if (isset($request->operation_private) && $request->operation_private == 'private') {
            $RefferalFormMeta['private']            = 1;
            $RefferalFormMeta['doctor_id']          = '';
            $RefferalFormMeta['health_facility_id'] = '';
        } else {
            $RefferalFormMeta['private']            = '';
            $RefferalFormMeta['doctor_id']          = $request->doctor_id;
            $RefferalFormMeta['health_facility_id'] = $request->health_facility_id;
        }
        // echo "<pre>";
        // print_r($RefferalFormMeta);
        // print_r($request->all());
        // echo $request->refferal_form_meta_id;
        // echo "</pre>";

        DB::table('pec_refferal_form_meta')
            ->where('pec_refferal_form_meta_id', '=', $request->refferal_form_meta_id)
            ->update($RefferalFormMeta);

        return redirect()->back()->with('success', 'Operated by information updated!');
    }
}
