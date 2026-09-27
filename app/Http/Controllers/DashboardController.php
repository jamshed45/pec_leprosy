<?php
namespace App\Http\Controllers;

use App\Models\FormCategories;
use App\Models\PatientEntry;
use App\Models\RefferalForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if ($request->has('date')) {
            $get_date = $request->query('date');
        } else {
            $get_date = '';
        }

        if ($request->has('month')) {
            $get_month = $request->query('month');
        } else {
            $get_month = '';
        }

        if ($request->has('year')) {
            $get_year = $request->query('year');
        } else {
            $get_year = '';
        }

        DB::enableQueryLog();
        $user = Auth::user();
        // echo $user->id;

        if ($user->hasRole('pec-user')) {

            $total_patient_examined = PatientEntry::query();
            $total_patient_examined = $total_patient_examined->where('user_id', $user->id);

            $total_patient_examined = $total_patient_examined->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $total_patient_examined = $total_patient_examined->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $total_patient_examined = $total_patient_examined->whereMonth('created_at', date('m', strtotime($get_month)));
                $total_patient_examined = $total_patient_examined->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $total_patient_examined = $total_patient_examined->whereYear('created_at', $get_year);
            }
            $total_patient_examined = $total_patient_examined->whereIn('patient_type', ['new-patients', 'old-patients', 'leprosy-patients', 'number-of-children-under-six'])
                ->get()
                ->toArray();

            $refractive_errors = PatientEntry::query();
            $refractive_errors = $refractive_errors->where('user_id', $user->id);

            $refractive_errors = $refractive_errors->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $refractive_errors = $refractive_errors->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $refractive_errors = $refractive_errors->whereMonth('created_at', date('m', strtotime($get_month)));
                $refractive_errors = $refractive_errors->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $refractive_errors = $refractive_errors->whereYear('created_at', $get_year);
            }
            $refractive_errors = $refractive_errors->whereIn('patient_type', ['presbyopia-nv', 'distance-vision-dv'])
                ->get()
                ->toArray();

            $referrals = PatientEntry::query();
            $referrals = $referrals->where('user_id', $user->id);

            $referrals = $referrals->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $referrals = $referrals->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $referrals = $referrals->whereMonth('created_at', date('m', strtotime($get_month)));
                $referrals = $referrals->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $referrals = $referrals->whereYear('created_at', $get_year);
            }
            $referrals = $referrals->whereIn('patient_type', ['cataract-surgery', 'glaucoma-surgery', 'trichiasis-surgery', 'pterygium-surgery', 'minor-surgery', 'investigations'])
                ->get()
                ->toArray();

            $cataract_referrals = PatientEntry::query();
            $cataract_referrals = $cataract_referrals->where('user_id', $user->id);

            $cataract_referrals = $cataract_referrals->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $cataract_referrals = $cataract_referrals->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $cataract_referrals = $cataract_referrals->whereMonth('created_at', date('m', strtotime($get_month)));
                $cataract_referrals = $cataract_referrals->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $cataract_referrals = $cataract_referrals->whereYear('created_at', $get_year);
            }
            $cataract_referrals = $cataract_referrals->whereIn('patient_type', ['cataract-surgery'])
                ->get()
                ->toArray();

            $confirmed_cataracts = PatientEntry::query();
            $confirmed_cataracts = $confirmed_cataracts->where('user_id', $user->id);

            $confirmed_cataracts = $confirmed_cataracts->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $confirmed_cataracts = $confirmed_cataracts->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $confirmed_cataracts = $confirmed_cataracts->whereMonth('created_at', date('m', strtotime($get_month)));
                $confirmed_cataracts = $confirmed_cataracts->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $confirmed_cataracts = $confirmed_cataracts->whereYear('created_at', $get_year);
            }
            $confirmed_cataracts = $confirmed_cataracts->whereIn('patient_type', ['with-iol', 'witout-iol'])
                ->get()
                ->toArray();

            $persons_with_disability = PatientEntry::query();
            $persons_with_disability = $persons_with_disability->where('user_id', $user->id);

            $persons_with_disability = $persons_with_disability->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $persons_with_disability = $persons_with_disability->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $persons_with_disability = $persons_with_disability->whereMonth('created_at', date('m', strtotime($get_month)));
                $persons_with_disability = $persons_with_disability->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $persons_with_disability = $persons_with_disability->whereYear('created_at', $get_year);
            }
            $persons_with_disability = $persons_with_disability->whereIn('patient_type', ['person-with-disability'])
                ->get()
                ->toArray();

            $province_wise_pie = [];

            $today_punjab_refferals_user = [];
            $today_kpk_refferals_user    = [];

            $today_punjab_patient_entries_user = [];
            $today_kpk_patient_entries_user    = [];

            $patient_entries     = [];
            $get_patient_entries = PatientEntry::query();
            $get_patient_entries = $get_patient_entries->where('user_id', $user->id);

            if ($get_date) {
                $get_patient_entries = $get_patient_entries->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $get_patient_entries = $get_patient_entries->whereMonth('created_at', date('m', strtotime($get_month)));
                $get_patient_entries = $get_patient_entries->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $get_patient_entries = $get_patient_entries->whereYear('created_at', $get_year);
            }

            $get_patient_entries = $get_patient_entries->get()->toArray();

            if ($get_patient_entries) {

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

            } else {
                $patient_entries = [];
            }

        } else {

            $today_punjab_refferals_user = RefferalForm::query();
            $today_punjab_refferals_user = $today_punjab_refferals_user->select('users.name', 'users.id', 'pec_center.center_name');

            if ($get_date) {
                $today_punjab_refferals_user = $today_punjab_refferals_user->whereDate('pec_refferal_forms.created_at', $get_date);
            }
            if ($get_month) {
                $today_punjab_refferals_user = $today_punjab_refferals_user->whereMonth('pec_refferal_forms.created_at', date('m', strtotime($get_month)));
                $today_punjab_refferals_user = $today_punjab_refferals_user->whereYear('pec_refferal_forms.created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $today_punjab_refferals_user = $today_punjab_refferals_user->whereYear('pec_refferal_forms.created_at', $get_year);
            }
            $today_punjab_refferals_user = $today_punjab_refferals_user->where('pec_refferal_forms.province_id', 1);
            // $today_punjab_refferals_user = $today_punjab_refferals_user->where('pec_refferal_forms.verify', 1);
            $today_punjab_refferals_user = $today_punjab_refferals_user->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->groupBy('pec_refferal_forms.center_id')
                ->orderBy('pec_refferal_forms.user_id', 'DESC')
                ->get()
                ->toArray();


            $today_kpk_refferals_user = RefferalForm::query();
            $today_kpk_refferals_user = $today_kpk_refferals_user->select('users.name', 'users.id', 'pec_center.center_name');
            if ($get_date) {
                $today_kpk_refferals_user = $today_kpk_refferals_user->whereDate('pec_refferal_forms.created_at', $get_date);
            }
            if ($get_month) {
                $today_kpk_refferals_user = $today_kpk_refferals_user->whereMonth('pec_refferal_forms.created_at', date('m', strtotime($get_month)));
                $today_kpk_refferals_user = $today_kpk_refferals_user->whereYear('pec_refferal_forms.created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $today_kpk_refferals_user = $today_kpk_refferals_user->whereYear('pec_refferal_forms.created_at', $get_year);
            }
            $today_kpk_refferals_user = $today_kpk_refferals_user->where('pec_refferal_forms.province_id', 2);
            // $today_kpk_refferals_user = $today_kpk_refferals_user->where('pec_refferal_forms.verify', 1);
            $today_kpk_refferals_user = $today_kpk_refferals_user->join('users', 'users.id', '=', 'pec_refferal_forms.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_refferal_forms.center_id')
                ->groupBy('pec_refferal_forms.center_id')
                ->orderBy('pec_refferal_forms.user_id', 'DESC')
                ->get()
                ->toArray();

            // $query = DB::getQueryLog();
            // dd($query);

            $today_punjab_patient_entries_user = PatientEntry::query();
            $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->select('users.name', 'users.id', 'pec_center.center_name', 'pec_center.center_id');
            if ($get_date) {
                $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->whereDate('pec_center_patients_record.created_at', $get_date);
            }
            if ($get_month) {
                $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->whereMonth('pec_center_patients_record.created_at', date('m', strtotime($get_month)));
                $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->whereYear('pec_center_patients_record.created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->whereYear('pec_center_patients_record.created_at', $get_year);
            }
            $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->where('pec_center_patients_record.province_id', 1);
            // $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->where('pec_center_patients_record.verify', 1);
            $today_punjab_patient_entries_user = $today_punjab_patient_entries_user->join('users', 'users.id', '=', 'pec_center_patients_record.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_center_patients_record.center_id')
                ->groupBy('pec_center_patients_record.center_id')
                ->get()
                ->toArray();

            $today_kpk_patient_entries_user = PatientEntry::query();
            $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->select('users.name', 'users.id', 'pec_center.center_name', 'pec_center.center_id');
            if ($get_date) {
                $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->whereDate('pec_center_patients_record.created_at', $get_date);
            }
            if ($get_month) {
                $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->whereMonth('pec_center_patients_record.created_at', date('m', strtotime($get_month)));
                $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->whereYear('pec_center_patients_record.created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->whereYear('pec_center_patients_record.created_at', $get_year);
            }
            $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->where('pec_center_patients_record.province_id', 2);
            // $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->where('pec_center_patients_record.verify', 1);
            $today_kpk_patient_entries_user = $today_kpk_patient_entries_user->join('users', 'users.id', '=', 'pec_center_patients_record.user_id')
                ->join('pec_center', 'pec_center.center_id', '=', 'pec_center_patients_record.center_id')
                ->groupBy('pec_center_patients_record.center_id')
                ->get()
                ->toArray();

            $total_patient_examined = PatientEntry::query();
            $total_patient_examined = $total_patient_examined->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $total_patient_examined = $total_patient_examined->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $total_patient_examined = $total_patient_examined->whereMonth('created_at', date('m', strtotime($get_month)));
                $total_patient_examined = $total_patient_examined->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $total_patient_examined = $total_patient_examined->whereYear('created_at', $get_year);
            }
            //$total_patient_examined = $total_patient_examined->where('verify', 1);
            $total_patient_examined = $total_patient_examined->whereIn('patient_type', ['new-patients', 'old-patients', 'leprosy-patients', 'number-of-children-under-six'])
                ->get()
                ->toArray();

                // $sql = $total_patient_examined->toSql();
                // $bindings = $total_patient_examined->getBindings();
                // $finalQuery = vsprintf(str_replace('?', "'%s'", $sql), $bindings);

                // dd($finalQuery);

            $refractive_errors = PatientEntry::query();
            $refractive_errors = $refractive_errors->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $refractive_errors = $refractive_errors->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $refractive_errors = $refractive_errors->whereMonth('created_at', date('m', strtotime($get_month)));
                $refractive_errors = $refractive_errors->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $refractive_errors = $refractive_errors->whereYear('created_at', $get_year);
            }
            // $refractive_errors = $refractive_errors->where('verify', 1);
            $refractive_errors = $refractive_errors->whereIn('patient_type', ['presbyopia-nv', 'distance-vision-dv'])
                ->get()
                ->toArray();

            $referrals = PatientEntry::query();
            $referrals = $referrals->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $referrals = $referrals->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $referrals = $referrals->whereMonth('created_at', date('m', strtotime($get_month)));
                $referrals = $referrals->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $referrals = $referrals->whereYear('created_at', $get_year);
            }
            // $referrals = $referrals->where('verify', 1);
            $referrals = $referrals->whereIn('patient_type', ['cataract-surgery', 'glaucoma-surgery', 'trichiasis-surgery', 'pterygium-surgery', 'minor-surgery', 'investigations'])
                ->get()
                ->toArray();

            $cataract_referrals = PatientEntry::query();
            $cataract_referrals = $cataract_referrals->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $cataract_referrals = $cataract_referrals->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $cataract_referrals = $cataract_referrals->whereMonth('created_at', date('m', strtotime($get_month)));
                $cataract_referrals = $cataract_referrals->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $cataract_referrals = $cataract_referrals->whereYear('created_at', $get_year);
            }
            // $cataract_referrals = $cataract_referrals->where('verify', 1);
            $cataract_referrals = $cataract_referrals->whereIn('patient_type', ['cataract-surgery'])
                ->get()
                ->toArray();

            $confirmed_cataracts = PatientEntry::query();
            $confirmed_cataracts = $confirmed_cataracts->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $confirmed_cataracts = $confirmed_cataracts->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $confirmed_cataracts = $confirmed_cataracts->whereMonth('created_at', date('m', strtotime($get_month)));
                $confirmed_cataracts = $confirmed_cataracts->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $confirmed_cataracts = $confirmed_cataracts->whereYear('created_at', $get_year);
            }
            // $confirmed_cataracts = $confirmed_cataracts->where('verify', 1);
            $confirmed_cataracts = $confirmed_cataracts->whereIn('patient_type', ['with-iol', 'witout-iol'])
                ->get()
                ->toArray();

            $persons_with_disability = PatientEntry::query();
            $persons_with_disability = $persons_with_disability->select('ma', 'fa', 'mc', 'fc');
            if ($get_date) {
                $persons_with_disability = $persons_with_disability->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $persons_with_disability = $persons_with_disability->whereMonth('created_at', date('m', strtotime($get_month)));
                $persons_with_disability = $persons_with_disability->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $persons_with_disability = $persons_with_disability->whereYear('created_at', $get_year);
            }
            // $persons_with_disability = $persons_with_disability->where('verify', 1);
            $persons_with_disability = $persons_with_disability->whereIn('patient_type', ['person-with-disability'])
                ->get()
                ->toArray();

            $province_wise_pie = PatientEntry::query();
            $province_wise_pie = $province_wise_pie->select('*');
            if ($get_date) {
                $province_wise_pie = $province_wise_pie->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $province_wise_pie = $province_wise_pie->whereMonth('created_at', date('m', strtotime($get_month)));
                $province_wise_pie = $province_wise_pie->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $province_wise_pie = $province_wise_pie->whereYear('created_at', $get_year);
            }
            // $province_wise_pie = $province_wise_pie->where('verify', 1);
            $province_wise_pie = $province_wise_pie->whereIn('patient_type', ['new-patients', 'old-patients', 'leprosy-patients', 'number-of-children-under-six', 'cataract-surgery', 'glaucoma-surgery', 'trichiasis-surgery', 'pterygium-surgery', 'minor-surgery', 'investigations', 'with-iol', 'witout-iol'])
                ->get()
                ->toArray();

            $patient_entries     = [];
            $get_patient_entries = PatientEntry::query();
            if ($get_date) {
                $get_patient_entries = $get_patient_entries->whereDate('created_at', $get_date);
            }
            if ($get_month) {
                $get_patient_entries = $get_patient_entries->whereMonth('created_at', date('m', strtotime($get_month)));
                $get_patient_entries = $get_patient_entries->whereYear('created_at', date('Y', strtotime($get_month)));
            }
            if ($get_year) {
                $get_patient_entries = $get_patient_entries->whereYear('created_at', $get_year);
            }
            // $get_patient_entries = $get_patient_entries->where('verify', 1);
            $get_patient_entries = $get_patient_entries->get()->toArray();

            if ($get_patient_entries) {

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

            } else {
                $patient_entries = [];
            }
        }

        $form_categories = FormCategories::whereNull('parent_id')
            ->get()
            ->toArray();

        return view('dashboard'
            , compact('form_categories', 'patient_entries', 'today_punjab_refferals_user', 'today_kpk_refferals_user', 'today_punjab_patient_entries_user', 'today_kpk_patient_entries_user', 'get_date', 'get_month', 'get_year', 'total_patient_examined', 'refractive_errors', 'referrals', 'cataract_referrals', 'confirmed_cataracts', 'persons_with_disability', 'province_wise_pie')
        );
    }
}
