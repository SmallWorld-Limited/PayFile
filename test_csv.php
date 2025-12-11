<?php
session_start();

echo "<!DOCTYPE html>
<html>
<head>
    <title>CSV File Validator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        pre { background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .form-group { margin-bottom: 15px; }
        .form-control { padding: 8px; width: 300px; }
    </style>
</head>
<body>
<div class='container'>
<h2>CSV File Validator</h2>
<p>Upload your CSV file to check if it matches bank requirements</p>";

// Helper functions
function detect_delimiter($content) {
    $delimiters = [';' => 0, ',' => 0, "\t" => 0];
    $first_line = strtok($content, "\n");
    
    foreach ($delimiters as $delimiter => &$count) {
        $count = substr_count($first_line, $delimiter);
    }
    
    arsort($delimiters);
    return key($delimiters);
}

function array_to_csv($fields, $delimiter) {
    $output = '';
    foreach ($fields as $field) {
        if ($output !== '') {
            $output .= $delimiter;
        }
        if (strpos($field, $delimiter) !== false || strpos($field, '"') !== false || strpos($field, ' ') !== false) {
            $output .= '"' . str_replace('"', '""', $field) . '"';
        } else {
            $output .= $field;
        }
    }
    return $output;
}

function fix_csv_file($content, $delimiter) {
    // Remove BOM
    if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
        $content = substr($content, 3);
    }
    
    // Normalize line endings
    $content = str_replace(["\r\n", "\r"], "\n", $content);
    
    $lines = explode("\n", trim($content));
    $fixed_lines = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $fields = str_getcsv($line, $delimiter);
            
            // Clean fields
            $fields = array_map(function($field) {
                $field = trim($field, " \t\n\r\0\x0B\"'");
                if (is_numeric($field)) {
                    $field = str_replace(',', '', $field);
                    if (strpos($field, '.') !== false) {
                        $field = number_format(floatval($field), 2, '.', '');
                    }
                }
                return $field;
            }, $fields);
            
            $fixed_lines[] = array_to_csv($fields, $delimiter);
        }
    }
    
    return implode("\n", $fixed_lines);
}

