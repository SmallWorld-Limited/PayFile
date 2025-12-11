<?php
session_start();
include 'admin.php';

// Handle add validation rule
if (isset($_POST['add_validation_rule'])) {
    $response = array();
    
    try {
        // Validate required fields
        $required = ['rule_name', 'rule_type', 'rule_condition', 'error_message'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Sanitize inputs
        $rule_name = trim($_POST['rule_name']);
        $rule_type = $_POST['rule_type'];
        $rule_condition = trim($_POST['rule_condition']);
        $error_message = trim($_POST['error_message']);
        $severity = $_POST['severity'] ?? 'error';
        $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
        
        $validation_mgmt = new validation_management();
        $result = $validation_mgmt->add_validation_rule(
            $rule_name, $rule_type, $rule_condition, $error_message, $severity, $is_active
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

// Handle edit validation rule
elseif (isset($_POST['edit_validation_rule'])) {
    $response = array();
    
    try {
        // Validate required fields
        if (empty($_POST['rule_id'])) {
            throw new Exception("Rule ID is required");
        }
        
        $required = ['rule_name', 'rule_type', 'rule_condition', 'error_message'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }
        
        // Sanitize inputs
        $rule_id = intval($_POST['rule_id']);
        $rule_name = trim($_POST['rule_name']);
        $rule_type = $_POST['rule_type'];
        $rule_condition = trim($_POST['rule_condition']);
        $error_message = trim($_POST['error_message']);
        $severity = $_POST['severity'] ?? 'error';
        $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
        
        $validation_mgmt = new validation_management();
        $result = $validation_mgmt->update_validation_rule(
            $rule_id, $rule_name, $rule_type, $rule_condition, $error_message, $severity, $is_active
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

// Handle delete validation rule
elseif (isset($_POST['delete_validation_rule'])) {
    $response = array();
    
    try {
        if (empty($_POST['rule_id'])) {
            throw new Exception("Rule ID is required");
        }
        
        $rule_id = intval($_POST['rule_id']);
        $validation_mgmt = new validation_management();
        $result = $validation_mgmt->delete_validation_rule($rule_id);
        
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

// Handle test validation rule
elseif (isset($_POST['test_validation_rule'])) {
    $response = array();
    
    try {
        if (empty($_POST['rule_condition'])) {
            throw new Exception("Rule condition is required");
        }
        
        $rule_condition = trim($_POST['rule_condition']);
        $validation_mgmt = new validation_management();
        $result = $validation_mgmt->test_validation_rule($rule_condition);
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'test_result' => $result['test_result'],
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

// Handle validate batch
elseif (isset($_POST['validate_batch'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id'])) {
            throw new Exception("Batch ID is required");
        }
        
        $batch_id = intval($_POST['batch_id']);
        $validation_mgmt = new validation_management();
        $result = $validation_mgmt->validate_batch($batch_id);
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'validation_results' => $result['validation_results'],
                'status' => $result['status'],
                'has_errors' => $result['has_errors'],
                'has_warnings' => $result['has_warnings'],
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

// Handle check duplicates
elseif (isset($_POST['check_duplicates'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id'])) {
            throw new Exception("Batch ID is required");
        }
        
        $batch_id = intval($_POST['batch_id']);
        $field_name = $_POST['field_name'] ?? null;
        
        $validation_mgmt = new validation_management();
        $result = $validation_mgmt->check_duplicates($batch_id, $field_name);
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'duplicates' => $result['duplicates'],
                'total_items' => $result['total_items'],
                'duplicate_count' => $result['duplicate_count'],
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

// Handle get rule details
elseif (isset($_POST['get_rule_details'])) {
    $response = array();
    
    try {
        if (empty($_POST['rule_id'])) {
            throw new Exception("Rule ID is required");
        }
        
        $rule_id = intval($_POST['rule_id']);
        $validation_mgmt = new validation_management();
        $rule = $validation_mgmt->get_rule_details($rule_id);
        
        if ($rule) {
            $response = array(
                'id' => 1,
                'rule' => $rule,
                'type' => 'success'
            );
        } else {
            throw new Exception("Rule not found");
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

// If no valid action found
header('HTTP/1.0 400 Bad Request');
echo json_encode(array(
    'id' => 0,
    'mssg' => 'Invalid request',
    'type' => 'error'
));
?>