<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Lab Resources & Materials</title>
</head>
<body class="d-flex justify-content-center align-items-center">
    
    <?php include 'adminHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>

    <main>
        <div class="container">
            <div class="row justify-content-center g-2">
                <h2>Upload Lab Resource</h2>
                <form action="../db/upload_adminresource.php" method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="mb-3">
                        <label class="form-label">Resource Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload PDF File</label>
                        <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">OR Paste Google Drive Link</label>
                        <input type="url" name="google_link" class="form-control">
                    </div>

                    <input type="hidden" name="file_type" id="file_type_input" value="">

                    <button type="submit" class="btn btn-primary">Upload Resource</button>
                </form>

                <h2 class="pt-3">Uploaded Lab Resources</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody id="lab-resources-body">
                        <!-- Fetched rows will go here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="../js/sideNav.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Fetch and display lab resources
            fetch('../db/fetch_labresource.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('lab-resources-body');
                    tbody.innerHTML = ''; // Clear previous content

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3">No resources found.</td></tr>';
                        return;
                    }

                    data.forEach(resource => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${resource.title}</td>
                            <td>${resource.type}</td>
                            <td><a href="${resource.link}" target="_blank">View</a></td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    document.getElementById('lab-resources-body').innerHTML = 
                        '<tr><td colspan="3">Error loading resources. Please try again.</td></tr>';
                });

            // Handle file type detection
            const form = document.querySelector('form');
            const pdfInput = form.querySelector('input[name="pdf_file"]');
            const linkInput = form.querySelector('input[name="google_link"]');
            const typeInput = form.querySelector('#file_type_input');

            if (pdfInput && linkInput && typeInput) {
                pdfInput.addEventListener('change', () => {
                    if (pdfInput.files.length > 0) {
                        typeInput.value = 'pdf';
                        linkInput.value = ''; // Clear link if file is selected
                    }
                });

                linkInput.addEventListener('input', () => {
                    if (linkInput.value) {
                        typeInput.value = 'link';
                        pdfInput.value = ''; // Clear file if link is entered
                    }
                });

                // Validate form before submission
                form.addEventListener('submit', (e) => {
                    if (!typeInput.value) {
                        e.preventDefault();
                        alert('Please either upload a PDF file or provide a Google Drive link.');
                    }
                });
            }
        });
    </script>
</body>
</html>