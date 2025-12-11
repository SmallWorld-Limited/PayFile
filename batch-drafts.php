<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

// Get batch management instance
$batchMgr = new batch_management();
$drafts = $batchMgr->get_all_batches(['status' => 'draft']);
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
    <title>JPI Systems - Draft Batches</title>
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
                                <h3>Draft Batches</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Creation</li>
                                    <li class="breadcrumb-item active">Draft Batches</li>
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
                                    <h4>My Draft Batches</h4>
                                    <div class="float-end">
                                        <a href="./create-batch.php" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Create New Batch
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($drafts)): ?>
                                    <div class="text-center py-5">
                                        <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h4>No Draft Batches Found</h4>
                                        <p class="text-muted">You don't have any draft batches. Create your first batch!</p>
                                        <a href="./create-batch.php" class="btn btn-primary mt-2">
                                            <i class="fa fa-plus"></i> Create New Batch
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="drafts-table" role="grid" aria-describedby="drafts-table_info">
                                            <thead>
                                                <tr role="row">
                                                    <th>Batch #</th>
                                                    <th>Reference</th>
                                                    <th>File Type</th>
                                                    <th>Currency</th>
                                                    <th>Amount</th>
                                                    <th>Count</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($drafts as $batch): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($batch['batch_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($batch['reference_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($batch['file_type']); ?></td>
                                                    <td><?php echo htmlspecialchars($batch['currency_code']); ?></td>
                                                    <td><?php echo number_format($batch['total_amount'], 2); ?></td>
                                                    <td><?php echo $batch['total_count']; ?></td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($batch['created_at'])); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-info btn-sm view-draft" 
                                                                    data-batch-id="<?php echo $batch['id']; ?>"
                                                                    title="View Details">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-warning btn-sm edit-draft" 
                                                                    data-batch-id="<?php echo $batch['id']; ?>"
                                                                    title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-success btn-sm submit-draft" 
                                                                    data-batch-id="<?php echo $batch['id']; ?>"
                                                                    title="Submit for Approval">
                                                                <i class="fa fa-paper-plane"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm delete-draft" 
                                                                    data-batch-id="<?php echo $batch['id']; ?>"
                                                                    title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
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
            $('#drafts-table').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[6, 'desc']] // Sort by created date descending
            });
            
            // View draft details
            $(document).on('click', '.view-draft', function () {
                const batchId = $(this).data('batch-id');
                window.location.href = './batch-view.php?id=' + batchId;
            });
            
            // Edit draft
            $(document).on('click', '.edit-draft', function () {
                const batchId = $(this).data('batch-id');
                window.location.href = './batch-edit.php?id=' + batchId;
            });
            
            // Submit draft for approval
            $(document).on('click', '.submit-draft', function () {
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
            
            // Delete draft
            $(document).on('click', '.delete-draft', function () {
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
                                Swal.fire('Error', 'Failed to delete batch', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>