<div class="pagetitle">
  <h1>Employees</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
      <li class="breadcrumb-item active">Employees</li>
    </ol>
  </nav>
</div>

<div class="card mt-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Employee Roster</h5>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
      <div>
        <button class="btn btn-primary me-2" id="addEmployeeBtn">
          Add Employee
        </button>
        <button class="btn btn-outline-success" id="exportBtn">
          Export List
        </button>
      </div>

      <div class="d-flex gap-2">
        <select id="departmentFilter" class="form-select" style="width: 220px;">
          <option value="">All Departments</option>
        </select>
        <input type="text" id="searchEmployee" class="form-control" placeholder="Search name..." style="width: 280px;">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>ID#</th>
            <th>Employee Name</th>
            <th>Position</th>
            <th>Department</th>
            <th>Type</th>
            <th>Location</th>
            <th>Status</th>
            <th width="100">Actions</th>
          </tr>
        </thead>
        <tbody id="employeeTableBody"></tbody>
      </table>
    </div>
  </div>
</div>

<div class="card mt-4">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title mb-0">Employee Birthdays</h5>
      <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-outline-primary active" id="showAllBirthdays">All</button>
        <button type="button" class="btn btn-outline-primary" id="showUpcomingBirthdays">Upcoming</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Employee</th>
            <th>Department</th>
            <th>Birthday</th>
            <th>Turns</th>
          </tr>
        </thead>
        <tbody id="birthdaysBody">
          <tr><td colspan="4" class="text-center text-muted">Loading birthdays...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="viewEmployeeModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewEmployeeName">Employee Profile</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewEmployeeBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="employeeFormModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      
      <div class="modal-header bg-light border-bottom-0 py-3 ps-4">
        <div>
            <h5 class="modal-title fw-bold text-primary" id="employeeFormTitle">Add New Employee</h5>
            <small class="text-muted">Fill in the details below to manage the employee record.</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body bg-light-subtle p-4">
        <form id="employeeForm" class="needs-validation">
          <input type="hidden" id="empId">

          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 ps-4">
                <h6 class="text-uppercase fw-bold text-primary small mb-0">
                    Personal Information
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstName" placeholder="e.g. Juan" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-secondary">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lastName" placeholder="e.g. Dela Cruz" required>
                    </div>
                     <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary">Age</label>
                        <input type="number" class="form-control" id="empAge" placeholder="--">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">@</span>
                            <input type="email" class="form-control" id="empEmail" placeholder="juan@company.com" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Phone Number</label>
                        <input type="text" class="form-control" id="empPhone" placeholder="+63 9xx xxx xxxx">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Home Address</label>
                        <input type="text" class="form-control" id="empAddress" placeholder="Unit, Street, City, Province">
                    </div>
                    <div class="col-12">
                         <label class="form-label small fw-bold text-secondary">Emergency Contact</label>
                         <input type="text" class="form-control" id="empEmergency" placeholder="Name - Relationship - Phone Number">
                    </div>
                </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 ps-4">
                <h6 class="text-uppercase fw-bold text-primary small mb-0">
                    Employment Details
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Department <span class="text-danger">*</span></label>
                        <select class="form-select" id="empDept" required></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Position Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="empPosition" placeholder="e.g. Software Engineer" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Manager</label>
                        <select class="form-select" id="empManager">
                            <option value="">None (Top Level)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                         <label class="form-label small fw-bold text-secondary">Employment Type</label>
                        <select class="form-select" id="empType">
                            <option>Full-Time</option>
                            <option>Part-Time</option>
                            <option>Contract</option>
                            <option>Intern</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Work Location</label>
                        <select class="form-select" id="empLocation">
                            <option>Headquarters</option>
                            <option>Remote</option>
                            <option>Branch Office</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Current Status</label>
                        <select class="form-select" id="empStatus">
                            <option>Active</option>
                            <option>On Leave</option>
                            <option>Terminated</option>
                            <option>Suspended</option>
                        </select>
                    </div>

                    <div class="col-12"><hr class="text-muted my-2 opacity-25"></div>

                    <div class="col-md-4">
                         <label class="form-label small fw-bold text-secondary">Date Hired</label>
                        <input type="date" class="form-control" id="empHiredDate">
                    </div>
                     <div class="col-md-4">
                         <label class="form-label small fw-bold text-secondary">Base Salary</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">₱</span>
                            <input type="number" step="0.01" class="form-control" id="empSalary" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-4">
                         <label class="form-label small fw-bold text-secondary">Date Terminated</label>
                        <input type="date" class="form-control bg-light" id="empTerminatedDate">
                        <div class="form-text small fst-italic mt-1" style="font-size: 0.75rem;">Leave blank unless terminated</div>
                    </div>
                </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer border-top-0 bg-light py-3">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" id="saveEmployeeBtn">
            Save Record
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let allEmployees = [];
let currentPage = 1;
const itemsPerPage = 10;

