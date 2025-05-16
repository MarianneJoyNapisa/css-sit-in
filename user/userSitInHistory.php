<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check for user_id (consistent with userProfile.php)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get user data from session
$id_number = $_SESSION['id_number'] ?? '';
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Sit-In History</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    <?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
    <main>
        <div class="container mt-4">
            <h5 class="text-primary mb-4">Sit-In History for <?php echo htmlspecialchars($username); ?></h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Number</th>
                            <th>Name</th>
                            <th>Purpose</th>
                            <th>Lab</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Duration</th>
                            <th>Date</th>
                            <th>Actions</th> <!-- Added Actions column -->
                        </tr>
                    </thead>
                    <tbody id="sitInHistoryTableBody">
                        <tr>
                            <td colspan="9" class="text-center">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedbackForm">
                        <input type="hidden" id="feedbackSitInId">
                        <div class="mb-3">
                            <label for="feedbackRating" class="form-label">Rating</label>
                            <select class="form-select" id="feedbackRating" required>
                                <option value="">Select rating</option>
                                <option value="5">Excellent</option>
                                <option value="4">Good</option>
                                <option value="3">Average</option>
                                <option value="2">Poor</option>
                                <option value="1">Very Poor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="feedbackComments" class="form-label">Comments</label>
                            <textarea class="form-control" id="feedbackComments" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitFeedback()">Submit Feedback</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    
    <script>
        // Initialize Bootstrap modal
        const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
        let currentSitInId = null;

        function loadSitInHistory() {
            const id_number = "<?php 
                echo isset($_SESSION['id_number']) && !empty($_SESSION['id_number']) 
                    ? htmlspecialchars($_SESSION['id_number'], ENT_QUOTES, 'UTF-8') 
                    : ''; 
            ?>";
            
            const username = "<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>";
            
            console.log("Fetching history for:", username, "ID:", id_number);

            if (!id_number) {
                showError("Please refresh the page or login again to load your history");
                console.error("ID Number missing - Session:", <?php echo json_encode($_SESSION); ?>);
                return;
            }

            fetch(`../db/fetch_userhistory.php?id_number=${encodeURIComponent(id_number)}`)
                .then(handleResponse)
                .then(updateTable)
                .catch(handleError);
        }

        function handleResponse(response) {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        }

        function updateTable(data) {
            const tableBody = document.getElementById("sitInHistoryTableBody");
            
            if (data.status === "success" && data.data?.length) {
                tableBody.innerHTML = data.data.map(history => `
                    <tr>
                        <td>${history.id_number || 'N/A'}</td>
                        <td>${history.name || 'N/A'}</td>
                        <td>${history.purpose || 'N/A'}</td>
                        <td>${history.lab || 'N/A'}</td>
                        <td>${formatTime(history.time_in)}</td>
                        <td>${history.timeout ? formatTime(history.timeout) : 'Active'}</td>
                        <td>${calculateDuration(history.time_in, history.timeout)}</td>
                        <td>${formatDate(history.created_at)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="openFeedbackModal('${history.id}')">
                                <i class="bi bi-chat-left-text"></i> Feedback
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tableBody.innerHTML = `<tr><td colspan="9" class="text-center">${data.message || 'No history found'}</td></tr>`;
            }
        }

        function openFeedbackModal(sitInId) {
            currentSitInId = sitInId;
            document.getElementById('feedbackSitInId').value = sitInId;
            feedbackModal.show();
        }

        function submitFeedback() {
            const sitInId = document.getElementById('feedbackSitInId').value;
            const rating = document.getElementById('feedbackRating').value;
            const comments = document.getElementById('feedbackComments').value;

            if (!rating) {
                alert('Please select a rating');
                return;
            }

            fetch('../db/submit_feedback.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `sit_in_id=${sitInId}&rating=${rating}&comments=${encodeURIComponent(comments)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Feedback submitted successfully!');
                    feedbackModal.hide();
                    document.getElementById('feedbackForm').reset();
                } else {
                    alert('Error: ' + (data.message || 'Failed to submit feedback'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to submit feedback. Please try again.');
            });
        }

        function handleError(error) {
            console.error("Error:", error);
            showError("Failed to load data. Please try again.");
        }

        function showError(message) {
            document.getElementById("sitInHistoryTableBody").innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-danger">${message}</td>
                </tr>`;
        }

        function formatTime(datetime) {
            return datetime ? new Date(datetime).toLocaleTimeString() : 'N/A';
        }

        function formatDate(datetime) {
            return datetime ? new Date(datetime).toLocaleDateString() : 'N/A';
        }

        function calculateDuration(timeIn, timeOut) {
            if (!timeIn) return 'N/A';
            const start = new Date(timeIn);
            const end = timeOut ? new Date(timeOut) : new Date();
            
            const diff = Math.floor((end - start) / 1000 / 60); // minutes
            return `${Math.floor(diff/60)}h ${diff%60}m`;
        }

        document.addEventListener("DOMContentLoaded", loadSitInHistory);
    </script>
</body>
</html>