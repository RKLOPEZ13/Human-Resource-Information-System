<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../main.php">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
  <div class="row align-items-stretch">
    <div class="col-xxl-8">

      <!-- Metric Cards  -->
      <div class="row">
        <div class="col">
          <div class="card info-card sales-card">
            <div class="card-body">
              <h5 class="card-title">Total Employees</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6 id="totalEmployees"></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">On Leave</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning">
                  <i class="bi bi-calendar-x"></i>
                </div>
                <div class="ps-3">
                  <h6 id="onLeave"></h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card info-card users-card">
            <div class="card-body">
              <h5 class="card-title">Active</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success">
                  <i class="bi bi-check-circle"></i>
                </div>
                <div class="ps-3">
                  <h6 id="activeToday"></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Upcoming Leaves  -->
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">Upcoming Leaves</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Employee</th>
                  <th>Type</th>
                  <th>Dates</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="upcomingLeavesBody">
                <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Line Chart: Monthly Attendance Trend -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Attendance Trends <span class="text-muted small">| 2025</span></h5>
          <canvas id="lineChart"></canvas>
        </div>
      </div>

    </div>

    <div class="col-xxl-4">

      <!-- Manager Quick Actions -->
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title text-center">Quick Actions</h5>
          <div class="d-grid gap-3">
            <a href="main.php?page=announcement" class="btn btn-outline-primary d-flex align-items-center justify-content-start px-4 py-3">
              <i class="bi bi-megaphone me-3 fs-4"></i> Create Announcement
            </a>
            <a href="main.php?page=employees" class="btn btn-outline-success d-flex align-items-center justify-content-start px-4 py-3">
              <i class="bi bi-person-plus-fill me-3 fs-4"></i> View Employees
            </a>
            <a href="main.php?page=attendance" class="btn btn-outline-warning d-flex align-items-center justify-content-start px-4 py-3">
              <i class="bi bi-calendar-check me-3 fs-4"></i> Review Attendance
            </a>
            <a href="main.php?page=attendance" class="btn btn-outline-info d-flex align-items-center justify-content-start px-4 py-3">
              <i class="bi bi-calendar-x me-3 fs-4"></i> Approve Leave Requests
            </a>
          </div>
        </div>
      </div>

      <!-- Pie Chart: Employee Distribution by Department -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Employees by Department</h5>
          <div id="pieChart" style="min-height: 380px;"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> <!-- Make sure ApexCharts is loaded if using donut -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- For line chart -->
<script>
  let lineChart, pieChart;

  function fetchData() {
    // Line chart: Attendance
    $.getJSON('./backend/get_attendance_data.php', function(data){
      if(lineChart){
        lineChart.data.datasets[0].data = data.present;
        lineChart.data.datasets[1].data = data.absent;
        lineChart.data.datasets[2].data = data.late;
        lineChart.update();
      } else {
        lineChart = new Chart(document.querySelector('#lineChart'), {
          type: 'line',
          data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [
              { label: 'Present', data: data.present, borderColor: 'rgb(34,197,94)', backgroundColor: 'rgba(34,197,94,0.1)', tension:0.3, fill:true },
              { label: 'Absent',  data: data.absent, borderColor: 'rgb(239,68,68)', backgroundColor: 'rgba(239,68,68,0.1)', tension:0.3, fill:true },
              { label: 'Late',    data: data.late, borderColor: 'rgb(234,179,8)', backgroundColor: 'rgba(234,179,8,0.1)', tension:0.3, fill:true }
            ]
          },
          options: { responsive:true, plugins:{ legend:{ position:'top' }, tooltip:{ mode:'index', intersect:false } }, scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } } }
        });
      }
    });

    // Pie chart: Departments
    $.getJSON('./backend/get_department_data.php', function(data){
      if(pieChart){
        pieChart.updateSeries(data.counts);
        pieChart.updateOptions({ labels: data.departments });
      } else {
        pieChart = new ApexCharts(document.querySelector("#pieChart"), {
          series: data.counts,
          chart: { type: 'donut', height: 380 },
          labels: data.departments,
          colors: ['#4361ee','#3f37c9','#560bad','#7209b7','#b5179e','#f72585'],
          legend: { position: 'bottom' },
          dataLabels: { enabled: true },
          responsive: [{ breakpoint:480, options:{ chart:{ height:300 }, legend:{ position:'bottom' }}}]
        });
        pieChart.render();
      }
    });
  }

  function fetchMetrics() {
      $.ajax({
          url: './backend/get_metrics.php',
          type: 'GET',
          dataType: 'json',
          success: function(data) {
              $('#totalEmployees').text(data.totalEmployees);
              $('#onLeave').text(data.onLeave);
              $('#activeToday').text(data.activeToday);
          },
          error: function(err) {
              console.error('Error fetching metrics', err);
          }
      });
  }

  $(document).ready(function(){
    fetchMetrics();
    fetchData();

    setInterval(fetchMetrics, 5000);
    setInterval(fetchData, 5000);
  });


  // Fetch Upcoming Leaves
  function loadUpcomingLeaves() {
    $.getJSON('./backend/get_upcoming_leaves.php', function(data) {
      const tbody = $('#upcomingLeavesBody');
      tbody.empty();

      if (!data.success || data.leaves.length === 0) {
        tbody.append('<tr><td colspan="4" class="text-center text-muted">No upcoming leaves</td></tr>');
        return;
      }

      data.leaves.forEach(leave => {
        tbody.append(`
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:35px;height:35px;font-size:0.9rem;">
                  ${leave.initials}
                </div>
                <div>
                  <div class="fw-semibold">${leave.full_name}</div>
                  <small class="text-muted">${leave.department}</small>
                </div>
              </div>
            </td>
            <td><span class="badge bg-${leave.type_class}">${leave.type}</span></td>
            <td>${leave.dates}</td>
            <td><span class="badge bg-${leave.status_badge}">${leave.status}</span></td>
          </tr>
        `);
      });
    }).fail(() => {
      $('#upcomingLeavesBody').html('<tr><td colspan="4" class="text-danger text-center">Failed to load leaves</td></tr>');
    });
  }

  // Load data on page ready
  $(document).ready(function() {
    loadUpcomingLeaves();

    setInterval(loadUpcomingLeaves, 30000);
  });
</script>