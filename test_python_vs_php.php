<?php
echo "<h2>Compare PHP vs Python OpenSSL Signing</h2>";

// Test CSV file (create a simple one)
$test_csv = "test_compare.csv";
$csv_content = "0;TEST_13;MWK;5000.00;0005
1;0001;MWK;0013007800002;Test Account;1000.00;Test Payee;4610002;903962;58301610;615023;SBICMWM0;9100001187829;CC001;;058SC2200657843;Test";
file_put_contents($test_csv, $csv_content);

echo "<p>Created test CSV: $test_csv (" . filesize($test_csv) . " bytes)</p>";

$cert_file = 'cert.pem';
$key_file = 'key.pem';
$password = 'xyz456';

if (file_exists($cert_file) && file_exists($key_file)) {
    
    echo "<h3>1. PHP Signing:</h3>";
    
    // Method 1: PHP exec() - our current method
    $php_output1 = "php_signed_1.csv";
    $cmd1 = '"C:\Program Files\OpenSSL-Win64\bin\openssl.exe" smime -sign';
    $cmd1 .= ' -in "' . $test_csv . '"';
    $cmd1 .= ' -out "' . $php_output1 . '"';
    $cmd1 .= ' -signer "' . $cert_file . '"';
    $cmd1 .= ' -inkey "' . $key_file . '"';
    $cmd1 .= ' -outform DER -nodetach -binary';
    
    if (!empty($password)) {
        $cmd1 .= ' -passin pass:' . escapeshellarg($password);
    }
    
    echo "<p>Command 1: <code>" . htmlspecialchars($cmd1) . "</code></p>";
    
    $output1 = [];
    $return1 = 0;
    exec($cmd1 . ' 2>&1', $output1, $return1);
    
    echo "<p>Return code: $return1</p>";
    if ($return1 === 0) {
        echo "<p style='color:green'>✓ PHP signed file created: $php_output1 (" . filesize($php_output1) . " bytes)</p>";
    } else {
        echo "<p style='color:red'>✗ PHP signing failed: " . implode("<br>", $output1) . "</p>";
    }
    
    echo "<h3>2. Python-style Signing:</h3>";
    
    // Method 2: Try to mimic Python's argument list
    $php_output2 = "php_signed_2.csv";
    $args = [
        'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe',
        'smime',
        '-sign',
        '-in',
        $test_csv,
        '-out',
        $php_output2,
        '-signer',
        $cert_file,
        '-inkey',
        $key_file,
        '-outform',
        'DER',
        '-nodetach',
        '-binary'
    ];
    
    if (!empty($password)) {
        array_push($args, '-passin', 'pass:' . $password);
    }
    
    // Build command with proper escaping
    $cmd2 = '';
    foreach ($args as $arg) {
        if (strpos($arg, ' ') !== false) {
            $cmd2 .= '"' . $arg . '" ';
        } else {
            $cmd2 .= $arg . ' ';
        }
    }
    $cmd2 = trim($cmd2);
    
    echo "<p>Command 2 (Python-style): <code>" . htmlspecialchars($cmd2) . "</code></p>";
    
    $output2 = [];
    $return2 = 0;
    exec($cmd2 . ' 2>&1', $output2, $return2);
    
    echo "<p>Return code: $return2</p>";
    if ($return2 === 0) {
        echo "<p style='color:green'>✓ Python-style signed file created: $php_output2 (" . filesize($php_output2) . " bytes)</p>";
    } else {
        echo "<p style='color:red'>✗ Python-style signing failed: " . implode("<br>", $output2) . "</p>";
    }
    
    echo "<h3>3. Direct System Call:</h3>";
    
    // Method 3: Use system() instead of exec()
    $php_output3 = "php_signed_3.csv";
    $cmd3 = 'start /B "" "C:\Program Files\OpenSSL-Win64\bin\openssl.exe" smime -sign';
    $cmd3 .= ' -in "' . realpath($test_csv) . '"';
    $cmd3 .= ' -out "' . realpath($php_output3) . '"';
    $cmd3 .= ' -signer "' . realpath($cert_file) . '"';
    $cmd3 .= ' -inkey "' . realpath($key_file) . '"';
    $cmd3 .= ' -outform DER -nodetach -binary';
    
    if (!empty($password)) {
        $cmd3 .= ' -passin pass:' . $password;
    }
    
    echo "<p>Command 3: <code>" . htmlspecialchars($cmd3) . "</code></p>";
    
    system($cmd3, $return3);
    
    echo "<p>Return code: $return3</p>";
    if (file_exists($php_output3)) {
        echo "<p style='color:green'>✓ System call file created: $php_output3 (" . filesize($php_output3) . " bytes)</p>";
    } else {
        echo "<p style='color:red'>✗ System call failed</p>";
    }
    
    echo "<h3>4. File Comparison:</h3>";
    
    if (file_exists($php_output1) && file_exists($php_output2)) {
        $content1 = file_get_contents($php_output1);
        $content2 = file_get_contents($php_output2);
        
        if ($content1 === $content2) {
            echo "<p style='color:green'>✓ Files are identical</p>";
        } else {
            echo "<p style='color:red'>✗ Files are DIFFERENT!</p>";
            echo "<p>Size 1: " . strlen($content1) . " bytes</p>";
            echo "<p>Size 2: " . strlen($content2) . " bytes</p>";
            
            // Show hex diff
            echo "<h4>First 100 bytes (hex):</h4>";
            echo "<p>File 1: ";
            for ($i = 0; $i < min(100, strlen($content1)); $i++) {
                printf("%02X ", ord($content1[$i]));
            }
            echo "</p>";
            
            echo "<p>File 2: ";
            for ($i = 0; $i < min(100, strlen($content2)); $i++) {
                printf("%02X ", ord($content2[$i]));
            }
            echo "</p>";
        }
    }
    
    // Clean up
    @unlink($test_csv);
    @unlink($php_output1);
    @unlink($php_output2);
    @unlink($php_output3);
    
} else {
    echo "<p style='color:red'>Certificate files not found:</p>";
    echo "<ul>";
    echo "<li>cert.pem: " . (file_exists('cert.pem') ? 'Found' : 'Not found') . "</li>";
    echo "<li>key.pem: " . (file_exists('key.pem') ? 'Found' : 'Not found') . "</li>";
    echo "</ul>";
}

