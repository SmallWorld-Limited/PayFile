<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$template_id = isset($_GET['id']) ? intval($_GET['id']) : 1;
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
    <title>JPI Systems - Template Preview</title>
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
                                <h3>Template Structure</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="./templates.php">File Templates</a></li>
                                    <li class="breadcrumb-item active">Template Structure</li>
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
                                            <h4><?php echo htmlspecialchars($template['name']); ?> - Field Structure</h4>
                                            <p class="mb-0 text-muted">Complete field definition and structure</p>
                                        </div>
                                        <div>
                                            <a href="./template-view.php?id=<?php echo $template_id; ?>" class="btn btn-primary">
                                                <i class="fa fa-edit"></i> Edit Template
                                            </a>
                                            <button class="btn btn-success" onclick="window.print()">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($template['fields'])): ?>
                                    <div class="text-center py-5">
                                        <i class="fa fa-file-alt fa-4x text-muted mb-3"></i>
                                        <h4>No Fields Defined</h4>
                                        <p>This template doesn't have any fields defined yet.</p>
                                        <a href="./template-view.php?id=<?php echo $template_id; ?>" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Add Fields
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <!-- Template Structure -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="table-responsive theme-scrollbar">
                                                <table class="table table-bordered table-striped">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th width="50">#</th>
                                                            <th>Section</th>
                                                            <th>Field Name</th>
                                                            <th>Display Name</th>
                                                            <th>Data Type</th>
                                                            <th>Length</th>
                                                            <th>Mandatory</th>
                                                            <th>Default Value</th>
                                                            <th>Validation</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($template['fields'] as $index => $field): ?>
                                                        <tr>
                                                            <td><?php echo $index + 1; ?></td>
                                                            <td>
                                                                <span class="badge badge-<?php 
                                                                    echo $field['section'] == 'header' ? 'primary' : 
                                                                        ($field['section'] == 'body' ? 'success' : 'secondary'); 
                                                                ?>">
                                                                    <?php echo ucfirst($field['section']); ?>
                                                                </span>
                                                            </td>
                                                            <td><code><?php echo htmlspecialchars($field['field_name']); ?></code></td>
                                                            <td><?php echo htmlspecialchars($field['display_name']); ?></td>
                                                            <td>
                                                                <span class="badge badge-info">
                                                                    <?php echo ucfirst($field['data_type']); ?>
                                                                    <?php if ($field['data_type'] == 'decimal' && $field['decimal_places']): ?>
                                                                        (<?php echo $field['decimal_places']; ?>dp)
                                                                    <?php endif; ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo $field['length'] ? $field['length'] : 'N/A'; ?></td>
                                                            <td class="text-center">
                                                                <?php if ($field['mandatory']): ?>
                                                                    <span class="badge badge-danger">Yes</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-light">No</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($field['default_value']): ?>
                                                                    <code><?php echo htmlspecialchars($field['default_value']); ?></code>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($field['validation_regex']): ?>
                                                                    <small><code><?php echo htmlspecialchars($field['validation_regex']); ?></code></small>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <?php if ($field['comments']): ?>
                                                        <tr class="bg-light">
                                                            <td colspan="9">
                                                                <small class="text-muted">
                                                                    <strong>Comments:</strong> <?php echo htmlspecialchars($field['comments']); ?>
                                                                </small>
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Sample Implementation -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <h5>Sample Implementation</h5>
                                                </div>
                                                <div class="card-body">
                                                    <?php
                                                    $sample = $templateManager->get_template_sample($template_id);
                                                    if ($sample): ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Header Format</h6>
                                                            <pre class="bg-light p-3 border rounded"><?php echo htmlspecialchars($sample['header']); ?></pre>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Body Format</h6>
                                                            <pre class="bg-light p-3 border rounded"><?php echo htmlspecialchars($sample['body']); ?></pre>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <h6>Description</h6>
                                                        <p><?php echo htmlspecialchars($sample['description']); ?></p>
                                                    </div>
                                                    <?php else: ?>
                                                    <p class="text-muted">No sample data available for this template.</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Technical Details -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card border">
                                                <div class="card-header">
                                                    <h5>Technical Details</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="detail-item">
                                                                <h6>File Format</h6>
                                                                <p><code><?php echo htmlspecialchars($template['file_format'] ?? 'CSV'); ?></code></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="detail-item">
                                                                <h6>Delimiter</h6>
                                                                <p><kbd><?php echo htmlspecialchars($template['delimiter'] ?? ';'); ?></kbd></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="detail-item">
                                                                <h6>Has Header?</h6>
                                                                <p><?php echo $template['has_header'] ? 'Yes' : 'No'; ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="detail-item">
                                                                <h6>Sections</h6>
                                                                <p>
                                                                    <?php 
                                                                    $sections = array_unique(array_column($template['fields'], 'section'));
                                                                    echo implode(', ', array_map('ucfirst', $sections));
                                                                    ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
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
    
    <?php include "includes/scripts.php"; ?>
</body>
</html>