<?php
namespace App\Http\Controllers;

use App\Http\Controllers\View;
use App\Models\ActivityLocation;
use App\Models\Cataract;
use App\Models\Center;
use App\Models\District;
use App\Models\Division;
use App\Models\FormCategories;
use App\Models\PatientEntry;
use App\Models\Province;
use App\Models\Referral;
use App\Models\RefferalForm;
use App\Models\Tehsil;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class PatientEntriesController extends Controller
{

    protected $moduleName = 'Cataract Patient';
    protected $moduleName1;

    // public function __construct()
    // {
    //     $this->moduleName = 'Cataract Patient';

    // }

    public function boot(): void
    {
        // View::share(['moduleFunc' => $moduleFunc]);
        View::share('moduleName', $moduleName);
        // View::share('moduleName1', $moduleName1);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $user = Auth::user();

        $patient_entries = DB::table('pec_center_patients_record')
            ->select('pec_center_patients_record.*', 'pec_center_patients_record.records_set_unique_id', 'province.province_name', 'pec_center.center_name', 'tehsil.tehsil_name', 'users.name')
            ->leftJoin('province', 'pec_center_patients_record.province_id', '=', 'province.province_id')
            ->leftJoin('tehsil', 'pec_center_patients_record.tehsil_id', '=', 'tehsil.tehsil_id')
            ->leftJoin('pec_center', 'pec_center_patients_record.center_id', '=', 'pec_center.center_id')
            ->leftJoin('users', 'pec_center_patients_record.user_id', '=', 'users.id')
            ->where('pec_center_patients_record.records_set_unique_id', '!=', '')
            ->where('pec_center_patients_record.user_id', '=', $user->id)
        // ->where('users.id', '=' , $user->id)
            ->groupBy('pec_center_patients_record.records_set_unique_id')
            ->orderBy('pec_center_patients_record.entry_at', 'desc')
            ->get();

        return view('patient-entries.index', compact('patient_entries'));
    }

    public function verify_entries_form(Request $request)
    {
        $user_id = $request->id;

        $patient_entries = DB::table('pec_center_patients_record')
            ->select('pec_center_patients_record.*', 'pec_center_patients_record.records_set_unique_id', 'province.province_name', 'pec_center.center_name', 'tehsil.tehsil_name', 'users.name')
            ->leftJoin('province', 'pec_center_patients_record.province_id', '=', 'province.province_id')
            ->leftJoin('tehsil', 'pec_center_patients_record.tehsil_id', '=', 'tehsil.tehsil_id')
            ->leftJoin('pec_center', 'pec_center_patients_record.center_id', '=', 'pec_center.center_id')
            ->leftJoin('users', 'pec_center_patients_record.user_id', '=', 'users.id')
            ->where('pec_center_patients_record.records_set_unique_id', '!=', '')
            ->where('pec_center_patients_record.user_id', '=', $user_id)
            ->where('pec_center_patients_record.verify', '=', 0)
            ->groupBy('pec_center_patients_record.records_set_unique_id')
            ->orderBy('pec_center_patients_record.entry_at', 'desc')
            ->get();

        return view('patient-entries.index', compact('patient_entries'));
    }

    public function view_all_patients_entries(Request $request)
    {

        $users             = User::orderBy('name', 'asc')->get();
        $centers           = Center::orderBy('center_name', 'asc')->get();
        $activity_location = DB::table('activity_locations')->get();

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();

        if ($request->all()) {

            $query = DB::table('pec_center_patients_record')
                ->where('user_id', '=', $request->user_id);

            if ($request->activity_location_id) {
                $query->where('activity_location_id', '=', $request->activity_location_id);
            }

            if ($request->center_id) {
                $query->where('center_id', '=', $request->center_id);
            }

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            // echo $request->searh_qauterly;
            // echo $request->year_qauterly;
            // print_r($request->all());

            $search_field_info['user_id']              = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id']            = $request->center_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['searh_qauterly']       = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly']  = $request->year_qauterly;
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

            DB::enableQueryLog(); // Enable query log

            $get_patient_entries = $query->get()->toArray();
            // dd(DB::getQueryLog()); // Show results of log

            // echo "<pre>";
            // print_r($get_patient_entries);
            // echo "</pre>";
            // die();
            $patient_entries = [];

            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['search_status']        = false;
            $search_field_info['searh_year_qauterly']  = '';
            $search_field_info['searh_qauterly']       = '';
            $filter_centers                            = [];
            $patient_entries                           = [];
            $search_field_info['filter_date']          = '';

            $get_patient_entries = PatientEntry::get()
            ->toArray();

        foreach ($get_patient_entries as $entry) {

            if (isset($patient_entries[$entry['patient_type']])) {
                $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
            } else {
                $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
            }

        }
        }



        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.view_all_patients_entries', compact('patient_entries', 'form_categories', 'users', 'search_field_info', 'activity_location', 'centers', 'filter_centers'));
    }

    public function all_patients_entries_by_report(Request $request)
    {
        DB::enableQueryLog();

        $user = Auth::user();

        $users = User::orderBy('name', 'asc')->get();

        $my_centers = DB::table('usermeta')
            ->where('user_id', $user->id)
            ->where('meta_key', 'center_id')
            ->pluck('meta_value')
            ->toArray();

        $centers = Center::orderBy('center_name', 'asc')->wherein('center_id', $my_centers)->get();

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {
            $query = DB::table('pec_center_patients_record');

            if ($request->user_id) {
                $query->where('user_id', '=', $request->user_id);
            }

            if ($request->activity_location_id) {
                $query->where('activity_location_id', '=', $request->activity_location_id);
            }

            if ($request->center_id) {
                $query->where('center_id', '=', $request->center_id);
            }

            if ($request->filter_date) {
                $query->whereDate('entry_at', '=', $request->filter_date);
            }

            if ($request->year_qauterly && $request->searh_qauterly) {
                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            $get_patient_entries = $query->get()->toArray();

            // dd(DB::getQueryLog());

            // print_r($request->all());
            // echo $request->searh_qauterly;
            // echo $request->searh_year_qauterly;

            // die();

            $search_field_info = [];

            $search_field_info['user_id'] = $request->user_id;

            if ($request->user_id) {
                $search_field_info['user_name'] = User::get_username_by_id($request->user_id);
            } else {
                $search_field_info['user_name'] = '';
            }

            $search_field_info['activity_location_id'] = $request->activity_location_id;

            if ($request->activity_location_id) {
                $search_field_info['activity_name'] = ActivityLocation::get_activity_location_name_by_id($request->activity_location_id);
            } else {
                $search_field_info['activity_name'] = '';
            }

            $search_field_info['center_id'] = $request->center_id;

            if ($request->center_id) {
                $search_field_info['center_name'] = Center::get_center_name_by_id($request->center_id);
            } else {
                $search_field_info['center_name'] = '';
            }

            $search_field_info['filter_date']     = $request->filter_date;
            $search_field_info['filter_cat_slug'] = $request->filter_cat_slug;
            $search_field_info['search_status']   = true;

            if ($get_patient_entries) {

                foreach ($get_patient_entries as $entry) {
                    $entry = (array) $entry;

                    if (isset($patient_entries[$entry['patient_type']])) {
                        $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                        $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                        $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                        $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                    } else {
                        $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                        $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                        $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                        $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                    }

                }

            } else {
                $patient_entries = [];
            }

            $search_field_info['searh_month']         = $request->searh_month;
            $search_field_info['searh_qauterly']      = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly'] = $request->year_qauterly;
            $search_field_info['search_status']       = true;

        } else {
            $get_patient_entries = PatientEntry::where('user_id', $user->id)
                ->get()
                ->toArray();

            $search_field_info                         = [];
            $search_field_info['user_id']              = '';
            $search_field_info['user_name']            = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['filter_date']          = '';
            $search_field_info['filter_cat_slug']      = '';
            $search_field_info['searh_year_qauterly']  = '';
            $search_field_info['searh_qauterly']       = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['search_status']        = false;

            foreach ($get_patient_entries as $entry) {

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.all_patients_entries_by_report', compact('patient_entries', 'form_categories', 'users', 'search_field_info', 'activity_location', 'centers'));
    }

    public function all_patients_entries_by_report_pdf()
    {
        $user_id              = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        $activity_location_id = isset($_GET['activity_location_id']) ? $_GET['activity_location_id'] : '';
        $center_id            = isset($_GET['center_id']) ? $_GET['center_id'] : '';
        $filter_cat_slug      = isset($_GET['filter_cat_slug']) ? $_GET['filter_cat_slug'] : '';
        $filter_date          = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
        $searh_qauterly       = isset($_GET['searh_qauterly']) ? $_GET['searh_qauterly'] : '';
        $year_qauterly        = isset($_GET['year_qauterly']) ? $_GET['year_qauterly'] : '';

        $search_field_info['filter_date']     = $filter_date;
        $search_field_info['filter_cat_slug'] = $filter_cat_slug;

        $user = Auth::user();

        $query = DB::table('pec_center_patients_record');
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }

        if ($activity_location_id) {
            $query->where('activity_location_id', '=', $activity_location_id);
        }

        if ($center_id) {
            $query->where('center_id', '=', $center_id);
        }

        if ($filter_date) {
            $query->whereDate('entry_at', '=', $filter_date);
        }

        if ($year_qauterly && $searh_qauterly) {
            if ($searh_qauterly == '1') {
                $query->whereMonth('entry_at', '>=', 1);
                $query->whereMonth('entry_at', '<=', 3);
                $query->whereYear('entry_at', '=', $year_qauterly);
            } elseif ($searh_qauterly == '2') {
                $query->whereMonth('entry_at', '>=', 4);
                $query->whereMonth('entry_at', '<=', 6);
                $query->whereYear('entry_at', '=', $year_qauterly);
            } elseif ($searh_qauterly == '3') {
                $query->whereMonth('entry_at', '>=', 7);
                $query->whereMonth('entry_at', '<=', 9);
                $query->whereYear('entry_at', '=', $year_qauterly);
            } elseif ($searh_qauterly == '4') {
                $query->whereMonth('entry_at', '>=', 10);
                $query->whereMonth('entry_at', '<=', 12);
                $query->whereYear('entry_at', '=', $year_qauterly);
            }
        }

        $get_patient_entries = $query->get()->toArray();

        $search_field_info = [];

        $search_field_info['user_id'] = $user_id;

        if (isset($user_id) && $user_id != '') {
            $search_field_info['user_name'] = User::get_username_by_id($user_id);
        } else {
            $search_field_info['user_name'] = '';
        }

        $search_field_info['activity_location_id'] = $activity_location_id;

        if ($activity_location_id) {
            $search_field_info['activity_name'] = ActivityLocation::get_activity_location_name_by_id($activity_location_id);
        } else {
            $search_field_info['activity_name'] = '';
        }

        $search_field_info['center_id'] = $center_id;

        if ($center_id) {
            $search_field_info['center_name'] = Center::get_center_name_by_id($center_id);
        } else {
            $search_field_info['center_name'] = '';
        }

        $search_field_info['filter_date']     = $filter_date;
        $search_field_info['filter_cat_slug'] = $filter_cat_slug;
        $search_field_info['search_status']   = true;

        if ($get_patient_entries) {

            foreach ($get_patient_entries as $entry) {
                $entry = (array) $entry;

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patient_entries = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        $pdf = PDF::loadView('patient-entries.all_patients_entries_by_report_pdf', compact('patient_entries', 'form_categories', 'search_field_info'));

        $pdf_name = 'patient-entries';

        if ($search_field_info['user_name']) {
            $pdf_name = $pdf_name . '-' . Str::slug($search_field_info['user_name']);
        }

        if ($search_field_info['activity_name']) {
            $pdf_name = $pdf_name . '-' . Str::slug($search_field_info['activity_name']);
        }

        if ($search_field_info['center_name']) {
            $pdf_name = $pdf_name . '-' . Str::slug($search_field_info['center_name']);
        }

        if ($search_field_info['filter_date']) {
            $pdf_name = $pdf_name . '-' . $search_field_info['filter_date'];
        }

        if ($search_field_info['filter_cat_slug']) {
            $pdf_name = $pdf_name . '-' . $search_field_info['filter_cat_slug'];
        }

        $pdf_name = $pdf_name . '-' . 'date-' . date('Y-m-d-h-i-s-A');

        //return view('patient-entries.all_patients_entries_by_report_pdf',compact('patient_entries', 'form_categories', 'search_field_info'));
        return $pdf->download($pdf_name . '.pdf');
        die();
    }

    public function all_patients_entries_by_admin_report_pdf()
    {

        $user_id              = isset($_GET['user_id']) ? $_GET['user_id'] : '';
        $activity_location_id = isset($_GET['activity_location_id']) ? $_GET['activity_location_id'] : '';
        $center_id            = isset($_GET['center_id']) ? $_GET['center_id'] : '';
        $district_id          = isset($_GET['district_id']) ? $_GET['district_id'] : '';
        $division_id          = isset($_GET['division_id']) ? $_GET['division_id'] : '';
        $province_id          = isset($_GET['province_id']) ? $_GET['province_id'] : '';
        $date_from            = isset($_GET['filter_from_date']) ? $_GET['filter_from_date'] : '';
        $date_to              = isset($_GET['filter_to_date']) ? $_GET['filter_to_date'] : '';
        $searh_month          = isset($_GET['filter_searh_month']) ? $_GET['filter_searh_month'] : '';
        $searh_year_qauterly  = isset($_GET['filter_year_qauterly']) ? $_GET['filter_year_qauterly'] : '';
        $searh_qauterly       = isset($_GET['filter_searh_qauterly']) ? $_GET['filter_searh_qauterly'] : '';

        $filter_cat_slug = isset($_GET['filter_cat_slug']) ? $_GET['filter_cat_slug'] : '';
        $filter_date     = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

        $search_field_info['filter_date']     = $filter_date;
        $search_field_info['filter_cat_slug'] = $filter_cat_slug;

        // print_r($_GET);
        // die();

        $user = Auth::user();

        $query = DB::table('pec_center_patients_record');
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }

        if ($activity_location_id) {
            $query->where('activity_location_id', '=', $activity_location_id);
        }

        if ($center_id) {
            $query->where('center_id', '=', $center_id);
        }
        if ($district_id) {
            $query->where('district_id', '=', $district_id);
        }
        if ($division_id) {
            $query->where('division_id', '=', $division_id);
        }
        if ($province_id) {
            $query->where('province_id', '=', $province_id);
        }

        if ($searh_month) {
            $get_month = date('m', strtotime($searh_month));
            $get_year  = date('Y', strtotime($searh_month));
            $query->whereMonth('entry_at', '=', $get_month);
            $query->whereYear('entry_at', '=', $get_year);
        }

        if ($date_from != '' && $date_to == '') {
            $query->whereDate('entry_at', '=', $date_from);
        } elseif ($date_from == '' && $date_to != '') {
            $query->whereDate('entry_at', '<=', $date_to);
        } elseif ($date_from != '' && $date_to != '') {
            $query->whereDate('entry_at', '>=', $date_from);
            $query->whereDate('entry_at', '<=', $date_to);
        }

        // echo $searh_qauterly;
        // echo $searh_year_qauterly;

        if ($searh_qauterly && $searh_year_qauterly) {

            if ($searh_qauterly == '1') {
                $query->whereMonth('entry_at', '>=', 1);
                $query->whereMonth('entry_at', '<=', 3);
                $query->whereYear('entry_at', '=', $searh_year_qauterly);
            } elseif ($searh_qauterly == '2') {
                $query->whereMonth('entry_at', '>=', 4);
                $query->whereMonth('entry_at', '<=', 6);
                $query->whereYear('entry_at', '=', $searh_year_qauterly);
            } elseif ($searh_qauterly == '3') {
                $query->whereMonth('entry_at', '>=', 7);
                $query->whereMonth('entry_at', '<=', 9);
                $query->whereYear('entry_at', '=', $searh_year_qauterly);
            } elseif ($searh_qauterly == '4') {
                $query->whereMonth('entry_at', '>=', 10);
                $query->whereMonth('entry_at', '<=', 12);
                $query->whereYear('entry_at', '=', $searh_year_qauterly);
            }
        }

        DB::enableQueryLog();

        $get_patient_entries = $query->get()->toArray();

        // dd(DB::getQueryLog());

        // die();

        $search_field_info = [];

        $search_field_info['user_id'] = $user_id;

        if (isset($user_id) && $user_id != '') {
            $search_field_info['user_name'] = User::get_username_by_id($user_id);
        } else {
            $search_field_info['user_name'] = '';
        }

        $search_field_info['activity_location_id'] = $activity_location_id;

        if ($activity_location_id) {
            $search_field_info['activity_name'] = ActivityLocation::get_activity_location_name_by_id($activity_location_id);
        } else {
            $search_field_info['activity_name'] = '';
        }

        $search_field_info['center_id'] = $center_id;

        if ($center_id) {
            $search_field_info['center_name'] = Center::get_center_name_by_id($center_id);
        } else {
            $search_field_info['center_name'] = '';
        }

        $search_field_info['filter_date']     = $filter_date;
        $search_field_info['filter_cat_slug'] = $filter_cat_slug;
        $search_field_info['search_status']   = true;

        if ($get_patient_entries) {

            foreach ($get_patient_entries as $entry) {
                $entry = (array) $entry;

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patient_entries = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        $pdf = PDF::loadView('patient-entries.all_patients_entries_by_report_pdf', compact('patient_entries', 'form_categories', 'search_field_info'));

        $pdf_name = 'patient-entries';

        if ($search_field_info['user_name']) {
            $pdf_name = $pdf_name . '-' . Str::slug($search_field_info['user_name']);
        }

        if ($search_field_info['activity_name']) {
            $pdf_name = $pdf_name . '-' . Str::slug($search_field_info['activity_name']);
        }

        if ($search_field_info['center_name']) {
            $pdf_name = $pdf_name . '-' . Str::slug($search_field_info['center_name']);
        }

        if ($search_field_info['filter_date']) {
            $pdf_name = $pdf_name . '-' . $search_field_info['filter_date'];
        }

        if ($search_field_info['filter_cat_slug']) {
            $pdf_name = $pdf_name . '-' . $search_field_info['filter_cat_slug'];
        }

        $pdf_name = $pdf_name . '-' . 'date-' . date('Y-m-d-h-i-s-A');

        // return view('patient-entries.all_patients_entries_by_report_pdf',compact('patient_entries', 'form_categories', 'search_field_info'));
        return $pdf->download($pdf_name . '.pdf');
        die();
    }

    public function all_patients_entries_by_report_pdf_view()
    {
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date'  => date('m/d/Y'),
            'users' => '',
        ];

        $user = Auth::user();

        $get_patient_entries = PatientEntry::where('user_id', $user->id)
            ->get()
            ->toArray();

        foreach ($get_patient_entries as $entry) {

            if (isset($patient_entries[$entry['patient_type']])) {
                $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
            } else {
                $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
            }

        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.all_patients_entries_by_report_pdf', compact('patient_entries', 'form_categories'));
    }

    public function patients_entries_by_user(Request $request)
    {

        $users = User::orderBy('name', 'asc')->get();

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {

            $query = DB::table('pec_center_patients_record')
                ->where('user_id', '=', $request->user_id);

            if ($request->activity_location_id) {
                $query->where('activity_location_id', '=', $request->activity_location_id);
            }

            if ($request->center_id) {
                $query->where('center_id', '=', $request->center_id);
            }

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            // echo $request->searh_qauterly;
            // echo $request->year_qauterly;
            // print_r($request->all());

            $search_field_info['user_id']              = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id']            = $request->center_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['searh_qauterly']       = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly']  = $request->year_qauterly;
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

            DB::enableQueryLog(); // Enable query log

            $get_patient_entries = $query->get()->toArray();
            // dd(DB::getQueryLog()); // Show results of log

            // echo "<pre>";
            // print_r($get_patient_entries);
            // echo "</pre>";
            // die();
            $patient_entries = [];

            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['searh_qauterly']       = '';
            $search_field_info['searh_year_qauterly']  = '';
            $search_field_info['search_status']        = false;
            $filter_centers                            = [];
            $patient_entries                           = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_user', compact('users', 'activity_location', 'search_field_info', 'filter_centers', 'form_categories', 'patient_entries'));
    }

    public function patients_entries_by_stat(Request $request)
    {

        DB::enableQueryLog();

        if ($_GET) {

            $query = DB::table('pec_center_patients_record');

            if (isset($_GET['user_id'])) {
                $query->where('user_id', '=', $_GET['user_id']);
            }

            // if(isset($_GET['month']))
            // {
            //     $get_month =  date('m', strtotime($_GET['month']));

            //     $query->whereMonth('entry_at', '=' , $get_month);
            // }

            if (isset($_GET['date'])) {
                $query->whereDate('created_at', '=', date('Y-m-d', strtotime($_GET['date'])));
            }

            if (isset($_GET['center_id'])) {
                $query->where('center_id', '=', $_GET['center_id']);
            }

            $search_field_info['search_status'] = true;

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

            $get_patient_entries = $query->get()->toArray();

            // $query = DB::getQueryLog();
            // dd($query);

            $patient_entries = [];

            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['search_status']        = false;
            $filter_centers                            = [];
            $patient_entries                           = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_stat', compact('form_categories', 'patient_entries', 'search_field_info'));
    }

    public function patients_entries_by_center(Request $request)
    {

        $centers = Center::orderBy('center_name', 'asc')->get();

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {
            // print_r($request->all());

            $query = DB::table('pec_center_patients_record')
                ->where('center_id', '=', $request->center_id);

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            // die();

            $search_field_info['user_id']              = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id']            = $request->center_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['searh_qauterly']       = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly']  = $request->year_qauterly;

            $search_field_info['search_status'] = true;

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
            $get_patient_entries = $query->get()->toArray();
            // echo "<pre>";
            // print_r($get_patient_entries);
            // echo "</pre>";
            // die();
            $patient_entries = [];
            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['searh_qauterly']       = '';
            $search_field_info['searh_year_qauterly']  = '';
            $search_field_info['search_status']        = false;
            $filter_centers                            = [];
            $patient_entries                           = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_center', compact('centers', 'activity_location', 'patient_entries', 'search_field_info', 'filter_centers', 'form_categories', 'patient_entries'));
    }

    public function patients_entries_by_location(Request $request)
    {

        $centers = Center::orderBy('center_name', 'asc')->get();

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {
            // print_r($request->all());

            $query = DB::table('pec_center_patients_record')
                ->where('activity_location_id', '=', $request->activity_location_id);

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            // die();

            $search_field_info['user_id']              = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['center_id']            = $request->center_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['searh_qauterly']       = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly']  = $request->year_qauterly;
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
            $get_patient_entries = $query->get()->toArray();
            // echo "<pre>";
            // print_r($get_patient_entries);
            // echo "</pre>";
            // die();
            $patient_entries = [];
            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['center_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['searh_qauterly']       = '';
            $search_field_info['searh_year_qauterly']  = '';
            $search_field_info['search_status']        = false;
            $filter_centers                            = [];
            $patient_entries                           = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_location', compact('centers', 'activity_location', 'patient_entries', 'search_field_info', 'filter_centers', 'form_categories', 'patient_entries'));
    }

    public function patients_entries_by_tehsil(Request $request)
    {

        $tehsils = Tehsil::orderBy('tehsil_name', 'asc')->get();

        $activity_location = DB::table('activity_locations')
            ->get();

        if ($request->all()) {
            // print_r($request->all());

            $query = DB::table('pec_center_patients_record')
                ->where('tehsil_id', '=', $request->tehsil_id);

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            // die();

            $search_field_info['user_id']              = $request->user_id;
            $search_field_info['activity_location_id'] = $request->activity_location_id;
            $search_field_info['tehsil_id']            = $request->tehsil_id;
            $search_field_info['from_date']            = $request->from_date;
            $search_field_info['to_date']              = $request->to_date;
            $search_field_info['searh_month']          = $request->searh_month;
            $search_field_info['searh_qauterly']       = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly']  = $request->year_qauterly;
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
            $get_patient_entries = $query->get()->toArray();
            // echo "<pre>";
            // print_r($get_patient_entries);
            // echo "</pre>";
            // die();
            $patient_entries = [];
            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']              = '';
            $search_field_info['activity_location_id'] = '';
            $search_field_info['tehsil_id']            = '';
            $search_field_info['from_date']            = '';
            $search_field_info['to_date']              = '';
            $search_field_info['searh_month']          = '';
            $search_field_info['searh_qauterly']       = '';
            $search_field_info['searh_year_qauterly']  = '';
            $search_field_info['search_status']        = false;
            $filter_centers                            = [];
            $patient_entries                           = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_tehsil', compact('tehsils', 'activity_location', 'search_field_info', 'filter_centers', 'form_categories', 'patient_entries'));
    }

    public function patients_entries_by_district(Request $request)
    {

        $districts = District::orderBy('district_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_center_patients_record')
                ->where('district_id', '=', $request->district_id);

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            $search_field_info['user_id']             = $request->user_id;
            $search_field_info['district_id']         = $request->district_id;
            $search_field_info['tehsil_id']           = $request->tehsil_id;
            $search_field_info['from_date']           = $request->from_date;
            $search_field_info['to_date']             = $request->to_date;
            $search_field_info['searh_month']         = $request->searh_month;
            $search_field_info['searh_qauterly']      = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly'] = $request->year_qauterly;
            $search_field_info['search_status']       = true;

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

            $get_patient_entries = $query->get()->toArray();

            $patient_entries = [];
            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']             = '';
            $search_field_info['district_id']         = '';
            $search_field_info['tehsil_id']           = '';
            $search_field_info['from_date']           = '';
            $search_field_info['to_date']             = '';
            $search_field_info['searh_month']         = '';
            $search_field_info['searh_qauterly']      = '';
            $search_field_info['searh_year_qauterly'] = '';
            $search_field_info['search_status']       = false;
            $filter_centers                           = [];
            $patient_entries                          = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_district', compact('districts', 'patient_entries', 'search_field_info', 'filter_centers', 'form_categories'));
    }

    public function patients_entries_by_division(Request $request)
    {

        $divisions = Division::orderBy('division_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_center_patients_record')
                ->where('division_id', '=', $request->division_id);

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            $search_field_info['user_id']             = $request->user_id;
            $search_field_info['division_id']         = $request->division_id;
            $search_field_info['tehsil_id']           = $request->tehsil_id;
            $search_field_info['from_date']           = $request->from_date;
            $search_field_info['to_date']             = $request->to_date;
            $search_field_info['searh_month']         = $request->searh_month;
            $search_field_info['searh_qauterly']      = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly'] = $request->year_qauterly;
            $search_field_info['search_status']       = true;

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

            $get_patient_entries = $query->get()->toArray();

            $patient_entries = [];
            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']             = '';
            $search_field_info['division_id']         = '';
            $search_field_info['tehsil_id']           = '';
            $search_field_info['from_date']           = '';
            $search_field_info['to_date']             = '';
            $search_field_info['searh_month']         = '';
            $search_field_info['searh_qauterly']      = '';
            $search_field_info['searh_year_qauterly'] = '';
            $search_field_info['search_status']       = false;
            $filter_centers                           = [];
            $patient_entries                          = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_division', compact('divisions', 'patient_entries', 'search_field_info', 'filter_centers', 'form_categories'));
    }

    public function patients_entries_by_province(Request $request)
    {

        $provinces = Province::orderBy('province_name', 'asc')->get();

        if ($request->all()) {

            $query = DB::table('pec_center_patients_record')
                ->where('province_id', '=', $request->province_id);

            if ($request->searh_month) {
                $get_month = date('m', strtotime($request->searh_month));
                $get_year  = date('Y', strtotime($request->searh_month));
                $query->whereMonth('entry_at', '=', $get_month);
                $query->whereYear('entry_at', '=', $get_year);
            }

            if ($request->from_date != '' && $request->to_date == '') {
                $query->whereDate('entry_at', '=', $request->from_date);
            } elseif ($request->from_date == '' && $request->to_date != '') {
                $query->whereDate('entry_at', '<=', $request->to_date);
            } elseif ($request->from_date != '' && $request->to_date != '') {
                $query->whereDate('entry_at', '>=', $request->from_date);
                $query->whereDate('entry_at', '<=', $request->to_date);
            }

            if ($request->searh_qauterly && $request->year_qauterly) {

                if ($request->searh_qauterly == '1') {
                    $query->whereMonth('entry_at', '>=', 1);
                    $query->whereMonth('entry_at', '<=', 3);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '2') {
                    $query->whereMonth('entry_at', '>=', 4);
                    $query->whereMonth('entry_at', '<=', 6);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '3') {
                    $query->whereMonth('entry_at', '>=', 7);
                    $query->whereMonth('entry_at', '<=', 9);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                } elseif ($request->searh_qauterly == '4') {
                    $query->whereMonth('entry_at', '>=', 10);
                    $query->whereMonth('entry_at', '<=', 12);
                    $query->whereYear('entry_at', '=', $request->year_qauterly);
                }
            }

            $search_field_info['user_id']             = $request->user_id;
            $search_field_info['province_id']         = $request->province_id;
            $search_field_info['tehsil_id']           = $request->tehsil_id;
            $search_field_info['from_date']           = $request->from_date;
            $search_field_info['to_date']             = $request->to_date;
            $search_field_info['searh_month']         = $request->searh_month;
            $search_field_info['searh_qauterly']      = $request->searh_qauterly;
            $search_field_info['searh_year_qauterly'] = $request->year_qauterly;
            $search_field_info['search_status']       = true;

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

            $get_patient_entries = $query->get()->toArray();

            $patient_entries = [];
            foreach ($get_patient_entries as $entry) {

                $entry = json_decode(json_encode($entry), true);

                if (isset($patient_entries[$entry['patient_type']])) {
                    $patient_entries[$entry['patient_type']]['mc'] += $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] += $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] += $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] += $entry['fa'];
                } else {
                    $patient_entries[$entry['patient_type']]['mc'] = $entry['mc'];
                    $patient_entries[$entry['patient_type']]['fc'] = $entry['fc'];
                    $patient_entries[$entry['patient_type']]['ma'] = $entry['ma'];
                    $patient_entries[$entry['patient_type']]['fa'] = $entry['fa'];
                }

            }

        } else {
            $patients      = '';
            $patient_count = '';

            $search_field_info['user_id']             = '';
            $search_field_info['province_id']         = '';
            $search_field_info['tehsil_id']           = '';
            $search_field_info['from_date']           = '';
            $search_field_info['to_date']             = '';
            $search_field_info['searh_month']         = '';
            $search_field_info['searh_qauterly']      = '';
            $search_field_info['searh_year_qauterly'] = '';
            $search_field_info['search_status']       = false;
            $filter_centers                           = [];
            $patient_entries                          = [];
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('patient-entries.patients_entries_by_province', compact('provinces', 'patient_entries', 'search_field_info', 'filter_centers', 'form_categories'));
    }

    public function create()
    {
        $user = Auth::user();

        if (session()->get('center_info')) {
            $center_session = session()->get('center_info');

            $patient_entries = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
                ->where('user_id', '=', $user->id)
                ->where('province_id', '=', $center_session['province_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->get()
                ->keyBy('patient_type')
                ->toArray();

            return view('patient-entries.create', compact('patient_entries'));

        } else {
            return view('patient-entries.create');
        }
    }

    public function total_patients_examined()
    {
        return view('patient-entries.total_patients_examined');
    }

    public function referrals()
    {
        $user = Auth::user();

        $referral_key = Referral::pluck('slug')
            ->toArray();
        // print_r($referral_key);
        if (session()->get('center_info')) {
            $center_session = session()->get('center_info');

            $patient_entries = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
                ->where('user_id', '=', $user->id)
                ->where('province_id', '=', $center_session['province_id'])
                ->where('tehsil_id', '=', $center_session['tehsil_id'])
                ->where('center_id', '=', $center_session['center_id'])
                ->whereIn('patient_type', $referral_key)
                ->get()
                ->keyBy('patient_type')
                ->toArray();

            // return view('patient-entries.create', compact( 'patient_entries'));

        } else {
            // return view('patient-entries.create');
        }
        // echo "<pre>";
        // print_r($patient_entries);
        // echo "</pre>";
        // die();

        $referral = Referral::get()
            ->toArray();
        return view('patient-entries.referrals', compact('referral', 'patient_entries'));
    }

    public function store(Request $request)
    {
        // $center_session = session()->get('center_info');
        // echo "<pre>";
        //     print_r($_POST);

        // echo "</pre>";
        // echo $request->disability_remarks;
        // die();
        $user = Auth::user();

        if (session()->get('center_info')) {
            $center_session = session()->get('center_info');
            // echo "<pre>";
            // print_r($center_session);
            // echo "</pre>";
            $form_link = $_POST['form_link_slug'];

            $first = true;
            foreach ($_POST['patient_entry'] as $key => $val) {
                // echo "<pre>";
                // print_r($val);
                // echo "</pre>";

                // if( $val['clinics_ma'] != '' || $val['clinics_fa'] != '' || $val['clinics_mc'] != '' || $val['clinics_fc'] != '' )
                if (
                    $val['ma'] != '' ||
                    $val['fa'] != '' ||
                    $val['mc'] != '' ||
                    $val['fc'] != '') {

                    $entry_exist = PatientEntry::whereDate('entry_at', '=', $center_session['entry_date'])
                        ->where('patient_type', '=', $key)
                        ->where('user_id', '=', $user->id)
                        ->where('activity_location_id', '=', $center_session['activity_location_id'])
                        ->where('province_id', '=', $center_session['province_id'])
                        ->where('division_id', '=', $center_session['division_id'])
                        ->where('district_id', '=', $center_session['district_id'])
                        ->where('tehsil_id', '=', $center_session['tehsil_id'])
                        ->where('center_id', '=', $center_session['center_id'])
                        ->first();
                    // ->toArray();

                    if (empty($entry_exist)) {
                        // echo "Add Array<br>";

                        $last_inserted_id = PatientEntry::create([

                            'user_id'              => $user->id,
                            'activity_location_id' => $center_session['activity_location_id'],
                            'center_id'            => $center_session['center_id'],
                            'province_id'          => $center_session['province_id'],
                            'division_id'          => $center_session['division_id'],
                            'district_id'          => $center_session['district_id'],
                            'tehsil_id'            => $center_session['tehsil_id'],

                            'patient_type'         => $key,

                            'ma'                   => ($val['ma']) ? $val['ma'] : 0,
                            'fa'                   => ($val['fa']) ? $val['fa'] : 0,
                            'mc'                   => ($val['mc']) ? $val['mc'] : 0,
                            'fc'                   => ($val['fc']) ? $val['fc'] : 0,

                            'entry_at'             => $center_session['entry_date'],
                        ]);

                        if ($key == 'person-with-disability' && isset($request->disability_remarks)) {
                            PatientEntry::where('center_patient_record_id', $last_inserted_id->center_patient_record_id)
                                ->update(['remarks' => $request->disability_remarks]);
                        }

                        if ($first) {
                            $first     = false;
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
                            // echo "kkkkk";
                            // print_r($key_exist);
                            if (! $key_exist) {
                                $unique_id = hexdec(uniqid() . rand(10, 100));
                                echo $last_inserted_id->center_patient_record_id;

                                $last_inserted_id->center_patient_record_id;
                                PatientEntry::where('center_patient_record_id', $last_inserted_id->center_patient_record_id)
                                    ->update(['records_set_unique_id' => $unique_id]);
                            }

                        }
                    } else {

                        PatientEntry::where('center_patient_record_id', '=', $entry_exist->center_patient_record_id)->update([

                            'ma' => ($val['ma']) ? $val['ma'] : 0,
                            'fa' => ($val['fa']) ? $val['fa'] : 0,
                            'mc' => ($val['mc']) ? $val['mc'] : 0,
                            'fc' => ($val['fc']) ? $val['fc'] : 0,

                        ]);

                        if ($key == 'person-with-disability' && isset($request->disability_remarks)) {
                            PatientEntry::where('center_patient_record_id', $entry_exist->center_patient_record_id)
                                ->update(['remarks' => $request->disability_remarks]);
                        }
                    }

                }
            }

        }

        return Redirect::to('patient-entries/' . $form_link)->with('success', 'Cataract Patient Information Added!');
        // return redirect()->route('patient-entries.'.$form_link)
        //                 ->with('success','Cataract Patient Information Added!');
    }

    public function update_entries_form(Request $request, PatientEntry $PatientEntry)
    {
        $user = Auth::user();

        $records_set_unique_id = $request->id;

        $records_set_unique_id_info = PatientEntry::where('records_set_unique_id', '=', $records_set_unique_id)
            ->get()
            ->first();

        $patient_entries = PatientEntry::whereDate('entry_at', '=', $records_set_unique_id_info->entry_at)
            ->where('user_id', '=', $records_set_unique_id_info->user_id)
            ->where('province_id', '=', $records_set_unique_id_info->province_id)
            ->where('tehsil_id', '=', $records_set_unique_id_info->tehsil_id)
            ->where('center_id', '=', $records_set_unique_id_info->center_id)
            ->get()
            ->keyBy('patient_type')
            ->toArray();

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        // dd(DB::getQueryLog());
        // print_r($get_record_set);

        return view('patient-entries.update_entries_form', compact('patient_entries', 'form_categories', 'records_set_unique_id', 'records_set_unique_id_info'));
    }

    public function update_entries_saved(Request $request, PatientEntry $PatientEntry)
    {

        $records_set_unique_id_info = PatientEntry::where('records_set_unique_id', '=', $request->records_set_unique_id)
            ->get()
            ->first();

        // whereDate('entry_at', '=' , $records_set_unique_id_info->entry_at)
        // ->where('user_id', '=',  $records_set_unique_id_info->user_id)
        // ->where('province_id', '=',  $records_set_unique_id_info->province_id)
        // ->where('tehsil_id', '=',  $records_set_unique_id_info->tehsil_id)
        // ->where('center_id', '=',  $records_set_unique_id_info->center_id)

        if ($request->verify_patient_entry) {
            PatientEntry::whereDate('entry_at', '=', $records_set_unique_id_info->entry_at)
                ->where('user_id', '=', $records_set_unique_id_info->user_id)
                ->where('activity_location_id', '=', $records_set_unique_id_info->activity_location_id)
                ->where('province_id', '=', $records_set_unique_id_info->province_id)
                ->where('division_id', '=', $records_set_unique_id_info->division_id)
                ->where('district_id', '=', $records_set_unique_id_info->district_id)
                ->where('tehsil_id', '=', $records_set_unique_id_info->tehsil_id)
                ->where('center_id', '=', $records_set_unique_id_info->center_id)
                ->update([
                    'verify' => 1,
                ]);

            $RefferalForm = RefferalForm::whereDate('entry_at', '=', $records_set_unique_id_info->entry_at)
                ->where('user_id', '=', $records_set_unique_id_info->user_id)
                ->update([
                    'verify' => 1,
                ]);

            return redirect('/patient-entries/verify-entries/' . $request->pec_user_id)->with('success', 'Entry Verified successfully');
        }

        foreach ($request->patient_entry as $key => $val) {
            // echo $key;
            // print_r($entry);
            if ($val['entry_existing_id'] == 'new') {
                if ($val['ma'] != '' || $val['mc'] != '' || $val['mc'] != '' || $val['mc'] != '') {
                    echo $val['ma'];
                    echo "new<br>";
                    PatientEntry::create([

                        'user_id'              => $request->user_id,
                        'activity_location_id' => $request->activity_location_id,
                        'center_id'            => $request->center_id,
                        'province_id'          => $request->province_id,
                        'division_id'          => $request->division_id,
                        'district_id'          => $request->district_id,
                        'tehsil_id'            => $request->tehsil_id,

                        'patient_type'         => $key,

                        'ma'                   => ($val['ma']) ? $val['ma'] : 0,
                        'fa'                   => ($val['fa']) ? $val['fa'] : 0,
                        'mc'                   => ($val['mc']) ? $val['mc'] : 0,
                        'fc'                   => ($val['fc']) ? $val['fc'] : 0,

                        'entry_at'             => $request->entry_at,
                    ]);
                }
            } else {
                if ($val['ma'] != '' || $val['mc'] != '' || $val['mc'] != '' || $val['mc'] != '') {
                    echo $val['entry_existing_id'] . "<br>";
                    PatientEntry::where('center_patient_record_id', '=', $val['entry_existing_id'])
                        ->update([
                            'ma' => ($val['ma']) ? $val['ma'] : 0,
                            'fa' => ($val['fa']) ? $val['fa'] : 0,
                            'mc' => ($val['mc']) ? $val['mc'] : 0,
                            'fc' => ($val['fc']) ? $val['fc'] : 0,
                        ]);
                } else {
                    // PatientEntry::where('center_patient_record_id',$id)->delete();
                }
            }
        }

        return redirect('/patient-entries/update-entries/' . $request->records_set_unique_id)->with('success', 'Entries updated successfully');
    }

    public function show(PatientEntry $PatientEntry)
    {
        $user = Auth::user();

        $patient_entries = PatientEntry::whereDate('entry_at', '=', $PatientEntry->entry_at)

            ->where('user_id', '=', $user->id)
            ->where('province_id', '=', $PatientEntry->province_id)
            ->where('tehsil_id', '=', $PatientEntry->tehsil_id)
            ->where('center_id', '=', $PatientEntry->center_id)
            ->get()
            ->keyBy('patient_type')
            ->toArray();

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        // dd(DB::getQueryLog());
        // print_r($get_record_set);
        // die();
        return view('patient-entries.show', compact('patient_entries', 'form_categories'));
    }

    public function edit(Cataract $cataract)
    {
        return view('cataracts.edit', compact('cataract'));
    }

    public function update(Request $request, PatientEntry $PatientEntry)
    {
        // $PatientEntry->update($request->all());

        // return redirect()->route('cataracts.index')
        //                 ->with('success','Cataract updated successfully');
    }

    public function destroy(Cataract $cataract)
    {

        $cataract->delete();

        return redirect()->route('cataracts.index')
            ->with('success', 'Cataract deleted successfully');
    }
}
