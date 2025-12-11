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
    <meta name="description" content="JPI Signing System Documentation">
    <meta name="keywords" content="documentation, file signing, guide">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI Systems - Documentation</title>
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
        .doc-section {
            margin-bottom: 30px;
            padding: 20px;
            border-left: 4px solid #24695c;
            background-color: #f8f9fa;
        }
        .doc-title {
            color: #24695c;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .doc-content {
            line-height: 1.6;
        }
        .feature-list {
            list-style-type: none;
            padding-left: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .feature-list li:before {
            content: "✓";
            color: #24695c;
            font-weight: bold;
            margin-right: 10px;
        }
        .step-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #24695c;
            color: white;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e8f4f1;
            color: #24695c;
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
                                <h3>Documentation</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Help</li>
                                    <li class="breadcrumb-item active">Documentation</li>
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
                                    <h4>JPI File Signing System Documentation</h4>
                                    <p>Complete guide to using the file signing system</p>
                                </div>
                                <div class="card-body">
                                    <!-- Introduction -->
                                    <div class="doc-section">
                                        <h4 class="doc-title">Introduction</h4>
                                        <div class="doc-content">
                                            <p>The JPI File Signing System is a comprehensive solution for digitally signing payment files using PKCS#7/SMIME signatures. This system ensures the integrity and authenticity of financial transaction files before they are transmitted to banking systems.</p>
                                            
                                            <h5>Key Features:</h5>
                                            <ul class="feature-list">
                                                <li>Upload and validate CSV payment files</li>
                                                <li>Digital signing using X.509 certificates</li>
                                                <li>Multiple file format support (Payment, Remittance, Foreign, Salary)</li>
                                                <li>Batch processing and management</li>
                                                <li>Certificate management with expiry monitoring</li>
                                                <li>Comprehensive audit trails</li>
                                                <li>User role-based access control</li>
                                                <li>Dashboard with system statistics</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Quick Start Guide -->
                                    <div class="doc-section">
                                        <h4 class="doc-title">Quick Start Guide</h4>
                                        <div class="doc-content">
                                            <div class="step-box">
                                                <span class="step-number">1</span>
                                                <strong>Upload Certificate</strong>
                                                <p>Navigate to <a href="./addCert.php">Certificates → Upload New</a> to add your signing certificate and private key.</p>
                                            </div>
                                            <div class="step-box">
                                                <span class="step-number">2</span>
                                                <strong>Upload CSV File</strong>
                                                <p>Go to <a href="./uploadNew.php">CSV Files → Upload & Sign</a> to upload your payment file.</p>
                                            </div>
                                            <div class="step-box">
                                                <span class="step-number">3</span>
                                                <strong>Select Certificate</strong>
                                                <p>Choose the appropriate certificate for signing from the dropdown.</p>
                                            </div>
                                            <div class="step-box">
                                                <span class="step-number">4</span>
                                                <strong>Sign File</strong>
                                                <p>Click "Upload & Sign" to digitally sign your file.</p>
                                            </div>
                                            <div class="step-box">
                                                <span class="step-number">5</span>
                                                <strong>Download Signed File</strong>
                                                <p>Download the signed file with .p7s extension for submission to banking systems.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Types -->
                                    <div class="doc-section">
                                        <h4 class="doc-title">Supported File Types</h4>
                                        <div class="doc-content">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>File Type</th>
                                                            <th>Code</th>
                                                            <th>Description</th>
                                                            <th>Format</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Payment File</td>
                                                            <td>PAYMENT</td>
                                                            <td>Standard payment transaction file</td>
                                                            <td>Semicolon-delimited CSV</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Remittance File</td>
                                                            <td>REMITTANCE</td>
                                                            <td>Remittance without payment reference</td>
                                                            <td>Semicolon-delimited CSV</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Remittance with PRN</td>
                                                            <td>REMITTANCE_PRN</td>
                                                            <td>Remittance with payment reference number</td>
                                                            <td>Semicolon-delimited CSV</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Foreign Payment</td>
                                                            <td>FOREIGN</td>
                                                            <td>Foreign currency payment file</td>
                                                            <td>Semicolon-delimited CSV</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Salary Payment</td>
                                                            <td>SALARY</td>
                                                            <td>Salary payment file</td>
                                                            <td>Semicolon-delimited CSV</td>
                                                        </tr>
                                                        <tr>
                                                            <td>OBDX Payment</td>
                                                            <td>OBDX_PAYMENT</td>
                                                            <td>OBDX system payment file</td>
                                                            <td>Semicolon-delimited CSV</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FAQs Accordion -->
                                    <div class="doc-section">
                                        <h4 class="doc-title">Frequently Asked Questions</h4>
                                        <div class="accordion" id="faqAccordion">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="faq1">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                                        What certificates are supported?
                                                    </button>
                                                </h2>
                                                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                                    <div class="accordion-body">
                                                        The system supports X.509 certificates in PEM format (.pem, .crt) with RSA private keys. Certificates should include the private key file and may be password-protected.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="faq2">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                                        What is the maximum file size?
                                                    </button>
                                                </h2>
                                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                    <div class="accordion-body">
                                                        The maximum file size for upload is 10MB. For larger files, please contact your system administrator.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="faq3">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                                        How are signed files stored?
                                                    </button>
                                                </h2>
                                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                    <div class="accordion-body">
                                                        Signed files are stored in the <code>/signed_files/</code> directory and tracked in the database with audit trails. Original files are kept in <code>/uploads/</code> directory.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="faq4">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                                        Can I track who signed which file?
                                                    </button>
                                                </h2>
                                                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                    <div class="accordion-body">
                                                        Yes, every signing action is logged in the audit trail with timestamp and user information. You can view this in the <a href="./audit-logs.php">Audit Logs</a> section.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="faq5">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                                        What happens if a certificate expires?
                                                    </button>
                                                </h2>
                                                <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                                    <div class="accordion-body">
                                                        The system monitors certificate expiry and displays warnings for certificates expiring within 30 days. Expired certificates are marked inactive and cannot be used for signing.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Technical Specifications -->
                                    <div class="doc-section">
                                        <h4 class="doc-title">Technical Specifications</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>System Requirements</h6>
                                                        <ul>
                                                            <li>PHP 7.4 or higher</li>
                                                            <li>MySQL 5.7 or higher</li>
                                                            <li>OpenSSL extension</li>
                                                            <li>PDO MySQL extension</li>
                                                            <li>File uploads enabled</li>
                                                            <li>Minimum 256MB memory</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>Signature Specifications</h6>
                                                        <ul>
                                                            <li>Signature type: PKCS#7 / SMIME</li>
                                                            <li>Format: DER (binary)</li>
                                                            <li>Algorithm: SHA256 with RSA</li>
                                                            <li>Detached signatures: No</li>
                                                            <li>Output extension: .p7s</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="doc-section">
                                        <h4 class="doc-title">Need More Help?</h4>
                                        <div class="doc-content">
                                            <p>If you need additional assistance, please:</p>
                                            <ol>
                                                <li>Check the <a href="./faq.php">FAQ section</a> for common questions</li>
                                                <li>Review <a href="./file-formats.php">file format specifications</a></li>
                                                <li>Contact <a href="./support.php">support team</a> for technical issues</li>
                                                <li>Refer to system administrators for account-related queries</li>
                                            </ol>
                                            <p class="mt-3"><strong>Note:</strong> Always ensure your certificates are valid and your files follow the required format before signing.</p>
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
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 100
                    }, 800);
                }
            });
        });
    </script>
</body>
</html>