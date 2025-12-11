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
    <title>JPI Systems - Data Export</title>
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
                                <h3>Data Export</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Audit & Reports</li>
                                    <li class="breadcrumb-item active">Data Export</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Export System Data</h4>
                                    <p>Export data from various system tables in different formats</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card export-card">
                                                <div class="card-body text-center">
                                                    <i class="fa fa-file-text fa-3x text-primary mb-3"></i>
                                                    <h5>File Batches</h5>
                                                    <p>Export all file batches with their details</p>
                                                    <button class="btn btn-primary export-btn" data-table="file_batches">
                                                        <i class="fa fa-download"></i> Export
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card export-card">
                                                <div class="card-body text-center">
                                                    <i class="fa fa-file-signature fa-3x text-success mb-3"></i>
                                                    <h5>Signed Files</h5>
                                                    <p>Export all signed files information</p>
                                                    <button class="btn btn-success export-btn" data-table="signed_files">
                                                        <i class="fa fa-download"></i> Export
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card export-card">
                                                <div class="card-body text-center">
                                                    <i class="fa fa-shield fa-3x text-warning mb-3"></i>
                                                    <h5>Certificates</h5>
                                                    <p>Export certificate details and status</p>
                                                    <button class="btn btn-warning export-btn" data-table="certificates">
                                                        <i class="fa fa-download"></i> Export
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <div class="card export-card">
                                                <div class="card-body text-center">
                                                    <i class="fa fa-users fa-3x text-info mb-3"></i>
                                                    <h5>Users</h5>
                                                    <p>Export user accounts and permissions</p>
                                                    <button class="btn btn-info export-btn" data-table="users">
                                                        <i class="fa fa-download"></i> Export
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card export-card">
                                                <div class="card-body text-center">
                                                    <i class="fa fa-history fa-3x text-secondary mb-3"></i>
                                                    <h5>Audit Logs</h5>
                                                    <p>Export system audit trail records</p>
                                                    <button class="btn btn-secondary export-btn" data-table="audit_log">
                                                        <i class="fa fa-download"></i> Export
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card export-card">
                                                <div class="card-body text-center">
                                                    <i class="fa fa-database fa-3x text-danger mb-3"></i>
                                                    <h5>All Data</h5>
                                                    <p>Complete system data backup</p>
                                                    <button class="btn btn-danger" id="exportAllBtn">
                                                        <i class="fa fa-download"></i> Full Backup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Custom Export Section -->
                                    <div class="row mt-5">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Custom Data Export</h5>
                                                </div>
                                                <div class="card-body">
                                                    <form id="customExportForm">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Select Table</label>
                                                                    <select class="form-control" id="customTable" required>
                                                                        <option value="">Select Table</option>
                                                                        <option value="file_batches">File Batches</option>
                                                                        <option value="uploaded_files">Uploaded Files</option>
                                                                        <option value="signed_files">Signed Files</option>
                                                                        <option value="certificates">Certificates</option>
                                                                        <option value="users">Users</option>
                                                                        <option value="audit_log">Audit Log</option>
                                                                        <option value="audit_trail">Audit Trail</option>
                                                                        <option value="batch_items">Batch Items</option>
                                                                        <option value="file_types">File Types</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Date Range</label>
                                                                    <div class="input-group">
                                                                        <input type="date" class="form-control" id="customDateFrom" placeholder="From">
                                                                        <span class="input-group-text">to</span>
                                                                        <input type="date" class="form-control" id="customDateTo" placeholder="To">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Export Format</label>
                                                                    <select class="form-control" id="customFormat" required>
                                                                        <option value="csv">CSV</option>
                                                                        <option value="excel">Excel</option>
                                                                        <option value="pdf">PDF</option>
                                                                        <option value="json">JSON</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group mt-3">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fa fa-cogs"></i> Generate Custom Export
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Recent Exports -->
                                    <div class="row mt-5">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Recent Exports</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="dt-ext table-responsive theme-scrollbar">
                                                        <table class="display dataTable" id="recent-exports-table" role="grid">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th>Export ID</th>
                                                                    <th>Table Name</th>
                                                                    <th>Records</th>
                                                                    <th>Format</th>
                                                                    <th>Exported By</th>
                                                                    <th>Export Date</th>
                                                                    <th>File Size</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $export = new export_manager();
                                                                $export->list_recent_exports();
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
            $('#recent-exports-table').DataTable({
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            // Export buttons
            $('.export-btn').click(function () {
                var table = $(this).data('table');
                var format = 'csv';
                
                Swal.fire({
                    title: 'Export Options',
                    html: `
                        <div class="text-start">
                            <label class="form-label">Select Format</label>
                            <select class="form-control" id="exportFormat">
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Export',
                    preConfirm: () => {
                        return {
                            format: $('#exportFormat').val()
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var selectedFormat = result.value.format;
                        window.location.href = `./php/export_handler.php?action=export_table&table=${table}&format=${selectedFormat}`;
                    }
                });
            });
            
            // Export All button
            $('#exportAllBtn').click(function () {
                Swal.fire({
                    title: 'Full System Backup',
                    text: 'This will create a complete backup of all system data. Continue?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Create Backup',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './php/export_handler.php?action=full_backup';
                    }
                });
            });
            
            // Custom Export Form
            $('#customExportForm').submit(function (e) {
                e.preventDefault();
                
                var table = $('#customTable').val();
                var dateFrom = $('#customDateFrom').val();
                var dateTo = $('#customDateTo').val();
                var format = $('#customFormat').val();
                
                if (!table) {
                    Swal.fire('Error', 'Please select a table to export', 'error');
                    return;
                }
                
                // Build URL with parameters
                var url = `./php/export_handler.php?action=export_custom&table=${table}&format=${format}`;
                
                if (dateFrom) {
                    url += `&date_from=${dateFrom}`;
                }
                
                if (dateTo) {
                    url += `&date_to=${dateTo}`;
                }
                
                window.location.href = url;
            });
        });
    </script>
</body>
</html>