<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\session;
class CustomAuthController extends Controller
{
    public function index() {
        return view('auth.login-2');
    }
    public function customLogin(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
//        $credentials = $request->only('email', 'password');
        $credentials = [
            'email' => $request->input('email'),
            'email' => $request->input('password'),
        ];
        dd($credentials);
        if (Auth::attempt($credentials)) {
            return redirect()->intended('admin')
                        ->withSuccess('Signed in');
        }
        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function registration() {
        return view('auth.registration');
    }

    public function customRegistration(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("dashboard")->withSuccess('You have signed-in');
    }

    public function create(array $data) {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function signOut() {
//        Session::flush();
//        dd(1);
        Auth::logout();

        return redirect()->route('login');
    }
}