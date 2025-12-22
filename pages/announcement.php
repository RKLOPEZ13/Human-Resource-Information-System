<style>
  /* --- Modern Color Palette & Overrides --- */
  :root {
    --primary-color: #4154f1;
    --primary-light: #f6f9ff;
    --text-dark: #012970;
    --text-muted: #6c757d;
    --border-radius: 12px;
    --card-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
  }

  /* Stepper */
  .stepper {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
  }
  .step-item {
    display: flex;
    align-items: center;
    position: relative;
    padding: 0 20px;
    color: var(--text-muted);
    font-weight: 600;
  }
  .step-item.active {
    color: var(--primary-color);
  }
  .step-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-weight: 700;
    transition: all 0.3s ease;
  }
  .step-item.active .step-circle {
    background: var(--primary-color);
    color: #fff;
    box-shadow: 0 4px 10px rgba(65, 84, 241, 0.3);
  }

  /* Cards */
  .modern-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    background: #fff;
    padding: 2rem;
    transition: all 0.3s ease-in-out;
  }

  /* Form Elements */
  .form-control {
    border-radius: 8px;
    padding: 12px 15px;
    border: 1px solid #ced4da;
    background-color: #fff;
  }
  .form-control:focus {
    box-shadow: 0 0 0 4px var(--primary-light);
    border-color: var(--primary-color);
  }
  .form-label {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.95rem;
  }

  /* Department Checkbox Cards */
  .dept-card {
    cursor: pointer;
    transition: transform 0.2s, border-color 0.2s;
    border: 1px solid #eee;
  }
  .dept-card:hover {
    transform: translateY(-2px);
    border-color: var(--primary-color);
    background-color: var(--primary-light);
  }
  .dept-card .form-check-input {
    cursor: pointer;
  }

  /* Employee Table */
  .table-modern thead th {
    border-top: none;
    border-bottom: 2px solid #f1f1f1;
    color: var(--text-muted);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .avatar-circle {
    width: 32px;
    height: 32px;
    background-color: var(--primary-light);
    color: var(--primary-color);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    margin-right: 10px;
  }
  .status-badge {
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 600;
  }
  .status-active { background: #d1e7dd; color: #0f5132; }
  .status-leave { background: #fff3cd; color: #664d03; }

  /* Buttons */
  .btn {
    border-radius: 8px;
    padding: 10px 24px;
    font-weight: 600;
    letter-spacing: 0.3px;
  }
  .btn-primary {
    background: var(--primary-color);
    border: none;
    box-shadow: 0 4px 10px rgba(65, 84, 241, 0.2);
  }
  .btn-primary:hover {
    background: #2a3ecc;
  }

  /* Nav Tabs */
  .nav-tabs-modern {
    border-bottom: 2px solid #eee;
  }
  .nav-tabs-modern .nav-link {
    border: none;
    color: var(--text-muted);
    font-weight: 600;
    padding-bottom: 12px;
    margin-bottom: -2px;
    transition: color 0.3s;
  }
  .nav-tabs-modern .nav-link:hover {
    color: var(--primary-color);
  }
  .nav-tabs-modern .nav-link.active {
    color: var(--primary-color);
    border-bottom: 3px solid var(--primary-color);
    background: transparent;
  }
</style>

<div class="pagetitle mb-4 pt-3 container-fluid">
  <h1>Announcement</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none text-muted">Home</a></li>
      <li class="breadcrumb-item active">Announcements</li>
    </ol>
  </nav>
</div>

<div class="container-fluid">

  <div class="stepper">
    <div class="step-item active" id="stepper-1">
      <div class="step-circle">1</div>
      <span>Compose Message</span>
    </div>
    <div class="step-item" id="stepper-2">
      <div class="step-circle">2</div>
      <span>Target Audience</span>
    </div>
  </div>

  <section class="modern-card mb-5 fade show" id="step1-card">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0 fw-bold text-dark">Draft Announcement</h4>
      <span class="badge bg-light text-primary border">Step 1 of 2</span>
    </div>

    <form id="composeForm">
      <div class="row g-4">
        
        <div class="col-12">
          <label class="form-label">Subject Line</label>
          <input type="text" class="form-control form-control-lg fw-bold text-dark" 
                 id="announcementSubject"
                 placeholder="e.g., Annual Company Outing 2025" 
                 value="Year-End Performance Bonus Announcement">
        </div>

        <div class="col-12">
          <label class="form-label">Salutation / Header</label>
          <input type="text" class="form-control" 
                 id="announcementHeader"
                 placeholder="e.g., Dear Team..." value="Dear Valued Team Members,">
        </div>

        <div class="col-12">
          <label class="form-label">Message Content</label>
          <textarea class="form-control" rows="8" 
                    id="announcementBody"
                    placeholder="Type your message here...">We are pleased to inform everyone that performance bonuses for FY2025 will be credited on December 20, 2025.

The bonus amount is based on individual performance reviews and company targets achieved.

Thank you for your hard work and dedication this year!</textarea>
          <div class="form-text mt-2"><i class="bi bi-info-circle"></i> Supports plain text and basic HTML tags.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Sign-off</label>
          <input type="text" class="form-control" 
                 id="announcementClosing"
                 value="Warm regards,">
        </div>
        <div class="col-md-6">
          <label class="form-label">Sender Signature</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-person-badge"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'HR Admin'; ?>" readonly style="background-color: #fff;">
          </div>
        </div>

      </div>

      <div class="d-flex justify-content-end mt-5">
        <button type="button" class="btn btn-primary" onclick="goToStep(2)">
          Next: Select Audience &nbsp;<i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </form>
  </section>

  <section class="modern-card mb-5 d-none fade" id="step2-card">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0 fw-bold text-dark">Distribution Settings</h4>
      <span class="badge bg-light text-primary border">Step 2 of 2</span>
    </div>

    <div class="p-3 mb-4 rounded bg-light border border-dashed">
      <label class="form-label mb-2 d-block fw-bold">Delivery Channel</label>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="sendEmail" checked>
        <label class="form-check-label" for="sendEmail">Send via Email (BCC)</label>
      </div>
      <small class="text-muted">All recipients will receive the announcement via email.</small>
    </div>

    <ul class="nav nav-tabs nav-tabs-modern mb-4" id="recipientTabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-all">All Employees</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dept">By Department</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-individual">Select Individuals</button>
      </li>
    </ul>

    <div class="tab-content">

      <div class="tab-pane fade show active" id="tab-all">
        <div class="alert alert-primary d-flex align-items-center border-0 bg-primary-light text-dark p-4 rounded-3">
          <i class="bi bi-broadcast fs-2 me-3 text-primary"></i>
          <div>
            <h5 class="alert-heading fw-bold mb-1">Company-Wide Broadcast</h5>
            <p class="mb-0 text-muted">This message will be sent to all <strong><span id="totalCountDisplay">0</span></strong> active employees in the database.</p>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="tab-dept">
        <p class="text-muted mb-3 small text-uppercase fw-bold">Select Departments</p>
        <div class="row g-3" id="deptCheckboxContainer">
            </div>
      </div>

      <div class="tab-pane fade" id="tab-individual">
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                 <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="searchEmployeeInput" placeholder="Search by name...">
                  </div>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted me-2">Selected Recipients:</small> 
                <span id="selectedCount" class="badge bg-primary rounded-pill">0</span>
            </div>
        </div>

        <div class="border rounded-3 overflow-hidden">
            <div class="overflow-auto" style="height: 300px;">
              <table class="table table-hover table-modern mb-0 align-middle">
                <thead class="table-light sticky-top">
                  <tr>
                    <th width="50" class="ps-3"><input type="checkbox" class="form-check-input" id="selectAllInd"></th>
                    <th>Employee Name</th>
                    <th>Department</th>
                  </tr>
                </thead>
                <tbody id="individualTableBody">
                    </tbody>
              </table>
            </div>
        </div>
      </div>

    </div>

    <hr class="my-5 opacity-25">

    <div class="d-flex justify-content-between">
      <button type="button" class="btn btn-outline-secondary px-4" onclick="goToStep(1)">
        <i class="bi bi-arrow-left"></i> &nbsp;Back to Compose
      </button>

      <button type="button" class="btn btn-success px-5 shadow" id="announceBtn">
        <i class="bi bi-send-fill me-2"></i> Announce Now
      </button>
    </div>

  </section>

</div>

<div class="modal fade" id="successModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-body text-center py-5 px-4">
        <div class="mb-3 text-success">
            <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
        </div>
        <h3 class="fw-bold text-dark">Message Queued!</h3>
        <p class="text-muted mb-4">Your announcement has been successfully created and is being processed for delivery.</p>
        <button type="button" class="btn btn-primary w-100 rounded-pill" data-bs-dismiss="modal">Return to Dashboard</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    loadAnnouncementData();
});

function loadAnnouncementData() {
    $.ajax({
        url: "backend/get_announcement_data.php",  // Correct path
        method: "GET",
        dataType: "json",
        success: function(res) {
            renderDepartments(res.departments);
            renderEmployees(res.employees);
            $("#totalCountDisplay").text(res.employees.length);
        },
        error: function() {
            alert("Failed to load data. Check if backend/get_announcement_data.php exists.");
        }
    });
}

// Render Departments
function renderDepartments(departments) {
    const container = document.getElementById('deptCheckboxContainer');
    container.innerHTML = '';
    
    if(!departments) return;

    departments.forEach((dept, i) => {
        container.innerHTML += `
            <div class="col-md-3 col-sm-6">
              <div class="dept-card p-3 rounded bg-white h-100">
                <div class="form-check">
                    <input class="form-check-input dept-check" type="checkbox" value="${dept.id}" id="dept-${i}">
                    <label class="form-check-label fw-bold text-dark ps-2" for="dept-${i}">
                        ${dept.name}
                    </label>
                </div>
              </div>
            </div>
        `;
    });
}

// Render Employees
function renderEmployees(emps) {
    const tbody = document.getElementById('individualTableBody');
    tbody.innerHTML = '';

    if(!emps) return;

    emps.forEach(emp => {
        // Handle name display safely
        const nameToUse = emp.full_name || "Unknown";
        const initials = getInitials(nameToUse);
        const statusClass = emp.status === "Active" ? "status-active" : "status-leave";
        // Use employee_number as the value for checkbox
        const empId = emp.employee_number; 

        tbody.innerHTML += `
            <tr class="emp-row">
                <td class="ps-3">
                    <input type="checkbox" class="form-check-input emp-checkbox" value="${empId}">
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle">${initials}</div>
                        <div>
                            <div class="fw-bold text-dark emp-name">${nameToUse}</div>
                            <div class="small text-muted">${emp.position}
                                <span class="mx-1">•</span>
                                <span class="status-badge ${statusClass}">${emp.status}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-light text-dark border">${emp.dept_name || 'Unassigned'}</span></td>
            </tr>
        `;
    });

    attachCheckboxListeners();
}

function getInitials(name) {
    if(!name) return "?";
    let parts = name.split(" ");
    if(parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0,2).toUpperCase();
}

// --- Logic: Selection & Search ---

document.getElementById('searchEmployeeInput').addEventListener('keyup', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.emp-row').forEach(row => {
        const name = row.querySelector('.emp-name').innerText.toLowerCase();
        row.style.display = name.includes(q) ? '' : 'none';
    });
});

