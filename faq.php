<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JPI Signing System - Frequently Asked Questions">
    <meta name="keywords" content="FAQ, help, troubleshooting">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI Systems - Frequently Asked Questions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <style>
        .faq-category {
            margin-bottom: 30px;
        }
        .faq-category h4 {
            color: #24695c;
            padding-bottom: 10px;
            border-bottom: 2px solid #24695c;
            margin-bottom: 20px;
        }
        .faq-item {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-left: 4px solid #e9ecef;
        }
        .faq-item:hover {
            border-left-color: #24695c;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .faq-question {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-question .toggle-icon {
            color: #24695c;
            transition: transform 0.3s ease;
        }
        .faq-question.collapsed .toggle-icon {
            transform: rotate(0deg);
        }
        .faq-question:not(.collapsed) .toggle-icon {
            transform: rotate(180deg);
        }
        .faq-answer {
            color: #6c757d;
            line-height: 1.6;
            padding-top: 10px;
            border-top: 1px solid #e9ecef;
        }
        .search-box {
            max-width: 500px;
            margin: 0 auto 30px;
        }
        .badge-category {
            background-color: #e8f4f1;
            color: #24695c;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"> </div>
        <div class="dot"></div>
    </div>
    <!-- Loader ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-header">
            <?php include "includes/header.php"; ?>
        </div>
        <!-- Page Header Ends-->
        <!-- Page Body Start-->
        <div class="page-body-wrapper horizontal-menu">
            <!-- Page Sidebar Start-->
            <div class="sidebar-wrapper">
                <div>
                    <?php include "includes/sidebar.php"; ?>
                </div>
            </div>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Frequently Asked Questions</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Help</li>
                                    <li class="breadcrumb-item active">FAQ</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Frequently Asked Questions</h4>
                                    <p>Find answers to common questions about the file signing system</p>
                                </div>
                                <div class="card-body">
                                    <!-- Search Box -->
                                    <div class="search-box">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                                            <input type="text" class="form-control" id="faqSearch" placeholder="Search for questions or keywords...">
                                            <button class="btn btn-primary" type="button" onclick="searchFAQs()">Search</button>
                                        </div>
                                    </div>

                                    <!-- General Questions -->
                                    <div class="faq-category">
                                        <h4>General Questions</h4>
                                        <div class="accordion" id="generalFaq">
                                            <div class="faq-item">
                                                <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
                                                    What is the JPI File Signing System?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq1" class="collapse show faq-answer" data-bs-parent="#generalFaq">
                                                    The JPI File Signing System is a web-based application for digitally signing payment files using PKCS#7/SMIME signatures. It ensures the integrity and authenticity of financial transaction files before transmission to banking systems.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                                                    Who can use this system?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq2" class="collapse faq-answer" data-bs-parent="#generalFaq">
                                                    The system is designed for financial institutions, corporate treasuries, and organizations that need to send digitally signed payment files to banks. Users must have appropriate permissions assigned by the system administrator.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                                                    What types of files can be signed?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq3" class="collapse faq-answer" data-bs-parent="#generalFaq">
                                                    The system supports multiple file types including Payment files, Remittance files (with and without PRN), Foreign Payment files, and Salary Payment files in CSV format.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">
                                                    Is there a limit on file size?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq4" class="collapse faq-answer" data-bs-parent="#generalFaq">
                                                    Yes, the maximum file size for upload is 10MB. For larger files, please contact your system administrator for alternative arrangements.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Upload & Signing -->
                                    <div class="faq-category">
                                        <h4>File Upload & Signing</h4>
                                        <div class="accordion" id="uploadFaq">
                                            <div class="faq-item">
                                                <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="true">
                                                    How do I upload a file for signing?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq5" class="collapse show faq-answer" data-bs-parent="#uploadFaq">
                                                    <ol>
                                                        <li>Navigate to <strong>CSV Files → Upload & Sign</strong></li>
                                                        <li>Select the file type from the dropdown</li>
                                                        <li>Choose a certificate for signing</li>
                                                        <li>Enter a file reference (Run ID & Date)</li>
                                                        <li>Browse and select your CSV file</li>
                                                        <li>Enter certificate password if required</li>
                                                        <li>Click "Upload & Sign"</li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq6">
                                                    Why is my file upload failing?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq6" class="collapse faq-answer" data-bs-parent="#uploadFaq">
                                                    Common reasons for upload failure:
                                                    <ul>
                                                        <li>File size exceeds 10MB limit</li>
                                                        <li>Invalid file format (must be CSV)</li>
                                                        <li>Missing required fields in CSV</li>
                                                        <li>File reference already exists</li>
                                                        <li>Network connectivity issues</li>
                                                        <li>Server storage space full</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq7">
                                                    What CSV formats are supported?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq7" class="collapse faq-answer" data-bs-parent="#uploadFaq">
                                                    The system supports semicolon-delimited CSV files with UTF-8 encoding. Refer to the <a href="./file-formats.php">File Formats</a> page for detailed specifications for each file type.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq8">
                                                    How long does the signing process take?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq8" class="collapse faq-answer" data-bs-parent="#uploadFaq">
                                                    The signing process typically takes a few seconds for most files. Larger files (thousands of transactions) may take up to a minute. If the process takes longer, check your internet connection and server status.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Certificates -->
                                    <div class="faq-category">
                                        <h4>Certificates & Security</h4>
                                        <div class="accordion" id="certFaq">
                                            <div class="faq-item">
                                                <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="true">
                                                    What certificates are supported?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq9" class="collapse show faq-answer" data-bs-parent="#certFaq">
                                                    The system supports X.509 certificates in PEM format (.pem, .crt) with RSA private keys. Certificates should be issued by a trusted Certificate Authority (CA) and can be password-protected.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq10">
                                                    How do I add a new certificate?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq10" class="collapse faq-answer" data-bs-parent="#certFaq">
                                                    <ol>
                                                        <li>Navigate to <strong>Certificates → Upload New</strong></li>
                                                        <li>Enter certificate details (name, issuer, subject)</li>
                                                        <li>Provide paths to certificate and private key files</li>
                                                        <li>Enter certificate password if applicable</li>
                                                        <li>Set validity dates</li>
                                                        <li>Mark as default if needed</li>
                                                        <li>Click "Add Certificate"</li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq11">
                                                    What happens when a certificate expires?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq11" class="collapse faq-answer" data-bs-parent="#certFaq">
                                                    Expired certificates are automatically marked as inactive and cannot be used for signing new files. The system shows warnings for certificates expiring within 30 days. Previously signed files remain valid and verifiable.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq12">
                                                    Is my certificate password stored securely?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq12" class="collapse faq-answer" data-bs-parent="#certFaq">
                                                    Yes, certificate passwords are encrypted in the database and only used temporarily during the signing process. They are never displayed in plain text and are not logged in audit trails.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Troubleshooting -->
                                    <div class="faq-category">
                                        <h4>Troubleshooting</h4>
                                        <div class="accordion" id="troubleFaq">
                                            <div class="faq-item">
                                                <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq13" aria-expanded="true">
                                                    "OpenSSL not found" error
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq13" class="collapse show faq-answer" data-bs-parent="#troubleFaq">
                                                    This error occurs when OpenSSL is not installed or not in the system PATH. Solutions:
                                                    <ul>
                                                        <li>Install OpenSSL on your server</li>
                                                        <li>Update the OpenSSL path in system configuration</li>
                                                        <li>Contact your system administrator</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq14">
                                                    "Invalid certificate format" error
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq14" class="collapse faq-answer" data-bs-parent="#troubleFaq">
                                                    Ensure your certificate is in PEM format (text-based with BEGIN/END markers). Convert DER certificates using:
                                                    <code>openssl x509 -inform DER -in certificate.der -out certificate.pem</code>
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq15">
                                                    Signed file is empty or corrupted
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq15" class="collapse faq-answer" data-bs-parent="#troubleFaq">
                                                    This could be due to:
                                                    <ul>
                                                        <li>Incorrect certificate password</li>
                                                        <li>Certificate and private key mismatch</li>
                                                        <li>Insufficient disk space</li>
                                                        <li>File permission issues</li>
                                                        <li>OpenSSL version incompatibility</li>
                                                    </ul>
                                                    Check the audit logs for specific error details.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq16">
                                                    Can't download signed files
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq16" class="collapse faq-answer" data-bs-parent="#troubleFaq">
                                                    If you cannot download signed files:
                                                    <ul>
                                                        <li>Check your internet connection</li>
                                                        <li>Ensure the file exists on the server</li>
                                                        <li>Verify you have download permissions</li>
                                                        <li>Check browser download settings</li>
                                                        <li>Contact support if the issue persists</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- System & Administration -->
                                    <div class="faq-category">
                                        <h4>System & Administration</h4>
                                        <div class="accordion" id="adminFaq">
                                            <div class="faq-item">
                                                <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq17" aria-expanded="true">
                                                    How do I reset my password?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq17" class="collapse show faq-answer" data-bs-parent="#adminFaq">
                                                    Contact your system administrator to reset your password. Administrators can reset passwords from the Users management section.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq18">
                                                    How are audit trails maintained?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq18" class="collapse faq-answer" data-bs-parent="#adminFaq">
                                                    All system activities are logged including file uploads, signings, certificate changes, and user actions. Audit trails include timestamp, user, action, and details. Access them via <strong>Audit & Reports → Audit Logs</strong>.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq19">
                                                    Can I export reports?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq19" class="collapse faq-answer" data-bs-parent="#adminFaq">
                                                    Yes, you can export various reports including:
                                                    <ul>
                                                        <li>Signing activity reports</li>
                                                        <li>Certificate expiry reports</li>
                                                        <li>User activity reports</li>
                                                        <li>Batch processing reports</li>
                                                    </ul>
                                                    Navigate to <strong>Audit & Reports → Reports</strong> to generate and export reports.
                                                </div>
                                            </div>
                                            <div class="faq-item">
                                                <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq20">
                                                    How do I backup system data?
                                                    <i class="fa fa-chevron-down toggle-icon"></i>
                                                </div>
                                                <div id="faq20" class="collapse faq-answer" data-bs-parent="#adminFaq">
                                                    System administrators can perform backups via:
                                                    <ul>
                                                        <li><strong>Settings → Backup & Restore</strong> for manual backups</li>
                                                        <li>Database backup scripts</li>
                                                        <li>File system backups of uploads and signed files</li>
                                                    </ul>
                                                    Regular backups are recommended.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Still Need Help? -->
                                    <div class="text-center mt-5">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="text-primary">Still Need Help?</h4>
                                                <p>If you couldn't find answers to your questions, please contact our support team.</p>
                                                <div class="mt-3">
                                                    <a href="./support.php" class="btn btn-primary me-2">
                                                        <i class="fa fa-life-ring"></i> Contact Support
                                                    </a>
                                                    <a href="./documentation.php" class="btn btn-outline-primary">
                                                        <i class="fa fa-book"></i> View Documentation
                                                    </a>
                                                </div>
                                                <p class="mt-3 text-muted">
                                                    <small>You can also check the system status and known issues on the support page.</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function() {
            // FAQ search functionality
            window.searchFAQs = function() {
                var searchTerm = $('#faqSearch').val().toLowerCase();
                if (!searchTerm.trim()) {
                    // Show all FAQs if search is empty
                    $('.faq-item').show();
                    $('.faq-category').show();
                    return;
                }
                
                var foundItems = 0;
                
                $('.faq-category').each(function() {
                    var category = $(this);
                    var categoryFound = false;
                    
                    category.find('.faq-item').each(function() {
                        var item = $(this);
                        var question = item.find('.faq-question').text().toLowerCase();
                        var answer = item.find('.faq-answer').text().toLowerCase();
                        
                        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                            item.show();
                            categoryFound = true;
                            foundItems++;
                            
                            // Expand the FAQ item
                            var collapseId = item.find('.faq-question').data('bs-target');
                            $(collapseId).collapse('show');
                        } else {
                            item.hide();
                        }
                    });
                    
                    if (categoryFound) {
                        category.show();
                    } else {
                        category.hide();
                    }
                });
                
                // Show message if no results found
                if (foundItems === 0) {
                    Swal.fire({
                        title: 'No Results Found',
                        text: 'No FAQs match your search criteria. Try different keywords.',
                        icon: 'info',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            };
            
            // Search on Enter key
            $('#faqSearch').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchFAQs();
                }
            });
            
            // Initialize all FAQ items as collapsed except first in each category
            $('.faq-item:not(:first-child) .faq-answer').collapse('hide');
            $('.faq-item:not(:first-child) .faq-question').addClass('collapsed');
            
            // Add click handler for FAQ items
            $('.faq-question').on('click', function() {
                var icon = $(this).find('.toggle-icon');
                if ($(this).hasClass('collapsed')) {
                    icon.css('transform', 'rotate(180deg)');
                } else {
                    icon.css('transform', 'rotate(0deg)');
                }
            });
            
            // Print FAQ functionality
            $('#printFaq').on('click', function() {
                window.print();
            });
        });
    </script>
</body>
</html>