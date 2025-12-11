<?php
session_start();
include 'admin.php';

// Handle save configuration
if (isset($_POST['save_config'])) {
    $response = array();
    
    try {
        // Validate user is admin
        if ($_SESSION["bt_role"] != 'admin') {
            throw new Exception("Only administrators can modify system configuration");
        }
        
        // Update configuration values
        $config_updates = array();
        
        // Map form fields to config keys
        $field_mapping = array(
            'openssl_path' => 'openssl_path',
            'default_certificate' => 'default_certificate',
            'upload_max_size' => 'upload_max_size',
            'allowed_file_types' => 'allowed_file_types',
            'file_storage_path' => 'file_storage_path',
            'backup_retention_days' => 'backup_retention_days',
            'auto_backup' => 'auto_backup',
            'session_timeout' => 'session_timeout',
            'max_login_attempts' => 'max_login_attempts',
            'enable_2fa' => 'enable_2fa',
            'system_name' => 'system_name',
            'system_email' => 'system_email',
            'timezone' => 'timezone'
        );
        
        $conn = $this->connect();
        
        foreach ($field_mapping as $form_field => $config_key) {
            if (isset($_POST[$form_field])) {
                $value = $_POST[$form_field];
                
                // Special handling for certain fields
                switch ($form_field) {
                    case 'upload_max_size':
                        $value = $value * 1024 * 1024; // Convert MB to bytes
                        break;
                    case 'session_timeout':
                        $value = $value * 60; // Convert minutes to seconds
                        break;
                }
                
                // Update in database
                $stmt = $conn->prepare("UPDATE system_config SET config_value = ? WHERE config_key = ?");
                $stmt->bind_param('ss', $value, $config_key);
                $stmt->execute();
                $stmt->close();
            }
        }
        
        $response = array(
            'id' => 1,
            'mssg' => 'Configuration saved successfully',
            'type' => 'success'
        );
        
    } catch (Exception $e) {
        $response = array(
            'id' => 0,
            'mssg' => $e->getMessage(),
            'type' => 'error'
        );
    }
    
    echo json_encode($response);
    exit;
}

// If no valid action found
header('HTTP/1.0 400 Bad Request');
echo json_encode(array(
    'id' => 0,
    'mssg' => 'Invalid request',
    'type' => 'error'
));
?>