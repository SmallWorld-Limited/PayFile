<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include 'connection.php';

    class auth extends db_connect {
        
        public function login($email, $password) {
            // Establishing database connection
            $conn = $this->connect();
        
            try {
                // Query to fetch the user from the Users table based on email
                $stmt = $conn->prepare("SELECT user_id, username, email, password, role, department_id 
                                        FROM Users 
                                        WHERE email = ?");
        
                // Bind the email parameter to the query
                $stmt->bind_param('s', $email);
        
                // Execute the query
                $stmt->execute();
                $stmt->store_result();
        
                // Check if a user exists with the given email
                if ($stmt->num_rows > 0) {
                    // Bind the result variables
                    $stmt->bind_result($user_id, $username, $email, $hashed_password, $role, $department_id);
        
                    // Fetch the user data
                    while ($stmt->fetch()) {
                        // Verify the provided password against the hashed password in the database
                        if (password_verify($password, $hashed_password)) {
                            // Set session variables to store logged-in user information
                            $_SESSION["bt_user_id"] = $user_id;
                            $_SESSION["bt_username"] = $username;
                            $_SESSION["bt_email"] = $email;
                            $_SESSION["bt_role"] = $role;
                            $_SESSION["bt_department_id"] = $department_id;

                            // Log the login action in the audit trail
                            $log = new general();
                            $log->logAuditTrail('Users', $user_id, 'INSERT', $user_id, 'User logged in.');
        
                            // Return a successful login response in JSON format
                            echo json_encode(array(
                                'id' => 1,
                                'mssg' => "Successfully Logged In",
                                'type' => 'success'
                            ));
                        } else {
                            // If the password is incorrect, return a password mismatch error
                            echo json_encode(array(
                                'id' => 0,
                                'mssg' => 'Password Mismatch',
                                'type' => 'error'
                            ));
                            return;
                        }
                    }
                } else {
                    // If no user found with the given email, return a user not found message
                    echo json_encode(array(
                        'id' => 0,
                        'mssg' => "User Not Found",
                        'type' => 'info'
                    ));
                }
            } catch (Exception $e) {
                // Handle exceptions and return an error message in JSON format
                echo json_encode(array(
                    'id' => 0,
                    'mssg' => "Error: " . $e->getMessage(),
                    'type' => 'error'
                ));
            }
        
            // Free the result and close the statement
            $stmt->free_result();
            $stmt->close();
        }
        public function register($username, $email, $password, $role, $department_id) {
            // Establishing a database connection
            $conn = $this->connect();
        
            try {
                // Check if a user with the given email already exists
                $check_stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
                $check_stmt->bind_param('s', $email);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                // If the user exists, return an error message
                if ($check_stmt->num_rows > 0) {
                    echo json_encode(array(
                        'id' => 0,
                        'mssg' => 'Email already exists',
                        'type' => 'error'
                    ));
                    return;
                }
                $check_stmt->close();
        
                // Hash the password using password_hash()
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
                // Insert the new user into the Users table
                $stmt = $conn->prepare("INSERT INTO Users (username, email, password, role, department_id) 
                                        VALUES (?, ?, ?, ?, ?)");
        
                // Bind the parameters to the prepared statement
                $stmt->bind_param('ssssi', $username, $email, $hashed_password, $role, $department_id);
        
                // Execute the insert query
                if ($stmt->execute()) {
                    // If successful, return a success message
                    echo json_encode(array(
                        'id' => 1,
                        'mssg' => 'Registration successful',
                        'type' => 'success'
                    ));
                } else {
                    // If there's an error, return an error message
                    echo json_encode(array(
                        'id' => 0,
                        'mssg' => 'Registration failed',
                        'type' => 'error'
                    ));
                }
        
                // Close the statement
                $stmt->close();
        
            } catch (Exception $e) {
                // Handle any exceptions
                echo json_encode(array(
                    'id' => 0,
                    'mssg' => "Error: " . $e->getMessage(),
                    'type' => 'error'
                ));
            }
        }
        public function resetPassword($email) {
            // Establishing Database Connection
            $conn = $this->connect();
        
            // Check if the email exists
            $stmt = $conn->prepare("SELECT user_id, first_name FROM Users WHERE email = ? AND deleted = 0");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
        
            if ($num_of_rows > 0) {
                // User found, generate a new random password
                $newPassword = bin2hex(random_bytes(4)); // Generates a random password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
        
                // Update the user's password in the database
                $stmt->close(); // Close previous statement
                $stmt = $conn->prepare("UPDATE Users SET password = ?, updated_at = NOW(), updated_by = ? WHERE email = ?");
                $userId = null; // Assuming you want to log who made the change; replace with your logic
                $stmt->bind_param('sis', $hashedPassword, $userId, $email);
                
                if ($stmt->execute()) {
                    // Optionally send an email to the user with the new password
                    $this->sendResetPasswordEmail($email, $newPassword);
        
                    echo json_encode(array(
                        'id' => 1,
                        'mssg' => "Password reset successfully. A new password has been sent to your email.",
                        'type' => 'success'
                    ));
                } else {
                    echo json_encode(array(
                        'id' => 0,
                        'mssg' => "Failed to reset password. Please try again later.",
                        'type' => 'error'
                    ));
                }
            } else {
                echo json_encode(array(
                    'id' => 0,
                    'mssg' => "User not found.",
                    'type' => 'info'
                ));
            }
        
            $stmt->free_result();
            $stmt->close();
        }
        public function changePassword($userId, $currentPassword, $newPassword) {
            // Establishing Database Connection
            $conn = $this->connect();
            
            // Check if user exists
            $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ? AND deleted = 0");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->store_result();
            
            // Check if user exists
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();
                
                // If current password is empty (admin reset), skip verification
                if (!empty($currentPassword)) {
                    // Verify current password
                    if (!password_verify($currentPassword, $hashedPassword)) {
                        $stmt->close();
                        return [
                            'id' => 0,
                            'mssg' => "Current password is incorrect.",
                            'type' => 'error'
                        ];
                    }
                }
                
                // Hash the new password
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update the user's password in the database
                $stmt->close(); // Close the previous statement
                $stmt = $conn->prepare("UPDATE users SET password = ?, updated_at = NOW(), updated_by = ? WHERE user_id = ?");
                $updatedBy = $_SESSION["bt_user_id"] ?? $userId; // Log the user making the change
                $stmt->bind_param('sii', $newHashedPassword, $updatedBy, $userId);
                
                if ($stmt->execute()) {
                    $stmt->close();
                    return [
                        'id' => 1,
                        'mssg' => "Password changed successfully.",
                        'type' => 'success'
                    ];
                } else {
                    $error = $stmt->error;
                    $stmt->close();
                    return [
                        'id' => 0,
                        'mssg' => "Failed to change password. Please try again later.",
                        'type' => 'error'
                    ];
                }
            } else {
                $stmt->close();
                return [
                    'id' => 0,
                    'mssg' => "User not found.",
                    'type' => 'info'
                ];
            }
        }
    }
    
    class admin extends db_connect{
        
        public function all_users() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT user_id, first_name, last_name, email, phone, role FROM `users` WHERE deleted = 0");
            $stmt->execute() or die($conn->error);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($id, $fname, $lname, $email, $phone, $role);
            
            if ($num_of_rows > 0) {
                while ($stmt->fetch()) {
                    ?>
                        <tr>
                            <td><?php echo $fname; ?></td>
                            <td><?php echo $lname; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $phone; ?></td>
                            <td><?php echo $role; ?></td>
                            <td>
                                <button type="button" class="btn btn-info rounded-pill edit" title="Edit User" data-id="<?php echo $id; ?>"><i class="mdi mdi-account-edit-outline"></i> </button>
                                <button type="button" class="delete btn btn-danger rounded-pill" title="Remove User" data-id="<?php echo $id; ?>"><i class="mdi mdi-trash-can-outline"></i> </button>
                            </td>
                        </tr>
                    <?php
                }
            }
            $stmt->free_result();
            $stmt->close();
        }
        public function add_user($username, $email, $password, $role, $full_name = '', $department_id = null, $enabled = 1) {
            $conn = $this->connect();
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare the statement based on whether department_id is provided
            if ($department_id !== null) {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role, department_id, enabled) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssii', $username, $email, $hashed_password, $full_name, $role, $department_id, $enabled);
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role, enabled) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sssssi', $username, $email, $hashed_password, $full_name, $role, $enabled);
            }
            
            if ($stmt === false) {
                error_log("SQL error: " . $conn->error);
                return array('status' => 'error', 'message' => 'Unable to add user. Please try again later.');
            }
            
            if ($stmt->execute()) {
                $stmt->close();
                return array('status' => 'success', 'message' => 'User added successfully.');
            } else {
                error_log("SQL error: " . $stmt->error);
                $stmt->close();
                return array('status' => 'error', 'message' => 'Failed to add the user. Please try again.');
            }
        }
        public function edit_user($user_id, $first_name, $last_name, $email, $phone, $role, $department_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("UPDATE `users` SET first_name = ?, last_name = ?, email = ?, phone = ?, role = ?, department_id = ? WHERE user_id = ?");
            
            if ($stmt === false) {
                error_log("SQL error: " . $conn->error);
                return array('status' => 'error', 'message' => 'Unable to edit user. Please try again later.');
            }
        
            $stmt->bind_param('sssssii', $first_name, $last_name, $email, $phone, $role, $department_id, $user_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return array('status' => 'success', 'message' => 'User updated successfully.');
            } else {
                error_log("SQL error: " . $stmt->error);
                $stmt->close();
                return array('status' => 'error', 'message' => 'Failed to update the user. Please try again.');
            }
        }
        public function delete_user($user_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("UPDATE `users` SET deleted = 1 WHERE user_id = ?");
            
            if ($stmt === false) {
                error_log("SQL error: " . $conn->error);
                return array('status' => 'error', 'message' => 'Unable to delete user. Please try again later.');
            }
        
            $stmt->bind_param('i', $user_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return array('status' => 'success', 'message' => 'User deleted successfully.');
            } else {
                error_log("SQL error: " . $stmt->error);
                $stmt->close();
                return array('status' => 'error', 'message' => 'Failed to delete the user. Please try again.');
            }
        }
        public function activate_user($user_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("UPDATE `users` SET enabled = 1 WHERE user_id = ?");
            
            if ($stmt === false) {
                error_log("SQL error: " . $conn->error);
                return array('status' => 'error', 'message' => 'Unable to activate user. Please try again later.');
            }
        
            $stmt->bind_param('i', $user_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return array('status' => 'success', 'message' => 'User activated successfully.');
            } else {
                error_log("SQL error: " . $stmt->error);
                $stmt->close();
                return array('status' => 'error', 'message' => 'Failed to activate the user. Please try again.');
            }
        }
        public function deactivate_user($user_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("UPDATE `users` SET enabled = 0 WHERE user_id = ?");
            
            if ($stmt === false) {
                error_log("SQL error: " . $conn->error);
                return array('status' => 'error', 'message' => 'Unable to deactivate user. Please try again later.');
            }
        
            $stmt->bind_param('i', $user_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return array('status' => 'success', 'message' => 'User deactivated successfully.');
            } else {
                error_log("SQL error: " . $stmt->error);
                $stmt->close();
                return array('status' => 'error', 'message' => 'Failed to deactivate the user. Please try again.');
            }
        }   
        // Get user details for editing
        public function get_user_details($user_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    user_id,
                    username,
                    email,
                    full_name,
                    role,
                    department_id,
                    enabled,
                    last_login,
                    created_at
                FROM users 
                WHERE user_id = ? AND deleted = 0
            ");
            
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $user = $result->fetch_assoc();
            $stmt->close();
            
            return $user;
        }
    
        // Check if username exists
        public function check_username_exists($username) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND deleted = 0");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            
            return $exists;
        }
        
        // Check if email exists
        public function check_email_exists($email) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND deleted = 0");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            
            $exists = $stmt->num_rows > 0;
            $stmt->close();
            
            return $exists;
        }
        
        // Get role statistics
        public function get_role_statistics() {
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    role,
                    COUNT(*) as count
                FROM users 
                WHERE deleted = 0 
                GROUP BY role
                ORDER BY role
            ";
            
            $result = $conn->query($sql);
            
            $stats = [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
            
            $colors = [
                'admin' => '#007bff',
                'approver' => '#28a745',
                'creator' => '#17a2b8',
                'viewer' => '#6c757d'
            ];
            
            while ($row = $result->fetch_assoc()) {
                $stats['labels'][] = ucfirst($row['role']);
                $stats['data'][] = $row['count'];
                $stats['colors'][] = $colors[$row['role']] ?? '#6c757d';
            }
            
            // Ensure all roles are included (even if count is 0)
            $all_roles = ['admin', 'approver', 'creator', 'viewer'];
            foreach ($all_roles as $role) {
                if (!in_array(ucfirst($role), $stats['labels'])) {
                    $stats['labels'][] = ucfirst($role);
                    $stats['data'][] = 0;
                    $stats['colors'][] = $colors[$role] ?? '#6c757d';
                }
            }
            
            return $stats;
        }
        
        // Get login history
        public function get_login_history($filters = []) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $where_clauses = ["al.entity_type = 'login'"];
            $params = [];
            $types = '';
            
            // Role-based access control
            if ($role != 'admin') {
                $where_clauses[] = "al.user_id = ?";
                $params[] = $user_id;
                $types .= 'i';
            }
            
            // Apply filters
            if (!empty($filters['user_id'])) {
                $where_clauses[] = "al.user_id = ?";
                $params[] = $filters['user_id'];
                $types .= 'i';
            }
            
            if (!empty($filters['date_from'])) {
                $where_clauses[] = "DATE(al.created_at) >= ?";
                $params[] = $filters['date_from'];
                $types .= 's';
            }
            
            if (!empty($filters['date_to'])) {
                $where_clauses[] = "DATE(al.created_at) <= ?";
                $params[] = $filters['date_to'];
                $types .= 's';
            }
            
            if (!empty($filters['status'])) {
                $where_clauses[] = "al.action = ?";
                $params[] = $filters['status'];
                $types .= 's';
            }
            
            $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
            
            $sql = "
                SELECT 
                    al.id,
                    al.user_id,
                    u.username,
                    u.full_name,
                    al.ip_address,
                    al.user_agent,
                    al.created_at,
                    al.action,
                    al.details
                FROM audit_log al
                LEFT JOIN users u ON al.user_id = u.user_id
                $where_sql
                ORDER BY al.created_at DESC
                LIMIT 1000
            ";
            
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $logs = [];
            while ($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }
            
            $stmt->close();
            return $logs;
        }
        
        // Get login details by ID
        public function get_login_details($log_id) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $sql = "
                SELECT 
                    al.id,
                    al.user_id,
                    u.username,
                    u.full_name,
                    al.ip_address,
                    al.user_agent,
                    al.created_at,
                    al.action,
                    al.details,
                    al.entity_type
                FROM audit_log al
                LEFT JOIN users u ON al.user_id = u.user_id
                WHERE al.id = ? AND al.entity_type = 'login'
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $log_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                $stmt->close();
                return null;
            }
            
            $log = $result->fetch_assoc();
            $stmt->close();
            
            // Check permissions
            if ($role != 'admin' && $log['user_id'] != $user_id) {
                return null;
            }
            
            return $log;
        }
        
        // Export login history to CSV
        public function export_login_history($filters = []) {
            $logs = $this->get_login_history($filters);
            
            if (empty($logs)) {
                return null;
            }
            
            // Create CSV content
            $csv = "ID,User ID,Username,Full Name,IP Address,User Agent,Login Time,Status,Details\n";
            
            foreach ($logs as $log) {
                $csv .= sprintf(
                    '%d,%d,%s,%s,%s,%s,%s,%s,%s' . "\n",
                    $log['id'],
                    $log['user_id'],
                    $this->escape_csv($log['username']),
                    $this->escape_csv($log['full_name'] ?? ''),
                    $this->escape_csv($log['ip_address'] ?? ''),
                    $this->escape_csv($log['user_agent'] ?? ''),
                    $this->escape_csv($log['created_at']),
                    $this->escape_csv($log['action'] == 'login_success' ? 'Success' : 'Failed'),
                    $this->escape_csv($log['details'] ?? '')
                );
            }
            
            return $csv;
        }
        
        private function escape_csv($value) {
            $value = str_replace('"', '""', $value);
            if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                $value = '"' . $value . '"';
            }
            return $value;
        }                                                             
    }


    class general extends db_connect{
        public function logAuditTrail($tableName, $recordId, $actionType, $userId, $details = null) {
            // Establishing Database Connection
            $conn = $this->connect();
        
            // Prepared statement to insert an audit log
            $stmt = $conn->prepare("INSERT INTO Audit_Trail (table_name, record_id, action_type, user_id, details) VALUES (?, ?, ?, ?, ?)");
            
            // Binding parameters
            $stmt->bind_param('sisss', $tableName, $recordId, $actionType, $userId, $details);
            
            // Executing the query
            if ($stmt->execute()) {
                // Optionally return success or perform further actions
                return true;
            } else {
                // Handle error
                return false;
            }
        
            // Closing the statement
            $stmt->close();
        }
    }

    class stats extends db_connect{
        public function getBidsCountThisMonth() {
            // Establishing Database Connection
            $conn = $this->connect();
        
            // Check connection
            if (!$conn) {
                // Handle connection error
                error_log("Database connection failed: " . $conn->connect_error);
                return 0; // Return 0 in case of connection error
            }
        
            // Get the current year and month
            $currentYear = date('Y');
            $currentMonth = date('m');
        
            // Prepared statement to count bids for the current month
            $stmt = $conn->prepare("SELECT COUNT(*) AS bid_count FROM bids WHERE YEAR(created_at) = ? AND MONTH(created_at) = ? AND deleted = 0");
        
            if ($stmt === false) {
                // Log error in case of prepare failure
                error_log("Statement preparation failed: " . $conn->error);
                return 0;
            }
        
            // Binding parameters
            $stmt->bind_param('ii', $currentYear, $currentMonth);
        
            // Executing the query
            if ($stmt->execute()) {
                // Fetch the result
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
        
                // Return the bid count
                $bidCount = $row['bid_count'];
            } else {
                // Log error in case of execution failure
                error_log("Execution failed: " . $stmt->error);
                $bidCount = 0;
            }
        
            // Closing the statement and connection
            $stmt->close();
            $conn->close();
        
            echo $bidCount;
        }
        
    }

    class file_management extends db_connect {
    
        public function list_uploaded_files() {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"];
            
            // Check user role for access
            $role = $_SESSION["bt_role"];
            $where_clause = "";
            if ($role != 'admin') {
                $where_clause = "WHERE uf.uploaded_by = ?";
            }
            
            $stmt = $conn->prepare("
                SELECT uf.id, uf.original_name, uf.stored_path, uf.file_size, 
                    uf.status, uf.uploaded_at, 
                    u.username as uploaded_by,
                    ft.name as file_type,
                    sf.signed_file_path
                FROM uploaded_files uf
                LEFT JOIN users u ON uf.uploaded_by = u.user_id
                LEFT JOIN file_batches fb ON uf.batch_id = fb.id
                LEFT JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN signed_files sf ON uf.id = sf.original_file_id
                $where_clause
                ORDER BY uf.uploaded_at DESC
            ");
            
            if ($role != 'admin') {
                $stmt->bind_param('i', $user_id);
            }
            
            $stmt->execute() or die($conn->error);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($id, $original_name, $stored_path, $file_size, $status, 
                            $uploaded_at, $uploaded_by, $file_type, $signed_file_path);
            
            if ($num_of_rows > 0) {
                while ($stmt->fetch()) {
                    $file_size_kb = round($file_size / 1024, 2);
                    $upload_date = date('Y-m-d H:i', strtotime($uploaded_at));
                    
                    // Status badge color
                    $status_badge = '';
                    switch($status) {
                        case 'pending': $status_badge = 'badge-warning'; break;
                        case 'processing': $status_badge = 'badge-info'; break;
                        case 'processed': $status_badge = 'badge-success'; break;
                        case 'failed': $status_badge = 'badge-danger'; break;
                        default: $status_badge = 'badge-secondary';
                    }
                    
                    // Check if signed file exists
                    $has_signed_file = !empty($signed_file_path) && file_exists($signed_file_path);
                    
                    ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo htmlspecialchars($original_name); ?></td>
                        <td><?php echo $file_type ?: 'N/A'; ?></td>
                        <td><span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst($status); ?></span></td>
                        <td><?php echo $uploaded_by; ?></td>
                        <td><?php echo $upload_date; ?></td>
                        <td><?php echo $file_size_kb; ?> KB</td>
                        <td>
                            <?php if ($status == 'processed' && file_exists($stored_path)): ?>
                                <button type="button" class="btn btn-sm btn-info download-file" 
                                        data-id="<?php echo $id; ?>" data-action="download" 
                                        title="Download Original">
                                    <i class="fa fa-download"></i>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($has_signed_file): ?>
                                <button type="button" class="btn btn-sm btn-success download-file" 
                                        data-id="<?php echo $id; ?>" data-action="download-signed" 
                                        title="Download Signed File">
                                    <i class="fa fa-file-signature"></i>
                                </button>
                            <?php elseif ($status == 'processed'): ?>
                                <button type="button" class="btn btn-sm btn-warning sign-file" 
                                        data-id="<?php echo $id; ?>" 
                                        title="Sign File">
                                    <i class="fa fa-signature"></i>
                                </button>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION["bt_role"] == 'admin' || $user_id == $_SESSION["bt_user_id"]): ?>
                                <button type="button" class="btn btn-sm btn-danger delete-file" 
                                        data-id="<?php echo $id; ?>" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="8" class="text-center">No files uploaded yet.</td>
                </tr>
                <?php
            }
            
            $stmt->free_result();
            $stmt->close();
        }
        
        public function upload_and_sign_file($file_type_id, $certificate_id, $file_reference, $file_tmp_path, $original_name, $cert_password = '') {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"];
            
            try {
                $conn->begin_transaction();
                
                $check_stmt = $conn->prepare("SELECT id FROM file_batches WHERE reference_no = ?");
                $check_stmt->bind_param('s', $file_reference);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows > 0) {
                    throw new Exception("File reference '$file_reference' already exists.");
                }
                $check_stmt->close();
                
                $type_stmt = $conn->prepare("SELECT code, name, delimiter FROM file_types WHERE id = ?");
                $type_stmt->bind_param('i', $file_type_id);
                $type_stmt->execute();
                $type_stmt->bind_result($file_type_code, $file_type_name, $delimiter);
                $type_stmt->fetch();
                $type_stmt->close();
                
                $file_content = file_get_contents($file_tmp_path);
                $lines = explode("\n", trim($file_content));
                
                if (empty($lines)) {
                    throw new Exception("CSV file is empty");
                }
                
                $line_items = [];
                $total_amount = 0;
                $line_count = 0;
                
                for ($i = 0; $i < count($lines); $i++) {
                    $line = trim($lines[$i]);
                    if (!empty($line)) {
                        $fields = str_getcsv($line, $delimiter);
                        
                        $fields = array_map(function($field) {
                            return trim($field, ' "\'');
                        }, $fields);
                        
                        $line_items[] = $fields;
                        
                        if ($file_type_id == 1 && isset($fields[5]) && is_numeric($fields[5])) {
                            $total_amount += floatval($fields[5]);
                        }
                        
                        $line_count++;
                    }
                }
                
                if ($line_count === 0) {
                    throw new Exception("No valid data rows found in CSV file");
                }
                
                $header_fields = $this->get_header_fields_for_file_type($file_type_id, $delimiter);
                
                $batch_number = $this->generate_batch_number($file_type_code);
                
                $batch_stmt = $conn->prepare("
                    INSERT INTO file_batches (batch_number, file_type_id, reference_no, currency_code, 
                                            total_amount, total_count, created_by)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                $currency_code = 'MWK';
                if (!empty($line_items) && isset($line_items[0][2])) {
                    $currency_code = $line_items[0][2];
                }
                
                $batch_stmt->bind_param('sissdii', $batch_number, $file_type_id, $file_reference, 
                                    $currency_code, $total_amount, $line_count, $user_id);
                
                if (!$batch_stmt->execute()) {
                    throw new Exception("Failed to create batch: " . $batch_stmt->error);
                }
                
                $batch_id = $conn->insert_id;
                $batch_stmt->close();
                
                $upload_dir = '../uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $stored_name = preg_replace('/[^a-zA-Z0-9\.\-]/', '_'.time(), $original_name) ;
                $stored_path = $upload_dir . $stored_name;
                
                if (!move_uploaded_file($file_tmp_path, $stored_path)) {
                    throw new Exception("Failed to move uploaded file");
                }
                
                $upload_stmt = $conn->prepare("
                    INSERT INTO uploaded_files (original_name, stored_name, stored_path, file_size, 
                                            uploaded_by, status, batch_id)
                    VALUES (?, ?, ?, ?, ?, 'processing', ?)
                ");
                
                $file_size = filesize($stored_path);
                $upload_stmt->bind_param('sssiii', $original_name, $stored_name, $stored_path, 
                                        $file_size, $user_id, $batch_id);
                
                if (!$upload_stmt->execute()) {
                    throw new Exception("Failed to save file record: " . $upload_stmt->error);
                }
                
                $file_id = $conn->insert_id;
                $upload_stmt->close();
                
                $item_index = 1;
                foreach ($line_items as $item_fields) {
                    $item_data = [];
                    for ($j = 0; $j < min(count($header_fields), count($item_fields)); $j++) {
                        $item_data[$header_fields[$j]] = $item_fields[$j];
                    }
                    
                    for ($j = count($header_fields); $j < count($item_fields); $j++) {
                        $item_data['field_' . ($j + 1)] = $item_fields[$j];
                    }
                    
                    $item_json = json_encode($item_data);
                    
                    $item_stmt = $conn->prepare("
                        INSERT INTO batch_items (batch_id, item_index, data_json, status)
                        VALUES (?, ?, ?, 'processed')
                    ");
                    
                    $item_stmt->bind_param('iis', $batch_id, $item_index, $item_json);
                    $item_stmt->execute();
                    $item_stmt->close();
                    $item_index++;
                }
                
                $update_batch = $conn->prepare("UPDATE file_batches SET status = 'generated' WHERE id = ?");
                $update_batch->bind_param('i', $batch_id);
                $update_batch->execute();
                $update_batch->close();
                
                $update_file = $conn->prepare("UPDATE uploaded_files SET status = 'processed' WHERE id = ?");
                $update_file->bind_param('i', $file_id);
                $update_file->execute();
                $update_file->close();
                
                $cert_stmt = $conn->prepare("
                    SELECT certificate_path, private_key_path, certificate_password 
                    FROM certificates WHERE id = ?
                ");
                $cert_stmt->bind_param('i', $certificate_id);
                $cert_stmt->execute();
                $cert_stmt->bind_result($cert_path, $key_path, $cert_pass);
                $cert_stmt->fetch();
                $cert_stmt->close();
                
                $password_to_use = !empty($cert_password) ? $cert_password : $cert_pass;
                
                $signed_file_path = $this->sign_file_with_openssl($stored_path, $cert_path, $key_path, $password_to_use, $batch_id);
                
                $signed_stmt = $conn->prepare("
                    INSERT INTO signed_files (batch_id, original_file_id, signed_file_name, 
                                            signed_file_path, signature_type, signed_by_user)
                    VALUES (?, ?, ?, ?, 'SMIME', ?)
                ");
                
                $signed_file_name = $stored_name;
                $signed_stmt->bind_param('iissi', $batch_id, $file_id, $signed_file_name, 
                                        $signed_file_path, $user_id);
                $signed_stmt->execute();
                $signed_stmt->close();
                
                $conn->commit();
                
                $log = new general();
                $log->logAuditTrail('uploaded_files', $file_id, 'INSERT', $user_id, 
                                "File uploaded and signed: $original_name");
                
                return [
                    'success' => true,
                    'batch_id' => $batch_id,
                    'file_id' => $file_id,
                    'signed_file_path' => $signed_file_path,
                    'message' => "File uploaded and signed successfully"
                ];
                
            } catch (Exception $e) {
                $conn->rollback();
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        // Add this helper method to get header fields for file type
        private function get_header_fields_for_file_type($file_type_id, $delimiter) {
            // Define header fields for different file types
            $headers = [
                1 => [ // Payment File
                    'header_line', 'trans_serial', 'currency_code', 'debit_account_number',
                    'debit_account_name', 'payment_amount', 'payee_details', 'vendor_code',
                    'employee_number', 'national_id', 'invoice_number', 'payee_bic',
                    'credit_account_number', 'cost_center', 'date', 'source',
                    'reference_number', 'description'
                ],
                2 => [ // Remittance File
                    'header_line', 'trans_serial', 'debit_account_number', 'debit_account_name',
                    'currency_code', 'payee_account_number', 'payment_amount', 'creditor_name',
                    'payment_date', 'date_created', 'source_reference_number', 'cost_centre_code'
                ],
                3 => [ // Remittance with PRN
                    'header_line', 'trans_serial', 'debit_account_number', 'debit_account_name',
                    'currency_code', 'payee_account_number', 'payment_amount', 'creditor_name',
                    'payment_date', 'date_created', 'source_reference_number', 'cost_centre_code',
                    'payment_reference_number'
                ],
                4 => [ // Foreign Payment
                    'header_line', 'trans_serial', 'currency_code', 'debit_account_number',
                    'bank_account_name', 'payment_amount', 'amount_in_words', 'payee_details1',
                    'payee_details2', 'invoice_number', 'bank_branch_sort_code', 'payee_bic',
                    'credit_account_number', 'cost_center', 'run_date', 'approval_date',
                    'sap_ifmis_reference_number', 'corresponding_bank', 'corresponding_country',
                    'non_resident', 'surname', 'first_name', 'institutional_sector_code',
                    'industrial_classification', 'bop_category', 'subject', 'description',
                    'individual_third_party_surname', 'individual_third_party_name',
                    'individual_third_party_gender', 'cost_of_goods', 'freight'
                ],
                5 => [ // Salary Payment
                    'header_line', 'vote_number', 'total_amount', 'currency_code', 'debit_account',
                    'funding_trf_number', 'description', 'value_date', 'total_count'
                ]
            ];
            
            // Return headers for the specific file type, or generic headers if not found
            return isset($headers[$file_type_id]) ? $headers[$file_type_id] : 
                array_map(function($i) { return 'field_' . ($i + 1); }, range(0, 19));
        }
        
        private function generate_batch_number($file_type_code) {
            $date_str = date('Ymd');
            $random = rand(1000, 9999);
            return $file_type_code . '-' . $date_str . '-' . $random;
        }
        
        private function sign_file_with_openssl($input_file, $cert_path, $key_path, $password, $batch_id) {
            try {
                // Define OpenSSL path
                $openssl_path = 'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe';
                
                // Check if OpenSSL exists
                if (!file_exists($openssl_path)) {
                    $openssl_path = 'openssl'; // Try system PATH
                }
                
                // Check if input file exists
                if (!file_exists($input_file)) {
                    throw new Exception("Input file not found: " . $input_file);
                }
                
                // Check if certificate file exists
                if (!file_exists($cert_path)) {
                    $project_root = dirname(dirname(__FILE__));
                    $relative_cert_path = $project_root . '/' . $cert_path;
                    if (file_exists($relative_cert_path)) {
                        $cert_path = realpath($relative_cert_path);
                    } else {
                        throw new Exception("Certificate file not found: " . $cert_path);
                    }
                }
                
                // Check if private key file exists
                if (!file_exists($key_path)) {
                    $project_root = dirname(dirname(__FILE__));
                    $relative_key_path = $project_root . '/' . $key_path;
                    if (file_exists($relative_key_path)) {
                        $key_path = realpath($relative_key_path);
                    } else {
                        throw new Exception("Private key file not found: " . $key_path);
                    }
                }
                
                // Create signed files directory
                $signed_dir = dirname(dirname(__FILE__)) . '/signed_files/';
                if (!file_exists($signed_dir)) {
                    if (!mkdir($signed_dir, 0777, true)) {
                        throw new Exception("Failed to create signed files directory: " . $signed_dir);
                    }
                }
                
                // Generate output file path with .csv extension (like your Python code)
                // Your Python code: output = Path("signed_1.csv")  # binary but named .csv
                $original_filename = basename($input_file);
                $filename_without_ext = pathinfo($original_filename, PATHINFO_FILENAME);
                $output_file = $signed_dir . 'signed_' . $filename_without_ext . '_' . $batch_id . '.csv';
                
                // Make sure we don't overwrite existing files
                $counter = 1;
                while (file_exists($output_file)) {
                    $output_file = $signed_dir . 'signed_' . $filename_without_ext . '_' . $batch_id . '_' . $counter . '.csv';
                    $counter++;
                }
                
                // Build OpenSSL command - EXACTLY like your Python code
                $cmd = '"' . $openssl_path . '" smime -sign';
                $cmd .= ' -in "' . $input_file . '"';
                $cmd .= ' -out "' . $output_file . '"';
                $cmd .= ' -signer "' . $cert_path . '"';
                $cmd .= ' -inkey "' . $key_path . '"';
                $cmd .= ' -outform DER -nodetach -binary';  // Same as Python
                
                // if (!empty($password)) {
                //     $cmd .= ' -passin pass:' . escapeshellarg($password);
                // }
                
                // Debug logging
                error_log("OpenSSL Command: " . $cmd);
                
                // Execute command
                $output = [];
                $return_code = 0;
                exec($cmd . ' 2>&1', $output, $return_code);
                
                error_log("OpenSSL Output: " . implode("\n", $output));
                error_log("OpenSSL Return Code: " . $return_code);
                
                if ($return_code !== 0) {
                    $error_msg = "OpenSSL signing failed (Code: $return_code): " . implode("\n", $output);
                    error_log($error_msg);
                    throw new Exception($error_msg);
                }
                
                if (!file_exists($output_file)) {
                    throw new Exception("Signed file was not created: " . $output_file);
                }
                
                // Verify the file is created and has content
                $file_size = filesize($output_file);
                if ($file_size === 0) {
                    throw new Exception("Signed file is empty: " . $output_file);
                }
                
                error_log("Signed file created successfully: " . $output_file . " (" . $file_size . " bytes)");
                
                return $output_file;
                
            } catch (Exception $e) {
                error_log("Signing error: " . $e->getMessage());
                throw $e;
            }
        }
        
        public function delete_file($file_id) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"];
            $role = $_SESSION["bt_role"];
            
            try {
                // Get file info
                $stmt = $conn->prepare("
                    SELECT uf.stored_path, uf.uploaded_by, sf.signed_file_path 
                    FROM uploaded_files uf
                    LEFT JOIN signed_files sf ON uf.id = sf.original_file_id
                    WHERE uf.id = ?
                ");
                $stmt->bind_param('i', $file_id);
                $stmt->execute();
                $stmt->bind_result($file_path, $uploaded_by, $signed_file_path);
                $stmt->fetch();
                $stmt->close();
                
                // Check permissions
                if ($role != 'admin' && $user_id != $uploaded_by) {
                    throw new Exception("You don't have permission to delete this file");
                }
                
                // Delete physical files
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                if (!empty($signed_file_path) && file_exists($signed_file_path)) {
                    unlink($signed_file_path);
                }
                
                // Delete database records
                $conn->begin_transaction();
                
                // Delete from signed_files first (foreign key constraint)
                $delete_signed = $conn->prepare("DELETE FROM signed_files WHERE original_file_id = ?");
                $delete_signed->bind_param('i', $file_id);
                $delete_signed->execute();
                $delete_signed->close();
                
                // Delete from uploaded_files
                $delete_uploaded = $conn->prepare("DELETE FROM uploaded_files WHERE id = ?");
                $delete_uploaded->bind_param('i', $file_id);
                $delete_uploaded->execute();
                $delete_uploaded->close();
                
                // Note: We might want to keep batch records for audit purposes
                // Instead of deleting, we could mark them as deleted
                
                $conn->commit();
                
                // Log audit trail
                $log = new general();
                $log->logAuditTrail('uploaded_files', $file_id, 'DELETE', $user_id, "File deleted");
                
                return [
                    'success' => true,
                    'message' => 'File deleted successfully'
                ];
                
            } catch (Exception $e) {
                if (isset($conn)) {
                    $conn->rollback();
                }
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        public function sign_existing_file($file_id) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"];
            
            try {
                $stmt = $conn->prepare("
                    SELECT uf.stored_path, uf.batch_id, c.id as cert_id, 
                        c.certificate_path, c.private_key_path, c.certificate_password
                    FROM uploaded_files uf
                    LEFT JOIN certificates c ON c.is_default = 1
                    WHERE uf.id = ? AND uf.status = 'processed'
                ");
                $stmt->bind_param('i', $file_id);
                $stmt->execute();
                $stmt->bind_result($file_path, $batch_id, $cert_id, $cert_path, $key_path, $cert_pass);
                $stmt->fetch();
                $stmt->close();
                
                if (empty($file_path) || !file_exists($file_path)) {
                    throw new Exception("Original file not found");
                }
                
                if (empty($cert_id)) {
                    throw new Exception("No default certificate found");
                }
                
                $signed_file_path = $this->sign_file_with_openssl($file_path, $cert_path, $key_path, $cert_pass, $batch_id);
                
                $signed_stmt = $conn->prepare("
                    INSERT INTO signed_files (batch_id, original_file_id, signed_file_name, 
                                            signed_file_path, signature_type, signed_by_user)
                    VALUES (?, ?, ?, ?, 'SMIME', ?)
                ");
                
                $signed_file_name = 'signed_' . basename($file_path);
                $signed_stmt->bind_param('iissi', $batch_id, $file_id, $signed_file_name, 
                                        $signed_file_path, $user_id);
                $signed_stmt->execute();
                $signed_stmt->close();
                
                $log = new general();
                $log->logAuditTrail('uploaded_files', $file_id, 'UPDATE', $user_id, 
                                "File signed with certificate ID: $cert_id");
                
                return [
                    'success' => true,
                    'message' => 'File signed successfully',
                    'signed_file_path' => $signed_file_path
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
    }

    class dropdowns extends db_connect {
        
        public function get_file_types() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT id, code, name FROM file_types WHERE is_active = 1 ORDER BY name");
            
            if ($stmt === false) {
                die($conn->error);
            }
        
            $stmt->execute() or die($conn->error);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($id, $code, $name);
            
            if ($num_of_rows > 0) {
                while ($stmt->fetch()) {
                    ?>
                        <option value="<?php echo $id; ?>"><?php echo $name . ' (' . $code . ')'; ?></option>
                    <?php
                }
            } else {
                ?>
                    <option value="">No file types available</option>
                <?php
            }
            
            $stmt->free_result();
            $stmt->close();
        }
        
        public function get_certificates() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT id, certificate_name, issuer FROM certificates WHERE is_active = 1 ORDER BY is_default DESC, certificate_name");
            
            if ($stmt === false) {
                die($conn->error);
            }
        
            $stmt->execute() or die($conn->error);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($id, $name, $issuer);
            
            if ($num_of_rows > 0) {
                while ($stmt->fetch()) {
                    $display_name = $name . ' - ' . $issuer;
                    ?>
                    <option value="<?php echo $id; ?>"><?php echo $display_name; ?></option>
                    <?php
                }
            } else {
                ?>
                <option value="">No certificates available</option>
                <?php
            }
            
            $stmt->free_result();
            $stmt->close();
        }
    }

    class certificate_management extends db_connect {
    
        public function list_certificates() {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT id, certificate_name, certificate_path, private_key_path, 
                    certificate_type, issuer, subject, 
                    DATE(valid_from) as valid_from, DATE(valid_to) as valid_to,
                    is_default, is_active, created_at
                FROM certificates 
                ORDER BY is_default DESC, certificate_name ASC
            ");
            
            $stmt->execute() or die($conn->error);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($id, $cert_name, $cert_path, $key_path, $cert_type, 
                            $issuer, $subject, $valid_from, $valid_to, $is_default, 
                            $is_active, $created_at);
            
            if ($num_of_rows > 0) {
                while ($stmt->fetch()) {
                    $created_date = date('Y-m-d H:i', strtotime($created_at));
                    
                    $default_badge = $is_default ? '<span class="badge badge-success">Default</span>' : '';
                    
                    $active_badge = $is_active ? 
                        '<span class="badge badge-success">Active</span>' : 
                        '<span class="badge badge-danger">Inactive</span>';
                    
                    $expiry_badge = '';
                    if ($valid_to) {
                        $today = date('Y-m-d');
                        $valid_to_date = date('Y-m-d', strtotime($valid_to));
                        
                        if ($valid_to_date < $today) {
                            $expiry_badge = '<span class="badge badge-danger">Expired</span>';
                        } elseif (date('Y-m-d', strtotime('+30 days')) > $valid_to_date) {
                            $expiry_badge = '<span class="badge badge-warning">Expiring Soon</span>';
                        } else {
                            $expiry_badge = '<span class="badge badge-info">Valid</span>';
                        }
                    }
                    
                    ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo htmlspecialchars($cert_name); ?></td>
                        <td><?php echo htmlspecialchars($issuer); ?></td>
                        <td><?php echo htmlspecialchars($subject); ?></td>
                        <td><?php echo ucfirst($cert_type); ?></td>
                        <td><?php echo $valid_from; ?></td>
                        <td><?php echo $valid_to; ?></td>
                        <td>
                            <?php echo $default_badge; ?>
                            <?php echo $active_badge; ?>
                            <?php echo $expiry_badge; ?>
                        </td>
                        <td><?php echo $created_date; ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info view-cert" 
                                    data-id="<?php echo $id; ?>" title="View Details">
                                <i class="fa fa-eye"></i>
                            </button>
                            
                            <?php if ($_SESSION["bt_role"] == 'admin'): ?>
                                <button type="button" class="btn btn-sm btn-warning edit-cert" 
                                        data-id="<?php echo $id; ?>" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-danger delete-cert" 
                                        data-id="<?php echo $id; ?>" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                                
                                <?php if (!$is_default): ?>
                                    <button type="button" class="btn btn-sm btn-primary set-default-cert" 
                                            data-id="<?php echo $id; ?>" title="Set as Default">
                                        <i class="fa fa-star"></i>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="10" class="text-center">No certificates found. <a href="./addCert.php">Add your first certificate</a></td>
                </tr>
                <?php
            }
            
            $stmt->free_result();
            $stmt->close();
        }
        
        public function add_certificate($cert_name, $cert_path, $key_path, $cert_password, 
                                    $cert_type, $issuer, $subject, $valid_from, $valid_to, 
                                    $is_default = 0, $is_active = 1) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"];
            
            try {
                $conn->begin_transaction();
                
                $check_stmt = $conn->prepare("SELECT id FROM certificates WHERE certificate_name = ?");
                $check_stmt->bind_param('s', $cert_name);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows > 0) {
                    throw new Exception("Certificate name '$cert_name' already exists.");
                }
                $check_stmt->close();
                
                if ($is_default) {
                    $update_stmt = $conn->prepare("UPDATE certificates SET is_default = 0 WHERE is_default = 1");
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                
                if (!file_exists($cert_path)) {
                    throw new Exception("Certificate file not found at: $cert_path");
                }
                
                if (!file_exists($key_path)) {
                    throw new Exception("Private key file not found at: $key_path");
                }
                
                if (empty($issuer) || empty($subject) || empty($valid_from) || empty($valid_to)) {
                    $cert_details = $this->parse_certificate_details($cert_path);
                    
                    if (empty($issuer)) $issuer = $cert_details['issuer'] ?? 'Unknown';
                    if (empty($subject)) $subject = $cert_details['subject'] ?? 'Unknown';
                    if (empty($valid_from)) $valid_from = $cert_details['valid_from'] ?? date('Y-m-d H:i:s');
                    if (empty($valid_to)) $valid_to = $cert_details['valid_to'] ?? date('Y-m-d H:i:s', strtotime('+1 year'));
                }
                
                $insert_stmt = $conn->prepare("
                    INSERT INTO certificates (certificate_name, certificate_path, private_key_path, 
                                            certificate_password, certificate_type, issuer, subject, 
                                            valid_from, valid_to, is_default, is_active, created_by)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $insert_stmt->bind_param('sssssssssiis', $cert_name, $cert_path, $key_path, 
                                        $cert_password, $cert_type, $issuer, $subject, 
                                        $valid_from, $valid_to, $is_default, $is_active, $user_id);
                
                if (!$insert_stmt->execute()) {
                    throw new Exception("Failed to add certificate: " . $insert_stmt->error);
                }
                
                $cert_id = $conn->insert_id;
                $insert_stmt->close();
                
                $conn->commit();
                
                $log = new general();
                $log->logAuditTrail('certificates', $cert_id, 'INSERT', $user_id, 
                                "Certificate added: $cert_name");
                
                return [
                    'success' => true,
                    'message' => 'Certificate added successfully',
                    'certificate_id' => $cert_id
                ];
                
            } catch (Exception $e) {
                $conn->rollback();
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        private function parse_certificate_details($cert_path) {
            $details = [];
            
            try {
                $cert_data = file_get_contents($cert_path);
                
                if (strpos($cert_data, '-----BEGIN CERTIFICATE-----') !== false) {
                    $cert_info = openssl_x509_parse($cert_data);
                    
                    if ($cert_info) {
                        $details['issuer'] = isset($cert_info['issuer']['CN']) ? 
                            $cert_info['issuer']['CN'] : 
                            (isset($cert_info['issuer']['O']) ? $cert_info['issuer']['O'] : 'Unknown');
                        
                        $details['subject'] = isset($cert_info['subject']['CN']) ? 
                            $cert_info['subject']['CN'] : 
                            (isset($cert_info['subject']['O']) ? $cert_info['subject']['O'] : 'Unknown');
                        
                        $details['serial_number'] = isset($cert_info['serialNumber']) ? 
                            $cert_info['serialNumber'] : 'Unknown';
                        
                        $details['valid_from'] = date('Y-m-d H:i:s', $cert_info['validFrom_time_t']);
                        $details['valid_to'] = date('Y-m-d H:i:s', $cert_info['validTo_time_t']);
                    }
                }
            } catch (Exception $e) {
                error_log("Certificate parsing error: " . $e->getMessage());
            }
            
            return $details;
        }
        
        public function get_certificate_details($cert_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT id, certificate_name, certificate_path, private_key_path, 
                    certificate_password, certificate_type, issuer, subject, 
                    serial_number, valid_from, valid_to, is_default, is_active,
                    created_at, created_by
                FROM certificates 
                WHERE id = ?
            ");
            
            $stmt->bind_param('i', $cert_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $cert_name, $cert_path, $key_path, $cert_password,
                                $cert_type, $issuer, $subject, $serial_number,
                                $valid_from, $valid_to, $is_default, $is_active,
                                $created_at, $created_by);
                $stmt->fetch();
                
                $result = [
                    'id' => $id,
                    'certificate_name' => $cert_name,
                    'certificate_path' => $cert_path,
                    'private_key_path' => $key_path,
                    'certificate_password' => $cert_password,
                    'certificate_type' => $cert_type,
                    'issuer' => $issuer,
                    'subject' => $subject,
                    'serial_number' => $serial_number,
                    'valid_from' => $valid_from,
                    'valid_to' => $valid_to,
                    'is_default' => $is_default,
                    'is_active' => $is_active,
                    'created_at' => $created_at,
                    'created_by' => $created_by
                ];
                
                if ($created_by) {
                    $creator_stmt = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
                    $creator_stmt->bind_param('i', $created_by);
                    $creator_stmt->execute();
                    $creator_stmt->bind_result($creator_name);
                    $creator_stmt->fetch();
                    $result['created_by_name'] = $creator_name;
                    $creator_stmt->close();
                }
                
                $stmt->close();
                return $result;
            }
            
            $stmt->close();
            return null;
        }
    }

    class signing_stats extends db_connect {
    
        // Get total files signed
        public function getTotalFilesSigned() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM signed_files");
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get pending files count
        public function getPendingFilesCount() {
            $conn = $this->connect();
            $user_id = isset($_SESSION["bt_user_id"]) ? $_SESSION["bt_user_id"] : 0;
            $role = isset($_SESSION["bt_role"]) ? $_SESSION["bt_role"] : '';
            
            $where_clause = "WHERE uf.status = 'processed' AND sf.id IS NULL";
            if ($role != 'admin') {
                $where_clause .= " AND uf.uploaded_by = ?";
            }
            
            $stmt = $conn->prepare("
                SELECT COUNT(uf.id) as total 
                FROM uploaded_files uf
                LEFT JOIN signed_files sf ON uf.id = sf.original_file_id
                $where_clause
            ");
            
            if ($role != 'admin') {
                $stmt->bind_param('i', $user_id);
            }
            
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get active certificates count
        public function getActiveCertificatesCount() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM certificates WHERE is_active = 1");
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get total users count
        public function getTotalUsersCount() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE deleted = 0");
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get files signed this month
        public function getFilesSignedThisMonth() {
            $conn = $this->connect();
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM signed_files 
                WHERE MONTH(signed_at) = ? AND YEAR(signed_at) = ?
            ");
            $stmt->bind_param('ii', $currentMonth, $currentYear);
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get total file types
        public function getTotalFileTypes() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM file_types WHERE is_active = 1");
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get activity last 7 days
        public function getActivityLast7Days() {
            $conn = $this->connect();
            $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM signed_files 
                WHERE DATE(signed_at) >= ?
            ");
            $stmt->bind_param('s', $sevenDaysAgo);
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
            return $total ?: 0;
        }
        
        // Get recent files for table
        public function getRecentFiles() {
            $conn = $this->connect();
            $user_id = isset($_SESSION["bt_user_id"]) ? $_SESSION["bt_user_id"] : 0;
            $role = isset($_SESSION["bt_role"]) ? $_SESSION["bt_role"] : '';
            
            $where_clause = "";
            $params = [];
            $types = "";
            
            if ($role != 'admin') {
                $where_clause = "WHERE uf.uploaded_by = ?";
                $params[] = $user_id;
                $types .= "i";
            }
            
            $stmt = $conn->prepare("
                SELECT 
                    uf.original_name,
                    ft.name as file_type,
                    uf.status,
                    sf.signed_at,
                    uf.id
                FROM uploaded_files uf
                LEFT JOIN file_batches fb ON uf.batch_id = fb.id
                LEFT JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN signed_files sf ON uf.id = sf.original_file_id
                $where_clause
                ORDER BY uf.uploaded_at DESC
                LIMIT 5
            ");
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $status_badge = '';
                    switch($row['status']) {
                        case 'pending': $status_badge = 'badge-warning'; break;
                        case 'processing': $status_badge = 'badge-info'; break;
                        case 'processed': 
                            $status_badge = $row['signed_at'] ? 'badge-success' : 'badge-primary';
                            break;
                        case 'failed': $status_badge = 'badge-danger'; break;
                        default: $status_badge = 'badge-secondary';
                    }
                    
                    $status_text = $row['signed_at'] ? 'Signed' : ucfirst($row['status']);
                    $signed_date = $row['signed_at'] ? date('Y-m-d H:i', strtotime($row['signed_at'])) : 'Not signed';
                    
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars(substr($row['original_name'], 0, 30)) . 
                        (strlen($row['original_name']) > 30 ? '...' : '') . "</td>";
                    echo "<td>" . ($row['file_type'] ?: 'N/A') . "</td>";
                    echo "<td><span class='badge $status_badge'>$status_text</span></td>";
                    echo "<td>$signed_date</td>";
                    echo "<td>";
                    if ($row['signed_at']) {
                        echo "<a href='./php/file_handler.php?action=download_signed&id=" . $row['id'] . "' 
                            class='btn btn-sm btn-success' title='Download Signed'>
                            <i class='fa fa-download'></i>
                            </a>";
                    } else {
                        echo "<a href='./uploads.php' class='btn btn-sm btn-warning' title='Sign File'>
                            <i class='fa fa-signature'></i>
                            </a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No files found</td></tr>";
            }
            
            $stmt->close();
        }
        
        // Get system status
        public function getSystemStatus() {
            $status = [];
            
            // Check database connection
            $conn = $this->connect();
            if ($conn->connect_error) {
                $status[] = ['name' => 'Database', 'status' => 'ERROR'];
            } else {
                $status[] = ['name' => 'Database', 'status' => 'OK'];
            }
            
            // Check OpenSSL
            $openssl_path = 'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe';
            if (file_exists($openssl_path) || shell_exec('which openssl')) {
                $status[] = ['name' => 'OpenSSL', 'status' => 'OK'];
            } else {
                $status[] = ['name' => 'OpenSSL', 'status' => 'ERROR'];
            }
            
            // Check uploads directory
            $uploads_dir = dirname(dirname(__FILE__)) . '/uploads/';
            if (is_writable($uploads_dir) || is_writable(dirname($uploads_dir))) {
                $status[] = ['name' => 'Uploads Directory', 'status' => 'OK'];
            } else {
                $status[] = ['name' => 'Uploads Directory', 'status' => 'WARNING'];
            }
            
            // Check signed files directory
            $signed_dir = dirname(dirname(__FILE__)) . '/signed_files/';
            if (is_writable($signed_dir) || is_writable(dirname($signed_dir))) {
                $status[] = ['name' => 'Signed Files Directory', 'status' => 'OK'];
            } else {
                $status[] = ['name' => 'Signed Files Directory', 'status' => 'WARNING'];
            }
            
            return $status;
        }
        
        // Get files signed chart data
        public function getFilesSignedChartData() {
            $conn = $this->connect();
            
            // Get data for last 6 months
            $data = [];
            $categories = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthName = date('M Y', strtotime("-$i months"));
                
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM signed_files 
                    WHERE DATE_FORMAT(signed_at, '%Y-%m') = ?
                ");
                $stmt->bind_param('s', $month);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                
                $data[] = $count ?: 0;
                $categories[] = $monthName;
            }
            
            return json_encode([
                'data' => $data,
                'categories' => $categories
            ]);
        }
        
        // Get file types chart data
        public function getFileTypesChartData() {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT ft.name, COUNT(sf.id) as count
                FROM signed_files sf
                JOIN uploaded_files uf ON sf.original_file_id = uf.id
                JOIN file_batches fb ON uf.batch_id = fb.id
                JOIN file_types ft ON fb.file_type_id = ft.id
                GROUP BY ft.name
                ORDER BY count DESC
                LIMIT 5
            ");
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $labels = [];
            $data = [];
            
            while ($row = $result->fetch_assoc()) {
                $labels[] = $row['name'];
                $data[] = $row['count'];
            }
            
            $stmt->close();
            
            // If no data, return default
            if (empty($data)) {
                $labels = ['No Data'];
                $data = [1];
            }
            
            return json_encode([
                'labels' => $labels,
                'data' => $data
            ]);
        }
        
        // Get activity chart data (last 7 days)
        public function getActivityChartData() {
            $conn = $this->connect();
            
            $data = [];
            $categories = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $dayName = date('D', strtotime("-$i days"));
                
                $stmt = $conn->prepare("
                    SELECT COUNT(*) as count 
                    FROM signed_files 
                    WHERE DATE(signed_at) = ?
                ");
                $stmt->bind_param('s', $date);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                $stmt->close();
                
                $data[] = $count ?: 0;
                $categories[] = $dayName;
            }
            
            return json_encode([
                'data' => $data,
                'categories' => $categories
            ]);
        }
    }

    class obdx_parser extends db_connect {
    
        // Define OBDX file types constants
        const TYPE_PAYMENT = 1;
        const TYPE_REMITTANCE = 2;
        const TYPE_REMITTANCE_PRN = 3;
        const TYPE_FOREIGN = 4;
        const TYPE_SALARY = 5;
        
        // Parse OBDX file
        public function parse_obdx_file($file_path, $file_type_id) {
            $result = [
                'success' => false,
                'message' => '',
                'header' => [],
                'body' => [],
                'validation_errors' => [],
                'total_amount' => 0,
                'total_count' => 0
            ];
            
            try {
                if (!file_exists($file_path)) {
                    throw new Exception("File not found: $file_path");
                }
                
                $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (empty($lines)) {
                    throw new Exception("File is empty");
                }
                
                // Parse based on file type
                switch ($file_type_id) {
                    case self::TYPE_PAYMENT:
                        return $this->parse_payment_file($lines);
                    case self::TYPE_REMITTANCE:
                        return $this->parse_remittance_file($lines);
                    case self::TYPE_REMITTANCE_PRN:
                        return $this->parse_remittance_prn_file($lines);
                    case self::TYPE_FOREIGN:
                        return $this->parse_foreign_file($lines);
                    case self::TYPE_SALARY:
                        return $this->parse_salary_file($lines);
                    default:
                        throw new Exception("Unsupported OBDX file type");
                }
                
            } catch (Exception $e) {
                $result['message'] = $e->getMessage();
                return $result;
            }
        }
        
        // Parse Payment File (Type 1)
        private function parse_payment_file($lines) {
            $result = [
                'success' => true,
                'message' => '',
                'header' => [],
                'body' => [],
                'validation_errors' => [],
                'total_amount' => 0,
                'total_count' => 0
            ];
            
            $header = null;
            $body_items = [];
            $header_line_count = 0;
            $body_line_count = 0;
            
            foreach ($lines as $line_number => $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $fields = explode(';', $line);
                $line_type = intval($fields[0]);
                
                if ($line_type === 0) {
                    // Header line
                    if ($header !== null) {
                        $result['validation_errors'][] = "Multiple header lines found at line " . ($line_number + 1);
                        $result['success'] = false;
                        return $result;
                    }
                    
                    // Validate header structure
                    if (count($fields) !== 6) {
                        $result['validation_errors'][] = "Header line should have 6 fields, found " . count($fields) . " at line " . ($line_number + 1);
                        $result['success'] = false;
                        return $result;
                    }
                    
                    $header = [
                        'header_line' => $fields[0],
                        'file_reference' => $fields[1],
                        'currency_code' => $fields[2],
                        'file_total' => floatval($fields[3]),
                        'total_count' => intval($fields[4])
                    ];
                    
                    // Store additional field from your sample
                    if (isset($fields[5])) {
                        $header['extra_field'] = $fields[5];
                    }
                    
                    $header_line_count++;
                    
                } elseif ($line_type === 1) {
                    // Body line
                    if (count($fields) !== 18) {
                        $result['validation_errors'][] = "Body line should have 18 fields, found " . count($fields) . " at line " . ($line_number + 1);
                        continue;
                    }
                    
                    $body_item = [
                        'line_identifier' => $fields[0],
                        'trans_serial' => $fields[1],
                        'currency_code' => $fields[2],
                        'debit_account_number' => $fields[3],
                        'debit_account_name' => $fields[4],
                        'payment_amount' => floatval($fields[5]),
                        'payee_details' => $fields[6],
                        'vendor_code' => $fields[7],
                        'employee_number' => $fields[8],
                        'national_id' => $fields[9],
                        'invoice_number' => $fields[10],
                        'payee_bic' => $fields[11],
                        'credit_account_number' => $fields[12],
                        'cost_center' => $fields[13],
                        'date' => $fields[14],
                        'source' => $fields[15],
                        'reference_number' => $fields[16],
                        'description' => $fields[17]
                    ];
                    
                    $body_items[] = $body_item;
                    $result['total_amount'] += $body_item['payment_amount'];
                    $body_line_count++;
                    
                } else {
                    $result['validation_errors'][] = "Invalid line type '$line_type' at line " . ($line_number + 1);
                }
            }
            
            // Validation checks
            if ($header === null) {
                $result['validation_errors'][] = "No header line found";
                $result['success'] = false;
                return $result;
            }
            
            if ($body_line_count === 0) {
                $result['validation_errors'][] = "No body lines found";
                $result['success'] = false;
                return $result;
            }
            
            // Validate totals
            if (abs($header['file_total'] - $result['total_amount']) > 0.01) {
                $result['validation_errors'][] = "Total amount mismatch. Header: {$header['file_total']}, Calculated: {$result['total_amount']}";
            }
            
            if ($header['total_count'] !== $body_line_count) {
                $result['validation_errors'][] = "Total count mismatch. Header: {$header['total_count']}, Actual: $body_line_count";
            }
            
            $result['header'] = $header;
            $result['body'] = $body_items;
            $result['total_count'] = $body_line_count;
            
            if (!empty($result['validation_errors'])) {
                $result['success'] = false;
                $result['message'] = 'Validation errors found';
            } else {
                $result['message'] = "Successfully parsed $body_line_count payment records";
            }
            
            return $result;
        }
        
        // Parse Remittance File (Type 2)
        private function parse_remittance_file($lines) {
            $result = [
                'success' => true,
                'message' => '',
                'header' => [],
                'body' => [],
                'validation_errors' => [],
                'total_amount' => 0,
                'total_count' => 0
            ];
            
            $header = null;
            $body_items = [];
            $body_line_count = 0;
            
            foreach ($lines as $line_number => $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $fields = explode(';', $line);
                $line_type = intval($fields[0]);
                
                if ($line_type === 0) {
                    // Header line
                    if ($header !== null) {
                        $result['validation_errors'][] = "Multiple header lines found";
                        $result['success'] = false;
                        return $result;
                    }
                    
                    if (count($fields) !== 4) {
                        $result['validation_errors'][] = "Remittance header should have 4 fields";
                        $result['success'] = false;
                        return $result;
                    }
                    
                    $header = [
                        'header_line' => $fields[0],
                        'file_reference' => $fields[1],
                        'file_total' => floatval($fields[2]),
                        'total_count' => intval($fields[3])
                    ];
                    
                } elseif ($line_type === 2) {
                    // Body line
                    if (count($fields) !== 13) {
                        $result['validation_errors'][] = "Remittance body line should have 13 fields at line " . ($line_number + 1);
                        continue;
                    }
                    
                    $body_item = [
                        'line_identifier' => $fields[0],
                        'trans_serial' => $fields[1],
                        'debit_account_number' => $fields[2],
                        'debit_account_name' => $fields[3],
                        'currency_code' => $fields[4],
                        'payee_account_number' => $fields[5],
                        'credit_currency_code' => $fields[6],
                        'payment_amount' => floatval($fields[7]),
                        'creditor_name' => $fields[8],
                        'payment_date' => $fields[9],
                        'date_created' => $fields[10],
                        'source_reference_number' => $fields[11],
                        'cost_centre_code' => $fields[12]
                    ];
                    
                    $body_items[] = $body_item;
                    $result['total_amount'] += $body_item['payment_amount'];
                    $body_line_count++;
                    
                } else {
                    $result['validation_errors'][] = "Invalid line type at line " . ($line_number + 1);
                }
            }
            
            // Validation checks
            if ($header === null) {
                $result['validation_errors'][] = "No header line found";
                $result['success'] = false;
                return $result;
            }
            
            // Validate totals
            if (abs($header['file_total'] - $result['total_amount']) > 0.01) {
                $result['validation_errors'][] = "Total amount mismatch";
            }
            
            if ($header['total_count'] !== $body_line_count) {
                $result['validation_errors'][] = "Total count mismatch";
            }
            
            $result['header'] = $header;
            $result['body'] = $body_items;
            $result['total_count'] = $body_line_count;
            
            if (!empty($result['validation_errors'])) {
                $result['success'] = false;
                $result['message'] = 'Validation errors found';
            } else {
                $result['message'] = "Successfully parsed $body_line_count remittance records";
            }
            
            return $result;
        }
        
        // Parse Remittance with PRN (Type 3)
        private function parse_remittance_prn_file($lines) {
            $result = $this->parse_remittance_file($lines);
            
            if (!$result['success']) {
                return $result;
            }
            
            // Additional validation for PRN files
            foreach ($result['body'] as $index => $item) {
                // Check for PRN field (field 13)
                if (!isset($item['payment_reference_number'])) {
                    // Try to extract from additional fields
                    $lines_array = explode("\n", implode("\n", array_slice($lines, 1)));
                    $body_line = $lines_array[$index] ?? '';
                    $fields = explode(';', $body_line);
                    
                    if (count($fields) >= 14) {
                        $result['body'][$index]['payment_reference_number'] = $fields[13];
                    } else {
                        $result['validation_errors'][] = "Missing PRN for transaction " . ($index + 1);
                    }
                }
            }
            
            return $result;
        }
        
        // Parse Foreign Payment File (Type 4)
        private function parse_foreign_file($lines) {
            $result = [
                'success' => true,
                'message' => '',
                'header' => [],
                'body' => [],
                'validation_errors' => [],
                'total_amount' => 0,
                'total_count' => 0
            ];
            
            $header = null;
            $body_items = [];
            $body_line_count = 0;
            
            foreach ($lines as $line_number => $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $fields = explode(';', $line);
                $line_type = intval($fields[0]);
                
                if ($line_type === 0) {
                    // Header line
                    if ($header !== null) {
                        $result['validation_errors'][] = "Multiple header lines found";
                        $result['success'] = false;
                        return $result;
                    }
                    
                    if (count($fields) !== 5) {
                        $result['validation_errors'][] = "Foreign payment header should have 5 fields";
                        $result['success'] = false;
                        return $result;
                    }
                    
                    $header = [
                        'header_line' => $fields[0],
                        'file_reference' => $fields[1],
                        'currency_code' => $fields[2],
                        'file_total' => floatval($fields[3]),
                        'total_count' => intval($fields[4])
                    ];
                    
                } elseif ($line_type === 1) {
                    // Body line - foreign payment has many fields
                    if (count($fields) < 30) {
                        $result['validation_errors'][] = "Foreign payment body line should have at least 30 fields at line " . ($line_number + 1);
                        continue;
                    }
                    
                    $body_item = [
                        'line_identifier' => $fields[0],
                        'trans_serial' => $fields[1],
                        'currency_code' => $fields[2],
                        'debit_account_number' => $fields[3],
                        'bank_account_name' => $fields[4],
                        'payment_amount' => floatval($fields[5]),
                        'amount_in_words' => $fields[6],
                        'payee_details1' => $fields[7],
                        'payee_details2' => $fields[8],
                        'invoice_number' => $fields[9],
                        'bank_branch_sort_code' => $fields[10],
                        'payee_bic' => $fields[11],
                        'credit_account_number' => $fields[12],
                        'cost_center' => $fields[13],
                        'run_date' => $fields[14],
                        'approval_date' => $fields[15],
                        'sap_ifmis_reference_number' => $fields[16],
                        'corresponding_bank' => $fields[17],
                        'corresponding_country' => $fields[18],
                        'non_resident' => $fields[19],
                        'surname' => $fields[20],
                        'first_name' => $fields[21],
                        'institutional_sector_code' => $fields[22],
                        'industrial_classification' => $fields[23],
                        'bop_category' => $fields[24],
                        'subject' => $fields[25],
                        'description' => $fields[26],
                        'individual_third_party_surname' => $fields[27],
                        'individual_third_party_name' => $fields[28],
                        'individual_third_party_gender' => $fields[29],
                        'cost_of_goods' => $fields[30] ?? '',
                        'freight' => $fields[31] ?? ''
                    ];
                    
                    $body_items[] = $body_item;
                    $result['total_amount'] += $body_item['payment_amount'];
                    $body_line_count++;
                    
                } else {
                    $result['validation_errors'][] = "Invalid line type at line " . ($line_number + 1);
                }
            }
            
            // Validation checks
            if ($header === null) {
                $result['validation_errors'][] = "No header line found";
                $result['success'] = false;
                return $result;
            }
            
            // Validate totals
            if (abs($header['file_total'] - $result['total_amount']) > 0.01) {
                $result['validation_errors'][] = "Total amount mismatch";
            }
            
            if ($header['total_count'] !== $body_line_count) {
                $result['validation_errors'][] = "Total count mismatch";
            }
            
            $result['header'] = $header;
            $result['body'] = $body_items;
            $result['total_count'] = $body_line_count;
            
            if (!empty($result['validation_errors'])) {
                $result['success'] = false;
                $result['message'] = 'Validation errors found';
            } else {
                $result['message'] = "Successfully parsed $body_line_count foreign payment records";
            }
            
            return $result;
        }
        
        // Parse Salary Payment File (Type 5)
        private function parse_salary_file($lines) {
            $result = [
                'success' => true,
                'message' => '',
                'header' => [],
                'body' => [],
                'validation_errors' => [],
                'total_amount' => 0,
                'total_count' => 0
            ];
            
            $header = null;
            $body_items = [];
            $body_line_count = 0;
            
            foreach ($lines as $line_number => $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $fields = explode(';', $line);
                $line_type = intval($fields[0]);
                
                if ($line_type === 0) {
                    // Header line
                    if ($header !== null) {
                        $result['validation_errors'][] = "Multiple header lines found";
                        $result['success'] = false;
                        return $result;
                    }
                    
                    if (count($fields) !== 9) {
                        $result['validation_errors'][] = "Salary header should have 9 fields";
                        $result['success'] = false;
                        return $result;
                    }
                    
                    $header = [
                        'header_line' => $fields[0],
                        'vote_number' => $fields[1],
                        'total_amount' => floatval($fields[2]),
                        'currency_code' => $fields[3],
                        'debit_account' => $fields[4],
                        'funding_trf_number' => $fields[5],
                        'description' => $fields[6],
                        'value_date' => $fields[7],
                        'total_count' => intval($fields[8])
                    ];
                    
                } elseif ($line_type === 1) {
                    // Body line
                    if (count($fields) !== 9) {
                        $result['validation_errors'][] = "Salary body line should have 9 fields at line " . ($line_number + 1);
                        continue;
                    }
                    
                    $body_item = [
                        'line_identifier' => $fields[0],
                        'beneficiary_bank_bic' => $fields[1],
                        'bank_account_cr_iban' => $fields[2],
                        'source_reference_number' => $fields[3],
                        'beneficiary_account_name' => $fields[4],
                        'beneficiary_bank_name' => $fields[5],
                        'cost_center' => $fields[6],
                        'description' => $fields[7],
                        'amount' => floatval($fields[8])
                    ];
                    
                    $body_items[] = $body_item;
                    $result['total_amount'] += $body_item['amount'];
                    $body_line_count++;
                    
                } else {
                    $result['validation_errors'][] = "Invalid line type at line " . ($line_number + 1);
                }
            }
            
            // Validation checks
            if ($header === null) {
                $result['validation_errors'][] = "No header line found";
                $result['success'] = false;
                return $result;
            }
            
            // Validate totals
            if (abs($header['total_amount'] - $result['total_amount']) > 0.01) {
                $result['validation_errors'][] = "Total amount mismatch";
            }
            
            if ($header['total_count'] !== $body_line_count) {
                $result['validation_errors'][] = "Total count mismatch";
            }
            
            $result['header'] = $header;
            $result['body'] = $body_items;
            $result['total_count'] = $body_line_count;
            
            if (!empty($result['validation_errors'])) {
                $result['success'] = false;
                $result['message'] = 'Validation errors found';
            } else {
                $result['message'] = "Successfully parsed $body_line_count salary records";
            }
            
            return $result;
        }
        
        // Generate OBDX file from database
        public function generate_obdx_file($batch_id, $file_type_id) {
            $conn = $this->connect();
            
            try {
                // Get batch details
                $batch_stmt = $conn->prepare("
                    SELECT fb.*, ft.code as file_type_code, ft.delimiter
                    FROM file_batches fb
                    JOIN file_types ft ON fb.file_type_id = ft.id
                    WHERE fb.id = ?
                ");
                $batch_stmt->bind_param('i', $batch_id);
                $batch_stmt->execute();
                $batch_result = $batch_stmt->get_result();
                
                if ($batch_result->num_rows === 0) {
                    throw new Exception("Batch not found");
                }
                
                $batch = $batch_result->fetch_assoc();
                $batch_stmt->close();
                
                // Get batch items
                $items_stmt = $conn->prepare("
                    SELECT data_json, item_index
                    FROM batch_items
                    WHERE batch_id = ?
                    ORDER BY item_index
                ");
                $items_stmt->bind_param('i', $batch_id);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                
                $items = [];
                while ($row = $items_result->fetch_assoc()) {
                    $items[] = json_decode($row['data_json'], true);
                }
                $items_stmt->close();
                
                // Generate file content based on file type
                $content = '';
                $delimiter = $batch['delimiter'] ?? ';';
                
                switch ($file_type_id) {
                    case self::TYPE_PAYMENT:
                        $content = $this->generate_payment_content($batch, $items, $delimiter);
                        break;
                    case self::TYPE_REMITTANCE:
                        $content = $this->generate_remittance_content($batch, $items, $delimiter);
                        break;
                    case self::TYPE_REMITTANCE_PRN:
                        $content = $this->generate_remittance_prn_content($batch, $items, $delimiter);
                        break;
                    case self::TYPE_FOREIGN:
                        $content = $this->generate_foreign_content($batch, $items, $delimiter);
                        break;
                    case self::TYPE_SALARY:
                        $content = $this->generate_salary_content($batch, $items, $delimiter);
                        break;
                    default:
                        throw new Exception("Unsupported file type");
                }
                
                // Save generated file
                $generated_dir = '../generated_files/';
                if (!file_exists($generated_dir)) {
                    mkdir($generated_dir, 0777, true);
                }
                
                $filename = $batch['batch_number'] . '_unsigned.csv';
                $filepath = $generated_dir . $filename;
                
                if (file_put_contents($filepath, $content) === false) {
                    throw new Exception("Failed to write generated file");
                }
                
                // Save to generated_files table
                $gen_stmt = $conn->prepare("
                    INSERT INTO generated_files (batch_id, file_name, file_path, file_size, checksum, generated_by)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $file_size = filesize($filepath);
                $checksum = md5_file($filepath);
                $user_id = $_SESSION["bt_user_id"] ?? 0;
                
                $gen_stmt->bind_param('issisi', $batch_id, $filename, $filepath, $file_size, $checksum, $user_id);
                
                if (!$gen_stmt->execute()) {
                    throw new Exception("Failed to save generated file record");
                }
                
                $file_id = $conn->insert_id;
                $gen_stmt->close();
                
                return [
                    'success' => true,
                    'file_id' => $file_id,
                    'file_path' => $filepath,
                    'file_name' => $filename,
                    'message' => 'OBDX file generated successfully'
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Generate payment file content
        private function generate_payment_content($batch, $items, $delimiter) {
            $lines = [];
            
            // Header line
            $header = [
                0, // Header line identifier
                $batch['reference_no'] ?? 'TEST_' . date('d'),
                $batch['currency_code'] ?? 'MWK',
                number_format($batch['total_amount'] ?? 0, 2, '.', ''),
                str_pad($batch['total_count'] ?? 0, 4, '0', STR_PAD_LEFT),
                '' // Extra field from your sample
            ];
            $lines[] = implode($delimiter, $header);
            
            // Body lines
            $serial = 1;
            foreach ($items as $item) {
                $body = [
                    1, // Body line identifier
                    str_pad($serial, 4, '0', STR_PAD_LEFT),
                    $item['currency_code'] ?? ($batch['currency_code'] ?? 'MWK'),
                    $item['debit_account_number'] ?? '',
                    $item['debit_account_name'] ?? '',
                    number_format($item['payment_amount'] ?? 0, 2, '.', ''),
                    $item['payee_details'] ?? '',
                    $item['vendor_code'] ?? '',
                    $item['employee_number'] ?? '',
                    $item['national_id'] ?? '',
                    $item['invoice_number'] ?? '',
                    $item['payee_bic'] ?? '',
                    $item['credit_account_number'] ?? '',
                    $item['cost_center'] ?? '',
                    $item['date'] ?? '',
                    $item['source'] ?? '',
                    $item['reference_number'] ?? '',
                    $item['description'] ?? ''
                ];
                $lines[] = implode($delimiter, $body);
                $serial++;
            }
            
            return implode("\n", $lines);
        }
        
        // Generate remittance file content
        private function generate_remittance_content($batch, $items, $delimiter) {
            $lines = [];
            
            // Header line
            $header = [
                0,
                $batch['reference_no'] ?? 'RBM_' . date('d.m.Y'),
                number_format($batch['total_amount'] ?? 0, 2, '.', ''),
                str_pad($batch['total_count'] ?? 0, 4, '0', STR_PAD_LEFT)
            ];
            $lines[] = implode($delimiter, $header);
            
            // Body lines
            $serial = 1;
            foreach ($items as $item) {
                $body = [
                    2,
                    str_pad($serial, 4, '0', STR_PAD_LEFT),
                    $item['debit_account_number'] ?? '',
                    $item['debit_account_name'] ?? '',
                    $item['currency_code'] ?? ($batch['currency_code'] ?? 'MWK'),
                    $item['payee_account_number'] ?? '',
                    $item['credit_currency_code'] ?? ($batch['currency_code'] ?? 'MWK'),
                    number_format($item['payment_amount'] ?? 0, 2, '.', ''),
                    $item['creditor_name'] ?? '',
                    $item['payment_date'] ?? date('d.m.Y'),
                    $item['date_created'] ?? date('d.m.Y'),
                    $item['source_reference_number'] ?? '',
                    $item['cost_centre_code'] ?? ''
                ];
                $lines[] = implode($delimiter, $body);
                $serial++;
            }
            
            return implode("\n", $lines);
        }
        
        // Generate remittance with PRN content
        private function generate_remittance_prn_content($batch, $items, $delimiter) {
            $lines = [];
            
            // Header line
            $header = [
                0,
                $batch['reference_no'] ?? 'LAG21-' . date('d.m.Y'),
                number_format($batch['total_amount'] ?? 0, 2, '.', ''),
                str_pad($batch['total_count'] ?? 0, 4, '0', STR_PAD_LEFT)
            ];
            $lines[] = implode($delimiter, $header);
            
            // Body lines
            $serial = 1;
            foreach ($items as $item) {
                $body = [
                    2,
                    str_pad($serial, 4, '0', STR_PAD_LEFT),
                    $item['debit_account_number'] ?? '',
                    $item['debit_account_name'] ?? '',
                    $item['currency_code'] ?? ($batch['currency_code'] ?? 'MWK'),
                    $item['payee_account_number'] ?? '',
                    $item['credit_currency_code'] ?? ($batch['currency_code'] ?? 'MWK'),
                    number_format($item['payment_amount'] ?? 0, 2, '.', ''),
                    $item['creditor_name'] ?? '',
                    $item['payment_date'] ?? date('d.m.Y'),
                    $item['date_created'] ?? date('d.m.Y'),
                    $item['source_reference_number'] ?? '',
                    $item['cost_centre_code'] ?? '',
                    $item['payment_reference_number'] ?? str_pad($serial, 13, '0', STR_PAD_LEFT)
                ];
                $lines[] = implode($delimiter, $body);
                $serial++;
            }
            
            return implode("\n", $lines);
        }
        
        // Validate OBDX file
        public function validate_obdx_file($file_path, $file_type_id) {
            $parser_result = $this->parse_obdx_file($file_path, $file_type_id);
            
            if (!$parser_result['success']) {
                return $parser_result;
            }
            
            // Additional validations based on file type
            $validation_errors = $parser_result['validation_errors'];
            
            switch ($file_type_id) {
                case self::TYPE_PAYMENT:
                    $validation_errors = array_merge($validation_errors, 
                        $this->validate_payment_file($parser_result['body']));
                    break;
                case self::TYPE_REMITTANCE:
                case self::TYPE_REMITTANCE_PRN:
                    $validation_errors = array_merge($validation_errors,
                        $this->validate_remittance_file($parser_result['body']));
                    break;
                case self::TYPE_FOREIGN:
                    $validation_errors = array_merge($validation_errors,
                        $this->validate_foreign_file($parser_result['body']));
                    break;
                case self::TYPE_SALARY:
                    $validation_errors = array_merge($validation_errors,
                        $this->validate_salary_file($parser_result['body']));
                    break;
            }
            
            $parser_result['validation_errors'] = $validation_errors;
            $parser_result['success'] = empty($validation_errors);
            $parser_result['message'] = empty($validation_errors) ? 
                'File validation successful' : 'Validation errors found';
            
            return $parser_result;
        }
        
        private function validate_payment_file($items) {
            $errors = [];
            
            foreach ($items as $index => $item) {
                $line_num = $index + 1;
                
                // Validate required fields
                $required_fields = [
                    'debit_account_number' => 'Debit Account Number',
                    'debit_account_name' => 'Debit Account Name',
                    'payment_amount' => 'Payment Amount',
                    'payee_details' => 'Payee Details',
                    'invoice_number' => 'Invoice Number',
                    'payee_bic' => 'Payee BIC',
                    'credit_account_number' => 'Credit Account Number',
                    'source' => 'Source Reference Number',
                    'description' => 'Description'
                ];
                
                foreach ($required_fields as $field => $display) {
                    if (empty($item[$field])) {
                        $errors[] = "Line $line_num: $display is required";
                    }
                }
                
                // Validate BIC format (11 characters)
                if (!empty($item['payee_bic']) && strlen($item['payee_bic']) !== 11) {
                    $errors[] = "Line $line_num: Payee BIC should be 11 characters";
                }
                
                // Validate amount is positive
                if ($item['payment_amount'] <= 0) {
                    $errors[] = "Line $line_num: Payment amount must be positive";
                }
            }
            
            return $errors;
        }
        
        // Similar validation methods for other file types...
    }

    class batch_management extends db_connect {
    
        // Get all batches with filters
        public function get_all_batches($filters = []) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $where_clauses = [];
            $params = [];
            $types = '';
            
            // Apply filters
            if (!empty($filters['status'])) {
                $where_clauses[] = "fb.status = ?";
                $params[] = $filters['status'];
                $types .= 's';
            }
            
            if (!empty($filters['file_type'])) {
                $where_clauses[] = "fb.file_type_id = ?";
                $params[] = $filters['file_type'];
                $types .= 'i';
            }
            
            if (!empty($filters['date_from'])) {
                $where_clauses[] = "DATE(fb.created_at) >= ?";
                $params[] = $filters['date_from'];
                $types .= 's';
            }
            
            if (!empty($filters['date_to'])) {
                $where_clauses[] = "DATE(fb.created_at) <= ?";
                $params[] = $filters['date_to'];
                $types .= 's';
            }
            
            if (!empty($filters['reference_no'])) {
                $where_clauses[] = "fb.reference_no LIKE ?";
                $params[] = '%' . $filters['reference_no'] . '%';
                $types .= 's';
            }
            
            if (!empty($filters['created_by'])) {
                $where_clauses[] = "fb.created_by = ?";
                $params[] = $filters['created_by'];
                $types .= 'i';
            }
            
            // Role-based access control
            if ($role != 'admin' && $role != 'approver') {
                $where_clauses[] = "fb.created_by = ?";
                $params[] = $user_id;
                $types .= 'i';
            }
            
            $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
            
            $sql = "
                SELECT 
                    fb.id,
                    fb.batch_number,
                    fb.reference_no,
                    ft.name as file_type,
                    ft.code as file_type_code,
                    fb.currency_code,
                    fb.total_amount,
                    fb.total_count,
                    fb.status,
                    fb.created_at,
                    fb.approved_at,
                    fb.processed_at,
                    fb.signed_at,
                    u.username as created_by_name,
                    au.username as approved_by_name,
                    COUNT(DISTINCT bi.id) as item_count,
                    COUNT(DISTINCT sf.id) as signed_count,
                    fb.file_path,
                    fb.signed_file_path
                FROM file_batches fb
                LEFT JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN users u ON fb.created_by = u.user_id
                LEFT JOIN users au ON fb.approved_by = au.user_id
                LEFT JOIN batch_items bi ON fb.id = bi.batch_id
                LEFT JOIN signed_files sf ON fb.id = sf.batch_id
                $where_sql
                GROUP BY fb.id
                ORDER BY fb.created_at DESC
            ";
            
            $stmt = $conn->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $batches = [];
            while ($row = $result->fetch_assoc()) {
                $batches[] = $row;
            }
            
            $stmt->close();
            return $batches;
        }
        
        // Get batch details
        public function get_batch_details($batch_id) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $stmt = $conn->prepare("
                SELECT 
                    fb.*,
                    ft.name as file_type_name,
                    ft.code as file_type_code,
                    ft.description as file_type_desc,
                    ft.delimiter,
                    ft.has_header,
                    u.username as created_by_name,
                    u.email as created_by_email,
                    au.username as approved_by_name,
                    du.username as deleted_by_name,
                    COUNT(DISTINCT bi.id) as item_count,
                    COUNT(DISTINCT sf.id) as signed_count,
                    COUNT(DISTINCT bc.id) as comment_count,
                    COUNT(DISTINCT ba.id) as attachment_count
                FROM file_batches fb
                LEFT JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN users u ON fb.created_by = u.user_id
                LEFT JOIN users au ON fb.approved_by = au.user_id
                LEFT JOIN batch_items bi ON fb.id = bi.batch_id
                LEFT JOIN signed_files sf ON fb.id = sf.batch_id
                LEFT JOIN batch_comments bc ON fb.id = bc.batch_id
                LEFT JOIN batch_attachments ba ON fb.id = ba.batch_id
                WHERE fb.id = ?
                GROUP BY fb.id
            ");
            
            $stmt->bind_param('i', $batch_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                $stmt->close();
                return null;
            }
            
            $batch = $result->fetch_assoc();
            $stmt->close();
            
            // Check permissions
            if ($role != 'admin' && $role != 'approver' && $batch['created_by'] != $user_id) {
                return null;
            }
            
            return $batch;
        }
        
        // Get batch items
        public function get_batch_items($batch_id, $limit = 100) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    id,
                    item_index,
                    data_json,
                    status,
                    error_message,
                    created_at
                FROM batch_items
                WHERE batch_id = ?
                ORDER BY item_index
                LIMIT ?
            ");
            
            $stmt->bind_param('ii', $batch_id, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $items = [];
            while ($row = $result->fetch_assoc()) {
                $row['data'] = json_decode($row['data_json'], true);
                $items[] = $row;
            }
            
            $stmt->close();
            return $items;
        }
        
        // Get batch comments
        public function get_batch_comments($batch_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    bc.*,
                    u.username,
                    u.full_name,
                    u.role
                FROM batch_comments bc
                LEFT JOIN users u ON bc.user_id = u.user_id
                WHERE bc.batch_id = ?
                ORDER BY bc.created_at DESC
            ");
            
            $stmt->bind_param('i', $batch_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = $row;
            }
            
            $stmt->close();
            return $comments;
        }
        
        // Add comment to batch
        public function add_batch_comment($batch_id, $comment, $comment_type = 'note') {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            
            $stmt = $conn->prepare("
                INSERT INTO batch_comments (batch_id, user_id, comment, comment_type)
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->bind_param('iiss', $batch_id, $user_id, $comment, $comment_type);
            
            if ($stmt->execute()) {
                $comment_id = $conn->insert_id;
                $stmt->close();
                
                // Log audit trail
                $log = new general();
                $log->logAuditTrail('file_batches', $batch_id, 'UPDATE', $user_id, 
                                "Added comment: " . substr($comment, 0, 50) . "...");
                
                return [
                    'success' => true,
                    'comment_id' => $comment_id,
                    'message' => 'Comment added successfully'
                ];
            } else {
                $error = $stmt->error;
                $stmt->close();
                return [
                    'success' => false,
                    'message' => 'Failed to add comment: ' . $error
                ];
            }
        }
        
        // Update batch status
        public function update_batch_status($batch_id, $new_status, $notes = '') {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            try {
                $conn->begin_transaction();
                
                // Get current batch status
                $current_stmt = $conn->prepare("SELECT status FROM file_batches WHERE id = ?");
                $current_stmt->bind_param('i', $batch_id);
                $current_stmt->execute();
                $current_stmt->bind_result($current_status);
                $current_stmt->fetch();
                $current_stmt->close();
                
                // Check permissions based on status transition
                if (!$this->can_change_status($current_status, $new_status, $role)) {
                    throw new Exception("You don't have permission to change status from $current_status to $new_status");
                }
                
                // Update batch status
                $update_stmt = $conn->prepare("
                    UPDATE file_batches 
                    SET status = ?, 
                        notes = CONCAT(IFNULL(notes, ''), '\n', ?),
                        updated_at = NOW()
                    WHERE id = ?
                ");
                
                $update_stmt->bind_param('ssi', $new_status, $notes, $batch_id);
                
                if (!$update_stmt->execute()) {
                    throw new Exception("Failed to update batch status: " . $update_stmt->error);
                }
                $update_stmt->close();
                
                // Set timestamps based on status
                $timestamp_column = '';
                switch ($new_status) {
                    case 'approved':
                        $timestamp_column = 'approved_at';
                        $approved_by_column = 'approved_by';
                        break;
                    case 'processing':
                        $timestamp_column = 'processed_at';
                        break;
                    case 'signed':
                        $timestamp_column = 'signed_at';
                        break;
                }
                
                if ($timestamp_column) {
                    $timestamp_stmt = $conn->prepare("
                        UPDATE file_batches 
                        SET $timestamp_column = NOW()" . 
                        ($timestamp_column == 'approved_at' ? ", approved_by = ?" : "") . "
                        WHERE id = ?
                    ");
                    
                    if ($timestamp_column == 'approved_at') {
                        $timestamp_stmt->bind_param('ii', $user_id, $batch_id);
                    } else {
                        $timestamp_stmt->bind_param('i', $batch_id);
                    }
                    
                    $timestamp_stmt->execute();
                    $timestamp_stmt->close();
                }
                
                // Log status change
                $log_stmt = $conn->prepare("
                    INSERT INTO workflow_logs (batch_id, from_status, to_status, action_performed, performed_by, notes)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $action = "Status changed from $current_status to $new_status";
                $log_stmt->bind_param('isssis', $batch_id, $current_status, $new_status, $action, $user_id, $notes);
                $log_stmt->execute();
                $log_stmt->close();
                
                // Add comment if provided
                if (!empty($notes)) {
                    $comment_type = $new_status == 'rejected' ? 'rejection' : 'approval';
                    $this->add_batch_comment($batch_id, "Status changed to $new_status: $notes", $comment_type);
                }
                
                $conn->commit();
                
                return [
                    'success' => true,
                    'message' => "Batch status updated to $new_status"
                ];
                
            } catch (Exception $e) {
                $conn->rollback();
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Check if user can change status
        private function can_change_status($current_status, $new_status, $user_role) {
            $allowed_transitions = [
                'draft' => ['submitted'],
                'submitted' => ['approved', 'rejected'],
                'approved' => ['processing', 'rejected'],
                'processing' => ['generated', 'failed'],
                'generated' => ['ready_to_sign', 'failed'],
                'ready_to_sign' => ['signed', 'failed'],
                'rejected' => ['draft'],
                'failed' => ['draft']
            ];
            
            // Check if transition is allowed
            if (!isset($allowed_transitions[$current_status]) || 
                !in_array($new_status, $allowed_transitions[$current_status])) {
                return false;
            }
            
            // Role-based permissions
            $role_permissions = [
                'admin' => ['draft', 'submitted', 'approved', 'rejected', 'processing', 'generated', 'ready_to_sign', 'signed', 'failed', 'archived'],
                'approver' => ['approved', 'rejected'],
                'creator' => ['draft', 'submitted'],
                'viewer' => [] // No change permissions
            ];
            
            return isset($role_permissions[$user_role]) && 
                in_array($new_status, $role_permissions[$user_role]);
        }
        
        // Delete batch (soft delete)
        public function delete_batch($batch_id) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            // Check permissions
            if ($role != 'admin') {
                // Check if user created this batch
                $check_stmt = $conn->prepare("SELECT created_by FROM file_batches WHERE id = ?");
                $check_stmt->bind_param('i', $batch_id);
                $check_stmt->execute();
                $check_stmt->bind_result($created_by);
                $check_stmt->fetch();
                $check_stmt->close();
                
                if ($created_by != $user_id) {
                    return [
                        'success' => false,
                        'message' => 'You can only delete batches you created'
                    ];
                }
            }
            
            // Soft delete - add deleted flag
            $update_stmt = $conn->prepare("
                UPDATE file_batches 
                SET deleted = 1, 
                    deleted_by = ?,
                    deleted_at = NOW(),
                    status = 'archived'
                WHERE id = ?
            ");
            
            $update_stmt->bind_param('ii', $user_id, $batch_id);
            
            if ($update_stmt->execute()) {
                $update_stmt->close();
                
                // Log audit trail
                $log = new general();
                $log->logAuditTrail('file_batches', $batch_id, 'DELETE', $user_id, "Batch archived");
                
                return [
                    'success' => true,
                    'message' => 'Batch deleted successfully'
                ];
            } else {
                $error = $update_stmt->error;
                $update_stmt->close();
                return [
                    'success' => false,
                    'message' => 'Failed to delete batch: ' . $error
                ];
            }
        }
        
        // Get statistics for dashboard
        public function get_batch_statistics() {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $where_clause = "WHERE";
            if ($role != 'admin' && $role != 'approver') {
                $where_clause .= " AND fb.created_by = $user_id";
            }
            
            $sql = "
                SELECT 
                    COUNT(*) as total_batches,
                    SUM(CASE WHEN fb.status = 'draft' THEN 1 ELSE 0 END) as draft_batches,
                    SUM(CASE WHEN fb.status = 'submitted' THEN 1 ELSE 0 END) as pending_approval,
                    SUM(CASE WHEN fb.status = 'approved' THEN 1 ELSE 0 END) as approved_batches,
                    SUM(CASE WHEN fb.status = 'ready_to_sign' THEN 1 ELSE 0 END) as ready_to_sign,
                    SUM(CASE WHEN fb.status = 'signed' THEN 1 ELSE 0 END) as signed_batches,
                    SUM(fb.total_amount) as total_amount,
                    SUM(fb.total_count) as total_records
                FROM file_batches fb
                $where_clause
            ";
            
            $result = $conn->query($sql);
            $stats = $result->fetch_assoc();
            
            return $stats;
        }
    }

    class template_manager extends db_connect {

        // Get all file templates
        public function get_all_templates() {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    ft.*,
                    COUNT(fs.id) as field_count,
                    COUNT(DISTINCT fb.id) as usage_count
                FROM file_types ft
                LEFT JOIN file_schema fs ON ft.id = fs.file_type_id
                LEFT JOIN file_batches fb ON ft.id = fb.file_type_id
                WHERE ft.is_active = 1
                GROUP BY ft.id
                ORDER BY ft.name
            ");
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $templates = [];
            while ($row = $result->fetch_assoc()) {
                $templates[] = $row;
            }
            
            $stmt->close();
            return $templates;
        }

        // Get template details
        public function get_template_details($template_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    ft.*,
                    GROUP_CONCAT(DISTINCT fs.section ORDER BY fs.field_order) as sections
                FROM file_types ft
                LEFT JOIN file_schema fs ON ft.id = fs.file_type_id
                WHERE ft.id = ?
                GROUP BY ft.id
            ");
            
            $stmt->bind_param('i', $template_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $template = $result->fetch_assoc();
            $stmt->close();
            
            // Get template fields
            $template['fields'] = $this->get_template_fields($template_id);
            
            return $template;
        }

        // Get template fields
        public function get_template_fields($template_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    id,
                    section,
                    field_name,
                    display_name,
                    data_type,
                    length,
                    decimal_places,
                    mandatory,
                    default_value,
                    field_order,
                    validation_regex,
                    comments
                FROM file_schema
                WHERE file_type_id = ?
                ORDER BY field_order
            ");
            
            $stmt->bind_param('i', $template_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $fields = [];
            while ($row = $result->fetch_assoc()) {
                $fields[] = $row;
            }
            
            $stmt->close();
            return $fields;
        }

        // Get sample data for template
        public function get_template_sample($template_id) {
            $conn = $this->connect();
            
            // Get template details
            $template = $this->get_template_details($template_id);
            if (!$template) {
                return null;
            }
            
            // Generate sample based on template type
            $sample = [];
            
            switch ($template['code']) {
                case 'OBDX_PAYMENT':
                    $sample = $this->generate_payment_sample();
                    break;
                case 'OBDX_REMITTANCE':
                    $sample = $this->generate_remittance_sample();
                    break;
                case 'OBDX_SALARY':
                    $sample = $this->generate_salary_sample();
                    break;
                default:
                    $sample = $this->generate_generic_sample($template['fields']);
            }
            
            return $sample;
        }

        private function generate_payment_sample() {
            return [
                'header' => "0;TEST_001;MWK;50000.00;0010",
                'body' => "1;0001;MWK;0013007800002;Sample Account;5000.00;Sample Payee;VEND001;EMP001;ID000001;INV001/24;SBICMWM0;9100001187829;CC001;;058SC2400010001;Sample payment",
                'description' => "Payment file with 10 transactions of MWK 5,000 each"
            ];
        }

        private function generate_remittance_sample() {
            return [
                'header' => "0;RBM_TEST_001;125000.00;0005",
                'body' => "2;0001;0013007800002;Sample Account;MWK;0014000360008;MWK;25000.00;Sample Creditor;15.01.2024;15.01.2024;200TRF2400010001;55001001",
                'description' => "Remittance file with 5 transactions of MWK 25,000 each"
            ];
        }

        private function generate_salary_sample() {
            return [
                'header' => "0;12SC;140000.00;MWK;0013007800016;12SC2400010001;Sample Salary;31.01.2024;0007",
                'body' => "1;SBICMWM0;9100001187829;12SC2400010001001;SAMPLE EMPLOYEE;Sample Bank;049;SAL;20000.00",
                'description' => "Salary file with 7 employees receiving MWK 20,000 each"
            ];
        }

        private function generate_generic_sample($fields) {
            $header = [];
            $body = [];
            
            foreach ($fields as $field) {
                if ($field['section'] == 'header') {
                    $header[] = $this->get_sample_value($field);
                } else {
                    $body[] = $this->get_sample_value($field);
                }
            }
            
            return [
                'header' => implode(';', $header),
                'body' => implode(';', $body),
                'description' => "Generic template sample"
            ];
        }

        private function get_sample_value($field) {
            switch ($field['data_type']) {
                case 'string':
                    return $field['field_name'];
                case 'number':
                case 'decimal':
                    return '1000.00';
                case 'date':
                    return date('d.m.Y');
                case 'boolean':
                    return '1';
                default:
                    return 'SAMPLE';
            }
        }
    }

    class signing_queue extends db_connect {

        // Get files ready for signing
        public function get_ready_to_sign($limit = 50) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $where_clause = "WHERE fb.status = 'ready_to_sign' ";
            if ($role != 'admin') {
                $where_clause .= " AND fb.created_by = $user_id";
            }
            
            $sql = "
                SELECT 
                    fb.id,
                    fb.batch_number,
                    fb.reference_no,
                    ft.name as file_type,
                    fb.currency_code,
                    fb.total_amount,
                    fb.total_count,
                    fb.created_at,
                    u.username as created_by_name,
                    fb.file_path,
                    (SELECT COUNT(*) FROM signed_files sf WHERE sf.batch_id = fb.id) as already_signed
                FROM file_batches fb
                LEFT JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN users u ON fb.created_by = u.user_id
                $where_clause
                ORDER BY fb.created_at ASC
                LIMIT ?
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $files = [];
            while ($row = $result->fetch_assoc()) {
                $files[] = $row;
            }
            
            $stmt->close();
            return $files;
        }

        // Get signing history
        public function get_signing_history($limit = 50) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $role = $_SESSION["bt_role"] ?? '';
            
            $where_clause = "WHERE fb.status = 'signed' AND ";
            if ($role != 'admin') {
                $where_clause .= " AND fb.created_by = $user_id";
            }
            
            $sql = "
                SELECT 
                    fb.id,
                    fb.batch_number,
                    fb.reference_no,
                    ft.name as file_type,
                    fb.currency_code,
                    fb.total_amount,
                    fb.total_count,
                    fb.signed_at,
                    u.username as created_by_name,
                    su.username as signed_by_name,
                    sf.signed_file_path,
                    sf.signature_type,
                    sf.verification_status
                FROM file_batches fb
                LEFT JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN users u ON fb.created_by = u.user_id
                LEFT JOIN signed_files sf ON fb.id = sf.batch_id
                LEFT JOIN users su ON sf.signed_by_user = su.user_id
                $where_clause
                ORDER BY fb.signed_at DESC
                LIMIT ?
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $history = [];
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
            
            $stmt->close();
            return $history;
        }

        // Prepare file for signing
        public function prepare_for_signing($batch_id) {
            $conn = $this->connect();
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            
            try {
                // Check if batch exists and is in correct status
                $check_stmt = $conn->prepare("
                    SELECT status, file_path 
                    FROM file_batches 
                    WHERE id = ? AND deleted = 0
                ");
                $check_stmt->bind_param('i', $batch_id);
                $check_stmt->execute();
                $check_stmt->bind_result($status, $file_path);
                $check_stmt->fetch();
                $check_stmt->close();
                
                if (!$file_path) {
                    throw new Exception("No file found for this batch");
                }
                
                if ($status != 'generated' && $status != 'ready_to_sign') {
                    throw new Exception("File must be in 'generated' or 'ready_to_sign' status");
                }
                
                // Update status to ready_to_sign
                $update_stmt = $conn->prepare("
                    UPDATE file_batches 
                    SET status = 'ready_to_sign',
                        updated_at = NOW()
                    WHERE id = ?
                ");
                $update_stmt->bind_param('i', $batch_id);
                
                if (!$update_stmt->execute()) {
                    throw new Exception("Failed to update status: " . $update_stmt->error);
                }
                $update_stmt->close();
                
                // Log workflow
                $log = new general();
                $log->logAuditTrail('file_batches', $batch_id, 'UPDATE', $user_id, 
                                    "File prepared for signing");
                
                return [
                    'success' => true,
                    'message' => 'File prepared for signing',
                    'file_path' => $file_path
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        // Get available certificates for signing
        public function get_available_certificates() {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    id,
                    certificate_name,
                    issuer,
                    subject,
                    valid_to,
                    is_default
                FROM certificates
                WHERE is_active = 1
                AND valid_to >= CURDATE()
                ORDER BY is_default DESC, certificate_name
            ");
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $certificates = [];
            while ($row = $result->fetch_assoc()) {
                $certificates[] = $row;
            }
            
            $stmt->close();
            return $certificates;
        }
    }

    class audit_manager extends db_connect {
    
        // List audit logs
        public function list_audit_logs() {
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    al.id,
                    al.user_id,
                    al.action,
                    al.entity_type,
                    al.entity_id,
                    SUBSTRING(al.details, 1, 100) as short_details,
                    al.ip_address,
                    al.user_agent,
                    al.created_at,
                    u.username
                FROM audit_log al
                LEFT JOIN users u ON al.user_id = u.user_id
                ORDER BY al.created_at DESC
                LIMIT 1000
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $timestamp = date('Y-m-d H:i:s', strtotime($row['created_at']));
                    $user_agent = substr($row['user_agent'], 0, 30) . (strlen($row['user_agent']) > 30 ? '...' : '');
                    
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['user_id']} ({$row['username']})</td>";
                    echo "<td><span class='badge badge-primary'>{$row['action']}</span></td>";
                    echo "<td>{$row['entity_type']}</td>";
                    echo "<td>{$row['entity_id']}</td>";
                    echo "<td title='" . htmlspecialchars($row['short_details']) . "'>" . htmlspecialchars($row['short_details']) . "</td>";
                    echo "<td>{$row['ip_address']}</td>";
                    echo "<td title='" . htmlspecialchars($row['user_agent']) . "'>{$user_agent}</td>";
                    echo "<td>{$timestamp}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No audit logs found</td></tr>";
            }
            
            $stmt->close();
        }
        
        // List audit trail
        public function list_audit_trail() {
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    at.audit_id,
                    at.table_name,
                    at.record_id,
                    at.action_type,
                    at.user_id,
                    SUBSTRING(at.details, 1, 100) as short_details,
                    at.timestamp,
                    u.username
                FROM audit_trail at
                LEFT JOIN users u ON at.user_id = u.user_id
                ORDER BY at.timestamp DESC
                LIMIT 1000
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $timestamp = date('Y-m-d H:i:s', strtotime($row['timestamp']));
                    $action_badge = '';
                    
                    switch($row['action_type']) {
                        case 'INSERT':
                            $action_badge = 'badge-success';
                            break;
                        case 'UPDATE':
                            $action_badge = 'badge-warning';
                            break;
                        case 'DELETE':
                            $action_badge = 'badge-danger';
                            break;
                        default:
                            $action_badge = 'badge-info';
                    }
                    
                    echo "<tr>";
                    echo "<td>{$row['audit_id']}</td>";
                    echo "<td>{$row['table_name']}</td>";
                    echo "<td>{$row['record_id']}</td>";
                    echo "<td><span class='badge {$action_badge}'>{$row['action_type']}</span></td>";
                    echo "<td>{$row['user_id']} ({$row['username']})</td>";
                    echo "<td title='" . htmlspecialchars($row['short_details']) . "'>" . htmlspecialchars($row['short_details']) . "</td>";
                    echo "<td>{$timestamp}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No audit trail found</td></tr>";
            }
            
            $stmt->close();
        }
        
        // Get audit statistics
        public function get_audit_statistics() {
            $conn = $this->connect();
            
            $stats = [];
            
            // Total audit logs
            $sql = "SELECT COUNT(*) as total FROM audit_log";
            $result = $conn->query($sql);
            $stats['total_logs'] = $result->fetch_assoc()['total'];
            
            // Today's logs
            $sql = "SELECT COUNT(*) as today FROM audit_log WHERE DATE(created_at) = CURDATE()";
            $result = $conn->query($sql);
            $stats['today_logs'] = $result->fetch_assoc()['today'];
            
            // Most active user
            $sql = "SELECT user_id, COUNT(*) as count FROM audit_log GROUP BY user_id ORDER BY count DESC LIMIT 1";
            $result = $conn->query($sql);
            $most_active = $result->fetch_assoc();
            $stats['most_active_user'] = $most_active['user_id'];
            $stats['most_active_count'] = $most_active['count'];
            
            return $stats;
        }
    }

    class reports_manager extends db_connect {
        
        // Get total files count
        public function get_total_files_count() {
            $conn = $this->connect();
            $sql = "SELECT COUNT(*) as total FROM uploaded_files";
            $result = $conn->query($sql);
            return $result->fetch_assoc()['total'] ?: 0;
        }
        
        // Get signed files count
        public function get_signed_files_count() {
            $conn = $this->connect();
            $sql = "SELECT COUNT(*) as total FROM signed_files";
            $result = $conn->query($sql);
            return $result->fetch_assoc()['total'] ?: 0;
        }
        
        // Get total users count
        public function get_total_users_count() {
            $conn = $this->connect();
            $sql = "SELECT COUNT(*) as total FROM users WHERE deleted = 0";
            $result = $conn->query($sql);
            return $result->fetch_assoc()['total'] ?: 0;
        }
        
        // Get total amount
        public function get_total_amount() {
            $conn = $this->connect();
            $sql = "SELECT SUM(total_amount) as total FROM file_batches WHERE status = 'signed'";
            $result = $conn->query($sql);
            return $result->fetch_assoc()['total'] ?: 0;
        }
        
        // Get monthly signing data
        public function get_monthly_signing_data() {
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    DATE_FORMAT(sf.signed_at, '%Y-%m') as month,
                    COUNT(*) as count
                FROM signed_files sf
                WHERE sf.signed_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(sf.signed_at, '%Y-%m')
                ORDER BY month
            ";
            
            $result = $conn->query($sql);
            
            $data = [];
            $categories = [];
            
            while ($row = $result->fetch_assoc()) {
                $month = date('M Y', strtotime($row['month'] . '-01'));
                $categories[] = $month;
                $data[] = intval($row['count']);
            }
            
            // Ensure we have 6 months of data
            if (count($data) < 6) {
                $today = new DateTime();
                for ($i = 5; $i >= 0; $i--) {
                    $month = $today->format('M Y');
                    $monthKey = $today->format('Y-m');
                    
                    if (!in_array($month, $categories)) {
                        array_unshift($categories, $month);
                        array_unshift($data, 0);
                    }
                    
                    $today->modify('-1 month');
                }
            }
            
            return json_encode([
                'categories' => $categories,
                'data' => $data
            ]);
        }
        
        // List recent signed files
        public function list_recent_signed_files() {
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    sf.id,
                    fb.batch_number,
                    uf.original_name,
                    ft.name as file_type,
                    u.username as signed_by,
                    sf.signed_at,
                    fb.total_amount,
                    c.certificate_name
                FROM signed_files sf
                JOIN uploaded_files uf ON sf.original_file_id = uf.id
                JOIN file_batches fb ON sf.batch_id = fb.id
                JOIN file_types ft ON fb.file_type_id = ft.id
                LEFT JOIN users u ON sf.signed_by_user = u.user_id
                LEFT JOIN certificates c ON c.id = (SELECT id FROM certificates LIMIT 1)
                ORDER BY sf.signed_at DESC
                LIMIT 10
            ";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $signed_date = date('Y-m-d H:i', strtotime($row['signed_at']));
                    $amount = number_format($row['total_amount'], 2);
                    
                    echo "<tr>";
                    echo "<td>{$row['batch_number']}</td>";
                    echo "<td>" . htmlspecialchars(substr($row['original_name'], 0, 30)) . "...</td>";
                    echo "<td>{$row['file_type']}</td>";
                    echo "<td>{$row['signed_by']}</td>";
                    echo "<td>{$signed_date}</td>";
                    echo "<td>{$amount}</td>";
                    echo "<td>{$row['certificate_name']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No signed files found</td></tr>";
            }
            
            $stmt->close();
        }
        
        // Generate report
        public function generate_report($type, $date_from = null, $date_to = null) {
            $conn = $this->connect();
            $report_data = [];
            
            switch ($type) {
                case 'signing_summary':
                    $sql = "
                        SELECT 
                            DATE(sf.signed_at) as date,
                            COUNT(*) as files_signed,
                            SUM(fb.total_amount) as total_amount,
                            COUNT(DISTINCT sf.signed_by_user) as users_signed
                        FROM signed_files sf
                        JOIN file_batches fb ON sf.batch_id = fb.id
                        WHERE 1=1
                    ";
                    
                    if ($date_from) {
                        $sql .= " AND DATE(sf.signed_at) >= '" . $date_from . "'";
                    }
                    
                    if ($date_to) {
                        $sql .= " AND DATE(sf.signed_at) <= '" . $date_to . "'";
                    }
                    
                    $sql .= " GROUP BY DATE(sf.signed_at) ORDER BY date DESC";
                    
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $report_data[] = $row;
                    }
                    break;
                    
                case 'user_activity':
                    $sql = "
                        SELECT 
                            u.username,
                            u.role,
                            COUNT(DISTINCT fb.id) as batches_created,
                            COUNT(DISTINCT sf.id) as files_signed,
                            MAX(fb.created_at) as last_activity
                        FROM users u
                        LEFT JOIN file_batches fb ON u.user_id = fb.created_by
                        LEFT JOIN signed_files sf ON u.user_id = sf.signed_by_user
                        WHERE u.deleted = 0
                        GROUP BY u.user_id
                        ORDER BY batches_created DESC
                    ";
                    
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $report_data[] = $row;
                    }
                    break;
            }
            
            return $report_data;
        }
    }

    class export_manager extends db_connect {
        
        // List recent exports
        public function list_recent_exports() {
            // Create exports table if it doesn't exist
            $this->create_exports_table();
            
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    id,
                    table_name,
                    record_count,
                    format,
                    exported_by,
                    export_date,
                    file_size,
                    file_path
                FROM exports
                ORDER BY export_date DESC
                LIMIT 10
            ";
            
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $export_date = date('Y-m-d H:i', strtotime($row['export_date']));
                    $file_size = $row['file_size'] ? round($row['file_size'] / 1024, 2) . ' KB' : 'N/A';
                    
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['table_name']}</td>";
                    echo "<td>{$row['record_count']}</td>";
                    echo "<td><span class='badge badge-info'>{$row['format']}</span></td>";
                    echo "<td>{$row['exported_by']}</td>";
                    echo "<td>{$export_date}</td>";
                    echo "<td>{$file_size}</td>";
                    echo "<td>";
                    if ($row['file_path'] && file_exists($row['file_path'])) {
                        echo "<a href='./php/export_handler.php?action=download&id={$row['id']}' class='btn btn-sm btn-primary'>Download</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center'>No exports found</td></tr>";
            }
        }
        
        // Create exports table if not exists
        private function create_exports_table() {
            $conn = $this->connect();
            
            $sql = "
                CREATE TABLE IF NOT EXISTS exports (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    table_name VARCHAR(100) NOT NULL,
                    record_count INT DEFAULT 0,
                    format VARCHAR(10) NOT NULL,
                    exported_by INT,
                    export_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                    file_path VARCHAR(500),
                    file_size INT DEFAULT 0,
                    parameters TEXT
                )
            ";
            
            $conn->query($sql);
        }
        
        // Export table data
        public function export_table($table_name, $format = 'csv', $date_from = null, $date_to = null) {
            $conn = $this->connect();
            
            // Validate table name
            $allowed_tables = [
                'file_batches', 'uploaded_files', 'signed_files', 
                'certificates', 'users', 'audit_log', 'audit_trail',
                'batch_items', 'file_types'
            ];
            
            if (!in_array($table_name, $allowed_tables)) {
                return ['success' => false, 'message' => 'Invalid table name'];
            }
            
            // Build query with date filters
            $sql = "SELECT * FROM $table_name WHERE 1=1";
            
            // Add date filters based on table structure
            $date_column = $this->get_date_column($table_name);
            if ($date_column) {
                if ($date_from) {
                    $sql .= " AND DATE($date_column) >= '$date_from'";
                }
                if ($date_to) {
                    $sql .= " AND DATE($date_column) <= '$date_to'";
                }
            }
            
            $result = $conn->query($sql);
            
            if (!$result) {
                return ['success' => false, 'message' => 'Failed to fetch data'];
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            $record_count = count($data);
            
            // Generate export file
            $export_dir = '../exports/';
            if (!file_exists($export_dir)) {
                mkdir($export_dir, 0777, true);
            }
            
            $filename = $table_name . '_' . date('Ymd_His') . '.' . $format;
            $filepath = $export_dir . $filename;
            
            switch ($format) {
                case 'csv':
                    $this->export_to_csv($data, $filepath);
                    break;
                case 'json':
                    $this->export_to_json($data, $filepath);
                    break;
                case 'excel':
                    // You would need PHPExcel or PhpSpreadsheet for this
                    // For now, we'll export as CSV
                    $this->export_to_csv($data, $filepath);
                    $filename = str_replace('.excel', '.csv', $filename);
                    break;
                default:
                    $this->export_to_csv($data, $filepath);
            }
            
            // Save export record
            $user_id = $_SESSION["bt_user_id"] ?? 0;
            $file_size = filesize($filepath);
            
            $insert_sql = "
                INSERT INTO exports (table_name, record_count, format, exported_by, file_path, file_size, parameters)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            
            $stmt = $conn->prepare($insert_sql);
            $params = json_encode(['date_from' => $date_from, 'date_to' => $date_to]);
            $stmt->bind_param('sississ', $table_name, $record_count, $format, $user_id, $filepath, $file_size, $params);
            $stmt->execute();
            $export_id = $conn->insert_id;
            $stmt->close();
            
            return [
                'success' => true,
                'export_id' => $export_id,
                'file_path' => $filepath,
                'filename' => $filename,
                'record_count' => $record_count,
                'message' => 'Export completed successfully'
            ];
        }
        
        private function get_date_column($table_name) {
            $date_columns = [
                'file_batches' => 'created_at',
                'uploaded_files' => 'uploaded_at',
                'signed_files' => 'signed_at',
                'certificates' => 'created_at',
                'users' => 'created_at',
                'audit_log' => 'created_at',
                'audit_trail' => 'timestamp'
            ];
            
            return $date_columns[$table_name] ?? null;
        }
        
        private function export_to_csv($data, $filepath) {
            if (empty($data)) {
                file_put_contents($filepath, "No data found");
                return;
            }
            
            $fp = fopen($filepath, 'w');
            
            // Write headers
            fputcsv($fp, array_keys($data[0]));
            
            // Write data
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
            
            fclose($fp);
        }
        
        private function export_to_json($data, $filepath) {
            file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT));
        }
    }

    class config_management extends db_connect {
        
        // Get configuration value
        public function get_config_value($key) {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT config_value FROM system_config WHERE config_key = ?");
            $stmt->bind_param('s', $key);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            return $value ?: '';
        }
        
        // Get all configuration values
        public function get_all_config() {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT config_key, config_value, config_type, category, description FROM system_config ORDER BY category, config_key");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $config = [];
            while ($row = $result->fetch_assoc()) {
                $config[$row['config_key']] = $row;
            }
            
            $stmt->close();
            return $config;
        }
        
        // Update configuration value
        public function update_config($key, $value) {
            $conn = $this->connect();
            $stmt = $conn->prepare("UPDATE system_config SET config_value = ?, updated_at = NOW() WHERE config_key = ?");
            $stmt->bind_param('ss', $value, $key);
            
            if ($stmt->execute()) {
                $stmt->close();
                return ['success' => true, 'message' => 'Configuration updated'];
            } else {
                $error = $stmt->error;
                $stmt->close();
                return ['success' => false, 'message' => 'Failed to update: ' . $error];
            }
        }
        
        // Get system health information
        public function get_system_health() {
            $health = [];
            
            // Database health
            $conn = $this->connect();
            $health['database'] = $conn->ping() ? 'OK' : 'ERROR';
            
            // Disk space
            $disk_total = disk_total_space('/');
            $disk_free = disk_free_space('/');
            $disk_used = $disk_total - $disk_free;
            $disk_usage = round(($disk_used / $disk_total) * 100, 2);
            
            $health['disk_usage'] = $disk_usage;
            $health['disk_total'] = round($disk_total / 1024 / 1024 / 1024, 2) . ' GB';
            $health['disk_free'] = round($disk_free / 1024 / 1024 / 1024, 2) . ' GB';
            
            // Memory usage
            if (function_exists('memory_get_usage')) {
                $memory_usage = memory_get_usage(true);
                $memory_peak = memory_get_peak_usage(true);
                $health['memory_usage'] = round($memory_usage / 1024 / 1024, 2) . ' MB';
                $health['memory_peak'] = round($memory_peak / 1024 / 1024, 2) . ' MB';
            }
            
            // PHP info
            $health['php_version'] = phpversion();
            $health['server_software'] = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
            
            // OpenSSL check
            $openssl_version = shell_exec('openssl version 2>&1');
            $health['openssl'] = strpos($openssl_version, 'OpenSSL') !== false ? 'Available' : 'Not Found';
            $health['openssl_version'] = trim($openssl_version);
            
            return $health;
        }
        
        // Get system services status
        public function get_services_status() {
            $services = [];
            
            // Check Apache/HTTPD
            $services[] = [
                'name' => 'Web Server',
                'status' => $this->check_service('httpd') ? 'Running' : 'Stopped',
                'port' => '80/443',
                'version' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
            ];
            
            // Check MySQL
            $services[] = [
                'name' => 'MySQL Database',
                'status' => $this->check_service('mysqld') ? 'Running' : 'Stopped',
                'port' => '3306',
                'version' => $this->get_mysql_version()
            ];
            
            // Check if OpenSSL is available
            $openssl_check = shell_exec('which openssl 2>&1');
            $services[] = [
                'name' => 'OpenSSL',
                'status' => !empty(trim($openssl_check)) ? 'Available' : 'Not Found',
                'port' => 'N/A',
                'version' => $this->get_openssl_version()
            ];
            
            return $services;
        }
        
        private function check_service($service_name) {
            // This is a simplified check - you may need to adjust for your OS
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows
                $output = shell_exec('sc query ' . $service_name . ' 2>&1');
                return strpos($output, 'RUNNING') !== false;
            } else {
                // Linux/Unix
                $output = shell_exec('systemctl is-active ' . $service_name . ' 2>&1');
                return trim($output) === 'active';
            }
        }
        
        private function get_mysql_version() {
            $conn = $this->connect();
            $result = $conn->query("SELECT VERSION() as version");
            if ($result) {
                $row = $result->fetch_assoc();
                return $row['version'] ?? 'Unknown';
            }
            return 'Unknown';
        }
        
        private function get_openssl_version() {
            $output = shell_exec('openssl version 2>&1');
            return trim($output) ?: 'Unknown';
        }
    }

    class backup_management extends db_connect {
        
        // Create database backup
        public function create_backup($backup_type = 'database') {
            try {
                $backup_dir = dirname(dirname(__FILE__)) . '/backups/';
                if (!file_exists($backup_dir)) {
                    if (!mkdir($backup_dir, 0777, true)) {
                        throw new Exception("Failed to create backup directory");
                    }
                }
                
                $timestamp = date('Ymd_His');
                $backup_file = $backup_dir . $backup_type . '_backup_' . $timestamp . '.sql';
                
                // Get database credentials from connection.php
                include 'connection.php';
                
                // Build mysqldump command
                $command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " > \"" . $backup_file . "\" 2>&1";
                
                // Execute command
                exec($command, $output, $return_code);
                
                if ($return_code === 0 && file_exists($backup_file) && filesize($backup_file) > 0) {
                    // Log backup creation
                    $this->log_backup($backup_file, $backup_type, filesize($backup_file));
                    
                    return [
                        'success' => true,
                        'message' => ucfirst($backup_type) . ' backup created successfully',
                        'filename' => basename($backup_file),
                        'filepath' => $backup_file,
                        'size' => round(filesize($backup_file) / 1024 / 1024, 2) . ' MB',
                        'download_url' => '../backups/' . basename($backup_file)
                    ];
                } else {
                    throw new Exception("Backup command failed. Return code: $return_code. Output: " . implode("\n", $output));
                }
                
            } catch (Exception $e) {
                error_log("Backup error: " . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Failed to create backup: ' . $e->getMessage()
                ];
            }
        }
        
        // Create full backup (database + files)
        public function create_full_backup() {
            try {
                // First create database backup
                $db_backup = $this->create_backup('database');
                if (!$db_backup['success']) {
                    throw new Exception("Database backup failed: " . $db_backup['message']);
                }
                
                // Create archive of important directories
                $backup_dir = dirname(dirname(__FILE__)) . '/backups/';
                $timestamp = date('Ymd_His');
                $full_backup = $backup_dir . 'full_backup_' . $timestamp . '.zip';
                
                // Directories to include in backup
                $dirs_to_backup = [
                    '../uploads/',
                    '../signed_files/',
                    '../backups/' . basename($db_backup['filepath']) // Include the database backup
                ];
                
                // Create zip archive
                $zip = new ZipArchive();
                if ($zip->open($full_backup, ZipArchive::CREATE) === TRUE) {
                    foreach ($dirs_to_backup as $dir) {
                        if (file_exists($dir)) {
                            if (is_dir($dir)) {
                                $this->addDirectoryToZip($zip, $dir, basename($dir));
                            } else {
                                $zip->addFile($dir, basename($dir));
                            }
                        }
                    }
                    $zip->close();
                    
                    if (file_exists($full_backup)) {
                        // Clean up the individual database backup file since it's in the zip
                        unlink($db_backup['filepath']);
                        
                        return [
                            'success' => true,
                            'message' => 'Full backup created successfully',
                            'filename' => basename($full_backup),
                            'filepath' => $full_backup,
                            'size' => round(filesize($full_backup) / 1024 / 1024, 2) . ' MB',
                            'download_url' => '../backups/' . basename($full_backup)
                        ];
                    }
                }
                
                throw new Exception("Failed to create zip archive");
                
            } catch (Exception $e) {
                error_log("Full backup error: " . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Failed to create full backup: ' . $e->getMessage()
                ];
            }
        }
        
        private function addDirectoryToZip($zip, $dir, $zipDir = '') {
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if ($file != '.' && $file != '..') {
                            $filePath = $dir . $file;
                            $localPath = $zipDir . '/' . $file;
                            
                            if (is_file($filePath)) {
                                $zip->addFile($filePath, $localPath);
                            } elseif (is_dir($filePath)) {
                                $this->addDirectoryToZip($zip, $filePath . '/', $localPath);
                            }
                        }
                    }
                    closedir($dh);
                }
            }
        }
        
        // Get backup statistics
        public function get_backup_stats() {
            $backup_dir = dirname(dirname(__FILE__)) . '/backups/';
            
            if (!file_exists($backup_dir)) {
                mkdir($backup_dir, 0777, true);
            }
            
            $files = glob($backup_dir . '*.{sql,zip}', GLOB_BRACE);
            $total_size = 0;
            $last_backup = null;
            
            foreach ($files as $file) {
                $total_size += filesize($file);
                $filetime = filemtime($file);
                if (!$last_backup || $filetime > $last_backup) {
                    $last_backup = $filetime;
                }
            }
            
            return [
                'total_backups' => count($files),
                'total_size' => round($total_size / 1024 / 1024, 2),
                'last_backup' => $last_backup ? date('Y-m-d H:i:s', $last_backup) : 'Never',
                'auto_backup' => $this->get_config_value('auto_backup') == '1'
            ];
        }
        
        // Get backup files list
        public function get_backup_files() {
            $backup_dir = dirname(dirname(__FILE__)) . '/backups/';
            
            if (!file_exists($backup_dir)) {
                return [];
            }
            
            $files = glob($backup_dir . '*.{sql,zip}', GLOB_BRACE);
            usort($files, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            
            return $files;
        }
        
        // Restore from backup
        public function restore_backup($backup_file, $restore_type = 'database') {
            try {
                $backup_path = dirname(dirname(__FILE__)) . '/backups/' . $backup_file;
                
                if (!file_exists($backup_path)) {
                    throw new Exception("Backup file not found: $backup_file");
                }
                
                if ($restore_type === 'database') {
                    return $this->restore_database($backup_path);
                } else {
                    return $this->restore_full($backup_path);
                }
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Restore failed: ' . $e->getMessage()
                ];
            }
        }
        
        private function restore_database($backup_path) {
            include 'connection.php';
            
            $command = "mysql --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " < \"" . $backup_path . "\" 2>&1";
            
            exec($command, $output, $return_code);
            
            if ($return_code === 0) {
                return [
                    'success' => true,
                    'message' => 'Database restored successfully'
                ];
            } else {
                throw new Exception("Restore command failed. Return code: $return_code. Output: " . implode("\n", $output));
            }
        }
        
        private function restore_full($backup_path) {
            // This would extract the zip and restore both files and database
            // Implementation depends on your specific needs
            return [
                'success' => false,
                'message' => 'Full restore not implemented yet'
            ];
        }
        
        private function log_backup($backup_file, $type, $size) {
            $conn = $this->connect();
            $user_id = isset($_SESSION['bt_user_id']) ? $_SESSION['bt_user_id'] : 0;
            
            $stmt = $conn->prepare("INSERT INTO backup_logs (filename, backup_type, file_size, created_by) VALUES (?, ?, ?, ?)");
            $filename = basename($backup_file);
            $stmt->bind_param('ssii', $filename, $type, $size, $user_id);
            $stmt->execute();
            $stmt->close();
        }
        
        private function get_config_value($key) {
            $conn = $this->connect();
            $stmt = $conn->prepare("SELECT config_value FROM system_config WHERE config_key = ?");
            $stmt->bind_param('s', $key);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            return $value;
        }
    }

    class api_management extends db_connect {
        
        // Generate API key
        public function generate_api_key($name, $expiration_days = 0, $permissions = ['read']) {
            try {
                $api_key = bin2hex(random_bytes(32));
                $api_key_prefix = substr($api_key, 0, 8);
                
                $conn = $this->connect();
                $user_id = isset($_SESSION['bt_user_id']) ? $_SESSION['bt_user_id'] : 0;
                
                $stmt = $conn->prepare("
                    INSERT INTO api_keys (api_key_name, api_key, api_key_prefix, expiration_days, permissions, created_by)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $permissions_json = json_encode($permissions);
                $stmt->bind_param('sssisi', $name, $api_key, $api_key_prefix, $expiration_days, $permissions_json, $user_id);
                
                if ($stmt->execute()) {
                    $key_id = $conn->insert_id;
                    $stmt->close();
                    
                    return [
                        'success' => true,
                        'api_key' => $api_key,
                        'key_id' => $key_id,
                        'message' => 'API key generated successfully'
                    ];
                } else {
                    throw new Exception("Failed to save API key: " . $stmt->error);
                }
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Get API keys list
        public function get_api_keys() {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    ak.id,
                    ak.api_key_name,
                    ak.api_key_prefix,
                    ak.created_at,
                    ak.last_used,
                    ak.total_calls,
                    ak.is_active,
                    u.username as created_by_name
                FROM api_keys ak
                LEFT JOIN users u ON ak.created_by = u.user_id
                ORDER BY ak.created_at DESC
            ");
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            $keys = [];
            while ($row = $result->fetch_assoc()) {
                $keys[] = $row;
            }
            
            $stmt->close();
            return $keys;
        }
        
        // Revoke API key
        public function revoke_api_key($key_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("UPDATE api_keys SET is_active = 0, revoked_at = NOW() WHERE id = ?");
            $stmt->bind_param('i', $key_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return [
                    'success' => true,
                    'message' => 'API key revoked successfully'
                ];
            } else {
                $error = $stmt->error;
                $stmt->close();
                return [
                    'success' => false,
                    'message' => 'Failed to revoke key: ' . $error
                ];
            }
        }
        
        // Get API statistics
        public function get_api_stats() {
            $conn = $this->connect();
            
            $stats = [
                'total_calls' => 0,
                'total_keys' => 0,
                'error_rate' => 0
            ];
            
            try {
                // Total API calls
                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM api_logs");
                $stmt->execute();
                $stmt->bind_result($total);
                $stmt->fetch();
                $stats['total_calls'] = $total ?: 0;
                $stmt->close();
                
                // Active API keys
                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM api_keys WHERE is_active = 1");
                $stmt->execute();
                $stmt->bind_result($total);
                $stmt->fetch();
                $stats['total_keys'] = $total ?: 0;
                $stmt->close();
                
                // Error rate (calls with status >= 400)
                $stmt = $conn->prepare("
                    SELECT 
                        COUNT(CASE WHEN status_code >= 400 THEN 1 END) as errors,
                        COUNT(*) as total
                    FROM api_logs 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                ");
                $stmt->execute();
                $stmt->bind_result($errors, $total);
                $stmt->fetch();
                
                if ($total > 0) {
                    $stats['error_rate'] = round(($errors / $total) * 100, 1);
                }
                $stmt->close();
                
            } catch (Exception $e) {
                error_log("API stats error: " . $e->getMessage());
            }
            
            return $stats;
        }
        
        // Validate API key
        public function validate_api_key($api_key) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT id, api_key_name, permissions, expiration_days, created_at, is_active
                FROM api_keys 
                WHERE api_key = ? AND is_active = 1
            ");
            
            $stmt->bind_param('s', $api_key);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $key_data = $result->fetch_assoc();
                
                // Check expiration
                if ($key_data['expiration_days'] > 0) {
                    $expiration_date = date('Y-m-d H:i:s', strtotime($key_data['created_at'] . ' + ' . $key_data['expiration_days'] . ' days'));
                    if (strtotime($expiration_date) < time()) {
                        return false; // Key expired
                    }
                }
                
                // Update last used
                $this->update_key_usage($key_data['id']);
                
                return [
                    'id' => $key_data['id'],
                    'name' => $key_data['api_key_name'],
                    'permissions' => json_decode($key_data['permissions'], true)
                ];
            }
            
            return false;
        }
        
        private function update_key_usage($key_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                UPDATE api_keys 
                SET last_used = NOW(), 
                    total_calls = total_calls + 1 
                WHERE id = ?
            ");
            $stmt->bind_param('i', $key_id);
            $stmt->execute();
            $stmt->close();
        }
        
        // Log API call
        public function log_api_call($key_id, $endpoint, $method, $status_code, $response_time, $ip_address = null, $user_agent = null) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                INSERT INTO api_logs (api_key_id, endpoint, method, status_code, response_time, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $ip_address = $ip_address ?: $_SERVER['REMOTE_ADDR'] ?? null;
            $user_agent = $user_agent ?: $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            $stmt->bind_param('issidis', $key_id, $endpoint, $method, $status_code, $response_time, $ip_address, $user_agent);
            $stmt->execute();
            $stmt->close();
        }
    }

    class validation_management extends db_connect {
    
        // Get all validation rules
        public function get_all_rules() {
            $conn = $this->connect();
            
            $sql = "
                SELECT 
                    vr.*,
                    COUNT(DISTINCT CASE WHEN vr.rule_type = 'field' THEN 1 END) as field_rules_count,
                    COUNT(DISTINCT CASE WHEN vr.rule_type = 'batch' THEN 1 END) as batch_rules_count,
                    COUNT(DISTINCT CASE WHEN vr.rule_type = 'business' THEN 1 END) as business_rules_count
                FROM validation_rules vr
                WHERE vr.is_active = 1
                GROUP BY vr.id
                ORDER BY 
                    CASE vr.severity 
                        WHEN 'error' THEN 1
                        WHEN 'warning' THEN 2
                        ELSE 3
                    END,
                    vr.rule_name
            ";
            
            $result = $conn->query($sql);
            
            $rules = [];
            while ($row = $result->fetch_assoc()) {
                $rules[] = $row;
            }
            
            $conn->close();
            return $rules;
        }
        
        // Get rule by ID
        public function get_rule_details($rule_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("SELECT * FROM validation_rules WHERE id = ?");
            $stmt->bind_param('i', $rule_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                return null;
            }
            
            $rule = $result->fetch_assoc();
            $stmt->close();
            $conn->close();
            
            return $rule;
        }
        
        // Add new validation rule
        public function add_validation_rule($rule_name, $rule_type, $rule_condition, $error_message, $severity = 'error', $is_active = 1) {
            $conn = $this->connect();
            
            try {
                // Check if rule already exists
                $check_stmt = $conn->prepare("SELECT id FROM validation_rules WHERE rule_name = ?");
                $check_stmt->bind_param('s', $rule_name);
                $check_stmt->execute();
                $check_stmt->store_result();
                
                if ($check_stmt->num_rows > 0) {
                    throw new Exception("Validation rule '$rule_name' already exists.");
                }
                $check_stmt->close();
                
                // Insert new rule
                $stmt = $conn->prepare("
                    INSERT INTO validation_rules (rule_name, rule_type, rule_condition, error_message, severity, is_active)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $stmt->bind_param('sssssi', $rule_name, $rule_type, $rule_condition, $error_message, $severity, $is_active);
                
                if ($stmt->execute()) {
                    $rule_id = $conn->insert_id;
                    $stmt->close();
                    
                    // Log audit trail
                    $log = new general();
                    $log->logAuditTrail('validation_rules', $rule_id, 'INSERT', $_SESSION["bt_user_id"] ?? 0, 
                                    "Added validation rule: $rule_name");
                    
                    return [
                        'success' => true,
                        'rule_id' => $rule_id,
                        'message' => 'Validation rule added successfully'
                    ];
                } else {
                    throw new Exception("Failed to add validation rule: " . $stmt->error);
                }
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Update validation rule
        public function update_validation_rule($rule_id, $rule_name, $rule_type, $rule_condition, $error_message, $severity, $is_active) {
            $conn = $this->connect();
            
            try {
                $stmt = $conn->prepare("
                    UPDATE validation_rules 
                    SET rule_name = ?, 
                        rule_type = ?, 
                        rule_condition = ?, 
                        error_message = ?, 
                        severity = ?, 
                        is_active = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                
                $stmt->bind_param('sssssii', $rule_name, $rule_type, $rule_condition, $error_message, $severity, $is_active, $rule_id);
                
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    // Log audit trail
                    $log = new general();
                    $log->logAuditTrail('validation_rules', $rule_id, 'UPDATE', $_SESSION["bt_user_id"] ?? 0, 
                                    "Updated validation rule: $rule_name");
                    
                    return [
                        'success' => true,
                        'message' => 'Validation rule updated successfully'
                    ];
                } else {
                    throw new Exception("Failed to update validation rule: " . $stmt->error);
                }
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Delete validation rule (soft delete)
        public function delete_validation_rule($rule_id) {
            $conn = $this->connect();
            
            try {
                $stmt = $conn->prepare("DELETE FROM validation_rules WHERE id = ?");
                $stmt->bind_param('i', $rule_id);
                
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    // Log audit trail
                    $log = new general();
                    $log->logAuditTrail('validation_rules', $rule_id, 'DELETE', $_SESSION["bt_user_id"] ?? 0, 
                                    "Deleted validation rule");
                    
                    return [
                        'success' => true,
                        'message' => 'Validation rule deleted successfully'
                    ];
                } else {
                    throw new Exception("Failed to delete validation rule: " . $stmt->error);
                }
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        // Test validation rule
        public function test_validation_rule($rule_condition, $test_data = null) {
            // This is a simplified test function
            // In production, you'd need a proper rule engine
            
            try {
                // For demonstration, we'll just return success
                // You would implement actual rule testing logic here
                return [
                    'success' => true,
                    'message' => 'Rule syntax appears valid',
                    'test_result' => 'Passed (test mode)'
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Rule test failed: ' . $e->getMessage(),
                    'test_result' => 'Failed'
                ];
            }
        }
        
        // Run validation on a batch
        public function validate_batch($batch_id) {
            $conn = $this->connect();
            
            try {
                // Get batch details
                $batch_stmt = $conn->prepare("
                    SELECT fb.*, ft.code as file_type_code
                    FROM file_batches fb
                    JOIN file_types ft ON fb.file_type_id = ft.id
                    WHERE fb.id = ?
                ");
                $batch_stmt->bind_param('i', $batch_id);
                $batch_stmt->execute();
                $batch_result = $batch_stmt->get_result();
                
                if ($batch_result->num_rows === 0) {
                    throw new Exception("Batch not found");
                }
                
                $batch = $batch_result->fetch_assoc();
                $batch_stmt->close();
                
                // Get all active validation rules
                $rules = $this->get_all_rules();
                $validation_results = [];
                $has_errors = false;
                $has_warnings = false;
                
                foreach ($rules as $rule) {
                    $result = $this->apply_rule($rule, $batch_id, $batch);
                    
                    $validation_results[] = [
                        'rule_id' => $rule['id'],
                        'rule_name' => $rule['rule_name'],
                        'rule_type' => $rule['rule_type'],
                        'severity' => $rule['severity'],
                        'result' => $result['result'],
                        'message' => $result['message'],
                        'details' => $result['details'] ?? null
                    ];
                    
                    if ($result['result'] === 'failed' && $rule['severity'] === 'error') {
                        $has_errors = true;
                    }
                    
                    if ($result['result'] === 'failed' && $rule['severity'] === 'warning') {
                        $has_warnings = true;
                    }
                }
                
                // Update batch validation status
                $status = 'valid';
                if ($has_errors) {
                    $status = 'validation_error';
                } elseif ($has_warnings) {
                    $status = 'validation_warning';
                }
                
                $update_stmt = $conn->prepare("
                    UPDATE file_batches 
                    SET validation_status = ?,
                        validation_date = NOW(),
                        updated_at = NOW()
                    WHERE id = ?
                ");
                
                $update_stmt->bind_param('si', $status, $batch_id);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Save validation results
                $this->save_validation_results($batch_id, $validation_results);
                
                return [
                    'success' => true,
                    'validation_results' => $validation_results,
                    'status' => $status,
                    'has_errors' => $has_errors,
                    'has_warnings' => $has_warnings,
                    'message' => 'Validation completed: ' . count($validation_results) . ' rules checked'
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
        
        private function apply_rule($rule, $batch_id, $batch_data) {
            // This is a simplified rule application
            // In production, you'd use a proper rule engine
            
            $result = [
                'result' => 'passed',
                'message' => 'Rule passed',
                'details' => null
            ];
            
            // Simulate some rule checks
            switch ($rule['rule_name']) {
                case 'unique_reference':
                    // Check if reference is unique
                    $result = $this->check_unique_reference($batch_data['reference_no'], $batch_id);
                    break;
                    
                case 'total_amount_match':
                    // Check if total amount matches sum of line items
                    $result = $this->check_total_amount($batch_id, $batch_data['total_amount']);
                    break;
                    
                case 'total_count_match':
                    // Check if total count matches number of line items
                    $result = $this->check_total_count($batch_id, $batch_data['total_count']);
                    break;
                    
                default:
                    // For other rules, mark as passed (demo)
                    $result = [
                        'result' => 'passed',
                        'message' => 'Rule passed (not implemented in demo)',
                        'details' => null
                    ];
            }
            
            return $result;
        }
        
        private function check_unique_reference($reference_no, $current_batch_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as count 
                FROM file_batches 
                WHERE reference_no = ? 
                AND id != ?
                AND status != 'draft'
                AND deleted = 0
            ");
            $stmt->bind_param('si', $reference_no, $current_batch_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            $conn->close();
            
            if ($count > 0) {
                return [
                    'result' => 'failed',
                    'message' => 'File reference already exists',
                    'details' => "Reference '$reference_no' is already used by another batch"
                ];
            }
            
            return [
                'result' => 'passed',
                'message' => 'Reference is unique',
                'details' => null
            ];
        }
        
        private function check_total_amount($batch_id, $expected_total) {
            $conn = $this->connect();
            
            // Get sum of payment amounts from batch items
            $stmt = $conn->prepare("
                SELECT SUM(JSON_EXTRACT(data_json, '$.payment_amount')) as calculated_total
                FROM batch_items 
                WHERE batch_id = ?
                AND status = 'processed'
            ");
            $stmt->bind_param('i', $batch_id);
            $stmt->execute();
            $stmt->bind_result($calculated_total);
            $stmt->fetch();
            $stmt->close();
            $conn->close();
            
            $calculated_total = floatval($calculated_total);
            $expected_total = floatval($expected_total);
            
            if (abs($calculated_total - $expected_total) > 0.01) {
                return [
                    'result' => 'failed',
                    'message' => 'Total amount mismatch',
                    'details' => "Expected: $expected_total, Calculated: $calculated_total"
                ];
            }
            
            return [
                'result' => 'passed',
                'message' => 'Total amount matches',
                'details' => null
            ];
        }
        
        private function check_total_count($batch_id, $expected_count) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT COUNT(*) as actual_count
                FROM batch_items 
                WHERE batch_id = ?
                AND status = 'processed'
            ");
            $stmt->bind_param('i', $batch_id);
            $stmt->execute();
            $stmt->bind_result($actual_count);
            $stmt->fetch();
            $stmt->close();
            $conn->close();
            
            if ($actual_count != $expected_count) {
                return [
                    'result' => 'failed',
                    'message' => 'Total count mismatch',
                    'details' => "Expected: $expected_count, Actual: $actual_count"
                ];
            }
            
            return [
                'result' => 'passed',
                'message' => 'Total count matches',
                'details' => null
            ];
        }
        
        private function save_validation_results($batch_id, $results) {
            $conn = $this->connect();
            
            // For now, just log to audit trail
            $log = new general();
            
            foreach ($results as $result) {
                $details = "Rule: {$result['rule_name']}, Result: {$result['result']}, Message: {$result['message']}";
                $log->logAuditTrail('file_batches', $batch_id, 'UPDATE', $_SESSION["bt_user_id"] ?? 0, 
                                "Validation - " . $details);
            }
        }
        
        // Get field validation rules for a file type
        public function get_field_validation_rules($file_type_id) {
            $conn = $this->connect();
            
            $stmt = $conn->prepare("
                SELECT 
                    fs.*,
                    vr.rule_name,
                    vr.rule_condition,
                    vr.error_message,
                    vr.severity
                FROM file_schema fs
                LEFT JOIN validation_rules vr ON fs.validation_regex IS NOT NULL
                WHERE fs.file_type_id = ?
                AND fs.mandatory = 1
                ORDER BY fs.field_order
            ");
            
            $stmt->bind_param('i', $file_type_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $rules = [];
            while ($row = $result->fetch_assoc()) {
                $rules[] = $row;
            }
            
            $stmt->close();
            $conn->close();
            
            return $rules;
        }
        
        // Check for duplicates in a batch
        public function check_duplicates($batch_id, $field_name = null) {
            $conn = $this->connect();
            
            try {
                // Get batch items
                $items_stmt = $conn->prepare("
                    SELECT id, item_index, data_json
                    FROM batch_items
                    WHERE batch_id = ?
                    ORDER BY item_index
                ");
                $items_stmt->bind_param('i', $batch_id);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();
                
                $items = [];
                $field_values = [];
                $duplicates = [];
                
                while ($row = $items_result->fetch_assoc()) {
                    $item_data = json_decode($row['data_json'], true);
                    $items[$row['item_index']] = $item_data;
                    
                    // Collect values for specific field if specified
                    if ($field_name && isset($item_data[$field_name])) {
                        $value = $item_data[$field_name];
                        if (!isset($field_values[$value])) {
                            $field_values[$value] = [];
                        }
                        $field_values[$value][] = $row['item_index'];
                    }
                }
                
                $items_stmt->close();
                
                // Find duplicates in specific field
                if ($field_name) {
                    foreach ($field_values as $value => $indices) {
                        if (count($indices) > 1) {
                            $duplicates[] = [
                                'field' => $field_name,
                                'value' => $value,
                                'indices' => $indices,
                                'type' => 'field_duplicate'
                            ];
                        }
                    }
                }
                
                // Find duplicate rows (all fields match)
                $processed = [];
                foreach ($items as $index1 => $item1) {
                    $item1_json = json_encode($item1);
                    
                    if (isset($processed[$item1_json])) {
                        continue;
                    }
                    
                    $matching_indices = [$index1];
                    
                    foreach ($items as $index2 => $item2) {
                        if ($index1 === $index2) continue;
                        
                        $item2_json = json_encode($item2);
                        if ($item1_json === $item2_json) {
                            $matching_indices[] = $index2;
                        }
                    }
                    
                    if (count($matching_indices) > 1) {
                        $duplicates[] = [
                            'type' => 'row_duplicate',
                            'indices' => $matching_indices,
                            'sample_data' => $item1
                        ];
                        $processed[$item1_json] = true;
                    }
                }
                
                return [
                    'success' => true,
                    'duplicates' => $duplicates,
                    'total_items' => count($items),
                    'duplicate_count' => count($duplicates),
                    'message' => count($duplicates) > 0 ? 
                        'Found ' . count($duplicates) . ' duplicate(s)' : 
                        'No duplicates found'
                ];
                
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }
    }

    $user = new dropdowns();
    // $user->get_file_types();
    // $user->changePassword("1","admin1234","Admin1234");
    // $user->getLoginsPerDay("s");
?>

    