function getStatusBadge(status) {
  const map = {
    'Active': 'bg-success',
    'On Leave': 'bg-warning text-dark',
    'Terminated': 'bg-danger',
    'Suspended': 'bg-secondary'
  };
  return `<span class="badge ${map[status] || 'bg-secondary'}">${status}</span>`;
}

const formatSalary = (salary) => {
  if (!salary) return '—';
  return '₱' + parseFloat(salary).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

function viewEmployee(empNumber) {
  const emp = allEmployees.find(e => e.employee_number === empNumber);
  if (!emp) return;

  document.getElementById('viewEmployeeName').textContent = `${emp.first_name} ${emp.last_name} - Profile`;
  document.getElementById('viewEmployeeBody').innerHTML = `
    <div class="row g-4">
      <div class="col-md-4 text-center">
        <div class="text-center p-4 bg-light rounded">
          <div class="display-1 text-secondary mb-3">👤</div>
          <h4>${emp.first_name} ${emp.last_name}</h4>
          <p class="text-muted mb-2">${emp.position}</p>
          ${getStatusBadge(emp.status)}
        </div>
      </div>
      <div class="col-md-8">
        <h6 class="border-bottom pb-1 text-primary">Personal Information</h6>
        <div class="row g-2 small mb-3">
          <div class="col-5 fw-bold">Employee ID:</div><div class="col-7">${emp.employee_number}</div>
          <div class="col-5 fw-bold">Age:</div><div class="col-7">${emp.age || '—'}</div>
          <div class="col-5 fw-bold">Email:</div><div class="col-7">${emp.email}</div>
          <div class="col-5 fw-bold">Phone:</div><div class="col-7">${emp.phone || '—'}</div>
          <div class="col-5 fw-bold">Address:</div><div class="col-7">${emp.address || '—'}</div>
          <div class="col-5 fw-bold">Emergency:</div><div class="col-7 text-danger">${emp.emergency_contact || '—'}</div>
        </div>
        <h6 class="border-bottom pb-1 text-primary mt-4">Employment Details</h6>
        <div class="row g-2 small">
          <div class="col-5 fw-bold">Department:</div><div class="col-7">${emp.department || '—'}</div>
          <div class="col-5 fw-bold">Position:</div><div class="col-7">${emp.position}</div>
          <div class="col-5 fw-bold">Manager:</div><div class="col-7">${emp.manager_name || '—'}</div>
          <div class="col-5 fw-bold">Type/Location:</div><div class="col-7">${emp.employment_type} / ${emp.location}</div>
          <div class="col-5 fw-bold">Date Hired:</div><div class="col-7">${emp.date_hired}</div>
          <div class="col-5 fw-bold">Date Terminated:</div><div class="col-7">${emp.date_terminated || '—'}</div>
        </div>
      </div>
    </div>
  `;
  new bootstrap.Modal('#viewEmployeeModal').show();
}

function editEmployee(empNumber) {
  const emp = allEmployees.find(e => e.employee_number === empNumber);
  if (!emp) return;

  console.log('Editing employee:', emp); // DEBUG - Check what data we have

  document.getElementById('employeeFormTitle').textContent = "Edit Employee";
  document.getElementById('empId').value = emp.employee_number;
  document.getElementById('firstName').value = emp.first_name;
  document.getElementById('lastName').value = emp.last_name;
  document.getElementById('empEmail').value = emp.email;
  document.getElementById('empPhone').value = emp.phone || '';
  document.getElementById('empAge').value = emp.age || '';
  
  // FIXED: Use department name, not department_id
  document.getElementById('empDept').value = emp.department || '';
  
  document.getElementById('empPosition').value = emp.position;
  
  // FIXED: Use manager_number 
  document.getElementById('empManager').value = emp.manager_number || '';
  
  document.getElementById('empType').value = emp.employment_type;
  document.getElementById('empLocation').value = emp.location;
  document.getElementById('empStatus').value = emp.status;
  document.getElementById('empEmergency').value = emp.emergency_contact || '';
  document.getElementById('empAddress').value = emp.address || '';
  
  // FIXED: Make sure date_hired is set
  document.getElementById('empHiredDate').value = emp.date_hired || '';
  
  document.getElementById('empSalary').value = emp.base_salary || '';
  document.getElementById('empTerminatedDate').value = emp.date_terminated || '';

  new bootstrap.Modal('#employeeFormModal').show();
}

// Add this global listener for all Bootstrap modals
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hide.bs.modal', function() {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {

  document.getElementById('saveEmployeeBtn')?.addEventListener('click', async () => {
      const form = document.getElementById('employeeForm');

      if (!form.checkValidity()) {
          form.reportValidity();
          return;
      }

      const formData = new FormData();
      const managerValue = document.getElementById('empManager').value.trim();
      
      formData.append('emp_number', document.getElementById('empId').value);
      formData.append('first_name', document.getElementById('firstName').value.trim());
      formData.append('last_name', document.getElementById('lastName').value.trim());
      formData.append('email', document.getElementById('empEmail').value.trim());
      formData.append('phone', document.getElementById('empPhone').value.trim());
      formData.append('department', document.getElementById('empDept').value);
      formData.append('position', document.getElementById('empPosition').value.trim());
      formData.append('manager_id', managerValue || '');
      formData.append('employment_type', document.getElementById('empType').value);
      formData.append('location', document.getElementById('empLocation').value);
      formData.append('status', document.getElementById('empStatus').value);
      formData.append('emergency_contact', document.getElementById('empEmergency').value.trim());
      formData.append('address', document.getElementById('empAddress').value.trim());
      formData.append('date_hired', document.getElementById('empHiredDate').value);
      formData.append('age', document.getElementById('empAge').value);
      formData.append('base_salary', document.getElementById('empSalary').value);
      formData.append('date_terminated', document.getElementById('empTerminatedDate').value);

      console.log('Manager value being sent:', managerValue || '(empty)');

    try {
        const response = await fetch('./backend/save_employee.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (err) {
        console.error('Submission error:', err);
        alert('Failed to save employee. Check console for details.');
    }
});

  function filterAndPaginate() {
    const departmentFilter = document.getElementById('departmentFilter')?.value || '';
    const searchTerm = (document.getElementById('searchEmployee')?.value || '').toLowerCase();

    let filtered = allEmployees;

    if (departmentFilter !== "") {
      filtered = filtered.filter(emp => emp.department === departmentFilter);
    }

    if (searchTerm.length > 0) {
      filtered = filtered.filter(emp => {
        const fullName = `${emp.first_name} ${emp.last_name}`.toLowerCase();
        const position = (emp.position || '').toLowerCase();
        const email = (emp.email || '').toLowerCase();
        return fullName.includes(searchTerm) || position.includes(searchTerm) || email.includes(searchTerm);
      });
    }

    const totalItems = filtered.length;
    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
    currentPage = Math.min(currentPage, totalPages);

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = filtered.slice(start, end);

    renderTable(pageData);
    renderPagination(totalPages, totalItems);
  }

  function renderTable(employees) {
    const tbody = document.getElementById('employeeTableBody');
    tbody.innerHTML = '';

    if (employees.length === 0) {
      tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">No employees found</td></tr>';
      return;
    }

    employees.forEach(emp => {
      tbody.innerHTML += `
        <tr>
          <td>${emp.employee_number}</td>
          <td>${emp.first_name} ${emp.last_name}</td>
          <td>${emp.position}</td>
          <td><span class="badge bg-light text-dark border">${emp.department || '—'}</span></td>
          <td><small class="text-muted">${emp.employment_type}</small></td>
          <td><small>${emp.location}</small></td>
          <td>${getStatusBadge(emp.status)}</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-sm btn-outline-primary me-1" onclick="viewEmployee('${emp.employee_number}')">View</button>
              <button class="btn btn-sm btn-outline-info" onclick="editEmployee('${emp.employee_number}')">Edit</button>
            </div>
          </td>
        </tr>
      `;
    });
  }

  function renderPagination(totalPages, totalItems) {
    let container = document.querySelector('.table-responsive');
    let pagination = document.getElementById('paginationControls');

    if (!pagination) {
      pagination = document.createElement('div');
      pagination.id = 'paginationControls';
      pagination.className = 'd-flex justify-content-between align-items-center mt-4';
      container.after(pagination);
    }

    pagination.innerHTML = `
      <div class="text-muted small">
        Showing ${(currentPage - 1) * itemsPerPage + 1} to ${Math.min(currentPage * itemsPerPage, totalItems)} of ${totalItems} employees
      </div>
      <nav>
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
          </li>
          ${Array.from({length: totalPages}, (_, i) => `
            <li class="page-item ${i + 1 === currentPage ? 'active' : ''}">
              <a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a>
            </li>
          `).join('')}
          <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
          </li>
        </ul>
      </nav>
    `;

    pagination.querySelectorAll('.page-link').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const page = parseInt(link.dataset.page);
        if (page >= 1 && page <= totalPages && page !== currentPage) {
          currentPage = page;
          filterAndPaginate();
        }
      });
    });
  }

  async function loadEmployees() {
    try {
      const res = await fetch('./backend/employees.php');
      const json = await res.json();

      if (json.success) {
        allEmployees = json.data;
        console.log('Loaded employees:', allEmployees); // DEBUG - Check the data
        currentPage = 1;
        filterAndPaginate();
      }
    } catch (err) {
      console.error('Failed to load employees:', err);
    }
  }

