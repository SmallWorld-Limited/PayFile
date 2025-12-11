<?php
// Simple test script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Signing System Test</h2>";

// Test 1: Check if OpenSSL is installed
echo "<h3>1. Checking OpenSSL Installation:</h3>";
$openssl_paths = [
    'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe',
    'C:\\Program Files\\OpenSSL\\bin\\openssl.exe',
    'openssl'
];

foreach ($openssl_paths as $path) {
    $output = [];
    $return_code = 0;
    exec('"' . $path . '" version 2>&1', $output, $return_code);
    
    if ($return_code === 0) {
        echo "<p style='color:green'>✓ OpenSSL found at: $path</p>";
        echo "<pre>" . implode("\n", $output) . "</pre>";
        $working_path = $path;
        break;
    } else {
        echo "<p style='color:orange'>✗ OpenSSL not found at: $path</p>";
    }
}

// Test 2: Check certificate and key files
echo "<h3>2. Checking Certificate Files:</h3>";
$cert_files = ['C:\Projects\Yankho\cert.pem', 'C:\Projects\Yankho\key.pem', './cert.pem', './key.pem'];

foreach ($cert_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color:green'>✓ File found: $file (" . filesize($file) . " bytes)</p>";
        
        // Check if it's a valid PEM file
        $content = file_get_contents($file);
        if (strpos($content, '-----BEGIN') !== false) {
            echo "<p style='color:green'>  ✓ Valid PEM format detected</p>";
            
            if (strpos($content, 'CERTIFICATE') !== false) {
                echo "<p style='color:green'>  ✓ This is a certificate file</p>";
                
                // Try to parse certificate
                $cert_info = openssl_x509_parse($content);
                if ($cert_info) {
                    echo "<p style='color:green'>  ✓ Certificate parsed successfully</p>";
                    echo "<pre>Subject: " . print_r($cert_info['subject'], true) . "</pre>";
                    echo "<pre>Valid From: " . date('Y-m-d H:i:s', $cert_info['validFrom_time_t']) . "</pre>";
                    echo "<pre>Valid To: " . date('Y-m-d H:i:s', $cert_info['validTo_time_t']) . "</pre>";
                }
            } elseif (strpos($content, 'PRIVATE KEY') !== false) {
                echo "<p style='color:green'>  ✓ This is a private key file</p>";
            }
        }
    } else {
        echo "<p style='color:red'>✗ File not found: $file</p>";
    }
}

// Test 3: Test OpenSSL command directly
echo "<h3>3. Testing OpenSSL Command:</h3>";
if (isset($working_path)) {
    // Create a test CSV file
    $test_csv = "test.csv";
    file_put_contents($test_csv, "0;TEST;MWK;100.00;1\n1;0001;MWK;123456;Test Account;100.00;Test Payee");
    
    $cert_file = file_exists('cert.pem') ? 'cert.pem' : (file_exists('../cert.pem') ? '../cert.pem' : null);
    $key_file = file_exists('key.pem') ? 'key.pem' : (file_exists('../key.pem') ? '../key.pem' : null);
    
    if ($cert_file && $key_file) {
        $output_file = "test_signed.p7s";
        $cmd = '"' . $working_path . '" smime -sign';
        $cmd .= ' -in "' . $test_csv . '"';
        $cmd .= ' -out "' . $output_file . '"';
        $cmd .= ' -signer "' . $cert_file . '"';
        $cmd .= ' -inkey "' . $key_file . '"';
        $cmd .= ' -outform DER -nodetach -binary 2>&1';
        
        echo "<p>Command: <code>" . htmlspecialchars($cmd) . "</code></p>";
        
        $output = [];
        $return_code = 0;
        exec($cmd, $output, $return_code);
        
        echo "<p>Return Code: $return_code</p>";
        echo "<pre>Output: " . implode("\n", $output) . "</pre>";
        
        if (file_exists($output_file)) {
            echo "<p style='color:green'>✓ Signed file created: $output_file (" . filesize($output_file) . " bytes)</p>";
            unlink($output_file);
        } else {
            echo "<p style='color:red'>✗ Signed file was not created</p>";
        }
        
        unlink($test_csv);
    } else {
        echo "<p style='color:red'>✗ Certificate or key file not found for testing</p>";
    }
}

// Test 4: Check PHP configuration
echo "<h3>4. Checking PHP Configuration:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>OpenSSL Extension: " . (extension_loaded('openssl') ? 'Enabled ✓' : 'Disabled ✗') . "</p>";
echo "<p>Safe Mode: " . (ini_get('safe_mode') ? 'On ✗' : 'Off ✓') . "</p>";
echo "<p>exec() function: " . (function_exists('exec') ? 'Enabled ✓' : 'Disabled ✗') . "</p>";

// Test 5: Check directory permissions
echo "<h3>5. Checking Directory Permissions:</h3>";
$directories = ['.', './uploads', './signed_files', './php'];
foreach ($directories as $dir) {
    if (is_dir($dir) || mkdir($dir, 0777, true)) {
        $writable = is_writable($dir);
        echo "<p>" . ($writable ? '✓' : '✗') . " $dir is " . ($writable ? 'writable' : 'NOT writable') . "</p>";
    } else {
        echo "<p>✗ Cannot create directory: $dir</p>";
    }
}

echo "<hr><h3>Troubleshooting Steps:</h3>";
echo "<ol>
    <li>Ensure OpenSSL is installed on your server</li>
    <li>Check that cert.pem and key.pem files exist in the correct location</li>
    <li>Verify file permissions (PHP needs read access to certificates)</li>
    <li>Check if exec() function is enabled in php.ini</li>
    <li>Look at PHP error logs for more details</li>
</ol>";
?>