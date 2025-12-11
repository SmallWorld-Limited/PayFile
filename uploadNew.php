<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JPI Signing System">
    <meta name="keywords" content="">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI Systems - Upload & Sign</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
</head>
<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"> </div>
        <div class="dot"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-header">
            <?php include "includes/header.php"; ?>
        </div>
        <!-- Page Header Ends-->
        <!-- Page Body Start-->
        <div class="page-body-wrapper horizontal-menu">
            <!-- Page Sidebar Start-->
            <div class="sidebar-wrapper">
                <div>
                    <?php include "includes/sidebar.php"; ?>
                </div>
            </div>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Upload & Sign CSV File</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">CSV Files</li>
                                    <li class="breadcrumb-item active">Upload & Sign</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Upload CSV File for Signing</h4>
                                    <p>Select a CSV file and certificate to generate a signed file</p>
                                </div>
                                <div class="card-body">
                                    <form id="uploadForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Select File Type</label>
                                                    <select class="form-control" id="file_type" name="file_type" required>
                                                        <option value="">-- Select File Type --</option>
                                                        <?php
                                                          $dropdowns = new dropdowns();
                                                          $dropdowns->get_file_types();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Select Certificate</label>
                                                    <select class="form-control" id="certificate_id" name="certificate_id" required>
                                                        <option value="">-- Select Certificate --</option>
                                                        <?php
                                                          $dropdowns->get_certificates();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">File Reference</label>
                                                    <input class="form-control" type="text" id="file_reference" name="file_reference" placeholder="Enter file reference (e.g., WTC01-31.01.2023)" required>
                                                    <small class="form-text text-muted">Run ID & Date format</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Upload CSV File</label>
                                                    <div class="custom-file">
                                                        <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                                                        <small class="form-text text-muted">Max file size: 10MB. Supported formats: CSV, TXT</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Certificate Password (if required)</label>
                                                    <input class="form-control" type="password" id="cert_password" name="cert_password" placeholder="Enter certificate password">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="progress d-none" id="uploadProgress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="text-end mt-3">
                                                        <button class="btn btn-secondary" type="button" onclick="window.history.back()">Cancel</button>
                                                        <button class="btn btn-primary" id="submit" type="submit">Upload & Sign</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function () {
            // Handle form submission
            $('#uploadForm').submit(function (e) {
                e.preventDefault();
                
                var fileInput = $('#csv_file')[0];
                var file = fileInput.files[0];
                
                if (!file) {
                    Swal.fire({
                        title: "No File Selected",
                        text: "Please select a CSV file to upload",
                        icon: "error"
                    });
                    return;
                }
                
                // Check file size (10MB limit)
                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire({
                        title: "File Too Large",
                        text: "File size must be less than 10MB",
                        icon: "error"
                    });
                    return;
                }
                
                // Check file extension
                var allowedExtensions = /(\.csv|\.txt)$/i;
                if (!allowedExtensions.exec(file.name)) {
                    Swal.fire({
                        title: "Invalid File Type",
                        text: "Please upload a CSV or TXT file",
                        icon: "error"
                    });
                    return;
                }
                
                // Show progress bar
                $('#uploadProgress').removeClass('d-none');
                
                // Create FormData object
                var formData = new FormData();
                formData.append('upload_file', '1');
                formData.append('file_type', $('#file_type').val());
                formData.append('certificate_id', $('#certificate_id').val());
                formData.append('file_reference', $('#file_reference').val());
                formData.append('csv_file', file);
                formData.append('cert_password', $('#cert_password').val());
                
                // Submit using AJAX
                $.ajax({
                    type: "POST",
                    url: "./php/file_handler.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $('#uploadProgress .progress-bar').css('width', percentComplete + '%');
                                $('#uploadProgress .progress-bar').text(Math.round(percentComplete) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        console.log("Raw response:", response);
                        
                        try {
                            var res = response;
                            console.log("Parsed response:", res);
                            alert(res)
                            if (res.id == 1) {
                                Swal.fire({
                                    title: "Success!",
                                    html: res.mssg + "<br><br>" + 
                                          '<a href="./uploads.php" class="btn btn-outline-primary">View All Files</a> ' +
                                          '<a href="' + res.download_url + '" class="btn btn-primary">Download Signed File</a>',
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    width: '600px'
                                });
                                
                                // Reset form
                                $('#uploadForm')[0].reset();
                                $('#uploadProgress').addClass('d-none');
                                $('#uploadProgress .progress-bar').css('width', '0%');
                            } else {
                                var errorMsg = res.mssg;
                                if (res.debug) {
                                    errorMsg += "<br><br><small>Debug: " + JSON.stringify(res.debug) + "</small>";
                                }
                                
                                Swal.fire({
                                    title: "Error",
                                    html: errorMsg,
                                    icon: "error",
                                    width: '600px'
                                });
                                $('#uploadProgress').addClass('d-none');
                            }
                        } catch (e) {
                            console.error("Parse error:", e, "Response:", response);
                            
                            // Try to extract error message from HTML response
                            var errorMsg = "Invalid response from server. ";
                            if (typeof response === 'string' && response.includes('<b>')) {
                                // Try to extract PHP error from HTML
                                var tempDiv = document.createElement('div');
                                tempDiv.innerHTML = response;
                                var textContent = tempDiv.textContent || tempDiv.innerText || '';
                                if (textContent) {
                                    errorMsg += "Server returned: " + textContent.substring(0, 200);
                                }
                            }
                            
                            Swal.fire({
                                title: "Error",
                                html: errorMsg,
                                icon: "error"
                            });
                            $('#uploadProgress').addClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error, xhr.responseText);
                        
                        var errorMsg = "Upload failed: " + error;
                        if (xhr.responseText) {
                            try {
                                var errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse.mssg) {
                                    errorMsg = errorResponse.mssg;
                                }
                            } catch (e) {
                                // If not JSON, show raw response
                                errorMsg += "<br><br><small>Response: " + xhr.responseText.substring(0, 200) + "</small>";
                            }
                        }
                        
                        Swal.fire({
                            title: "Upload Failed",
                            html: errorMsg,
                            icon: "error"
                        });
                        $('#uploadProgress').addClass('d-none');
                    }
                });
            });
        });
    </script>
</body>
</html>