<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function username()
    {
        return 'admin_code';
    }

    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        // Validate form data
        $this->validate($request, [
            'admin_code' => 'required|alpha_num',
            'password' => 'required'],
            [],
            [
                'admin_code' => trans('admin_login.admin_code'),
                'password' => trans('admin_login.password'),
        ]);

        // Attempt to log the user in
        if(Auth::guard('admin')->attempt(['admin_code' => $request->admin_code, 'password' => $request->password]))
        {
            return redirect()->intended(route('admin.plan'));
        }

        // if unsuccessful
        return redirect()->back()->withInput()->withErrors(['failed' => trans('admin_auth.failed')]);
    }

    public function logout(Request $request)
    {
        if(Auth::guard('admin')->check()) // this means that the admin was logged in.
        {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
