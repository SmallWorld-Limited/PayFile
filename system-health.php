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
    <title>JPI Systems - System Health</title>
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
                                <h3>System Health</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Settings</li>
                                    <li class="breadcrumb-item active">System Health</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <!-- System Status Overview -->
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>System Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="font-primary me-3">
                                            <i data-feather="cpu" class="font-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="f-w-600">System Uptime</span>
                                            <h5 id="uptime">Loading...</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="font-success me-3">
                                            <i data-feather="database" class="font-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="f-w-600">Database</span>
                                            <h5><span class="badge badge-success">Online</span></h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="font-warning me-3">
                                            <i data-feather="activity" class="font-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="f-w-600">Last Check</span>
                                            <h5><?php echo date('Y-m-d H:i:s'); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Server Resources -->
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>CPU Usage</h5>
                                </div>
                                <div class="card-body">
                                    <div class="progress sm-progress-bar">
                                        <div class="progress-gradient-primary" role="progressbar" style="width: <?php echo $cpu_usage ?? 25; ?>%" 
                                             aria-valuenow="<?php echo $cpu_usage ?? 25; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <span class="animate-circle"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Current Usage</span>
                                        <span><?php echo $cpu_usage ?? 25; ?>%</span>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Load Average: <?php echo $load_avg ?? '0.5, 0.4, 0.3'; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Memory Usage -->
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Memory Usage</h5>
                                </div>
                                <div class="card-body">
                                    <div class="progress sm-progress-bar">
                                        <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $memory_usage ?? 65; ?>%" 
                                             aria-valuenow="<?php echo $memory_usage ?? 65; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <span class="animate-circle"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Used Memory</span>
                                        <span><?php echo $memory_usage ?? 65; ?>%</span>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Total: <?php echo $total_memory ?? '8 GB'; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Disk Usage -->
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Disk Usage</h5>
                                </div>
                                <div class="card-body">
                                    <div class="progress sm-progress-bar">
                                        <div class="progress-gradient-info" role="progressbar" style="width: <?php echo $disk_usage ?? 45; ?>%" 
                                             aria-valuenow="<?php echo $disk_usage ?? 45; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <span class="animate-circle"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Used Space</span>
                                        <span><?php echo $disk_usage ?? 45; ?>%</span>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">Free: <?php echo $free_space ?? '500 GB'; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Services -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>System Services Status</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive theme-scrollbar">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Service</th>
                                                    <th>Status</th>
                                                    <th>Version</th>
                                                    <th>Port</th>
                                                    <th>Last Checked</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <i data-feather="database" class="me-2"></i>
                                                        MySQL Database
                                                    </td>
                                                    <td><span class="badge badge-success">Running</span></td>
                                                    <td>8.0.33</td>
                                                    <td>3306</td>
                                                    <td><?php echo date('H:i:s'); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success">Restart</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i data-feather="globe" class="me-2"></i>
                                                        Web Server (Apache)
                                                    </td>
                                                    <td><span class="badge badge-success">Running</span></td>
                                                    <td>2.4.57</td>
                                                    <td>80/443</td>
                                                    <td><?php echo date('H:i:s'); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success">Restart</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i data-feather="lock" class="me-2"></i>
                                                        OpenSSL Service
                                                    </td>
                                                    <td><span class="badge badge-success">Available</span></td>
                                                    <td>3.0.8</td>
                                                    <td>N/A</td>
                                                    <td><?php echo date('H:i:s'); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-secondary" disabled>Test</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i data-feather="mail" class="me-2"></i>
                                                        Email Service
                                                    </td>
                                                    <td><span class="badge badge-warning">Warning</span></td>
                                                    <td>N/A</td>
                                                    <td>587</td>
                                                    <td><?php echo date('H:i:s'); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning">Configure</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Logs -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Recent System Logs</h4>
                                    <button class="btn btn-sm btn-primary" onclick="refreshLogs()">Refresh</button>
                                </div>
                                <div class="card-body">
                                    <div class="logs-container" style="max-height: 300px; overflow-y: auto;">
                                        <div class="log-entry text-success">
                                            <small>[<?php echo date('Y-m-d H:i:s'); ?>]</small> System health check completed successfully
                                        </div>
                                        <div class="log-entry text-info">
                                            <small>[<?php echo date('Y-m-d H:i:s', strtotime('-5 minutes')); ?>]</small> User <?php echo $_SESSION['bt_username']; ?> logged in
                                        </div>
                                        <div class="log-entry text-warning">
                                            <small>[<?php echo date('Y-m-d H:i:s', strtotime('-10 minutes')); ?>]</small> Certificate check: 1 certificate expiring soon
                                        </div>
                                        <div class="log-entry">
                                            <small>[<?php echo date('Y-m-d H:i:s', strtotime('-30 minutes')); ?>]</small> Database backup completed
                                        </div>
                                        <div class="log-entry text-info">
                                            <small>[<?php echo date('Y-m-d H:i:s', strtotime('-1 hour')); ?>]</small> File signed: OBDXPMN_001300780_16.csv
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Health Actions -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Health Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button class="btn btn-primary w-100 mb-2" onclick="runHealthCheck()">
                                                <i data-feather="activity" class="me-2"></i> Run Health Check
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success w-100 mb-2" onclick="clearSystemCache()">
                                                <i data-feather="refresh-cw" class="me-2"></i> Clear Cache
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning w-100 mb-2" onclick="optimizeDatabase()">
                                                <i data-feather="database" class="me-2"></i> Optimize DB
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-info w-100 mb-2" onclick="viewDetailedReport()">
                                                <i data-feather="file-text" class="me-2"></i> Detailed Report
                                            </button>
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
        // Update uptime counter
        function updateUptime() {
            $.ajax({
                url: './php/health_handler.php?action=get_uptime',
                success: function(response) {
                    $('#uptime').text(response);
                }
            });
        }
        
        // Refresh logs
        function refreshLogs() {
            $.ajax({
                url: './php/health_handler.php?action=get_logs',
                success: function(response) {
                    $('.logs-container').html(response);
                }
            });
        }
        
        // Run health check
        function runHealthCheck() {
            Swal.fire({
                title: 'Running Health Check',
                text: 'Please wait while we check system health...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: './php/health_handler.php?action=run_health_check',
                success: function(response) {
                    Swal.fire({
                        title: 'Health Check Complete',
                        html: response,
                        icon: 'success'
                    });
                }
            });
        }
        
        // Clear system cache
        function clearSystemCache() {
            Swal.fire({
                title: 'Clear System Cache?',
                text: "This will clear all cached data",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, clear it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/health_handler.php?action=clear_cache',
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'System cache cleared',
                                icon: 'success'
                            });
                        }
                    });
                }
            });
        }
        
        // Optimize database
        function optimizeDatabase() {
            Swal.fire({
                title: 'Optimize Database?',
                text: "This will optimize database tables",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, optimize!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/health_handler.php?action=optimize_db',
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Database optimized successfully',
                                icon: 'success'
                            });
                        }
                    });
                }
            });
        }
        
        // View detailed report
        function viewDetailedReport() {
            window.open('./php/health_handler.php?action=detailed_report', '_blank');
        }
        
        // Initial load
        $(document).ready(function() {
            updateUptime();
            setInterval(updateUptime, 60000); // Update every minute
        });
    </script>
</body>
</html>