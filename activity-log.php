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
    <title>JPI Systems - Activity Log</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatable-extension.css">
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
                                <h3>Activity Log</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Audit & Reports</li>
                                    <li class="breadcrumb-item active">Activity Log</li>
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
                                    <h4>Database Activity Log</h4>
                                    <div class="float-end">
                                        <button class="btn btn-primary" id="exportActivityBtn">
                                            <i class="fa fa-download"></i> Export to CSV
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select class="form-control" id="activityTableFilter">
                                                    <option value="">All Tables</option>
                                                    <option value="users">Users</option>
                                                    <option value="file_batches">File Batches</option>
                                                    <option value="uploaded_files">Uploaded Files</option>
                                                    <option value="certificates">Certificates</option>
                                                    <option value="signed_files">Signed Files</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" id="activityActionFilter">
                                                    <option value="">All Actions</option>
                                                    <option value="INSERT">INSERT</option>
                                                    <option value="UPDATE">UPDATE</option>
                                                    <option value="DELETE">DELETE</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" class="form-control" id="activityDateFilter" placeholder="Date">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" id="activityUserFilter" placeholder="User ID">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="activity-log-table" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>Audit ID</th>
                                                    <th>Table Name</th>
                                                    <th>Record ID</th>
                                                    <th>Action Type</th>
                                                    <th>User ID</th>
                                                    <th>Details</th>
                                                    <th>Timestamp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $audit = new audit_manager();
                                                $audit->list_audit_trail();
                                                ?>
                                            </tbody>
                                        </table>
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
            // Initialize DataTable
            var activityTable = $('#activity-log-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                pageLength: 25,
                order: [[0, 'desc']],
                language: {
                    search: "Search activity:"
                }
            });
            
            // Filter by table name
            $('#activityTableFilter').on('change', function () {
                activityTable.column(1).search(this.value).draw();
            });
            
            // Filter by action type
            $('#activityActionFilter').on('change', function () {
                activityTable.column(3).search(this.value).draw();
            });
            
            // Filter by date
            $('#activityDateFilter').on('change', function () {
                activityTable.column(6).search(this.value).draw();
            });
            
            // Filter by user ID
            $('#activityUserFilter').on('keyup', function () {
                activityTable.column(4).search(this.value).draw();
            });
            
            // Export button
            $('#exportActivityBtn').click(function () {
                window.location.href = './php/audit_handler.php?action=export_trail&format=csv';
            });
        });
    </script>
</body>
</html>