async function loadDropdowns() {
    try {
        const [deptRes, mgrRes] = await Promise.all([
            fetch('./backend/department.php'),
            fetch('./backend/managers.php')
        ]);

        const departments = await deptRes.json();
        const managers = await mgrRes.json();
        
        console.log('Loaded managers:', managers); // DEBUG

      const deptFilter = document.getElementById('departmentFilter');
      const deptSelect = document.getElementById('empDept');
      const managerSelect = document.getElementById('empManager');

      if (deptFilter) {
        deptFilter.innerHTML = '<option value="">All Departments</option>';
        departments.forEach(d => deptFilter.add(new Option(d, d)));
      }
      if (deptSelect) {
        deptSelect.innerHTML = '';
        departments.forEach(d => deptSelect.add(new Option(d, d)));
      }

      if (managerSelect) {
          managerSelect.innerHTML = '<option value="">None (Top Level)</option>';
          managers.forEach(m => {
              // Use 'id' from your managers.php (which contains employee_number)
              managerSelect.add(new Option(m.full_name, m.id));
          });
      }
    } catch (err) {
        console.error('Dropdown load failed:', err);
    }
}

  // Event listeners
  document.getElementById('departmentFilter')?.addEventListener('change', () => {
    currentPage = 1;
    filterAndPaginate();
  });
  document.getElementById('searchEmployee')?.addEventListener('keyup', () => {
    currentPage = 1;
    filterAndPaginate();
  });

  document.getElementById('addEmployeeBtn')?.addEventListener('click', () => {
    document.getElementById('employeeForm').reset();
    document.getElementById('employeeFormTitle').textContent = "Add New Employee";
    document.getElementById('empId').value = "";
    
    // Set today's date as default for date_hired
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('empHiredDate').value = today;
    
    new bootstrap.Modal('#employeeFormModal').show();
  });

  document.getElementById('exportBtn')?.addEventListener('click', () => {
    const departmentFilter = document.getElementById('departmentFilter')?.value || '';
    const searchTerm = (document.getElementById('searchEmployee')?.value || '').toLowerCase();
    let employeesToExport = allEmployees;

    if (departmentFilter !== "") {
      employeesToExport = employeesToExport.filter(emp => emp.department === departmentFilter);
    }

    if (searchTerm.length > 0) {
      employeesToExport = employeesToExport.filter(emp => {
        const fullName = `${emp.first_name} ${emp.last_name}`.toLowerCase();
        const position = (emp.position || '').toLowerCase();
        const email = (emp.email || '').toLowerCase();
        return fullName.includes(searchTerm) || position.includes(searchTerm) || email.includes(searchTerm);
      });
    }

    exportToCsv('employee_roster.csv', employeesToExport);
  });

  // Initial load
  loadDropdowns();
  loadEmployees();
});

