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
    <meta name="description" content="JPI Signing System - File Formats">
    <meta name="keywords" content="file formats, CSV, payment files">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI Systems - File Formats</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <style>
        .format-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 5px solid #24695c;
        }
        .format-title {
            color: #24695c;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        .field-table {
            font-size: 14px;
        }
        .field-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .required-field {
            color: #dc3545;
            font-weight: bold;
        }
        .optional-field {
            color: #6c757d;
        }
        .sample-code {
            background: #f8f9fa;
            border-left: 4px solid #24695c;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.4;
            overflow-x: auto;
        }
        .format-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }
        .format-tabs .nav-link.active {
            background-color: #24695c;
            color: white;
            border-color: #24695c;
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
                                <h3>File Formats</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Help</li>
                                    <li class="breadcrumb-item active">File Formats</li>
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
                                    <h4>Supported File Formats & Specifications</h4>
                                    <p>Detailed specifications for all supported file types</p>
                                </div>
                                <div class="card-body">
                                    <!-- Tabs Navigation -->
                                    <ul class="nav nav-tabs format-tabs mb-4" id="formatTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button">Payment File</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="remittance-tab" data-bs-toggle="tab" data-bs-target="#remittance" type="button">Remittance</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="foreign-tab" data-bs-toggle="tab" data-bs-target="#foreign" type="button">Foreign Payment</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary" type="button">Salary Payment</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">General Rules</button>
                                        </li>
                                    </ul>

                                    <!-- Tabs Content -->
                                    <div class="tab-content" id="formatTabsContent">
                                        
                                        <!-- Payment File Tab -->
                                        <div class="tab-pane fade show active" id="payment" role="tabpanel">
                                            <div class="format-section">
                                                <h4 class="format-title">Payment File Format (Type 1)</h4>
                                                <p>Standard payment transaction file format used for domestic payments.</p>
                                                
                                                <h6>File Structure:</h6>
                                                <ul>
                                                    <li><strong>Delimiter:</strong> Semicolon (;)</li>
                                                    <li><strong>Encoding:</strong> UTF-8</li>
                                                    <li><strong>Header Line:</strong> Mandatory (Type 0)</li>
                                                    <li><strong>Body Lines:</strong> Transaction records (Type 1)</li>
                                                    <li><strong>No Footer</strong></li>
                                                </ul>
                                                
                                                <h6>Header Line (Line Type 0):</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered field-table">
                                                        <thead>
                                                            <tr>
                                                                <th width="100">Position</th>
                                                                <th>Field Name</th>
                                                                <th>Data Type</th>
                                                                <th>Length</th>
                                                                <th>Required</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Header Line</td>
                                                                <td>Number</td>
                                                                <td>1</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Constant value: 0</td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>File Reference</td>
                                                                <td>String</td>
                                                                <td>16</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Run ID & Date (e.g., TEST_13)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Currency Code</td>
                                                                <td>String</td>
                                                                <td>3</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Transaction currency (MWK, USD, EUR, etc.)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>File Total</td>
                                                                <td>Decimal</td>
                                                                <td>20,2</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Total value of all transactions</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td>Total Count</td>
                                                                <td>Number</td>
                                                                <td>4</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Number of transaction lines</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <h6>Body Line (Line Type 1):</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered field-table">
                                                        <thead>
                                                            <tr>
                                                                <th width="100">Position</th>
                                                                <th>Field Name</th>
                                                                <th>Required</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Line Identifier</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Constant value: 1</td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Transaction Serial</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Sequential number (0001, 0002, etc.)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Currency Code</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Transaction currency</td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>Debit Account Number</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Source account number</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td>Debit Account Name</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Source account name</td>
                                                            </tr>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>Payment Amount</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Transaction amount</td>
                                                            </tr>
                                                            <tr>
                                                                <td>7</td>
                                                                <td>Payee Details</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Beneficiary account name</td>
                                                            </tr>
                                                            <tr>
                                                                <td>8</td>
                                                                <td>Vendor Code</td>
                                                                <td><span class="optional-field">Optional</span></td>
                                                                <td>UDF1 at bank</td>
                                                            </tr>
                                                            <tr>
                                                                <td>9</td>
                                                                <td>Employee Number</td>
                                                                <td><span class="optional-field">Optional</span></td>
                                                                <td>UDF2 at bank</td>
                                                            </tr>
                                                            <tr>
                                                                <td>10</td>
                                                                <td>National ID</td>
                                                                <td><span class="optional-field">Optional</span></td>
                                                                <td>UDF3 at bank</td>
                                                            </tr>
                                                            <tr>
                                                                <td>11</td>
                                                                <td>Invoice Number</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Payee's reference number</td>
                                                            </tr>
                                                            <tr>
                                                                <td>12</td>
                                                                <td>Payee BIC</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Beneficiary bank BIC code (11 chars)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>13</td>
                                                                <td>Credit Account Number</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Beneficiary account number</td>
                                                            </tr>
                                                            <tr>
                                                                <td>14</td>
                                                                <td>Cost Center</td>
                                                                <td><span class="optional-field">Optional</span></td>
                                                                <td>Originating cost/funds center</td>
                                                            </tr>
                                                            <tr>
                                                                <td>15</td>
                                                                <td>Date</td>
                                                                <td><span class="optional-field">Optional</span></td>
                                                                <td>Settlement date (DD.MM.YYYY)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>16</td>
                                                                <td>Source</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Unique reference from IFMIS</td>
                                                            </tr>
                                                            <tr>
                                                                <td>17</td>
                                                                <td>Reference Number</td>
                                                                <td><span class="optional-field">Optional</span></td>
                                                                <td>Additional reference</td>
                                                            </tr>
                                                            <tr>
                                                                <td>18</td>
                                                                <td>Description</td>
                                                                <td><span class="required-field">Required</span></td>
                                                                <td>Transaction description</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <h6>Sample Payment File:</h6>
                                                <div class="sample-code">
                                                    0;TEST_13;MWK;5000.00;0005<br>
                                                    1;0001;MWK;0013007800002;Malawi Vulnerability Assessment Committee;1000.00;UNDP;4610002;903962;58301610;615023;SBICMWM0;9100001187829;CC001;;058SC2200657840;PM1Nodate<br>
                                                    1;0002;MWK;0013007800002;Malawi Vulnerability Assessment Committee;1000.00;UNDP;4596989;154702;52914104;278044;SBICMWM0;9100001187829;CC002;;058SC2200657841;PM1Nodate<br>
                                                    1;0003;MWK;0013007800002;Malawi Vulnerability Assessment Committee;1000.00;UNDP;4553188;982633;72910067;697860;SBICMWM0;9100001187829;CC003;;058SC2200657842;PM1Nodate<br>
                                                    1;0004;MWK;0013007800002;Malawi Vulnerability Assessment Committee;1000.00;UNDP;4610923;608855;34146410;710413;SBICMWM0;9100001187829;CC004;;058SC2200657843;PM1Nodate<br>
                                                    1;0005;MWK;0013007800002;Malawi Vulnerability Assessment Committee;1000.00;UNDP;4567128;440809;15689667;435382;SBICMWM0;9100001187829;CC005;;058SC2200657844;PM1Nodate
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Remittance Tab -->
                                        <div class="tab-pane fade" id="remittance" role="tabpanel">
                                            <div class="format-section">
                                                <h4 class="format-title">Remittance File Format (Type 2 & 3)</h4>
                                                <p>Remittance files with or without Payment Reference Number (PRN).</p>
                                                
                                                <h6>Key Differences:</h6>
                                                <ul>
                                                    <li><strong>Type 2:</strong> Remittance without PRN</li>
                                                    <li><strong>Type 3:</strong> Remittance with PRN (extra field)</li>
                                                    <li>Line Identifier: 2 (instead of 1)</li>
                                                    <li>Different field structure</li>
                                                </ul>
                                                
                                                <h6>Sample Remittance File:</h6>
                                                <div class="sample-code">
                                                    0;RBM_TEST_001;125000.00;0005<br>
                                                    2;0001;0013007800002;Sample Account;MWK;0014000360008;MWK;25000.00;Sample Creditor;15.01.2024;15.01.2024;200TRF2400010001;55001001
                                                </div>
                                                
                                                <h6>Sample Remittance with PRN:</h6>
                                                <div class="sample-code">
                                                    0;LAG21-15.01.2024;125000.00;0005<br>
                                                    2;0001;0013007800002;Sample Account;MWK;0014000360008;MWK;25000.00;Sample Creditor;15.01.2024;15.01.2024;200TRF2400010001;55001001;0000000000123
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Foreign Payment Tab -->
                                        <div class="tab-pane fade" id="foreign" role="tabpanel">
                                            <div class="format-section">
                                                <h4 class="format-title">Foreign Payment File Format (Type 4)</h4>
                                                <p>Foreign currency payment file with extended fields for international transactions.</p>
                                                
                                                <h6>Special Fields:</h6>
                                                <ul>
                                                    <li>Corresponding Bank details</li>
                                                    <li>Corresponding Country</li>
                                                    <li>Non-resident indicator</li>
                                                    <li>BOP (Balance of Payments) category</li>
                                                    <li>Industrial classification</li>
                                                    <li>Additional beneficiary details</li>
                                                </ul>
                                                
                                                <h6>Sample Foreign Payment:</h6>
                                                <div class="sample-code">
                                                    0;FOREIGN_001;USD;25000.00;0010<br>
                                                    1;0001;USD;0013007800016;International Account;5000.00;FIVE THOUSAND US DOLLARS;International Payee Line 1;International Payee Line 2;INV001/24;NYC123;CHASUS33;12345678901234;CC001;15.01.2024;14.01.2024;SAP001;Citibank NA;United States;1;SMITH;JOHN;...
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Salary Payment Tab -->
                                        <div class="tab-pane fade" id="salary" role="tabpanel">
                                            <div class="format-section">
                                                <h4 class="format-title">Salary Payment File Format (Type 5)</h4>
                                                <p>Salary payment file format for bulk salary disbursements.</p>
                                                
                                                <h6>Header Structure:</h6>
                                                <ul>
                                                    <li>Vote Number</li>
                                                    <li>Funding Transfer Number</li>
                                                    <li>Value Date</li>
                                                    <li>Specific salary-related fields</li>
                                                </ul>
                                                
                                                <h6>Sample Salary File:</h6>
                                                <div class="sample-code">
                                                    0;12SC;140000.00;MWK;0013007800016;12SC2400010001;Sample Salary;31.01.2024;0007<br>
                                                    1;SBICMWM0;9100001187829;12SC2400010001001;SAMPLE EMPLOYEE;Sample Bank;049;SAL;20000.00
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- General Rules Tab -->
                                        <div class="tab-pane fade" id="general" role="tabpanel">
                                            <div class="format-section">
                                                <h4 class="format-title">General File Format Rules</h4>
                                                
                                                <h6>File Naming Convention:</h6>
                                                <ul>
                                                    <li><code>OBDXPMN_&lt;account&gt;_&lt;sequence&gt;.csv</code></li>
                                                    <li>Example: <code>OBDXPMN_001300780_16.csv</code></li>
                                                    <li>Use only alphanumeric characters and underscores</li>
                                                    <li>No spaces or special characters</li>
                                                </ul>
                                                
                                                <h6>General Requirements:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Requirement</th>
                                                                <th>Specification</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>File Encoding</td>
                                                                <td>UTF-8 (without BOM)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Line Endings</td>
                                                                <td>CRLF (Windows) or LF (Unix)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Maximum File Size</td>
                                                                <td>10 MB</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Field Delimiter</td>
                                                                <td>Semicolon (;)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Text Qualifier</td>
                                                                <td>Double quotes (") for fields containing delimiters</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Decimal Separator</td>
                                                                <td>Period (.)</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date Format</td>
                                                                <td>DD.MM.YYYY or YYYY-MM-DD</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Number Format</td>
                                                                <td>No thousands separators, 2 decimal places</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <h6>Validation Rules:</h6>
                                                <ul>
                                                    <li>Header line must be first line</li>
                                                    <li>Total amount must match sum of transaction amounts</li>
                                                    <li>Transaction count must match actual lines</li>
                                                    <li>Currency codes must be valid ISO codes</li>
                                                    <li>Account numbers must be numeric</li>
                                                    <li>BIC codes must be 8 or 11 characters</li>
                                                    <li>Amounts must be positive numbers</li>
                                                    <li>Dates must be valid and in correct format</li>
                                                </ul>
                                                
                                                <h6>Common Errors to Avoid:</h6>
                                                <ol>
                                                    <li>Missing or extra fields in a line</li>
                                                    <li>Incorrect delimiter usage</li>
                                                    <li>Trailing spaces in fields</li>
                                                    <li>Empty lines in the file</li>
                                                    <li>Incorrect line endings</li>
                                                    <li>Non-numeric values in numeric fields</li>
                                                    <li>Invalid date formats</li>
                                                    <li>Special characters without text qualifiers</li>
                                                </ol>
                                                
                                                <h6>Best Practices:</h6>
                                                <ul>
                                                    <li>Validate files before uploading</li>
                                                    <li>Keep backup of original files</li>
                                                    <li>Use consistent naming conventions</li>
                                                    <li>Test with small files first</li>
                                                    <li>Check certificate validity before signing</li>
                                                    <li>Verify signed files can be opened</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Download Templates -->
                                    <div class="format-section">
                                        <h4 class="format-title">Download Template Files</h4>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <i class="fa fa-file-text fa-3x text-primary mb-3"></i>
                                                        <h6>Payment Template</h6>
                                                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="downloadTemplate('payment')">Download</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <i class="fa fa-exchange fa-3x text-success mb-3"></i>
                                                        <h6>Remittance Template</h6>
                                                        <button class="btn btn-sm btn-outline-success mt-2" onclick="downloadTemplate('remittance')">Download</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <i class="fa fa-globe fa-3x text-info mb-3"></i>
                                                        <h6>Foreign Template</h6>
                                                        <button class="btn btn-sm btn-outline-info mt-2" onclick="downloadTemplate('foreign')">Download</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <i class="fa fa-users fa-3x text-warning mb-3"></i>
                                                        <h6>Salary Template</h6>
                                                        <button class="btn btn-sm btn-outline-warning mt-2" onclick="downloadTemplate('salary')">Download</button>
                                                    </div>
                                                </div>
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
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Handle template downloads
            window.downloadTemplate = function(type) {
                var content = '';
                var filename = '';
                
                switch(type) {
                    case 'payment':
                        content = '0;TEST_TEMPLATE;MWK;0.00;0000\n1;0001;MWK;;;;;;;Invoice_Number;BIC_Code;;Cost_Center;;Source_Reference;Payment_Description';
                        filename = 'payment_template.csv';
                        break;
                    case 'remittance':
                        content = '0;RBM_TEMPLATE;0.00;0000\n2;0001;;;;;;Creditor_Name;DD.MM.YYYY;DD.MM.YYYY;Source_Reference;Cost_Center';
                        filename = 'remittance_template.csv';
                        break;
                    case 'foreign':
                        content = '0;FOREIGN_TEMPLATE;USD;0.00;0000\n1;0001;USD;;;;;;;;;BIC_Code;;Cost_Center;DD.MM.YYYY;DD.MM.YYYY;SAP_Reference;Corresponding_Bank;Country;Non_Resident;Surname;First_Name;...';
                        filename = 'foreign_template.csv';
                        break;
                    case 'salary':
                        content = '0;VOTE_NUMBER;0.00;MWK;Debit_Account;Funding_TRF;Description;DD.MM.YYYY;0000\n1;BIC;IBAN;Reference;Account_Name;Bank_Name;Cost_Center;Description;0.00';
                        filename = 'salary_template.csv';
                        break;
                }
                
                // Create download link
                var blob = new Blob([content], { type: 'text/csv' });
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
                
                Swal.fire({
                    title: 'Template Downloaded',
                    text: filename + ' has been downloaded',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            };
            
            // Copy sample code to clipboard
            $('.sample-code').each(function() {
                $(this).append('<button class="btn btn-sm btn-outline-secondary copy-btn" style="float:right;margin-top:-5px;"><i class="fa fa-copy"></i> Copy</button>');
            });
            
            $(document).on('click', '.copy-btn', function() {
                var code = $(this).parent().text().replace('Copy', '').trim();
                navigator.clipboard.writeText(code).then(function() {
                    var btn = $(this);
                    btn.html('<i class="fa fa-check"></i> Copied');
                    setTimeout(function() {
                        btn.html('<i class="fa fa-copy"></i> Copy');
                    }, 2000);
                }.bind(this));
            });
        });
    </script>
</body>
</html>