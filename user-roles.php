<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}
// Check if user has admin role
if ($_SESSION["bt_role"] != 'admin') {
    header("Location: ./");
    exit;
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
    <title>JPI Systems - User Roles & Permissions</title>
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
                                <h3>User Roles & Permissions</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Users</li>
                                    <li class="breadcrumb-item active">Roles & Permissions</li>
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
                                    <h4>Role Definitions</h4>
                                    <p>System roles and their permissions</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="role-card card">
                                                <div class="card-header bg-primary">
                                                    <h5 class="text-white mb-0">Administrator</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text">Full system access and control.</p>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> User Management</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> Certificate Management</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> File Upload & Signing</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> System Configuration</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> View All Data</li>
                                                    </ul>
                                                    <?php
                                                    $conn = new db_connect();
                                                    $db = $conn->connect();
                                                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin' AND deleted = 0");
                                                    $stmt->execute();
                                                    $result = $stmt->fetch_assoc();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="mt-3">
                                                        <span class="badge badge-light"><?php echo $result['count']; ?> Users</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="role-card card">
                                                <div class="card-header bg-success">
                                                    <h5 class="text-white mb-0">Approver</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text">Can approve/reject files for signing.</p>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> Approve/Reject Files</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> File Upload & Signing</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> User Management</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> Certificate Management</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> View All Data</li>
                                                    </ul>
                                                    <?php
                                                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'approver' AND deleted = 0");
                                                    $stmt->execute();
                                                    $result = $stmt->fetch_assoc();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="mt-3">
                                                        <span class="badge badge-light"><?php echo $result['count']; ?> Users</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="role-card card">
                                                <div class="card-header bg-info">
                                                    <h5 class="text-white mb-0">Creator</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text">Can create and upload files.</p>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> File Upload & Signing</li>
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> View Own Files</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> Approve/Reject Files</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> User Management</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> View All Data</li>
                                                    </ul>
                                                    <?php
                                                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'creator' AND deleted = 0");
                                                    $stmt->execute();
                                                    $result = $stmt->fetch_assoc();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="mt-3">
                                                        <span class="badge badge-light"><?php echo $result['count']; ?> Users</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="role-card card">
                                                <div class="card-header bg-secondary">
                                                    <h5 class="text-white mb-0">Viewer</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text">Read-only access to view files.</p>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><i class="fa fa-check text-success"></i> View Own Files</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> File Upload & Signing</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> Approve/Reject Files</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> User Management</li>
                                                        <li class="list-group-item"><i class="fa fa-times text-danger"></i> View All Data</li>
                                                    </ul>
                                                    <?php
                                                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'viewer' AND deleted = 0");
                                                    $stmt->execute();
                                                    $result = $stmt->fetch_assoc();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="mt-3">
                                                        <span class="badge badge-light"><?php echo $result['count']; ?> Users</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Detailed Permissions Matrix</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th>Permission</th>
                                                                    <th class="text-center">Admin</th>
                                                                    <th class="text-center">Approver</th>
                                                                    <th class="text-center">Creator</th>
                                                                    <th class="text-center">Viewer</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>User Management</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Certificate Management</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>File Upload</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>File Signing</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Approve/Reject Files</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>View All Files</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>View Own Files</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>System Configuration</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Audit Logs Access</td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                    <td class="text-center"><i class="fa fa-times text-danger"></i></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Role Distribution</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <canvas id="roleDistributionChart" height="250"></canvas>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="role-stats">
                                                                <h6>User Statistics</h6>
                                                                <ul class="list-group">
                                                                    <?php
                                                                    $conn = new db_connect();
                                                                    $db = $conn->connect();
                                                                    
                                                                    // Get total users
                                                                    $stmt = $db->prepare("SELECT COUNT(*) as total FROM users WHERE deleted = 0");
                                                                    $stmt->execute();
                                                                    $total = $stmt->fetch_assoc();
                                                                    $stmt->close();
                                                                    
                                                                    // Get active users
                                                                    $stmt = $db->prepare("SELECT COUNT(*) as active FROM users WHERE enabled = 1 AND deleted = 0");
                                                                    $stmt->execute();
                                                                    $active = $stmt->fetch_assoc();
                                                                    $stmt->close();
                                                                    
                                                                    // Get inactive users
                                                                    $stmt = $db->prepare("SELECT COUNT(*) as inactive FROM users WHERE enabled = 0 AND deleted = 0");
                                                                    $stmt->execute();
                                                                    $inactive = $stmt->fetch_assoc();
                                                                    $stmt->close();
                                                                    ?>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        Total Users
                                                                        <span class="badge badge-primary badge-pill"><?php echo $total['total']; ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        Active Users
                                                                        <span class="badge badge-success badge-pill"><?php echo $active['active']; ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        Inactive Users
                                                                        <span class="badge badge-danger badge-pill"><?php echo $inactive['inactive']; ?></span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        Admin Users
                                                                        <?php
                                                                        $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin' AND deleted = 0");
                                                                        $stmt->execute();
                                                                        $admin_count = $stmt->fetch_assoc();
                                                                        $stmt->close();
                                                                        ?>
                                                                        <span class="badge badge-primary badge-pill"><?php echo $admin_count['count']; ?></span>
                                                                    </li>
                                                                </ul>
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
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    <?php include "includes/scripts.php"; ?>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function () {
            // Get role distribution data
            $.ajax({
                type: "POST",
                url: "./php/user_handler.php",
                data: { get_role_stats: '1' },
                success: function(response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            createRoleDistributionChart(res.data);
                        } else {
                            // If AJAX fails, use static data from PHP
                            useStaticData();
                        }
                    } catch (e) {
                        console.error("Error loading role stats:", e, response);
                        // Use static data if AJAX fails
                        useStaticData();
                    }
                },
                error: function() {
                    useStaticData();
                }
            });
            
            function useStaticData() {
                // Get static data from PHP
                var labels = [];
                var data = [];
                var colors = [];
                
                // Extract data from role cards
                $('.role-card').each(function() {
                    var role = $(this).find('.card-header h5').text().trim();
                    var count = $(this).find('.badge-light').text().trim();
                    
                    // Map role names
                    var roleMap = {
                        'Administrator': 'Admin',
                        'Approver': 'Approver',
                        'Creator': 'Creator',
                        'Viewer': 'Viewer'
                    };
                    
                    var colorMap = {
                        'Admin': '#007bff',
                        'Approver': '#28a745',
                        'Creator': '#17a2b8',
                        'Viewer': '#6c757d'
                    };
                    
                    var mappedRole = roleMap[role] || role;
                    
                    labels.push(mappedRole);
                    data.push(parseInt(count) || 0);
                    colors.push(colorMap[mappedRole] || '#6c757d');
                });
                
                createRoleDistributionChart({
                    labels: labels,
                    data: data,
                    colors: colors
                });
            }
            
            function createRoleDistributionChart(chartData) {
                var ctx = document.getElementById('roleDistributionChart').getContext('2d');
                if (!ctx) {
                    console.error("Canvas element not found");
                    return;
                }
                
                var roleChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            data: chartData.data,
                            backgroundColor: chartData.colors,
                            borderWidth: 1,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.raw || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return label + ': ' + value + ' user' + (value !== 1 ? 's' : '') + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
                
                // Store chart instance for potential updates
                window.roleDistributionChart = roleChart;
            }
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>