<x-app-layout>
    <div class="show-container">
        <div class="header">
            <h2>Moji projekti</h2>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Dodaj novi projekt</a>
        </div>
        @if($myProjects->isEmpty())
            <p class="empty-message">Niste voditelj nijednog projekta.</p>
        @else
            <ul class="my-projects-list">
                <li class="list-group-header">
                    <p>Naziv</p>
                    <p>Opis</p>
                    <p>Cijena</p>
                    <p>Obavljeni poslovi</p>
                    <p>Datum početka</p>
                    <p>Datum završetka</p>
                    <p></p>
                </li>
                @foreach($myProjects as $project)
                    <li class="list-group-item">
                        <h5>{{ $project->naziv_projekta }}</h5>
                        <p>{{ $project->opis_projekta }}</p>
                        <p>{{ $project->cijena_projekta }} EUR</p>
                        <p>{{ $project->obavljeni_poslovi }}</p>
                        <p>{{ $project->datum_pocetka }}</p>
                        <p>{{ $project->datum_zavrsetka }}</p>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary btn-sm">Uredi</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="header">
            <h2>Projekti u kojima sudjelujem</h2>
        </div>
        @if($teamProjects->isEmpty())
            <p class="empty-message">Niste dodani ni na jedan projekt kao član tima.</p>
        @else
            <ul class="my-projects-list">
                <li class="list-group-header">
                    <p>Naziv</p>
                    <p>Opis</p>
                    <p>Cijena</p>
                    <p>Obavljeni poslovi</p>
                    <p>Datum početka</p>
                    <p>Datum završetka</p>
                    <p></p>
                </li>
                @foreach($teamProjects as $project)
                    <li class="list-group-item">
                        <h5>{{ $project->naziv_projekta }}</h5>
                        <p>{{ $project->opis_projekta }}</p>
                        <p>{{ $project->cijena_projekta }} EUR</p>
                        <p id="p-{{ $project->id }}">{{ $project->obavljeni_poslovi }}</p>
                        <form action="{{ route('projects.updateTasks', $project) }}" method="POST" id="form-{{ $project->id }}" style="display: none;" class="mt-3">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="obavljeni_poslovi-{{ $project->id }}" style="display: none;">Obavljeni poslovi:</label>
                                <textarea name="obavljeni_poslovi" id="obavljeni_poslovi-{{ $project->id }}" class="form-control" rows="3" required>{{ $project->obavljeni_poslovi }}</textarea>
                            </div>
                        </form>
                        <p>{{ $project->datum_pocetka }}</p>
                        <p>{{ $project->datum_zavrsetka }}</p>

                        <!-- Button to toggle the input field -->
                        <button id="edit-btn-{{ $project->id }}" class="btn btn-secondary btn-sm" onclick="toggleInput({{ $project->id }})">
                            Uredi
                        </button>
                        <!-- Save button (initially hidden) -->
                        <button id="save-btn-{{ $project->id }}" type="submit" form="form-{{ $project->id }}" class="btn btn-success btn-sm" style="display: none;">Spremi</button>

                        <!-- Hidden form for updating "obavljeni_poslovi" -->
                        <!-- <form action="{{ route('projects.updateTasks', $project) }}" method="POST" id="form-{{ $project->id }}" style="display: none;" class="mt-3">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="obavljeni_poslovi-{{ $project->id }}">Obavljeni poslovi:</label>
                                <textarea name="obavljeni_poslovi" id="obavljeni_poslovi-{{ $project->id }}" class="form-control" rows="3" required>{{ $project->obavljeni_poslovi }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">Spremi</button>
                        </form> -->
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- JavaScript for toggling the input field -->
    <script>
        function toggleInput(projectId) {
            const form = document.getElementById(`form-${projectId}`);
            const p = document.getElementById(`p-${projectId}`);
            const editBtn = document.getElementById(`edit-btn-${projectId}`);
            const saveBtn = document.getElementById(`save-btn-${projectId}`);
            
            if (form.style.display === "none") {
                form.style.display = "block";
                p.style.display = "none";
                editBtn.style.display = "none";
                saveBtn.style.display = "inline-block";
            } else {
                p.style.display = "block";
                form.style.display = "none";
                editBtn.style.display = "inline-block";
                saveBtn.style.display = "none";
            }
        }
    </script>
</x-app-layout>