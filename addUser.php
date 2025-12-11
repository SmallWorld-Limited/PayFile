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
    <title>JPI Systems - Add New User</title>
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
                                <h3>Add New User</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Users</li>
                                    <li class="breadcrumb-item active">Add New</li>
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
                                    <h4>Create New User Account</h4>
                                    <p>Fill in the details below to create a new user account</p>
                                </div>
                                <div class="card-body">
                                    <form id="addUserForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Username *</label>
                                                    <input class="form-control" type="text" id="username" name="username" required placeholder="Enter username">
                                                    <small class="form-text text-muted">Unique username for login</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Full Name</label>
                                                    <input class="form-control" type="text" id="full_name" name="full_name" placeholder="Enter full name">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Email Address *</label>
                                                    <input class="form-control" type="email" id="email" name="email" required placeholder="Enter email address">
                                                    <small class="form-text text-muted">User's email for notifications</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Role *</label>
                                                    <select class="form-control" id="role" name="role" required>
                                                        <option value="">-- Select Role --</option>
                                                        <option value="admin">Administrator</option>
                                                        <option value="approver">Approver</option>
                                                        <option value="creator">Creator</option>
                                                        <option value="viewer">Viewer</option>
                                                    </select>
                                                    <small class="form-text text-muted">User permissions level</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Department</label>
                                                    <select class="form-control" id="department_id" name="department_id">
                                                        <option value="">-- Select Department --</option>
                                                        <?php
                                                        $conn = new db_connect();
                                                        $db = $conn->connect();
                                                        $stmt = $db->prepare("SELECT department_id, department_name FROM departments ORDER BY department_name");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="' . $row['department_id'] . '">' . htmlspecialchars($row['department_name']) . '</option>';
                                                        }
                                                        $stmt->close();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Status</label>
                                                    <select class="form-control" id="enabled" name="enabled">
                                                        <option value="1" selected>Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Password *</label>
                                                    <input class="form-control" type="password" id="password" name="password" required placeholder="Enter password">
                                                    <small class="form-text text-muted">Minimum 6 characters</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Confirm Password *</label>
                                                    <input class="form-control" type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="text-end mt-3">
                                                        <button class="btn btn-secondary" type="button" onclick="window.history.back()">Cancel</button>
                                                        <button class="btn btn-primary" id="submit" type="submit">Create User</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
            // Handle form submission
            $('#addUserForm').submit(function (e) {
                e.preventDefault();
                
                // Validate passwords match
                var password = $('#password').val();
                var confirmPassword = $('#confirm_password').val();
                
                if (password !== confirmPassword) {
                    Swal.fire({
                        title: "Password Mismatch",
                        text: "Passwords do not match. Please try again.",
                        icon: "error"
                    });
                    return;
                }
                
                if (password.length < 6) {
                    Swal.fire({
                        title: "Password Too Short",
                        text: "Password must be at least 6 characters.",
                        icon: "error"
                    });
                    return;
                }
                
                // Check if username already exists
                $.ajax({
                    type: "POST",
                    url: "./php/user_handler.php",
                    data: {
                        check_username: '1',
                        username: $('#username').val()
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.exists) {
                            Swal.fire({
                                title: "Username Exists",
                                text: "This username is already taken. Please choose another.",
                                icon: "error"
                            });
                        } else {
                            // Check if email already exists
                            $.ajax({
                                type: "POST",
                                url: "./php/user_handler.php",
                                data: {
                                    check_email: '1',
                                    email: $('#email').val()
                                },
                                success: function (response) {
                                    var res = JSON.parse(response);
                                    if (res.exists) {
                                        Swal.fire({
                                            title: "Email Exists",
                                            text: "This email is already registered. Please use another.",
                                            icon: "error"
                                        });
                                    } else {
                                        // Submit form
                                        submitUserForm();
                                    }
                                }
                            });
                        }
                    }
                });
            });
            
            function submitUserForm() {
                var formData = $('#addUserForm').serialize() + '&add_user=1';
                
                $.ajax({
                    type: "POST",
                    url: "./php/user_handler.php",
                    data: formData,
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: "Success!",
                                    html: res.message + "<br><br>" + 
                                          '<a href="./users.php" class="btn btn-outline-primary">View All Users</a> ' +
                                          '<a href="./addUser.php" class="btn btn-primary">Add Another User</a>',
                                    icon: "success",
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    width: '600px'
                                });
                                
                                // Reset form
                                $('#addUserForm')[0].reset();
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
                                text: "Failed to create user. Please try again.",
                                icon: "error"
                            });
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>