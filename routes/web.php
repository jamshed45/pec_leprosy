<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RefferalsFormController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\TehsilController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\PatientEntriesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\HealthFacilityController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReferralsController;
use App\Http\Controllers\ConfirmedReferralsController;
use App\Http\Controllers\DiabeticScreeningController;
use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\ReferByController;
use App\Models\PatientEntry;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DailyAllowanceController;
use App\Http\Controllers\TourDairyController;
use App\Http\Controllers\TravellingAllowanceController;
use App\Http\Controllers\AdvanvceTourProgrammeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::resource('products', ProductController::class);
// Route::resource('cataracts', CataractPatientsController::class);

Route::get('get-refferal-forms', [RefferalsFormController::class, 'get_refferal_forms'])->name('get-refferal-forms');

Route::get('refferal-forms/view-unverified-refferal', [RefferalsFormController::class, 'view_unverified_refferal'])->name('view-unverified-refferal');
Route::get('refferal-forms/view-verified-refferal', [RefferalsFormController::class, 'view_verified_refferal'])->name('view-verified-refferal');

Route::post('refferal-forms/add-operated-by', [RefferalsFormController::class, 'add_operated_by'])->name('add-operated-by');
Route::get('refferal-forms/edit/{id}', [RefferalsFormController::class, 'edit_refferal_form']);
// Route::get('refferal-forms/operated-by/{key}', [RefferalsFormController::class, 'operated_by'])->name('operated-by');
Route::get('refferal-forms/operated-by/{id}', [RefferalsFormController::class, 'operated_by']);
Route::get('refferal-forms/update-operated-by/{id}', [RefferalsFormController::class, 'update_operated_by']);

Route::get('refferal-forms/edit-update-operated-by', [RefferalsFormController::class, 'edit_update_operated_by'])->name('edit-update-operated-by');
Route::post('refferal-forms/edit-update-operated-by', [RefferalsFormController::class, 'edit_update_operated_by'])->name('edit-update-operated-by');

// Route::get('diabetic-screening-reports/diabetic-screening-reports-by-division', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_division'])->name('diabetic-screening-reports-by-division');
// Route::post('diabetic-screening-reports/diabetic-screening-reports-by-division', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_division'])->name('diabetic-screening-reports-by-division');



Route::get('refferal-forms/add-old-refferal-patient', [RefferalsFormController::class, 'add_old_refferal_patient']);
Route::post('refferal-forms/save-old-refferal-patient', [RefferalsFormController::class, 'save_old_refferal_patient'])->name('refferal-forms.save-old-refferal-patient');

Route::post('refferal-forms/verify-refferal-patient', [RefferalsFormController::class, 'verify_refferal_patient'])->name('refferal-forms.verify-refferal-patient');


Route::get('refferal-forms/refferal-patients-by-user', [RefferalsFormController::class, 'refferal_patients_by_user'])->name('refferal-patients-by-user');
Route::post('refferal-forms/refferal-patients-by-user', [RefferalsFormController::class, 'refferal_patients_by_user'])->name('refferal-patients-by-user');

Route::get('refferal-forms/refferal-patients-by-center', [RefferalsFormController::class, 'refferal_patients_by_center'])->name('refferal-patients-by-center');
Route::post('refferal-forms/refferal-patients-by-center', [RefferalsFormController::class, 'refferal_patients_by_center'])->name('refferal-patients-by-center');

Route::get('refferal-forms/refferal-patients-by-location', [RefferalsFormController::class, 'refferal_patients_by_location'])->name('refferal-patients-by-location');
Route::post('refferal-forms/refferal-patients-by-location', [RefferalsFormController::class, 'refferal_patients_by_location'])->name('refferal-patients-by-location');

Route::get('refferal-forms/refferal-patients-by-tehsil', [RefferalsFormController::class, 'refferal_patients_by_tehsil'])->name('refferal-patients-by-tehsil');
Route::post('refferal-forms/refferal-patients-by-tehsil', [RefferalsFormController::class, 'refferal_patients_by_tehsil'])->name('refferal-patients-by-tehsil');

