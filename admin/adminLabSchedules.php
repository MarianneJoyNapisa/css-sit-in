<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Schedules</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
</head>
<body class="d-flex justify-content-center align-items-center">

    <?php include 'adminHeaderSideNav.php'; ?>

    <div id="overlay" class="overlay"></div>

    <main class="container mt-5">

        <!-- Title -->
        <div class  ="row justify-content-center mb-4">
            <h1>Manage Lab Schedules</h1>
        </div>
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
        <!-- ========== SECTION 1: Schedule Upload ========== -->
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                Upload Lab Schedule (Google Drive Link)
            </div>
            <div class="card-body">
                <form action="../db/upload_adminlabsched.php" method="POST">
                    <div class="row g-3">
                        <?php 
                        $labs = [524, 526, 528, 530, 542, 544, 517];
                        foreach ($labs as $lab): 
                        ?>
                            <div class="col-md-6">
                                <label for="link<?= $lab ?>" class="form-label">Lab <?= $lab ?> Schedule Link</label>
                                <input type="url" class="form-control" name="links[<?= $lab ?>]" id="link<?= $lab ?>" placeholder="https://drive.google.com/..." required>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-success mt-4">Save All Schedules</button>
                </form>
            </div>
        </div>

        <!-- ========== SECTION 2: Availability Toggles ========== -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                Lab Availability Status
            </div>
            <div class="card-body" id="labAvailabilityTable">
                <!-- Dynamically loaded switch toggle table via JS -->
                <div class="text-center text-muted">Loading availability status...</div>
            </div>
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
                const container = document.getElementById('labAvailabilityTable');
                container.innerHTML = '';

                if (data.length > 0) {
                    let table = document.createElement('div');
                    table.className = "row row-cols-1 row-cols-md-2 g-4";

                    data.forEach(schedule => {
                        const card = document.createElement('div');
                        card.className = 'col';
                        card.innerHTML = `
                            <div class="card border-info">
                                <div class="card-body">
                                    <h5 class="card-title">Lab ${schedule.lab_number}</h5>
                                    <p>
                                        <strong>Schedule:</strong> <a href="${schedule.schedule_link}" target="_blank">${schedule.schedule_link}</a><br>
                                        <strong>Last Updated:</strong> ${schedule.last_updated}
                                    </p>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input availability-switch" type="checkbox" 
                                            data-lab="${schedule.lab_number}"
                                            id="switch${schedule.lab_number}" 
                                            ${schedule.availability === 'available' ? 'checked' : ''}>
                                        <label class="form-check-label" for="switch${schedule.lab_number}">
                                            ${schedule.availability === 'available' ? 'Available' : 'Unavailable'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        `;
                        table.appendChild(card);
                    });

                    container.appendChild(table);
                } else {
                    container.innerHTML = `<div class="alert alert-warning">No lab records found.</div>`;
                }

                // Attach event listeners to switches
                document.querySelectorAll('.availability-switch').forEach(switchEl => {
                    switchEl.addEventListener('change', function () {
                        const labNumber = this.getAttribute('data-lab');
                        const newStatus = this.checked ? 'available' : 'unavailable';

                        fetch('../db/update_labavailability.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ lab_number: labNumber, availability: newStatus })
                        })
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {
                                this.nextElementSibling.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                            } else {
                                alert('Failed to update. Try again.');
                                this.checked = !this.checked; // revert toggle
                            }
                        });
                    });
                });

            })
            .catch(error => {
                console.error('Error fetching availability:', error);
                document.getElementById('labAvailabilityTable').innerHTML = `<div class="alert alert-danger">Failed to load availability data.</div>`;
            });
    });
</script>

