<?php
session_start();
include 'admin.php';

// Get system health stats
if (isset($_GET['action']) && $_GET['action'] == 'get_stats') {
    $response = array();
    
    try {
        $conn = $this->connect();
        
        // Get system uptime (simplified - in a real system you'd get this from the OS)
        $uptime = shell_exec('uptime -p 2>/dev/null || echo "Server uptime unavailable"');
        
        // Get server load
        $load = sys_getloadavg();
        
        // Get memory usage
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $used_mem = $mem[2];
        $total_mem = $mem[1];
        $memory_usage = round($used_mem / $total_mem * 100, 2);
        
        // Get disk usage
        $disk_total = disk_total_space('/');
        $disk_free = disk_free_space('/');
        $disk_used = $disk_total - $disk_free;
        $disk_usage = round(($disk_used / $disk_total) * 100, 2);
        
        $response = array(
            'uptime' => trim($uptime),
            'load_average' => $load,
            'memory_usage' => $memory_usage,
            'total_memory' => round($total_mem / 1024 / 1024, 2) . ' GB',
            'disk_usage' => $disk_usage,
            'total_disk' => round($disk_total / 1024 / 1024 / 1024, 2) . ' GB',
            'free_disk' => round($disk_free / 1024 / 1024 / 1024, 2) . ' GB'
        );
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        echo json_encode(array(
            'error' => $e->getMessage()
        ));
    }
    exit;
}

// Run health check
if (isset($_GET['action']) && $_GET['action'] == 'run_health_check') {
    $checks = array();
    
    try {
        // Database connection check
        $conn = $this->connect();
        if ($conn->connect_error) {
            $checks[] = array('check' => 'Database Connection', 'status' => 'FAILED', 'message' => $conn->connect_error);
        } else {
            $checks[] = array('check' => 'Database Connection', 'status' => 'OK', 'message' => 'Connected successfully');
        }
        
        // OpenSSL check
        $openssl_version = shell_exec('openssl version 2>&1');
        if (strpos($openssl_version, 'OpenSSL') !== false) {
            $checks[] = array('check' => 'OpenSSL', 'status' => 'OK', 'message' => trim($openssl_version));
        } else {
            $checks[] = array('check' => 'OpenSSL', 'status' => 'WARNING', 'message' => 'OpenSSL not found or not accessible');
        }
        
        // Directory permissions
        $directories = array(
            '../uploads/' => 'Uploads Directory',
            '../signed_files/' => 'Signed Files Directory',
            '../backups/' => 'Backups Directory'
        );
        
        foreach ($directories as $dir => $name) {
            if (!file_exists($dir)) {
                if (@mkdir($dir, 0755, true)) {
                    $checks[] = array('check' => $name, 'status' => 'OK', 'message' => 'Created successfully');
                } else {
                    $checks[] = array('check' => $name, 'status' => 'ERROR', 'message' => 'Cannot create directory');
                }
            } elseif (!is_writable($dir)) {
                $checks[] = array('check' => $name, 'status' => 'ERROR', 'message' => 'Directory not writable');
            } else {
                $checks[] = array('check' => $name, 'status' => 'OK', 'message' => 'Writable');
            }
        }
        
        // Session check
        if (session_status() === PHP_SESSION_ACTIVE) {
            $checks[] = array('check' => 'Session Management', 'status' => 'OK', 'message' => 'Sessions active');
        } else {
            $checks[] = array('check' => 'Session Management', 'status' => 'WARNING', 'message' => 'Sessions not active');
        }
        
        // Build HTML response
        $html = '<div class="health-check-results">';
        $html .= '<h5>Health Check Results</h5>';
        $html .= '<div class="table-responsive"><table class="table table-sm">';
        $html .= '<thead><tr><th>Check</th><th>Status</th><th>Message</th></tr></thead><tbody>';
        
        foreach ($checks as $check) {
            $status_class = strtolower($check['status']);
            $html .= '<tr>';
            $html .= '<td>' . $check['check'] . '</td>';
            $html .= '<td><span class="badge badge-' . $status_class . '">' . $check['status'] . '</span></td>';
            $html .= '<td>' . $check['message'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table></div>';
        
        echo $html;
        
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error running health check: ' . $e->getMessage() . '</div>';
    }
    exit;
}

echo json_encode(array('error' => 'Invalid action'));
?>