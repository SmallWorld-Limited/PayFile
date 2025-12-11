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
    <title>JPI Systems - Create New Batch</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
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
                                <h3>Create New Batch</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Creation</li>
                                    <li class="breadcrumb-item active">Create New Batch</li>
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
                                    <h4>Create New File Batch</h4>
                                    <p>Manually create a new batch or generate from template</p>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="batchTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="manual-tab" data-bs-toggle="tab" href="#manual" role="tab">Manual Entry</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="template-tab" data-bs-toggle="tab" href="#template" role="tab">Use Template</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="upload-tab" data-bs-toggle="tab" href="#upload" role="tab">Upload Data</a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="batchTabContent">
                                        <!-- Manual Entry Tab -->
                                        <div class="tab-pane fade show active" id="manual" role="tabpanel">
                                            <form id="manualForm" class="mt-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">File Type</label>
                                                            <select class="form-control" id="file_type" name="file_type" required>
                                                                <option value="">-- Select File Type --</option>
                                                                <?php
                                                                $dropdowns = new dropdowns();
                                                                $dropdowns->get_file_types();
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">File Reference</label>
                                                            <input class="form-control" type="text" id="reference_no" name="reference_no" placeholder="Enter file reference" required>
                                                            <small class="form-text text-muted">Unique reference for this batch</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Currency Code</label>
                                                            <select class="form-control" id="currency_code" name="currency_code" required>
                                                                <option value="MWK">MWK - Malawian Kwacha</option>
                                                                <option value="USD">USD - US Dollar</option>
                                                                <option value="EUR">EUR - Euro</option>
                                                                <option value="GBP">GBP - British Pound</option>
                                                                <option value="ZAR">ZAR - South African Rand</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Batch Description</label>
                                                            <input class="form-control" type="text" id="description" name="description" placeholder="Enter batch description">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Batch Notes</label>
                                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any notes for this batch"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <h5 class="mt-4">Batch Items</h5>
                                                <div class="table-responsive">
                                                    <table class="table" id="itemsTable">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Transaction Serial</th>
                                                                <th>Debit Account</th>
                                                                <th>Debit Account Name</th>
                                                                <th>Amount</th>
                                                                <th>Payee Details</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="itemsBody">
                                                            <!-- Items will be added dynamically -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-primary" id="addItemBtn">
                                                            <i class="fa fa-plus"></i> Add Item
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-4">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total Amount</label>
                                                            <input class="form-control" type="text" id="total_amount" name="total_amount" readonly value="0.00">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total Count</label>
                                                            <input class="form-control" type="text" id="total_count" name="total_count" readonly value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-0">
                                                            <div class="text-end">
                                                                <button class="btn btn-secondary" type="button" onclick="window.history.back()">Cancel</button>
                                                                <button class="btn btn-primary" id="saveDraftBtn" type="button">Save as Draft</button>
                                                                <button class="btn btn-success" id="submitBatchBtn" type="button">Submit for Approval</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <!-- Template Tab -->
                                        <div class="tab-pane fade" id="template" role="tabpanel">
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <p>Select a template to create a new batch:</p>
                                                    <div class="row">
                                                        <?php
                                                        $templateMgr = new template_manager();
                                                        $templates = $templateMgr->get_all_templates();
                                                        
                                                        foreach ($templates as $template): ?>
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card template-card">
                                                                <div class="card-body">
                                                                    <h5 class="card-title"><?php echo htmlspecialchars($template['name']); ?></h5>
                                                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($template['code']); ?></h6>
                                                                    <p class="card-text"><?php echo htmlspecialchars($template['description']); ?></p>
                                                                    <div class="template-info">
                                                                        <small>Fields: <?php echo $template['field_count']; ?></small>
                                                                        <small>Usage: <?php echo $template['usage_count']; ?></small>
                                                                    </div>
                                                                    <button class="btn btn-primary btn-sm mt-2 use-template" 
                                                                            data-template-id="<?php echo $template['id']; ?>">
                                                                        Use Template
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Upload Tab -->
                                        <div class="tab-pane fade" id="upload" role="tabpanel">
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5>Upload Data File</h5>
                                                            <p>Upload a CSV or Excel file to create a batch</p>
                                                            
                                                            <form id="uploadDataForm">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-form-label">Select File Type</label>
                                                                            <select class="form-control" id="upload_file_type" name="upload_file_type" required>
                                                                                <option value="">-- Select File Type --</option>
                                                                                <?php $dropdowns->get_file_types(); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-form-label">Delimiter</label>
                                                                            <select class="form-control" id="delimiter" name="delimiter">
                                                                                <option value=";">Semicolon (;)</option>
                                                                                <option value=",">Comma (,)</option>
                                                                                <option value="\t">Tab (\t)</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="col-form-label">Upload Data File</label>
                                                                            <div class="custom-file">
                                                                                <input class="form-control" type="file" id="data_file" name="data_file" accept=".csv,.xlsx,.xls" required>
                                                                                <small class="form-text text-muted">Supported formats: CSV, Excel (.xlsx, .xls)</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="has_headers" name="has_headers" checked>
                                                                            <label class="form-check-label" for="has_headers">
                                                                                File has headers
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row mt-3">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group mb-0">
                                                                            <div class="text-end">
                                                                                <button class="btn btn-secondary" type="button" onclick="resetUploadForm()">Cancel</button>
                                                                                <button class="btn btn-primary" id="uploadDataBtn" type="submit">Upload & Preview</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
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
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    <?php include "includes/scripts.php"; ?>
    
    <script>
        // Item counter for manual entry
        let itemCounter = 0;
        
        $(document).ready(function () {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Add item button click
            $('#addItemBtn').click(function () {
                itemCounter++;
                addItemRow(itemCounter);
                updateTotals();
            });
            
            // Use template button click
            $('.use-template').click(function () {
                const templateId = $(this).data('template-id');
                Swal.fire({
                    title: 'Use Template?',
                    text: 'This will switch to manual entry with template fields',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, use template'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Switch to manual tab
                        $('#manual-tab').tab('show');
                        
                        // Load template fields via AJAX
                        $.ajax({
                            url: './php/batch_handler.php',
                            type: 'POST',
                            data: {
                                get_template_fields: 1,
                                template_id: templateId
                            },
                            success: function(response) {
                                // Handle template fields loading
                                console.log('Template loaded:', response);
                            }
                        });
                    }
                });
            });
            
            // Save draft button
            $('#saveDraftBtn').click(function () {
                saveBatch('draft');
            });
            
            // Submit batch button
            $('#submitBatchBtn').click(function () {
                saveBatch('submitted');
            });
            
            // Upload data form
            $('#uploadDataForm').submit(function (e) {
                e.preventDefault();
                uploadDataFile();
            });
        });
        
        function addItemRow(index) {
            const row = `
                <tr id="itemRow${index}">
                    <td>${index}</td>
                    <td><input type="text" class="form-control item-trans-serial" value="${String(index).padStart(4, '0')}" readonly></td>
                    <td><input type="text" class="form-control item-debit-account" placeholder="0013007800002"></td>
                    <td><input type="text" class="form-control item-debit-name" placeholder="Account Name"></td>
                    <td><input type="number" class="form-control item-amount" placeholder="0.00" step="0.01" min="0"></td>
                    <td><input type="text" class="form-control item-payee" placeholder="Payee Details"></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
            $('#itemsBody').append(row);
            
            // Add event listeners for the new row
            $(`#itemRow${index} .item-amount`).on('input', updateTotals);
            $(`#itemRow${index} .remove-item`).click(function() {
                $(`#itemRow${$(this).data('index')}`).remove();
                updateTotals();
            });
        }
        
        function updateTotals() {
            let totalAmount = 0;
            let itemCount = 0;
            
            $('.item-amount').each(function() {
                const amount = parseFloat($(this).val()) || 0;
                totalAmount += amount;
                itemCount++;
            });
            
            $('#total_amount').val(totalAmount.toFixed(2));
            $('#total_count').val(itemCount);
        }
        
        function saveBatch(status) {
            // Validate form
            if (!$('#file_type').val()) {
                Swal.fire('Error', 'Please select a file type', 'error');
                return;
            }
            
            if (!$('#reference_no').val()) {
                Swal.fire('Error', 'Please enter a file reference', 'error');
                return;
            }
            
            if (itemCounter === 0) {
                Swal.fire('Error', 'Please add at least one batch item', 'error');
                return;
            }
            
            // Collect batch data
            const batchData = {
                file_type_id: $('#file_type').val(),
                reference_no: $('#reference_no').val(),
                currency_code: $('#currency_code').val(),
                description: $('#description').val(),
                notes: $('#notes').val(),
                total_amount: $('#total_amount').val(),
                total_count: $('#total_count').val(),
                status: status
            };
            
            // Collect items data
            const items = [];
            $('tr[id^="itemRow"]').each(function() {
                const item = {
                    trans_serial: $(this).find('.item-trans-serial').val(),
                    debit_account: $(this).find('.item-debit-account').val(),
                    debit_name: $(this).find('.item-debit-name').val(),
                    amount: $(this).find('.item-amount').val(),
                    payee: $(this).find('.item-payee').val()
                };
                items.push(item);
            });
            
            batchData.items = items;
            
            // Send data to server
            $.ajax({
                url: './php/batch_handler.php',
                type: 'POST',
                data: {
                    create_batch: 1,
                    batch_data: JSON.stringify(batchData)
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.href = './batch-drafts.php';
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Failed to save batch: ' + error, 'error');
                }
            });
        }
        
        function uploadDataFile() {
            const formData = new FormData($('#uploadDataForm')[0]);
            formData.append('upload_data_file', 1);
            
            $.ajax({
                url: './php/batch_handler.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: res.message,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = './batch-drafts.php?batch_id=' + res.batch_id;
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Invalid response from server', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to upload file', 'error');
                }
            });
        }
        
        function resetUploadForm() {
            $('#uploadDataForm')[0].reset();
        }
    </script>
    
    <style>
        .template-card {
            height: 100%;
            transition: transform 0.3s;
        }
        
        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .template-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .remove-item {
            cursor: pointer;
        }
    </style>
</body>
</html>