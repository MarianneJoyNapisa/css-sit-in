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
        .export-buttons {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .filter-sort-buttons {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title {
            font-weight: bold;
            font-size: 16px;
        }
        .report-subtitle {
            font-size: 14px;
        }
        .report-system {
            font-size: 12px;
            margin-top: 5px;
        }
        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
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
                        <li><a class="dropdown-item" href="#" onclick="sortByDate('desc')">Newest First</a></li>
                        <li><a class="dropdown-item" href="#" onclick="sortByDate('asc')">Oldest First</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="sortByLoginTime('desc')">Latest Login First</a></li>
                        <li><a class="dropdown-item" href="#" onclick="sortByLoginTime('asc')">Earliest Login First</a></li>
                    </ul>
                </div>

                <!-- Filter Dropdown -->
                <div class="dropdown d-inline-block ms-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item" href="#" onclick="filterByLab()">Lab</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByPurpose()">Purpose</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus()">Status</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="resetFilters()">Reset Filters</a></li>
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
                        <th>Status</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="labReportTableBody">
                    <!-- Data will be dynamically inserted here -->
                </tbody>
            </table>
            
            <!-- Hidden div for export header -->
            <div id="exportHeader" style="display: none;">
                <div class="report-header">
                    <div class="report-title">University of Cebu-Main</div>
                    <div class="report-subtitle">College of Computer Studies</div>
                    <div class="report-system">Computer Laboratory Sit-In Monitoring System Report</div>
                    <div class="report-date">Generated on: <span id="exportDate"></span></div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.22/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        let labReportData = [];
        let filteredData = [];

        function loadLabReportData() {
            fetch("../db/fetchsitin_report.php")
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    labReportData = data.data;
                    // Default sort: newest first
                    sortByDate('desc');
                } else {
                    console.error("Error loading lab report data:", data.message);
                    document.getElementById("labReportTableBody").innerHTML = 
                        `<tr><td colspan="8" class="text-center text-danger">Error loading data: ${data.message}</td></tr>`;
                }
            })
            .catch(error => {
                console.error("Error fetching lab report data:", error);
                document.getElementById("labReportTableBody").innerHTML = 
                    `<tr><td colspan="8" class="text-center text-danger">Failed to load data. Please try again.</td></tr>`;
            });
        }

        function displayLabReportData(data) {
            let tableBody = document.getElementById("labReportTableBody");
            tableBody.innerHTML = "";

            if (data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="8" class="text-center">No records found</td></tr>`;
                return;
            }

            data.forEach(log => {
                const loginTime = formatDateTime(log.time_in);
                const logoutTime = log.time_out ? formatDateTime(log.time_out) : 'N/A';
                const dateOnly = formatDate(log.time_out || log.time_in);
                
                let row = `<tr>
                    <td>${log.id_number}</td>
                    <td>${log.name}</td>
                    <td>${log.purpose}</td>
                    <td>${log.lab}</td>
                    <td><span class="badge ${getStatusBadgeClass(log.status)}">${log.status}</span></td>
                    <td>${loginTime.time}</td>
                    <td>${logoutTime.time}</td>
                    <td>${dateOnly}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        }

        function formatDateTime(timestamp) {
            if (!timestamp) return { date: 'N/A', time: 'N/A' };
            const date = new Date(timestamp);
            return {
                date: date.toLocaleDateString(),
                time: date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            };
        }

        function formatDate(timestamp) {
            if (!timestamp) return 'N/A';
            return new Date(timestamp).toLocaleDateString();
        }

        function getStatusBadgeClass(status) {
            switch(status) {
                case 'Active': return 'bg-success';
                case 'Reserved': return 'bg-info';
                case 'Timed Out': return 'bg-secondary';
                default: return 'bg-warning';
            }
        }

        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            filteredData = labReportData.filter(log => 
                log.id_number.toLowerCase().includes(filter) || 
                log.name.toLowerCase().includes(filter) || 
                log.purpose.toLowerCase().includes(filter) ||
                log.lab.toLowerCase().includes(filter) ||
                (log.status && log.status.toLowerCase().includes(filter))
            );
            displayLabReportData(filteredData);
        }

        function filterByLab() {
            const lab = prompt("Enter Lab to filter by (e.g., Lab1, Lab2):");
            if (lab) {
                filteredData = labReportData.filter(log => 
                    log.lab.toLowerCase().includes(lab.toLowerCase())
                );
                displayLabReportData(filteredData);
            }
        }

        function filterByPurpose() {
            const purpose = prompt("Enter Purpose to filter by:");
            if (purpose) {
                filteredData = labReportData.filter(log => 
                    log.purpose.toLowerCase().includes(purpose.toLowerCase())
                );
                displayLabReportData(filteredData);
            }
        }

        function filterByStatus() {
            const status = prompt("Enter Status to filter by (Active/Reserved/Timed Out):");
            if (status) {
                filteredData = labReportData.filter(log => 
                    log.status && log.status.toLowerCase().includes(status.toLowerCase())
                );
                displayLabReportData(filteredData);
            }
        }

        function resetFilters() {
            filteredData = [...labReportData];
            document.getElementById("searchInput").value = "";
            displayLabReportData(filteredData);
        }

        function sortByDate(order = 'desc') {
            filteredData = [...(filteredData.length ? filteredData : labReportData)];
            filteredData.sort((a, b) => {
                const dateA = new Date(a.time_out || a.time_in);
                const dateB = new Date(b.time_out || b.time_in);
                return order === 'desc' ? dateB - dateA : dateA - dateB;
            });
            displayLabReportData(filteredData);
        }

        function sortByLoginTime(order = 'desc') {
            filteredData = [...(filteredData.length ? filteredData : labReportData)];
            filteredData.sort((a, b) => {
                const dateA = new Date(a.time_in);
                const dateB = new Date(b.time_in);
                return order === 'desc' ? dateB - dateA : dateA - dateB;
            });
            displayLabReportData(filteredData);
        }

        function getCurrentDateTime() {
            return new Date().toLocaleString();
        }

        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Add report header
            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.text("University of Cebu-Main", 105, 15, { align: 'center' });
            doc.text("College of Computer Studies", 105, 22, { align: 'center' });
            doc.setFontSize(12);
            doc.text("Computer Laboratory Sit-In Monitoring System Report", 105, 29, { align: 'center' });
            doc.setFontSize(10);
            doc.text("Generated on: " + getCurrentDateTime(), 105, 36, { align: 'center' });

            // Add table
            doc.autoTable({
                html: '#labReportTable',
                startY: 45,
                theme: 'grid',
                headStyles: {
                    fillColor: [41, 128, 185],
                    textColor: 255
                },
                columnStyles: {
                    4: { cellWidth: 20 }, // Status column
                    5: { cellWidth: 25 }, // Login time
                    6: { cellWidth: 25 }, // Logout time
                    7: { cellWidth: 25 }  // Date
                },
                styles: {
                    fontSize: 8,
                    cellPadding: 2
                }
            });
            
            doc.save('lab-report_' + new Date().toISOString().slice(0,10) + '.pdf');
        }

        function exportToCSV() {
            const rows = [];
            const headers = [];
            
            // Add header information
            rows.push("University of Cebu-Main");
            rows.push("College of Computer Studies");
            rows.push("Computer Laboratory Sit-In Monitoring System Report");
            rows.push("Generated on: " + getCurrentDateTime());
            rows.push("");
            
            // Add table headers
            document.querySelectorAll("#labReportTable thead th").forEach(th => {
                headers.push(th.innerText);
            });
            rows.push(headers.join(","));
            
            // Add table data
            document.querySelectorAll("#labReportTable tbody tr").forEach(tr => {
                const row = [];
                tr.querySelectorAll("td").forEach(td => {
                    // Remove badge HTML if present
                    const badge = td.querySelector(".badge");
                    row.push(badge ? badge.innerText : td.innerText);
                });
                rows.push(row.join(","));
            });

            const csvContent = "data:text/csv;charset=utf-8," + rows.join("\n");
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "lab-report_" + new Date().toISOString().slice(0,10) + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function exportToExcel() {
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(document.getElementById("labReportTable"));
            
            // Add header information
            XLSX.utils.sheet_add_aoa(ws, [
                ["University of Cebu-Main"],
                ["College of Computer Studies"],
                ["Computer Laboratory Sit-In Monitoring System Report"],
                ["Generated on: " + getCurrentDateTime()],
                []
            ], { origin: "A1" });
            
            // Format columns
            if (!ws['!cols']) ws['!cols'] = [];
            ws['!cols'][4] = { width: 10 }; // Status column
            ws['!cols'][5] = { width: 15 }; // Login time
            ws['!cols'][6] = { width: 15 }; // Logout time
            ws['!cols'][7] = { width: 15 }; // Date
            
            XLSX.utils.book_append_sheet(wb, ws, "Lab Report");
            XLSX.writeFile(wb, "lab-report_" + new Date().toISOString().slice(0,10) + ".xlsx");
        }

        function printTable() {
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Lab Report</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-size: 12px; }');
            printWindow.document.write('.table { width: 100%; margin-bottom: 1rem; }');
            printWindow.document.write('.badge { font-size: 0.75em; padding: 0.25em 0.4em; }');
            printWindow.document.write('@media print { .no-print { display: none; } }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            
            // Add report header
            printWindow.document.write('<div class="report-header">');
            printWindow.document.write('<div class="report-title">University of Cebu-Main</div>');
            printWindow.document.write('<div class="report-subtitle">College of Computer Studies</div>');
            printWindow.document.write('<div class="report-system">Computer Laboratory Sit-In Monitoring System Report</div>');
            printWindow.document.write('<div>Generated on: ' + getCurrentDateTime() + '</div>');
            printWindow.document.write('</div>');
            
            // Add the table
            printWindow.document.write(document.getElementById('labReportTable').outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 200);
        }

        // Initialize when page loads
        document.addEventListener("DOMContentLoaded", loadLabReportData);
    </script>
</body>
</html>