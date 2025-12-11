<?php
session_start();
include 'admin.php';

// Handle create batch
if (isset($_POST['create_batch'])) {
    $response = array();
    
    try {
        if (!isset($_POST['batch_data'])) {
            throw new Exception("Batch data is required");
        }
        
        $batch_data = json_decode($_POST['batch_data'], true);
        
        if (!$batch_data || !isset($batch_data['file_type_id'])) {
            throw new Exception("Invalid batch data");
        }
        
        $conn = new db_connect();
        $db = $conn->connect();
        
        $db->begin_transaction();
        
        // Generate batch number
        $file_type_stmt = $db->prepare("SELECT code FROM file_types WHERE id = ?");
        $file_type_stmt->bind_param('i', $batch_data['file_type_id']);
        $file_type_stmt->execute();
        $file_type_stmt->bind_result($file_code);
        $file_type_stmt->fetch();
        $file_type_stmt->close();
        
        $batch_number = $file_code . '-' . date('Ymd') . '-' . rand(1000, 9999);
        
        // Insert batch
        $batch_stmt = $db->prepare("
            INSERT INTO file_batches (batch_number, file_type_id, reference_no, 
                                    currency_code, total_amount, total_count, 
                                    status, created_by, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $user_id = $_SESSION["bt_user_id"];
        $status = $batch_data['status'] ?? 'draft';
        $notes = $batch_data['description'] ?? '';
        
        $batch_stmt->bind_param('sissdiiis', $batch_number, $batch_data['file_type_id'], 
                              $batch_data['reference_no'], $batch_data['currency_code'],
                              $batch_data['total_amount'], $batch_data['total_count'],
                              $status, $user_id, $notes);
        
        if (!$batch_stmt->execute()) {
            throw new Exception("Failed to create batch: " . $batch_stmt->error);
        }
        
        $batch_id = $db->insert_id;
        $batch_stmt->close();
        
        // Insert items
        if (isset($batch_data['items']) && is_array($batch_data['items'])) {
            $item_index = 1;
            foreach ($batch_data['items'] as $item) {
                // Create data_json based on file type
                $data_json = json_encode([
                    'trans_serial' => $item['trans_serial'] ?? str_pad($item_index, 4, '0', STR_PAD_LEFT),
                    'debit_account' => $item['debit_account'] ?? '',
                    'debit_name' => $item['debit_name'] ?? '',
                    'amount' => $item['amount'] ?? 0,
                    'payee' => $item['payee'] ?? ''
                ]);
                
                $item_stmt = $db->prepare("
                    INSERT INTO batch_items (batch_id, item_index, data_json, status)
                    VALUES (?, ?, ?, 'pending')
                ");
                
                $item_stmt->bind_param('iis', $batch_id, $item_index, $data_json);
                $item_stmt->execute();
                $item_stmt->close();
                $item_index++;
            }
        }
        
        $db->commit();
        
        // Log audit trail
        $log = new general();
        $log->logAuditTrail('file_batches', $batch_id, 'INSERT', $user_id, 
                          "Batch created: $batch_number");
        
        $response = array(
            'success' => true,
            'message' => 'Batch created successfully',
            'batch_id' => $batch_id,
            'batch_number' => $batch_number
        );
        
    } catch (Exception $e) {
        if (isset($db)) {
            $db->rollback();
        }
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle add comment
elseif (isset($_POST['add_comment'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id']) || empty($_POST['comment'])) {
            throw new Exception("Batch ID and comment are required");
        }
        
        $batch_mgmt = new batch_management();
        $result = $batch_mgmt->add_batch_comment(
            $_POST['batch_id'],
            $_POST['comment'],
            $_POST['comment_type'] ?? 'note'
        );
        
        $response = $result;
        
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle update batch status
elseif (isset($_POST['update_status'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id']) || empty($_POST['new_status'])) {
            throw new Exception("Batch ID and new status are required");
        }
        
        $batch_mgmt = new batch_management();
        $result = $batch_mgmt->update_batch_status(
            $_POST['batch_id'],
            $_POST['new_status'],
            $_POST['notes'] ?? ''
        );
        
        $response = $result;
        
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle delete batch
elseif (isset($_POST['delete_batch'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id'])) {
            throw new Exception("Batch ID is required");
        }
        
        $batch_mgmt = new batch_management();
        $result = $batch_mgmt->delete_batch($_POST['batch_id']);
        
        $response = $result;
        
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle prepare for signing
elseif (isset($_POST['prepare_for_signing'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id'])) {
            throw new Exception("Batch ID is required");
        }
        
        $signing_queue = new signing_queue();
        $result = $signing_queue->prepare_for_signing($_POST['batch_id']);
        
        $response = $result;
        
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle sign file
elseif (isset($_POST['sign_file'])) {
    $response = array();
    
    try {
        if (empty($_POST['batch_id']) || empty($_POST['certificate_id'])) {
            throw new Exception("Batch ID and certificate are required");
        }
        
        // Check if user is logged in
        if (!isset($_SESSION["bt_user_id"])) {
            throw new Exception("You must be logged in to sign files");
        }
        
        $file_management = new file_management();
        
        // Get batch details
        $batch_mgmt = new batch_management();
        $batch = $batch_mgmt->get_batch_details($_POST['batch_id']);
        
        if (!$batch) {
            throw new Exception("Batch not found");
        }
        
        if ($batch['status'] != 'ready_to_sign') {
            throw new Exception("File must be in 'ready_to_sign' status");
        }
        
        if (empty($batch['file_path']) || !file_exists($batch['file_path'])) {
            throw new Exception("Original file not found");
        }
        
        // Sign the file
        $result = $file_management->sign_existing_file(
            $_POST['batch_id'],
            $_POST['certificate_id'],
            $_POST['cert_password'] ?? ''
        );
        
        if ($result['success']) {
            // Update batch status
            $batch_mgmt->update_batch_status(
                $_POST['batch_id'],
                'signed',
                "File signed with certificate ID: " . $_POST['certificate_id']
            );
            
            $response = array(
                'success' => true,
                'message' => 'File signed successfully',
                'signed_file_path' => $result['signed_file_path'],
                'download_url' => './php/file_handler.php?action=download_signed&id=' . $_POST['batch_id']
            );
        } else {
            throw new Exception($result['message']);
        }
        
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle get certificates
elseif (isset($_POST['get_certificates'])) {
    $response = array();
    
    try {
        $signing_queue = new signing_queue();
        $certificates = $signing_queue->get_available_certificates();
        
        $response = array(
            'success' => true,
            'certificates' => $certificates
        );
        
    } catch (Exception $e) {
        $response = array(
            'success' => false,
            'message' => $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// If no valid action found
header('HTTP/1.0 400 Bad Request');
echo json_encode(array(
    'success' => false,
    'message' => 'Invalid request'
));
?>