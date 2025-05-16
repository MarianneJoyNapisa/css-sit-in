<?php
session_start();

// Verify admin access
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['username'] !== 'admin') {
    header("Location: ../user/userDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary mb-4">User Feedback</h5>
            
            <!-- Search Filter -->
            <div class="mb-3">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search feedback...">
                    <button class="btn btn-outline-secondary" type="button" onclick="loadFeedback()">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Lab</th>
                            <th>Purpose</th>
                            <th>Date Submitted</th>
                            <th>Rating</th>
                            <th>Comments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="feedbackTableBody">
                        <tr>
                            <td colspan="8" class="text-center">Loading feedback data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this feedback?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    
    <script>
        // Initialize modals
        const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        let feedbackIdToDelete = null;
        let allFeedbackData = [];

        // Rating mapping to match your user interface
        const ratingTexts = {
            5: 'Excellent',
            4: 'Good',
            3: 'Average',
            2: 'Poor',
            1: 'Very Poor'
        };

        function loadFeedback() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const tableBody = document.getElementById('feedbackTableBody');
            
            // Show loading state
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Loading feedback data...</td></tr>';
            
            fetch('../db/fetch_feedback.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error("Response is not JSON");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        allFeedbackData = data.data;
                        renderFeedbackTable(searchTerm);
                    } else {
                        showError(data.message || 'Failed to load feedback');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showError('Failed to load feedback. Please try again.');
                });
        }

        function renderFeedbackTable(searchTerm = '') {
            const tableBody = document.getElementById('feedbackTableBody');
            
            if (allFeedbackData.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">No feedback found</td></tr>';
                return;
            }

            const filteredData = searchTerm 
                ? allFeedbackData.filter(feedback => 
                    feedback.name.toLowerCase().includes(searchTerm) ||
                    feedback.lab.toLowerCase().includes(searchTerm) ||
                    feedback.purpose.toLowerCase().includes(searchTerm) ||
                    feedback.comments.toLowerCase().includes(searchTerm))
                : allFeedbackData;

            tableBody.innerHTML = filteredData.map(feedback => `
                <tr>
                    <td>${feedback.user_id}</td>
                    <td>${feedback.name}</td>
                    <td>${feedback.lab || 'N/A'}</td>
                    <td>${feedback.purpose || 'N/A'}</td>
                    <td>${formatDate(feedback.created_at)}</td>
                    <td>${ratingTexts[feedback.rating] || 'N/A'}</td>
                    <td>${feedback.comments || 'No comments'}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteFeedback(${feedback.id})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }

        function deleteFeedback(id) {
            feedbackIdToDelete = id;
            confirmDeleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!feedbackIdToDelete) return;
            
            fetch('../db/delete_feedback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${feedbackIdToDelete}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    loadFeedback();
                }
                alert(data.message || (data.status === 'success' ? 'Feedback deleted' : 'Error deleting feedback'));
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete feedback. Please try again.');
            })
            .finally(() => {
                confirmDeleteModal.hide();
            });
        });

        function showError(message) {
            document.getElementById("feedbackTableBody").innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-danger">${message}</td>
                </tr>`;
        }

        // Load feedback when page loads
        document.addEventListener("DOMContentLoaded", loadFeedback);
    </script>
</body>
</html>