<x-app-layout>
    <div class="edit-container">
        <h2 class="mb-4">Uredi projekt</h2>
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="naziv_projekta">Naziv projekta:</label>
                <input type="text" name="naziv_projekta" id="naziv_projekta" value="{{ $project->naziv_projekta }}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="opis_projekta">Opis projekta:</label>
                <textarea name="opis_projekta" id="opis_projekta" class="form-control" rows="4" required>{{ $project->opis_projekta }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="cijena_projekta">Cijena:</label>
                <input type="number" name="cijena_projekta" id="cijena_projekta" value="{{ $project->cijena_projekta }}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="obavljeni_poslovi">Obavljeni poslovi:</label>
                <textarea name="obavljeni_poslovi" id="obavljeni_poslovi" class="form-control" rows="4">{{ $project->obavljeni_poslovi }}</textarea>
            </div>
            <div class="form-group mb-3">
                <label for="datum_pocetka">Datum početka:</label>
                <input type="date" name="datum_pocetka" id="datum_pocetka" value="{{ $project->datum_pocetka }}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="datum_zavrsetka">Datum završetka:</label>
                <input type="date" name="datum_zavrsetka" id="datum_zavrsetka" value="{{ $project->datum_zavrsetka }}" class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="members">Uredi članove tima:</label>
                <select name="members[]" id="members" class="form-control" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $project->members->contains($user->id) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Držite tipku CTRL (ili CMD na Macu) za odabir više članova.</small>
            </div>
            <button type="submit" class="btn btn-primary">Ažuriraj projekt</button>
        </form>
    </div>
</x-app-layout>
