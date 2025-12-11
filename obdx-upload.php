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
    <meta name="description" content="JPI File Signing System">
    <meta name="keywords" content="File Signing, CSV, PKCS7, Digital Signature, OBDX">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>Upload OBDX File - JPI File Signing System</title>
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
    <style>
        .format-example {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 10px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
        }
        .file-type-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .file-type-card:hover {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0,123,255,0.1);
        }
        .file-type-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
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
                                <h3>Upload OBDX File</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">CSV Files</li>
                                    <li class="breadcrumb-item active">Upload OBDX</li>
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
                                    <h4>Upload OBDX Payment File</h4>
                                    <p>Upload OBDX format files for validation and signing</p>
                                </div>
                                <div class="card-body">
                                    <form id="obdxUploadForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Select OBDX File Type *</label>
                                                    <select class="form-control" id="file_type" name="file_type" required>
                                                        <option value="">-- Select File Type --</option>
                                                        <option value="1">Payment File (Type 1)</option>
                                                        <option value="2">Remittance File (Type 2)</option>
                                                        <option value="3">Remittance with PRN (Type 3)</option>
                                                        <option value="4">Foreign Payment (Type 4)</option>
                                                        <option value="5">Salary Payment (Type 5)</option>
                                                    </select>
                                                    <small class="form-text text-muted">Select the OBDX file type based on your payment type</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">File Reference *</label>
                                                    <input class="form-control" type="text" id="file_reference" 
                                                           name="file_reference" required
                                                           placeholder="e.g., WTC01-31.01.2023">
                                                    <small class="form-text text-muted">Run ID & Date format. Must be unique.</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Upload OBDX File *</label>
                                                    <div class="custom-file">
                                                        <input class="form-control" type="file" id="obdx_file" 
                                                               name="obdx_file" accept=".csv,.txt" required>
                                                        <small class="form-text text-muted">
                                                            Max file size: 10MB. File must be in OBDX format with semicolon (;) delimiter.
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i>
                                                    <strong>OBDX File Requirements:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        <li>File must use semicolon (;) as delimiter (not comma)</li>
                                                        <li>Header line must start with "0" (zero)</li>
                                                        <li>Body lines must start with "1" or "2" based on file type</li>
                                                        <li>Total amounts and counts in header must match body records</li>
                                                        <li>All mandatory fields must be populated</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="progress d-none" id="uploadProgress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                             role="progressbar" style="width: 0%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="text-end mt-3">
                                                        <button class="btn btn-secondary" type="button" onclick="window.history.back()">
                                                            <i class="fa fa-arrow-left"></i> Back
                                                        </button>
                                                        <button class="btn btn-primary" id="submit" type="submit">
                                                            <i class="fa fa-upload"></i> Upload & Validate
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- OBDX File Format Examples -->
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>OBDX File Format Examples</h4>
                                    <p>Sample file structures for each OBDX file type</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Payment File (Type 1) -->
                                        <div class="col-lg-6">
                                            <div class="file-type-card position-relative">
                                                <span class="file-type-badge">1</span>
                                                <h5>Payment File (Type 1)</h5>
                                                <p class="text-muted">Standard payment transactions</p>
                                                <h6>Header Format:</h6>
                                                <div class="format-example">
                                                    0;TEST_13;MWK;5000.00;0005
                                                </div>
                                                <h6>Body Format:</h6>
                                                <div class="format-example">
                                                    1;0001;MWK;0013007800002;Malawi Vulnerability Assessment Committee;1000.00;UNDP;4610002;903962;58301610;615023;SBICMWM0;9100001187829;CC001;;058SC2200657843;PM1Nodate
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-info"><i class="fa fa-check-circle"></i> 18 fields per line</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Remittance File (Type 2) -->
                                        <div class="col-lg-6">
                                            <div class="file-type-card position-relative">
                                                <span class="file-type-badge">2</span>
                                                <h5>Remittance File (Type 2)</h5>
                                                <p class="text-muted">Remittance/advice payments</p>
                                                <h6>Header Format:</h6>
                                                <div class="format-example">
                                                    0;RBM_09.09.2025;6272978.00;0010
                                                </div>
                                                <h6>Body Format:</h6>
                                                <div class="format-example">
                                                    2;0001;0013007800002;MVAC;MWK;0014000360008;MWK;937189.00;MRA;09.09.2025;09.09.2025;200TRF2200578990;55001001
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-info"><i class="fa fa-check-circle"></i> 13 fields per line</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Remittance with PRN (Type 3) -->
                                        <div class="col-lg-6">
                                            <div class="file-type-card position-relative">
                                                <span class="file-type-badge">3</span>
                                                <h5>Remittance with PRN (Type 3)</h5>
                                                <p class="text-muted">Remittance with Payment Reference Number</p>
                                                <h6>Header Format:</h6>
                                                <div class="format-example">
                                                    0;LAG21-09.09.2025;10000.00;0004
                                                </div>
                                                <h6>Body Format:</h6>
                                                <div class="format-example">
                                                    2;0001;0013006161243;Salaries Pool Account;MWK;0014000360008;MWK;1000.00;MRA Revenue;09.09.2025;09.09.2025;271TRF2100675570;271001011;0000010001001
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-info"><i class="fa fa-check-circle"></i> 14 fields per line (includes PRN)</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Foreign Payment (Type 4) -->
                                        <div class="col-lg-6">
                                            <div class="file-type-card position-relative">
                                                <span class="file-type-badge">4</span>
                                                <h5>Foreign Payment (Type 4)</h5>
                                                <p class="text-muted">Foreign currency payments</p>
                                                <h6>Header Format:</h6>
                                                <div class="format-example">
                                                    0;WTC01-31.01.2023;USD;5000000;0002
                                                </div>
                                                <h6>Body Format (truncated):</h6>
                                                <div class="format-example">
                                                    1;0001;USD;13006161244;(ORT) MG Other Recurrent Expenditure A/C;2000000;Two Million USD Only;SMARTECH INTERNATIONAL...
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-info"><i class="fa fa-check-circle"></i> 32+ fields per line</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Salary Payment (Type 5) -->
                                        <div class="col-lg-6">
                                            <div class="file-type-card position-relative">
                                                <span class="file-type-badge">5</span>
                                                <h5>Salary Payment (Type 5)</h5>
                                                <p class="text-muted">Salary/bulk payment files</p>
                                                <h6>Header Format:</h6>
                                                <div class="format-example">
                                                    0;12SC;140000.00;C;0013007800016;12SC2100537593;Test Salary for 12SC;30.05.2025;0007
                                                </div>
                                                <h6>Body Format:</h6>
                                                <div class="format-example">
                                                    1;SBICMWM0;9100001187829;12SC210053759300001;MAILOSI MODESTER;Standard Bank;049;SAL;20000.0
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-info"><i class="fa fa-check-circle"></i> 9 fields per line</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Upload Instructions -->
                                        <div class="col-lg-6">
                                            <div class="file-type-card bg-light">
                                                <h5><i class="fa fa-graduation-cap text-primary"></i> Upload Instructions</h5>
                                                <ol class="mb-0">
                                                    <li>Select the correct OBDX file type</li>
                                                    <li>Enter a unique file reference</li>
                                                    <li>Upload your OBDX format file</li>
                                                    <li>System will validate the format</li>
                                                    <li>After validation, you can sign the file</li>
                                                </ol>
                                                <div class="alert alert-warning mt-3 mb-0">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <strong>Note:</strong> Sage exports must be configured to use semicolon (;) delimiter and match OBDX format.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            $('#obdxUploadForm').submit(function (e) {
                e.preventDefault();
                
                var fileInput = $('#obdx_file')[0];
                var file = fileInput.files[0];
                var fileType = $('#file_type').val();
                var fileReference = $('#file_reference').val();
                
                // Validation
                if (!fileType) {
                    Swal.fire({
                        title: "File Type Required",
                        text: "Please select an OBDX file type",
                        icon: "error"
                    });
                    return;
                }
                
                if (!fileReference) {
                    Swal.fire({
                        title: "File Reference Required",
                        text: "Please enter a file reference",
                        icon: "error"
                    });
                    return;
                }
                
                if (!file) {
                    Swal.fire({
                        title: "No File Selected",
                        text: "Please select an OBDX file to upload",
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
                $('#submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Validating...');
                
                // Create FormData object
                var formData = new FormData();
                formData.append('upload_obdx', '1');
                formData.append('file_type', fileType);
                formData.append('file_reference', fileReference);
                formData.append('obdx_file', file);
                
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
                            var res = typeof response === 'string' ? JSON.parse(response) : response;
                            
                            $('#submit').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Validate');
                            $('#uploadProgress').addClass('d-none');
                            
                            if (res.id == 1) {
                                Swal.fire({
                                    title: "Success!",
                                    html: res.mssg + "<br><br>" + 
                                        '<div class="d-grid gap-2">' +
                                        '<a href="./uploads.php" class="btn btn-outline-primary">View All Files</a>' +
                                        '<a href="' + res.download_url + '" class="btn btn-primary">Download Validated File</a>' +
                                        '<a href="./uploadNew.php?batch_id=' + res.batch_id + '" class="btn btn-success">Sign This File</a>' +
                                        '</div>',
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    width: '600px'
                                });
                                
                                // Reset form after successful upload
                                $('#obdxUploadForm')[0].reset();
                                
                            } else {
                                var errorMsg = res.mssg;
                                if (res.validation_errors) {
                                    errorMsg += "<br><br><strong>Validation Errors:</strong><ul>";
                                    if (Array.isArray(res.validation_errors)) {
                                        res.validation_errors.forEach(function(error) {
                                            errorMsg += "<li>" + error + "</li>";
                                        });
                                    }
                                    errorMsg += "</ul>";
                                }
                                
                                Swal.fire({
                                    title: "Validation Failed",
                                    html: errorMsg,
                                    icon: "error",
                                    width: '700px'
                                });
                            }
                        } catch (e) {
                            console.error("Parse error:", e, "Response:", response);
                            
                            Swal.fire({
                                title: "Error",
                                text: "Invalid response from server. Please check the file format.",
                                icon: "error"
                            });
                            $('#submit').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Validate');
                            $('#uploadProgress').addClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error, xhr.responseText);
                        
                        $('#submit').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Validate');
                        $('#uploadProgress').addClass('d-none');
                        
                        var errorMsg = "Upload failed: " + error;
                        if (xhr.responseText) {
                            try {
                                var errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse.mssg) {
                                    errorMsg = errorResponse.mssg;
                                }
                            } catch (e) {
                                errorMsg += "<br><br><small>Response: " + xhr.responseText.substring(0, 200) + "</small>";
                            }
                        }
                        
                        Swal.fire({
                            title: "Upload Failed",
                            html: errorMsg,
                            icon: "error"
                        });
                    }
                });
            });
            
            // File type selection change
            $('#file_type').change(function() {
                var fileType = $(this).val();
                var fileTypeNames = {
                    '1': 'Payment File',
                    '2': 'Remittance File', 
                    '3': 'Remittance with PRN',
                    '4': 'Foreign Payment',
                    '5': 'Salary Payment'
                };
                
                if (fileType && fileTypeNames[fileType]) {
                    $('.file-type-card').removeClass('border-primary');
                    $('.file-type-card').find('.file-type-badge').css('background', '#007bff');
                    
                    // Highlight selected file type
                    $('.file-type-card .file-type-badge').each(function() {
                        if ($(this).text() === fileType) {
                            $(this).closest('.file-type-card').addClass('border-primary');
                            $(this).css('background', '#28a745');
                        }
                    });
                }
            });
            
            // Check file reference uniqueness on blur
            $('#file_reference').on('blur', function() {
                var ref = $(this).val().trim();
                if (ref.length > 0) {
                    $.ajax({
                        type: "POST",
                        url: "./php/file_handler.php",
                        data: {
                            check_reference: '1',
                            file_reference: ref
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.exists) {
                                $('#file_reference').addClass('is-invalid');
                                $('#file_reference').next('.invalid-feedback').remove();
                                $('#file_reference').after('<div class="invalid-feedback">' + response.message + '</div>');
                            } else {
                                $('#file_reference').removeClass('is-invalid');
                                $('#file_reference').addClass('is-valid');
                                $('#file_reference').next('.invalid-feedback').remove();
                            }
                        },
                        error: function() {
                            // Silently fail - don't show error for reference check
                        }
                    });
                }
            });
            
            // Remove validation classes on input
            $('#file_reference, #file_type').on('input change', function() {
                $(this).removeClass('is-invalid is-valid');
                $(this).next('.invalid-feedback').remove();
            });
            
            // File input change
            $('#obdx_file').change(function() {
                var file = this.files[0];
                if (file) {
                    var fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                    var fileName = file.name;
                    
                    // Check extension
                    var ext = fileName.split('.').pop().toLowerCase();
                    if (ext !== 'csv' && ext !== 'txt') {
                        $(this).addClass('is-invalid');
                        $(this).next('.invalid-feedback').remove();
                        $(this).after('<div class="invalid-feedback">Only CSV and TXT files are allowed</div>');
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).addClass('is-valid');
                        $(this).next('.invalid-feedback').remove();
                        
                        // Show file info
                        $(this).next('.form-text').html(
                            'Selected: ' + fileName + ' (' + fileSize + ' MB). File must be in OBDX format with semicolon (;) delimiter.'
                        );
                    }
                }
            });
        });
    </script>
</body>
</html>