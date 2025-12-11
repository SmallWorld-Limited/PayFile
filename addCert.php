<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
    header("Location: ./login.php");
}

// Check if editing existing certificate
$edit_mode = isset($_GET['edit']);
$cert_id = $edit_mode ? intval($_GET['edit']) : 0;
$certificate_data = null;

if ($edit_mode && $cert_id > 0) {
    $cert_mgmt = new certificate_management();
    $certificate_data = $cert_mgmt->get_certificate_details($cert_id);
    
    if (!$certificate_data) {
        header("Location: ./certs.php");
        exit;
    }
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
    <title>JPI Systems - <?php echo $edit_mode ? 'Edit' : 'Add'; ?> Certificate</title>
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
                                <h3><?php echo $edit_mode ? 'Edit Certificate' : 'Add New Certificate'; ?></h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="./certs.php">Certificates</a></li>
                                    <li class="breadcrumb-item active"><?php echo $edit_mode ? 'Edit' : 'Add'; ?></li>
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
                                    <h4>Certificate Information</h4>
                                    <p><?php echo $edit_mode ? 'Update certificate details below' : 'Fill in the certificate details below'; ?></p>
                                </div>
                                <div class="card-body">
                                    <form id="certForm">
                                        <?php if ($edit_mode): ?>
                                            <input type="hidden" id="cert_id" name="cert_id" value="<?php echo $cert_id; ?>">
                                        <?php endif; ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Certificate Name *</label>
                                                    <input class="form-control" type="text" id="certificate_name" 
                                                           name="certificate_name" required
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['certificate_name']) : ''; ?>"
                                                           placeholder="e.g., JPI Production Certificate">
                                                    <small class="form-text text-muted">A descriptive name for this certificate</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Certificate Type *</label>
                                                    <select class="form-control" id="certificate_type" name="certificate_type" required>
                                                        <option value="">-- Select Type --</option>
                                                        <option value="signing" <?php echo ($edit_mode && $certificate_data['certificate_type'] == 'signing') ? 'selected' : ''; ?>>Signing Certificate</option>
                                                        <option value="verification" <?php echo ($edit_mode && $certificate_data['certificate_type'] == 'verification') ? 'selected' : ''; ?>>Verification Certificate</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Certificate File Path *</label>
                                                    <input class="form-control" type="text" id="certificate_path" 
                                                           name="certificate_path" required
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['certificate_path']) : ''; ?>"
                                                           placeholder="e.g., C:\certificates\cert.pem">
                                                    <small class="form-text text-muted">Full path to the certificate file (PEM format)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Private Key File Path *</label>
                                                    <input class="form-control" type="text" id="private_key_path" 
                                                           name="private_key_path" required
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['private_key_path']) : ''; ?>"
                                                           placeholder="e.g., C:\certificates\key.pem">
                                                    <small class="form-text text-muted">Full path to the private key file</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Certificate Password</label>
                                                    <input class="form-control" type="password" id="certificate_password" 
                                                           name="certificate_password"
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['certificate_password']) : ''; ?>"
                                                           placeholder="Leave empty if no password">
                                                    <small class="form-text text-muted">Password for the private key (if protected)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Serial Number</label>
                                                    <input class="form-control" type="text" id="serial_number" 
                                                           name="serial_number"
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['serial_number']) : ''; ?>"
                                                           placeholder="Auto-detected from certificate">
                                                    <small class="form-text text-muted">Certificate serial number</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Issuer</label>
                                                    <input class="form-control" type="text" id="issuer" 
                                                           name="issuer"
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['issuer']) : ''; ?>"
                                                           placeholder="e.g., JPI CA">
                                                    <small class="form-text text-muted">Certificate issuer (CN or O)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Subject</label>
                                                    <input class="form-control" type="text" id="subject" 
                                                           name="subject"
                                                           value="<?php echo $edit_mode ? htmlspecialchars($certificate_data['subject']) : ''; ?>"
                                                           placeholder="e.g., JPI Systems">
                                                    <small class="form-text text-muted">Certificate subject (CN or O)</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Valid From</label>
                                                    <input class="form-control" type="datetime-local" id="valid_from" 
                                                           name="valid_from"
                                                           value="<?php echo $edit_mode ? date('Y-m-d\TH:i', strtotime($certificate_data['valid_from'])) : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Valid To</label>
                                                    <input class="form-control" type="datetime-local" id="valid_to" 
                                                           name="valid_to"
                                                           value="<?php echo $edit_mode ? date('Y-m-d\TH:i', strtotime($certificate_data['valid_to'])) : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" id="is_default" name="is_default" 
                                                               value="1" <?php echo ($edit_mode && $certificate_data['is_default']) ? 'checked' : ''; ?>>
                                                        <label for="is_default">Set as default certificate</label>
                                                    </div>
                                                    <small class="form-text text-muted">This certificate will be used by default for signing operations</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-primary">
                                                        <input type="checkbox" id="is_active" name="is_active" 
                                                               value="1" <?php echo ($edit_mode ? ($certificate_data['is_active'] ? 'checked' : '') : 'checked'); ?>>
                                                        <label for="is_active">Certificate is active</label>
                                                    </div>
                                                    <small class="form-text text-muted">Inactive certificates cannot be used for signing</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="text-end mt-3">
                                                        <button class="btn btn-secondary" type="button" onclick="window.location.href='./certs.php'">Cancel</button>
                                                        <button class="btn btn-primary" id="submit" type="submit">
                                                            <i class="fa fa-save"></i> 
                                                            <?php echo $edit_mode ? 'Update Certificate' : 'Add Certificate'; ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <?php if (!$edit_mode): ?>
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Certificate File Formats</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Certificate File (PEM format)</h6>
                                            <pre class="bg-light p-3" style="font-size: 12px;">
