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

<script>
let allEmployees = [];

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
          <div class="display-1 text-secondary mb-3"></div>
          <h4>${emp.first_name} ${emp.last_name}</h4>
          <p class="text-muted mb-2">${emp.position}</p>
          ${getStatusBadge(emp.status)}
        </div>
        <div class="mt-3 p-3 border rounded text-start">
          <p class="mb-1 small fw-bold text-primary">Compensation Details</p>
          <div class="d-flex justify-content-between small">
            <span class="fw-bold">Base Salary:</span> 
            <span>${formatSalary(emp.base_salary)}</span>
          </div>
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

  document.getElementById('employeeFormTitle').textContent = "Edit Employee";
  document.getElementById('empId').value = emp.employee_number;
  document.getElementById('firstName').value = emp.first_name;
  document.getElementById('lastName').value = emp.last_name;
  document.getElementById('empEmail').value = emp.email;
  document.getElementById('empPhone').value = emp.phone || '';
  document.getElementById('empAge').value = emp.age || '';
  document.getElementById('empDept').value = emp.department;
  document.getElementById('empPosition').value = emp.position;

  const mgrName = (emp.manager_name === '—' || !emp.manager_name) ? '' : emp.manager_name;
  document.getElementById('empManager').value = mgrName;

  document.getElementById('empType').value = emp.employment_type;
  document.getElementById('empLocation').value = emp.location;
  document.getElementById('empStatus').value = emp.status;
  document.getElementById('empEmergency').value = emp.emergency_contact || '';
  document.getElementById('empAddress').value = emp.address || '';
  document.getElementById('empHiredDate').value = emp.date_hired;
  document.getElementById('empSalary').value = emp.base_salary || '';
  document.getElementById('empTerminatedDate').value = emp.date_terminated || '';

  new bootstrap.Modal('#employeeFormModal').show();
}