// Birthdays Section
let allBirthdays = [];

function loadBirthdays() {
  $.getJSON('./backend/get_birthdays.php', function(response) {
    const tbody = $('#birthdaysBody');
    tbody.empty();

    if (!response.success || response.birthdays.length === 0) {
      tbody.html('<tr><td colspan="4" class="text-center text-muted py-4">No birthday data available</td></tr>');
      return;
    }

    allBirthdays = response.birthdays;
    renderBirthdays('all');
  }).fail(function() {
    $('#birthdaysBody').html('<tr><td colspan="4" class="text-center text-danger py-4">Failed to load birthdays</td></tr>');
  });
}

function renderBirthdays(mode) {
  const tbody = $('#birthdaysBody');
  tbody.empty();

  let list = [...allBirthdays];

  if (mode === 'upcoming') {
    const today = '12-20';

    list = list.filter(b => b.birth_month_day >= today || b.birth_month_day <= '01-20');

    list.sort((a, b) => {
      if (a.birth_month_day >= today && b.birth_month_day < today) return -1;
      if (b.birth_month_day >= today && a.birth_month_day < today) return 1;
      return a.birth_month_day.localeCompare(b.birth_month_day);
    });
  }

  if (list.length === 0) {
    tbody.html('<tr><td colspan="4" class="text-center text-muted py-4">No upcoming birthdays in the next 30 days</td></tr>');
    return;
  }

  list.forEach(emp => {
    const yearText = emp.next_year === 2026 ? '2026' : '2025';
    tbody.append(`
      <tr>
        <td>
          <div class="d-flex align-items-center">
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:35px;height:35px;font-size:0.9rem;">
              ${emp.initials}
            </div>
            <div class="fw-semibold">${emp.full_name}</div>
          </div>
        </td>
        <td>${emp.department || 'N/A'}</td>
        <td>${emp.birthday_display}</td>
        <td><strong>${emp.age_next}</strong> in ${yearText}</td>
      </tr>
    `);
  });
}

$(document).on('click', '#showAllBirthdays', function() {
  $(this).addClass('active').siblings().removeClass('active');
  renderBirthdays('all');
});

$(document).on('click', '#showUpcomingBirthdays', function() {
  $(this).addClass('active').siblings().removeClass('active');
  renderBirthdays('upcoming');
});

$(document).ready(function() {
  loadBirthdays();
});
</script>