<?php
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$sage_id = $_GET['id'] ?? 0;
if (!$sage_id) {
    header("Location: ./upload-sage.php");
    exit;
}

$sage_mgmt = new sage_management();
$sage_data = $sage_mgmt->get_sage_preview($sage_id, 3);

// Get target file type from URL or session
$target_file_type = $_GET['target_type'] ?? $_SESSION['sage_target_type'] ?? 1;
$_SESSION['sage_target_type'] = $target_file_type;

// Get schema for mapping
$schema = $sage_mgmt->get_file_schema($target_file_type);
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
    <title>Sage Field Mapping - JPI Systems</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <style>
        .mapping-row {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .sage-field {
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 4px;
            font-family: monospace;
        }
        .target-field {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .preview-table {
            font-size: 12px;
        }
        .mapping-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .mapping-table td {
            vertical-align: middle;
        }
        .instruction-card {
            border-left: 4px solid #007bff;
        }
        .preview-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        .test-btn {
            min-width: 70px;
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
                                <h3>Sage Field Mapping</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Creation</li>
                                    <li class="breadcrumb-item"><a href="./upload-sage.php">Upload Sage</a></li>
                                    <li class="breadcrumb-item active">Field Mapping</li>
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
                                    <h4>Map Sage Fields to Target Format</h4>
                                    <p>Map your Sage data columns to the required OBDX format fields</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <form id="mappingForm">
                                                <input type="hidden" id="sage_id" value="<?php echo $sage_id; ?>">
                                                <input type="hidden" id="target_file_type" value="<?php echo $target_file_type; ?>">
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-bordered mapping-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Target Field</th>
                                                                <th width="120">Field Type</th>
                                                                <th width="100">Mandatory</th>
                                                                <th>Map to Sage Column</th>
                                                                <th>Default Value</th>
                                                                <th width="80">Test</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($schema as $field): ?>
                                                            <tr>
                                                                <td>
                                                                    <strong><?php echo htmlspecialchars($field['display_name']); ?></strong><br>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($field['field_name']); ?></small>
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-light"><?php echo strtoupper($field['data_type']); ?></span>
                                                                </td>
                                                                <td>
                                                                    <?php if ($field['mandatory']): ?>
                                                                    <span class="badge badge-danger">Required</span>
                                                                    <?php else: ?>
                                                                    <span class="badge badge-secondary">Optional</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control form-control-sm sage-column-select" 
                                                                            name="mapping[<?php echo $field['id']; ?>][sage_column]"
                                                                            data-field-id="<?php echo $field['id']; ?>">
                                                                        <option value="">-- Select Sage Column --</option>
                                                                        <?php if (!empty($sage_data)): ?>
                                                                            <?php for ($i = 0; $i < count($sage_data[0]['fields']); $i++): ?>
                                                                            <option value="<?php echo $i; ?>">
                                                                                Column <?php echo $i; ?>: "<?php echo htmlspecialchars(substr($sage_data[0]['fields'][$i], 0, 30)); ?>"
                                                                            </option>
                                                                            <?php endfor; ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control form-control-sm" 
                                                                           name="mapping[<?php echo $field['id']; ?>][default_value]"
                                                                           value="<?php echo htmlspecialchars($field['default_value']); ?>"
                                                                           placeholder="Enter default value">
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-outline-info test-mapping test-btn"
                                                                            data-field-id="<?php echo $field['id']; ?>">
                                                                        Test
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-0">
                                                            <div class="text-end">
                                                                <button class="btn btn-secondary" type="button" onclick="window.history.back()">
                                                                    <i class="fa fa-arrow-left"></i> Back
                                                                </button>
                                                                <button class="btn btn-info" type="button" id="previewBtn">
                                                                    <i class="fa fa-eye"></i> Preview File
                                                                </button>
                                                                <button class="btn btn-primary" type="button" id="validateBtn">
                                                                    <i class="fa fa-check-circle"></i> Validate Mappings
                                                                </button>
                                                                <button class="btn btn-success" type="button" id="generateBtn">
                                                                    <i class="fa fa-file-export"></i> Generate File
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card preview-card">
                                                <div class="card-header pb-0">
                                                    <h5>Sage Data Preview</h5>
                                                </div>
                                                <div class="card-body">
                                                    <?php if (empty($sage_data)): ?>
                                                    <div class="alert alert-warning">
                                                        <i class="fa fa-exclamation-triangle"></i> No Sage data found
                                                    </div>
                                                    <?php else: ?>
                                                    <div class="table-responsive preview-table">
                                                        <table class="table table-sm table-bordered">
                                                            <thead>
                                                                <tr class="table-light">
                                                                    <th>#</th>
                                                                    <?php for ($i = 0; $i < count($sage_data[0]['fields']); $i++): ?>
                                                                    <th>Col <?php echo $i; ?></th>
                                                                    <?php endfor; ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($sage_data as $row): ?>
                                                                <tr>
                                                                    <td class="text-center"><strong><?php echo $row['index']; ?></strong></td>
                                                                    <?php foreach ($row['fields'] as $value): ?>
                                                                    <td title="<?php echo htmlspecialchars($value); ?>">
                                                                        <?php echo htmlspecialchars(substr($value, 0, 15)); ?>
                                                                        <?php if (strlen($value) > 15): ?>...<?php endif; ?>
                                                                    </td>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <small class="text-muted"><i class="fa fa-info-circle"></i> Showing first 3 records</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="card mt-3 instruction-card">
                                                <div class="card-header pb-0">
                                                    <h5><i class="fa fa-graduation-cap text-primary"></i> Instructions</h5>
                                                </div>
                                                <div class="card-body">
                                                    <ol class="mb-0" style="padding-left: 15px;">
                                                        <li>For each target field, select which Sage column contains the data</li>
                                                        <li>For static values, leave Sage column empty and enter a default value</li>
                                                        <li>Click "Test" to see sample values for a mapping</li>
                                                        <li>Click "Preview" to see the generated file format</li>
                                                        <li>Click "Validate" to check for errors before generating</li>
                                                        <li>Click "Generate" to create the final OBDX file</li>
                                                    </ol>
                                                    
                                                    <div class="alert alert-info mt-3 mb-0">
                                                        <i class="fa fa-lightbulb"></i>
                                                        <strong>Tip:</strong> Required fields must be mapped or have default values
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
        $(document).ready(function () {
            // Test mapping button
            $('.test-mapping').click(function () {
                const fieldId = $(this).data('field-id');
                const sageColumn = $(`select[name="mapping[${fieldId}][sage_column]"]`).val();
                const defaultValue = $(`input[name="mapping[${fieldId}][default_value]"]`).val();
                
                $.ajax({
                    url: './php/sage_handler.php',
                    type: 'POST',
                    data: {
                        test_mapping: 1,
                        sage_id: $('#sage_id').val(),
                        field_id: fieldId,
                        sage_column: sageColumn,
                        default_value: defaultValue
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.id == 1) {
                            Swal.fire({
                                title: 'Test Result',
                                html: response.result + '<br><br><strong>Preview:</strong><br>' + 
                                      '<code style="background: #f8f9fa; padding: 10px; border-radius: 4px; display: block; margin-top: 10px;">' + 
                                      response.preview + '</code>',
                                icon: 'info',
                                width: '600px'
                            });
                        } else {
                            Swal.fire('Error', response.mssg, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to test mapping', 'error');
                    }
                });
            });
            
            // Preview button
            $('#previewBtn').click(function () {
                const formData = $('#mappingForm').serialize();
                
                $.ajax({
                    url: './php/sage_handler.php',
                    type: 'POST',
                    data: formData + '&preview_file=1&sage_id=' + $('#sage_id').val(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#previewBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating...');
                    },
                    success: function(response) {
                        $('#previewBtn').prop('disabled', false).html('<i class="fa fa-eye"></i> Preview File');
                        
                        if (response.id == 1) {
                            // Show preview in modal
                            const previewHtml = `
                                <div style="max-height: 400px; overflow-y: auto;">
                                    <pre style="background: #f8f9fa; padding: 15px; border-radius: 4px; font-size: 12px; font-family: 'Courier New', monospace;">${response.preview}</pre>
                                </div>`;
                            
                            Swal.fire({
                                title: 'File Preview',
                                html: previewHtml,
                                width: '800px',
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                                showCloseButton: true
                            });
                        } else {
                            Swal.fire('Error', response.mssg, 'error');
                        }
                    },
                    error: function() {
                        $('#previewBtn').prop('disabled', false).html('<i class="fa fa-eye"></i> Preview File');
                        Swal.fire('Error', 'Failed to generate preview', 'error');
                    }
                });
            });
            
            // Validate button
            $('#validateBtn').click(function () {
                const formData = $('#mappingForm').serialize();
                
                $.ajax({
                    url: './php/sage_handler.php',
                    type: 'POST',
                    data: formData + '&validate_mappings=1&sage_id=' + $('#sage_id').val(),
                    dataType: 'json',
                    beforeSend: function() {
                        $('#validateBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Validating...');
                    },
                    success: function(response) {
                        $('#validateBtn').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Validate Mappings');
                        
                        if (response.id == 1) {
                            Swal.fire({
                                title: 'Validation Successful',
                                text: response.mssg,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            let errorMsg = response.mssg;
                            if (response.validation_errors && response.validation_errors.length > 0) {
                                errorMsg += '<br><br><strong>Errors:</strong><ul>';
                                response.validation_errors.forEach(function(error) {
                                    errorMsg += `<li>${error}</li>`;
                                });
                                errorMsg += '</ul>';
                            }
                            
                            Swal.fire({
                                title: 'Validation Failed',
                                html: errorMsg,
                                icon: 'error',
                                width: '600px'
                            });
                        }
                    },
                    error: function() {
                        $('#validateBtn').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Validate Mappings');
                        Swal.fire('Error', 'Failed to validate mappings', 'error');
                    }
                });
            });
            
            // Generate button
            $('#generateBtn').click(function () {
                Swal.fire({
                    title: 'Generate File?',
                    text: 'This will create the final OBDX format file. Continue?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, generate',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = $('#mappingForm').serialize();
                        
                        $.ajax({
                            url: './php/sage_handler.php',
                            type: 'POST',
                            data: formData + '&generate_file=1&sage_id=' + $('#sage_id').val(),
                            dataType: 'json',
                            beforeSend: function() {
                                $('#generateBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating...');
                            },
                            success: function(response) {
                                $('#generateBtn').prop('disabled', false).html('<i class="fa fa-file-export"></i> Generate File');
                                
                                if (response.id == 1) {
                                    Swal.fire({
                                        title: 'Success!',
                                        html: response.mssg + '<br><br>' +
                                              '<div class="d-grid gap-2">' +
                                              '<a href="' + response.download_url + '" class="btn btn-primary">Download File</a>' +
                                              '<a href="./uploads.php" class="btn btn-outline-primary">View All Files</a>' +
                                              '</div>',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        width: '500px'
                                    });
                                } else {
                                    Swal.fire('Error', response.mssg, 'error');
                                }
                            },
                            error: function() {
                                $('#generateBtn').prop('disabled', false).html('<i class="fa fa-file-export"></i> Generate File');
                                Swal.fire('Error', 'Failed to generate file', 'error');
                            }
                        });
                    }
                });
            });
            
            // Auto-select based on field names if they match
            function autoMapFields() {
                $('.sage-column-select').each(function() {
                    const fieldName = $(this).closest('tr').find('strong').text().toLowerCase();
                    const options = $(this).find('option');
                    
                    let foundMatch = false;
                    options.each(function() {
                        const optionText = $(this).text().toLowerCase();
                        
                        // Try to match common field names
                        if (fieldName.includes('account') && optionText.includes('account')) {
                            $(this).prop('selected', true);
                            foundMatch = true;
                            return false;
                        }
                        if (fieldName.includes('name') && optionText.includes('name')) {
                            $(this).prop('selected', true);
                            foundMatch = true;
                            return false;
                        }
                        if (fieldName.includes('amount') && optionText.includes('amount')) {
                            $(this).prop('selected', true);
                            foundMatch = true;
                            return false;
                        }
                        if (fieldName.includes('date') && optionText.includes('date')) {
                            $(this).prop('selected', true);
                            foundMatch = true;
                            return false;
                        }
                        if (fieldName.includes('reference') && optionText.includes('reference')) {
                            $(this).prop('selected', true);
                            foundMatch = true;
                            return false;
                        }
                    });
                    
                    // If no match found and field has example value, use that as default
                    if (!foundMatch) {
                        const exampleValue = $(this).closest('tr').find('input[type="text"]').attr('placeholder');
                        if (exampleValue) {
                            $(this).closest('tr').find('input[type="text"]').val(exampleValue);
                        }
                    }
                });
            }
            
            // Run auto-mapping on page load
            autoMapFields();
        });
    </script>
</body>
</html>