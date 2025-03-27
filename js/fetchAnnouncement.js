document.addEventListener("DOMContentLoaded", function () {
    fetchAnnouncements();

    // Handle form submission
    const announcementForm = document.getElementById('announcementForm');
    if (announcementForm) {
        announcementForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(announcementForm);
            console.log([...formData.entries()]); // Log form data for debugging

            fetch('../db/announcements.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                return response.json(); // Parse as JSON
            })
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    fetchAnnouncements(); // Refresh the announcements list
                    announcementForm.reset(); // Clear the form
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while posting the announcement.');
            });
        });
    }

    // Handle modal focus
    const sitInModal = document.getElementById('sitInModal');
    if (sitInModal) {
        sitInModal.addEventListener('shown.bs.modal', function () {
            // Set focus to the first input field in the modal
            document.getElementById('idNumber').focus();
        });

        sitInModal.addEventListener('hidden.bs.modal', function () {
            // Remove focus from the modal when it is hidden
            document.activeElement.blur();
        });
    }
});

function fetchAnnouncements() {
    fetch('../db/announcements.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        return response.json(); // Parse as JSON
    })
    .then(data => {
        if (data.status === 'success') {
            const announcementsList = document.getElementById('announcements-list');
            if (announcementsList) {
                announcementsList.innerHTML = ''; // Clear existing content

                if (data.data.length > 0) {
                    data.data.forEach(announcement => {
                        const announcementItem = document.createElement('div');
                        announcementItem.className = 'announcement-item mb-3';

                        const announcementHeader = document.createElement('h6');
                        announcementHeader.className = 'text-secondary';
                        announcementHeader.textContent = `${announcement.author_name} | ${announcement.created_at}`;

                        const announcementContent = document.createElement('p');
                        announcementContent.textContent = announcement.content;

                        announcementItem.appendChild(announcementHeader);
                        announcementItem.appendChild(announcementContent);
                        announcementsList.appendChild(announcementItem);
                    });
                } else {
                    const noAnnouncements = document.createElement('p');
                    noAnnouncements.textContent = 'No announcements found.';
                    announcementsList.appendChild(noAnnouncements);
                }
            } else {
                console.error('Element with id "announcements-list" not found.');
            }
        } else {
            alert('Error fetching announcements: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching announcements:', error);
        alert('An error occurred while fetching announcements.');
    });
}