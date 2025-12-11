<?php
session_start();
include 'admin.php';

// Handle report generation
if (isset($_POST['action']) && $_POST['action'] == 'generate_report') {
    $response = [];
    
    try {
        $report_type = $_POST['report_type'] ?? '';
        $date_from = $_POST['date_from'] ?? null;
        $date_to = $_POST['date_to'] ?? null;
        $format = $_POST['format'] ?? 'html';
        
        $reports = new reports_manager();
        $report_data = $reports->generate_report($report_type, $date_from, $date_to);
        
        if ($format == 'csv') {
            // Generate CSV
            $filename = $report_type . '_' . date('Ymd_His') . '.csv';
            $filepath = '../exports/' . $filename;
            
            if (!file_exists('../exports/')) {
                mkdir('../exports/', 0777, true);
            }
            
            $fp = fopen($filepath, 'w');
            
            if (!empty($report_data)) {
                // Write headers
                fputcsv($fp, array_keys($report_data[0]));
                
                // Write data
                foreach ($report_data as $row) {
                    fputcsv($fp, $row);
                }
            }
            
            fclose($fp);
            
            $response = [
                'success' => true,
                'message' => 'Report generated successfully',
                'download_url' => './export_handler.php?action=download_report&file=' . $filename
            ];
        } else {
            // For HTML/PDF, we'd return the data for display
            $response = [
                'success' => true,
                'message' => 'Report data retrieved',
                'data' => $report_data,
                'format' => $format
            ];
        }
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Error generating report: ' . $e->getMessage()
        ];
    }
    
    echo json_encode($response);
    exit;
}