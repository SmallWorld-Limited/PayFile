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
    <title>JPI Systems - System Configuration</title>
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
                                <h3>System Configuration</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Settings</li>
                                    <li class="breadcrumb-item active">Configuration</li>
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
                                    <h4>System Configuration Settings</h4>
                                    <p>Configure system-wide settings and preferences</p>
                                </div>
                                <div class="card-body">
                                    <form id="configForm">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mt-3 mb-4 border-bottom pb-3">File Signing Settings</h6>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">OpenSSL Path</label>
                                                    <input class="form-control" type="text" id="openssl_path" name="openssl_path" 
                                                           value="<?php echo $this->get_config_value('openssl_path'); ?>" 
                                                           placeholder="C:\Program Files\OpenSSL-Win64\bin\openssl.exe">
                                                    <small class="form-text text-muted">Path to OpenSSL executable</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Default Certificate</label>
                                                    <select class="form-control" id="default_certificate" name="default_certificate">
                                                        <?php
                                                        $dropdowns = new dropdowns();
                                                        // We need to get certificates for dropdown
                                                        // This is a placeholder - you'll need to implement the method
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mt-3 mb-4 border-bottom pb-3">File Upload Settings</h6>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Maximum Upload Size (MB)</label>
                                                    <input class="form-control" type="number" id="upload_max_size" name="upload_max_size" 
                                                           value="<?php echo $this->get_config_value('upload_max_size') / 1024 / 1024; ?>" 
                                                           min="1" max="100">
                                                    <small class="form-text text-muted">Maximum file size in megabytes</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Allowed File Types</label>
                                                    <input class="form-control" type="text" id="allowed_file_types" name="allowed_file_types" 
                                                           value="<?php echo $this->get_config_value('allowed_file_types'); ?>">
                                                    <small class="form-text text-muted">Comma-separated list (e.g., csv,txt,dat)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">File Storage Path</label>
                                                    <input class="form-control" type="text" id="file_storage_path" name="file_storage_path" 
                                                           value="<?php echo $this->get_config_value('file_storage_path'); ?>">
                                                    <small class="form-text text-muted">Path for storing uploaded files</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mt-3 mb-4 border-bottom pb-3">Backup Settings</h6>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Backup Retention (Days)</label>
                                                    <input class="form-control" type="number" id="backup_retention_days" name="backup_retention_days" 
                                                           value="<?php echo $this->get_config_value('backup_retention_days'); ?>" 
                                                           min="1" max="365">
                                                    <small class="form-text text-muted">Number of days to retain backups</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Auto Backup</label>
                                                    <select class="form-control" id="auto_backup" name="auto_backup">
                                                        <option value="1" <?php echo $this->get_config_value('auto_backup') == '1' ? 'selected' : ''; ?>>Enabled</option>
                                                        <option value="0" <?php echo $this->get_config_value('auto_backup') == '0' ? 'selected' : ''; ?>>Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mt-3 mb-4 border-bottom pb-3">Security Settings</h6>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Session Timeout (Minutes)</label>
                                                    <input class="form-control" type="number" id="session_timeout" name="session_timeout" 
                                                           value="<?php echo $this->get_config_value('session_timeout') / 60; ?>" 
                                                           min="5" max="120">
                                                    <small class="form-text text-muted">Session timeout in minutes</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Max Login Attempts</label>
                                                    <input class="form-control" type="number" id="max_login_attempts" name="max_login_attempts" 
                                                           value="<?php echo $this->get_config_value('max_login_attempts'); ?>" 
                                                           min="1" max="10">
                                                    <small class="form-text text-muted">Maximum failed login attempts before lockout</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Enable Two-Factor Auth</label>
                                                    <select class="form-control" id="enable_2fa" name="enable_2fa">
                                                        <option value="1" <?php echo $this->get_config_value('enable_2fa') == '1' ? 'selected' : ''; ?>>Enabled</option>
                                                        <option value="0" <?php echo $this->get_config_value('enable_2fa') == '0' ? 'selected' : ''; ?>>Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6 class="mt-3 mb-4 border-bottom pb-3">System Settings</h6>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">System Name</label>
                                                    <input class="form-control" type="text" id="system_name" name="system_name" 
                                                           value="<?php echo $this->get_config_value('system_name'); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">System Email</label>
                                                    <input class="form-control" type="email" id="system_email" name="system_email" 
                                                           value="<?php echo $this->get_config_value('system_email'); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Time Zone</label>
                                                    <select class="form-control" id="timezone" name="timezone">
                                                        <?php
                                                        $timezones = timezone_identifiers_list();
                                                        foreach ($timezones as $tz) {
                                                            $selected = ($tz == $this->get_config_value('timezone')) ? 'selected' : '';
                                                            echo "<option value='$tz' $selected>$tz</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="text-end mt-3">
                                                        <button class="btn btn-secondary" type="button" onclick="resetForm()">Reset</button>
                                                        <button class="btn btn-primary" id="saveConfig" type="submit">Save Changes</button>
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
            $('#configForm').submit(function (e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Save Changes?',
                    text: "Are you sure you want to save these configuration changes?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save changes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/settings_handler.php",
                            data: $(this).serialize() + '&save_config=1',
                            success: function (response) {
                                try {
                                    var res = JSON.parse(response);
                                    if (res.id == 1) {
                                        Swal.fire({
                                            title: "Success!",
                                            text: res.mssg,
                                            icon: "success"
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "Error",
                                            text: res.mssg,
                                            icon: "error"
                                        });
                                    }
                                } catch (e) {
                                    Swal.fire({
                                        title: "Error",
                                        text: "Invalid response from server",
                                        icon: "error"
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: "Error",
                                    text: "Failed to save configuration",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        });
        
        function resetForm() {
            Swal.fire({
                title: 'Reset Form?',
                text: "This will reset all fields to their original values",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
    </script>
</body>
</html>