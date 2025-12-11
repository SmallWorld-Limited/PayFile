<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

// Get signing queue instance
$signingQueue = new signing_queue();
$readyFiles = $signingQueue->get_ready_to_sign(100);
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
    <title>JPI Systems - Signing Queue</title>
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
                                <h3>Signing Queue</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Signing</li>
                                    <li class="breadcrumb-item active">Signing Queue</li>
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
                                    <h4>Files Ready for Signing</h4>
                                    <p>Select a file and certificate to sign</p>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($readyFiles)): ?>
                                    <div class="text-center py-5">
                                        <i class="fa fa-check-circle fa-3x text-muted mb-3"></i>
                                        <h4>No Files in Queue</h4>
                                        <p class="text-muted">All files have been signed or are still being processed.</p>
                                        <a href="./generated-files.php" class="btn btn-primary mt-2">
                                            <i class="fa fa-eye"></i> View Generated Files
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="sign-queue-table" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>Batch #</th>
                                                    <th>Reference</th>
                                                    <th>File Type</th>
                                                    <th>Currency</th>
                                                    <th>Amount</th>
                                                    <th>Count</th>
                                                    <th>Created By</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($readyFiles as $file): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($file['batch_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($file['reference_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($file['file_type']); ?></td>
                                                    <td><?php echo htmlspecialchars($file['currency_code']); ?></td>
                                                    <td><?php echo number_format($file['total_amount'], 2); ?></td>
                                                    <td><?php echo $file['total_count']; ?></td>
                                                    <td><?php echo htmlspecialchars($file['created_by_name']); ?></td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($file['created_at'])); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-info btn-sm view-file" 
                                                                    data-batch-id="<?php echo $file['id']; ?>"
                                                                    title="View Details">
                                                                <i class="fa fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-primary btn-sm sign-file" 
                                                                    data-batch-id="<?php echo $file['id']; ?>"
                                                                    data-reference="<?php echo htmlspecialchars($file['reference_no']); ?>"
                                                                    title="Sign File">
                                                                <i class="fa fa-signature"></i>
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
                    
                    <!-- Recent Signing History -->
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Recent Signing History</h4>
                                </div>
                                <div class="card-body">
                                    <?php 
                                    $signingHistory = $signingQueue->get_signing_history(10);
                                    if (empty($signingHistory)): ?>
                                    <div class="text-center py-3">
                                        <p class="text-muted">No recent signing history</p>
                                    </div>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Batch #</th>
                                                    <th>Reference</th>
                                                    <th>File Type</th>
                                                    <th>Signed By</th>
                                                    <th>Signed At</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($signingHistory as $history): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($history['batch_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($history['reference_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($history['file_type']); ?></td>
                                                    <td><?php echo htmlspecialchars($history['signed_by_name']); ?></td>
                                                    <td><?php echo date('Y-m-d H:i', strtotime($history['signed_at'])); ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php 
                                                            echo $history['verification_status'] == 'verified' ? 'success' : 
                                                                ($history['verification_status'] == 'failed' ? 'danger' : 'warning');
                                                        ?>">
                                                            <?php echo ucfirst($history['verification_status']); ?>
                                                        </span>
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
    
    <!-- Sign File Modal -->
    <div class="modal fade" id="signFileModal" tabindex="-1" role="dialog" aria-labelledby="signFileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signFileModalLabel">Sign File</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="signFileForm">
                        <input type="hidden" id="sign_batch_id" name="batch_id">
                        
                        <div class="form-group">
                            <label class="col-form-label">Select Certificate</label>
                            <select class="form-control" id="sign_certificate_id" name="certificate_id" required>
                                <option value="">-- Select Certificate --</option>
                                <?php
                                $certificates = $signingQueue->get_available_certificates();
                                foreach ($certificates as $cert): ?>
                                <option value="<?php echo $cert['id']; ?>" <?php echo $cert['is_default'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cert['certificate_name'] . ' - ' . $cert['issuer']); ?>
                                    <?php echo $cert['is_default'] ? ' (Default)' : ''; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group mt-3">
                            <label class="col-form-label">Certificate Password (if required)</label>
                            <input class="form-control" type="password" id="sign_cert_password" name="cert_password" placeholder="Enter certificate password">
                        </div>
                        
                        <div class="form-group mt-3">
                            <label class="col-form-label">Signing Notes</label>
                            <textarea class="form-control" id="signing_notes" name="signing_notes" rows="2" placeholder="Optional notes about this signing"></textarea>
                        </div>
                        
                        <div class="progress d-none mt-3" id="signingProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="confirmSignBtn" type="button">Sign File</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#sign-queue-table').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[7, 'asc']] // Sort by created date ascending (oldest first)
            });
            
            // View file details
            $(document).on('click', '.view-file', function () {
                const batchId = $(this).data('batch-id');
                window.location.href = './batch-view.php?id=' + batchId;
            });
            
            // Sign file - open modal
            $(document).on('click', '.sign-file', function () {
                const batchId = $(this).data('batch-id');
                const reference = $(this).data('reference');
                
                $('#signFileModalLabel').text('Sign File: ' + reference);
                $('#sign_batch_id').val(batchId);
                $('#signFileModal').modal('show');
            });
            
            // Confirm signing
            $('#confirmSignBtn').click(function () {
                const batchId = $('#sign_batch_id').val();
                const certificateId = $('#sign_certificate_id').val();
                
                if (!certificateId) {
                    Swal.fire('Error', 'Please select a certificate', 'error');
                    return;
                }
                
                // Show progress bar
                $('#signingProgress').removeClass('d-none');
                
                $.ajax({
                    url: './php/batch_handler.php',
                    type: 'POST',
                    data: {
                        sign_file: 1,
                        batch_id: batchId,
                        certificate_id: certificateId,
                        cert_password: $('#sign_cert_password').val(),
                        notes: $('#signing_notes').val()
                    },
                    success: function(response) {
                        $('#signingProgress').addClass('d-none');
                        
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: 'Signed Successfully!',
                                    html: res.message + '<br><br>' + 
                                          '<a href="' + res.download_url + '" class="btn btn-primary">Download Signed File</a>',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    width: '600px'
                                }).then(() => {
                                    $('#signFileModal').modal('hide');
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
                        $('#signingProgress').addClass('d-none');
                        Swal.fire('Error', 'Failed to sign file', 'error');
                    }
                });
            });
            
            // Reset modal when closed
            $('#signFileModal').on('hidden.bs.modal', function () {
                $('#signFileForm')[0].reset();
                $('#signingProgress').addClass('d-none');
                $('#signingProgress .progress-bar').css('width', '0%');
            });
            
            // Simulate progress for signing
            function simulateSigningProgress() {
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    $('#signingProgress .progress-bar').css('width', progress + '%');
                    $('#signingProgress .progress-bar').text(progress + '%');
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                    }
                }, 200);
            }
        });
    </script>
    
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
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
    </style>
</body>
</html>