document.getElementById('selectAllInd').addEventListener('change', function () {
    document.querySelectorAll('.emp-checkbox').forEach(cb => {
        if (cb.closest('tr').style.display !== 'none') cb.checked = this.checked;
    });
    updateCount();
});

function attachCheckboxListeners() {
    document.querySelectorAll('.emp-checkbox').forEach(cb => cb.addEventListener('change', updateCount));
}

function updateCount() {
    const count = document.querySelectorAll('.emp-checkbox:checked').length;
    document.getElementById('selectedCount').innerText = count;
}

// --- Stepper Navigation ---

function goToStep(step) {
    if(step === 2) {
        document.getElementById('step1-card').classList.remove('show');
        document.getElementById('step1-card').classList.add('d-none');
        
        document.getElementById('step2-card').classList.remove('d-none');
        setTimeout(() => document.getElementById('step2-card').classList.add('show'), 50);

        document.getElementById('stepper-1').classList.remove('active');
        document.getElementById('stepper-2').classList.add('active');
    } else {
        document.getElementById('step2-card').classList.remove('show');
        document.getElementById('step2-card').classList.add('d-none');
        
        document.getElementById('step1-card').classList.remove('d-none');
        setTimeout(() => document.getElementById('step1-card').classList.add('show'), 50);

        document.getElementById('stepper-2').classList.remove('active');
        document.getElementById('stepper-1').classList.add('active');
    }
}

