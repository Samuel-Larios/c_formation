<div>
    <h1>Statistiques des Promotions</h1>

    <!-- Formulaire de sélection de la promotion -->
    <div class="form-group mb-4">
        <label for="promotion-select">Sélectionnez une promotion :</label>
        <select wire:model.live="selectedPromotion" id="promotion-select" class="form-control" wire:loading.attr="disabled">
            <option value="">Choisissez une promotion</option>
            @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
            @endforeach
        </select>
        <div wire:loading wire:target="selectedPromotion" class="mt-2">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="sr-only">Chargement...</span>
            </div>
            <small class="text-muted ml-2">Mise à jour des statistiques...</small>
        </div>
    </div>

    <!-- Champ pour le nombre d'étudiants prévus -->
    <div class="form-group mb-4">
        <label for="expected-students">Nombre d'étudiants prévus :</label>
        <input type="number" wire:model.live="expectedStudents" id="expected-students" class="form-control" min="0">
    </div>

    @if($selectedPromotion)
    <!-- Statistiques des Job Creations -->
    <div class="row mb-4" wire:loading.class="opacity-50" wire:target="selectedPromotion">
        <div class="col-md-12">
            <h3>Statistiques des Créations d'Emploi</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Nombre d'Étudiants</h5>
                            <h2 wire:loading.remove>{{ $currentStudentsCount }}</h2>
                            <div wire:loading wire:target="selectedPromotion" class="mt-2">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Job Creations Réelles</h5>
                            <h2 wire:loading.remove>{{ $jobCreationsCount }}</h2>
                            <div wire:loading wire:target="selectedPromotion" class="mt-2">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Job Creations Attendues</h5>
                            <h2 wire:loading.remove>{{ $expectedJobCreations }}</h2>
                            <small wire:loading.remove>(70% des étudiants)</small>
                            <div wire:loading wire:target="selectedPromotion" class="mt-2">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pourcentage Atteint</h5>
                            <h2 wire:loading.remove>{{ $jobCreationsPercentage }}%</h2>
                            <div wire:loading wire:target="selectedPromotion" class="mt-2">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Histogramme des Job Creations -->
    <div class="mb-4">
        <h3>Distribution des Job Creations</h3>
        <canvas id="jobCreationsHistogram" width="400" height="200"></canvas>
    </div>
    @endif

    <!-- Graphique de comparaison pour la promotion sélectionnée -->
    <div class="mb-4">
        <h3>Comparaison pour la promotion sélectionnée</h3>
        <canvas id="comparisonChart" width="400" height="200"></canvas>
    </div>

    <!-- Graphique des 5 dernières promotions -->
    <div class="mb-4">
        <h3>Évolution des effectifs des 5 dernières promotions</h3>
        <canvas id="lastFiveChart" width="400" height="200"></canvas>
    </div>

    <!-- Styles personnalisés pour les indicateurs de chargement -->
    <style>
        .opacity-50 {
            opacity: 0.5 !important;
            pointer-events: none;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .text-muted {
            color: #6c757d !important;
        }
    </style>

    <!-- Scripts pour Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Variables globales pour stocker les instances de graphiques
        let histogramChart = null;
        let comparisonChart = null;
        let lastFiveChart = null;

        // Fonction pour détruire un graphique existant
        function destroyChart(chart) {
            if (chart) {
                chart.destroy();
            }
        }

        // Fonction pour créer l'histogramme des job creations
        function createHistogramChart() {
            const histogramCtx = document.getElementById('jobCreationsHistogram');
            if (!histogramCtx) return;

            const histogramData = @json($histogramData);

            destroyChart(histogramChart);

            const histogramChartData = {
                labels: ['Étudiants avec Job', 'Étudiants sans Job'],
                datasets: [{
                    label: 'Distribution',
                    data: [histogramData.students_with_jobs, histogramData.students_without_jobs],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)', // Green for students with jobs
                        'rgba(220, 53, 69, 0.8)'  // Red for students without jobs
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            histogramChart = new Chart(histogramCtx, {
                type: 'bar',
                data: histogramChartData,
                options: {
                    responsive: true,
                    animation: {
                        duration: 750
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Distribution des Étudiants par Statut d\'Emploi'
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre d\'Étudiants'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Statut d\'Emploi'
                            }
                        }
                    }
                }
            });
        }

        // Fonction pour créer le graphique de comparaison
        function createComparisonChart() {
            const comparisonCtx = document.getElementById('comparisonChart');
            if (!comparisonCtx) return;

            destroyChart(comparisonChart);

            const comparisonData = {
                labels: ['Étudiants Réels', 'Job Creations Réelles', 'Job Creations Attendues'],
                datasets: [{
                    label: 'Statistiques',
                    data: [{{ $currentStudentsCount }}, {{ $jobCreationsCount }}, {{ $expectedJobCreations }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(40, 167, 69, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            comparisonChart = new Chart(comparisonCtx, {
                type: 'bar',
                data: comparisonData,
                options: {
                    responsive: true,
                    animation: {
                        duration: 750
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Comparaison des Statistiques'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre'
                            }
                        }
                    }
                }
            });
        }

        // Fonction pour créer le graphique des 5 dernières promotions
        function createLastFiveChart() {
            const lastFiveCtx = document.getElementById('lastFiveChart');
            if (!lastFiveCtx) return;

            destroyChart(lastFiveChart);

            const lastFiveData = @json($lastFivePromotionsData);
            const lastFiveLabels = lastFiveData.map(item => item.promotion);
            const lastFiveCounts = lastFiveData.map(item => item.student_count);

            const lineData = {
                labels: lastFiveLabels,
                datasets: [{
                    label: 'Nombre d\'étudiants',
                    data: lastFiveCounts,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    pointBackgroundColor: 'rgb(75, 192, 192)',
                    pointBorderColor: 'rgb(75, 192, 192)',
                    pointRadius: 5
                }]
            };

            lastFiveChart = new Chart(lastFiveCtx, {
                type: 'line',
                data: lineData,
                options: {
                    responsive: true,
                    animation: {
                        duration: 750
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Évolution des Effectifs'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre d\'Étudiants'
                            }
                        }
                    }
                }
            });
        }

        // Écouter les événements Livewire
        document.addEventListener('livewire:updated', function () {
            @if($selectedPromotion)
                createHistogramChart();
            @endif
            createComparisonChart();
            createLastFiveChart();
        });

        // Écouter l'événement personnalisé de changement de promotion
        document.addEventListener('promotion-changed', function () {
            // Ajouter un petit délai pour s'assurer que les données sont mises à jour
            setTimeout(() => {
                @if($selectedPromotion)
                    createHistogramChart();
                @endif
                createComparisonChart();
                createLastFiveChart();
            }, 100);
        });

        // Initialiser les graphiques au chargement de la page
        document.addEventListener('DOMContentLoaded', function () {
            @if($selectedPromotion)
                createHistogramChart();
            @endif
            createComparisonChart();
            createLastFiveChart();
        });
    </script>
</div>
