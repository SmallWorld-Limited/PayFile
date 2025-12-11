<?php
session_start();
include 'admin.php';

// Handle add user
if (isset($_POST['add_user'])) {
    $response = array();
    
    try {
        // Validate required fields
        $required = ['username', 'email', 'password', 'role'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can add users");
        }
        
        // Sanitize inputs
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'];
        $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
        $department_id = isset($_POST['department_id']) && !empty($_POST['department_id']) ? intval($_POST['department_id']) : null;
        $enabled = isset($_POST['enabled']) ? intval($_POST['enabled']) : 1;
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Validate password length
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
        
        $admin = new admin();
        
        // Check if username already exists
        if ($admin->check_username_exists($username)) {
            throw new Exception("Username '$username' already exists");
        }
        
        // Check if email already exists
        if ($admin->check_email_exists($email)) {
            throw new Exception("Email '$email' already exists");
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user using existing method
        $result = $admin->add_user($username, $email, $hashed_password, $role, $full_name, $department_id, $enabled);
        
        if ($result['status'] == 'success') {
            // Get the user ID (we need to fetch it since add_user doesn't return it)
            $conn = new db_connect();
            $db = $conn->connect();
            $stmt = $db->prepare("SELECT user_id FROM users WHERE username = ? ORDER BY user_id DESC LIMIT 1");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();
            
            // Log audit trail
            $log = new general();
            $log->logAuditTrail('users', $user_id, 'INSERT', $_SESSION["bt_user_id"], 
                              "User created: $username ($role)");
            
            $response = [
                'success' => true,
                'message' => 'User added successfully',
                'user_id' => $user_id
            ];
        } else {
            throw new Exception($result['message']);
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle edit user
elseif (isset($_POST['edit_user'])) {
    $response = array();
    
    try {
        // Validate required fields
        if (empty($_POST['user_id'])) {
            throw new Exception("User ID is required");
        }
        
        $required = ['username', 'email', 'role'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can edit users");
        }
        
        // Sanitize inputs
        $user_id = intval($_POST['user_id']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];
        $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
        $department_id = isset($_POST['department_id']) && !empty($_POST['department_id']) ? intval($_POST['department_id']) : null;
        $enabled = isset($_POST['enabled']) ? intval($_POST['enabled']) : 1;
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        $admin = new admin();
        
        // Check if username already exists (excluding current user)
        $conn = new db_connect();
        $db = $conn->connect();
        $stmt = $db->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ? AND deleted = 0");
        $stmt->bind_param('si', $username, $user_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            throw new Exception("Username '$username' already exists");
        }
        $stmt->close();
        
        // Check if email already exists (excluding current user)
        $stmt = $db->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ? AND deleted = 0");
        $stmt->bind_param('si', $email, $user_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            throw new Exception("Email '$email' already exists");
        }
        $stmt->close();
        
        // Update user
        $update_stmt = $db->prepare("
            UPDATE users 
            SET username = ?, 
                email = ?, 
                full_name = ?, 
                role = ?, 
                department_id = ?, 
                enabled = ?, 
                updated_by = ?, 
                updated_at = NOW()
            WHERE user_id = ? AND deleted = 0
        ");
        
        $updated_by = $_SESSION["bt_user_id"];
        $update_stmt->bind_param('ssssiiis', $username, $email, $full_name, $role, $department_id, $enabled, $updated_by, $user_id);
        
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update user: " . $update_stmt->error);
        }
        
        $update_stmt->close();
        
        // Log audit trail
        $log = new general();
        $log->logAuditTrail('users', $user_id, 'UPDATE', $_SESSION["bt_user_id"], 
                          "User updated: $username ($role)");
        
        $response = [
            'success' => true,
            'message' => 'User updated successfully'
        ];
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle delete user (soft delete)
elseif (isset($_POST['delete_user'])) {
    $response = array();
    
    try {
        if (empty($_POST['user_id'])) {
            throw new Exception("User ID is required");
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can delete users");
        }
        
        $user_id = intval($_POST['user_id']);
        
        // Cannot delete yourself
        if ($user_id == $_SESSION["bt_user_id"]) {
            throw new Exception("You cannot delete your own account");
        }
        
        $admin = new admin();
        $result = $admin->delete_user($user_id);
        
        if ($result['status'] == 'success') {
            // Log audit trail
            $log = new general();
            $log->logAuditTrail('users', $user_id, 'DELETE', $_SESSION["bt_user_id"], "User deleted");
            
            $response = [
                'success' => true,
                'message' => 'User deleted successfully'
            ];
        } else {
            throw new Exception($result['message']);
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle activate user
elseif (isset($_POST['activate_user'])) {
    $response = array();
    
    try {
        if (empty($_POST['user_id'])) {
            throw new Exception("User ID is required");
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can activate users");
        }
        
        $user_id = intval($_POST['user_id']);
        
        $admin = new admin();
        $result = $admin->activate_user($user_id);
        
        if ($result['status'] == 'success') {
            // Log audit trail
            $log = new general();
            $log->logAuditTrail('users', $user_id, 'UPDATE', $_SESSION["bt_user_id"], "User activated");
            
            $response = [
                'success' => true,
                'message' => $result['message']
            ];
        } else {
            throw new Exception($result['message']);
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle deactivate user
elseif (isset($_POST['deactivate_user'])) {
    $response = array();
    
    try {
        if (empty($_POST['user_id'])) {
            throw new Exception("User ID is required");
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can deactivate users");
        }
        
        $user_id = intval($_POST['user_id']);
        
        // Cannot deactivate yourself
        if ($user_id == $_SESSION["bt_user_id"]) {
            throw new Exception("You cannot deactivate your own account");
        }
        
        $admin = new admin();
        $result = $admin->deactivate_user($user_id);
        
        if ($result['status'] == 'success') {
            // Log audit trail
            $log = new general();
            $log->logAuditTrail('users', $user_id, 'UPDATE', $_SESSION["bt_user_id"], "User deactivated");
            
            $response = [
                'success' => true,
                'message' => $result['message']
            ];
        } else {
            throw new Exception($result['message']);
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle reset password
elseif (isset($_POST['reset_password'])) {
    $response = array();
    
    try {
        if (empty($_POST['user_id']) || empty($_POST['new_password'])) {
            throw new Exception("User ID and new password are required");
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can reset passwords");
        }
        
        $user_id = intval($_POST['user_id']);
        $new_password = $_POST['new_password'];
        
        // Validate password length
        if (strlen($new_password) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
        
        $auth = new auth();
        $result = $auth->changePassword($user_id, '', $new_password); // Empty current password for admin reset
        
        if ($result && isset($result['id']) && $result['id'] == 1) {
            // Log audit trail
            $log = new general();
            $log->logAuditTrail('users', $user_id, 'UPDATE', $_SESSION["bt_user_id"], "Password reset");
            
            $response = [
                'success' => true,
                'message' => 'Password reset successfully'
            ];
        } else {
            throw new Exception("Failed to reset password");
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle get user details
elseif (isset($_POST['get_user_details'])) {
    $response = array();
    
    try {
        if (empty($_POST['user_id'])) {
            throw new Exception("User ID is required");
        }
        
        // Check if user is admin
        if (!isset($_SESSION["bt_user_id"]) || $_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can view user details");
        }
        
        $user_id = intval($_POST['user_id']);
        
        $admin = new admin();
        $user = $admin->get_user_details($user_id);
        
        if ($user) {
            $response = [
                'success' => true,
                'user' => $user
            ];
        } else {
            throw new Exception("User not found");
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle check username
elseif (isset($_POST['check_username'])) {
    $response = array();
    
    try {
        if (empty($_POST['username'])) {
            throw new Exception("Username is required");
        }
        
        $username = trim($_POST['username']);
        
        $admin = new admin();
        $exists = $admin->check_username_exists($username);
        
        $response = [
            'exists' => $exists,
            'message' => $exists ? 'Username already exists' : 'Username available'
        ];
        
    } catch (Exception $e) {
        $response = [
            'exists' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle check email
elseif (isset($_POST['check_email'])) {
    $response = array();
    
    try {
        if (empty($_POST['email'])) {
            throw new Exception("Email is required");
        }
        
        $email = trim($_POST['email']);
        
        $admin = new admin();
        $exists = $admin->check_email_exists($email);
        
        $response = [
            'exists' => $exists,
            'message' => $exists ? 'Email already exists' : 'Email available'
        ];
        
    } catch (Exception $e) {
        $response = [
            'exists' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle get role statistics
elseif (isset($_POST['get_role_stats'])) {
    $response = array();
    
    try {
        // Check if user is admin or approver
        if (!isset($_SESSION["bt_user_id"]) || !in_array($_SESSION["bt_role"], ['admin', 'approver'])) {
            throw new Exception("You don't have permission to view role statistics");
        }
        
        $admin = new admin();
        $stats = $admin->get_role_statistics();
        
        $response = [
            'success' => true,
            'data' => $stats
        ];
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle get login details
elseif (isset($_POST['get_login_details'])) {
    $response = array();
    
    try {
        if (empty($_POST['log_id'])) {
            throw new Exception("Log ID is required");
        }
        
        // Check if user is admin or approver
        if (!isset($_SESSION["bt_user_id"]) || !in_array($_SESSION["bt_role"], ['admin', 'approver'])) {
            throw new Exception("You don't have permission to view login details");
        }
        
        $log_id = intval($_POST['log_id']);
        
        $admin = new admin();
        $log = $admin->get_login_details($log_id);
        
        if ($log) {
            $response = [
                'success' => true,
                'log' => $log
            ];
        } else {
            throw new Exception("Login record not found or access denied");
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle export login history (GET request)
elseif (isset($_GET['export']) && $_GET['export'] == 'csv') {
    try {
        // Check if user is admin or approver
        if (!isset($_SESSION["bt_user_id"]) || !in_array($_SESSION["bt_role"], ['admin', 'approver'])) {
            throw new Exception("You don't have permission to export login history");
        }
        
        $admin = new admin();
        
        // Build filters from GET parameters
        $filters = [];
        if (!empty($_GET['user_id'])) $filters['user_id'] = $_GET['user_id'];
        if (!empty($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
        if (!empty($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
        if (!empty($_GET['status'])) $filters['status'] = $_GET['status'];
        
        $csv = $admin->export_login_history($filters);
        
        if ($csv) {
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="login_history_' . date('Y-m-d_H-i-s') . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            echo $csv;
            exit;
        } else {
            throw new Exception("No data to export");
        }
        
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}

// If no valid action found
header('HTTP/1.0 400 Bad Request');
echo json_encode(array(
    'success' => false,
    'message' => 'Invalid request'
));
?>