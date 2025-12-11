<?php
session_start();
include 'admin.php';

// Handle add certificate
if (isset($_POST['add_cert'])) {
    $response = array();
    
    try {
        // Validate required fields
        $required = ['certificate_name', 'certificate_path', 'private_key_path', 'certificate_type'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Sanitize inputs
        $cert_name = trim($_POST['certificate_name']);
        $cert_path = trim($_POST['certificate_path']);
        $key_path = trim($_POST['private_key_path']);
        $cert_password = isset($_POST['certificate_password']) ? trim($_POST['certificate_password']) : '';
        $cert_type = $_POST['certificate_type'];
        $issuer = isset($_POST['issuer']) ? trim($_POST['issuer']) : '';
        $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
        $serial_number = isset($_POST['serial_number']) ? trim($_POST['serial_number']) : '';
        $valid_from = isset($_POST['valid_from']) ? $_POST['valid_from'] : null;
        $valid_to = isset($_POST['valid_to']) ? $_POST['valid_to'] : null;
        $is_default = isset($_POST['is_default']) && $_POST['is_default'] == '1' ? 1 : 0;
        $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
        
        // Convert datetime strings to MySQL format
        if ($valid_from) $valid_from = date('Y-m-d H:i:s', strtotime($valid_from));
        if ($valid_to) $valid_to = date('Y-m-d H:i:s', strtotime($valid_to));
        
        $cert_mgmt = new certificate_management();
        $result = $cert_mgmt->add_certificate(
            $cert_name, $cert_path, $key_path, $cert_password,
            $cert_type, $issuer, $subject, $valid_from, $valid_to,
            $is_default, $is_active
        );
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'type' => 'success'
            );
        } else {
            throw new Exception($result['message']);
        }
        
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

// Handle edit certificate
elseif (isset($_POST['edit_cert'])) {
    $response = array();
    
    try {
        // Validate required fields
        if (empty($_POST['cert_id'])) {
            throw new Exception("Certificate ID is required");
        }
        
        $required = ['certificate_name', 'certificate_path', 'private_key_path', 'certificate_type'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Sanitize inputs
        $cert_id = intval($_POST['cert_id']);
        $cert_name = trim($_POST['certificate_name']);
        $cert_path = trim($_POST['certificate_path']);
        $key_path = trim($_POST['private_key_path']);
        $cert_password = isset($_POST['certificate_password']) ? trim($_POST['certificate_password']) : '';
        $cert_type = $_POST['certificate_type'];
        $issuer = isset($_POST['issuer']) ? trim($_POST['issuer']) : '';
        $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
        $serial_number = isset($_POST['serial_number']) ? trim($_POST['serial_number']) : '';
        $valid_from = isset($_POST['valid_from']) ? $_POST['valid_from'] : null;
        $valid_to = isset($_POST['valid_to']) ? $_POST['valid_to'] : null;
        $is_default = isset($_POST['is_default']) && $_POST['is_default'] == '1' ? 1 : 0;
        $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
        
        // Convert datetime strings to MySQL format
        if ($valid_from) $valid_from = date('Y-m-d H:i:s', strtotime($valid_from));
        if ($valid_to) $valid_to = date('Y-m-d H:i:s', strtotime($valid_to));
        
        $cert_mgmt = new certificate_management();
        $result = $cert_mgmt->edit_certificate(
            $cert_id, $cert_name, $cert_path, $key_path, $cert_password,
            $cert_type, $issuer, $subject, $valid_from, $valid_to,
            $is_default, $is_active
        );
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'type' => 'success'
            );
        } else {
            throw new Exception($result['message']);
        }
        
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

// Handle delete certificate
elseif (isset($_POST['delete_cert'])) {
    $response = array();
    
    try {
        if (empty($_POST['cert_id'])) {
            throw new Exception("Certificate ID is required");
        }
        
        $cert_id = intval($_POST['cert_id']);
        $cert_mgmt = new certificate_management();
        $result = $cert_mgmt->delete_certificate($cert_id);
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'type' => 'success'
            );
        } else {
            throw new Exception($result['message']);
        }
        
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

// Handle set default certificate
elseif (isset($_POST['set_default_cert'])) {
    $response = array();
    
    try {
        if (empty($_POST['cert_id'])) {
            throw new Exception("Certificate ID is required");
        }
        
        $cert_id = intval($_POST['cert_id']);
        $cert_mgmt = new certificate_management();
        $result = $cert_mgmt->set_default_certificate($cert_id);
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'type' => 'success'
            );
        } else {
            throw new Exception($result['message']);
        }
        
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

// Handle get certificate details
elseif (isset($_POST['get_cert_details'])) {
    $response = array();
    
    try {
        if (empty($_POST['cert_id'])) {
            throw new Exception("Certificate ID is required");
        }
        
        $cert_id = intval($_POST['cert_id']);
        $cert_mgmt = new certificate_management();
        $certificate = $cert_mgmt->get_certificate_details($cert_id);
        
        if ($certificate) {
            $response = array(
                'id' => 1,
                'certificate' => $certificate,
                'type' => 'success'
            );
        } else {
            throw new Exception("Certificate not found");
        }
        
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

// Handle test certificate (optional feature)
elseif (isset($_POST['test_cert'])) {
    $response = array();
    
    try {
        if (empty($_POST['cert_path']) || empty($_POST['key_path'])) {
            throw new Exception("Certificate and key paths are required");
        }
        
        $cert_path = trim($_POST['cert_path']);
        $key_path = trim($_POST['key_path']);
        $password = isset($_POST['cert_password']) ? trim($_POST['cert_password']) : '';
        
        // Test if files exist
        if (!file_exists($cert_path)) {
            throw new Exception("Certificate file not found: $cert_path");
        }
        
        if (!file_exists($key_path)) {
            throw new Exception("Private key file not found: $key_path");
        }
        
        // Try to read certificate
        $cert_data = file_get_contents($cert_path);
        if (!$cert_data) {
            throw new Exception("Failed to read certificate file");
        }
        
        // Try to parse certificate
        $cert_info = openssl_x509_parse($cert_data);
        if (!$cert_info) {
            throw new Exception("Invalid certificate format");
        }
        
        // Test private key (simplified check)
        $key_data = file_get_contents($key_path);
        if (!$key_data) {
            throw new Exception("Failed to read private key file");
        }
        
        $response = array(
            'id' => 1,
            'mssg' => "Certificate test successful. Valid from " . 
                     date('Y-m-d', $cert_info['validFrom_time_t']) . 
                     " to " . date('Y-m-d', $cert_info['validTo_time_t']),
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