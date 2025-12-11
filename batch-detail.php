<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$batch_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$batch_id) {
    header("Location: ./batches.php");
    exit;
}

$batch_mgmt = new batch_management();
$batch = $batch_mgmt->get_batch_details($batch_id);

if (!$batch) {
    header("Location: ./batches.php");
    exit;
}

$items = $batch_mgmt->get_batch_items($batch_id, 50);
$comments = $batch_mgmt->get_batch_comments($batch_id);
$workflow_logs = $batch_mgmt->get_workflow_logs($batch_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Batch Details - JPI File Signing System</title>
    <!-- Include all CSS files from batches.php -->
</head>
<body>
    <!-- Same header/sidebar structure as batches.php -->
    
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
                            <li class="breadcrumb-item"><a href="./batches.php">Batches</a></li>
                            <li class="breadcrumb-item active"><?php echo $batch['batch_number']; ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid">
            <!-- Batch Summary Card -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Batch Information</h4>
                            <div class="card-header-right">
                                <span class="batch-status status-<?php echo $batch['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $batch['status'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Batch Number:</th>
                                            <td><?php echo htmlspecialchars($batch['batch_number']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Reference No:</th>
                                            <td><?php echo htmlspecialchars($batch['reference_no']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>File Type:</th>
                                            <td>
                                                <?php echo htmlspecialchars($batch['file_type_name']); ?>
                                                <small class="text-muted">(<?php echo $batch['file_type_code']; ?>)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Currency:</th>
                                            <td><?php echo htmlspecialchars($batch['currency_code']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Total Amount:</th>
                                            <td><strong><?php echo number_format($batch['total_amount'], 2); ?></strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Total Records:</th>
                                            <td><?php echo $batch['total_count']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td><?php echo htmlspecialchars($batch['created_by_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Created Date:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($batch['created_at'])); ?></td>
                                        </tr>
                                        <?php if ($batch['approved_at']): ?>
                                        <tr>
                                            <th>Approved By:</th>
                                            <td><?php echo htmlspecialchars($batch['approved_by_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Approved Date:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($batch['approved_at'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if ($batch['signed_at']): ?>
                                        <tr>
                                            <th>Signed Date:</th>
                                            <td><?php echo date('d/m/Y H:i', strtotime($batch['signed_at'])); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="btn-group" role="group">
                                        <?php if ($batch['file_path'] && file_exists($batch['file_path'])): ?>
                                        <a href="./php/file_handler.php?action=download&id=<?php echo $batch_id; ?>" 
                                           class="btn btn-primary">
                                            <i class="fa fa-download"></i> Download File
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($batch['signed_file_path'] && file_exists($batch['signed_file_path'])): ?>
                                        <a href="./php/file_handler.php?action=download_signed&id=<?php echo $batch_id; ?>" 
                                           class="btn btn-success">
                                            <i class="fa fa-file-signature"></i> Download Signed
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($batch['status'] == 'ready_to_sign'): ?>
                                        <a href="./sign-file.php?batch_id=<?php echo $batch_id; ?>" 
                                           class="btn btn-warning">
                                            <i class="fa fa-signature"></i> Sign File
                                        </a>
                                        <?php endif; ?>
                                        
                                        <!-- Status update buttons based on user role and current status -->
                                        <?php 
                                        $user_role = $_SESSION["bt_role"];
                                        $can_update = false;
                                        
                                        if ($user_role == 'admin' || 
                                            ($user_role == 'approver' && in_array($batch['status'], ['submitted', 'approved'])) ||
                                            ($user_role == 'creator' && in_array($batch['status'], ['draft', 'rejected', 'failed']))) {
                                            $can_update = true;
                                        }
                                        
                                        if ($can_update): 
                                            $next_status = '';
                                            $button_text = '';
                                            $button_class = '';
                                            
                                            switch($batch['status']) {
                                                case 'draft':
                                                    $next_status = 'submitted';
                                                    $button_text = 'Submit for Approval';
                                                    $button_class = 'btn-info';
                                                    break;
                                                case 'submitted':
                                                    if ($user_role == 'approver' || $user_role == 'admin') {
                                                        $next_status = 'approved';
                                                        $button_text = 'Approve';
                                                        $button_class = 'btn-success';
                                                    }
                                                    break;
                                                case 'approved':
                                                    $next_status = 'processing';
                                                    $button_text = 'Start Processing';
                                                    $button_class = 'btn-primary';
                                                    break;
                                                case 'generated':
                                                    $next_status = 'ready_to_sign';
                                                    $button_text = 'Prepare for Signing';
                                                    $button_class = 'btn-warning';
                                                    break;
                                            }
                                            
                                            if ($next_status): 
                                        ?>
                                        <button type="button" class="btn <?php echo $button_class; ?> update-status" 
                                                data-id="<?php echo $batch_id; ?>" data-status="<?php echo $batch['status']; ?>">
                                            <i class="fa fa-arrow-right"></i> <?php echo $button_text; ?>
                                        </button>
                                        <?php endif; endif; ?>
                                        
                                        <?php if ($_SESSION["bt_role"] == 'admin' || $batch['created_by'] == $_SESSION["bt_user_id"]): ?>
                                            <?php if ($batch['status'] == 'draft'): ?>
                                            <a href="./edit-batch.php?id=<?php echo $batch_id; ?>" class="btn btn-secondary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($batch['status'], ['draft', 'submitted'])): ?>
                                            <button type="button" class="btn btn-danger delete-batch" data-id="<?php echo $batch_id; ?>">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Batch Items Preview -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>Data Preview</h4>
                            <small>Showing first 50 records</small>
                        </div>
                        <div class="card-body">
                            <?php if (empty($items)): ?>
                                <p class="text-muted">No data records found.</p>
                            <?php else: ?>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered">
                                        <thead class="sticky-top bg-light">
                                            <tr>
                                                <th>#</th>
                                                <?php 
                                                // Get headers from first item
                                                $first_item = $items[0]['data'];
                                                if (is_array($first_item)) {
                                                    foreach ($first_item as $key => $value) {
                                                        echo "<th>" . htmlspecialchars($key) . "</th>";
                                                    }
                                                }
                                                ?>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo $item['item_index']; ?></td>
                                                <?php 
                                                if (is_array($item['data'])) {
                                                    foreach ($item['data'] as $value) {
                                                        echo "<td>" . htmlspecialchars(substr($value, 0, 50)) . 
                                                             (strlen($value) > 50 ? '...' : '') . "</td>";
                                                    }
                                                }
                                                ?>
                                                <td>
                                                    <span class="badge badge-<?php echo $item['status'] == 'processed' ? 'success' : 'warning'; ?>">
                                                        <?php echo ucfirst($item['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if ($batch['total_count'] > 50): ?>
                                    <div class="text-center mt-3">
                                        <a href="./batch-items.php?id=<?php echo $batch_id; ?>" class="btn btn-sm btn-outline-primary">
                                            View All <?php echo $batch['total_count']; ?> Records
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar: Comments & Activity -->
                <div class="col-lg-4">
                    <!-- Comments Card -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Comments & Notes</h4>
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <?php if (empty($comments)): ?>
                                <p class="text-muted">No comments yet.</p>
                            <?php else: ?>
                                <?php foreach ($comments as $comment): ?>
                                    <div class="mb-3 p-2 border-bottom">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo htmlspecialchars($comment['full_name'] ?? $comment['username']); ?></strong>
                                            <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></small>
                                        </div>
                                        <small class="badge badge-<?php 
                                            switch($comment['comment_type']) {
                                                case 'approval': echo 'success'; break;
                                                case 'rejection': echo 'danger'; break;
                                                case 'correction': echo 'warning'; break;
                                                default: echo 'info';
                                            }
                                        ?>"><?php echo ucfirst($comment['comment_type']); ?></small>
                                        <p class="mb-1 mt-1"><?php echo htmlspecialchars($comment['comment']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <form id="addCommentForm">
                                <input type="hidden" name="batch_id" value="<?php echo $batch_id; ?>">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="comment" placeholder="Add a comment...">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Activity Log -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>Activity Log</h4>
                        </div>
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <?php if (empty($workflow_logs)): ?>
                                <p class="text-muted">No activity recorded.</p>
                            <?php else: ?>
                                <?php foreach ($workflow_logs as $log): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-primary"><?php echo htmlspecialchars($log['action_performed']); ?></small>
                                            <small class="text-muted"><?php echo date('H:i', strtotime($log['created_at'])); ?></small>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo $log['from_status'] ? 'From ' . $log['from_status'] . ' to ' : ''; ?>
                                            <?php echo $log['to_status']; ?>
                                            <?php if ($log['notes']): ?>
                                                <br><?php echo htmlspecialchars(substr($log['notes'], 0, 50)); ?>
                                                <?php if (strlen($log['notes']) > 50) echo '...'; ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- File Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>File Information</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>Original File:</th>
                                    <td>
                                        <?php if ($batch['file_path'] && file_exists($batch['file_path'])): ?>
                                            <a href="./php/file_handler.php?action=download&id=<?php echo $batch_id; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo round(filesize($batch['file_path']) / 1024, 2); ?> KB
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">Not available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Signed File:</th>
                                    <td>
                                        <?php if ($batch['signed_file_path'] && file_exists($batch['signed_file_path'])): ?>
                                            <a href="./php/file_handler.php?action=download_signed&id=<?php echo $batch_id; ?>" 
                                               class="btn btn-sm btn-outline-success">
                                                <i class="fa fa-file-signature"></i> Download
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                <?php echo round(filesize($batch['signed_file_path']) / 1024, 2); ?> KB
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">Not signed yet</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include scripts and JavaScript -->
    <script>
    $(document).ready(function() {
        // Add comment
        $('#addCommentForm').submit(function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            
            $.ajax({
                url: './php/batch_handler.php',
                type: 'POST',
                data: formData + '&add_comment=1',
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
        });
        
        // Status update
        $('.update-status').click(function() {
            var batchId = $(this).data('id');
            var currentStatus = $(this).data('status');
            
            // Determine next status
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
                    input: 'textarea',
                    inputLabel: 'Notes (optional)',
                    inputPlaceholder: 'Enter any notes about this status change...',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
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