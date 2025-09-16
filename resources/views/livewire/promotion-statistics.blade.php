<div>
    <h1>Statistiques des Promotions</h1>

    <!-- Formulaire de sélection de la promotion -->
    <div class="form-group mb-4">
        <label for="promotion-select">Sélectionnez une promotion :</label>
        <select wire:model.live="selectedPromotion" id="promotion-select" class="form-control">
            <option value="">Choisissez une promotion</option>
            @foreach($promotions as $promotion)
                <option value="{{ $promotion->id }}">{{ $promotion->num_promotion }}</option>
            @endforeach
        </select>
    </div>

    <!-- Champ pour le nombre d'étudiants prévus -->
    <div class="form-group mb-4">
        <label for="expected-students">Nombre d'étudiants prévus :</label>
        <input type="number" wire:model.live="expectedStudents" id="expected-students" class="form-control" min="0">
    </div>

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

    <!-- Scripts pour Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:updated', function () {
            // Graphique de comparaison
            const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
            const comparisonData = {
                labels: ['Étudiants Réels', 'Étudiants Prévu'],
                datasets: [{
                    label: 'Nombre d\'étudiants',
                    data: [{{ $currentStudentsCount }}, {{ $expectedStudents }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            };
            new Chart(comparisonCtx, {
                type: 'bar',
                data: comparisonData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des 5 dernières promotions
            const lastFiveCtx = document.getElementById('lastFiveChart').getContext('2d');
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
                    tension: 0.1
                }]
            };
            new Chart(lastFiveCtx, {
                type: 'line',
                data: lineData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</div>
