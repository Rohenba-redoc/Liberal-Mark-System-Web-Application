<?php 
include '../includes/header.php';
?>
<style>
    .progress-bar {
        background-color: #007bff; /* Blue color */
        text-align: center;
        color: white;
        transition: width 0.3s ease-in-out; /* Smooth transition */
    }
    .error-message {
        color: red;
        margin-top: 10px;
    }
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
        display: none; /* Hidden by default */
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="header">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <!-- Pretitle -->
                            <h6 class="header-pretitle">Overview</h6>
                            <!-- Title -->
                            <h1 class="header-title">Upload Student</h1>
                        </div>
                        <div class="col-auto">
                            <!-- Button -->
                            <a href="student.php" class="btn btn-primary lift">Cancel</a>
                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <div class="col-12" style="justify-content:center;align-items:center;">
                <form id="uploadForm" action="../functions/process_upload2.php" method="post" enctype="multipart/form-data">
                    <input type="file" id="fileInput" name="excel_file" accept=".xlsx, .xls" required class="form-control mb-5">
                    <button id="uploadButton" type="submit" class="btn btn-primary lift">Import Student</button>
                    <div id="errorMessage" class="error-message"></div>
                </form>
                <div class="progress mt-3" style="width: 100%; display: none;">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <dotlottie-player src="https://lottie.host/219ef7b9-9084-4b0f-a77d-f90bb9876d96/kaJBEaGBS3.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
</div>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    var fileInput = document.getElementById('fileInput');
    var errorMessage = document.getElementById('errorMessage');
    var uploadButton = document.getElementById('uploadButton');
    var loadingOverlay = document.getElementById('loadingOverlay');
    var progressBar = document.getElementById('progressBar');
    var progressContainer = document.querySelector('.progress');
    
    if (!fileInput.files.length) {
        errorMessage.textContent = 'Please select a file.';
        return;
    }

    var file = fileInput.files[0];
    var allowedExtensions = /(\.xlsx|\.xls)$/i;

    if (!allowedExtensions.exec(file.name)) {
        errorMessage.textContent = 'Invalid file type. Please upload an Excel file (.xlsx, .xls).';
        return;
    } else {
        errorMessage.textContent = ''; // Clear any previous error messages
    }

    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    var percentComplete = 0;

    // Show the progress bar and loading overlay
    progressContainer.style.display = 'block';
    loadingOverlay.style.display = 'flex';
    uploadButton.disabled = true; // Disable the upload button

    // Simulate random progress increments
    function updateProgress() {
        if (percentComplete < 100) {
            var randomIncrement = Math.floor(Math.random() * 5) + 1; // Random value between 1 and 5
            percentComplete = Math.min(percentComplete + randomIncrement, 100); // Ensure it doesn't exceed 100%
            
            progressBar.style.width = percentComplete + '%';
            progressBar.innerHTML = percentComplete + '%';

            setTimeout(updateProgress, Math.floor(Math.random() * 500) + 300); // Random delay between 300ms to 800ms
        } else {
            // Once the progress reaches 100%, start the actual upload
            xhr.open('POST', document.getElementById('uploadForm').action, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('Successfully uploaded the Student');
                    window.location.href = 'student.php';
                } else {
                    alert('Upload failed. Please try again.');
                }
                progressContainer.style.display = 'none';
                progressBar.style.width = '0%';
                progressBar.innerHTML = '0%';
                loadingOverlay.style.display = 'none'; // Hide the loading overlay
                uploadButton.disabled = false; // Re-enable the upload button
            };

            xhr.send(formData);
        }
    }

    updateProgress(); // Start the progress simulation
});
</script>

<?php 
include '../includes/footer.php';
?>
