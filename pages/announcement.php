<div class="pagetitle mb-4">
  <h1>Announcement</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
      <li class="breadcrumb-item active">Announcements</li>
    </ol>
  </nav>
</div>

<div class="container-fluid">

  <!-- ====================== STEP 1 ====================== -->
  <section class="mb-4 p-4 border rounded bg-white shadow-sm" id="step1-card">

    <h4 class="mb-3 fw-bold d-flex align-items-center">
      Step 1: Compose Message
    </h4>
    <hr>

    <form id="composeForm" class="mt-4">

      <!-- Subject -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Subject / Title</label>
        <input type="text" class="form-control form-control-lg" placeholder="e.g., Annual Company Outing 2025" value="Year-End Performance Bonus Announcement">
      </div>

      <!-- Header -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Header / Introduction</label>
        <input type="text" class="form-control" placeholder="e.g., Greetings Team..." value="Dear Valued Team Members,">
      </div>

      <!-- Main Body -->
      <div class="mb-4">
        <label class="form-label fw-semibold">Main Body</label>
        <textarea class="form-control" rows="6" placeholder="Type the detailed content...">We are pleased to inform everyone that performance bonuses for FY2025 will be credited on December 20, 2025.

The bonus amount is based on individual performance reviews and company targets achieved.

Thank you for your hard work and dedication this year!</textarea>
        <div class="form-text">HTML formatting or plain text allowed.</div>
      </div>

      <!-- Closing + Issued By -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <label class="form-label fw-semibold">Closing / Final Note</label>
          <input type="text" class="form-control" placeholder="e.g., Best regards..." value="Warm regards,">
        </div>

        <div class="col-md-6 mb-4">
          <label class="form-label fw-semibold">Issued By</label>
          <div class="input-group">
            <span class="input-group-text"></span>
            <input type="text" class="form-control" value="Jane Smith – HR Manager" readonly>
          </div>
        </div>
      </div>

      <div class="text-end mt-4">
        <button type="button" class="btn btn-primary btn-lg px-4" onclick="goToStep(2)">
          Select Recipients
        </button>
      </div>

    </form>

  </section>

  <!-- ====================== STEP 2 ====================== -->
  <section class="mb-4 p-4 border rounded bg-white shadow-sm d-none" id="step2-card">

    <h4 class="mb-3 fw-bold d-flex align-items-center text-success">
      Step 2: Audience & Channels
    </h4>
    <hr>

    <!-- Channels -->
    <div class="mb-4 p-3 border bg-light rounded">
      <label class="form-label fw-bold">Delivery Channels:</label>

      <div class="d-flex gap-4 mt-2">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="sendEmail" checked>
          <label class="form-check-label" for="sendEmail">
            Email
          </label>
        </div>

        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" id="sendSMS">
          <label class="form-check-label" for="sendSMS">
            SMS
          </label>
        </div>
      </div>
    </div>

    <h6 class="fw-bold">Recipients</h6>
    <ul class="nav nav-tabs nav-tabs-bordered mb-3" id="recipientTabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-all" type="button">
          All Employees
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dept" type="button">
          By Department
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-individual" type="button">
          Select Individuals
        </button>
      </li>
    </ul>

    <div class="tab-content pt-2">

      <!-- ALL -->
      <div class="tab-pane fade show active" id="tab-all">
        <div class="alert alert-info d-flex align-items-center">
          <div>
            <strong>Broadcasting to Company</strong><br>
            This announcement will be sent to all <strong><span id="totalCountDisplay">8</span></strong> active employees.
          </div>
        </div>
      </div>

      <!-- DEPARTMENT -->
      <div class="tab-pane fade" id="tab-dept">
        <p class="small text-muted">Select target departments:</p>
        <div class="row g-3" id="deptCheckboxContainer"></div>
      </div>

      <!-- INDIVIDUAL -->
      <div class="tab-pane fade" id="tab-individual">
        <div class="input-group mb-3">
          <span class="input-group-text"></span>
          <input type="text" class="form-control" id="searchEmployeeInput" placeholder="Search employees...">
        </div>

        <div class="border rounded p-0 overflow-auto" style="height: 260px;">
          <table class="table table-hover table-striped mb-0">
            <thead class="table-light sticky-top">
              <tr>
                <th width="50"><input type="checkbox" class="form-check-input" id="selectAllInd"></th>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Department</th>
              </tr>
            </thead>
            <tbody id="individualTableBody"></tbody>
          </table>
        </div>

        <div class="text-end mt-2 small text-muted">
          Selected: <span id="selectedCount" class="fw-bold text-primary">0</span>
        </div>
      </div>

    </div>

    <div class="d-flex justify-content-between mt-4">
      <button type="button" class="btn btn-secondary px-4" onclick="goToStep(1)">
        Back
      </button>

      <button type="button" class="btn btn-success px-4" id="announceBtn">
        Announce Now
      </button>
    </div>

  </section>

