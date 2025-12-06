<div class="pagetitle">
  <h1>Attendance</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
      <li class="breadcrumb-item active">Attendance</li>
    </ol>
  </nav>
</div>

<div class="card mt-4">
  <div class="card-body">
    
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
       <div>
           <h5 class="card-title mb-0">Monthly View</h5>
           <small class="text-muted">Hover over cells for time logs (Auto-refreshes every 5s)</small>
       </div>
       
       <div class="d-flex gap-3 small align-items-center bg-light p-2 rounded">
           <span><span class="badge bg-success">P</span> Present</span>
           <span><span class="badge bg-danger">A</span> Absent</span>
           <span><span class="badge bg-warning text-dark">L</span> Late</span>
           <span><span class="badge bg-info text-dark">VL</span> Vacation</span>
           <span><span class="badge bg-primary">SL</span> Sick</span>
           <span><span class="badge bg-secondary">W</span> Weekend</span>
           <span class="text-muted small">--</span> Missing Record
       </div>
    </div>

    <div class="row g-2 mb-3 align-items-center">
      <div class="col-md-auto">
          <input type="month" class="form-control" id="monthFilter">
      </div>
      <div class="col-md-auto">
          <select id="deptFilter" class="form-select" style="width: 220px;">
              <option value="">All Departments</option>
          </select>
      </div>
      <div class="col-md">
          <input type="text" id="searchEmp" class="form-control" placeholder="Search employee by name...">
      </div>
      <div class="col-md-auto ms-auto d-flex gap-2">
          <button class="btn btn-primary" id="markLeaveBtn" data-bs-toggle="modal" data-bs-target="#markLeaveModal">
            Set/Approve Leave
          </button>
          <button class="btn btn-success" id="exportBtn">
            Export Excel
          </button>
      </div>
    </div>
    
    <div id="alertContainer" class="mt-3"></div>

    <div class="table-responsive border rounded" style="max-height: 650px;">
      <table class="table table-bordered text-center align-middle mb-0 table-hover" style="min-width: 1600px;">
        <thead class="table-light sticky-top" style="z-index: 1020;">
          <tr id="tableHeaderRow">
            </tr>
        </thead>
        <tbody id="attendanceGridBody">
          <tr><td colspan="100%" class="text-center p-5">Loading data...</td></tr>
        </tbody>
      </table>
    </div>

  </div>
</div>

<div class="modal fade" id="markLeaveModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">HR Leave Approval & Setting</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="leaveApprovalForm">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-bold">Employee</label>
              <input class="form-control" list="employeeDatalist" id="leaveEmpSearch" placeholder="Search employee..." required>
              <datalist id="employeeDatalist"></datalist>
              <div class="small text-muted mt-1">Employee: <strong id="selectedEmployeeDisplay">None</strong></div>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Leave Type</label>
              <select class="form-select" id="leaveTypeSelect" required>
                <option value="">Choose type...</option>
                <option value="VL">Vacation Leave (VL)</option>
                <option value="SL">Sick Leave (SL)</option>
                <option value="Emergency">Emergency Leave</option>
                <option value="Maternity">Maternity Leave (Fixed)</option>
                <option value="Paternity">Paternity Leave (Fixed)</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Available Balance</label>
              <div class="alert alert-info py-2 mb-0">
                <span id="balanceDisplay">Select employee & type</span>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Start Date</label>
              <input type="date" class="form-control" id="leaveStart" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">End Date</label>
              <input type="date" class="form-control" id="leaveEnd" required>
            </div>

            <div class="col-12">
              <label class="form-label">Reason</label>
              <textarea class="form-control" rows="3" id="leaveReason" placeholder="HR reason for approval/setting..." required></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitLeaveBtn">Approve & Set Leave</button>
      </div>
    </div>
  </div>
</div>

<script>
// State Management
let refreshInterval;
let popoverList = [];
let employeeData = []; // Global store for employee details and balances

