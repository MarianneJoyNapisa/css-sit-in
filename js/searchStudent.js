// searchStudent.js

document.addEventListener("DOMContentLoaded", function () {
    const searchStudentButton = document.getElementById('searchStudentButton');
    const searchStudentInput = document.getElementById('searchStudentInput');
    const searchResults = document.getElementById('searchResults');

    if (searchStudentButton && searchStudentInput && searchResults) {
        searchStudentButton.addEventListener('click', function () {
            const searchTerm = searchStudentInput.value.trim();

            if (searchTerm === '') {
                alert('Please enter a search term.');
                return;
            }

            fetch(`../db/search_student.php?searchTerm=${encodeURIComponent(searchTerm)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }
                    return response.json(); // Parse as JSON
                })
                .then(data => {
                    if (data.status === 'success') {
                        searchResults.innerHTML = ''; // Clear existing results

                        if (data.data.length > 0) {
                            data.data.forEach(student => {
                                const studentItem = document.createElement('div');
                                studentItem.className = 'student-result-item';

                                const studentInfo = document.createElement('div');
                                const studentName = document.createElement('h6');
                                studentName.textContent = `${student.firstname} ${student.lastname}`;

                                const studentId = document.createElement('p');
                                studentId.textContent = `Student ID: ${student.idno}`;

                                studentInfo.appendChild(studentName);
                                studentInfo.appendChild(studentId);

                                const sitInButton = document.createElement('button');
                                sitInButton.className = 'btn btn-primary sit-in-button';
                                sitInButton.textContent = 'Sit-In';
                                sitInButton.addEventListener('click', function () {
                                    // Populate the modal with student data
                                    document.getElementById('idNumber').value = student.idno;
                                    document.getElementById('studentName').value = `${student.firstname} ${student.lastname}`;
                                    document.getElementById('remainingSessions').value = student.remaining_sessions;

                                    // Show the modal
                                    const sitInModal = new bootstrap.Modal(document.getElementById('sitInModal'));
                                    sitInModal.show();
                                });

                                studentItem.appendChild(studentInfo);
                                studentItem.appendChild(sitInButton);
                                searchResults.appendChild(studentItem);
                            });
                        } else {
                            searchResults.innerHTML = '<p class="text-muted">No students found.</p>';
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while searching for students.');
                });
        });
    }
});