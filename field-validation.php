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
    <title>JPI Systems - Field Validation</title>
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
                                <h3>Field Validation</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Validation</li>
                                    <li class="breadcrumb-item active">Field Validation</li>
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
                                    <h4>Field Validation Rules by File Type</h4>
                                    <div class="float-end">
                                        <select class="form-control form-control-sm" id="fileTypeFilter" style="width: 200px; display: inline-block;">
                                            <option value="">All File Types</option>
                                            <?php
                                            $conn = new db_connect();
                                            $conn = $conn->connect();
                                            $stmt = $conn->prepare("SELECT id, name FROM file_types WHERE is_active = 1 ORDER BY name");
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                            }
                                            $stmt->close();
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="fieldValidationTable" role="grid" aria-describedby="fieldValidationTable_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>File Type</th>
                                                    <th>Section</th>
                                                    <th>Field Name</th>
                                                    <th>Display Name</th>
                                                    <th>Data Type</th>
                                                    <th>Mandatory</th>
                                                    <th>Length/Format</th>
                                                    <th>Default Value</th>
                                                    <th>Validation</th>
                                                    <th>Example</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $validation_mgmt = new validation_management();
                                                
                                                // Get all file types
                                                $conn = new db_connect();
                                                $conn = $conn->connect();
                                                $stmt = $conn->prepare("SELECT id, name, code FROM file_types WHERE is_active = 1 ORDER BY name");
                                                $stmt->execute();
                                                $file_types_result = $stmt->get_result();
                                                
                                                $all_fields = [];
                                                
                                                while ($file_type = $file_types_result->fetch_assoc()) {
                                                    // Get file schema for this file type
                                                    $schema_stmt = $conn->prepare("
                                                        SELECT * FROM file_schema 
                                                        WHERE file_type_id = ? 
                                                        ORDER BY section, field_order
                                                    ");
                                                    $schema_stmt->bind_param('i', $file_type['id']);
                                                    $schema_stmt->execute();
                                                    $schema_result = $schema_stmt->get_result();
                                                    
                                                    while ($field = $schema_result->fetch_assoc()) {
                                                        $field['file_type_name'] = $file_type['name'];
                                                        $field['file_type_code'] = $file_type['code'];
                                                        $all_fields[] = $field;
                                                    }
                                                    $schema_stmt->close();
                                                }
                                                $stmt->close();
                                                $conn->close();
                                                
                                                if (empty($all_fields)) {
                                                    echo '<tr><td colspan="10" class="text-center">No field validation rules found.</td></tr>';
                                                } else {
                                                    foreach ($all_fields as $field) {
                                                        $mandatory_badge = $field['mandatory'] ? 
                                                            '<span class="badge badge-danger">Required</span>' : 
                                                            '<span class="badge badge-secondary">Optional</span>';
                                                        
                                                        $section_badge = $field['section'] == 'header' ? 'badge-info' : 
                                                                        ($field['section'] == 'body' ? 'badge-primary' : 'badge-secondary');
                                                        
                                                        $data_type_badge = 'badge-light';
                                                        switch ($field['data_type']) {
                                                            case 'string': $data_type_badge = 'badge-info'; break;
                                                            case 'number': $data_type_badge = 'badge-success'; break;
                                                            case 'decimal': $data_type_badge = 'badge-warning'; break;
                                                            case 'date': $data_type_badge = 'badge-danger'; break;
                                                        }
                                                        
                                                        $length_info = '';
                                                        if ($field['length']) {
                                                            $length_info .= 'Max: ' . $field['length'];
                                                            if ($field['decimal_places'] > 0) {
                                                                $length_info .= '.' . $field['decimal_places'];
                                                            }
                                                            $length_info .= ' chars';
                                                        }
                                                        
                                                        $validation_info = '';
                                                        if ($field['validation_regex']) {
                                                            $validation_info = '<code title="' . htmlspecialchars($field['validation_regex']) . '">' . 
                                                                               substr($field['validation_regex'], 0, 20) . 
                                                                               (strlen($field['validation_regex']) > 20 ? '...' : '') . 
                                                                               '</code>';
                                                        }
                                                        
                                                        ?>
                                                        <tr data-file-type="<?php echo $field['file_type_code']; ?>">
                                                            <td><?php echo htmlspecialchars($field['file_type_name']); ?></td>
                                                            <td><span class="badge <?php echo $section_badge; ?>"><?php echo ucfirst($field['section']); ?></span></td>
                                                            <td><code><?php echo htmlspecialchars($field['field_name']); ?></code></td>
                                                            <td><?php echo htmlspecialchars($field['display_name']); ?></td>
                                                            <td><span class="badge <?php echo $data_type_badge; ?>"><?php echo ucfirst($field['data_type']); ?></span></td>
                                                            <td><?php echo $mandatory_badge; ?></td>
                                                            <td><?php echo $length_info; ?></td>
                                                            <td><?php echo $field['default_value'] ? htmlspecialchars($field['default_value']) : '<em>None</em>'; ?></td>
                                                            <td><?php echo $validation_info ?: '<em>None</em>'; ?></td>
                                                            <td><?php echo $field['example_value'] ? htmlspecialchars(substr($field['example_value'], 0, 30)) : '<em>None</em>'; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Validation Test Section -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Test Field Validation</h4>
                                </div>
                                <div class="card-body">
                                    <form id="testValidationForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Select File Type</label>
                                                    <select class="form-control" id="test_file_type" name="test_file_type" required>
                                                        <option value="">-- Select File Type --</option>
                                                        <?php
                                                        $conn = new db_connect();
                                                        $conn = $conn->connect();
                                                        $stmt = $conn->prepare("SELECT id, name FROM file_types WHERE is_active = 1 ORDER BY name");
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                        }
                                                        $stmt->close();
                                                        $conn->close();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Select Field</label>
                                                    <select class="form-control" id="test_field" name="test_field" required disabled>
                                                        <option value="">-- Select File Type First --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">Test Value</label>
                                                    <input class="form-control" type="text" id="test_value" name="test_value" 
                                                           placeholder="Enter value to test">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="text-end">
                                                        <button class="btn btn-primary" type="button" id="testValidationBtn">
                                                            <i class="fa fa-vial"></i> Test Validation
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row d-none" id="testResultRow">
                                            <div class="col-md-12">
                                                <div class="alert" id="testResultAlert">
                                                    <h6>Test Result:</h6>
                                                    <div id="testResultContent"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
            var table = $('#fieldValidationTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                pageLength: 25,
                responsive: true
            });
            
            // File type filter
            $('#fileTypeFilter').change(function () {
                var fileType = $(this).val();
                if (fileType) {
                    // Get file type code
                    var fileTypeCode = '';
                    $('#fileTypeFilter option:selected').each(function() {
                        fileTypeCode = $(this).text().toLowerCase().replace(/\s+/g, '-');
                    });
                    
                    if (fileTypeCode) {
                        table.columns(0).search(fileTypeCode).draw();
                    }
                } else {
                    table.columns(0).search('').draw();
                }
            });
            
            // Load fields when file type is selected
            $('#test_file_type').change(function () {
                var fileTypeId = $(this).val();
                $('#test_field').prop('disabled', !fileTypeId);
                
                if (fileTypeId) {
                    $.ajax({
                        type: "POST",
                        url: "./php/validation_handler.php",
                        data: {
                            get_field_rules: '1',
                            file_type_id: fileTypeId
                        },
                        success: function (response) {
                            try {
                                var res = JSON.parse(response);
                                var $fieldSelect = $('#test_field');
                                $fieldSelect.empty();
                                
                                if (res.id == 1 && res.fields && res.fields.length > 0) {
                                    $fieldSelect.append('<option value="">-- Select Field --</option>');
                                    $.each(res.fields, function(index, field) {
                                        $fieldSelect.append('<option value="' + field.field_name + '" data-validation="' + 
                                                          (field.validation_regex || '') + '" data-datatype="' + field.data_type + 
                                                          '" data-mandatory="' + field.mandatory + '">' + 
                                                          field.display_name + ' (' + field.field_name + ')</option>');
                                    });
                                } else {
                                    $fieldSelect.append('<option value="">No fields found</option>');
                                }
                            } catch (e) {
                                console.error("Error parsing response:", e, response);
                                $('#test_field').empty().append('<option value="">Error loading fields</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX error:", error);
                            $('#test_field').empty().append('<option value="">Error loading fields</option>');
                        }
                    });
                } else {
                    $('#test_field').empty().append('<option value="">-- Select File Type First --</option>');
                }
                
                // Hide previous results
                $('#testResultRow').addClass('d-none');
            });
            
            // Test validation button
            $('#testValidationBtn').click(function () {
                var fileTypeId = $('#test_file_type').val();
                var fieldName = $('#test_field').val();
                var testValue = $('#test_value').val();
                var selectedField = $('#test_field option:selected');
                
                if (!fileTypeId || !fieldName) {
                    Swal.fire({
                        title: "Missing Information",
                        text: "Please select both file type and field",
                        icon: "warning"
                    });
                    return;
                }
                
                var validationRegex = selectedField.data('validation');
                var dataType = selectedField.data('datatype');
                var isMandatory = selectedField.data('mandatory') == '1';
                
                // Perform validation
                var result = {
                    valid: true,
                    messages: [],
                    details: {
                        field: fieldName,
                        value: testValue,
                        data_type: dataType,
                        mandatory: isMandatory,
                        validation_regex: validationRegex
                    }
                };
                
                // Check mandatory field
                if (isMandatory && (!testValue || testValue.trim() === '')) {
                    result.valid = false;
                    result.messages.push('This field is mandatory and cannot be empty');
                }
                
                // Check data type
                if (testValue && testValue.trim() !== '') {
                    switch (dataType) {
                        case 'number':
                            if (!/^-?\d+$/.test(testValue.trim())) {
                                result.valid = false;
                                result.messages.push('Value must be a valid integer number');
                            }
                            break;
                            
                        case 'decimal':
                            if (!/^-?\d+(\.\d+)?$/.test(testValue.trim())) {
                                result.valid = false;
                                result.messages.push('Value must be a valid decimal number');
                            }
                            break;
                            
                        case 'date':
                            // Simple date validation (can be enhanced)
                            if (!/^\d{4}-\d{2}-\d{2}$/.test(testValue.trim()) && 
                                !/^\d{2}\.\d{2}\.\d{4}$/.test(testValue.trim())) {
                                result.valid = false;
                                result.messages.push('Value must be in date format (YYYY-MM-DD or DD.MM.YYYY)');
                            }
                            break;
                    }
                }
                
                // Check regex validation
                if (validationRegex && testValue && testValue.trim() !== '') {
                    try {
                        var regex = new RegExp(validationRegex);
                        if (!regex.test(testValue)) {
                            result.valid = false;
                            result.messages.push('Value does not match validation pattern');
                        }
                    } catch (e) {
                        result.messages.push('Invalid validation regex pattern');
                    }
                }
                
                // Display results
                var $resultRow = $('#testResultRow');
                var $resultAlert = $('#testResultAlert');
                var $resultContent = $('#testResultContent');
                
                if (result.valid) {
                    $resultAlert.removeClass('alert-danger').addClass('alert-success');
                    $resultAlert.find('h6').html('<i class="fa fa-check-circle"></i> Validation Passed');
                } else {
                    $resultAlert.removeClass('alert-success').addClass('alert-danger');
                    $resultAlert.find('h6').html('<i class="fa fa-times-circle"></i> Validation Failed');
                }
                
                var contentHtml = '<div class="row">';
                contentHtml += '<div class="col-md-6">';
                contentHtml += '<strong>Field:</strong> ' + result.details.field + '<br>';
                contentHtml += '<strong>Data Type:</strong> ' + result.details.data_type + '<br>';
                contentHtml += '<strong>Mandatory:</strong> ' + (result.details.mandatory ? 'Yes' : 'No') + '<br>';
                contentHtml += '</div>';
                contentHtml += '<div class="col-md-6">';
                contentHtml += '<strong>Test Value:</strong> ' + (testValue || '<em>Empty</em>') + '<br>';
                contentHtml += '<strong>Regex:</strong> ' + (result.details.validation_regex || '<em>None</em>') + '<br>';
                contentHtml += '</div>';
                contentHtml += '</div>';
                
                if (result.messages.length > 0) {
                    contentHtml += '<hr><strong>Messages:</strong><ul>';
                    $.each(result.messages, function(index, message) {
                        contentHtml += '<li>' + message + '</li>';
                    });
                    contentHtml += '</ul>';
                } else {
                    contentHtml += '<hr><p class="mb-0"><strong>✓ All validation checks passed</strong></p>';
                }
                
                $resultContent.html(contentHtml);
                $resultRow.removeClass('d-none');
            });
        });
    </script>
</body>
</html>