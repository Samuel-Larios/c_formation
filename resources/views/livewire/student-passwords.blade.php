<div>
    <h3>Changer le mot de passe des étudiants</h3>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-3">
        <label for="studentSelect" class="form-label">Sélectionner un étudiant</label>
        <select wire:model="selectedStudent" class="form-control" id="studentSelect">
            <option value="">Choisir un étudiant</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->email }}</option>
            @endforeach
        </select>
    </div>

    @if ($selectedStudent)
        <div class="mb-3">
            <label for="newPassword" class="form-label">Nouveau mot de passe</label>
            <input type="password" wire:model="newPassword" class="form-control" id="newPassword"
                placeholder="Entrez le nouveau mot de passe">
            @error('newPassword')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
            <input type="password" wire:model="confirmPassword" class="form-control" id="confirmPassword"
                placeholder="Confirmez le nouveau mot de passe">
            @error('confirmPassword')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button wire:click="updatePassword" class="btn btn-primary">Mettre à jour le mot de passe</button>
        <button wire:click="resetForm" class="btn btn-secondary ml-2">Annuler</button>
    @endif
</div>
