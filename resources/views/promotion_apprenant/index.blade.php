@extends('base_admin')

@section('title', 'Liste des promotions avec étudiants')

@section('content')
<div class="container">
    <h1 class="mb-4">Liste des promotions avec étudiants</h1>
    <a href="{{ route('promotion_apprenant.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Ajouter un étudiant à une promotion
    </a>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($promotions->isEmpty())
        <div class="alert alert-info">
            Aucune promotion trouvée pour votre site.
        </div>
    @else
        @foreach ($promotions as $promotion)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Promotion: {{ $promotion->num_promotion }}
                </h5>
                <div>
                    <span class="badge badge-light">
                        {{ $promotion->students->count() }} étudiant(s)
                    </span>
                </div>
            </div>

            <div class="card-body">
                @if($promotion->students->isEmpty())
                    <div class="alert alert-warning mb-0">
                        Aucun étudiant dans cette promotion.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promotion->students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->last_name }}</td>
                                    <td>{{ $student->first_name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->contact }}</td>
                                    <td>
                                        @php
                                            $relation = $promotion->promotionApprenants
                                                ->where('student_id', $student->id)
                                                ->first();
                                        @endphp

                                        @if($relation)
                                            <a href="{{ route('promotion_apprenant.edit', $relation->id) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('promotion_apprenant.destroy', $relation->id) }}"
                                                  method="POST"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Retirer cet étudiant de la promotion ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between">
                    <small class="text-muted">
                        Créée le: {{ $promotion->created_at->format('d/m/Y H:i') }}
                    </small>
                    <div>
                        <a href="{{ route('promotions.edit', $promotion->id) }}"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-cog"></i> Gérer la promotion
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center mt-4">
            {{ $promotions->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
