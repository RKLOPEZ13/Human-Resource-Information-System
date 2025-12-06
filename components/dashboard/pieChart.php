<div class="card">
  <div class="card-body">
    <h5 class="card-title">Employee Distribution</h5>

    <!-- Pie Chart -->
    <div id="pieChart" style="min-height: 400px;"></div>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#pieChart"), {
          series: [20, 15, 12, 12, 9, 8], // same values as old chart
          chart: {
            height: 400,
            type: 'pie',
            toolbar: { show: true }
          },
          labels: [
            'Operations', 
            'IT', 
            'Human Resources', 
            'Marketing', 
            'Customer Service', 
            'Finance'
          ],
          legend: {
            position: 'bottom' // moves legend below the chart
          },
          responsive: [{
            breakpoint: 576,
            options: {
              chart: { height: 300 },
              legend: { position: 'bottom' }
            }
          }]
        }).render();
      });
    </script>
    <!-- End Pie Chart -->

  </div>
</div>
