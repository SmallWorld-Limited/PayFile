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
    <meta name="description" content="JPI Signing System">
    <meta name="keywords" content="">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI Systems - Duplicate Check</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatable-extension.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
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
                                <h3>Duplicate Check</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Validation</li>
                                    <li class="breadcrumb-item active">Duplicate Check</li>
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
                                    <h4>Batch Duplicate Detection</h4>
                                    <div class="float-end">
                                        <button type="button" class="btn btn-primary" id="runDuplicateCheckBtn">
                                            <i class="fa fa-search"></i> Run Duplicate Check
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="col-form-label">Select Batch</label>
                                                <select class="form-control" id="batchSelect" name="batch_id">
                                                    <option value="">-- Select Batch --</option>
                                                    <?php
                                                    $batch_mgmt = new batch_management();
                                                    $batches = $batch_mgmt->get_all_batches();
                                                    
                                                    foreach ($batches as $batch) {
                                                        echo '<option value="' . $batch['id'] . '" data-count="' . $batch['total_count'] . '">' .
                                                             $batch['batch_number'] . ' - ' . $batch['reference_no'] . 
                                                             ' (' . $batch['total_count'] . ' items)</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <small class="form-text text-muted">Select a batch to check for duplicates</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Check Specific Field (Optional)</label>
                                                <input class="form-control" type="text" id="fieldName" name="field_name" 
                                                       placeholder="e.g., invoice_number, credit_account_number">
                                                <small class="form-text text-muted">Leave empty to check for duplicate rows</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div id="checkProgress" class="d-none">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                         role="progressbar" style="width: 100%">Checking for duplicates...</div>
                                                </div>
                                            </div>
                                            
                                            <div id="checkResult" class="d-none">
                                                <div class="alert" id="resultAlert">
                                                    <h5 id="resultTitle"></h5>
                                                    <div id="resultContent"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Duplicate Checks -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Recent Duplicate Checks</h4>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="duplicateHistoryTable" role="grid" aria-describedby="duplicateHistoryTable_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>Date</th>
                                                    <th>Batch</th>
                                                    <th>Field Checked</th>
                                                    <th>Total Items</th>
                                                    <th>Duplicates Found</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // This would normally come from a database table of duplicate check history
                                                // For now, we'll show a placeholder
                                                ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fa fa-info-circle"></i> 
                                                            Duplicate check history will be displayed here after running checks.
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Duplicate Prevention Guidelines -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Duplicate Prevention Guidelines</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="duplicate-guideline">
                                                <h6><i class="fa fa-check-circle text-success"></i> Common Fields to Check</h6>
                                                <ul>
                                                    <li><strong>Invoice Number:</strong> Should be unique per transaction</li>
                                                    <li><strong>Reference Number:</strong> Should be unique per payment</li>
                                                    <li><strong>Credit Account:</strong> Check for multiple payments to same account</li>
                                                    <li><strong>Vendor Code + Invoice:</strong> Unique combination</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="duplicate-guideline">
                                                <h6><i class="fa fa-exclamation-triangle text-warning"></i> Prevention Tips</h6>
                                                <ul>
                                                    <li>Always run duplicate check before signing files</li>
                                                    <li>Check for duplicates across different batches</li>
                                                    <li>Use unique reference numbers for all transactions</li>
                                                    <li>Validate data at entry point to prevent duplicates</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="alert alert-warning">
                                                <h6><i class="fa fa-lightbulb"></i> Best Practices</h6>
                                                <p class="mb-0">
                                                    • Run duplicate checks regularly, especially before batch approval<br>
                                                    • Maintain a central reference database to prevent cross-batch duplicates<br>
                                                    • Implement validation rules to catch duplicates at upload time<br>
                                                    • Review duplicate reports before file signing
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
        $(document).ready(function () {
            // Initialize DataTable
            var historyTable = $('#duplicateHistoryTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                pageLength: 10,
                responsive: true
            });
            
            // Run duplicate check
            $('#runDuplicateCheckBtn').click(function () {
                var batchId = $('#batchSelect').val();
                var fieldName = $('#fieldName').val();
                
                if (!batchId) {
                    Swal.fire({
                        title: "Missing Information",
                        text: "Please select a batch to check",
                        icon: "warning"
                    });
                    return;
                }
                
                // Show progress
                $('#checkProgress').removeClass('d-none');
                $('#checkResult').addClass('d-none');
                
                $.ajax({
                    type: "POST",
                    url: "./php/validation_handler.php",
                    data: {
                        check_duplicates: '1',
                        batch_id: batchId,
                        field_name: fieldName
                    },
                    success: function (response) {
                        $('#checkProgress').addClass('d-none');
                        
                        try {
                            var res = JSON.parse(response);
                            var $resultDiv = $('#checkResult');
                            var $resultAlert = $('#resultAlert');
                            var $resultTitle = $('#resultTitle');
                            var $resultContent = $('#resultContent');
                            
                            if (res.id == 1) {
                                // Success
                                if (res.duplicate_count > 0) {
                                    $resultAlert.removeClass('alert-success').addClass('alert-danger');
                                    $resultTitle.html('<i class="fa fa-exclamation-triangle"></i> Duplicates Found!');
                                    
                                    var content = '<p><strong>Total Items:</strong> ' + res.total_items + '</p>';
                                    content += '<p><strong>Duplicates Found:</strong> ' + res.duplicate_count + '</p>';
                                    content += '<hr>';
                                    
                                    $.each(res.duplicates, function(index, duplicate) {
                                        content += '<div class="duplicate-item mb-3">';
                                        
                                        if (duplicate.type === 'field_duplicate') {
                                            content += '<h6><i class="fa fa-field"></i> Field: ' + duplicate.field + '</h6>';
                                            content += '<p><strong>Value:</strong> ' + duplicate.value + '</p>';
                                            content += '<p><strong>Found in rows:</strong> ' + duplicate.indices.join(', ') + '</p>';
                                        } else {
                                            content += '<h6><i class="fa fa-copy"></i> Duplicate Row</h6>';
                                            content += '<p><strong>Found in rows:</strong> ' + duplicate.indices.join(', ') + '</p>';
                                            if (duplicate.sample_data) {
                                                content += '<p><strong>Sample Data:</strong></p>';
                                                content += '<pre class="bg-light p-2 border rounded">' + 
                                                          JSON.stringify(duplicate.sample_data, null, 2) + '</pre>';
                                            }
                                        }
                                        
                                        content += '</div>';
                                    });
                                    
                                    content += '<div class="mt-3">';
                                    content += '<button class="btn btn-sm btn-outline-danger" id="exportDuplicatesBtn">';
                                    content += '<i class="fa fa-download"></i> Export Duplicates Report';
                                    content += '</button>';
                                    content += '</div>';
                                    
                                } else {
                                    $resultAlert.removeClass('alert-danger').addClass('alert-success');
                                    $resultTitle.html('<i class="fa fa-check-circle"></i> No Duplicates Found');
                                    
                                    var content = '<p><strong>✓ Excellent! No duplicates found.</strong></p>';
                                    content += '<p><strong>Total Items Checked:</strong> ' + res.total_items + '</p>';
                                    
                                    if (fieldName) {
                                        content += '<p><strong>Field Checked:</strong> ' + fieldName + '</p>';
                                    } else {
                                        content += '<p><strong>Check Type:</strong> Complete row comparison</p>';
                                    }
                                }
                                
                                $resultContent.html(content);
                                $resultDiv.removeClass('d-none');
                                
                                // Scroll to results
                                $('html, body').animate({
                                    scrollTop: $('#checkResult').offset().top - 100
                                }, 500);
                                
                                // Add to history table (demo)
                                var batchText = $('#batchSelect option:selected').text();
                                var date = new Date().toLocaleString();
                                
                                historyTable.row.add([
                                    date,
                                    batchText.split(' - ')[0],
                                    fieldName || 'All Fields',
                                    res.total_items,
                                    res.duplicate_count,
                                    res.duplicate_count > 0 ? 
                                        '<span class="badge badge-danger">Duplicates</span>' : 
                                        '<span class="badge badge-success">Clean</span>',
                                    '<button class="btn btn-sm btn-info view-details" title="View Details">' +
                                    '<i class="fa fa-eye"></i></button>'
                                ]).draw();
                                
                            } else {
                                // Error
                                Swal.fire({
                                    title: "Error",
                                    text: res.mssg,
                                    icon: "error"
                                });
                            }
                        } catch (e) {
                            console.error("Parse error:", e, response);
                            Swal.fire({
                                title: "Error",
                                text: "Invalid response from server",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#checkProgress').addClass('d-none');
                        Swal.fire({
                            title: "Error",
                            text: "Failed to check duplicates: " + error,
                            icon: "error"
                        });
                    }
                });
            });
            
            // Export duplicates button (delegated event)
            $(document).on('click', '#exportDuplicatesBtn', function() {
                var batchId = $('#batchSelect').val();
                var fieldName = $('#fieldName').val();
                
                Swal.fire({
                    title: 'Export Duplicates Report',
                    text: 'Do you want to export the duplicates report?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, export'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and download CSV
                        var batchText = $('#batchSelect option:selected').text();
                        var filename = 'duplicates_' + batchText.split(' - ')[0] + '_' + 
                                     new Date().toISOString().slice(0,10) + '.csv';
                        
                        var csvContent = "data:text/csv;charset=utf-8,";
                        csvContent += "Duplicate Check Report\r\n";
                        csvContent += "Batch: " + batchText + "\r\n";
                        csvContent += "Date: " + new Date().toLocaleString() + "\r\n";
                        csvContent += "Field Checked: " + (fieldName || "All Fields") + "\r\n";
                        csvContent += "\r\n";
                        csvContent += "Type,Field,Value,Row Numbers,Details\r\n";
                        
                        // This would be populated with actual duplicate data
                        // For now, just create a basic template
                        csvContent += "Sample,Field Name,Sample Value,1,2,3,Sample duplicate\r\n";
                        
                        var encodedUri = encodeURI(csvContent);
                        var link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", filename);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        Swal.fire({
                            title: "Exported!",
                            text: "Duplicate report downloaded",
                            icon: "success"
                        });
                    }
                });
            });
            
            // View details button in history table (delegated event)
            $(document).on('click', '.view-details', function() {
                var row = historyTable.row($(this).parents('tr'));
                var data = row.data();
                
                Swal.fire({
                    title: 'Check Details',
                    html: '<div class="text-left">' +
                          '<p><strong>Date:</strong> ' + data[0] + '</p>' +
                          '<p><strong>Batch:</strong> ' + data[1] + '</p>' +
                          '<p><strong>Field Checked:</strong> ' + data[2] + '</p>' +
                          '<p><strong>Total Items:</strong> ' + data[3] + '</p>' +
                          '<p><strong>Duplicates Found:</strong> ' + data[4] + '</p>' +
                          '<p><strong>Status:</strong> ' + data[5] + '</p>' +
                          '</div>',
                    icon: 'info',
                    confirmButtonText: 'Close'
                });
            });
            
            // Batch selection change
            $('#batchSelect').change(function() {
                var selectedOption = $(this).find('option:selected');
                var itemCount = selectedOption.data('count');
                
                if (itemCount) {
                    $(this).next('.form-text').text('Selected batch has ' + itemCount + ' items');
                }
                
                // Clear previous results
                $('#checkResult').addClass('d-none');
            });
        });
    </script>
    
    <style>
        .duplicate-guideline {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 5px 5px 0;
        }
        
        .duplicate-guideline h6 {
            color: #495057;
            margin-bottom: 10px;
        }
        
        .duplicate-guideline ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        
        .duplicate-guideline li {
            margin-bottom: 5px;
            color: #6c757d;
        }
        
        .duplicate-item {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }
        
        .duplicate-item h6 {
            color: #dc3545;
            margin-bottom: 10px;
        }
        
        #resultAlert {
            border-radius: 5px;
            padding: 20px;
        }
        
        #resultAlert.alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        #resultAlert.alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</body>
</html>