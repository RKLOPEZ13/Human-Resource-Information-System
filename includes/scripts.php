    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

    <!-- Dashboard dynamic table script -->
    <script>
      const tableData = {
        birthdays: {
          columns: ['#', 'Name', 'Department', 'Birthday'],
          rows: [
            [1, 'John Doe', 'IT', 'Nov 25'],
            [2, 'Jane Smith', 'HR', 'Nov 28'],
            [3, 'Mark Lee', 'Finance', 'Dec 2']
          ]
        },
        leave: {
          columns: ['#', 'Name', 'Leave Type', 'Start', 'End', 'Status'],
          rows: [
            [1, 'John Doe', 'Sick Leave', 'Nov 26', 'Nov 28', 'Pending'],
            [2, 'Jane Smith', 'Vacation', 'Dec 1', 'Dec 5', 'Pending']
          ]
        },
        positions: {
          columns: ['#', 'Job Title', 'Department', 'Applicants', 'Status'],
          rows: [
            [1, 'Software Engineer', 'IT', 5, 'Open'],
            [2, 'HR Assistant', 'HR', 3, 'Open']
          ]
        }
      };

      const tableTitle = document.getElementById('table_title');
      const tableHead = document.getElementById('tableHead');
      const tableBody = document.getElementById('tableBody');
      const tableSelector = document.getElementById('tableSelector');

      function renderTable(type) {
        const data = tableData[type];

        // Render columns
        tableHead.innerHTML = '<tr>' + data.columns.map(col => `<th scope="col">${col}</th>`).join('') + '</tr>';

        // Render rows
        tableBody.innerHTML = data.rows.map(row => 
          `<tr>` + row.map(cell => `<td>${cell}</td>`).join('') + `</tr>`).join('');
      }

      // Default render
      renderTable('birthdays');

      // Change table on dropdown
      tableSelector.addEventListener('change', (e) => {
        renderTable(e.target.value);
        tableTitle.innerText = tableSelector.options[tableSelector.selectedIndex].text;
      });
    </script>

  </body>
</html>