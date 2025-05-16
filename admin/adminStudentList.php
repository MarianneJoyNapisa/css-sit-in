<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Student List</title>
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h5 {
            margin: 0;
        }
        .btn-container {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 0.5rem 1rem;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .pagination {
            margin-top: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    
    <!-- Main Container -->
    <main>
        <div class="container">
            
            <div class="row justify-content-center g-4">
                <div class="col-md-12">

                    <div class="btn-container mb-3">
                        <button id="addStudentBtn" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Add Student
                        </button>
                        <button id="resetSessionsBtn" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset All Sessions
                        </button>
                    </div>

                    <div class="card shadow">
                        <!-- Card Header with Buttons and Search -->
                        <div class="card-header bg-primary text-white border-0">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h5 class="mb-0 w-100">Student Information</h5>
                                <input type="text" id="searchInput" class="form-control form-control-sm ms-2" placeholder="Search...">
                            </div>
                        </div>
                        
                        <!-- Card Body with Table and Pagination -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Year Level</th>
                                            <th>Course</th>
                                            <th>Remaining Sessions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentList"></tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination Info and Navigation -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div id="paginationInfo"></div>
                                <nav>
                                    <ul id="pagination" class="pagination pagination-sm"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../js/sideNav.js"></script>
    <script>
        // Function to handle adding a student
        document.getElementById('addStudentBtn').addEventListener('click', function() {
            window.location.href = 'adminAddStudent.php'; // Redirect to Add Student page
        });

        // Function to handle resetting all sessions
        document.getElementById('resetSessionsBtn').addEventListener('click', function() {
            if (confirm("Are you sure you want to reset all sessions?")) {
                fetch('../db/reset_sessions.php')
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        fetchStudents(); // Reload the student list after resetting sessions
                    })
                    .catch(error => {
                        console.error('Error resetting sessions:', error);
                    });
            }
        });

        // Function to reset sessions for a single student
        function resetStudentSessions(studentId) {
            if (confirm(`Are you sure you want to reset sessions for student ID ${studentId}?`)) {
                fetch('../db/reset_sessions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `student_id=${studentId}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        fetchStudents(); // Reload the student list after resetting sessions
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error resetting student sessions:', error);
                    alert('Error resetting sessions: ' + error.message);
                });
            }
        }

        // Function to handle edit button click
        function handleEditClick(studentId) {
            window.location.href = `adminEditStudent.php?id=${studentId}`;
        }

        // Function to handle delete button click
        function handleDeleteClick(studentId) {
            if (confirm(`Are you sure you want to delete student ID ${studentId}?`)) {
                fetch('../db/delete_student.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `student_id=${studentId}`
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    fetchStudents(); // Reload the student list after deletion
                })
                .catch(error => {
                    console.error('Error deleting student:', error);
                });
            }
        }

        // Fetch data from PHP API
        function fetchStudents(page = 1) {
            fetch(`../db/fetch_students.php?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    const students = data.students;
                    const totalStudents = data.totalStudents;
                    const perPage = data.perPage;
                    const totalPages = data.totalPages;

                    // Display students in the table
                    const studentList = document.getElementById('studentList');
                    studentList.innerHTML = '';
                    students.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = ` 
                            <td>${student.idno}</td>
                            <td>${student.full_name}</td>
                            <td>${student.yearlvl}</td>
                            <td>${student.course}</td>
                            <td>${student.remaining_sessions}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary" onclick="handleEditClick('${student.idno}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="handleDeleteClick('${student.idno}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="resetStudentSessions('${student.idno}')">
                                        <i class="bi bi-arrow-clockwise"></i> Reset
                                    </button>
                                </div>
                            </td>
                        `;
                        studentList.appendChild(row);
                    });

                    // Display pagination
                    const pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';
                    for (let i = 1; i <= totalPages; i++) {
                        const pageItem = document.createElement('li');
                        pageItem.classList.add('page-item');
                        pageItem.classList.toggle('active', i === page);
                        pageItem.innerHTML = `<a class="page-link" href="#" onclick="fetchStudents(${i})">${i}</a>`;
                        pagination.appendChild(pageItem);
                    }

                    // Display pagination info
                    const paginationInfo = document.getElementById('paginationInfo');
                    paginationInfo.textContent = `Showing ${(page - 1) * perPage + 1} to ${Math.min(page * perPage, totalStudents)} of ${totalStudents} entries`;
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                });
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#studentList tr');
            
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Initial fetch
        fetchStudents();
    </script>
</body>
</html>