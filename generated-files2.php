<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$batch_mgmt = new batch_management();
$dropdowns = new dropdowns();

// Get generated files (files with status 'generated' or 'ready_to_sign')
$generated_files = $batch_mgmt->get_all_batches(['status' => ['generated', 'ready_to_sign']]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JPI File Signing System">
    <meta name="keywords" content="File Signing, CSV, PKCS7, Digital Signature">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>Generated Files - JPI File Signing System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatable-extension.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <style>
        .file-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .file-card:hover {
            border-color: #007bff;
            box-shadow: 0 5px 15px rgba(0,123,255,0.1);
        }
        .file-icon {
            font-size: 40px;
            color: #007bff;
        }
        .file-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .file-badge {
            position: absolute;
            top: 10px;
            left: 10px;
        }
    </style>
</head>
<body>
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <div class="loader-wrapper">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"> </div>
        <div class="dot"></div>
    </div>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-header">
            <?php include "includes/header.php"; ?>
        </div>
        <div class="page-body-wrapper horizontal-menu">
            <div class="sidebar-wrapper">
                <div>
                    <?php include "includes/sidebar.php"; ?>
                </div>
            </div>
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>Generated Files</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">CSV Files</li>
                                    <li class="breadcrumb-item active">Generated Files</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>All Generated Files</h4>
                                    <p>Files that have been generated and are ready for signing</p>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($generated_files)): ?>
                                        <div class="text-center py-5">
                                            <i class="fa fa-folder-open fa-4x text-muted mb-3"></i>
                                            <h5>No generated files found</h5>
                                            <p class="text-muted">Generate files by uploading OBDX files or converting Sage data</p>
                                            <div class="mt-3">
                                                <a href="./obdx-upload.php" class="btn btn-primary me-2">
                                                    <i class="fa fa-upload"></i> Upload OBDX File
                                                </a>
                                                <a href="./upload-sage.php" class="btn btn-success">
                                                    <i class="fa fa-database"></i> Convert Sage Data
                                                </a>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Grid View -->
                                        <div class="row" id="gridView">
                                            <?php foreach ($generated_files as $file): 
                                                $status_class = 'status-' . $file['status'];
                                            ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="file-card position-relative">
                                                    <span class="file-badge batch-status <?php echo $status_class; ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $file['status'])); ?>
                                                    </span>
                                                    
                                                    <div class="file-actions">
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="./batch-detail.php?id=<?php echo $file['id']; ?>" 
                                                               class="btn btn-info" title="View">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <?php if ($file['file_path'] && file_exists($file['file_path'])): ?>
                                                            <a href="./php/file_handler.php?action=download&id=<?php echo $file['id']; ?>" 
                                                               class="btn btn-secondary" title="Download">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                            <?php if ($file['status'] == 'generated' || $file['status'] == 'ready_to_sign'): ?>
                                                            <a href="./sign-file.php?batch_id=<?php echo $file['id']; ?>" 
                                                               class="btn btn-success" title="Sign">
                                                                <i class="fa fa-signature"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="text-center mb-3">
                                                        <i class="fa fa-file-csv file-icon"></i>
                                                    </div>
                                                    
                                                    <h5 class="text-truncate"><?php echo htmlspecialchars($file['batch_number']); ?></h5>
                                                    <p class="text-muted mb-1">
                                                        <i class="fa fa-hashtag"></i> <?php echo htmlspecialchars($file['reference_no']); ?>
                                                    </p>
                                                    <p class="text-muted mb-1">
                                                        <i class="fa fa-file-alt"></i> <?php echo htmlspecialchars($file['file_type']); ?>
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Amount:</strong> 
                                                        <?php echo number_format($file['total_amount'], 2); ?> 
                                                        <?php echo $file['currency_code']; ?>
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Records:</strong> <?php echo $file['total_count']; ?>
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Created:</strong> <?php echo date('d/m/Y', strtotime($file['created_at'])); ?>
                                                    </p>
                                                    <p class="mb-0">
                                                        <strong>By:</strong> <?php echo htmlspecialchars($file['created_by_name']); ?>
                                                    </p>
                                                    
                                                    <?php if ($file['status'] == 'generated'): ?>
                                                    <div class="mt-3">
                                                        <button class="btn btn-sm btn-block btn-outline-primary prepare-signing" 
                                                                data-id="<?php echo $file['id']; ?>">
                                                            <i class="fa fa-signature"></i> Prepare for Signing
                                                        </button>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <!-- Table View (Hidden by default) -->
                                        <div class="table-responsive d-none" id="tableView">
                                            <table class="table table-hover" id="filesTable">
                                                <thead>
                                                    <tr>
                                                        <th>Batch #</th>
                                                        <th>Reference</th>
                                                        <th>File Type</th>
                                                        <th>Amount</th>
                                                        <th>Records</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($generated_files as $file): 
                                                        $status_class = 'status-' . $file['status'];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($file['batch_number']); ?></strong>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($file['reference_no']); ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($file['file_type']); ?>
                                                            <br>
                                                            <small class="text-muted"><?php echo $file['currency_code']; ?></small>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo number_format($file['total_amount'], 2); ?></strong>
                                                        </td>
                                                        <td><?php echo $file['total_count']; ?></td>
                                                        <td>
                                                            <span class="batch-status <?php echo $status_class; ?>">
                                                                <?php echo ucfirst(str_replace('_', ' ', $file['status'])); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php echo date('d/m/Y', strtotime($file['created_at'])); ?>
                                                            <br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($file['created_by_name']); ?></small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="./batch-detail.php?id=<?php echo $file['id']; ?>" 
                                                                   class="btn btn-info" title="View">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                <?php if ($file['file_path'] && file_exists($file['file_path'])): ?>
                                                                <a href="./php/file_handler.php?action=download&id=<?php echo $file['id']; ?>" 
                                                                   class="btn btn-secondary" title="Download">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                                <?php endif; ?>
                                                                <?php if ($file['status'] == 'generated' || $file['status'] == 'ready_to_sign'): ?>
                                                                <a href="./sign-file.php?batch_id=<?php echo $file['id']; ?>" 
                                                                   class="btn btn-success" title="Sign">
                                                                    <i class="fa fa-signature"></i>
                                                                </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary active" id="gridViewBtn">
                                                    <i class="fa fa-th-large"></i> Grid View
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" id="tableViewBtn">
                                                    <i class="fa fa-table"></i> Table View
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="./obdx-upload.php" class="btn btn-primary">
                                                    <i class="fa fa-plus"></i> New OBDX File
                                                </a>
                                                <a href="./upload-sage.php" class="btn btn-success">
                                                    <i class="fa fa-database"></i> Convert Sage
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "includes/footer.php"; ?>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
    $(document).ready(function() {
        // View toggle
        $('#gridViewBtn').click(function() {
            $(this).addClass('active');
            $('#tableViewBtn').removeClass('active');
            $('#gridView').removeClass('d-none');
            $('#tableView').addClass('d-none');
        });
        
        $('#tableViewBtn').click(function() {
            $(this).addClass('active');
            $('#gridViewBtn').removeClass('active');
            $('#gridView').addClass('d-none');
            $('#tableView').removeClass('d-none');
            
            // Initialize DataTable if not already initialized
            if (!$.fn.DataTable.isDataTable('#filesTable')) {
                $('#filesTable').DataTable({
                    "pageLength": 25,
                    "order": [[6, 'desc']]
                });
            }
        });
        
        // Prepare for signing
        $(document).on('click', '.prepare-signing', function() {
            var batchId = $(this).data('id');
            
            Swal.fire({
                title: 'Prepare for Signing?',
                text: "This will mark the file as ready for digital signing.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, prepare it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/batch_handler.php',
                        type: 'POST',
                        data: {
                            prepare_signing: '1',
                            batch_id: batchId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            });
        });
        
        // Quick download
        $(document).on('click', '.quick-download', function(e) {
            e.preventDefault();
            var batchId = $(this).data('id');
            window.location.href = './php/file_handler.php?action=download&id=' + batchId;
        });
        
        // Quick sign
        $(document).on('click', '.quick-sign', function(e) {
            e.preventDefault();
            var batchId = $(this).data('id');
            window.location.href = './sign-file.php?batch_id=' + batchId;
        });
    });
    </script>
</body>
</html>