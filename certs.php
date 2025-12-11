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
    <title>JPI Systems - Certificates</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatable-extension.css">
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
                                <h3>Certificates</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Certificates</li>
                                    <li class="breadcrumb-item active">View All</li>
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
                                    <h4>Certificate Management</h4>
                                    <div class="float-end">
                                        <?php if ($_SESSION["bt_role"] == 'admin'): ?>
                                            <a href="./addCert.php" class="btn btn-primary">
                                                <i class="fa fa-plus-circle"></i> Add New Certificate
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if ($_SESSION["bt_role"] == 'admin'): ?>
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> 
                                            <strong>Certificate Storage:</strong> 
                                            Certificate files are stored on the server filesystem. 
                                            Ensure proper permissions and security for certificate storage locations.
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="certificates-table" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>ID</th>
                                                    <th>Certificate Name</th>
                                                    <th>Issuer</th>
                                                    <th>Subject</th>
                                                    <th>Type</th>
                                                    <th>Valid From</th>
                                                    <th>Valid To</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $certs = new certificate_management();
                                                $certs->list_certificates();
                                                ?>
                                            </tbody>
                                        </table>
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
    
    <!-- Certificate Details Modal -->
    <div class="modal fade" id="certDetailsModal" tabindex="-1" aria-labelledby="certDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="certDetailsModalLabel">Certificate Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Certificate Name:</th>
                                    <td id="detail-name"></td>
                                </tr>
                                <tr>
                                    <th>Issuer:</th>
                                    <td id="detail-issuer"></td>
                                </tr>
                                <tr>
                                    <th>Subject:</th>
                                    <td id="detail-subject"></td>
                                </tr>
                                <tr>
                                    <th>Serial Number:</th>
                                    <td id="detail-serial"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Validity & Status</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Valid From:</th>
                                    <td id="detail-valid-from"></td>
                                </tr>
                                <tr>
                                    <th>Valid To:</th>
                                    <td id="detail-valid-to"></td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td id="detail-type"></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td id="detail-status"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>File Paths</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="30%">Certificate File:</th>
                                    <td id="detail-cert-path" class="text-truncate"></td>
                                </tr>
                                <tr>
                                    <th>Private Key File:</th>
                                    <td id="detail-key-path" class="text-truncate"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            // Handle view certificate details
            $(document).on('click', '.view-cert', function (e) {
                e.preventDefault();
                var certId = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "./php/cert_handler.php",
                    data: {
                        get_cert_details: '1',
                        cert_id: certId
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.id == 1) {
                            var cert = res.certificate;
                            
                            // Populate modal with certificate details
                            $('#detail-name').text(cert.certificate_name);
                            $('#detail-issuer').text(cert.issuer);
                            $('#detail-subject').text(cert.subject);
                            $('#detail-serial').text(cert.serial_number || 'N/A');
                            $('#detail-valid-from').text(cert.valid_from);
                            $('#detail-valid-to').text(cert.valid_to);
                            $('#detail-type').text(cert.certificate_type);
                            
                            // Build status text
                            var statusHtml = '';
                            if (cert.is_default) statusHtml += '<span class="badge badge-success">Default</span> ';
                            if (cert.is_active) {
                                statusHtml += '<span class="badge badge-success">Active</span>';
                            } else {
                                statusHtml += '<span class="badge badge-danger">Inactive</span>';
                            }
                            $('#detail-status').html(statusHtml);
                            
                            $('#detail-cert-path').text(cert.certificate_path);
                            $('#detail-key-path').text(cert.private_key_path);
                            
                            // Show modal
                            $('#certDetailsModal').modal('show');
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.mssg,
                                icon: "error"
                            });
                        }
                    }
                });
            });
            
            // Handle edit certificate
            $(document).on('click', '.edit-cert', function (e) {
                e.preventDefault();
                var certId = $(this).data('id');
                window.location.href = './addCert.php?edit=' + certId;
            });
            
            // Handle delete certificate
            $(document).on('click', '.delete-cert', function (e) {
                e.preventDefault();
                var certId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the certificate!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/cert_handler.php",
                            data: {
                                delete_cert: '1',
                                cert_id: certId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.id == 1) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: res.mssg,
                                        icon: res.type
                                    });
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: res.mssg,
                                        icon: res.type
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Handle set default certificate
            $(document).on('click', '.set-default-cert', function (e) {
                e.preventDefault();
                var certId = $(this).data('id');
                
                Swal.fire({
                    title: 'Set as Default?',
                    text: "This certificate will be used as default for signing operations",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, set as default!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/cert_handler.php",
                            data: {
                                set_default_cert: '1',
                                cert_id: certId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.id == 1) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: res.mssg,
                                        icon: res.type
                                    });
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: res.mssg,
                                        icon: res.type
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>