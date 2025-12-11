<?php
// Phase 1: Get certificate from database, keep everything else hardcoded
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include connection but not admin.php yet
include 'connection.php';

function send_json_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function handle_error($message) {
    send_json_response([
        'id' => 0,
        'mssg' => $message,
        'type' => 'error'
    ]);
}

try {
    // HARDCODED VALUES THAT WORK
    const OPENSSL_PATH = 'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe';
    
    if (isset($_POST['upload_file'])) {
        
        // Validate
        if (empty($_POST['file_reference'])) {
            handle_error("File reference is required");
        }
        
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            handle_error("Please select a valid CSV file");
        }
        
        if (!isset($_SESSION["bt_user_id"])) {
            handle_error("You must be logged in");
        }
        
        // STEP 1: TRY TO GET CERTIFICATE FROM DATABASE
        $certificate_id = $_POST['certificate_id'] ?? 1; // Default to ID 1
        
        $conn = new db_connect();
        $db = $conn->connect();
        
        $stmt = $db->prepare("SELECT certificate_path, private_key_path FROM certificates WHERE id = ?");
        $stmt->bind_param('i', $certificate_id);
        $stmt->execute();
        $stmt->bind_result($cert_path, $key_path);
        
        if (!$stmt->fetch()) {
            handle_error("Certificate not found in database. Using hardcoded fallback.");
            // Fallback to hardcoded
            $cert_path = "cert.pem";
            $key_path = "key.pem";
        }
        $stmt->close();
        
        error_log("Database certificate path: " . $cert_path);
        error_log("Database key path: " . $key_path);
        
        // Check if database paths exist
        if (!file_exists($cert_path)) {
            error_log("Database cert path not found, trying relative...");
            // Try relative path
            $project_root = dirname(dirname(__FILE__));
            $relative_cert_path = $project_root . '/' . $cert_path;
            if (file_exists($relative_cert_path)) {
                $cert_path = realpath($relative_cert_path);
                error_log("Found cert at relative path: " . $cert_path);
            } else {
                // Fallback to hardcoded
                $cert_path = "cert.pem";
                error_log("Falling back to hardcoded cert.pem");
            }
        }
        
        if (!file_exists($key_path)) {
            error_log("Database key path not found, trying relative...");
            $project_root = dirname(dirname(__FILE__));
            $relative_key_path = $project_root . '/' . $key_path;
            if (file_exists($relative_key_path)) {
                $key_path = realpath($relative_key_path);
            } else {
                $key_path = "key.pem";
                error_log("Falling back to hardcoded key.pem");
            }
        }
        
        // Final check
        if (!file_exists($cert_path) || !file_exists($key_path)) {
            handle_error("Certificate files not found. Cert: $cert_path, Key: $key_path");
        }
        
        error_log("Final paths - Cert: $cert_path, Key: $key_path");
        
        // Move uploaded file
        $upload_dir = '../uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $stored_name = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $_FILES['csv_file']['name']);
        $stored_path = $upload_dir . $stored_name;
        
        if (!move_uploaded_file($_FILES['csv_file']['tmp_name'], $stored_path)) {
            handle_error("Failed to move uploaded file");
        }
        
        // Generate output filename
        $input_filename = basename($_FILES['csv_file']['name']);
        $filename_without_ext = pathinfo($input_filename, PATHINFO_FILENAME);
        $output_filename = $filename_without_ext . '_Signed.csv';
        $output_file = '../signed_files/' . $output_filename;
        
        // Create signed files directory
        $signed_dir = '../signed_files/';
        if (!file_exists($signed_dir)) {
            mkdir($signed_dir, 0777, true);
        }
        
        // Sign the file
        $result = sign_file_exact($stored_path, $cert_path, $key_path, $output_file, OPENSSL_PATH);
        
        if ($result['success']) {
            $response = [
                'id' => 1,
                'mssg' => 'File signed successfully. Certificate source: ' . 
                         ($cert_path === 'cert.pem' ? 'Hardcoded' : 'Database'),
                'type' => 'success',
                'download_url' => './php/file_handler_phase1.php?action=download&file=' . urlencode($output_filename)
            ];
            send_json_response($response);
        } else {
            handle_error($result['message']);
        }
    }
    
    // Download handler
    elseif (isset($_GET['action']) && isset($_GET['file'])) {
        $file = '../signed_files/' . $_GET['file'];
        
        if (file_exists($file)) {
            $download_name = basename($file);
            
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $download_name . '"');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
        
        handle_error("File not found");
    }
    
    handle_error("Invalid request");
    
} catch (Exception $e) {
    handle_error("Error: " . $e->getMessage());
}

/**
 * Exact signing function from your working script
 */
function sign_file_exact($input_csv, $cert_pem, $key_pem, $output_file, $openssl_path) {
    try {
        // Validate
        if (!file_exists($openssl_path)) {
            throw new Exception("OpenSSL not found at: $openssl_path");
        }
        if (!file_exists($input_csv)) {
            throw new Exception("Input file not found: $input_csv");
        }
        if (!file_exists($cert_pem)) {
            throw new Exception("Certificate not found: $cert_pem");
        }
        if (!file_exists($key_pem)) {
            throw new Exception("Private key not found: $key_pem");
        }

        // Build command EXACTLY like Python
        $cmd_parts = [
            '"' . $openssl_path . '"',
            'smime',
            '-sign',
            '-in',      '"' . realpath($input_csv) . '"',
            '-out',     '"' . $output_file . '"',
            '-signer',  '"' . realpath($cert_pem) . '"',
            '-inkey',   '"' . realpath($key_pem) . '"',
            '-outform', 'DER',
            '-nodetach',
            '-binary',
        ];

        $cmd = implode(' ', $cmd_parts);
        error_log("Signing command: $cmd");

        // Execute
        $output = [];
        $return_code = 0;
        exec($cmd . ' 2>&1', $output, $return_code);

        error_log("OpenSSL output: " . implode("\n", $output));

        if ($return_code !== 0) {
            throw new Exception("OpenSSL failed: " . implode("\n", $output));
        }

        if (!file_exists($output_file)) {
            throw new Exception("Signed file not created: $output_file");
        }
        
        $file_size = filesize($output_file);
        if ($file_size === 0) {
            throw new Exception("Signed file is empty");
        }

        error_log("Signed file created: $output_file ($file_size bytes)");
        
        return [
            'success' => true,
            'message' => 'File signed successfully'
        ];
        
    } catch (Exception $e) {
        error_log("Signing error: " . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
?>