// Initialization
document.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    const isoMonth = today.toISOString().slice(0, 7);
    document.getElementById('monthFilter').value = isoMonth;
    
    const todayStr = today.toISOString().split('T')[0];
    document.getElementById('leaveStart').min = todayStr;
    document.getElementById('leaveEnd').min = todayStr;

    fetchFilters();
    fetchModalEmployees();
    fetchGridData();

    // Event Listeners for Grid Filters
    document.getElementById('monthFilter').addEventListener('change', () => { fetchGridData(); resetInterval(); });
    document.getElementById('deptFilter').addEventListener('change', () => { fetchGridData(); resetInterval(); });
    document.getElementById('searchEmp').addEventListener('keyup', () => { fetchGridData(); });

    // Event Listeners for Modal Logic
    document.getElementById('leaveEmpSearch').addEventListener('input', updateBalanceDisplay);
    document.getElementById('leaveTypeSelect').addEventListener('change', updateBalanceDisplay);
    document.getElementById('leaveStart').addEventListener('change', updateBalanceDisplay);
    document.getElementById('leaveEnd').addEventListener('change', updateBalanceDisplay);
    document.getElementById('submitLeaveBtn').addEventListener('click', submitLeaveApproval);

    startInterval();
});

function startInterval() {
    refreshInterval = setInterval(fetchGridData, 5000);
}

function resetInterval() {
    clearInterval(refreshInterval);
    startInterval();
}

// Helper to extract employee number from the datalist input string
function getEmployeeNumberFromInput() {
    const input = document.getElementById('leaveEmpSearch').value;
    const match = input.match(/\((EMP\d+)\)$/);
    return match ? match[1] : null;
}

// Helper to format success/error messages
function displayAlert(message, type) {
    const container = document.getElementById('alertContainer');
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    container.innerHTML = alertHtml;
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alertEl = container.querySelector('.alert');
        if (alertEl) {
            bootstrap.Alert.getOrCreateInstance(alertEl).close();
        }
    }, 5000);
}

// --- MODAL LOGIC ---

// Updates the balance display based on selected employee and leave type
function updateBalanceDisplay() {
    const empNum = getEmployeeNumberFromInput();
    const leaveType = document.getElementById('leaveTypeSelect').value;
    const balanceDisplay = document.getElementById('balanceDisplay');
    const inputField = document.getElementById('leaveEmpSearch');
    
    // Update selected employee display
    document.getElementById('selectedEmployeeDisplay').textContent = inputField.value || 'None';

    balanceDisplay.innerHTML = '<span class="text-muted">Select employee & type</span>';
    
    if (!empNum || !leaveType) return;

    const employee = employeeData.find(e => e.employee_number === empNum);
    if (!employee) return;

    let balanceKey;
    let message = '';

    if (leaveType === 'VL') balanceKey = 'vacation_leave';
    else if (leaveType === 'SL') balanceKey = 'sick_leave';
    else if (leaveType === 'Emergency') balanceKey = 'emergency_leave';
    
    if (balanceKey) {
        message = `<span class="fw-bold">${employee[balanceKey] || 0} days remaining</span>`;
    } else if (['Maternity', 'Paternity'].includes(leaveType)) {
        message = `Fixed entitlement. No balance deduction required.`;
    } else {
        message = '<span class="text-danger">Invalid Leave Type.</span>';
    }

    balanceDisplay.innerHTML = message;
}

