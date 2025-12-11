<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$batch_mgmt = new batch_management();
$dropdowns = new dropdowns();

// Get filter values
$filters = [];
if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
if (isset($_GET['file_type'])) $filters['file_type'] = $_GET['file_type'];
if (isset($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
if (isset($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
if (isset($_GET['reference_no'])) $filters['reference_no'] = $_GET['reference_no'];

// Get batches
$batches = $batch_mgmt->get_all_batches($filters);
$stats = $batch_mgmt->get_batch_statistics();
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
    <title>Batch Management - JPI File Signing System</title>
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
        .batch-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-draft { background: #6c757d; color: white; }
        .status-submitted { background: #17a2b8; color: white; }
        .status-approved { background: #28a745; color: white; }
        .status-rejected { background: #dc3545; color: white; }
        .status-processing { background: #ffc107; color: #212529; }
        .status-generated { background: #20c997; color: white; }
        .status-ready_to_sign { background: #007bff; color: white; }
        .status-signed { background: #6610f2; color: white; }
        .status-failed { background: #e83e8c; color: white; }
        .status-archived { background: #6f42c1; color: white; }
        
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card i {
            font-size: 40px;
            opacity: 0.8;
        }
        .stat-card h3 {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-card p {
            opacity: 0.9;
            margin: 0;
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
                                <h3>Batch Management</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Batch Management</li>
                                    <li class="breadcrumb-item active">All Batches</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 col-xl-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $stats['total_batches'] ?? 0; ?></h3>
                                        <p>Total Batches</p>
                                    </div>
                                    <i class="fa fa-layer-group"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $stats['pending_approval'] ?? 0; ?></h3>
                                        <p>Pending Approval</p>
                                    </div>
                                    <i class="fa fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $stats['ready_to_sign'] ?? 0; ?></h3>
                                        <p>Ready to Sign</p>
                                    </div>
                                    <i class="fa fa-file-signature"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?php echo $stats['signed_batches'] ?? 0; ?></h3>
                                        <p>Signed Files</p>
                                    </div>
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filters Card -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Filter Batches</h4>
                                    <button class="btn btn-primary btn-sm float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                        <i class="fa fa-filter"></i> Toggle Filters
                                    </button>
                                </div>
                                <div class="collapse show" id="filterCollapse">
                                    <div class="card-body">
                                        <form method="GET" id="filterForm">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control" name="status">
                                                            <option value="">All Status</option>
                                                            <option value="draft" <?php echo isset($_GET['status']) && $_GET['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                                            <option value="submitted" <?php echo isset($_GET['status']) && $_GET['status'] == 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                                                            <option value="approved" <?php echo isset($_GET['status']) && $_GET['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                            <option value="ready_to_sign" <?php echo isset($_GET['status']) && $_GET['status'] == 'ready_to_sign' ? 'selected' : ''; ?>>Ready to Sign</option>
                                                            <option value="signed" <?php echo isset($_GET['status']) && $_GET['status'] == 'signed' ? 'selected' : ''; ?>>Signed</option>
                                                            <option value="rejected" <?php echo isset($_GET['status']) && $_GET['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>File Type</label>
                                                        <select class="form-control" name="file_type">
                                                            <option value="">All Types</option>
                                                            <?php
                                                            $types_stmt = $conn->prepare("SELECT id, name FROM file_types WHERE is_active = 1 ORDER BY name");
                                                            $types_stmt->execute();
                                                            $types_result = $types_stmt->get_result();
                                                            while ($type = $types_result->fetch_assoc()) {
                                                                $selected = isset($_GET['file_type']) && $_GET['file_type'] == $type['id'] ? 'selected' : '';
                                                                echo "<option value='{$type['id']}' $selected>{$type['name']}</option>";
                                                            }
                                                            $types_stmt->close();
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Date From</label>
                                                        <input type="date" class="form-control" name="date_from" 
                                                               value="<?php echo $_GET['date_from'] ?? ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Date To</label>
                                                        <input type="date" class="form-control" name="date_to" 
                                                               value="<?php echo $_GET['date_to'] ?? ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Reference No</label>
                                                        <input type="text" class="form-control" name="reference_no" 
                                                               placeholder="Search..." value="<?php echo $_GET['reference_no'] ?? ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <div class="text-end">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i> Apply Filters
                                                        </button>
                                                        <a href="./batches.php" class="btn btn-secondary">
                                                            <i class="fa fa-times"></i> Clear Filters
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Batches Table -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>All Batches</h4>
                                    <div class="float-end">
                                        <span class="badge badge-light">Showing <?php echo count($batches); ?> batches</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($batches)): ?>
                                        <div class="text-center py-5">
                                            <i class="fa fa-inbox fa-4x text-muted mb-3"></i>
                                            <h5>No batches found</h5>
                                            <p class="text-muted">Try adjusting your filters or create a new batch</p>
                                            <a href="./obdx-upload.php" class="btn btn-primary mt-2">
                                                <i class="fa fa-plus"></i> Create New Batch
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="batchesTable">
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
                                                    <?php foreach ($batches as $batch): 
                                                        $status_class = 'status-' . $batch['status'];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($batch['batch_number']); ?></strong>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($batch['reference_no']); ?>
                                                            <br>
                                                            <small class="text-muted">By: <?php echo htmlspecialchars($batch['created_by_name']); ?></small>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($batch['file_type']); ?>
                                                            <br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($batch['currency_code']); ?></small>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo number_format($batch['total_amount'], 2); ?></strong>
                                                        </td>
                                                        <td>
                                                            <?php echo $batch['total_count']; ?>
                                                            <?php if ($batch['signed_count'] > 0): ?>
                                                                <br>
                                                                <small class="text-success">
                                                                    <i class="fa fa-check-circle"></i> <?php echo $batch['signed_count']; ?> signed
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="batch-status <?php echo $status_class; ?>">
                                                                <?php echo ucfirst(str_replace('_', ' ', $batch['status'])); ?>
                                                            </span>
                                                            <?php if ($batch['approved_at']): ?>
                                                                <br>
                                                                <small class="text-muted">
                                                                    Approved: <?php echo date('d/m/Y', strtotime($batch['approved_at'])); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo date('d/m/Y H:i', strtotime($batch['created_at'])); ?>
                                                            <?php if ($batch['signed_at']): ?>
                                                                <br>
                                                                <small class="text-success">
                                                                    Signed: <?php echo date('d/m/Y', strtotime($batch['signed_at'])); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a href="./batch-detail.php?id=<?php echo $batch['id']; ?>" 
                                                                   class="btn btn-info" title="View Details">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                
                                                                <?php if ($_SESSION["bt_role"] == 'admin' || $batch['created_by'] == $_SESSION["bt_user_id"]): ?>
                                                                    <?php if ($batch['status'] == 'draft'): ?>
                                                                        <a href="./edit-batch.php?id=<?php echo $batch['id']; ?>" 
                                                                           class="btn btn-warning" title="Edit">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if ($batch['status'] == 'draft' || $batch['status'] == 'submitted'): ?>
                                                                        <button type="button" class="btn btn-danger delete-batch" 
                                                                                data-id="<?php echo $batch['id']; ?>" title="Delete">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                                
                                                                <?php if ($batch['file_path'] && file_exists($batch['file_path'])): ?>
                                                                    <a href="./php/file_handler.php?action=download&id=<?php echo $batch['id']; ?>" 
                                                                       class="btn btn-secondary" title="Download">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                                
                                                                <?php if ($batch['status'] == 'ready_to_sign'): ?>
                                                                    <a href="./sign-file.php?batch_id=<?php echo $batch['id']; ?>" 
                                                                       class="btn btn-success" title="Sign File">
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
        // Initialize DataTable
        $('#batchesTable').DataTable({
            "pageLength": 25,
            "order": [[6, 'desc']],
            "language": {
                "search": "Search batches:",
                "lengthMenu": "Show _MENU_ batches per page",
                "zeroRecords": "No batches found",
                "info": "Showing _START_ to _END_ of _TOTAL_ batches",
                "infoEmpty": "No batches available",
                "infoFiltered": "(filtered from _MAX_ total batches)"
            }
        });
        
        // Delete batch confirmation
        $(document).on('click', '.delete-batch', function() {
            var batchId = $(this).data('id');
            
            Swal.fire({
                title: 'Delete Batch?',
                text: "This will archive the batch. You can restore it later if needed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/batch_handler.php',
                        type: 'POST',
                        data: {
                            delete_batch: '1',
                            batch_id: batchId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Archived!',
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
        
        // Quick status update
        $(document).on('click', '.update-status', function() {
            var batchId = $(this).data('id');
            var currentStatus = $(this).data('status');
            
            // Determine next status based on current status
            var nextStatus = '';
            var actionText = '';
            
            switch(currentStatus) {
                case 'draft':
                    nextStatus = 'submitted';
                    actionText = 'Submit for Approval';
                    break;
                case 'submitted':
                    nextStatus = 'approved';
                    actionText = 'Approve Batch';
                    break;
                case 'approved':
                    nextStatus = 'processing';
                    actionText = 'Start Processing';
                    break;
                case 'generated':
                    nextStatus = 'ready_to_sign';
                    actionText = 'Prepare for Signing';
                    break;
            }
            
            if (nextStatus) {
                Swal.fire({
                    title: actionText + '?',
                    text: "Change batch status to " + nextStatus + "?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    input: 'textarea',
                    inputLabel: 'Notes (optional)',
                    inputPlaceholder: 'Enter any notes about this status change...',
                    inputAttributes: {
                        'aria-label': 'Enter notes'
                    },
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: './php/batch_handler.php',
                            type: 'POST',
                            data: {
                                update_status: '1',
                                batch_id: batchId,
                                new_status: nextStatus,
                                notes: result.value
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
            }
        });
    });
    </script>
</body>
</html>