<div class="card">
  <div class="card-body">
    <h5 class="card-title">Monthly Hires / Turnover</h5>

    <canvas id="lineChart" style="max-height: 400px;"></canvas>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        new Chart(document.querySelector('#lineChart'), {
          type: 'line',
          data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
              {
                label: 'Hires',
                data: [2, 3, 1, 4, 3, 2, 5],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgb(75, 192, 192)',
                tension: 0.2
              },
              {
                label: 'Turnover',
                data: [1, 0, 1, 2, 0, 1, 1],
                fill: false,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgb(255, 99, 132)',
                tension: 0.2
              }
            ]
          },
          options: {
            responsive: true,
            plugins: {
              tooltip: {
                mode: 'index',
                intersect: false
              },
              legend: {
                position: 'top'
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Number of Employees'
                }
              },
              x: {
                title: {
                  display: true,
                  text: 'Month'
                }
              }
            }
          }
        });
      });
    </script>
  </div>
</div>