Route::get('refferal-forms/refferal-patients-by-district', [RefferalsFormController::class, 'refferal_patients_by_district'])->name('refferal-patients-by-district');
Route::post('refferal-forms/refferal-patients-by-district', [RefferalsFormController::class, 'refferal_patients_by_district'])->name('refferal-patients-by-district');

Route::get('refferal-forms/refferal-patients-by-division', [RefferalsFormController::class, 'refferal_patients_by_division'])->name('refferal-patients-by-division');
Route::post('refferal-forms/refferal-patients-by-division', [RefferalsFormController::class, 'refferal_patients_by_division'])->name('refferal-patients-by-division');

Route::get('refferal-forms/refferal-patients-by-province', [RefferalsFormController::class, 'refferal_patients_by_province'])->name('refferal-patients-by-province');
Route::post('refferal-forms/refferal-patients-by-province', [RefferalsFormController::class, 'refferal_patients_by_province'])->name('refferal-patients-by-province');



// Route::any('refferal-forms/operated-by', [RefferalsFormController::class, 'operated_by']);
Route::resource('refferal-forms', RefferalsFormController::class);




Route::resource('dashboard', DashboardController::class);


Route::get('users/change_user_status', [UserController::class, 'change_user_status']);
Route::get('users/set_user_center', [UserController::class, 'set_user_center']);
Route::get('users/check_username', [UserController::class, 'check_username']);
Route::resource('users', UserController::class);

Route::resource('provinces', ProvinceController::class);
Route::resource('divisions', DivisionController::class);
Route::resource('districts', DistrictController::class);

Route::get('tehsils/get_province_tehsils', [TehsilController::class, 'get_province_tehsils']);
Route::get('tehsils/get_province_hf_tehsils', [TehsilController::class, 'get_province_hf_tehsils']);
Route::resource('tehsils', TehsilController::class);

// Route::get('cities/get_province_cities', [CityController::class, 'get_province_cities']);
// Route::get('cities/get_province_hf_cities', [CityController::class, 'get_province_hf_cities']);
// Route::resource('cities', CityController::class);


Route::get('centers/add-my-center', [CenterController::class, 'add_my_center']);
Route::get('centers/my-center', [CenterController::class, 'my_center']);
Route::get('centers/get_activity_location_centers', [CenterController::class, 'get_activity_location_centers']);
Route::get('centers/get_filter_centers', [CenterController::class, 'get_filter_centers']);
Route::get('centers/get_tehsil_centers', [CenterController::class, 'get_tehsil_centers']);
Route::resource('centers', CenterController::class);


Route::get('patient-entries/update-entries/{id}', [PatientEntriesController::class, 'update_entries_form']);

Route::post('patient-entries/update-entries/{id}', [PatientEntriesController::class, 'update_entries_form']);


Route::post('patient-entries/update-entries-saved/{id}', [PatientEntriesController::class, 'update_entries_saved'])->name('patient-entries.update-entries-saved');

Route::post('patient-entries/update-saved-entries/{id}', [PatientEntriesController::class, 'update_saved_entries'])->name('patient-entries.update-saved-entries');


Route::get('patient-entries/verify-entries/{id}', [PatientEntriesController::class, 'verify_entries_form']);



$form_links = DB::table('pec_form_categories')
                              ->where('parent_id', '=', NULL )
                              ->get()
                              ->toArray();

foreach($form_links  as $link)
    {
        $form_link_slug = $link->slug;
        Route::get('patient-entries/'.$form_link_slug, function () use ($form_link_slug) {

            //return view('patient-entries.'.$form_link_slug, compact('form_link_slug'));
            return view('patient-entries.dynamic_form', compact('form_link_slug'));
        });
    };


Route::get('patients-entries/view-all-patient-entries', [PatientEntriesController::class, 'view_all_patients_entries'])->name('view-all-patients-entries');
Route::post('patients-entries/view-all-patient-entries', [PatientEntriesController::class, 'view_all_patients_entries'])->name('view-all-patients-entries');


