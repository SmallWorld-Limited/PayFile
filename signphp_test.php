<?php

// --- Configuration ---
// Define the paths and password here, matching your Python configuration
const PASSWORD = "xyz456";
const OPENSSL_PATH = 'C:\\Program Files\\OpenSSL-Win64\\bin\\openssl.exe';
// ---------------------

// --- Input Files ---
$input_csv = "OBDXPMN_001300780_16test4.csv";
$cert_file = "cert.pem";
$key_file = "key.pem";
$output_file = "OBDXPMN_001300780_16test4_Signed.csv";
// ---------------------

/**
 * Signs an input file using OpenSSL's smime command.
 * * @param string $input_csv Path to the input CSV file.
 * @param string $cert_pem Path to the signer certificate file (PEM format).
 * @param string $key_pem Path to the private key file (PEM format).
 * @param string $output_file Path to save the signed DER output file.
 * @param string $openssl_path Path to the openssl executable.
 * @throws Exception If the signing command fails or the output file is not created.
 */
function sign_file(
    string $input_csv, 
    string $cert_pem, 
    string $key_pem, 
    string $output_file, 
    string $openssl_path
) {
    echo "Starting file signing...\n";

    // 1. Validate File Existence
    if (!file_exists($openssl_path)) {
        throw new Exception("OpenSSL executable not found at: " . $openssl_path);
    }
    if (!file_exists($input_csv)) {
        throw new Exception("Input file not found: " . $input_csv);
    }
    if (!file_exists($cert_pem)) {
        throw new Exception("Certificate file not found: " . $cert_pem);
    }
    if (!file_exists($key_pem)) {
        throw new Exception("Private key file not found: " . $key_pem);
    }

    // 2. Build the Command - DIRECTLY MIMICKING PYTHON'S LIST OF ARGUMENTS
    // Use realpath() to ensure absolute paths for maximum compatibility
    $cmd_parts = [
        // Enclose paths in double quotes to handle spaces (e.g., in "Program Files")
        '"' . $openssl_path . '"',
        'smime',
        '-sign',
        '-in',      '"' . realpath($input_csv) . '"',
        '-out',     '"' . $output_file . '"', // Output file can be relative or absolute
        '-signer',  '"' . realpath($cert_pem) . '"',
        '-inkey',   '"' . realpath($key_pem) . '"',
        '-outform', 'DER',
        '-nodetach',
        '-binary',
        // If your key is password protected, uncomment this:
        // '-passin', 'pass:' . escapeshellarg(PASSWORD) 
    ];

    $cmd = implode(' ', $cmd_parts);

    echo "Executing OpenSSL Command:\n$cmd\n";

    // 3. Execute the Command
    $output = [];
    $return_code = 0;
    
    // `2>&1` redirects stderr (errors) to stdout (output), so we capture all messages
    exec($cmd . ' 2>&1', $output, $return_code);

    echo "OpenSSL Output:\n" . implode("\n", $output) . "\n";
    echo "OpenSSL Return Code: $return_code\n";

    // 4. Check for success
    if ($return_code !== 0) {
        $error_msg = "OpenSSL signing failed (Code: $return_code). Output:\n" . implode("\n", $output);
        throw new Exception($error_msg);
    }

    if (!file_exists($output_file)) {
        throw new Exception("Signed file was not created: " . $output_file);
    }
    
    $file_size = filesize($output_file);
    if ($file_size === 0) {
         throw new Exception("Signed file is empty: " . $output_file);
    }

    echo "✅ Signed file created successfully: **" . $output_file . "** (" . $file_size . " bytes)\n";
}

try {
    sign_file(
        $input_csv, 
        $cert_file, 
        $key_file, 
        $output_file, 
        OPENSSL_PATH
    );
} catch (Exception $e) {
    // Catch and display any errors
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

?>