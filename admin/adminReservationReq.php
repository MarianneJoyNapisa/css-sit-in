<?php
session_start();

// Verify admin access
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

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
    <title>Reservation Requests</title>

    <style>
        .reservation-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .reservation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
        
        .reservation-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .reservation-detail {
            margin-bottom: 8px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--primary-dark-color);
        }
        
        .reservation-actions {
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .empty-state p {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .status-denied {
            background-color: #f8d7da;
            color: #842029;
        }
        
        .purpose-text {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 3px solid var(--secondary-color);
        }
        
        .nav-tabs .nav-link.active {
            font-weight: 600;
            color: var(--primary-dark-color);
            border-bottom: 3px solid var(--primary-color);
        }
        
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 10px 20px;
        }
        
        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .log-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            border-left: 4px solid #6c757d;
            padding: 15px;
        }
        
        .log-approved {
            border-left-color: #198754;
        }
        
        .log-denied {
            border-left-color: #dc3545;
        }
        
        .log-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .log-processed-by {
            font-size: 0.85rem;
            font-style: italic;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center">
    <?php include 'adminHeaderSideNav.php'; ?>

    <div id="overlay" class="overlay"></div>

    <main>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Reservation Requests
                </h2>
                <!-- <span class="badge bg-primary rounded-pill" id="request-count">0</span> -->
            </div>
            
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                Manage seat reservation requests from students.
            </div>
            
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="reservationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                        <i class="bi bi-hourglass me-1"></i>Pending
                        <span class="badge bg-warning ms-1" id="pending-count">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                        <i class="bi bi-check-circle me-1"></i>Approved
                        <span class="badge bg-success ms-1" id="approved-count">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="denied-tab" data-bs-toggle="tab" data-bs-target="#denied" type="button" role="tab">
                        <i class="bi bi-x-circle me-1"></i>Denied
                        <span class="badge bg-danger ms-1" id="denied-count">0</span>
                    </button>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content" id="reservationTabsContent">
                <!-- Pending Tab -->
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    <div id="reservations" class="row g-4"></div>
                    
                    <div id="loading-spinner" class="text-center my-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading pending requests...</p>
                    </div>
                </div>
                
                <!-- Approved Tab -->
                <div class="tab-pane fade" id="approved" role="tabpanel">
                    <div id="approved-reservations" class="row g-3">
                        <div class="col-12 text-center my-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading approved reservations...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Denied Tab -->
                <div class="tab-pane fade" id="denied" role="tabpanel">
                    <div id="denied-reservations" class="row g-3">
                        <div class="col-12 text-center my-5">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading denied reservations...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    <script>
    // Global variables
    let allReservations = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tab functionality
        const tabElms = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabElms.forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                const targetTab = event.target.getAttribute('data-bs-target');
                if (targetTab === '#approved' && document.getElementById('approved-reservations').children.length === 1) {
                    loadProcessedReservations('approved');
                } else if (targetTab === '#denied' && document.getElementById('denied-reservations').children.length === 1) {
                    loadProcessedReservations('denied');
                }
            });
        });
        
        // Load all initial data (pending, approved, denied counts)
        Promise.all([
            fetch('../db/fetch_seatreserv.php').then(r => r.json()),
            fetch('../db/fetch_approved_reserv.php').then(r => r.json()),
            fetch('../db/fetch_denied_reserv.php').then(r => r.json())
        ]).then(([pendingData, approvedData, deniedData]) => {
            // Update counts with all three states
            updateCounts(
                pendingData.status === 'success' ? pendingData.data : [],
                approvedData.status === 'success' ? approvedData.data : [],
                deniedData.status === 'success' ? deniedData.data : []
            );
            
            // Render pending reservations
            loadPendingReservations();
        }).catch(error => {
            console.error("Initial load error:", error);
            // Fallback to just loading pending if other requests fail
            loadPendingReservations();
        });
    });

    // Load pending reservations
    function loadPendingReservations() {
        fetch('../db/fetch_seatreserv.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('reservations');
                const loadingSpinner = document.getElementById('loading-spinner');
                
                // Hide loading spinner
                loadingSpinner.style.display = 'none';
                
                // Check for error or empty data
                if (data.status !== 'success') {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Error: ${data.message || "Unable to fetch pending reservations."}
                            </div>
                        </div>
                    `;
                    return;
                }

                const reservations = data.data;
                allReservations = reservations; // Store for potential filtering
                
                if (reservations.length === 0) {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bi bi-check-circle"></i>
                                <h4>No pending requests</h4>
                                <p>All reservation requests have been processed.</p>
                            </div>
                        </div>
                    `;
                    return;
                }

                // Render each reservation
                renderReservations(reservations, container, true);
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('reservations').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Failed to load pending reservations. Please try again later.
                        </div>
                    </div>
                `;
                document.getElementById('loading-spinner').style.display = 'none';
            });
    }

    async function loadProcessedReservations(status) {
        const endpoint = status === 'approved' 
            ? '../db/fetch_approved_reserv.php' 
            : '../db/fetch_denied_reserv.php';
        const container = document.getElementById(`${status}-reservations`);
        
        try {
            // Show loading state
            container.innerHTML = `
                <div class="col-12 text-center my-5">
                    <div class="spinner-border text-${status === 'approved' ? 'success' : 'danger'}" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading ${status} reservations...</p>
                </div>
            `;

            const response = await fetch(endpoint);
            
            // First check if response looks like HTML error
            const text = await response.text();
            if (text.startsWith('<') || text.includes('<br />')) {
                throw new Error('Server returned HTML error');
            }
            
            // Try to parse JSON
            const data = JSON.parse(text);
            
            // Rest of your success handling...
            container.innerHTML = '';
            
            if (data.status !== 'success') {
                throw new Error(data.message || `Unable to fetch ${status} reservations.`);
            }

            const reservations = data.data || [];
            document.getElementById(`${status}-count`).textContent = reservations.length;
            
            if (reservations.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-database-exclamation"></i>
                            <h4>No ${status} reservations</h4>
                            <p>There are no ${status} reservations in the system yet.</p>
                        </div>
                    </div>
                `;
                return;
            }

            // Render reservations
            reservations.forEach(res => {
                // Safely handle potentially undefined values
                const processedDate = res.processed_date || res.updated_at || new Date().toISOString();
                const processedBy = res.processed_by ? 
                    `<small class="log-processed-by">Processed by: ${res.processed_by}</small>` : '';
                
                const col = document.createElement('div');
                col.className = 'col-12';
                
                col.innerHTML = `
                    <div class="log-card ${status === 'approved' ? 'log-approved' : 'log-denied'}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">${res.fullname || 'Unknown User'}</h5>
                                <span class="status-badge ${status === 'approved' ? 'status-approved' : 'status-denied'}">
                                    <i class="bi bi-${status === 'approved' ? 'check-circle' : 'x-circle'} me-1"></i>
                                    ${status === 'approved' ? 'Approved' : 'Denied'}
                                </span>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="bi bi-building me-1"></i>
                                        <strong>Lab:</strong> ${res.laboratory || 'N/A'}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <strong>Seat:</strong> ${res.seat_number || 'N/A'}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="bi bi-calendar me-1"></i>
                                        <strong>Date:</strong> ${res.date ? new Date(res.date).toLocaleDateString('en-US', { 
                                            year: 'numeric', 
                                            month: 'long', 
                                            day: 'numeric' 
                                        }) : 'N/A'}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-clock me-1"></i>
                                        <strong>Time Slot:</strong> ${res.time_slot || 'N/A'}
                                    </p>
                                </div>
                            </div>
                            
                            ${res.purpose ? `
                            <div class="reservation-detail">
                                <i class="bi bi-chat-square-text me-1"></i>
                                <strong>Purpose:</strong>
                                <div class="purpose-text mt-1">${res.purpose}</div>
                            </div>
                            ` : ''}
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="log-date">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Processed on: ${new Date(processedDate).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}
                                </small>
                                ${processedBy}
                            </div>
                        </div>
                    </div>
                `;
                
                container.appendChild(col);
            });
            
        } catch (error) {
            console.error(`Error loading ${status} reservations:`, error);
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-${status === 'approved' ? 'success' : 'danger'}">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        ${error.message.includes('HTML error') 
                            ? 'Server error occurred. Please check server logs.' 
                            : `Failed to load ${status} reservations.`}
                        <small class="d-block mt-1">Try refreshing the page.</small>
                    </div>
                </div>
            `;
        }
    }
    
    // Update all count badges
    function updateCounts(pending, approved, denied) {
        // document.getElementById('request-count').textContent = pending.length;
        document.getElementById('pending-count').textContent = pending.length;
        document.getElementById('approved-count').textContent = approved.length;
        document.getElementById('denied-count').textContent = denied.length;
    }
    
    // Render reservations in cards (for pending tab)
    function renderReservations(reservations, container, showActions = false) {
        container.innerHTML = '';
        
        reservations.forEach(res => {
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6 col-lg-4';
            
            const card = document.createElement('div');
            card.className = 'reservation-card p-4 h-100';
            
            card.innerHTML = `
                <div class="reservation-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="mb-0">${res.fullname}</h5>
                        <span class="status-badge status-pending">
                            <i class="bi bi-hourglass-split me-1"></i>Pending
                        </span>
                    </div>
                    <small class="text-muted">Request ID: ${res.id}</small>
                </div>
                
                <div class="reservation-body">
                    <div class="reservation-detail">
                        <span class="detail-label"><i class="bi bi-building me-1"></i>Lab:</span>
                        ${res.laboratory}
                    </div>
                    <div class="reservation-detail">
                        <span class="detail-label"><i class="bi bi-geo-alt me-1"></i>Seat:</span>
                        ${res.seat_number}
                    </div>
                    <div class="reservation-detail">
                        <span class="detail-label"><i class="bi bi-calendar me-1"></i>Date:</span>
                        ${new Date(res.date).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}
                    </div>
                    <div class="reservation-detail">
                        <span class="detail-label"><i class="bi bi-clock me-1"></i>Time Slot:</span>
                        ${res.time_slot}
                    </div>
                    <div class="reservation-detail">
                        <span class="detail-label"><i class="bi bi-chat-square-text me-1"></i>Purpose:</span>
                        <div class="purpose-text mt-2">${res.purpose}</div>
                    </div>
                </div>
                
                ${showActions ? `
                <div class="reservation-actions">
                    <button class="btn btn-primary" onclick="updateStatus(${res.id}, 'approved')">
                        <i class="bi bi-check-circle me-1"></i>Approve
                    </button>
                    <button class="btn btn-secondary" onclick="updateStatus(${res.id}, 'denied')">
                        <i class="bi bi-x-circle me-1"></i>Deny
                    </button>
                </div>
                ` : ''}
            `;
            
            col.appendChild(card);
            container.appendChild(col);
        });
    }
    
    // Approve or deny reservation with confirmation
    function updateStatus(id, status) {
        const action = status === 'approved' ? 'approve' : 'deny';
        const icon = status === 'approved' ? 'check-circle' : 'x-circle';

        if (confirm(`Are you sure you want to ${action} this reservation request?`)) {
            // Disable both buttons during request
            const card = document.querySelector(`button[onclick="updateStatus(${id}, '${status}')"]`).closest('.reservation-card');
            const buttons = card.querySelectorAll('.btn');
            
            // Store original button states
            const originalButtonStates = {};
            buttons.forEach(btn => {
                originalButtonStates[btn.className] = btn.innerHTML;
                btn.disabled = true;
                if (btn.onclick && btn.onclick.toString().includes(`updateStatus(${id}, '${status}')`)) {
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Processing...`;
                }
            });

            fetch('../db/update_reservstat.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    reservation_id: id,
                    status: status
                })
            })
                .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log("Received response:", result);
                
                if (result.status === 'success') {
                    showNotification(result.message, 'success');
                    
                    // Remove card from UI
                    card.parentElement.removeChild(card);
                    
                    // Update counts
                    const pendingCount = document.querySelectorAll('#reservations .col-12').length - 1; // -1 because we removed one
                    const approvedCountEl = document.getElementById('approved-count');
                    const deniedCountEl = document.getElementById('denied-count');
                    
                    if (status === 'approved') {
                        approvedCountEl.textContent = parseInt(approvedCountEl.textContent) + 1;
                    } else {
                        deniedCountEl.textContent = parseInt(deniedCountEl.textContent) + 1;
                    }
                    
                    // document.getElementById('request-count').textContent = pendingCount;
                    document.getElementById('pending-count').textContent = pendingCount;

                    // Show empty state if no more cards
                    if (pendingCount === 0) {
                        const container = document.getElementById('reservations');
                        container.innerHTML = `
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="bi bi-check-circle"></i>
                                    <h4>No pending requests</h4>
                                    <p>All reservation requests have been processed.</p>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    const errorMsg = result.message || result.error_details || `Failed to ${action} reservation`;
                    showNotification(errorMsg, 'error');
                    resetButtons(card, originalButtonStates);
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showNotification('Network error. Please check connection and try again.', 'error');
                resetButtons(card, originalButtonStates);
            });
        }
    }

    // Helper function to reset buttons with original state
    function resetButtons(card, originalStates) {
        const buttons = card.querySelectorAll('.btn');
        buttons.forEach(btn => {
            btn.disabled = false;
            if (originalStates[btn.className]) {
                btn.innerHTML = originalStates[btn.className];
            } else {
                // Fallback if original state wasn't stored
                if (btn.classList.contains('btn-primary')) {
                    btn.innerHTML = `<i class="bi bi-check-circle me-1"></i>Approve`;
                } else if (btn.classList.contains('btn-secondary')) {
                    btn.innerHTML = `<i class="bi bi-x-circle me-1"></i>Deny`;
                }
            }
        });
    }
        
    // Show notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}-fill"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Trigger the show animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
</script>
</body>
</html>