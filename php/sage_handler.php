<?php
session_start();
include 'admin.php';

// Handle sage file upload
if (isset($_POST['upload_sage'])) {
    $response = array();
    
    try {
        // Validate required fields
        if (empty($_POST['target_file_type']) || empty($_POST['file_reference'])) {
            throw new Exception("All required fields must be filled");
        }
        
        if (!isset($_FILES['sage_file']) || $_FILES['sage_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Please select a valid Sage file");
        }
        
        // Check if user is logged in
        if (!isset($_SESSION["bt_user_id"])) {
            throw new Exception("You must be logged in to upload files");
        }
        
        // Validate file size (10MB max)
        if ($_FILES['sage_file']['size'] > 10 * 1024 * 1024) {
            throw new Exception("File size must be less than 10MB");
        }
        
        // Create sage_management instance
        $sage_mgmt = new sage_management();
        
        // Process the upload
        $result = $sage_mgmt->upload_sage_file(
            $_POST['target_file_type'],
            $_POST['file_reference'],
            $_FILES['sage_file']['tmp_name'],
            $_FILES['sage_file']['name'],
            $_POST['file_delimiter'] ?? ',',
            $_POST['has_headers'] ?? 1
        );
        
        if ($result['success']) {
            $response = array(
                'id' => 1,
                'mssg' => $result['message'],
                'sage_id' => $result['sage_id'],
                'redirect' => 'sage-mapping.php?id=' . $result['sage_id'] . '&target_type=' . $_POST['target_file_type'],
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

// Handle check reference
elseif (isset($_POST['check_reference'])) {
    $response = array();
    
    try {
        if (empty($_POST['file_reference'])) {
            throw new Exception("File reference is required");
        }
        
        $sage_mgmt = new sage_management();
        $exists = $sage_mgmt->check_reference_exists($_POST['file_reference']);
        
        $response = array(
            'exists' => $exists,
            'message' => $exists ? 'This reference already exists. Please use a unique reference.' : 'Reference is available.'
        );
        
    } catch (Exception $e) {
        $response = array(
            'exists' => false,
            'message' => 'Error checking reference: ' . $e->getMessage()
        );
    }
    
    echo json_encode($response);
    exit;
}

// Handle test mapping
elseif (isset($_POST['test_mapping'])) {
    $response = array();
    
    try {
        if (empty($_POST['sage_id']) || empty($_POST['field_id'])) {
            throw new Exception("Missing required parameters");
        }
        
        $sage_mgmt = new sage_management();
        
        // Get Sage data for the first record
        $preview = $sage_mgmt->get_sage_preview($_POST['sage_id'], 1);
        
        if (empty($preview)) {
            throw new Exception("No Sage data found");
        }
        
        $sage_columns = $preview[0]['fields'];
        $sage_column = $_POST['sage_column'] ?? null;
        $default_value = $_POST['default_value'] ?? '';
        
        // Determine the value
        if ($sage_column !== null && $sage_column !== '' && isset($sage_columns[$sage_column])) {
            $value = $sage_columns[$sage_column];
            $source = "Sage Column $sage_column";
        } else {
            $value = $default_value;
            $source = "Default Value";
        }
        
        // Get field info
        $conn = new db_connect();
        $db = $conn->connect();
        $stmt = $db->prepare("SELECT display_name FROM file_schema WHERE id = ?");
        $stmt->bind_param('i', $_POST['field_id']);
        $stmt->execute();
        $stmt->bind_result($display_name);
        $stmt->fetch();
        $stmt->close();
        
        $response = array(
            'id' => 1,
            'result' => "Field: <strong>$display_name</strong><br>Source: $source<br>Value: <code>$value</code>",
            'preview' => $value,
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

// Handle preview file
elseif (isset($_POST['preview_file'])) {
    $response = array();
    
    try {
        if (empty($_POST['sage_id'])) {
            throw new Exception("Sage ID is required");
        }
        
        $sage_mgmt = new sage_management();
        
        // Get all Sage data (limit to 5 for preview)
        $sage_data = $sage_mgmt->get_sage_preview($_POST['sage_id'], 5);
        
        if (empty($sage_data)) {
            throw new Exception("No Sage data found");
        }
        
        // Get file type to determine format
        $target_file_type = $_POST['target_file_type'] ?? 1;
        
        // Generate preview based on file type
        $preview_lines = [];
        
        // Header based on file type
        switch ($target_file_type) {
            case 1: // Payment File
                $preview_lines[] = "0;PREVIEW_" . date('d'); // Header line
                break;
            case 2: // Remittance
                $preview_lines[] = "0;RBM_" . date('d.m.Y') . ";0.00;0000";
                break;
            case 3: // Remittance with PRN
                $preview_lines[] = "0;LAG" . date('d') . "-" . date('d.m.Y') . ";0.00;0000";
                break;
            case 4: // Foreign Payment
                $preview_lines[] = "0;WTC01-" . date('d.m.Y') . ";USD;0;0000";
                break;
            case 5: // Salary Payment
                $preview_lines[] = "0;12SC;0.00;C;0013007800016;12SC2100537593;Test Salary;".date('d.m.Y').";0000";
                break;
        }
        
        // Process mappings from form data
        $mappings = array();
        if (isset($_POST['mapping']) && is_array($_POST['mapping'])) {
            foreach ($_POST['mapping'] as $field_id => $mapping_data) {
                $mappings[$field_id] = $mapping_data;
            }
        }
        
        // Generate body lines based on mappings
        foreach ($sage_data as $index => $row) {
            $line_fields = [];
            
            // Add line identifier based on file type
            switch ($target_file_type) {
                case 1: // Payment File
                case 4: // Foreign Payment
                case 5: // Salary Payment
                    $line_fields[] = "1"; // Line identifier
                    break;
                case 2: // Remittance
                case 3: // Remittance with PRN
                    $line_fields[] = "2"; // Line identifier
                    break;
            }
            
            // Add transaction serial
            $line_fields[] = str_pad($row['index'], 4, '0', STR_PAD_LEFT);
            
            // For now, just show placeholder values
            $line_fields[] = "SAMPLE_DATA_" . $row['index'];
            $line_fields[] = "Sample Value";
            $line_fields[] = "123.45";
            
            $preview_lines[] = implode(';', $line_fields);
        }
        
        $response = array(
            'id' => 1,
            'preview' => implode("\n", $preview_lines),
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

// Handle validate mappings
elseif (isset($_POST['validate_mappings'])) {
    $response = array();
    
    try {
        if (empty($_POST['sage_id'])) {
            throw new Exception("Sage ID is required");
        }
        
        $sage_mgmt = new sage_management();
        
        // Get required fields for the file type
        $target_file_type = $_POST['target_file_type'] ?? 1;
        $schema = $sage_mgmt->get_file_schema($target_file_type);
        
        $validation_errors = [];
        $mappings = array();
        
        // Check if mappings are provided
        if (isset($_POST['mapping']) && is_array($_POST['mapping'])) {
            foreach ($_POST['mapping'] as $field_id => $mapping_data) {
                $mappings[$field_id] = $mapping_data;
            }
        }
        
        // Validate each required field
        foreach ($schema as $field) {
            if ($field['mandatory']) {
                $field_id = $field['id'];
                
                if (!isset($mappings[$field_id]) || 
                    (empty($mappings[$field_id]['sage_column']) && empty($mappings[$field_id]['default_value']))) {
                    $validation_errors[] = "Required field '{$field['display_name']}' is not mapped and has no default value";
                }
            }
        }
        
        if (empty($validation_errors)) {
            $response = array(
                'id' => 1,
                'mssg' => 'All required fields are properly mapped.',
                'validation_results' => 'OK',
                'type' => 'success'
            );
        } else {
            $response = array(
                'id' => 0,
                'mssg' => 'Validation failed. Please fix the errors below.',
                'validation_errors' => $validation_errors,
                'type' => 'error'
            );
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

// Handle generate file
elseif (isset($_POST['generate_file'])) {
    $response = array();
    
    try {
        if (empty($_POST['sage_id'])) {
            throw new Exception("Sage ID is required");
        }
        
        $conn = new db_connect();
        $db = $conn->connect();
        $user_id = $_SESSION["bt_user_id"];
        
        // Get Sage upload details
        $sage_stmt = $db->prepare("SELECT * FROM sage_uploads WHERE id = ?");
        $sage_stmt->bind_param('i', $_POST['sage_id']);
        $sage_stmt->execute();
        $sage_result = $sage_stmt->get_result();
        $sage_upload = $sage_result->fetch_assoc();
        $sage_stmt->close();
        
        if (!$sage_upload) {
            throw new Exception("Sage upload not found");
        }
        
        // Get Sage data
        $data_stmt = $db->prepare("SELECT record_index, data_json FROM sage_data WHERE sage_upload_id = ? ORDER BY record_index");
        $data_stmt->bind_param('i', $_POST['sage_id']);
        $data_stmt->execute();
        $data_result = $data_stmt->get_result();
        
        $sage_records = [];
        while ($row = $data_result->fetch_assoc()) {
            $sage_records[] = [
                'index' => $row['record_index'],
                'fields' => json_decode($row['data_json'], true)
            ];
        }
        $data_stmt->close();
        
        // Get file type details
        $target_file_type = $_POST['target_file_type'] ?? 1;
        $type_stmt = $db->prepare("SELECT code, name, delimiter FROM file_types WHERE id = ?");
        $type_stmt->bind_param('i', $target_file_type);
        $type_stmt->execute();
        $type_result = $type_stmt->get_result();
        $file_type = $type_result->fetch_assoc();
        $type_stmt->close();
        
        // Get field schema
        $schema_stmt = $db->prepare("SELECT id, field_name, mandatory FROM file_schema WHERE file_type_id = ? ORDER BY field_order");
        $schema_stmt->bind_param('i', $target_file_type);
        $schema_stmt->execute();
        $schema_result = $schema_stmt->get_result();
        
        $schema_fields = [];
        while ($row = $schema_result->fetch_assoc()) {
            $schema_fields[] = $row;
        }
        $schema_stmt->close();
        
        // Process mappings
        $mappings = [];
        if (isset($_POST['mapping']) && is_array($_POST['mapping'])) {
            foreach ($_POST['mapping'] as $field_id => $mapping_data) {
                $mappings[$field_id] = $mapping_data;
            }
        }
        
        // Start transaction
        $db->begin_transaction();
        
        // Generate batch number
        $batch_number = $file_type['code'] . '-SAGE-' . date('Ymd') . '-' . rand(1000, 9999);
        
        // Calculate totals
        $total_amount = 0;
        $total_count = count($sage_records);
        
        // Create file content based on file type
        $file_lines = [];
        $delimiter = $file_type['delimiter'] ?? ';';
        
        // Add header based on file type
        switch ($target_file_type) {
            case 1: // Payment File
                $file_lines[] = "0;SAGE_" . date('d') . ";MWK;" . number_format($total_amount, 2) . ";" . str_pad($total_count, 4, '0', STR_PAD_LEFT);
                break;
            case 2: // Remittance
                $file_lines[] = "0;RBM_" . date('d.m.Y') . ";" . number_format($total_amount, 2) . ";" . str_pad($total_count, 4, '0', STR_PAD_LEFT);
                break;
            case 3: // Remittance with PRN
                $file_lines[] = "0;LAG" . date('d') . "-" . date('d.m.Y') . ";" . number_format($total_amount, 2) . ";" . str_pad($total_count, 4, '0', STR_PAD_LEFT);
                break;
            case 4: // Foreign Payment
                $file_lines[] = "0;SAGE-" . date('d.m.Y') . ";USD;" . number_format($total_amount, 2) . ";" . str_pad($total_count, 4, '0', STR_PAD_LEFT);
                break;
            case 5: // Salary Payment
                $file_lines[] = "0;12SC;" . number_format($total_amount, 2) . ";C;0013007800016;12SC" . date('Y') . "0537593;Sage Converted Salary;" . date('d.m.Y') . ";" . str_pad($total_count, 4, '0', STR_PAD_LEFT);
                break;
        }
        
        // Process each Sage record
        foreach ($sage_records as $record) {
            $line_fields = [];
            $sage_columns = $record['fields'];
            
            // Add line identifier
            switch ($target_file_type) {
                case 1: case 4: case 5:
                    $line_fields[] = "1";
                    break;
                case 2: case 3:
                    $line_fields[] = "2";
                    break;
            }
            
            // Add transaction serial
            $line_fields[] = str_pad($record['index'], 4, '0', STR_PAD_LEFT);
            
            // Add mapped fields
            foreach ($schema_fields as $field) {
                $field_id = $field['id'];
                $sage_column = $mappings[$field_id]['sage_column'] ?? null;
                $default_value = $mappings[$field_id]['default_value'] ?? '';
                
                if ($sage_column !== null && $sage_column !== '' && isset($sage_columns[$sage_column])) {
                    $value = $sage_columns[$sage_column];
                } else {
                    $value = $default_value;
                }
                
                // Format based on field type
                if ($field['field_name'] == 'payment_amount' || $field['field_name'] == 'amount') {
                    $value = number_format(floatval($value), 2);
                    $total_amount += floatval($value);
                }
                
                $line_fields[] = $value;
            }
            
            $file_lines[] = implode($delimiter, $line_fields);
        }
        
        // Update header with actual total
        $file_lines[0] = str_replace(
            number_format(0, 2), 
            number_format($total_amount, 2), 
            $file_lines[0]
        );
        
        $file_content = implode("\n", $file_lines);
        
        // Create batch record
        $batch_stmt = $db->prepare("
            INSERT INTO file_batches (batch_number, file_type_id, reference_no, 
                                    currency_code, total_amount, total_count, 
                                    status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, 'generated', ?)
        ");
        
        $reference_no = 'SAGE_' . date('Ymd_His');
        $currency_code = ($target_file_type == 4) ? 'USD' : 'MWK';
        
        $batch_stmt->bind_param('sissdii', $batch_number, $target_file_type, $reference_no,
                              $currency_code, $total_amount, $total_count, $user_id);
        
        if (!$batch_stmt->execute()) {
            throw new Exception("Failed to create batch: " . $batch_stmt->error);
        }
        
        $batch_id = $db->insert_id;
        $batch_stmt->close();
        
        // Save generated file
        $generated_dir = '../generated_files/';
        if (!file_exists($generated_dir)) {
            mkdir($generated_dir, 0777, true);
        }
        
        $filename = $batch_number . '.csv';
        $filepath = $generated_dir . $filename;
        
        if (file_put_contents($filepath, $file_content) === false) {
            throw new Exception("Failed to write generated file");
        }
        
        // Save to generated_files table
        $gen_stmt = $db->prepare("
            INSERT INTO generated_files (batch_id, file_name, file_path, file_size, checksum, generated_by)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $file_size = filesize($filepath);
        $checksum = md5_file($filepath);
        
        $gen_stmt->bind_param('issisi', $batch_id, $filename, $filepath, $file_size, $checksum, $user_id);
        
        if (!$gen_stmt->execute()) {
            throw new Exception("Failed to save generated file record");
        }
        
        $file_id = $db->insert_id;
        $gen_stmt->close();
        
        // Update Sage upload status
        $update_stmt = $db->prepare("UPDATE sage_uploads SET status = 'processed' WHERE id = ?");
        $update_stmt->bind_param('i', $_POST['sage_id']);
        $update_stmt->execute();
        $update_stmt->close();
        
        $db->commit();
        
        // Log audit trail
        $log = new general();
        $log->logAuditTrail('file_batches', $batch_id, 'INSERT', $user_id, 
                          "Sage file converted to OBDX format. Batch: $batch_number");
        
        $response = array(
            'id' => 1,
            'mssg' => "File generated successfully. $total_count records processed.",
            'batch_id' => $batch_id,
            'file_id' => $file_id, // Add this line
            'download_url' => './php/file_handler.php?action=download_generated&id=' . $file_id, // Change this line
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
header('HTTP/1.0 400 Bad Request');
echo json_encode(array(
    'id' => 0,
    'mssg' => 'Invalid request',
    'type' => 'error'
));
?>