-----BEGIN CERTIFICATE-----
MIIE... (certificate data)
-----END CERTIFICATE-----</pre>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Private Key File (PEM format)</h6>
                                            <pre class="bg-light p-3" style="font-size: 12px;">
-----BEGIN PRIVATE KEY-----
MIIE... (private key data)
-----END PRIVATE KEY-----</pre>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mt-3">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        <strong>Security Notice:</strong> 
                                        Ensure certificate files are stored in a secure location with proper file permissions.
                                        Do not store certificates in publicly accessible directories.
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
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
            // Auto-detect certificate details when path is entered
            $('#certificate_path').on('blur', function() {
                var certPath = $(this).val();
                if (certPath && !certPath.includes('example') && $('#issuer').val() === '') {
                    // You could implement AJAX call here to parse certificate details
                    // For now, we'll just show a message
                    if (certPath.toLowerCase().endsWith('.pem') || certPath.toLowerCase().endsWith('.crt')) {
                        $('#certificate_path').addClass('is-valid');
                    }
                }
            });
            
            // Handle form submission
            $('#certForm').submit(function (e) {
                e.preventDefault();
                
                var formData = $(this).serialize();
                
                // Add action based on edit mode
                if (<?php echo $edit_mode ? 'true' : 'false'; ?>) {
                    formData += '&edit_cert=1';
                } else {
                    formData += '&add_cert=1';
                }
                
                // Validate required fields
                var requiredFields = ['certificate_name', 'certificate_path', 'private_key_path', 'certificate_type'];
                var isValid = true;
                
                for (var i = 0; i < requiredFields.length; i++) {
                    var field = $('#' + requiredFields[i]);
                    if (!field.val().trim()) {
                        field.addClass('is-invalid');
                        isValid = false;
                    } else {
                        field.removeClass('is-invalid');
                    }
                }
                
                if (!isValid) {
                    Swal.fire({
                        title: "Validation Error",
                        text: "Please fill in all required fields",
                        icon: "error"
                    });
                    return;
                }
                
                // Validate file paths
                var certPath = $('#certificate_path').val();
                var keyPath = $('#private_key_path').val();
                
                if (!certPath.toLowerCase().endsWith('.pem') && !certPath.toLowerCase().endsWith('.crt')) {
                    Swal.fire({
                        title: "Invalid Certificate File",
                        text: "Certificate file must be in PEM or CRT format",
                        icon: "warning"
                    });
                    return;
                }
                
                // Show loading
                $('#submit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                
                // Submit form
                $.ajax({
                    type: "POST",
                    url: "./php/cert_handler.php",
                    data: formData,
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.id == 1) {
                                Swal.fire({
                                    title: "Success!",
                                    text: res.mssg,
                                    icon: res.type,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = './certs.php';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: res.mssg,
                                    icon: res.type
                                });
                                $('#submit').prop('disabled', false).html('<i class="fa fa-save"></i> <?php echo $edit_mode ? "Update Certificate" : "Add Certificate"; ?>');
                            }
                        } catch (e) {
                            Swal.fire({
                                title: "Error",
                                text: "Invalid response from server",
                                icon: "error"
                            });
                            $('#submit').prop('disabled', false).html('<i class="fa fa-save"></i> <?php echo $edit_mode ? "Update Certificate" : "Add Certificate"; ?>');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to connect to server",
                            icon: "error"
                        });
                        $('#submit').prop('disabled', false).html('<i class="fa fa-save"></i> <?php echo $edit_mode ? "Update Certificate" : "Add Certificate"; ?>');
                    }
                });
            });
            
            // Remove validation class on input
            $('input, select').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
            
            // Test certificate button (optional feature)
            $('#test-certificate').click(function() {
                var certPath = $('#certificate_path').val();
                var keyPath = $('#private_key_path').val();
                var password = $('#certificate_password').val();
                
                if (!certPath || !keyPath) {
                    Swal.fire({
                        title: "Missing Paths",
                        text: "Please enter certificate and key paths first",
                        icon: "warning"
                    });
                    return;
                }
                
                $.ajax({
                    type: "POST",
                    url: "./php/cert_handler.php",
                    data: {
                        test_cert: '1',
                        cert_path: certPath,
                        key_path: keyPath,
                        cert_password: password
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        Swal.fire({
                            title: res.id == 1 ? "Success" : "Error",
                            text: res.mssg,
                            icon: res.type
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>