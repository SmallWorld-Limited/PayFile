<?php
session_start();
include 'admin.php';

// Handle table export
if (isset($_GET['action']) && $_GET['action'] == 'export_table') {
    $table = $_GET['table'] ?? '';
    $format = $_GET['format'] ?? 'csv';
    $date_from = $_GET['date_from'] ?? null;
    $date_to = $_GET['date_to'] ?? null;
    
    $export = new export_manager();
    $result = $export->export_table($table, $format, $date_from, $date_to);
    
    if ($result['success'] && file_exists($result['file_path'])) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
        readfile($result['file_path']);
        exit;
    } else {
        echo "Export failed: " . ($result['message'] ?? 'Unknown error');
        exit;
    }
}

// Handle custom export
elseif (isset($_GET['action']) && $_GET['action'] == 'export_custom') {
    $table = $_GET['table'] ?? '';
    $format = $_GET['format'] ?? 'csv';
    $date_from = $_GET['date_from'] ?? null;
    $date_to = $_GET['date_to'] ?? null;
    
    $export = new export_manager();
    $result = $export->export_table($table, $format, $date_from, $date_to);
    
    if ($result['success'] && file_exists($result['file_path'])) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
        readfile($result['file_path']);
        exit;
    } else {
        echo "Export failed: " . ($result['message'] ?? 'Unknown error');
        exit;
    }
}

// Handle full backup
elseif (isset($_GET['action']) && $_GET['action'] == 'full_backup') {
    $export = new export_manager();
    
    // List of tables to export
    $tables = [
        'file_batches', 'uploaded_files', 'signed_files', 
        'certificates', 'users', 'audit_log', 'audit_trail',
        'batch_items', 'file_types', 'validation_rules',
        'system_config', 'workflow_status'
    ];
    
    // Create ZIP file
    $zip_filename = 'system_backup_' . date('Ymd_His') . '.zip';
    $zip_filepath = '../exports/' . $zip_filename;
    
    if (!file_exists('../exports/')) {
        mkdir('../exports/', 0777, true);
    }
    
    $zip = new ZipArchive();
    if ($zip->open($zip_filepath, ZipArchive::CREATE) === TRUE) {
        foreach ($tables as $table) {
            $result = $export->export_table($table, 'csv');
            if ($result['success'] && file_exists($result['file_path'])) {
                $zip->addFile($result['file_path'], basename($result['file_path']));
            }
        }
        
        $zip->close();
        
        // Add database schema
        $schema_file = '../exports/database_schema_' . date('Ymd_His') . '.sql';
        $this->export_database_schema($schema_file);
        if (file_exists($schema_file)) {
            $zip->open($zip_filepath);
            $zip->addFile($schema_file, 'database_schema.sql');
            $zip->close();
            unlink($schema_file);
        }
        
        // Download the ZIP
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
        readfile($zip_filepath);
        
        // Clean up
        unlink($zip_filepath);
        exit;
    }
}

// Handle download
elseif (isset($_GET['action']) && $_GET['action'] == 'download') {
    $export_id = $_GET['id'] ?? 0;
    
    $conn = (new db_connect())->connect();
    $sql = "SELECT file_path, format FROM exports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $export_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc() && file_exists($row['file_path'])) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="export_' . $export_id . '.' . $row['format'] . '"');
        readfile($row['file_path']);
        exit;
    }
}

// Handle report download
elseif (isset($_GET['action']) && $_GET['action'] == 'download_report') {
    $filename = $_GET['file'] ?? '';
    $filepath = '../exports/' . $filename;
    
    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filepath);
        exit;
    }
}

// Helper function to export database schema
private function export_database_schema($filename) {
    $conn = (new db_connect())->connect();
    
    // Get all table names
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    
    $output = "-- Database Schema Export\n";
    $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $output .= "-- \n\n";
    
    foreach ($tables as $table) {
        // Get table structure
        $result = $conn->query("SHOW CREATE TABLE `$table`");
        if ($row = $result->fetch_assoc()) {
            $output .= $row['Create Table'] . ";\n\n";
        }
    }
    
    file_put_contents($filename, $output);
}