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
    <title>JPI Systems - User Management</title>
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
                                <h3>User Management</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Users</li>
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
                                    <h4>System Users</h4>
                                    <div class="float-end">
                                        <a href="./addUser.php" class="btn btn-primary">
                                            <i class="fa fa-plus-circle"></i> Add New User
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="users-table" role="grid" aria-describedby="users-table_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>ID</th>
                                                    <th>Username</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Department</th>
                                                    <th>Status</th>
                                                    <th>Last Login</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $admin = new admin();
                                                $conn = new db_connect();
                                                $db = $conn->connect();
                                                
                                                $stmt = $db->prepare("
                                                    SELECT 
                                                        u.user_id,
                                                        u.username,
                                                        u.full_name,
                                                        u.email,
                                                        u.role,
                                                        d.department_name,
                                                        u.enabled,
                                                        u.last_login,
                                                        u.created_at
                                                    FROM users u
                                                    LEFT JOIN departments d ON u.department_id = d.department_id
                                                    WHERE u.deleted = 0
                                                    ORDER BY u.user_id DESC
                                                ");
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $status_badge = $row['enabled'] ? 
                                                            '<span class="badge badge-success">Active</span>' : 
                                                            '<span class="badge badge-danger">Inactive</span>';
                                                        
                                                        $last_login = $row['last_login'] ? 
                                                            date('Y-m-d H:i', strtotime($row['last_login'])) : 
                                                            'Never';
                                                        
                                                        $created_at = date('Y-m-d H:i', strtotime($row['created_at']));
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row['user_id']; ?></td>
                                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['full_name'] ?: 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                            <td><span class="badge badge-info"><?php echo ucfirst($row['role']); ?></span></td>
                                                            <td><?php echo htmlspecialchars($row['department_name'] ?: 'N/A'); ?></td>
                                                            <td><?php echo $status_badge; ?></td>
                                                            <td><?php echo $last_login; ?></td>
                                                            <td><?php echo $created_at; ?></td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-info edit-user" 
                                                                        data-id="<?php echo $row['user_id']; ?>" title="Edit User">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                
                                                                <?php if ($row['user_id'] != $_SESSION["bt_user_id"]): ?>
                                                                    <?php if ($row['enabled']): ?>
                                                                        <button type="button" class="btn btn-sm btn-warning deactivate-user" 
                                                                                data-id="<?php echo $row['user_id']; ?>" title="Deactivate">
                                                                            <i class="fa fa-user-times"></i>
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <button type="button" class="btn btn-sm btn-success activate-user" 
                                                                                data-id="<?php echo $row['user_id']; ?>" title="Activate">
                                                                            <i class="fa fa-user-check"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                    
                                                                    <button type="button" class="btn btn-sm btn-danger delete-user" 
                                                                            data-id="<?php echo $row['user_id']; ?>" title="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                                
                                                                <button type="button" class="btn btn-sm btn-secondary reset-password" 
                                                                        data-id="<?php echo $row['user_id']; ?>" title="Reset Password">
                                                                    <i class="fa fa-key"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="10" class="text-center">No users found.</td>
                                                    </tr>
                                                    <?php
                                                }
                                                $stmt->close();
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
            // Initialize DataTable
            $('#users-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            
            // Handle edit user
            $(document).on('click', '.edit-user', function (e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "./php/user_handler.php",
                    data: {
                        get_user_details: '1',
                        user_id: userId
                    },
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                // Open edit modal with user details
                                openEditUserModal(res.user);
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
                                text: "Failed to load user details",
                                icon: "error"
                            });
                        }
                    }
                });
            });
            
            // Handle activate user
            $(document).on('click', '.activate-user', function (e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                Swal.fire({
                    title: 'Activate User?',
                    text: "Do you want to activate this user?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, activate!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/user_handler.php",
                            data: {
                                activate_user: '1',
                                user_id: userId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: "Activated!",
                                        text: res.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: res.message,
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Handle deactivate user
            $(document).on('click', '.deactivate-user', function (e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                Swal.fire({
                    title: 'Deactivate User?',
                    text: "Do you want to deactivate this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, deactivate!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/user_handler.php",
                            data: {
                                deactivate_user: '1',
                                user_id: userId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: "Deactivated!",
                                        text: res.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: res.message,
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Handle delete user
            $(document).on('click', '.delete-user', function (e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                Swal.fire({
                    title: 'Delete User?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/user_handler.php",
                            data: {
                                delete_user: '1',
                                user_id: userId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: res.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: res.message,
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Handle reset password
            $(document).on('click', '.reset-password', function (e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                Swal.fire({
                    title: 'Reset Password',
                    html: `
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" class="form-control" placeholder="Enter new password">
                        </div>
                        <div class="form-group mt-2">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" class="form-control" placeholder="Confirm new password">
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Reset Password',
                    preConfirm: () => {
                        const password = document.getElementById('new_password').value;
                        const confirm = document.getElementById('confirm_password').value;
                        
                        if (!password || !confirm) {
                            Swal.showValidationMessage('Please fill in both fields');
                            return false;
                        }
                        
                        if (password !== confirm) {
                            Swal.showValidationMessage('Passwords do not match');
                            return false;
                        }
                        
                        if (password.length < 6) {
                            Swal.showValidationMessage('Password must be at least 6 characters');
                            return false;
                        }
                        
                        return { password: password };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/user_handler.php",
                            data: {
                                reset_password: '1',
                                user_id: userId,
                                new_password: result.value.password
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: res.message,
                                        icon: "success"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: res.message,
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Function to open edit user modal
            function openEditUserModal(user) {
                // Create modal HTML
                var modalHtml = `
                    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit User: ${user.username}</h5>
                                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="editUserForm">
                                    <div class="modal-body">
                                        <input type="hidden" name="user_id" value="${user.user_id}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Username</label>
                                                    <input class="form-control" type="text" name="username" value="${user.username}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Full Name</label>
                                                    <input class="form-control" type="text" name="full_name" value="${user.full_name || ''}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Email</label>
                                                    <input class="form-control" type="email" name="email" value="${user.email}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Role</label>
                                                    <select class="form-control" name="role" required>
                                                        <option value="admin" ${user.role == 'admin' ? 'selected' : ''}>Admin</option>
                                                        <option value="approver" ${user.role == 'approver' ? 'selected' : ''}>Approver</option>
                                                        <option value="creator" ${user.role == 'creator' ? 'selected' : ''}>Creator</option>
                                                        <option value="viewer" ${user.role == 'viewer' ? 'selected' : ''}>Viewer</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Department</label>
                                                    <select class="form-control" name="department_id">
                                                        <option value="">-- Select Department --</option>
                                                        <?php
                                                        $dept_stmt = $db->prepare("SELECT department_id, department_name FROM departments ORDER BY department_name");
                                                        $dept_stmt->execute();
                                                        $dept_result = $dept_stmt->get_result();
                                                        while ($dept = $dept_result->fetch_assoc()) {
                                                            echo '<option value="' . $dept['department_id'] . '">' . htmlspecialchars($dept['department_name']) . '</option>';
                                                        }
                                                        $dept_stmt->close();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Status</label>
                                                    <select class="form-control" name="enabled">
                                                        <option value="1" ${user.enabled == 1 ? 'selected' : ''}>Active</option>
                                                        <option value="0" ${user.enabled == 0 ? 'selected' : ''}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn btn-primary" type="submit">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add modal to body
                $('body').append(modalHtml);
                
                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
                
                // Set department if exists
                if (user.department_id) {
                    $('#editUserModal select[name="department_id"]').val(user.department_id);
                }
                
                // Handle form submission
                $('#editUserForm').submit(function(e) {
                    e.preventDefault();
                    
                    $.ajax({
                        type: "POST",
                        url: "./php/user_handler.php",
                        data: $(this).serialize() + '&edit_user=1',
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: res.message,
                                    icon: "success"
                                }).then(() => {
                                    modal.hide();
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: res.message,
                                    icon: "error"
                                });
                            }
                        }
                    });
                });
                
                // Remove modal when hidden
                $('#editUserModal').on('hidden.bs.modal', function () {
                    $(this).remove();
                });
            }
        });
    </script>
</body>
</html>