function validate_payment_line($fields, $line_number) {
    $errors = [];
    
    // Field 0: Should be '1' for data lines (or '0' for header)
    if (isset($fields[0])) {
        $first_field = trim($fields[0]);
        if ($first_field !== '0' && $first_field !== '1') {
            $errors[] = "First field must be '0' (header) or '1' (data line)";
        }
    }
    
    // Field 2: Currency code (3 characters)
    if (isset($fields[2])) {
        $currency = trim($fields[2]);
        if (strlen($currency) !== 3) {
            $errors[] = "Currency code must be 3 characters (e.g., 'MWK', 'USD')";
        }
    }
    
    // Field 5: Payment amount (numeric)
    if (isset($fields[5]) && $fields[5] !== '') {
        $amount = str_replace(',', '', $fields[5]);
        if (!is_numeric($amount)) {
            $errors[] = "Payment amount must be numeric";
        }
    }
    
    // Field 11: BIC code (8 or 11 characters if provided)
    if (isset($fields[11]) && trim($fields[11]) !== '') {
        $bic = trim($fields[11]);
        if (strlen($bic) !== 8 && strlen($bic) !== 11) {
            $errors[] = "BIC code must be 8 or 11 characters";
        }
    }
    
    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file_tmp_path = $_FILES['csv_file']['tmp_name'];
    $original_name = $_FILES['csv_file']['name'];
    
    echo "<h3>Analysis of: " . htmlspecialchars($original_name) . "</h3>";
    
    if (!file_exists($file_tmp_path)) {
        echo "<p class='error'>File upload failed</p>";
    } else {
        $content = file_get_contents($file_tmp_path);
        
        echo "<h4>1. Basic File Info:</h4>";
        echo "<p>File size: " . filesize($file_tmp_path) . " bytes</p>";
        echo "<p>First 500 characters:</p>";
        echo "<pre>" . htmlspecialchars(substr($content, 0, 500)) . "</pre>";
        
        echo "<h4>2. Line Analysis:</h4>";
        $lines = explode("\n", trim($content));
        echo "<p>Total lines: " . count($lines) . "</p>";
        
        // Detect delimiter
        $delimiter = detect_delimiter($content);
        echo "<p>Detected delimiter: '" . htmlspecialchars($delimiter) . "'</p>";
        
        echo "<h4>3. First 3 Lines Detailed:</h4>";
        for ($i = 0; $i < min(3, count($lines)); $i++) {
            echo "<p><strong>Line " . ($i + 1) . ":</strong></p>";
            $fields = str_getcsv($lines[$i], $delimiter);
            echo "<p>Field count: " . count($fields) . "</p>";
            echo "<table>";
            echo "<tr><th>Index</th><th>Field</th><th>Length</th><th>Type</th></tr>";
            foreach ($fields as $index => $field) {
                echo "<tr>";
                echo "<td>" . $index . "</td>";
                echo "<td><pre>" . htmlspecialchars($field) . "</pre></td>";
                echo "<td>" . strlen($field) . "</td>";
                echo "<td>";
                if (is_numeric($field)) {
                    echo "Number";
                } elseif (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $field)) {
                    echo "Date (DD.MM.YYYY)";
                } elseif (preg_match('/^[A-Z]{3}$/', $field)) {
                    echo "Currency Code";
                } elseif (preg_match('/^[A-Z0-9]{8,11}$/', $field)) {
                    echo "BIC/SWIFT Code";
                } else {
                    echo "String";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<h4>4. Validation Check:</h4>";
        
        // Validate each line
        $all_errors = [];
        for ($i = 0; $i < count($lines); $i++) {
            $fields = str_getcsv($lines[$i], $delimiter);
            $errors = validate_payment_line($fields, $i + 1);
            
            if (!empty($errors)) {
                $all_errors[] = "<strong>Line " . ($i + 1) . ":</strong> " . implode(", ", $errors);
            }
        }
        
        if (empty($all_errors)) {
            echo "<p class='success'>✓ No validation errors found!</p>";
        } else {
            echo "<p class='error'>Validation Errors:</p>";
            echo "<ul>";
            foreach ($all_errors as $error) {
                echo "<li class='error'>" . $error . "</li>";
            }
            echo "</ul>";
        }
        
        echo "<h4>5. Common Issues Check:</h4>";
        $issues = [];
        
        // Check for BOM
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $issues[] = "Has UTF-8 BOM (may cause issues)";
        }
        
        // Check line endings
        if (strpos($content, "\r\n") !== false) {
            $issues[] = "Windows line endings (CRLF)";
        } elseif (strpos($content, "\r") !== false) {
            $issues[] = "Mac line endings (CR)";
        } else {
            $issues[] = "Unix line endings (LF)";
        }
        
        // Check if first line is header
        $first_fields = str_getcsv($lines[0], $delimiter);
        if (isset($first_fields[0]) && $first_fields[0] === '0') {
            $issues[] = "First line appears to be header (starts with '0')";
        } elseif (isset($first_fields[0]) && $first_fields[0] === '1') {
            $issues[] = "First line appears to be data (starts with '1') - missing header?";
        }
        
        // Check field counts consistency
        $field_counts = [];
        foreach ($lines as $i => $line) {
            $fields = str_getcsv($line, $delimiter);
            $field_counts[] = count($fields);
        }
        
        $unique_counts = array_unique($field_counts);
        if (count($unique_counts) === 1) {
            $issues[] = "All lines have same field count: " . $unique_counts[0];
        } else {
            $issues[] = "Inconsistent field counts: " . implode(", ", array_unique($field_counts));
        }
        
        // Display issues
        echo "<ul>";
        foreach ($issues as $issue) {
            if (strpos($issue, 'missing header') !== false || strpos($issue, 'Inconsistent') !== false) {
                echo "<li class='error'>✗ " . $issue . "</li>";
            } else {
                echo "<li class='success'>✓ " . $issue . "</li>";
            }
        }
        echo "</ul>";
        
        echo "<h4>6. Sample Fixed Output:</h4>";
        
        // Try to fix the CSV
        $fixed_content = fix_csv_file($content, $delimiter);
        echo "<p>Fixed version (first 3 lines):</p>";
        $fixed_lines = explode("\n", trim($fixed_content));
        echo "<pre>";
        for ($i = 0; $i < min(3, count($fixed_lines)); $i++) {
            echo htmlspecialchars($fixed_lines[$i]) . "\n";
        }
        echo "</pre>";
        
        // Offer download of fixed version
        $fixed_filename = 'fixed_' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $original_name);
        file_put_contents($fixed_filename, $fixed_content);
        echo "<p><a href='$fixed_filename' download class='btn'>Download Fixed CSV</a></p>";
        
        // Clean up
        @unlink($fixed_filename);
    }
}

// Upload form
echo '<form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
    <div class="form-group">
        <label>Select CSV File:</label><br>
        <input type="file" name="csv_file" accept=".csv,.txt" class="form-control" required>
    </div>
    <button type="submit" class="btn">Validate CSV</button>
</form>';

echo "</div></body></html>";
?>