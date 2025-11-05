<div>
    <form class="navbar-left navbar-form nav-search mr-md-3">
        <div class="input-group">
            <label for="search" class="sr-only">Search...</label>
            <input type="text" wire:model.live="search" placeholder="Rechercher un étudiant..." class="form-control"
                id="search">
            <div class="input-group-append">
                <button class="btn btn-search pr-1" type="button">
                    <i class="fa fa-search search-icon"></i>
                </button>
            </div>
        </div>
    </form>

    @if (count($students) > 0)
        <div class="dropdown-menu show" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000;">
            @foreach ($students as $student)
                <a class="dropdown-item" href="{{ route('students.show', $student->id) }}">
                    {{ $student->name }} - {{ $student->email }}
                </a>
            @endforeach
        </div>
    @endif
</div>