</div>

<!-- SUCCESS MODAL -->
<div class="modal fade" id="successModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center py-5">
        <div class="display-1 text-success mb-3"></div>
        <h3>Announcement Sent!</h3>
        <p class="text-muted">Your message has been queued for delivery.</p>
        <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
// Realistic mock data directly from your SQL dump
const DEPARTMENTS = [
    { id: 1, name: "IT" },
    { id: 2, name: "Human Resources" },
    { id: 3, name: "Finance" },
    { id: 4, name: "Sales" },
    { id: 5, name: "Marketing" },
    { id: 6, name: "Engineering" },
    { id: 7, name: "Operations" },
    { id: 8, name: "Customer Support" }
];

const EMPLOYEES = [
    { id: 1,  first_name: "John",      last_name: "Doe",         position: "Software Engineer",      dept: "Engineering",        status: "Active" },
    { id: 2,  first_name: "Jane",      last_name: "Smith",       position: "HR Manager",             dept: "Human Resources",    status: "Active" },
    { id: 3,  first_name: "Mark",      last_name: "Lee",         position: "Accountant",             dept: "Finance",            status: "Active" },
    { id: 4,  first_name: "Sarah",     last_name: "Connor",      position: "Sales Rep",              dept: "Sales",              status: "On Leave" },
    { id: 6,  first_name: "Juan",      last_name: "Dela Cruz",   position: "Web Developer",          dept: "IT",                 status: "Active" },
    { id: 7,  first_name: "Maria",     last_name: "Santos",      position: "HR Specialist",          dept: "Human Resources",    status: "Active" },
    { id: 8,  first_name: "Pedro",     last_name: "Reyes",       position: "Financial Analyst",      dept: "Finance",            status: "Active" }
];

// Update total count
document.getElementById("totalCountDisplay").textContent = EMPLOYEES.length;

// Render Departments
function renderDepartments() {
    const container = document.getElementById('deptCheckboxContainer');
    container.innerHTML = '';
    DEPARTMENTS.forEach((dept, i) => {
        container.innerHTML += `
            <div class="col-md-4">
                <div class="form-check p-3 border rounded bg-white">
                    <input class="form-check-input" type="checkbox" value="${dept.id}" id="dept-${i}">
                    <label class="form-check-label fw-medium" for="dept-${i}">
                        ${dept.name}
                    </label>
                </div>
            </div>
        `;
    });
}

// Render Individual Employees (now using first_name + last_name)
function renderIndividualList() {
    const tbody = document.getElementById('individualTableBody');
    tbody.innerHTML = '';

    EMPLOYEES.forEach(emp => {
        const fullName = `${emp.first_name} ${emp.last_name}`;
        tbody.innerHTML += `
            <tr class="emp-row">
                <td><input type="checkbox" class="form-check-input emp-checkbox" value="${emp.id}"></td>
                <td class="emp-name fw-medium">${fullName}</td>
                <td><small class="text-muted">${emp.position}</small></td>
                <td><span class="badge bg-light text-dark border">${emp.dept}</span></td>
            </tr>
        `;
    });

    attachCheckboxListeners();
}

// Search + Select All + Counter
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

// Navigation
function goToStep(step) {
    document.getElementById('step1-card').classList.toggle('d-none', step === 2);
    document.getElementById('step2-card').classList.toggle('d-none', step === 1);
}

// Fake send
document.getElementById('announceBtn').addEventListener('click', () => {
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
});

// Init
renderDepartments();
renderIndividualList();
</script>