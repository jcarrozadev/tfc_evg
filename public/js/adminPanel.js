const ctx = document.getElementById('activityChart').getContext('2d');

const activityChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
        datasets: [{
            label: 'Guardias asignadas',
            data: window.guardiasSemanales, 
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
        const scheduleLink = document.getElementById('scheduleNav');

        if (scheduleLink) {
            const originalText = scheduleLink.textContent;

            scheduleLink.addEventListener('mouseenter', () => {
                scheduleLink.textContent = 'Próximamente';
            });

            scheduleLink.addEventListener('mouseleave', () => {
                scheduleLink.textContent = originalText;
            });

            scheduleLink.addEventListener('click', (e) => {
                e.preventDefault();
            });
        }
    });