// Test the actual Python script
echo "<h3>5. Test Actual Python Script:</h3>";
echo '<form method="POST" enctype="multipart/form-data">
    <input type="file" name="csv_file" accept=".csv">
    <button type="submit" name="test_python">Test with Actual Python Script</button>
</form>';

if (isset($_POST['test_python']) && isset($_FILES['csv_file'])) {
    $uploaded_file = $_FILES['csv_file']['tmp_name'];
    $original_name = $_FILES['csv_file']['name'];
    
    // Create Python script
    $python_script = "test_python_sign.py";
    $python_code = 'import subprocess
import sys
from pathlib import Path

PASSWORD = "xyz456"
OPENSSL = r\'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe\'

def sign(input_csv, cert_pem, key_pem, output_file):
    cmd = [
        OPENSSL, "smime", "-sign",
        "-in", str(input_csv),
        "-out", str(output_file),
        "-signer", str(cert_pem),
        "-inkey", str(key_pem),
        "-outform", "DER",
        "-nodetach",
        "-binary"
    ]
    
    print("Command:", " ".join(cmd))
    subprocess.run(cmd, check=True)
    print("Signed file written:", output_file)

if __name__ == "__main__":
    input_csv = Path("' . addslashes($uploaded_file) . '")
    cert_file = Path("cert.pem")
    key_file = Path("key.pem")
    output = Path("python_output_signed.csv")
    
    sign(input_csv, cert_file, key_file, output)';
    
    file_put_contents($python_script, $python_code);
    
    // Run Python script
    $output = [];
    $return = 0;
    exec('python ' . $python_script . ' 2>&1', $output, $return);
    
    echo "<pre>";
    echo implode("\n", $output);
    echo "</pre>";
    
    if (file_exists('python_output_signed.csv')) {
        echo "<p style='color:green'>✓ Python script output: python_output_signed.csv (" . filesize('python_output_signed.csv') . " bytes)</p>";
        @unlink('python_output_signed.csv');
    }
    
    @unlink($python_script);
}
?>