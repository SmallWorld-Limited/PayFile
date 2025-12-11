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
    <title>JPI Systems - Backup & Restore</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
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
                                <h3>Backup & Restore</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Settings</li>
                                    <li class="breadcrumb-item active">Backup & Restore</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <!-- Backup Statistics -->
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Total Backups</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-primary me-3">
                                            <i data-feather="hard-drive" class="font-primary" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3 id="totalBackups">0</h3>
                                            <span class="f-w-600">Backup Files</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Total Size</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-success me-3">
                                            <i data-feather="database" class="font-success" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3 id="totalSize">0 MB</h3>
                                            <span class="f-w-600">Disk Usage</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Last Backup</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-info me-3">
                                            <i data-feather="calendar" class="font-info" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 id="lastBackup">Never</h5>
                                            <span class="f-w-600">Date & Time</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Auto Backup</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-warning me-3">
                                            <i data-feather="refresh-cw" class="font-warning" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5><span class="badge badge-success" id="autoBackupStatus">Enabled</span></h5>
                                            <span class="f-w-600">Status</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Backup Actions -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Backup Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="backup-action-card text-center">
                                                <div class="action-icon mb-3">
                                                    <i data-feather="save" class="font-primary" style="width: 60px; height: 60px;"></i>
                                                </div>
                                                <h5>Database Backup</h5>
                                                <p class="text-muted">Backup only database</p>
                                                <button class="btn btn-primary w-100" onclick="createDatabaseBackup()">
                                                    Create Backup
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="backup-action-card text-center">
                                                <div class="action-icon mb-3">
                                                    <i data-feather="hard-drive" class="font-success" style="width: 60px; height: 60px;"></i>
                                                </div>
                                                <h5>Full Backup</h5>
                                                <p class="text-muted">Database + Files</p>
                                                <button class="btn btn-success w-100" onclick="createFullBackup()">
                                                    Create Full Backup
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="backup-action-card text-center">
                                                <div class="action-icon mb-3">
                                                    <i data-feather="download" class="font-info" style="width: 60px; height: 60px;"></i>
                                                </div>
                                                <h5>Restore</h5>
                                                <p class="text-muted">Restore from backup</p>
                                                <button class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#restoreModal">
                                                    Restore System
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="backup-action-card text-center">
                                                <div class="action-icon mb-3">
                                                    <i data-feather="settings" class="font-warning" style="width: 60px; height: 60px;"></i>
                                                </div>
                                                <h5>Settings</h5>
                                                <p class="text-muted">Configure backup</p>
                                                <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                                    Configure
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Backup History -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Backup History</h4>
                                    <button class="btn btn-sm btn-primary" onclick="refreshBackupList()">
                                        <i data-feather="refresh-cw" class="me-2"></i> Refresh
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="backup-table">
                                            <thead>
                                                <tr>
                                                    <th>Backup Name</th>
                                                    <th>Type</th>
                                                    <th>Size</th>
                                                    <th>Created Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="backupList">
                                                <!-- Backup list will be loaded here -->
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
    
    <!-- Restore Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Restore System</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="restoreForm">
                        <div class="form-group">
                            <label class="col-form-label">Select Backup File</label>
                            <select class="form-control" id="backupFile" name="backupFile" required>
                                <option value="">-- Select Backup File --</option>
                                <!-- Options will be populated via JavaScript -->
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label class="col-form-label">Restore Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="restoreType" id="restoreDatabase" value="database" checked>
                                <label class="form-check-label" for="restoreDatabase">Database Only</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="restoreType" id="restoreFull" value="full">
                                <label class="form-check-label" for="restoreFull">Full Restore (Database + Files)</label>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmRestore" required>
                                <label class="form-check-label" for="confirmRestore">
                                    I understand this will overwrite existing data
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" onclick="performRestore()">Restore Now</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Backup Settings</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="backupSettingsForm">
                        <div class="form-group">
                            <label class="col-form-label">Auto Backup Schedule</label>
                            <select class="form-control" id="backupSchedule" name="backupSchedule">
                                <option value="disabled">Disabled</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label class="col-form-label">Backup Retention (Days)</label>
                            <input class="form-control" type="number" id="backupRetention" name="backupRetention" 
                                   min="1" max="365" value="30">
                        </div>
                        <div class="form-group mt-3">
                            <label class="col-form-label">Backup Location</label>
                            <input class="form-control" type="text" id="backupLocation" name="backupLocation" 
                                   value="../backups/">
                        </div>
                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="backupEmail" name="backupEmail">
                                <label class="form-check-label" for="backupEmail">
                                    Email notification after backup
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="button" onclick="saveBackupSettings()">Save Settings</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
        // Load backup statistics
        function loadBackupStats() {
            $.ajax({
                url: './php/backup_handler.php?action=get_stats',
                success: function(response) {
                    try {
                        var stats = JSON.parse(response);
                        $('#totalBackups').text(stats.total_backups);
                        $('#totalSize').text(stats.total_size + ' MB');
                        $('#lastBackup').text(stats.last_backup);
                        $('#autoBackupStatus').text(stats.auto_backup ? 'Enabled' : 'Disabled');
                        $('#autoBackupStatus').removeClass('badge-success badge-danger')
                            .addClass(stats.auto_backup ? 'badge-success' : 'badge-danger');
                    } catch (e) {
                        console.error('Error loading backup stats:', e);
                    }
                }
            });
        }
        
        // Load backup list
        function loadBackupList() {
            $.ajax({
                url: './php/backup_handler.php?action=get_list',
                success: function(response) {
                    $('#backupList').html(response);
                }
            });
        }
        
        // Create database backup
        function createDatabaseBackup() {
            Swal.fire({
                title: 'Create Database Backup?',
                text: "This will create a backup of the database only",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, backup now!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Creating Backup',
                        text: 'Please wait while we create the backup...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: './php/backup_handler.php?action=create_db_backup',
                        success: function(response) {
                            try {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        html: 'Backup created successfully!<br><br>' +
                                              '<a href="' + res.download_url + '" class="btn btn-primary">Download Backup</a>',
                                        icon: 'success'
                                    });
                                    loadBackupStats();
                                    loadBackupList();
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: res.message,
                                        icon: 'error'
                                    });
                                }
                            } catch (e) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to create backup',
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            });
        }
        
        // Create full backup
        function createFullBackup() {
            Swal.fire({
                title: 'Create Full Backup?',
                text: "This will create a backup of database and files",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, backup everything!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Creating Full Backup',
                        text: 'This may take several minutes...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: './php/backup_handler.php?action=create_full_backup',
                        success: function(response) {
                            try {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        html: 'Full backup created successfully!<br><br>' +
                                              '<a href="' + res.download_url + '" class="btn btn-primary">Download Backup</a>',
                                        icon: 'success'
                                    });
                                    loadBackupStats();
                                    loadBackupList();
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: res.message,
                                        icon: 'error'
                                    });
                                }
                            } catch (e) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to create full backup',
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            });
        }
        
        // Perform restore
        function performRestore() {
            if (!$('#confirmRestore').is(':checked')) {
                Swal.fire({
                    title: 'Confirmation Required',
                    text: 'Please confirm that you understand this will overwrite existing data',
                    icon: 'warning'
                });
                return;
            }
            
            Swal.fire({
                title: 'Restore System?',
                text: "WARNING: This will overwrite existing data!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, restore now!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/backup_handler.php?action=restore',
                        method: 'POST',
                        data: $('#restoreForm').serialize(),
                        success: function(response) {
                            try {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'System restored successfully. Page will reload.',
                                        icon: 'success'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: res.message,
                                        icon: 'error'
                                    });
                                }
                            } catch (e) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to restore system',
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            });
        }
        
        // Save backup settings
        function saveBackupSettings() {
            $.ajax({
                url: './php/backup_handler.php?action=save_settings',
                method: 'POST',
                data: $('#backupSettingsForm').serialize(),
                success: function(response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Backup settings saved',
                                icon: 'success'
                            });
                            $('#settingsModal').modal('hide');
                            loadBackupStats();
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: res.message,
                                icon: 'error'
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to save settings',
                            icon: 'error'
                        });
                    }
                }
            });
        }
        
        // Refresh backup list
        function refreshBackupList() {
            loadBackupList();
            loadBackupStats();
        }
        
        // Initial load
        $(document).ready(function() {
            loadBackupStats();
            loadBackupList();
            
            // Load backup files for restore modal
            $.ajax({
                url: './php/backup_handler.php?action=get_backup_files',
                success: function(response) {
                    $('#backupFile').html(response);
                }
            });
        });
    </script>
</body>
</html>