// --- Submit Announcement ---

$("#announceBtn").click(function() {
    let target_type = "All";
    let departments = [];
    let selected_employees = [];

    if ($("#tab-dept").hasClass("show active")) {
        target_type = "Department";
        $(".dept-check:checked").each(function() {
            departments.push($(this).val());
        });
        if (departments.length === 0) {
            alert("Please select at least one department.");
            return;
        }
    } 
    else if ($("#tab-individual").hasClass("show active")) {
        target_type = "Individual";
        $(".emp-checkbox:checked").each(function() {
            selected_employees.push($(this).val());
        });
        if (selected_employees.length === 0) {
            alert("Please select at least one employee.");
            return;
        }
    }

    const payload = {
        subject: $("#announcementSubject").val().trim(),
        header: $("#announcementHeader").val().trim(),
        content: $("#announcementBody").val().trim(),
        closing: $("#announcementClosing").val().trim(),
        channels: JSON.stringify({ email: $("#sendEmail").is(":checked") }), // SMS removed
        target_type: target_type,
        departments: departments,
        selected_employees: selected_employees
    };

    if (!payload.subject || !payload.content) {
        alert("Subject and Message Content are required!");
        return;
    }

    const btn = $(this);
    btn.prop("disabled", true).html("Sending...");

    $.post("backend/send_announcement.php", payload, function(res) {
        if (res.success) {
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        } else {
            alert("Error: " + (res.message || "Unknown error"));
        }
    }, "json")
    .fail(function(xhr) {
        console.error(xhr.responseText);
        alert("Connection failed! Is send_announcement.php reachable?\nCheck path: backend/send_announcement.php");
    })
    .always(function() {
        btn.prop("disabled", false).html('<i class="bi bi-send-fill me-2"></i> Announce Now');
    });
});
</script>
