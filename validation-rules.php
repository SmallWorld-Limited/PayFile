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
    <title>JPI Systems - Validation Rules</title>
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
                                <h3>Validation Rules</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Validation</li>
                                    <li class="breadcrumb-item active">Rules</li>
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
                                    <h4>Validation Rules Management</h4>
                                    <div class="float-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRuleModal">
                                            <i class="fa fa-plus"></i> Add New Rule
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="export-button" role="grid" aria-describedby="export-button_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>ID</th>
                                                    <th>Rule Name</th>
                                                    <th>Type</th>
                                                    <th>Condition</th>
                                                    <th>Error Message</th>
                                                    <th>Severity</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $validation_mgmt = new validation_management();
                                                $rules = $validation_mgmt->get_all_rules();
                                                
                                                if (empty($rules)) {
                                                    echo '<tr><td colspan="8" class="text-center">No validation rules found.</td></tr>';
                                                } else {
                                                    foreach ($rules as $rule) {
                                                        $severity_badge = $rule['severity'] == 'error' ? 'badge-danger' : 'badge-warning';
                                                        $status_badge = $rule['is_active'] ? 'badge-success' : 'badge-secondary';
                                                        $type_badge = $rule['rule_type'] == 'field' ? 'badge-info' : 
                                                                     ($rule['rule_type'] == 'batch' ? 'badge-primary' : 'badge-secondary');
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $rule['id']; ?></td>
                                                            <td><?php echo htmlspecialchars($rule['rule_name']); ?></td>
                                                            <td><span class="badge <?php echo $type_badge; ?>"><?php echo ucfirst($rule['rule_type']); ?></span></td>
                                                            <td><code class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($rule['rule_condition']); ?>">
                                                                <?php echo htmlspecialchars(substr($rule['rule_condition'], 0, 50)); ?>
                                                                <?php if (strlen($rule['rule_condition']) > 50): ?>...<?php endif; ?>
                                                            </code></td>
                                                            <td><?php echo htmlspecialchars(substr($rule['error_message'], 0, 50)); ?>
                                                                <?php if (strlen($rule['error_message']) > 50): ?>...<?php endif; ?>
                                                            </td>
                                                            <td><span class="badge <?php echo $severity_badge; ?>"><?php echo ucfirst($rule['severity']); ?></span></td>
                                                            <td><span class="badge <?php echo $status_badge; ?>"><?php echo $rule['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-info view-rule" 
                                                                        data-id="<?php echo $rule['id']; ?>" title="View Details">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                
                                                                <?php if ($_SESSION["bt_role"] == 'admin'): ?>
                                                                    <button type="button" class="btn btn-sm btn-warning edit-rule" 
                                                                            data-id="<?php echo $rule['id']; ?>" title="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                    
                                                                    <button type="button" class="btn btn-sm btn-danger delete-rule" 
                                                                            data-id="<?php echo $rule['id']; ?>" title="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                    
                                                                    <button type="button" class="btn btn-sm btn-secondary test-rule" 
                                                                            data-id="<?php echo $rule['id']; ?>" 
                                                                            data-condition="<?php echo htmlspecialchars($rule['rule_condition']); ?>"
                                                                            title="Test Rule">
                                                                        <i class="fa fa-vial"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>
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
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    
    <!-- Add Rule Modal -->
    <div class="modal fade" id="addRuleModal" tabindex="-1" role="dialog" aria-labelledby="addRuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRuleModalLabel">Add Validation Rule</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addRuleForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Rule Name *</label>
                                    <input class="form-control" type="text" name="rule_name" required>
                                    <small class="form-text text-muted">Unique name for the rule</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Rule Type *</label>
                                    <select class="form-control" name="rule_type" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="field">Field Validation</option>
                                        <option value="batch">Batch Validation</option>
                                        <option value="business">Business Rule</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Rule Condition *</label>
                                    <textarea class="form-control" name="rule_condition" rows="3" required 
                                              placeholder="e.g., currency_code IN ('MWK', 'USD', 'EUR', 'GBP', 'ZAR')"></textarea>
                                    <small class="form-text text-muted">SQL condition or validation logic</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Error Message *</label>
                                    <input class="form-control" type="text" name="error_message" required 
                                           placeholder="e.g., Invalid currency code">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Severity</label>
                                    <select class="form-control" name="severity">
                                        <option value="error">Error</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Status</label>
                                    <select class="form-control" name="is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Add Rule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Rule Modal -->
    <div class="modal fade" id="editRuleModal" tabindex="-1" role="dialog" aria-labelledby="editRuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRuleModalLabel">Edit Validation Rule</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editRuleForm">
                    <input type="hidden" name="rule_id" id="edit_rule_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Rule Name *</label>
                                    <input class="form-control" type="text" name="rule_name" id="edit_rule_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Rule Type *</label>
                                    <select class="form-control" name="rule_type" id="edit_rule_type" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="field">Field Validation</option>
                                        <option value="batch">Batch Validation</option>
                                        <option value="business">Business Rule</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Rule Condition *</label>
                                    <textarea class="form-control" name="rule_condition" id="edit_rule_condition" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Error Message *</label>
                                    <input class="form-control" type="text" name="error_message" id="edit_error_message" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Severity</label>
                                    <select class="form-control" name="severity" id="edit_severity">
                                        <option value="error">Error</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Status</label>
                                    <select class="form-control" name="is_active" id="edit_is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Update Rule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Rule Modal -->
    <div class="modal fade" id="viewRuleModal" tabindex="-1" role="dialog" aria-labelledby="viewRuleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRuleModalLabel">Validation Rule Details</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Rule Name</label>
                                <p class="form-control-static" id="view_rule_name"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-form-label">Type</label>
                                <p class="form-control-static" id="view_rule_type"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-form-label">Severity</label>
                                <p class="form-control-static" id="view_severity"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Status</label>
                                <p class="form-control-static" id="view_status"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Created</label>
                                <p class="form-control-static" id="view_created_at"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Rule Condition</label>
                                <pre class="bg-light p-3 border rounded" id="view_rule_condition"></pre>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Error Message</label>
                                <p class="form-control-static" id="view_error_message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function () {
            // Handle add rule form submission
            $('#addRuleForm').submit(function (e) {
                e.preventDefault();
                
                $.ajax({
                    type: "POST",
                    url: "./php/validation_handler.php",
                    data: {
                        add_validation_rule: '1',
                        rule_name: $('input[name="rule_name"]').val(),
                        rule_type: $('select[name="rule_type"]').val(),
                        rule_condition: $('textarea[name="rule_condition"]').val(),
                        error_message: $('input[name="error_message"]').val(),
                        severity: $('select[name="severity"]').val(),
                        is_active: $('select[name="is_active"]').val()
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.id == 1) {
                            Swal.fire({
                                title: "Success!",
                                text: res.mssg,
                                icon: res.type
                            });
                            $('#addRuleModal').modal('hide');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: res.mssg,
                                icon: res.type
                            });
                        }
                    }
                });
            });
            
            // Handle edit rule form submission
            $('#editRuleForm').submit(function (e) {
                e.preventDefault();
                
                $.ajax({
                    type: "POST",
                    url: "./php/validation_handler.php",
                    data: {
                        edit_validation_rule: '1',
                        rule_id: $('#edit_rule_id').val(),
                        rule_name: $('#edit_rule_name').val(),
                        rule_type: $('#edit_rule_type').val(),
                        rule_condition: $('#edit_rule_condition').val(),
                        error_message: $('#edit_error_message').val(),
                        severity: $('#edit_severity').val(),
                        is_active: $('#edit_is_active').val()
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.id == 1) {
                            Swal.fire({
                                title: "Success!",
                                text: res.mssg,
                                icon: res.type
                            });
                            $('#editRuleModal').modal('hide');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: res.mssg,
                                icon: res.type
                            });
                        }
                    }
                });
            });
            
            // Handle view rule button
            $(document).on('click', '.view-rule', function (e) {
                e.preventDefault();
                var ruleId = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "./php/validation_handler.php",
                    data: {
                        get_rule_details: '1',
                        rule_id: ruleId
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.id == 1) {
                            var rule = res.rule;
                            
                            $('#view_rule_name').text(rule.rule_name);
                            $('#view_rule_type').text(rule.rule_type.charAt(0).toUpperCase() + rule.rule_type.slice(1));
                            $('#view_severity').html('<span class="badge ' + (rule.severity == 'error' ? 'badge-danger' : 'badge-warning') + '">' + 
                                                     rule.severity.charAt(0).toUpperCase() + rule.severity.slice(1) + '</span>');
                            $('#view_status').html('<span class="badge ' + (rule.is_active ? 'badge-success' : 'badge-secondary') + '">' + 
                                                   (rule.is_active ? 'Active' : 'Inactive') + '</span>');
                            $('#view_created_at').text(rule.created_at ? new Date(rule.created_at).toLocaleString() : 'N/A');
                            $('#view_rule_condition').text(rule.rule_condition);
                            $('#view_error_message').text(rule.error_message);
                            
                            $('#viewRuleModal').modal('show');
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: res.mssg,
                                icon: 'error'
                            });
                        }
                    }
                });
            });
            
            // Handle edit rule button
            $(document).on('click', '.edit-rule', function (e) {
                e.preventDefault();
                var ruleId = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "./php/validation_handler.php",
                    data: {
                        get_rule_details: '1',
                        rule_id: ruleId
                    },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res.id == 1) {
                            var rule = res.rule;
                            
                            $('#edit_rule_id').val(rule.id);
                            $('#edit_rule_name').val(rule.rule_name);
                            $('#edit_rule_type').val(rule.rule_type);
                            $('#edit_rule_condition').val(rule.rule_condition);
                            $('#edit_error_message').val(rule.error_message);
                            $('#edit_severity').val(rule.severity);
                            $('#edit_is_active').val(rule.is_active ? '1' : '0');
                            
                            $('#editRuleModal').modal('show');
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: res.mssg,
                                icon: 'error'
                            });
                        }
                    }
                });
            });
            
            // Handle delete rule button
            $(document).on('click', '.delete-rule', function (e) {
                e.preventDefault();
                var ruleId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/validation_handler.php",
                            data: {
                                delete_validation_rule: '1',
                                rule_id: ruleId
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                if (res.id == 1) {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: res.mssg,
                                        icon: res.type
                                    });
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: res.mssg,
                                        icon: res.type
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Handle test rule button
            $(document).on('click', '.test-rule', function (e) {
                e.preventDefault();
                var ruleCondition = $(this).data('condition');
                
                Swal.fire({
                    title: 'Test Rule',
                    text: 'Do you want to test this rule?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, test it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/validation_handler.php",
                            data: {
                                test_validation_rule: '1',
                                rule_condition: ruleCondition
                            },
                            success: function (response) {
                                var res = JSON.parse(response);
                                Swal.fire({
                                    title: res.id == 1 ? "Test Result" : "Test Failed",
                                    html: '<div class="text-left">' +
                                          '<strong>Result:</strong> ' + (res.test_result || res.mssg) + '<br>' +
                                          '<strong>Message:</strong> ' + res.mssg +
                                          '</div>',
                                    icon: res.type
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>