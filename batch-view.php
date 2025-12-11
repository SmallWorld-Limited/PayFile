<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

// Get batch ID from URL
$batch_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$batch_id) {
    header("Location: ./batch-drafts.php");
    exit;
}

// Get batch details
$batchMgr = new batch_management();
$batch = $batchMgr->get_batch_details($batch_id);
if (!$batch) {
    header("Location: ./batch-drafts.php");
    exit;
}

// Get batch items
$items = $batchMgr->get_batch_items($batch_id);

// Get batch comments
$comments = $batchMgr->get_batch_comments($batch_id);
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
    <title>JPI Systems - Batch Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css href="assets/css/style.css">
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
                                <h3>Batch Details</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="./batch-drafts.php">Batches</a></li>
                                    <li class="breadcrumb-item active">Batch #<?php echo $batch['batch_number']; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <!-- Batch Summary Card -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Batch Summary</h4>
                                    <div class="float-end">
                                        <span class="badge badge-<?php 
                                            echo getStatusBadgeClass($batch['status']);
                                        ?>">
                                            <?php echo ucfirst($batch['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Batch Number:</label>
                                                <p class="font-weight-bold"><?php echo htmlspecialchars($batch['batch_number']); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Reference:</label>
                                                <p class="font-weight-bold"><?php echo htmlspecialchars($batch['reference_no']); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>File Type:</label>
                                                <p><?php echo htmlspecialchars($batch['file_type_name']); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Currency:</label>
                                                <p><?php echo htmlspecialchars($batch['currency_code']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Total Amount:</label>
                                                <p class="font-weight-bold text-success"><?php echo number_format($batch['total_amount'], 2); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Total Items:</label>
                                                <p class="font-weight-bold"><?php echo $batch['total_count']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Created By:</label>
                                                <p><?php echo htmlspecialchars($batch['created_by_name']); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="batch-info">
                                                <label>Created At:</label>
                                                <p><?php echo date('Y-m-d H:i', strtotime($batch['created_at'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($batch['description']): ?>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="batch-info">
                                                <label>Description:</label>
                                                <p><?php echo htmlspecialchars($batch['description']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($batch['notes']): ?>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="batch-info">
                                                <label>Notes:</label>
                                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($batch['notes'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Action Buttons -->
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="btn-group" role="group">
                                                <?php if ($batch['status'] == 'draft'): ?>
                                                <a href="./batch-edit.php?id=<?php echo $batch_id; ?>" class="btn btn-warning">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-success submit-batch" data-batch-id="<?php echo $batch_id; ?>">
                                                    <i class="fa fa-paper-plane"></i> Submit for Approval
                                                </button>
                                                <?php endif; ?>
                                                
                                                <?php if ($batch['status'] == 'generated'): ?>
                                                <button class="btn btn-primary prepare-sign" data-batch-id="<?php echo $batch_id; ?>">
                                                    <i class="fa fa-signature"></i> Prepare for Signing
                                                </button>
                                                <?php endif; ?>
                                                
                                                <?php if ($batch['status'] == 'ready_to_sign'): ?>
                                                <button class="btn btn-primary sign-batch" data-batch-id="<?php echo $batch_id; ?>">
                                                    <i class="fa fa-signature"></i> Sign Now
                                                </button>
                                                <?php endif; ?>
                                                
                                                <?php if (in_array($batch['status'], ['draft', 'rejected', 'failed'])): ?>
                                                <button class="btn btn-danger delete-batch" data-batch-id="<?php echo $batch_id; ?>">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                                <?php endif; ?>
                                                
                                                <a href="./batch-drafts.php" class="btn btn-secondary">
                                                    <i class="fa fa-arrow-left"></i> Back to List
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Batch Items -->
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Batch Items (<?php echo count($items); ?>)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="batchItemsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Transaction Serial</th>
                                                    <th>Debit Account</th>
                                                    <th>Debit Account Name</th>
                                                    <th>Amount</th>
                                                    <th>Payee Details</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($items as $item): 
                                                    $data = $item['data'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $item['item_index']; ?></td>
                                                    <td><?php echo htmlspecialchars($data['trans_serial'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($data['debit_account_number'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($data['debit_account_name'] ?? ''); ?></td>
                                                    <td><?php echo number_format($data['payment_amount'] ?? 0, 2); ?></td>
                                                    <td><?php echo htmlspecialchars($data['payee_details'] ?? ''); ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php 
                                                            echo $item['status'] == 'processed' ? 'success' : 
                                                                ($item['status'] == 'error' ? 'danger' : 'warning');
                                                        ?>">
                                                            <?php echo ucfirst($item['status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Comments & History</h4>
                                </div>
                                <div class="card-body">
                                    <!-- Add Comment Form -->
                                    <div class="comment-form mb-4">
                                        <form id="addCommentForm">
                                            <input type="hidden" name="batch_id" value="<?php echo $batch_id; ?>">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <textarea class="form-control" id="comment_text" name="comment" rows="2" placeholder="Add a comment..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary mt-2">
                                                            <i class="fa fa-paper-plane"></i> Add Comment
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Comments List -->
                                    <div class="comments-list">
                                        <?php if (empty($comments)): ?>
                                        <div class="text-center py-3">
                                            <p class="text-muted">No comments yet</p>
                                        </div>
                                        <?php else: ?>
                                            <?php foreach ($comments as $comment): ?>
                                            <div class="comment-item mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="comment-avatar">
                                                            <i class="fa fa-user-circle fa-2x text-muted"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="comment-header">
                                                            <strong><?php echo htmlspecialchars($comment['full_name'] ?? $comment['username']); ?></strong>
                                                            <small class="text-muted">
                                                                <?php echo date('Y-m-d H:i', strtotime($comment['created_at'])); ?>
                                                                <?php if ($comment['comment_type'] != 'note'): ?>
                                                                <span class="badge badge-<?php 
                                                                    echo getCommentBadgeClass($comment['comment_type']);
                                                                ?> ms-2">
                                                                    <?php echo ucfirst($comment['comment_type']); ?>
                                                                </span>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>
                                                        <div class="comment-body">
                                                            <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
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
            // Initialize DataTable for items
            $('#batchItemsTable').DataTable({
                pageLength: 10,
                responsive: true,
                searching: false,
                lengthChange: false
            });
            
            // Submit batch for approval
            $('.submit-batch').click(function () {
                const batchId = $(this).data('batch-id');
                
                Swal.fire({
                    title: 'Submit for Approval?',
                    text: 'This will submit the batch for approval. You cannot edit it after submission.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: './php/batch_handler.php',
                            type: 'POST',
                            data: {
                                update_status: 1,
                                batch_id: batchId,
                                new_status: 'submitted'
                            },
                            success: function(response) {
                                try {
                                    const res = JSON.parse(response);
                                    if (res.success) {
                                        Swal.fire({
                                            title: 'Submitted!',
                                            text: res.message,
                                            icon: 'success'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid response from server', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to submit batch', 'error');
                            }
                        });
                    }
                });
            });
            
            // Prepare for signing
            $('.prepare-sign').click(function () {
                const batchId = $(this).data('batch-id');
                
                $.ajax({
                    url: './php/batch_handler.php',
                    type: 'POST',
                    data: {
                        prepare_for_signing: 1,
                        batch_id: batchId
                    },
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: 'Prepared!',
                                    text: res.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'Invalid response from server', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to prepare batch', 'error');
                    }
                });
            });
            
            // Sign batch
            $('.sign-batch').click(function () {
                const batchId = $(this).data('batch-id');
                window.location.href = './sign-queue.php?sign=' + batchId;
            });
            
            // Delete batch
            $('.delete-batch').click(function () {
                const batchId = $(this).data('batch-id');
                
                Swal.fire({
                    title: 'Delete Batch?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: './php/batch_handler.php',
                            type: 'POST',
                            data: {
                                delete_batch: 1,
                                batch_id: batchId
                            },
                            success: function(response) {
                                try {
                                    const res = JSON.parse(response);
                                    if (res.success) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: res.message,
                                            icon: 'success'
                                        }).then(() => {
                                            window.location.href = './batch-drafts.php';
                                        });
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid response from server', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to delete batch', 'error');
                            }
                        });
                    }
                });
            });
            
            // Add comment
            $('#addCommentForm').submit(function (e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: './php/batch_handler.php',
                    type: 'POST',
                    data: formData + '&add_comment=1',
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: 'Comment Added!',
                                    text: res.message,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'Invalid response from server', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to add comment', 'error');
                    }
                });
            });
        });
    </script>
    
    <style>
        .batch-info label {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .batch-info p {
            margin-bottom: 0;
        }
        
        .comment-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .comment-body {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 3px solid #007bff;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-warning {
            background-color: #ffc107;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        .badge-info {
            background-color: #17a2b8;
        }
        
        .badge-secondary {
            background-color: #6c757d;
        }
        
        .comment-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</body>
</html>

<?php
// Helper functions
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'draft': return 'secondary';
        case 'submitted': return 'info';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        case 'processing': return 'warning';
        case 'generated': return 'info';
        case 'ready_to_sign': return 'primary';
        case 'signed': return 'success';
        case 'failed': return 'danger';
        case 'archived': return 'dark';
        default: return 'secondary';
    }
}

function getCommentBadgeClass($type) {
    switch ($type) {
        case 'approval': return 'success';
        case 'rejection': return 'danger';
        case 'correction': return 'warning';
        default: return 'secondary';
    }
}
?>