Route::get('patients-entries/patients-entries-by-stat', [PatientEntriesController::class, 'patients_entries_by_stat'])->name('patients-entries-by-stat');
Route::post('patients-entries/patients-entries-by-stat', [PatientEntriesController::class, 'patients_entries_by_stat'])->name('patients-entries-by-stat');

Route::get('patients-entries/patients-entries-by-user', [PatientEntriesController::class, 'patients_entries_by_user'])->name('patients-entries-by-user');
Route::post('patients-entries/patients-entries-by-user', [PatientEntriesController::class, 'patients_entries_by_user'])->name('patients-entries-by-user');

Route::get('patients-entries/patients-entries-by-center', [PatientEntriesController::class, 'patients_entries_by_center'])->name('patients-entries-by-center');
Route::post('patients-entries/patients-entries-by-center', [PatientEntriesController::class, 'patients_entries_by_center'])->name('patients-entries-by-center');

Route::get('patients-entries/patients-entries-by-location', [PatientEntriesController::class, 'patients_entries_by_location'])->name('patients-entries-by-location');
Route::post('patients-entries/patients-entries-by-location', [PatientEntriesController::class, 'patients_entries_by_location'])->name('patients-entries-by-location');

Route::get('patients-entries/patients-entries-by-tehsil', [PatientEntriesController::class, 'patients_entries_by_tehsil'])->name('patients-entries-by-tehsil');
Route::post('patients-entries/patients-entries-by-tehsil', [PatientEntriesController::class, 'patients_entries_by_tehsil'])->name('patients-entries-by-tehsil');

Route::get('patients-entries/patients-entries-by-district', [PatientEntriesController::class, 'patients_entries_by_district'])->name('patients-entries-by-district');
Route::post('patients-entries/patients-entries-by-district', [PatientEntriesController::class, 'patients_entries_by_district'])->name('patients-entries-by-district');

Route::get('patients-entries/patients-entries-by-division', [PatientEntriesController::class, 'patients_entries_by_division'])->name('patients-entries-by-division');
Route::post('patients-entries/patients-entries-by-division', [PatientEntriesController::class, 'patients_entries_by_division'])->name('patients-entries-by-division');

Route::get('patients-entries/patients-entries-by-province', [PatientEntriesController::class, 'patients_entries_by_province'])->name('patients-entries-by-province');
Route::post('patients-entries/patients-entries-by-province', [PatientEntriesController::class, 'patients_entries_by_province'])->name('patients-entries-by-province');


Route::get('patients-entries/all-patients-entries-report', [PatientEntriesController::class, 'all_patients_entries_by_report'])->name('all-patients-entries-report');
Route::post('patients-entries/all-patients-entries-report', [PatientEntriesController::class, 'all_patients_entries_by_report'])->name('all-patients-entries-report');


Route::get('patients-entries/all-patients-entries-report-pdf', [PatientEntriesController::class, 'all_patients_entries_by_report_pdf'])->name('all-patients-entries-report-pdf');

Route::get('patients-entries/all-patients-entries-admin-report-pdf', [PatientEntriesController::class, 'all_patients_entries_by_admin_report_pdf'])->name('all-patients-entries-admin-report-pdf');

Route::get('patients-entries/all-patients-entries-report-pdf-view', [PatientEntriesController::class, 'all_patients_entries_by_report_pdf_view'])->name('all-patients-entries-report-view');



Route::resource('patient-entries', PatientEntriesController::class);

Route::resource('health-facilities', HealthFacilityController::class);

Route::get('referrals-reports/all-referrals-report', [ReferralsController::class, 'all_refferals_by_report'])->name('all-referrals-report');
Route::post('referrals-reports/all-referrals-report', [ReferralsController::class, 'all_refferals_by_report'])->name('all-referrals-report');

