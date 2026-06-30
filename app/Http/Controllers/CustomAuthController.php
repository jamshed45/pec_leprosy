<?php
namespace App\Http\Controllers;

use App\Models\ATPModel;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class CustomAuthController extends Controller
{
    // public function __construct()
    // {
    //         $this->middleware('auth');
    // }

    public function index()
    {
        // echo $pass = Hash::make('admin');
        return view('auth.login');
    }

    public function employee_login()
    {

        return view('auth.login-admin');
    }

    public function userLoginProcess(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        if (Auth::attempt(['username' => $username, 'password' => $password])) {

            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->hasRole('pec-user')) {

                $ATP = ATPModel::where('user_id', $user->id)
                    ->whereMonth('entry_month', date('m', strtotime(Carbon::now())))
                    ->whereYear('entry_month', date('Y', strtotime(Carbon::today())))->first();

                if ($ATP) {
                    $ATP = $ATP->toArray();

                    $APT_detail = json_decode($ATP['atp_detail']);

                    if ($APT_detail) {
                        $APT_center = json_decode(json_encode($APT_detail->center_id), true);

                        if (isset($APT_center[strtotime(date('Y-m-d'))])) {
                            $center_info = DB::table('pec_center')
                                ->where('center_id', '=', $APT_center[strtotime(date('Y-m-d'))])
                                ->first();

                            $activity__locatio_info = DB::table('activity_locations')
                                ->where('activity_location_id', '=', $center_info->activity_location_id)
                                ->first();

                            $center_sses_info = [
                                'activity_location_id'   => $activity__locatio_info->activity_location_id,
                                'activity_location_name' => $activity__locatio_info->activity_name,
                                'center_id'              => $center_info->center_id,
                                'center_name'            => $center_info->center_name,
                                'tehsil_id'              => $center_info->tehsil_id,
                                'district_id'            => $center_info->district_id,
                                'division_id'            => $center_info->division_id,
                                'province_id'            => $center_info->province_id,
                                'entry_date'             => date('Y-m-d'),
                            ];

                            $center_info_session = $request->session()->put('center_info', $center_sses_info);
                        }

                    }

                }

            }

            if ($user->status == 1) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard?date=' . date('Y-m-d'))
                    ->withSuccess('Signed in');
            } else {
                return redirect("login")->with('error', 'Your Account is Disabled. Contact Admin');
            }

        }

        if ($request->source == 'live') {
            return redirect()->to('https://alp.com.pk/login/?validation="error"');
        } else {
            return redirect("login")->with('error', 'Invalid username or password');
        }

    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data  = $request->all();
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('dashboard');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
