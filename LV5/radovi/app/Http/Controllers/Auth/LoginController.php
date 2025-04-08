<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validacija podataka
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Pokušaj prijave korisnika
        if (Auth::attempt($request->only('email', 'password'))) {
            // Redirect based on user role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin');
            } elseif ($user->role === 'nastavnik') {
                return redirect('/tasks/my');
            } elseif ($user->role === 'student') {
                return redirect('/tasks');
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid login credentials.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Odjava uspješna!');
    }
}
