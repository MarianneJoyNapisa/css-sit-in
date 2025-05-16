<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/globalStyle.css">
    <link rel="stylesheet" href="../css/header_sidenavStyle.css">
    <title>Lab Resources</title>
    <style>
        .resource-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
            height: 100%;
        }
        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .resource-icon {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .search-container {
            margin-bottom: 30px;
        }
        .filter-buttons {
            margin-bottom: 20px;
        }
        .btn-group-resource {
            display: flex;
            gap: 8px;
        }
        .btn-group-resource .btn {
            flex: 1;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    
    <?php include 'userHeaderSideNav.php'; ?>
    
    <div id="overlay" class="overlay"></div>

    <main class="w-100">
        <div class="container">
            <div class="row justify-content-center g-2">
                <h1 class="text-center mb-4">Lab Resources</h1>
                
                <!-- Search and Filter Section -->
                <div class="col-md-12 search-container">
                    <div class="input-group mb-3">
                        <input type="text" id="search-input" class="form-control" placeholder="Search resources...">
                        <button class="btn btn-primary" type="button" id="search-button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    
                    <div class="filter-buttons text-center">
                        <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                        <button class="btn btn-outline-primary filter-btn" data-filter="PDF">PDFs</button>
                        <button class="btn btn-outline-primary filter-btn" data-filter="Link">Links</button>
                    </div>
                </div>
                
                <!-- Resources will be loaded here -->
                <div class="row" id="resources-container">
                    <!-- Content will be loaded via JavaScript -->
                </div>
                
                <!-- Loading Spinner -->
                <div id="loading-spinner" class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <!-- No Results Message (hidden by default) -->
                <div id="no-results" class="text-center my-5" style="display: none;">
                    <h4>No resources found</h4>
                    <p>Try adjusting your search or filters</p>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sideNav.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load resources when page loads
            loadResources();
            
            // Search functionality
            document.getElementById('search-button').addEventListener('click', function() {
                loadResources();
            });
            
            // Also search when pressing Enter in search box
            document.getElementById('search-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    loadResources();
                }
            });
            
            // Filter functionality
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    // Reload resources with new filter
                    loadResources();
                });
            });
        });
        
        function loadResources() {
            const searchTerm = document.getElementById('search-input').value;
            const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
            
            // Show loading spinner
            document.getElementById('loading-spinner').style.display = 'block';
            document.getElementById('no-results').style.display = 'none';
            
            // Fetch resources from server
            fetch(`../db/fetch_labresource.php?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    // Hide loading spinner
                    document.getElementById('loading-spinner').style.display = 'none';
                    
                    const container = document.getElementById('resources-container');
                    container.innerHTML = '';
                    
                    // Filter data based on active filter
                    let filteredData = data;
                    if (activeFilter !== 'all') {
                        filteredData = data.filter(resource => resource.type === activeFilter);
                    }
                    
                    if (filteredData.length === 0) {
                        document.getElementById('no-results').style.display = 'block';
                        return;
                    }
                    
                    // Display each resource
                    filteredData.forEach(resource => {
                        const col = document.createElement('div');
                        col.className = 'col-md-4 col-sm-6';
                        
                        const card = document.createElement('div');
                        card.className = 'card resource-card';
                        
                        const cardBody = document.createElement('div');
                        cardBody.className = 'card-body d-flex flex-column';
                        
                        // Icon based on resource type
                        const icon = document.createElement('div');
                        icon.className = 'text-center resource-icon';
                        icon.innerHTML = resource.type === 'PDF' 
                            ? '<i class="bi bi-file-earmark-pdf text-danger"></i>' 
                            : '<i class="bi bi-link-45deg text-primary"></i>';
                        
                        // Title
                        const title = document.createElement('h5');
                        title.className = 'card-title text-center';
                        title.textContent = resource.title;
                        
                        // Description (if available)
                        if (resource.description) {
                            const desc = document.createElement('p');
                            desc.className = 'card-text text-muted';
                            desc.textContent = resource.description.length > 100 
                                ? resource.description.substring(0, 100) + '...' 
                                : resource.description;
                            cardBody.appendChild(desc);
                        }
                        
                        // Button group
                        const btnGroup = document.createElement('div');
                        btnGroup.className = 'btn-group-resource mt-auto';
                        
                        // View button
                        const viewButton = document.createElement('a');
                        viewButton.href = resource.link;
                        viewButton.target = '_blank';
                        viewButton.className = 'btn btn-primary';
                        viewButton.innerHTML = resource.type === 'PDF' 
                            ? '<i class="bi bi-eye"></i> View' 
                            : '<i class="bi bi-box-arrow-up-right"></i> Open';
                        
                        // Download button (only for PDFs)
                        if (resource.type === 'PDF') {
                            const downloadButton = document.createElement('button');
                            downloadButton.className = 'btn btn-outline-secondary';
                            downloadButton.innerHTML = '<i class="bi bi-download"></i> Download';
                            downloadButton.onclick = function() {
                                downloadPDF(resource.link, resource.title);
                            };
                            btnGroup.appendChild(downloadButton);
                        }
                        
                        // Append all elements
                        btnGroup.appendChild(viewButton);
                        cardBody.appendChild(icon);
                        cardBody.appendChild(title);
                        cardBody.appendChild(btnGroup);
                        card.appendChild(cardBody);
                        col.appendChild(card);
                        container.appendChild(col);
                    });
                })
                .catch(error => {
                    console.error('Error loading resources:', error);
                    document.getElementById('loading-spinner').style.display = 'none';
                    document.getElementById('no-results').style.display = 'block';
                });
        }
        
        function downloadPDF(url, filename) {
            // Remove .pdf extension if already present in filename
            filename = filename.replace(/\.pdf$/i, '');
            
            fetch(url)
                .then(response => response.blob())
                .then(blob => {
                    // Create a download link
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = `${filename}.pdf`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch(error => {
                    console.error('Download error:', error);
                    alert('Failed to download the file. Please try again.');
                });
        }
    </script>
</body>
</html>