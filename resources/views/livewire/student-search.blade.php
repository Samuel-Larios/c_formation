<div class="position-relative">
    <!-- Barre de recherche -->
    <div class="input-group">
        <div class="input-group-prepend">
            <button type="button" class="btn btn-search pe-1">
                <i class="fa fa-search search-icon"></i>
            </button>
        </div>
        <input type="text"
               wire:model.live="search"
               placeholder="Rechercher par nom, prénom ou numéro..."
               class="form-control"
               autocomplete="off" />
    </div>

    <!-- Résultats de recherche -->
    @if($students->count() > 0 && strlen($search) > 2)
        <div class="search-results position-absolute bg-white border rounded shadow-sm mt-1"
             style="z-index: 1000; width: 100%; max-height: 300px; overflow-y: auto;">
            @foreach($students as $student)
                <a href="{{ route('students.show', $student->id) }}"
                   class="text-decoration-none">
                    <div class="search-result-item p-2 border-bottom"
                         style="cursor: pointer; transition: background-color 0.2s;"
                         onmouseover="this.style.backgroundColor='#f8f9fa'"
                         onmouseout="this.style.backgroundColor='transparent'">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-img rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    {{ substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark">{{ $student->first_name }} {{ $student->last_name }}</div>
                                <div class="text-muted small">{{ $student->contact }}</div>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
