// Fichier : js/admin_dashboard.js

document.addEventListener('DOMContentLoaded', function() {

    // --- 1. GESTION DU GRAPHIQUE ---
    const canvas = document.getElementById('reservationsChart');

    if (canvas) {
        // On récupère les données "cachées" dans les attributs HTML data-labels et data-counts
        // JSON.parse transforme le texte brut en tableau utilisable par JS
        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const dataCounts = JSON.parse(canvas.dataset.counts || '[]');

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Réservations',
                    data: dataCounts,
                    borderColor: '#8fd14f',
                    backgroundColor: 'rgba(143, 209, 79, 0.2)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#8fd14f',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // --- 2. GESTION DU BOUTON CUSTOM DATE ---
    // On rend la fonction accessible globalement ou on attache l'événement directement ici
    const btnCustom = document.querySelector('.switch-btn[onclick="toggleCustomDate()"]');
    if(btnCustom) {
        // Pour éviter d'utiliser onclick="" dans le HTML, on peut le faire ici,
        // mais pour garder votre HTML actuel compatible, la fonction toggleCustomDate doit être globale.
        window.toggleCustomDate = function() {
            const block = document.getElementById('customDateBlock');
            const filterBtns = document.querySelectorAll('.switch-btn');

            if (block.style.display === 'none' || block.style.display === '') {
                block.style.display = 'flex';
                filterBtns.forEach(btn => btn.classList.remove('active'));
            } else {
                block.style.display = 'none';
            }
        };
    }
});