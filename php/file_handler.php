<?php
// Enable error logging but don't display them
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to send JSON response
function send_json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Function to handle errors
function handle_error($message, $debug_info = null) {
    $response = [
        'id' => 0,
        'mssg' => $message,
        'type' => 'error'
    ];
    
    if ($debug_info !== null) {
        $response['debug'] = $debug_info;
    }
    
    send_json_response($response);
}

try {
    // Include admin.php
    require_once 'admin.php';
    
    // Handle file upload and signing
    if (isset($_POST['upload_file'])) {
        
        // Validate required fields
        if (empty($_POST['file_type']) || empty($_POST['certificate_id']) || empty($_POST['file_reference'])) {
            handle_error("All required fields must be filled");
        }
        
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $error_msg = "Please select a valid CSV file.";
            if (isset($_FILES['csv_file']['error'])) {
                $error_codes = [
                    UPLOAD_ERR_INI_SIZE => 'File is too large (server limit)',
                    UPLOAD_ERR_FORM_SIZE => 'File is too large (form limit)',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
                ];
                $error_msg .= ' Error: ' . ($error_codes[$_FILES['csv_file']['error']] ?? 'Unknown error');
            }
            handle_error($error_msg);
        }
        
        // Validate file type
        $allowed_types = ['text/csv', 'text/plain', 'application/vnd.ms-excel', 'application/csv'];
        $file_type = mime_content_type($_FILES['csv_file']['tmp_name']);
        
        if (!in_array($file_type, $allowed_types)) {
            handle_error("Invalid file type: $file_type. Please upload a CSV file.");
        }
        
        // Validate file size (10MB max)
        if ($_FILES['csv_file']['size'] > 10 * 1024 * 1024) {
            handle_error("File size must be less than 10MB");
        }
        
        // Check if user is logged in
        if (!isset($_SESSION["bt_user_id"])) {
            handle_error("You must be logged in to upload files");
        }
        
        // Process the upload
        $file_management = new file_management();
        $result = $file_management->upload_and_sign_file(
            $_POST['file_type'],
            $_POST['certificate_id'],
            $_POST['file_reference'],
            $_FILES['csv_file']['tmp_name'],
            $_FILES['csv_file']['name'],
            $_POST['cert_password'] ?? ''
        );
        
        if ($result['success']) {
            $response = [
                'id' => 1,
                'mssg' => $result['message'],
                'type' => 'success',
                'download_url' => './php/file_handler.php?action=download_signed&id=' . $result['file_id']
            ];
            send_json_response($response);
        } else {
            handle_error($result['message']);
        }
        
    }
    
    // Handle file downloads
    // elseif (isset($_GET['action']) && isset($_GET['id'])) {
    //     $action = $_GET['action'];
    //     $file_id = intval($_GET['id']);
        
    //     if (!isset($_SESSION["bt_user_id"])) {
    //         header('HTTP/1.0 401 Unauthorized');
    //         echo "You must be logged in to download files";
    //         exit;
    //     }
        
    //     $conn = new db_connect();
    //     $db = $conn->connect();
        
    //     if ($action === 'download') {
    //         $stmt = $db->prepare("SELECT stored_path, original_name FROM uploaded_files WHERE id = ?");
    //         $stmt->bind_param('i', $file_id);
    //         $stmt->execute();
    //         $stmt->bind_result($file_path, $original_name);
    //         $stmt->fetch();
    //         $stmt->close();
            
    //         if (file_exists($file_path)) {
    //             header('Content-Type: application/octet-stream');
    //             header('Content-Disposition: attachment; filename="' . $original_name . '"');
    //             header('Content-Length: ' . filesize($file_path));
    //             readfile($file_path);
    //             exit;
    //         } else {
    //             header('HTTP/1.0 404 Not Found');
    //             echo "File not found";
    //             exit;
    //         }
            
    //     } elseif ($action === 'download_signed') {
    //         $stmt = $db->prepare("
    //             SELECT sf.signed_file_path, uf.original_name 
    //             FROM signed_files sf
    //             JOIN uploaded_files uf ON sf.original_file_id = uf.id
    //             WHERE uf.id = ?
    //         ");
    //         $stmt->bind_param('i', $file_id);
    //         $stmt->execute();
    //         $stmt->bind_result($signed_file_path, $original_name);
    //         $stmt->fetch();
    //         $stmt->close();
            
    //         if (file_exists($signed_file_path)) {
    //             $original_name_without_ext = pathinfo($original_name, PATHINFO_FILENAME);
    //             $download_name = 'signed_' . $original_name_without_ext . '.csv';
                
    //             header('Content-Type: application/octet-stream');
    //             header('Content-Disposition: attachment; filename="' . $download_name . '"');
    //             header('Content-Length: ' . filesize($signed_file_path));
    //             header('Cache-Control: must-revalidate');
    //             header('Pragma: public');
                
    //             readfile($signed_file_path);
    //             exit;
    //         } else {
    //             header('HTTP/1.0 404 Not Found');
    //             echo "Signed file not found";
    //             exit;
    //         }
    //     }
        
    //     handle_error("Invalid action specified");
    // }

    // Handle download generated file
    elseif (isset($_GET['action']) && $_GET['action'] === 'download_generated' && isset($_GET['id'])) {
        $file_id = intval($_GET['id']);
        
        if (!isset($_SESSION["bt_user_id"])) {
            header('HTTP/1.0 401 Unauthorized');
            echo "You must be logged in to download files";
            exit;
        }
        
        $conn = new db_connect();
        $db = $conn->connect();
        
        $stmt = $db->prepare("
            SELECT file_path, file_name 
            FROM generated_files 
            WHERE id = ?
        ");
        $stmt->bind_param('i', $file_id);
        $stmt->execute();
        $stmt->bind_result($file_path, $file_name);
        $stmt->fetch();
        $stmt->close();
        
        if ($file_path && file_exists($file_path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Content-Length: ' . filesize($file_path));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            readfile($file_path);
            exit;
        } else {
            header('HTTP/1.0 404 Not Found');
            echo "File not found. Path: " . htmlspecialchars($file_path ?? 'Not set');
            exit;
        }
    }

    // In file_handler.php, add OBDX handling
    elseif (isset($_POST['upload_obdx'])) {
        $response = array();
        
        try {
            // Validate required fields
            if (empty($_POST['file_type']) || empty($_POST['file_reference'])) {
                throw new Exception("All required fields must be filled");
            }
            
            if (!isset($_FILES['obdx_file']) || $_FILES['obdx_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please select a valid OBDX file");
            }
            
            // Check if user is logged in
            if (!isset($_SESSION["bt_user_id"])) {
                throw new Exception("You must be logged in to upload files");
            }
            
            // Validate file size (10MB max)
            if ($_FILES['obdx_file']['size'] > 10 * 1024 * 1024) {
                throw new Exception("File size must be less than 10MB");
            }
            
            // Check file extension
            $allowed_extensions = ['csv', 'txt'];
            $file_extension = strtolower(pathinfo($_FILES['obdx_file']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception("Invalid file type. Only CSV and TXT files are allowed");
            }
            
            // Process OBDX file
            $obdx_parser = new obdx_parser();
            $temp_file = $_FILES['obdx_file']['tmp_name'];
            
            // Parse and validate OBDX file
            $parse_result = $obdx_parser->validate_obdx_file($temp_file, $_POST['file_type']);
            
            if (!$parse_result['success']) {
                $error_msg = "OBDX file validation failed:\n";
                foreach ($parse_result['validation_errors'] as $error) {
                    $error_msg .= "- " . $error . "\n";
                }
                throw new Exception($error_msg);
            }
            
            // Create batch record
            $conn = new db_connect();
            $db = $conn->connect();
            
            $db->begin_transaction();
            
            // Check if reference exists
            $check_stmt = $db->prepare("SELECT id FROM file_batches WHERE reference_no = ?");
            $check_stmt->bind_param('s', $_POST['file_reference']);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                throw new Exception("File reference '{$_POST['file_reference']}' already exists");
            }
            $check_stmt->close();
            
            // Generate batch number
            $batch_number = 'OBDX-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // Insert batch
            $batch_stmt = $db->prepare("
                INSERT INTO file_batches (batch_number, file_type_id, reference_no, 
                                        currency_code, total_amount, total_count, 
                                        status, created_by)
                VALUES (?, ?, ?, ?, ?, ?, 'uploaded', ?)
            ");
            
            $currency_code = $parse_result['header']['currency_code'] ?? 'MWK';
            $batch_stmt->bind_param('sissdii', $batch_number, $_POST['file_type'], 
                                $_POST['file_reference'], $currency_code, 
                                $parse_result['total_amount'], $parse_result['total_count'],
                                $_SESSION["bt_user_id"]);
            
            if (!$batch_stmt->execute()) {
                throw new Exception("Failed to create batch: " . $batch_stmt->error);
            }
            
            $batch_id = $db->insert_id;
            $batch_stmt->close();
            
            // Save uploaded file
            $upload_dir = '../obdx_uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $stored_name = 'obdx_' . time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $_FILES['obdx_file']['name']);
            $stored_path = $upload_dir . $stored_name;
            
            if (!move_uploaded_file($temp_file, $stored_path)) {
                throw new Exception("Failed to save uploaded file");
            }
            
            $upload_stmt = $db->prepare("
                INSERT INTO uploaded_files (original_name, stored_name, stored_path, 
                                        file_size, file_type, uploaded_by, 
                                        status, batch_id)
                VALUES (?, ?, ?, ?, 'text/csv', ?, 'processed', ?)
            ");
            
            $file_size = filesize($stored_path);
            $upload_stmt->bind_param('sssiii', $_FILES['obdx_file']['name'], $stored_name, 
                                $stored_path, $file_size, $_SESSION["bt_user_id"], $batch_id);
            
            if (!$upload_stmt->execute()) {
                throw new Exception("Failed to save file record: " . $upload_stmt->error);
            }
            
            $file_id = $db->insert_id;
            $upload_stmt->close();
            
            // Save batch items
            $item_index = 1;
            foreach ($parse_result['body'] as $item) {
                $item_json = json_encode($item);
                
                $item_stmt = $db->prepare("
                    INSERT INTO batch_items (batch_id, item_index, data_json, status)
                    VALUES (?, ?, ?, 'processed')
                ");
                
                $item_stmt->bind_param('iis', $batch_id, $item_index, $item_json);
                $item_stmt->execute();
                $item_stmt->close();
                $item_index++;
            }
            
            // Update batch status
            $update_stmt = $db->prepare("UPDATE file_batches SET status = 'generated' WHERE id = ?");
            $update_stmt->bind_param('i', $batch_id);
            $update_stmt->execute();
            $update_stmt->close();
            
            $db->commit();
            
            // Log audit trail
            $log = new general();
            $log->logAuditTrail('file_batches', $batch_id, 'INSERT', $_SESSION["bt_user_id"], 
                            "OBDX file uploaded: {$_FILES['obdx_file']['name']}");
            
            $response = array(
                'id' => 1,
                'mssg' => "OBDX file uploaded and validated successfully. {$parse_result['total_count']} records processed.",
                'batch_id' => $batch_id,
                'file_id' => $file_id,
                'download_url' => './php/file_handler.php?action=download_unsigned&id=' . $batch_id,
                'type' => 'success'
            );
            
        } catch (Exception $e) {
            if (isset($db)) {
                $db->rollback();
            }
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
    handle_error("Invalid request");
    
} catch (Exception $e) {
    // Log the error
    error_log("File handler error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    
    // Send error response
    handle_error("An error occurred: " . $e->getMessage());
}
?>