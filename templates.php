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
    <title>JPI Systems - File Templates</title>
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
                                <h3>File Templates</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Templates</li>
                                    <li class="breadcrumb-item active">View Templates</li>
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
                                    <h4>All File Templates</h4>
                                    <div class="float-end">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                                            <i class="fa fa-plus"></i> Add New Template
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                        $templateManager = new template_manager();
                                        $templates = $templateManager->get_all_templates();
                                        
                                        if (empty($templates)): ?>
                                            <div class="col-12 text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fa fa-file-alt fa-4x text-muted mb-3"></i>
                                                    <h4>No Templates Found</h4>
                                                    <p>Start by creating your first file template</p>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                                                        <i class="fa fa-plus"></i> Add First Template
                                                    </button>
                                                </div>
                                            </div>
                                        <?php else: 
                                            foreach ($templates as $template): 
                                                $usage_count = $template['usage_count'] ?? 0;
                                                $field_count = $template['field_count'] ?? 0;
                                                $is_active = $template['is_active'] ?? 1;
                                                
                                                // Determine card color based on template type
                                                $card_class = '';
                                                $icon_class = 'fa-file-alt';
                                                $icon_color = 'text-primary';
                                                
                                                if (strpos($template['code'], 'PAYMENT') !== false) {
                                                    $card_class = 'border-primary';
                                                    $icon_class = 'fa-money-bill-wave';
                                                    $icon_color = 'text-primary';
                                                } elseif (strpos($template['code'], 'REMITTANCE') !== false) {
                                                    $card_class = 'border-success';
                                                    $icon_class = 'fa-exchange-alt';
                                                    $icon_color = 'text-success';
                                                } elseif (strpos($template['code'], 'SALARY') !== false) {
                                                    $card_class = 'border-warning';
                                                    $icon_class = 'fa-user-tie';
                                                    $icon_color = 'text-warning';
                                                } elseif (strpos($template['code'], 'FOREIGN') !== false) {
                                                    $card_class = 'border-info';
                                                    $icon_class = 'fa-globe';
                                                    $icon_color = 'text-info';
                                                }
                                        ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                            <div class="card h-100 <?php echo $card_class; ?>">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div class="template-icon <?php echo $icon_color; ?>">
                                                            <i class="fa <?php echo $icon_class; ?> fa-2x"></i>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item view-template" href="javascript:void(0)" data-id="<?php echo $template['id']; ?>"><i class="fa fa-eye"></i> View Details</a></li>
                                                                <li><a class="dropdown-item" href="./template-view.php?id=<?php echo $template['id']; ?>"><i class="fa fa-edit"></i> Edit Template</a></li>
                                                                <li><a class="dropdown-item" href="./template-preview.php?id=<?php echo $template['id']; ?>"><i class="fa fa-file-code"></i> View Structure</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger delete-template" href="javascript:void(0)" data-id="<?php echo $template['id']; ?>"><i class="fa fa-trash"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    
                                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($template['name']); ?></h5>
                                                    <p class="card-text text-muted small mb-3">
                                                        <?php echo htmlspecialchars($template['description'] ?? 'No description'); ?>
                                                    </p>
                                                    
                                                    <div class="template-meta d-flex justify-content-between mb-3">
                                                        <div class="meta-item text-center">
                                                            <div class="meta-value"><?php echo $field_count; ?></div>
                                                            <div class="meta-label text-muted small">Fields</div>
                                                        </div>
                                                        <div class="meta-item text-center">
                                                            <div class="meta-value"><?php echo $usage_count; ?></div>
                                                            <div class="meta-label text-muted small">Usage</div>
                                                        </div>
                                                        <div class="meta-item text-center">
                                                            <div class="meta-value">
                                                                <?php if ($is_active): ?>
                                                                    <span class="badge badge-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-danger">Inactive</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="meta-label text-muted small">Status</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="template-info small text-muted mb-3">
                                                        <div class="mb-1">
                                                            <i class="fa fa-code"></i> Code: <code><?php echo htmlspecialchars($template['code']); ?></code>
                                                        </div>
                                                        <div class="mb-1">
                                                            <i class="fa fa-file"></i> Format: <?php echo htmlspecialchars($template['file_format'] ?? 'CSV'); ?>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-columns"></i> Delimiter: <kbd><?php echo htmlspecialchars($template['delimiter'] ?? ';'); ?></kbd>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-grid gap-2">
                                                        <a href="./template-view.php?id=<?php echo $template['id']; ?>" class="btn btn-outline-primary">
                                                            <i class="fa fa-edit"></i> Edit Template
                                                        </a>
                                                        <a href="./template-preview.php?id=<?php echo $template['id']; ?>" class="btn btn-outline-secondary">
                                                            <i class="fa fa-file-code"></i> View Structure
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; endif; ?>
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
    
    <!-- Add Template Modal -->
    <div class="modal fade" id="addTemplateModal" tabindex="-1" role="dialog" aria-labelledby="addTemplateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTemplateModalLabel">Add New Template</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addTemplateForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Template Code *</label>
                                    <input type="text" class="form-control" name="code" required placeholder="e.g., OBDX_PAYMENT">
                                    <small class="form-text text-muted">Unique identifier for the template</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Template Name *</label>
                                    <input type="text" class="form-control" name="name" required placeholder="e.g., OBDX Payment File">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="2" placeholder="Describe this template..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-form-label">File Format</label>
                                    <select class="form-control" name="file_format">
                                        <option value="CSV">CSV</option>
                                        <option value="TXT">TXT</option>
                                        <option value="SAGE">SAGE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-form-label">Delimiter</label>
                                    <input type="text" class="form-control" name="delimiter" value=";" maxlength="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-form-label">Has Header?</label>
                                    <select class="form-control" name="has_header">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="isActive">
                                        <label class="form-check-label" for="isActive">
                                            Active Template
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Create Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Template Details Modal -->
    <div class="modal fade" id="templateDetailsModal" tabindex="-1" role="dialog" aria-labelledby="templateDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="templateDetailsModalLabel">Template Details</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="templateDetailsContent">
                    <!-- Template details will be loaded here -->
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
            // Handle add template form submission
            $('#addTemplateForm').submit(function (e) {
                e.preventDefault();
                
                var formData = $(this).serialize();
                
                $.ajax({
                    type: "POST",
                    url: "./php/template_handler.php",
                    data: formData + '&action=add',
                    beforeSend: function() {
                        $('#addTemplateForm button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
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
                                $('#addTemplateModal').modal('hide');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
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
                            text: "Failed to create template",
                            icon: "error"
                        });
                    },
                    complete: function() {
                        $('#addTemplateForm button[type="submit"]').prop('disabled', false).html('Create Template');
                    }
                });
            });
            
            // Handle view template details
            $(document).on('click', '.view-template', function (e) {
                e.preventDefault();
                var templateId = $(this).data('id');
                
                $.ajax({
                    type: "POST",
                    url: "./php/template_handler.php",
                    data: {
                        action: 'get_details',
                        template_id: templateId
                    },
                    beforeSend: function() {
                        $('#templateDetailsContent').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
                    },
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                $('#templateDetailsContent').html(res.html);
                                $('#templateDetailsModal').modal('show');
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
                                text: "Failed to load template details",
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to load template details",
                            icon: "error"
                        });
                    }
                });
            });
            
            // Handle delete template
            $(document).on('click', '.delete-template', function (e) {
                e.preventDefault();
                var templateId = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the template and all associated fields. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "./php/template_handler.php",
                            data: {
                                action: 'delete',
                                template_id: templateId
                            },
                            success: function (response) {
                                try {
                                    var res = JSON.parse(response);
                                    if (res.success) {
                                        Swal.fire({
                                            title: "Deleted!",
                                            text: res.message,
                                            icon: "success"
                                        });
                                        setTimeout(() => {
                                            location.reload();
                                        }, 1500);
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
                                        text: "Failed to delete template",
                                        icon: "error"
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to delete template",
                                    icon: "error"
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