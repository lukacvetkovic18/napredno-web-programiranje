<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function show() {
        $user = auth()->user();
        return view('projects.show', [
            'myProjects' => $user->projectAsLeader,
            'teamProjects' => $user->projectsAsMember,
        ]);
    }

    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get(); 
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'naziv_projekta' => 'required|string|max:255',
            'opis_projekta' => 'required|string',
            'cijena_projekta' => 'required|numeric',
            'obavljeni_poslovi' => 'nullable|string',
            'datum_pocetka' => 'required|date',
            'datum_zavrsetka' => 'nullable|date|after_or_equal:datum_pocetka',
            'members' => 'nullable|array',
        ]);

        $project = Project::create([
            'naziv_projekta' => $validated['naziv_projekta'],
            'opis_projekta' => $validated['opis_projekta'],
            'cijena_projekta' => $validated['cijena_projekta'],
            'obavljeni_poslovi' => $validated['obavljeni_poslovi'],
            'datum_pocetka' => $validated['datum_pocetka'],
            'datum_zavrsetka' => $validated['datum_zavrsetka'],
            'voditelj_id' => Auth::id(),
        ]);


        if (!empty($validated['members'])) {
            $project->members()->sync($validated['members']);
        }

        return redirect()->route('projects.show')->with('success', 'Projekt uspješno dodan!');
    }
    
    public function edit(Project $project)
    {
        if ($project->voditelj_id !== Auth::id()) {
            abort(403, 'Nemate dopuštenje za uređivanje ovog projekta.');
        }

        $users = User::where('id', '!=', auth()->id())->get();
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->voditelj_id !== Auth::id()) {
            abort(403, 'Nemate dopuštenje za ažuriranje ovog projekta.');
        }

        $validated = $request->validate([
            'naziv_projekta' => 'required|string|max:255',
            'opis_projekta' => 'required|string',
            'cijena_projekta' => 'required|numeric',
            'obavljeni_poslovi' => 'nullable|string',
            'datum_pocetka' => 'required|date',
            'datum_zavrsetka' => 'nullable|date|after_or_equal:datum_pocetka',
            'members' => 'nullable|array',
        ]);

        $project->update($validated);


        if (!empty($validated['members'])) {
            $project->members()->sync($validated['members']);
        } else {
            $project->members()->detach();
        }

        return redirect()->route('projects.show')->with('success', 'Projekt uspješno ažuriran!');
    }

    public function updateTasks(Request $request, Project $project)
    {
        if (!$project->members->contains(Auth::id())) {
            abort(403, 'Nemate dopuštenje za ažuriranje obavljenih poslova.');
        }

        $validated = $request->validate([
            'obavljeni_poslovi' => 'required|string',
        ]);

        $project->update(['obavljeni_poslovi' => $validated['obavljeni_poslovi']]);

        return redirect()->route('projects.show')->with('success', 'Obavljeni poslovi uspješno ažurirani!');
    }
}