document.addEventListener("DOMContentLoaded", () => {

  function filterAndSearchEmployees() {
    const departmentFilter = document.getElementById('departmentFilter')?.value || '';
    const searchTerm = (document.getElementById('searchEmployee')?.value || '').toLowerCase();
    let filteredEmployees = allEmployees;

    if (departmentFilter !== "") {
      filteredEmployees = filteredEmployees.filter(emp => emp.department === departmentFilter);
    }

    if (searchTerm.length > 0) {
      filteredEmployees = filteredEmployees.filter(emp => {
        const fullName = `${emp.first_name} ${emp.last_name}`.toLowerCase();
        const position = (emp.position || '').toLowerCase();
        const email = (emp.email || '').toLowerCase();
        return fullName.includes(searchTerm) || position.includes(searchTerm) || email.includes(searchTerm);
      });
    }

    renderTable(filteredEmployees);
  }

  async function loadEmployees() {
    try {
      const res = await fetch('./backend/employees.php');
      const json = await res.json();

      if (json.success) {
        allEmployees = json.data;
        filterAndSearchEmployees();
      }
    } catch (err) {
      console.error('Failed to load employees:', err);
    }
  }

  function renderTable(employees) {
    const tbody = document.getElementById('employeeTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (employees.length === 0) {
      tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">No employees found</td></tr>';
      return;
    }

    employees.forEach(emp => {
      tbody.innerHTML += `
        <tr>
          <td><strong>${emp.employee_number}</strong></td>
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

  async function loadDropdowns() {
    try {
      const [deptRes, mgrRes] = await Promise.all([
        fetch('./backend/department.php'),
        fetch('./backend/managers.php')
      ]);

      const departments = await deptRes.json();
      const managers = await mgrRes.json();

      const deptFilter = document.getElementById('departmentFilter');
      const deptSelect = document.getElementById('empDept');
      const managerSelect = document.getElementById('empManager');

      if (deptFilter) deptFilter.innerHTML = '<option value="">All Departments</option>';
      if (deptSelect) deptSelect.innerHTML = '';

      departments.forEach(d => {
        if (deptFilter) deptFilter.add(new Option(d, d));
        if (deptSelect) deptSelect.add(new Option(d, d));
      });

      if (managerSelect) {
        managerSelect.innerHTML = '<option value="">None (Top Level)</option>';
        managers.forEach(m => managerSelect.add(new Option(m.full_name, m.id)));
      }
    } catch (err) {
      console.error('Dropdown load failed:', err);
    }
  }

  loadDropdowns();
  loadEmployees();
  setInterval(loadEmployees, 5000);

  document.getElementById('departmentFilter')?.addEventListener('change', filterAndSearchEmployees);
  document.getElementById('searchEmployee')?.addEventListener('keyup', filterAndSearchEmployees);

  const addBtn = document.getElementById('addEmployeeBtn');
  if (addBtn) {
    addBtn.onclick = () => {
      document.getElementById('employeeForm').reset();
      document.getElementById('employeeFormTitle').textContent = "Add New Employee";
      document.getElementById('empId').value = "";
      new bootstrap.Modal('#employeeFormModal').show();
    };
  }

  const saveBtn = document.getElementById('saveEmployeeBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', async () => {
      const formData = new FormData();
      const isEdit = document.getElementById('empId').value;

      formData.append('emp_number', isEdit || '');
      formData.append('first_name', document.getElementById('firstName').value);
      formData.append('last_name', document.getElementById('lastName').value);
      formData.append('email', document.getElementById('empEmail').value);
      formData.append('phone', document.getElementById('empPhone').value);
      formData.append('age', document.getElementById('empAge').value);
      formData.append('department', document.getElementById('empDept').value);
      formData.append('position', document.getElementById('empPosition').value);
      formData.append('manager_id', document.getElementById('empManager').value || '');
      formData.append('employment_type', document.getElementById('empType').value);
      formData.append('location', document.getElementById('empLocation').value);
      formData.append('status', document.getElementById('empStatus').value);
      formData.append('emergency_contact', document.getElementById('empEmergency').value);
      formData.append('address', document.getElementById('empAddress').value);
      formData.append('date_hired', document.getElementById('empHiredDate').value);
      formData.append('base_salary', document.getElementById('empSalary').value);
      formData.append('date_terminated', document.getElementById('empTerminatedDate').value);

      try {
        const res = await fetch('./backend/save_employee.php', {
          method: 'POST',
          body: formData
        });

        const result = await res.json();

        if (result.success) {
          alert(result.message);
          bootstrap.Modal.getInstance('#employeeFormModal').hide();
          loadEmployees();
        } else {
          alert('Error: ' + result.message);
        }
      } catch (err) {
        alert('Save failed. Check console.');
        console.error(err);
      }
    });
  }
});

function exportToCsv(filename, employees) {
  if (employees.length === 0) {
    alert("No data to export!");
    return;
  }

  const headers = [
    "Employee ID", "First Name", "Last Name", "Email", "Phone",
    "Age", "Base Salary",
    "Department", "Position", "Manager Name",
    "Employment Type", "Location", "Status",
    "Date Hired", "Date Terminated",
    "Emergency Contact", "Address"
  ];

  let csvContent = headers.join(',') + '\n';

  employees.forEach(emp => {
    const row = [
      `"${emp.employee_number}"`,
      `"${emp.first_name}"`,
      `"${emp.last_name}"`,
      `"${emp.email}"`,
      `"${emp.phone || ''}"`,
      `"${emp.age || ''}"`,
      `"${emp.base_salary || ''}"`,
      `"${emp.department || ''}"`,
      `"${emp.position}"`,
      `"${emp.manager_name || ''}"`,
      `"${emp.employment_type}"`,
      `"${emp.location}"`,
      `"${emp.status}"`,
      `"${emp.date_hired}"`,
      `"${emp.date_terminated || ''}"`,
      `"${emp.emergency_contact || ''}"`,
      `"${(emp.address || '').replace(/"/g, '""')}"`
    ].join(',');
    csvContent += row + '\n';
  });

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');

  link.setAttribute('href', url);
  link.setAttribute('download', filename);
  link.style.visibility = 'hidden';

  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

const exportBtn = document.getElementById('exportBtn');
if (exportBtn) {
  exportBtn.addEventListener('click', () => {
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
}
</script>
