<?php
session_start();
include 'admin.php';

class backup_handler extends db_connect {
    
    public function get_backup_stats() {
        $conn = $this->connect();
        
        $stats = array(
            'total_backups' => 0,
            'total_size' => 0,
            'last_backup' => 'Never',
            'auto_backup' => false
        );
        
        try {
            // Count backup files
            $backup_dir = '../backups/';
            if (file_exists($backup_dir)) {
                $files = glob($backup_dir . '*.sql');
                $stats['total_backups'] = count($files);
                
                // Calculate total size
                foreach ($files as $file) {
                    $stats['total_size'] += filesize($file);
                }
                $stats['total_size'] = round($stats['total_size'] / 1024 / 1024, 2);
                
                // Get last backup date
                if (count($files) > 0) {
                    $latest_file = max(array_map('filemtime', $files));
                    $stats['last_backup'] = date('Y-m-d H:i:s', $latest_file);
                }
            }
            
            // Check auto backup setting
            $stmt = $conn->prepare("SELECT config_value FROM system_config WHERE config_key = 'auto_backup'");
            $stmt->execute();
            $stmt->bind_result($auto_backup);
            $stmt->fetch();
            $stats['auto_backup'] = ($auto_backup == '1');
            $stmt->close();
            
        } catch (Exception $e) {
            error_log("Backup stats error: " . $e->getMessage());
        }
        
        return json_encode($stats);
    }
    
    public function get_backup_list() {
        $conn = $this->connect();
        $backup_dir = '../backups/';
        
        if (!file_exists($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }
        
        $files = glob($backup_dir . '*.sql');
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $html = '';
        foreach ($files as $file) {
            $filename = basename($file);
            $filesize = round(filesize($file) / 1024 / 1024, 2);
            $filetime = date('Y-m-d H:i:s', filemtime($file));
            $backup_type = (strpos($filename, 'full_') === 0) ? 'Full' : 'Database';
            
            $html .= '<tr>';
            $html .= '<td>' . $filename . '</td>';
            $html .= '<td>' . $backup_type . '</td>';
            $html .= '<td>' . $filesize . ' MB</td>';
            $html .= '<td>' . $filetime . '</td>';
            $html .= '<td><span class="badge badge-success">Complete</span></td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-info" onclick="downloadBackup(\'' . $filename . '\')">Download</button> ';
            $html .= '<button class="btn btn-sm btn-danger" onclick="deleteBackup(\'' . $filename . '\')">Delete</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        
        if (empty($html)) {
            $html = '<tr><td colspan="6" class="text-center">No backup files found</td></tr>';
        }
        
        return $html;
    }
    
    public function create_database_backup() {
        try {
            $conn = $this->connect();
            $backup_dir = '../backups/';
            
            if (!file_exists($backup_dir)) {
                mkdir($backup_dir, 0755, true);
            }
            
            $timestamp = date('Ymd_His');
            $backup_file = $backup_dir . 'db_backup_' . $timestamp . '.sql';
            
            // Get database connection details from connection.php
            include 'connection.php';
            
            // Use mysqldump if available
            $command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " > " . $backup_file . " 2>&1";
            
            system($command, $output);
            
            if (file_exists($backup_file) && filesize($backup_file) > 0) {
                return json_encode(array(
                    'success' => true,
                    'message' => 'Database backup created successfully',
                    'filename' => basename($backup_file),
                    'download_url' => '../backups/' . basename($backup_file)
                ));
            } else {
                return json_encode(array(
                    'success' => false,
                    'message' => 'Failed to create database backup'
                ));
            }
            
        } catch (Exception $e) {
            return json_encode(array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ));
        }
    }
}

// Handle requests
$handler = new backup_handler();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_stats':
            echo $handler->get_backup_stats();
            break;
        case 'get_list':
            echo $handler->get_backup_list();
            break;
        case 'create_db_backup':
            echo $handler->create_database_backup();
            break;
        default:
            echo json_encode(array('error' => 'Invalid action'));
    }
    exit;
}

echo json_encode(array('error' => 'No action specified'));
?>