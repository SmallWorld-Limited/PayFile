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
    <meta name="keywords" content="File Signing, CSV, PKCS7, Digital Signature">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>Upload Sage File - JPI File Signing System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
</head>
<body>
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <div class="loader-wrapper">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"> </div>
        <div class="dot"></div>
    </div>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-header">
            <?php include "includes/header.php"; ?>
        </div>
        <div class="page-body-wrapper horizontal-menu">
            <div class="sidebar-wrapper">
                <div>
                    <?php include "includes/sidebar.php"; ?>
                </div>
            </div>
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Upload Sage File</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Creation</li>
                                    <li class="breadcrumb-item active">Upload Sage</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Upload Sage Payment File</h4>
                                    <p>Upload your Sage payment file to create signed CSV files</p>
                                </div>
                                <div class="card-body">
                                    <form id="sageUploadForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Select File Type</label>
                                                    <select class="form-control" id="target_file_type" name="target_file_type" required>
                                                        <option value="">-- Select Target File Type --</option>
                                                        <?php
                                                        $dropdowns = new dropdowns();
                                                        $dropdowns->get_file_types();
                                                        ?>
                                                    </select>
                                                    <small class="form-text text-muted">Select the format you want to generate</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">File Reference</label>
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
                                                    <label class="col-form-label">Upload Sage File</label>
                                                    <div class="custom-file">
                                                        <input class="form-control" type="file" id="sage_file" 
                                                               name="sage_file" accept=".csv,.txt,.xlsx,.xls" required>
                                                        <small class="form-text text-muted">
                                                            Supported formats: CSV, TXT, Excel (XLSX, XLS). Max size: 10MB
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">File Delimiter</label>
                                                    <select class="form-control" id="file_delimiter" name="file_delimiter">
                                                        <option value="," selected>Comma (,)</option>
                                                        <option value=";">Semicolon (;)</option>
                                                        <option value="\t">Tab</option>
                                                        <option value="|">Pipe (|)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">File Has Headers?</label>
                                                    <select class="form-control" id="has_headers" name="has_headers">
                                                        <option value="1" selected>Yes, first row contains headers</option>
                                                        <option value="0">No headers</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i>
                                                    <strong>Note:</strong> After uploading, you'll be able to map Sage fields to target fields and preview the generated file.
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
                                                        <button class="btn btn-secondary" type="button" onclick="window.history.back()">Cancel</button>
                                                        <button class="btn btn-primary" id="submit" type="submit">
                                                            <i class="fa fa-upload"></i> Upload & Process
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Sage File Format Guide -->
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Sage File Format Guide</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Expected Sage Columns (Example):</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Column</th>
                                                            <th>Description</th>
                                                            <th>Sample</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Account Number</td>
                                                            <td>Debit account</td>
                                                            <td>13006161244</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Account Name</td>
                                                            <td>Debit account name</td>
                                                            <td>(ORT) MG Other Recurrent Expenditure A/C</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Amount</td>
                                                            <td>Payment amount</td>
                                                            <td>2000000.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Payee Name</td>
                                                            <td>Beneficiary name</td>
                                                            <td>Bella Enterprise Pvt Ltd</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Reference</td>
                                                            <td>Payment reference</td>
                                                            <td>INV001/23</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Supported File Types:</h6>
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <i class="fa fa-file-text text-primary"></i>
                                                    <strong>CSV Files</strong> - Comma or semicolon separated
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="fa fa-file-excel text-success"></i>
                                                    <strong>Excel Files</strong> - XLSX or XLS format
                                                </li>
                                                <li class="list-group-item">
                                                    <i class="fa fa-file-alt text-info"></i>
                                                    <strong>Text Files</strong> - Fixed width or delimited
                                                </li>
                                            </ul>
                                            
                                            <div class="alert alert-warning mt-3">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                <strong>Important:</strong> Ensure your Sage file contains all required fields for the selected file type.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function () {
            // Handle form submission
            $('#sageUploadForm').submit(function (e) {
                e.preventDefault();
                
                var fileInput = $('#sage_file')[0];
                var file = fileInput.files[0];
                
                if (!file) {
                    Swal.fire({
                        title: "No File Selected",
                        text: "Please select a Sage file to upload",
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
                var allowedExtensions = /(\.csv|\.txt|\.xlsx|\.xls)$/i;
                if (!allowedExtensions.exec(file.name)) {
                    Swal.fire({
                        title: "Invalid File Type",
                        text: "Please upload a CSV, TXT, or Excel file",
                        icon: "error"
                    });
                    return;
                }
                
                // Show progress bar
                $('#uploadProgress').removeClass('d-none');
                $('#submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                
                // Create FormData object
                var formData = new FormData();
                formData.append('upload_sage', '1');
                formData.append('target_file_type', $('#target_file_type').val());
                formData.append('file_reference', $('#file_reference').val());
                formData.append('sage_file', file);
                formData.append('file_delimiter', $('#file_delimiter').val());
                formData.append('has_headers', $('#has_headers').val());
                
                // Submit using AJAX
                $.ajax({
                    type: "POST",
                    url: "./php/sage_handler.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
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
                        $('#submit').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Process');
                        $('#uploadProgress').addClass('d-none');
                        
                        if (response.id == 1) {
                            Swal.fire({
                                title: "Success!",
                                html: response.mssg + "<br><br>" +
                                    '<a href="sage-mapping.php?id=' + response.sage_id + '" class="btn btn-primary">Map Fields & Generate</a>',
                                icon: "success",
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                width: '600px'
                            }).then(() => {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: response.mssg,
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#submit').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload & Process');
                        $('#uploadProgress').addClass('d-none');
                        
                        Swal.fire({
                            title: "Upload Failed",
                            text: "Error: " + error,
                            icon: "error"
                        });
                    }
                });
            });
            
            // Validate file reference uniqueness
            $('#file_reference').on('blur', function() {
                var ref = $(this).val();
                if (ref.length > 0) {
                    $.ajax({
                        type: "POST",
                        url: "./php/sage_handler.php",
                        data: {
                            check_reference: '1',
                            file_reference: ref
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.exists) {
                                $('#file_reference').addClass('is-invalid');
                                $('#file_reference').after('<div class="invalid-feedback">' + response.message + '</div>');
                            } else {
                                $('#file_reference').removeClass('is-invalid');
                                $('#file_reference').addClass('is-valid');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>