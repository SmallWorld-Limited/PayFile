<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}
// Check if user has admin or approver role
if (!in_array($_SESSION["bt_role"], ['admin', 'approver'])) {
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
    <title>JPI Systems - Login History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href("assets/css/vendors/flag-icon.css");
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
                                <h3>Login History</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Users</li>
                                    <li class="breadcrumb-item active">Login History</li>
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
                                    <h4>User Login Activity</h4>
                                    <div class="float-end">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                                            <i class="fa fa-filter"></i> Filter
                                        </button>
                                        <button class="btn btn-success" id="exportLogs">
                                            <i class="fa fa-download"></i> Export
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="login-history-table" role="grid" aria-describedby="login-history-table_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>ID</th>
                                                    <th>User</th>
                                                    <th>IP Address</th>
                                                    <th>User Agent</th>
                                                    <th>Login Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $admin = new admin();
                                                
                                                // Build filters from GET parameters
                                                $filters = [];
                                                if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
                                                    $filters['user_id'] = intval($_GET['user_id']);
                                                }
                                                if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
                                                    $filters['date_from'] = $_GET['date_from'];
                                                }
                                                if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
                                                    $filters['date_to'] = $_GET['date_to'];
                                                }
                                                if (isset($_GET['status']) && !empty($_GET['status'])) {
                                                    $filters['status'] = $_GET['status'];
                                                }
                                                
                                                // $logs = $admin->get_login_history($filters);
                                                
                                                if (!empty($logs)) {
                                                    foreach ($logs as $row) {
                                                        $login_time = date('Y-m-d H:i:s', strtotime($row['created_at']));
                                                        $status_badge = $row['action'] == 'login_success' ? 
                                                            '<span class="badge badge-success">Success</span>' : 
                                                            '<span class="badge badge-danger">Failed</span>';
                                                        
                                                        // Truncate user agent for display
                                                        $user_agent = htmlspecialchars($row['user_agent'] ?? '');
                                                        if (strlen($user_agent) > 50) {
                                                            $user_agent = substr($user_agent, 0, 50) . '...';
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row['id']; ?></td>
                                                            <td>
                                                                <strong><?php echo htmlspecialchars($row['username']); ?></strong><br>
                                                                <small><?php echo htmlspecialchars($row['full_name'] ?: 'N/A'); ?></small>
                                                            </td>
                                                            <td><code><?php echo htmlspecialchars($row['ip_address'] ?: 'N/A'); ?></code></td>
                                                            <td><small><?php echo $user_agent; ?></small></td>
                                                            <td><?php echo $login_time; ?></td>
                                                            <td><?php echo $status_badge; ?></td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-info view-details" 
                                                                        data-id="<?php echo $row['id']; ?>" title="View Details">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center">No login records found.</td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Statistics -->
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <div class="card bg-primary text-white">
                                                <div class="card-body">
                                                    <h6>Total Logins</h6>
                                                    <h4>
                                                        <?php
                                                        $stmt = $db->prepare("SELECT COUNT(*) as total FROM audit_log WHERE entity_type = 'login'");
                                                        $stmt->execute();
                                                        $total = $stmt->fetch_assoc();
                                                        echo $total['total'];
                                                        $stmt->close();
                                                        ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-success text-white">
                                                <div class="card-body">
                                                    <h6>Successful Logins</h6>
                                                    <h4>
                                                        <?php
                                                        $stmt = $db->prepare("SELECT COUNT(*) as success FROM audit_log WHERE action = 'login_success'");
                                                        $stmt->execute();
                                                        $success = $stmt->fetch_assoc();
                                                        echo $success['success'];
                                                        $stmt->close();
                                                        ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-danger text-white">
                                                <div class="card-body">
                                                    <h6>Failed Logins</h6>
                                                    <h4>
                                                        <?php
                                                        $stmt = $db->prepare("SELECT COUNT(*) as failed FROM audit_log WHERE action = 'login_failed'");
                                                        $stmt->execute();
                                                        $failed = $stmt->fetch_assoc();
                                                        echo $failed['failed'];
                                                        $stmt->close();
                                                        ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-info text-white">
                                                <div class="card-body">
                                                    <h6>Unique Users</h6>
                                                    <h4>
                                                        <?php
                                                        $stmt = $db->prepare("SELECT COUNT(DISTINCT user_id) as unique_users FROM audit_log WHERE user_id IS NOT NULL");
                                                        $stmt->execute();
                                                        $unique = $stmt->fetch_assoc();
                                                        echo $unique['unique_users'];
                                                        $stmt->close();
                                                        ?>
                                                    </h4>
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
    
    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Login History</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="">
                    <div class="modal-body">
                        <div class="row">
                            <?php if ($_SESSION["bt_role"] == 'admin'): ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">User</label>
                                    <select class="form-control" name="user_id">
                                        <option value="">All Users</option>
                                        <?php
                                        $stmt = $db->prepare("SELECT user_id, username, full_name FROM users WHERE deleted = 0 ORDER BY username");
                                        $stmt->execute();
                                        $users = $stmt->get_result();
                                        while ($user = $users->fetch_assoc()) {
                                            $selected = (isset($_GET['user_id']) && $_GET['user_id'] == $user['user_id']) ? 'selected' : '';
                                            echo '<option value="' . $user['user_id'] . '" ' . $selected . '>' . 
                                                htmlspecialchars($user['username']) . ' - ' . 
                                                htmlspecialchars($user['full_name'] ?: 'N/A') . '</option>';
                                        }
                                        $stmt->close();
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Date From</label>
                                    <input class="form-control" type="date" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Date To</label>
                                    <input class="form-control" type="date" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="login_success" <?php echo (isset($_GET['status']) && $_GET['status'] == 'login_success') ? 'selected' : ''; ?>>Success</option>
                                        <option value="login_failed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'login_failed') ? 'selected' : ''; ?>>Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Apply Filters</button>
                        <a href="./login-history.php" class="btn btn-outline-danger">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#login-history-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                pageLength: 25,
                order: [[4, 'desc']]
            });
            
            // Handle view details
            $(document).on('click', '.view-details', function (e) {
                e.preventDefault();
                var logId = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "./php/user_handler.php",
                    data: {
                        get_login_details: '1',
                        log_id: logId
                    },
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                showLoginDetails(res.log);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: res.message,
                                    icon: "error"
                                });
                            }
                        } catch (e) {
                            console.error("Error:", e, response);
                            Swal.fire({
                                title: "Error",
                                text: "Failed to load login details",
                                icon: "error"
                            });
                        }
                    }
                });
            });
            
            // Handle export logs
            $('#exportLogs').click(function (e) {
                e.preventDefault();
                
                // Build export URL with current filters
                var params = new URLSearchParams(window.location.search);
                params.append('export', 'csv');
                
                window.location.href = './php/user_handler.php?' + params.toString();
            });
            
            function showLoginDetails(log) {
                var detailsHtml = `
                    <div class="login-details">
                        <table class="table table-sm">
                            <tr>
                                <th width="30%">User:</th>
                                <td>${log.username} (${log.full_name || 'N/A'})</td>
                            </tr>
                            <tr>
                                <th>IP Address:</th>
                                <td><code>${log.ip_address || 'N/A'}</code></td>
                            </tr>
                            <tr>
                                <th>Login Time:</th>
                                <td>${log.created_at}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>${log.action === 'login_success' ? '<span class="badge badge-success">Success</span>' : '<span class="badge badge-danger">Failed</span>'}</td>
                            </tr>
                            <tr>
                                <th>User Agent:</th>
                                <td><small>${log.user_agent || 'N/A'}</small></td>
                            </tr>
                            ${log.details ? `
                            <tr>
                                <th>Details:</th>
                                <td>${log.details}</td>
                            </tr>
                            ` : ''}
                        </table>
                    </div>
                `;
                
                Swal.fire({
                    title: 'Login Details',
                    html: detailsHtml,
                    width: '700px',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            }
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>