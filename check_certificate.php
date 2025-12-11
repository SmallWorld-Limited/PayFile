<?php
echo "<h2>Certificate Validation Check</h2>";

$cert_file = 'cert.pem';
$key_file = 'key.pem';

if (file_exists($cert_file) && file_exists($key_file)) {
    
    echo "<h3>1. Certificate Details:</h3>";
    
    // Read certificate
    $cert_data = file_get_contents($cert_file);
    $cert_info = openssl_x509_parse($cert_data);
    
    if ($cert_info) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        
        // Check critical fields for banking
        echo "<tr><td><strong>Subject</strong></td><td>";
        if (isset($cert_info['subject'])) {
            foreach ($cert_info['subject'] as $key => $value) {
                echo "$key: $value<br>";
            }
        }
        echo "</td></tr>";
        
        echo "<tr><td><strong>Issuer</strong></td><td>";
        if (isset($cert_info['issuer'])) {
            foreach ($cert_info['issuer'] as $key => $value) {
                echo "$key: $value<br>";
            }
        }
        echo "</td></tr>";
        
        echo "<tr><td><strong>Serial Number</strong></td><td>" . ($cert_info['serialNumber'] ?? 'N/A') . "</td></tr>";
        
        echo "<tr><td><strong>Valid From</strong></td><td>" . 
             date('Y-m-d H:i:s', $cert_info['validFrom_time_t']) . "</td></tr>";
        
        echo "<tr><td><strong>Valid To</strong></td><td>" . 
             date('Y-m-d H:i:s', $cert_info['validTo_time_t']) . "</td></tr>";
        
        // Check extensions (Template ID might be here)
        echo "<tr><td><strong>Extensions</strong></td><td>";
        if (isset($cert_info['extensions'])) {
            foreach ($cert_info['extensions'] as $key => $value) {
                echo "<strong>$key:</strong> " . htmlspecialchars($value) . "<br>";
                
                // Check for Template ID
                if (strpos($key, 'template') !== false || strpos(strtolower($value), 'template') !== false) {
                    echo "<span style='color:red; font-weight:bold;'>⚠️ Template ID found in extension</span><br>";
                }
            }
        }
        echo "</td></tr>";
        
        echo "</table>";
        
        // Check for specific banking certificate requirements
        echo "<h3>2. Banking Certificate Requirements:</h3>";
        
        $requirements = [
            'Certificate Type' => 'Should be a valid signing certificate',
            'Template ID' => 'Must match bank\'s expected template',
            'Key Usage' => 'Should include digitalSignature',
            'Extended Key Usage' => 'Should include emailProtection or codeSigning',
        ];
        
        $issues = [];
        
        // Check key usage
        if (isset($cert_info['extensions']['keyUsage'])) {
            $keyUsage = $cert_info['extensions']['keyUsage'];
            if (strpos($keyUsage, 'Digital Signature') === false) {
                $issues[] = "Key Usage missing Digital Signature";
            }
        }
        
        // Check extended key usage
        if (isset($cert_info['extensions']['extendedKeyUsage'])) {
            $extKeyUsage = $cert_info['extensions']['extendedKeyUsage'];
            if (strpos($extKeyUsage, 'Email Protection') === false && 
                strpos($extKeyUsage, 'Code Signing') === false) {
                $issues[] = "Extended Key Usage may not include required purposes";
            }
        }
        
        if (empty($issues)) {
            echo "<p style='color:green'>✓ Basic certificate requirements met</p>";
        } else {
            echo "<p style='color:orange'>⚠️ Potential issues:</p>";
            echo "<ul>";
            foreach ($issues as $issue) {
                echo "<li>$issue</li>";
            }
            echo "</ul>";
        }
        
    } else {
        echo "<p style='color:red'>✗ Could not parse certificate</p>";
    }
    
    echo "<h3>3. Test Signing with Current Certificate:</h3>";
    
    // Create test file
    $test_csv = "test_bank.csv";
    $csv_content = "0;BANK_TEST;MWK;100.00;1\n1;0001;MWK;1234567890;Test Account;100.00;Test Bank";
    file_put_contents($test_csv, $csv_content);
    
    echo "<p>Test file created: $test_csv</p>";
    
    // Test Python-style signing
    $output_file = "test_bank_signed.csv";
    $OPENSSL = 'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe';
    
    if (!file_exists($OPENSSL)) {
        $OPENSSL = 'openssl';
    }
    
    $cmd = '"' . $OPENSSL . '" smime -sign';
    $cmd .= ' -in "' . $test_csv . '"';
    $cmd .= ' -out "' . $output_file . '"';
    $cmd .= ' -signer "' . $cert_file . '"';
    $cmd .= ' -inkey "' . $key_file . '"';
    $cmd .= ' -outform DER -nodetach -binary';
    
    echo "<p>Command: <code>" . htmlspecialchars($cmd) . "</code></p>";
    
    $output = [];
    $return_code = 0;
    exec($cmd . ' 2>&1', $output, $return_code);
    
    if ($return_code === 0 && file_exists($output_file)) {
        echo "<p style='color:green'>✓ Test signing successful: $output_file (" . filesize($output_file) . " bytes)</p>";
        
        // Show file signature
        echo "<h4>Signed File Header (first 50 bytes in hex):</h4>";
        $content = file_get_contents($output_file);
        echo "<pre>";
        for ($i = 0; $i < min(50, strlen($content)); $i++) {
            printf("%02X ", ord($content[$i]));
        }
        echo "</pre>";
        
        // Clean up
        unlink($test_csv);
        unlink($output_file);
        
    } else {
        echo "<p style='color:red'>✗ Test signing failed:</p>";
        echo "<pre>" . implode("\n", $output) . "</pre>";
        @unlink($test_csv);
    }
    
} else {
    echo "<p style='color:red'>Certificate files not found in current directory.</p>";
    echo "<p>Looking for:</p>";
    echo "<ul>";
    echo "<li>cert.pem: " . (file_exists('cert.pem') ? 'Found ✓' : 'Not found ✗') . "</li>";
    echo "<li>key.pem: " . (file_exists('key.pem') ? 'Found ✓' : 'Not found ✗') . "</li>";
    echo "</ul>";
    
    // Check in parent directory
    $parent_cert = '../cert.pem';
    $parent_key = '../key.pem';
    
    if (file_exists($parent_cert) && file_exists($parent_key)) {
        echo "<p>Found certificates in parent directory. You should copy them to this directory.</p>";
    }
}

// Form to check certificate from upload
echo "<h3>4. Check Your Certificate File:</h3>";
echo '<form method="POST" enctype="multipart/form-data">
    <p>Upload your certificate file to check its details:</p>
    <input type="file" name="cert_file" accept=".pem,.crt,.cer">
    <button type="submit" name="check_cert">Check Certificate</button>
</form>';

if (isset($_POST['check_cert']) && isset($_FILES['cert_file'])) {
    $tmp_file = $_FILES['cert_file']['tmp_name'];
    $cert_data = file_get_contents($tmp_file);
    $cert_info = openssl_x509_parse($cert_data);
    
    if ($cert_info) {
        echo "<h4>Uploaded Certificate Analysis:</h4>";
        echo "<pre>";
        print_r($cert_info);
        echo "</pre>";
    } else {
        echo "<p style='color:red'>Could not parse uploaded certificate</p>";
    }
}
?>