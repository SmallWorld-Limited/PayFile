<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

// Get template ID from URL or default to payment template
$template_id = isset($_GET['id']) ? intval($_GET['id']) : 1; // Default to payment template (ID 1)
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
    <title>JPI Systems - Payment File Template</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
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
                                <h3>Payment File Template</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="./templates.php">File Templates</a></li>
                                    <li class="breadcrumb-item active">Payment File Template</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <?php
                    $templateManager = new template_manager();
                    $template = $templateManager->get_template_details($template_id);
                    
                    if (!$template): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="fa fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                                    <h4>Template Not Found</h4>
                                    <p>The requested template could not be found.</p>
                                    <a href="./templates.php" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i> Back to Templates
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4><?php echo htmlspecialchars($template['name']); ?></h4>
                                            <p class="mb-0 text-muted"><?php echo htmlspecialchars($template['description'] ?? ''); ?></p>
                                        </div>
                                        <div>
                                            <a href="./templates.php" class="btn btn-light">
                                                <i class="fa fa-arrow-left"></i> Back
                                            </a>
                                            <button type="button" class="btn btn-primary" id="saveTemplate">
                                                <i class="fa fa-save"></i> Save Template
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Template Info -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="template-meta">
                                                                <h6><i class="fa fa-code"></i> Template Code</h6>
                                                                <p><code><?php echo htmlspecialchars($template['code']); ?></code></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="template-meta">
                                                                <h6><i class="fa fa-file"></i> File Format</h6>
                                                                <p><?php echo htmlspecialchars($template['file_format'] ?? 'CSV'); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="template-meta">
                                                                <h6><i class="fa fa-columns"></i> Delimiter</h6>
                                                                <p><kbd><?php echo htmlspecialchars($template['delimiter'] ?? ';'); ?></kbd></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="template-meta">
                                                                <h6><i class="fa fa-list"></i> Total Fields</h6>
                                                                <p><?php echo count($template['fields'] ?? []); ?> fields</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Fields Editor -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Template Fields</h5>
                                                    <p class="mb-0 text-muted">Define the structure of your payment file template</p>
                                                </div>
                                                <div class="card-body">
                                                    <form id="templateFieldsForm">
                                                        <input type="hidden" name="template_id" value="<?php echo $template_id; ?>">
                                                        
                                                        <div class="table-responsive theme-scrollbar">
                                                            <table class="table table-bordered" id="fieldsTable">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th width="50">#</th>
                                                                        <th width="100">Section</th>
                                                                        <th>Field Name</th>
                                                                        <th>Display Name</th>
                                                                        <th width="100">Data Type</th>
                                                                        <th width="80">Length</th>
                                                                        <th width="80">Mandatory</th>
                                                                        <th width="100">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="fieldsTableBody">
                                                                    <?php if (!empty($template['fields'])): 
                                                                        $field_index = 1;
                                                                        foreach ($template['fields'] as $field): ?>
                                                                    <tr data-field-id="<?php echo $field['id']; ?>">
                                                                        <td><?php echo $field_index++; ?></td>
                                                                        <td>
                                                                            <select class="form-control form-control-sm section-select" name="section[]">
                                                                                <option value="header" <?php echo $field['section'] == 'header' ? 'selected' : ''; ?>>Header</option>
                                                                                <option value="body" <?php echo $field['section'] == 'body' ? 'selected' : ''; ?>>Body</option>
                                                                                <option value="footer" <?php echo $field['section'] == 'footer' ? 'selected' : ''; ?>>Footer</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control form-control-sm" name="field_name[]" value="<?php echo htmlspecialchars($field['field_name']); ?>" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control form-control-sm" name="display_name[]" value="<?php echo htmlspecialchars($field['display_name']); ?>" required>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control form-control-sm data-type-select" name="data_type[]">
                                                                                <option value="string" <?php echo $field['data_type'] == 'string' ? 'selected' : ''; ?>>String</option>
                                                                                <option value="number" <?php echo $field['data_type'] == 'number' ? 'selected' : ''; ?>>Number</option>
                                                                                <option value="decimal" <?php echo $field['data_type'] == 'decimal' ? 'selected' : ''; ?>>Decimal</option>
                                                                                <option value="date" <?php echo $field['data_type'] == 'date' ? 'selected' : ''; ?>>Date</option>
                                                                                <option value="boolean" <?php echo $field['data_type'] == 'boolean' ? 'selected' : ''; ?>>Boolean</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" class="form-control form-control-sm" name="length[]" value="<?php echo $field['length'] ?? ''; ?>">
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" name="mandatory[]" value="1" <?php echo $field['mandatory'] ? 'checked' : ''; ?>>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button type="button" class="btn btn-sm btn-danger remove-field">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; else: ?>
                                                                    <tr id="no-fields">
                                                                        <td colspan="8" class="text-center text-muted py-4">
                                                                            No fields defined. Add your first field to get started.
                                                                        </td>
                                                                    </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">
                                                                            <button type="button" class="btn btn-sm btn-primary" id="addField">
                                                                                <i class="fa fa-plus"></i> Add Field
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                        
                                                        <!-- Field Details (for advanced settings) -->
                                                        <div class="row mt-4">
                                                            <div class="col-md-12">
                                                                <h6>Advanced Field Settings</h6>
                                                                <div class="row" id="fieldDetailsContainer">
                                                                    <!-- Field details will be shown here when a field is selected -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview Section -->
                                    <div class="row mt-4">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Template Preview</h5>
                                                    <button type="button" class="btn btn-sm btn-info" id="generatePreview">
                                                        <i class="fa fa-refresh"></i> Generate Preview
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Header Format</h6>
                                                            <pre class="bg-light p-3 border rounded" id="headerPreview"></pre>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Body Format</h6>
                                                            <pre class="bg-light p-3 border rounded" id="bodyPreview"></pre>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <h6>Sample Data</h6>
                                                            <div class="card border">
                                                                <div class="card-body">
                                                                    <?php
                                                                    $sample = $templateManager->get_template_sample($template_id);
                                                                    if ($sample): ?>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <strong>Sample Header:</strong>
                                                                            <pre class="mt-2"><?php echo htmlspecialchars($sample['header']); ?></pre>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>Sample Body:</strong>
                                                                            <pre class="mt-2"><?php echo htmlspecialchars($sample['body']); ?></pre>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <strong>Description:</strong>
                                                                        <p><?php echo htmlspecialchars($sample['description']); ?></p>
                                                                    </div>
                                                                    <?php else: ?>
                                                                    <p class="text-muted">No sample data available. Save the template first to generate sample data.</p>
                                                                    <?php endif; ?>
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
                    </div>
                    <?php endif; ?>
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    
    <!-- Add Field Modal -->
    <div class="modal fade" id="addFieldModal" tabindex="-1" role="dialog" aria-labelledby="addFieldModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFieldModalLabel">Add New Field</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addFieldForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">Section</label>
                            <select class="form-control" name="section" required>
                                <option value="header">Header</option>
                                <option value="body">Body</option>
                                <option value="footer">Footer</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-form-label">Field Name *</label>
                            <input type="text" class="form-control" name="field_name" required placeholder="e.g., trans_serial">
                            <small class="form-text text-muted">Internal field identifier (no spaces)</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-form-label">Display Name *</label>
                            <input type="text" class="form-control" name="display_name" required placeholder="e.g., Transaction Serial">
                            <small class="form-text text-muted">User-friendly name</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Data Type</label>
                                    <select class="form-control" name="data_type" required>
                                        <option value="string">String</option>
                                        <option value="number">Number</option>
                                        <option value="decimal">Decimal</option>
                                        <option value="date">Date</option>
                                        <option value="boolean">Boolean</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Length</label>
                                    <input type="number" class="form-control" name="length" placeholder="Max characters">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Field Order</label>
                                    <input type="number" class="form-control" name="field_order" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Decimal Places</label>
                                    <input type="number" class="form-control" name="decimal_places" value="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mandatory" value="1" id="isMandatory">
                                <label class="form-check-label" for="isMandatory">
                                    Mandatory Field
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-form-label">Default Value</label>
                            <input type="text" class="form-control" name="default_value" placeholder="Default value for this field">
                        </div>
                        
                        <div class="form-group">
                            <label class="col-form-label">Validation Regex</label>
                            <input type="text" class="form-control" name="validation_regex" placeholder="e.g., ^[0-9]{10,20}$">
                        </div>
                        
                        <div class="form-group">
                            <label class="col-form-label">Comments</label>
                            <textarea class="form-control" name="comments" rows="2" placeholder="Additional comments about this field"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Add Field</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function () {
            var fieldCounter = <?php echo isset($field_index) ? $field_index : 1; ?>;
            
            // Add new field row
            $(document).on('click', '#addField', function (e) {
                e.preventDefault();
                $('#addFieldModal').modal('show');
            });
            
            // Handle add field form submission
            $('#addFieldForm').submit(function (e) {
                e.preventDefault();
                
                var formData = $(this).serializeArray();
                var data = {};
                formData.forEach(function(item) {
                    data[item.name] = item.value;
                });
                
                // Remove the "no fields" row if it exists
                if ($('#no-fields').length) {
                    $('#no-fields').remove();
                }
                
                // Add new row to table
                var newRow = `
                <tr data-new-field="true">
                    <td>${fieldCounter++}</td>
                    <td>
                        <select class="form-control form-control-sm section-select" name="section[]">
                            <option value="header" ${data.section == 'header' ? 'selected' : ''}>Header</option>
                            <option value="body" ${data.section == 'body' ? 'selected' : ''}>Body</option>
                            <option value="footer" ${data.section == 'footer' ? 'selected' : ''}>Footer</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="field_name[]" value="${data.field_name}" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="display_name[]" value="${data.display_name}" required>
                    </td>
                    <td>
                        <select class="form-control form-control-sm data-type-select" name="data_type[]">
                            <option value="string" ${data.data_type == 'string' ? 'selected' : ''}>String</option>
                            <option value="number" ${data.data_type == 'number' ? 'selected' : ''}>Number</option>
                            <option value="decimal" ${data.data_type == 'decimal' ? 'selected' : ''}>Decimal</option>
                            <option value="date" ${data.data_type == 'date' ? 'selected' : ''}>Date</option>
                            <option value="boolean" ${data.data_type == 'boolean' ? 'selected' : ''}>Boolean</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="length[]" value="${data.length || ''}">
                    </td>
                    <td class="text-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="mandatory[]" value="1" ${data.mandatory == '1' ? 'checked' : ''}>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-field">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                
                $('#fieldsTableBody').append(newRow);
                $('#addFieldModal').modal('hide');
                $('#addFieldForm')[0].reset();
                
                Swal.fire({
                    title: "Success!",
                    text: "Field added successfully",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });
            });
            
            // Remove field row
            $(document).on('click', '.remove-field', function (e) {
                e.preventDefault();
                var row = $(this).closest('tr');
                var fieldId = row.data('field-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove the field from the template",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If field has an ID (exists in database), we need to mark it for deletion
                        if (fieldId) {
                            row.addClass('deleted-field');
                            row.hide();
                        } else {
                            row.remove();
                        }
                        
                        // Update row numbers
                        updateRowNumbers();
                        
                        Swal.fire({
                            title: "Removed!",
                            text: "Field removed successfully",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
            
            // Update row numbers
            function updateRowNumbers() {
                $('#fieldsTableBody tr:visible').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }
            
            // Generate preview
            $(document).on('click', '#generatePreview', function (e) {
                e.preventDefault();
                
                var headerFields = [];
                var bodyFields = [];
                
                $('#fieldsTableBody tr:visible').each(function() {
                    var section = $(this).find('.section-select').val();
                    var fieldName = $(this).find('input[name="field_name[]"]').val();
                    var dataType = $(this).find('.data-type-select').val();
                    var length = $(this).find('input[name="length[]"]').val();
                    var mandatory = $(this).find('input[name="mandatory[]"]').is(':checked') ? 'Yes' : 'No';
                    
                    var fieldInfo = {
                        name: fieldName,
                        type: dataType,
                        length: length,
                        mandatory: mandatory
                    };
                    
                    if (section == 'header') {
                        headerFields.push(fieldInfo);
                    } else if (section == 'body') {
                        bodyFields.push(fieldInfo);
                    }
                });
                
                // Generate header preview
                var headerHtml = '';
                if (headerFields.length > 0) {
                    headerHtml += '<strong>Header Fields:</strong><br>';
                    headerFields.forEach(function(field, index) {
                        headerHtml += `${index + 1}. ${field.name} (${field.type}${field.length ? ', max ' + field.length + ' chars' : ''}) - Mandatory: ${field.mandatory}<br>`;
                    });
                } else {
                    headerHtml = 'No header fields defined';
                }
                
                // Generate body preview
                var bodyHtml = '';
                if (bodyFields.length > 0) {
                    bodyHtml += '<strong>Body Fields:</strong><br>';
                    bodyFields.forEach(function(field, index) {
                        bodyHtml += `${index + 1}. ${field.name} (${field.type}${field.length ? ', max ' + field.length + ' chars' : ''}) - Mandatory: ${field.mandatory}<br>`;
                    });
                } else {
                    bodyHtml = 'No body fields defined';
                }
                
                $('#headerPreview').html(headerHtml);
                $('#bodyPreview').html(bodyHtml);
            });
            
            // Save template
            $(document).on('click', '#saveTemplate', function (e) {
                e.preventDefault();
                
                // Collect all form data
                var formData = new FormData();
                formData.append('template_id', '<?php echo $template_id; ?>');
                formData.append('action', 'save_template');
                
                // Get all fields data
                var fields = [];
                $('#fieldsTableBody tr:visible').each(function(index) {
                    var fieldId = $(this).data('field-id') || 'new';
                    var section = $(this).find('.section-select').val();
                    var fieldName = $(this).find('input[name="field_name[]"]').val();
                    var displayName = $(this).find('input[name="display_name[]"]').val();
                    var dataType = $(this).find('.data-type-select').val();
                    var length = $(this).find('input[name="length[]"]').val();
                    var mandatory = $(this).find('input[name="mandatory[]"]').is(':checked') ? 1 : 0;
                    
                    fields.push({
                        field_id: fieldId,
                        section: section,
                        field_name: fieldName,
                        display_name: displayName,
                        data_type: dataType,
                        length: length,
                        mandatory: mandatory,
                        field_order: index + 1
                    });
                });
                
                formData.append('fields', JSON.stringify(fields));
                
                // Get deleted fields
                var deletedFields = [];
                $('#fieldsTableBody tr.deleted-field').each(function() {
                    var fieldId = $(this).data('field-id');
                    if (fieldId) {
                        deletedFields.push(fieldId);
                    }
                });
                formData.append('deleted_fields', JSON.stringify(deletedFields));
                
                $.ajax({
                    type: "POST",
                    url: "./php/template_handler.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#saveTemplate').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                    },
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: res.message,
                                    icon: "success"
                                });
                                
                                // Remove deleted fields from DOM
                                $('#fieldsTableBody tr.deleted-field').remove();
                                
                                // Update field IDs for newly added fields
                                if (res.field_ids) {
                                    Object.keys(res.field_ids).forEach(function(rowIndex) {
                                        var row = $('#fieldsTableBody tr:visible').eq(rowIndex);
                                        if (row.length) {
                                            row.data('field-id', res.field_ids[rowIndex]);
                                        }
                                    });
                                }
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: res.message,
                                    icon: "error"
                                });
                            }
                        } catch (e) {
                            Swal.fire({
                                title: "Error!",
                                text: "Invalid response from server",
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to save template",
                            icon: "error"
                        });
                    },
                    complete: function() {
                        $('#saveTemplate').prop('disabled', false).html('<i class="fa fa-save"></i> Save Template');
                    }
                });
            });
        });
    </script>
</body>
</html>