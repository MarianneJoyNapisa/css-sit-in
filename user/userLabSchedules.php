<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Room Schedules</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS + Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">

</head>
<body>
<?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>
<main class="container mt-5">
    <h2 class="text-center mb-4">Lab Room Schedules & Availability</h2>
    <!-- ========== SECTION 0: Schedule Table ========== -->
        <div class="card mb-5">
            <div class="card-header bg-info text-white">
                Current Lab Schedule
            </div>
            <div class="card-body">
                <div class="table-responsive schedule-table">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Monday/Wednesday</th>
                                <th>Tuesday/Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                                <th>Time Slot</th>
                                <th>Lab 517</th>
                                <th>Lab 524</th>
                                <th>Lab 526</th>
                                <th>Lab 528</th>
                                <th>Lab 530</th>
                                <th>Lab 542</th>
                                <th>Lab 544</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="9">Monday/Wednesday</td>
                                <td rowspan="9">Tuesday/Thursday</td>
                                <td rowspan="9">Friday</td>
                                <td rowspan="9">Saturday</td>
                                <td>7:30AM-9:00AM</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                            </tr>
                            <tr>
                                <td>9:00AM-10:30AM</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                            </tr>
                            <tr>
                                <td>10:30AM-12:00PM</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                            </tr>
                            <tr>
                                <td>12:00PM-1:00PM</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                            </tr>
                            <tr>
                                <td>1:00PM-3:00PM</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                            </tr>
                            <tr>
                                <td>3:00PM-4:30PM</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                            </tr>
                            <tr>
                                <td>4:30PM-6:00PM</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="occupied-cell">Occupied</td>
                            </tr>
                            <tr>
                                <td>6:00PM-7:30PM</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                            </tr>
                            <tr>
                                <td>7:30PM-8:00PM</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                                <td class="available-cell">Available</td>
                                <td class="occupied-cell">Occupied</td>
                                <td class="available-cell">Available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <h5>Legend</h5>
                    <p><span class="badge bg-success">Available</span> - Lab is vacant during this time</p>
                    <p><span class="badge bg-danger">Occupied</span> - Lab is in use during this time</p>
                    <p class="text-muted">Note: Lab availability is managed by administration and applies for the entire semester.</p>
                </div>
            </div>
        </div>
        
    <div id="userLabSchedules" class="row row-cols-1 row-cols-md-2 g-4">
        <!-- Dynamic lab cards will be inserted here -->
    </div>
</main>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/sideNav.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('../db/fetch_labschedules.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('userLabSchedules');
            if (data.length > 0) {
                data.forEach(lab => {
                    const card = document.createElement('div');
                    card.className = 'col';
                    card.innerHTML = `
                        <div class="card border-${lab.availability === 'available' ? 'success' : 'danger'} shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Lab ${lab.lab_number}</h5>
                                <p class="card-text mb-1">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-${lab.availability === 'available' ? 'success' : 'danger'} text-uppercase">${lab.availability}</span>
                                </p>
                                <p class="mb-1">
                                    <strong>Schedule:</strong> 
                                    ${lab.schedule_link 
                                        ? `<a href="${lab.schedule_link}" target="_blank">View Schedule</a>` 
                                        : '<span class="text-muted">No schedule uploaded</span>'}
                                </p>
                                <small class="text-muted">Last Updated: ${lab.last_updated}</small>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });
            } else {
                container.innerHTML = `<div class="alert alert-info text-center">No lab schedules found.</div>`;
            }
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            document.getElementById('userLabSchedules').innerHTML = `<div class="alert alert-danger">Failed to load data. Please try again later.</div>`;
        });
});
</script>

</body>
</html>
