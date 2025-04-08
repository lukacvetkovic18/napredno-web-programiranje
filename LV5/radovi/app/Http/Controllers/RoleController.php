<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function assignRole(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Provjera da li trenutni korisnik ima admin ulogu
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Nemate ovlasti za ovu akciju.'], 403);
        }

        // Promjena uloge korisnika
        $newRole = $request->input('role');
        if (!in_array($newRole, ['student', 'nastavnik'])) {
            return response()->json(['error' => 'Nevažeća uloga.'], 400);
        }

        $user->update(['role' => $newRole]);

        return redirect()->route('admin.index')->with('success', 'Uloga uspješno promijenjena!');
    }
}
