<?php
session_start();
include 'admin.php';

// Handle audit log export
if (isset($_GET['action']) && $_GET['action'] == 'export_logs') {
    $conn = (new db_connect())->connect();
    
    // Fetch audit logs
    $sql = "
        SELECT 
            al.id,
            al.user_id,
            al.action,
            al.entity_type,
            al.entity_id,
            al.details,
            al.ip_address,
            al.user_agent,
            al.created_at,
            u.username
        FROM audit_log al
        LEFT JOIN users u ON al.user_id = u.user_id
        ORDER BY al.created_at DESC
    ";
    
    $result = $conn->query($sql);
    
    // Generate CSV
    $filename = 'audit_logs_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, ['ID', 'User ID', 'Username', 'Action', 'Entity Type', 'Entity ID', 'Details', 'IP Address', 'User Agent', 'Timestamp']);
    
    // Write data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

// Handle audit trail export
elseif (isset($_GET['action']) && $_GET['action'] == 'export_trail') {
    $conn = (new db_connect())->connect();
    
    // Fetch audit trail
    $sql = "
        SELECT 
            at.audit_id,
            at.table_name,
            at.record_id,
            at.action_type,
            at.user_id,
            at.details,
            at.timestamp,
            u.username
        FROM audit_trail at
        LEFT JOIN users u ON at.user_id = u.user_id
        ORDER BY at.timestamp DESC
    ";
    
    $result = $conn->query($sql);
    
    // Generate CSV
    $filename = 'audit_trail_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, ['Audit ID', 'Table Name', 'Record ID', 'Action Type', 'User ID', 'Username', 'Details', 'Timestamp']);
    
    // Write data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['audit_id'],
            $row['table_name'],
            $row['record_id'],
            $row['action_type'],
            $row['user_id'],
            $row['username'],
            $row['details'],
            $row['timestamp']
        ]);
    }
    
    fclose($output);
    exit;
}