Route::get('referrals-reports/all-referrals-report-pdf', [ReferralsController::class, 'all_refferals_by_report_pdf'])->name('all-referrals-report-pdf');


Route::get('referrals-reports/referrals-reports-by-user', [ReferralsController::class, 'referrals_reports_by_user'])->name('referrals-entries-by-user');
Route::post('referrals-reports/referrals-reports-by-user', [ReferralsController::class, 'referrals_reports_by_user'])->name('referrals-reports-by-user');

Route::get('referrals-reports/referrals-reports-by-center', [ReferralsController::class, 'referrals_reports_by_center'])->name('referrals-entries-by-center');
Route::post('referrals-reports/referrals-reports-by-center', [ReferralsController::class, 'referrals_reports_by_center'])->name('referrals-reports-by-center');

Route::get('referrals-reports/referrals-reports-by-location', [ReferralsController::class, 'referrals_reports_by_location'])->name('referrals-entries-by-location');
Route::post('referrals-reports/referrals-reports-by-location', [ReferralsController::class, 'referrals_reports_by_location'])->name('referrals-reports-by-location');

Route::get('referrals-reports/referrals-reports-by-tehsil', [ReferralsController::class, 'referrals_reports_by_tehsil'])->name('referrals-entries-by-tehsil');
Route::post('referrals-reports/referrals-reports-by-tehsil', [ReferralsController::class, 'referrals_reports_by_tehsil'])->name('referrals-reports-by-tehsil');

Route::get('referrals-reports/referrals-reports-by-district', [ReferralsController::class, 'referrals_reports_by_district'])->name('referrals-entries-by-district');
Route::post('referrals-reports/referrals-reports-by-district', [ReferralsController::class, 'referrals_reports_by_district'])->name('referrals-reports-by-district');

Route::get('referrals-reports/referrals-reports-by-division', [ReferralsController::class, 'referrals_reports_by_division'])->name('referrals-entries-by-division');
Route::post('referrals-reports/referrals-reports-by-division', [ReferralsController::class, 'referrals_reports_by_division'])->name('referrals-reports-by-division');

Route::get('referrals-reports/referrals-reports-by-province', [ReferralsController::class, 'referrals_reports_by_province'])->name('referrals-entries-by-province');
Route::post('referrals-reports/referrals-reports-by-province', [ReferralsController::class, 'referrals_reports_by_province'])->name('referrals-reports-by-province');

Route::resource('referrals-reports', ReferralsController::class);


Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-user', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_user'])->name('confirmed-referrals-entries-by-user');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-user', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_user'])->name('confirmed-referrals-reports-by-user');

Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-center', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_center'])->name('confirmed-referrals-entries-by-center');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-center', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_center'])->name('confirmed-referrals-reports-by-center');

Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-location', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_location'])->name('confirmed-referrals-entries-by-location');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-location', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_location'])->name('confirmed-referrals-reports-by-location');

Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-tehsil', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_tehsil'])->name('confirmed-referrals-entries-by-tehsil');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-tehsil', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_tehsil'])->name('confirmed-referrals-reports-by-tehsil');

Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-district', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_district'])->name('confirmed-referrals-entries-by-district');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-district', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_district'])->name('confirmed-referrals-reports-by-district');

Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-division', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_division'])->name('confirmed-referrals-entries-by-division');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-division', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_division'])->name('confirmed-referrals-reports-by-division');