// Handles the AJAX submission for HR approval
function submitLeaveApproval() {
    const form = document.getElementById('leaveApprovalForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const empNum = getEmployeeNumberFromInput();
    if (!empNum) {
        displayAlert("Please select a valid employee from the list.", 'danger');
        return;
    }

    const leaveType = document.getElementById('leaveTypeSelect').value;
    const startDate = document.getElementById('leaveStart').value;
    const endDate = document.getElementById('leaveEnd').value;
    const reason = document.getElementById('leaveReason').value;

    const formData = new FormData();
    formData.append('employee_number', empNum);
    formData.append('leave_type', leaveType);
    formData.append('start_date', startDate);
    formData.append('end_date', endDate);
    formData.append('reason', reason);

    document.getElementById('submitLeaveBtn').disabled = true;
    document.getElementById('submitLeaveBtn').textContent = 'Processing...';

    fetch('./backend/attendance_data.php?action=approve_leave', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            displayAlert(data.message, 'success');
            // HIDE MODAL ON SUCCESS
            const modal = bootstrap.Modal.getInstance(document.getElementById('markLeaveModal'));
            modal.hide();
            // Reload grid data immediately
            fetchGridData();
        } else {
            displayAlert(`Approval Failed: ${data.message}`, 'danger');
        }
    })
    .catch(error => {
        console.error('Submission error:', error);
        displayAlert('An unexpected error occurred during submission.', 'danger');
    })
    .finally(() => {
        document.getElementById('submitLeaveBtn').disabled = false;
        document.getElementById('submitLeaveBtn').textContent = 'Approve & Set Leave';
    });
}

// 1. Fetch Department Options
function fetchFilters() {
    fetch('./backend/attendance_data.php?action=get_filters')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('deptFilter');
            data.departments.forEach(dept => {
                select.add(new Option(dept, dept));
            });
        });
}

// 2. Fetch Employee List for Modal (and store balances)
function fetchModalEmployees() {
    fetch('./backend/attendance_data.php?action=get_employees')
        .then(res => res.json())
        .then(data => {
            employeeData = data.employees; // Store the data
            const datalist = document.getElementById('employeeDatalist');
            datalist.innerHTML = '';
            employeeData.forEach(e => {
                const opt = document.createElement('option');
                opt.value = `${e.first_name} ${e.last_name} (${e.employee_number})`;
                datalist.appendChild(opt);
            });
        });
}

