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
    <title>JPI Systems - CSV Files</title>
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
                                <h3>CSV Files</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">CSV Files</li>
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
                                    <h4>All CSV Files</h4>
                                    <div class="float-end">
                                        <a href="./uploadNew.php" class="btn btn-primary">
                                            <i class="fa fa-upload"></i> Upload & Sign New File
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="export-button" role="grid" aria-describedby="export-button_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>ID</th>
                                                    <th>File Name</th>
                                                    <th>File Type</th>
                                                    <th>Status</th>
                                                    <th>Uploaded By</th>
                                                    <th>Upload Date</th>
                                                    <th>File Size</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $files = new file_management();
                                                $files->list_uploaded_files();
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
    
    <script>
        $(document).ready(function () {
            // Handle file download
            $(document).on('click', '.download-file', function (e) {
                e.preventDefault();
                var fileId = $(this).data('id');
                var action = $(this).data('action');
                
                if (action === 'download') {
                    window.location.href = './php/file_handler.php?action=download&id=' + fileId;
                } else if (action === 'download-signed') {
                    window.location.href = './php/file_handler.php?action=download_signed&id=' + fileId;
                }
            });
            
            // Handle file delete
            $(document).on('click', '.delete-file', function (e) {
                e.preventDefault();
                var fileId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/file_handler.php",
                            data: {
                                delete_file: '1',
                                file_id: fileId
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
            
            // Handle file signing
            $(document).on('click', '.sign-file', function (e) {
                e.preventDefault();
                var fileId = $(this).data('id');
                
                Swal.fire({
                    title: 'Sign File?',
                    text: "Do you want to sign this file?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, sign it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/file_handler.php",
                            data: {
                                sign_file: '1',
                                file_id: fileId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.id == 1) {
                                    Swal.fire({
                                        title: "Signed!",
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