Route::get('confirmed-referrals-reports/confirmed-referrals-reports-by-province', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_province'])->name('confirmed-referrals-entries-by-province');
Route::post('confirmed-referrals-reports/confirmed-referrals-reports-by-province', [ConfirmedReferralsController::class, 'confirmed_referrals_reports_by_province'])->name('confirmed-referrals-reports-by-province');

Route::resource('confirmed-referrals-reports', ConfirmedReferralsController::class);


Route::get('diabetic-screening-reports/diabetic-screening-reports-by-user', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_user'])->name('diabetic-screening-entries-by-user');
Route::post('diabetic-screening-reports/diabetic-screening-reports-by-user', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_user'])->name('diabetic-screening-reports-by-user');

Route::get('diabetic-screening-reports/diabetic-screening-reports-by-center', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_center'])->name('diabetic-screening-entries-by-center');
Route::post('diabetic-screening-reports/diabetic-screening-reports-by-center', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_center'])->name('diabetic-screening-reports-by-center');

Route::get('diabetic-screening-reports/diabetic-screening-reports-by-location', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_location'])->name('diabetic-screening-entries-by-location');
Route::post('diabetic-screening-reports/diabetic-screening-reports-by-location', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_location'])->name('diabetic-screening-reports-by-location');

Route::get('diabetic-screening-reports/diabetic-screening-reports-by-tehsil', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_tehsil'])->name('diabetic-screening-entries-by-tehsil');
Route::post('diabetic-screening-reports/diabetic-screening-reports-by-tehsil', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_tehsil'])->name('diabetic-screening-reports-by-tehsil');

Route::get('diabetic-screening-reports/diabetic-screening-reports-by-district', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_district'])->name('diabetic-screening-reports-by-district');
Route::post('diabetic-screening-reports-by-district', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_district'])->name('diabetic_screening_reports_by_district');

Route::get('diabetic-screening-reports/diabetic-screening-reports-by-division', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_division'])->name('diabetic-screening-reports-by-division');
Route::post('diabetic-screening-reports/diabetic-screening-reports-by-division', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_division'])->name('diabetic-screening-reports-by-division');

Route::get('diabetic-screening-reports/diabetic-screening-reports-by-province', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_province'])->name('diabetic-screening-entries-by-province');
Route::post('diabetic-screening-reports/diabetic-screening-reports-by-province', [DiabeticScreeningController::class, 'diabetic_screening_reports_by_province'])->name('diabetic-screening-reports-by-province');

Route::resource('diabetic-screening-reports', DiabeticScreeningController::class);


Route::get('doctors/get_doctors', [DoctorController::class, 'get_doctors']);
Route::resource('doctors', DoctorController::class);


Route::get('profile/edit-profile/{id}', [ProfileController::class, 'edit_profile']);
Route::post('profile/update-profile', [ProfileController::class, 'update_profile'])->name('profile.update-profile');
// Route::post('refferal-forms/save-old-refferal-patient', [RefferalsFormController::class, 'save_old_refferal_patient'])->name('refferal-forms.save-old-refferal-patient');

Route::resource('profile', ProfileController::class);


// Route::get('dashboard', [CustomAuthController::class, 'dashboard']);
Route::get('employee-login', [CustomAuthController::class, 'employee_login'])->name('login.employee');
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::get('user-login-process', [CustomAuthController::class, 'userLoginProcess'])->name('login.userLoginProcess');
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

Route::get('daily-allowance/generate_pdf', [DailyAllowanceController::class, 'generate_pdf']);
Route::resource('daily-allowance', DailyAllowanceController::class);

Route::get('travelling-allowance/generate_pdf', [TravellingAllowanceController::class, 'generate_pdf']);
Route::resource('travelling-allowance', TravellingAllowanceController::class);

Route::get('advance-tour-programme/generate_pdf', [AdvanvceTourProgrammeController::class, 'generate_pdf']);
Route::get('advance-tour-programme/atp_submit', [AdvanvceTourProgrammeController::class, 'atp_submit']);
Route::get('advance-tour-programme/get_reserved_apt_date', [AdvanvceTourProgrammeController::class, 'get_reserved_apt_date']);
Route::resource('advance-tour-programme', AdvanvceTourProgrammeController::class);

Route::get('tour-dairy/generate_pdf', [TourDairyController::class, 'generate_pdf']);
Route::resource('tour-dairy', TourDairyController::class);


Route::get('roles',  [PermissionController::class, 'Permission']);

Route::get('/clear-cache-all', function() {
    Artisan::call('optimize:clear');

    dd("Cache Clear All");
});