// 3. Main Data Fetcher (Grid)
function fetchGridData() {
    const month = document.getElementById('monthFilter').value;
    const dept = document.getElementById('deptFilter').value;
    const search = document.getElementById('searchEmp').value;

    const params = new URLSearchParams({
        action: 'grid',
        month: month,
        dept: dept,
        search: search
    });

    fetch(`./backend/attendance_data.php?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            renderHeader(data.days_in_month);
            renderBody(data);
            initPopovers();
        })
        .catch(err => console.error("Error loading attendance:", err));
}

// Render Table Header (unchanged)
function renderHeader(days) {
    const headerRow = document.getElementById('tableHeaderRow');
    if(headerRow.children.length === days + 1) return; 

    let html = `<th class="sticky-col-start bg-light fw-bold shadow-sm" style="width: 220px;">Employee</th>`;
    for (let i = 1; i <= days; i++) {
        html += `<th class="day-col">${i}</th>`;
    }
    headerRow.innerHTML = html;
}

// Render Table Body (unchanged)
function renderBody(data) {
    const tbody = document.getElementById('attendanceGridBody');
    let html = '';

    if (data.employees.length === 0) {
        tbody.innerHTML = '<tr><td colspan="100%" class="text-center p-4 text-muted">No employees found</td></tr>';
        return;
    }

    data.employees.forEach(emp => {
        const fullName = `${emp.first_name} ${emp.last_name}`;
        
        html += `<tr>
            <td class="sticky-col-start text-start bg-white">
                <div class="fw-bold text-truncate" style="max-width: 200px;">${fullName}</div>
                <div class="small text-muted" style="font-size: 0.75rem;">${emp.position} • ${emp.dept_name || '-'}</div>
            </td>`;

        for (let d = 1; d <= data.days_in_month; d++) {
            const dateObj = new Date(data.year, data.month - 1, d);
            const isWeekend = dateObj.getDay() === 0 || dateObj.getDay() === 6;
            
            const log = (data.logs[emp.employee_number] && data.logs[emp.employee_number][d]) 
                        ? data.logs[emp.employee_number][d] 
                        : null;

            html += `<td class="att-cell p-1">${getCellContent(log, d, data.year, data.month, isWeekend)}</td>`;
        }
        html += `</tr>`;
    });

    tbody.innerHTML = html;
}

// RENDER FUNCTION (UPDATED to use 'W' for weekends)
function getCellContent(log, day, year, month, isWeekend) {
    let status = '-';
    let timeIn = '-';
    let timeOut = '-';
    let ot = 0;

    const checkDate = new Date(year, month - 1, day);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Normalize today's date

    if (log) {
        status = log.status;
        timeIn = log.time_in || '-';
        timeOut = log.time_out || '-';
        ot = log.overtime_hours || 0;
    } else if (isWeekend) {
        // *** CHANGE: Weekend status is now 'W' ***
        status = 'W'; 
    } else {
        // No log and it's a weekday
        if (checkDate < today) {
            // Past date with no log uses '--' (Missing Record)
            status = '--'; 
        } else {
            // Date is today or in the future
            status = '-'; 
        }
    }

    // 2. Badges and Labels
    const badges = {
        P: `<i class="bi bi-check-circle-fill text-success fs-5"></i>`,
        A: `<span class="badge bg-danger">A</span>`,
        L: `<span class="badge bg-warning text-dark">L</span>`,
        VL: `<span class="badge bg-info text-dark">VL</span>`,
        SL: `<span class="badge bg-primary">SL</span>`,
        Emergency: `<span class="badge bg-warning text-dark">EL</span>`, 
        Maternity: `<span class="badge bg-success">ML</span>`,       
        Paternity: `<span class="badge bg-success">PL</span>`,       
        '--': `<small class="text-muted text-opacity-50">--</small>`, 
        // *** NEW BADGE FOR 'W' ***
        'W': `<span class="badge bg-secondary">W</span>`,
        '-': `<small class="text-muted text-opacity-25">-</small>` // Fallback for future/holiday
    };

    // 3. Popover Content
    const dateStr = new Date(year, month - 1, day).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    
    let content = '';

    if (status === '--') {
         content = `<div class="small text-start text-warning"><strong>No Record Found</strong><br>Status is Unknown.</div>`;
    } else if (status === 'W') {
        // *** Popover content for 'W' ***
        content = `<div class="small text-start text-muted">Weekend</div>`;
    } else if (status === '-') {
        content = `<div class="small text-start text-muted">Future Date</div>`;
    } else if (['VL', 'SL', 'Emergency', 'Maternity', 'Paternity'].includes(status)) {
        content = `<div class="small text-start"><strong>Status:</strong> ${getStatusLabel(status)}</div><div class="small mt-1 text-muted">Approved Leave</div>`;
    } else {
        // Standard attendance content (P, A, L)
        content = `
            <div class="small text-start">
                <div><strong>Status:</strong> ${getStatusLabel(status)}</div>
                ${status !== 'A' ? `
                    <div class="mt-1"><strong>In:</strong> ${timeIn}</div>
                    <div><strong>Out:</strong> ${timeOut}</div>
                    ${ot > 0 ? `<div class="text-success fw-bold">OT: ${ot} hrs</div>` : ''}
                ` : ''}
            </div>`;
    }

    // Return Cell HTML
    return `<div class="w-100 h-100 d-flex align-items-center justify-content-center"
                 data-bs-toggle="popover" 
                 data-bs-placement="top" 
                 data-bs-html="true" 
                 data-bs-trigger="hover focus"
                 title="${dateStr}" 
                 data-bs-content="${content.replace(/"/g, '&quot;')}">
              ${badges[status] || badges['-']}
            </div>`;
}

function getStatusLabel(code) {
    const map = {
        'P': 'Present', 'A': 'Absent', 'L': 'Late', 
        'VL': 'Vacation Leave', 'SL': 'Sick Leave', 
        'Emergency': 'Emergency Leave',
        'Maternity': 'Maternity Leave',
        'Paternity': 'Paternity Leave',
        'W': 'Weekend', // New label
        '-': 'No Log (Future)',
        '--': 'Missing Record'
    };
    return map[code] || code;
}

// Re-initialize Bootstrap Popovers after DOM update
function initPopovers() {
    popoverList.forEach(p => p.dispose());
    popoverList = [];

    const triggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverList = triggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}
</script>