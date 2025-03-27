<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Lab Reports</title>
    <style>
        /* Styling for the Export buttons */
        .export-buttons {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        /* Styling for search bar */
        .search-bar {
            margin-bottom: 20px;
        }

        /* Styling for filter and sort dropdowns */
        .filter-sort-buttons {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary">Lab Report</h5>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by ID, Name, Purpose, Lab..." onkeyup="searchTable()">
            </div>

            <!-- Export buttons -->
            <div class="export-buttons">
                <button class="btn btn-success" onclick="exportToPDF()">Export to PDF</button>
                <button class="btn btn-primary" onclick="exportToCSV()">Export to CSV</button>
                <button class="btn btn-warning" onclick="exportToExcel()">Export to Excel</button>
                <button class="btn btn-info" onclick="printTable()">Print</button>
            </div>

            <!-- Filter and Sort dropdown buttons -->
            <div class="filter-sort-buttons">
                <!-- Sort Dropdown -->
                <div class="dropdown d-inline-block">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="#" onclick="sortByDate()">Sort by Date</a></li>
                        <li><a class="dropdown-item" href="#" onclick="sortByLoginTime()">Sort by Login Time</a></li>
                    </ul>
                </div>

                <!-- Filter Dropdown -->
                <div class="dropdown d-inline-block ms-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item" href="#" onclick="filterByLab()">Filter by Lab</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByPurpose()">Filter by Purpose</a></li>
                    </ul>
                </div>
            </div>

            <!-- Lab Report Table -->
            <table class="table table-striped" id="labReportTable">
                <thead>
                    <tr>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Laboratory</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Date</th>  <!-- Added Date column -->
                    </tr>
                </thead>
                <tbody id="labReportTableBody">
                    <!-- Data will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for pie charts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>

    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- jsPDF autoTable Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.22/jspdf.plugin.autotable.min.js"></script>

    <script>
        let labReportData = [];  // Store the report data to be used for searching, sorting, and filtering

        function loadLabReportData() {
            fetch("../db/fetchsitin_report.php")  // This PHP script will fetch the report data from the database
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    labReportData = data.data;  // Store the fetched data globally
                    displayLabReportData(labReportData);  // Display the data
                } else {
                    console.error("Error loading lab report data:", data.message);
                }
            })
            .catch(error => console.error("Error fetching lab report data:", error));
        }

        function displayLabReportData(data) {
            let tableBody = document.getElementById("labReportTableBody");
            tableBody.innerHTML = ""; // Clear existing rows

            data.forEach(log => {
                // Extracting time from login and logout timestamps
                let loginTime = new Date(log.time_in).toLocaleTimeString(); // Extract the time part of time_in
                let logoutTime = new Date(log.time_out).toLocaleTimeString(); // Extract the time part of time_out

                // Extract date from the logout timestamp
                let logoutDate = new Date(log.time_out);
                let dateOnly = logoutDate.toLocaleDateString(); // Format the date (MM/DD/YYYY)

                let row = `<tr>
                    <td>${log.id_number}</td>
                    <td>${log.name}</td>
                    <td>${log.purpose}</td>
                    <td>${log.lab}</td>
                    <td>${loginTime}</td>  <!-- Displaying only the time -->
                    <td>${logoutTime}</td> <!-- Displaying only the time -->
                    <td>${dateOnly}</td> <!-- Displayed extracted date -->
                </tr>`;
                tableBody.innerHTML += row;
            });
        }


        // Search Function
        function searchTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            let filteredData = labReportData.filter(log => 
                log.id_number.toLowerCase().includes(filter) || 
                log.name.toLowerCase().includes(filter) || 
                log.purpose.toLowerCase().includes(filter) ||
                log.lab.toLowerCase().includes(filter)
            );
            displayLabReportData(filteredData);  // Display the filtered data
        }

        // Filter by Lab
        function filterByLab() {
            let lab = prompt("Enter Lab to filter by:");
            if (lab) {
                let filteredData = labReportData.filter(log => log.lab.toLowerCase().includes(lab.toLowerCase()));
                displayLabReportData(filteredData);
            }
        }

        // Filter by Purpose
        function filterByPurpose() {
            let purpose = prompt("Enter Purpose to filter by:");
            if (purpose) {
                let filteredData = labReportData.filter(log => log.purpose.toLowerCase().includes(purpose.toLowerCase()));
                displayLabReportData(filteredData);
            }
        }

        // Sort by Date
        function sortByDate() {
            let sortedData = [...labReportData].sort((a, b) => new Date(a.time_out) - new Date(b.time_out));
            displayLabReportData(sortedData);
        }

        // Sort by Login Time
        function sortByLoginTime() {
            let sortedData = [...labReportData].sort((a, b) => new Date(a.time_in) - new Date(b.time_in));
            displayLabReportData(sortedData);
        }

        function exportToPDF() {
            // Check if jsPDF and autoTable are loaded
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Check if the autoTable plugin is available (it should be if the jsPDF library and autoTable are correctly loaded)
            if (doc.autoTable) {
                doc.autoTable({ html: '#labReportTable' });
                doc.save('lab-report.pdf');
            } else {
                console.error('autoTable plugin is not available. Make sure the autoTable script is included.');
            }
        }

        // Export to CSV
        function exportToCSV() {
            const table = document.getElementById("labReportTable");
            let csv = [];
            let rows = table.querySelectorAll("tr");

            rows.forEach(row => {
                let cols = row.querySelectorAll("td, th");
                let rowData = [];
                cols.forEach(col => rowData.push(col.innerText));
                csv.push(rowData.join(","));
            });

            const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            const link = document.createElement("a");
            link.setAttribute("href", encodeURI(csvContent));
            link.setAttribute("download", "lab-report.csv");
            document.body.appendChild(link);
            link.click();
        }

        // Export to Excel
        function exportToExcel() {
            const table = document.getElementById("labReportTable");
            const wb = XLSX.utils.table_to_book(table, { sheet: "Lab Report" });
            XLSX.writeFile(wb, "lab-report.xlsx");
        }

        // Print table
        function printTable() {
            const printWindow = window.open('', '', 'height=500, width=800');
            printWindow.document.write('<html><head><title>Lab Report</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(document.getElementById('labReportTable').outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // Initialize the report data when the page loads
        window.onload = loadLabReportData;
    </script>
</body>
</html>
