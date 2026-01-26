<style>
  .attendance-card {
    max-width: 520px;
    margin: 0 auto;
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
  }
  .card-header {
    background: linear-gradient(135deg, #d3131d, #b30f18);
    color: white;
    padding: 2rem;
    text-align: center;
    font-weight: 600;
  }
  .btn-clock {
    height: 100px;
    font-size: 1.5rem;
    font-weight: 700;
    border-radius: 14px;
    margin: 15px 0;
    transition: all 0.3s ease;
  }
  .btn-clock-in {
    background: #e3f2fd;
    color: #1565c0;
    border: 3px solid #90caf9;
  }
  .btn-clock-in:hover {
    background: #90caf9;
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(25,118,210,0.25);
  }
  .btn-clock-out {
    background: #ffebee;
    color: #c62828;
    border: 3px solid #ef9a9a;
  }
  .btn-clock-out:hover {
    background: #ef9a9a;
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(198,40,40,0.25);
  }
  .time-display {
    font-size: 3.2rem;
    font-weight: 300;
    color: #1e293b;
    letter-spacing: 2px;
  }
  .am-pm {
    font-size: 1.6rem;
    color: #64748b;
    vertical-align: super;
  }
  .date-display {
    color: #64748b;
    font-size: 1.2rem;
    margin-top: 10px;
    font-weight: 600 1.1rem 'Segoe UI';
  }
  .summary-table th {
    background-color: #f1f3f5;
    font-weight: 600;
    color: #343a40;
  }
</style>

<div class="container">
  <div class="card attendance-card">
    <div class="card-header">
      <h4 class="mb-0"><i class="bi bi-clock-history me-2"></i> Time Attendance System</h4>
      <small class="opacity-75">HR Information System</small>
    </div>
    <div class="card-body text-center py-5 px-4">

      <div class="mb-5">
        <div class="time-display" id="currentTime">--:--:--</div>
        <span class="am-pm" id="amPm">--</span>
        <div class="date-display" id="currentDate">Loading...</div>
      </div>

      <div class="row g-4">
        <div class="col-12">
          <button class="btn btn-clock btn-clock-in w-100 shadow-sm" data-action="in" data-bs-toggle="modal" data-bs-target="#entryModal">
            <i class="bi bi-box-arrow-in-right fs-3"></i><br>CLOCK IN
          </button>
        </div>
        <div class="col-12">
          <button class="btn btn-clock btn-clock-out w-100 shadow-sm" data-action="out" data-bs-toggle="modal" data-bs-target="#entryModal">
            <i class="bi bi-box-arrow-left fs-3"></i><br>CLOCK OUT
          </button>
        </div>
      </div>

      <div class="mt-4 text-muted small">
        <i class="bi bi-info-circle"></i> Use manual entry if biometric device is unavailable
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="entryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-primary" id="modalTitle">Manual Clock In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body pt-3">

        <form method="post" action="./backend/insert_attendance.php" id="clockForm">
          <input type="hidden" id="actionTypeHidden" name="action_type">
          <input type="hidden" id="dateHidden" name="date">
          <input type="hidden" id="timeInHidden" name="time_in">
          <input type="hidden" id="timeOutHidden" name="time_out">
          <input type="hidden" id="statusHidden" name="status">
          <input type="hidden" id="undertimeHoursHidden" name="undertime_hours">
          <input type="hidden" id="overtimeHoursHidden" name="overtime_hours">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-4">
                <label class="form-label fw-bold text-dark">Employee Number</label>
                <input type="text" class="form-control form-control-lg text-center fw-bold" id="empNumber" name="empNumber"
                            placeholder="EMP0001" maxlength="10" required autofocus style="letter-spacing: 5px; font-size: 1.5rem;">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-4 text-center mt-4">
                <div class="display-5 fw-light" id="recordedTime">--:--:--</div>
                <span class="h3 text-muted" id="recordedAmPm"></span>
              </div>
            </div>
          </div>

          <hr>

          <div id="summarySection">
            <h6 class="fw-bold text-primary mb-3">Attendance Summary</h6>
            <table class="table table-sm summary-table text-start">
              <tbody id="summaryTableBody">
              </tbody>
            </table>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-lg fw-bold" id="submitBtn">
              <span id="submitBtnText">Confirm Clock In</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Live clock update (12-hour format with seconds)
  function updateLiveClock() {
    const now = new Date();
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const displayHours = hours % 12 || 12;

    document.getElementById('currentTime').textContent = `${displayHours}:${minutes}:${seconds}`;
    document.getElementById('amPm').textContent = ampm;
    document.getElementById('currentDate').textContent = 
      now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  }
  updateLiveClock();
  setInterval(updateLiveClock, 1000);

  // Variables to store recorded time
  let recordedDate = '';
  let recordedTime24 = ''; // 24-hour format
  let actionType = ''; // 'in' or 'out'

  document.querySelectorAll('[data-action]').forEach(btn => {
    btn.addEventListener('click', function() {
      const now = new Date();
      actionType = this.getAttribute('data-action');

      // Format date and time
      const year = now.getFullYear();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const day = String(now.getDate()).padStart(2, '0');
      recordedDate = `${year}-${month}-${day}`;

      let hours24 = now.getHours();
      const minutes = now.getMinutes().toString().padStart(2, '0');
      const seconds = now.getSeconds().toString().padStart(2, '0');
      recordedTime24 = `${hours24.toString().padStart(2, '0')}:${minutes}:${seconds}`;

      // For display (12-hour)
      const ampm = hours24 >= 12 ? 'PM' : 'AM';
      const hours12 = hours24 % 12 || 12;

      // Populate common hidden fields
      document.getElementById('actionTypeHidden').value = actionType;
      document.getElementById('dateHidden').value = recordedDate;

      // Clear Time-specific hidden fields
      document.getElementById('statusHidden').value = '';
      document.getElementById('undertimeHoursHidden').value = 0;
      document.getElementById('overtimeHoursHidden').value = 0;
      
      // Update modal content
      const isIn = actionType === 'in';
      document.getElementById('modalTitle').textContent = isIn ? 'Manual Clock In' : 'Manual Clock Out';
      document.getElementById('submitBtnText').textContent = isIn ? 'Confirm Clock In' : 'Confirm Clock Out';
      document.getElementById('recordedTime').textContent = `${hours12}:${minutes}:${seconds}`;
      document.getElementById('recordedAmPm').textContent = ampm;

      // Button style
      const submitBtn = document.getElementById('submitBtn');
      if (isIn) {
        submitBtn.className = 'btn btn-success btn-lg fw-bold';
      } else {
        submitBtn.className = 'btn btn-danger btn-lg fw-bold';
      }

      // Generate summary
      const tbody = document.getElementById('summaryTableBody');
      tbody.innerHTML = '';

      if (isIn) {
        // CLOCK IN LOGIC
        // Set Time In, clear Time Out
        document.getElementById('timeInHidden').value = recordedTime24;
        document.getElementById('timeOutHidden').value = ''; 
        
        let status, statusText, badgeClass;

        if (hours24 < 8) {
            status = 'P';
            statusText = 'Present (On Time)';
            badgeClass = 'success';
        } else if (hours24 >= 17) {
            status = 'A';
            statusText = 'Absent (Clock In after 5PM)';
            badgeClass = 'danger';
        } else {
            status = 'L';
            statusText = 'Late';
            badgeClass = 'warning';
        }

        // Set hidden input for status
        document.getElementById('statusHidden').value = status;

        tbody.innerHTML = `
          <tr><th>Date</th><td name="date">${recordedDate}</td></tr>
          <tr><th>Time In (24hr)</th><td name="timeIn">${recordedTime24}</td></tr>
          <tr><th>Status</th><td><span class="badge bg-${badgeClass}"><span name="status">${status}</span> - ${statusText}</span></td></tr>
        `;
      } else {
        // Clock Out Logic
        // Set Time Out, clear Time In, clear status
        document.getElementById('timeOutHidden').value = recordedTime24;
        document.getElementById('timeInHidden').value = '';
        document.getElementById('statusHidden').value = '';
        
        let undertime = '';
        let overtime = '';
        let undertimeHours = 0;
        let overtimeHours = 0;

        if (hours24 < 17) {
          // Calculate string for display
          const targetTime = new Date(`${recordedDate} 17:00:00`);
          const clockOutTime = new Date(`${recordedDate} ${recordedTime24}`);
          const diffMs = targetTime - clockOutTime;
          const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
          const diffMins = Math.round((diffMs % (1000 * 60 * 60)) / (1000 * 60));
          
          undertime = `${diffHours}h ${diffMins}m early`;
          undertimeHours = diffMs / (1000 * 60 * 60); // Total hours as decimal

        } else if (hours24 > 17 || (hours24 === 17 && minutes > 0)) {
          // Calculate string for display
          const diffHours = hours24 - 17;
          const diffMins = minutes;
          overtime = `${diffHours}h ${diffMins}m`;
          if (hours24 === 17) overtime = `${diffMins}m`;

          // Calculate hours for hidden input
          overtimeHours = diffHours + (diffMins / 60);
        } else {
          overtime = 'None';
          undertime = 'None';
        }

        // Set hidden inputs for CLOCK OUT
        document.getElementById('undertimeHoursHidden').value = undertimeHours.toFixed(2);
        document.getElementById('overtimeHoursHidden').value = overtimeHours.toFixed(2);

        tbody.innerHTML = `
          <tr><th>Date</th><td>${recordedDate}</td></tr>
          <tr><th>Time Out (24hr)</th><td>${recordedTime24}</td></tr>
          <tr><th>Undertime</th><td><span class="text-danger fw-bold">${undertime || 'None'}</span></td></tr>
          <tr><th>Overtime</th><td><span class="text-success fw-bold">${overtime || 'None'}</span></td></tr>
        `;
      }
    });
  });
</script>
