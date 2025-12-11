<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$signing_queue = new signing_queue();
$ready_files = $signing_queue->get_ready_to_sign();
$signing_history = $signing_queue->get_signing_history(10);
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
    <title>Signing Queue - JPI File Signing System</title>
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
        .queue-item {
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .queue-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .queue-item.priority {
            border-left-color: #dc3545;
            background: #fff5f5;
        }
        .queue-item.old {
            border-left-color: #6c757d;
            opacity: 0.8;
        }
        
        .sign-progress {
            height: 5px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 10px;
        }
        .sign-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #00d4ff);
            border-radius: 3px;
            transition: width 0.3s;
        }
        
        .certificate-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .certificate-card:hover {
            border-color: #007bff;
            box-shadow: 0 0 15px rgba(0,123,255,0.1);
        }
        .certificate-card.selected {
            border-color: #28a745;
            background: #f8fff9;
            box-shadow: 0 0 15px rgba(40,167,69,0.2);
        }
        .certificate-card.expired {
            border-color: #dc3545;
            background: #fff5f5;
            opacity: 0.6;
        }
        .certificate-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
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
                
                <!-- Quick Stats -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Digital Signing Dashboard</h4>
                                    <p>Files ready for digital signature</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="text-center p-3 border rounded">
                                                <h2 class="text-primary mb-1"><?php echo count($ready_files); ?></h2>
                                                <p class="mb-0">Ready to Sign</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="text-center p-3 border rounded">
                                                <h2 class="text-success mb-1"><?php echo count($signing_history); ?></h2>
                                                <p class="mb-0">Recently Signed</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="text-center p-3 border rounded">
                                                <h2 class="text-warning mb-1">
                                                    <?php 
                                                    $pending = 0;
                                                    foreach ($ready_files as $file) {
                                                        if (strtotime($file['created_at']) < strtotime('-3 days')) {
                                                            $pending++;
                                                        }
                                                    }
                                                    echo $pending;
                                                    ?>
                                                </h2>
                                                <p class="mb-0">Pending >3 days</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="text-center p-3 border rounded">
                                                <h2 class="text-info mb-1">
                                                    <?php
                                                    $total_amount = 0;
                                                    foreach ($ready_files as $file) {
                                                        $total_amount += $file['total_amount'];
                                                    }
                                                    echo number_format($total_amount);
                                                    ?>
                                                </h2>
                                                <p class="mb-0">Total Value (MWK)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Files Ready for Signing -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Files Ready for Signing</h4>
                                    <div class="float-end">
                                        <button class="btn btn-sm btn-primary" id="refreshQueue">
                                            <i class="fa fa-sync-alt"></i> Refresh
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" id="queueContainer">
                                    <?php if (empty($ready_files)): ?>
                                        <div class="text-center py-5">
                                            <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                                            <h5>No files in queue</h5>
                                            <p class="text-muted">All files are signed! Check back later.</p>
                                            <a href="./batches.php" class="btn btn-primary mt-2">
                                                <i class="fa fa-layer-group"></i> View All Batches
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($ready_files as $file): 
                                            $is_old = strtotime($file['created_at']) < strtotime('-3 days');
                                            $is_priority = $file['total_amount'] > 1000000; // Priority for large amounts
                                            $queue_class = '';
                                            if ($is_priority) $queue_class = 'priority';
                                            if ($is_old) $queue_class = 'old';
                                        ?>
                                        <div class="queue-item <?php echo $queue_class; ?>" data-id="<?php echo $file['id']; ?>">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        <?php echo htmlspecialchars($file['batch_number']); ?>
                                                        <?php if ($is_priority): ?>
                                                            <span class="badge badge-danger ml-2">Priority</span>
                                                        <?php endif; ?>
                                                        <?php if ($is_old): ?>
                                                            <span class="badge badge-warning ml-2">Old</span>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <p class="mb-1 text-muted">
                                                        <strong><?php echo htmlspecialchars($file['reference_no']); ?></strong> | 
                                                        <?php echo htmlspecialchars($file['file_type']); ?> | 
                                                        Created: <?php echo date('d/m/Y', strtotime($file['created_at'])); ?>
                                                    </p>
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <small class="text-success">
                                                                <i class="fa fa-money-bill"></i> 
                                                                MWK <?php echo number_format($file['total_amount'], 2); ?>
                                                            </small>
                                                        </div>
                                                        <div class="me-3">
                                                            <small class="text-info">
                                                                <i class="fa fa-file-alt"></i> 
                                                                <?php echo $file['total_count']; ?> records
                                                            </small>
                                                        </div>
                                                        <div>
                                                            <small class="text-primary">
                                                                <i class="fa fa-user"></i> 
                                                                <?php echo htmlspecialchars($file['created_by_name']); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <?php if ($file['already_signed'] > 0): ?>
                                                        <div class="sign-progress">
                                                            <div class="sign-progress-bar" style="width: 100%"></div>
                                                        </div>
                                                        <small class="text-success">
                                                            <i class="fa fa-check-circle"></i> Already signed
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="./batch-detail.php?id=<?php echo $file['id']; ?>" 
                                                           class="btn btn-info" title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="./sign-file.php?batch_id=<?php echo $file['id']; ?>" 
                                                           class="btn btn-success" title="Sign Now">
                                                            <i class="fa fa-signature"></i> Sign
                                                        </a>
                                                        <?php if ($_SESSION["bt_role"] == 'admin'): ?>
                                                            <button type="button" class="btn btn-warning prepare-sign" 
                                                                    data-id="<?php echo $file['id']; ?>" title="Re-prepare">
                                                                <i class="fa fa-redo"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="text-center">
                                        <small class="text-muted">
                                            Showing <?php echo count($ready_files); ?> files ready for signing
                                            <?php if (!empty($ready_files)): ?>
                                                | <a href="./sign-file.php?batch_id=<?php echo $ready_files[0]['id']; ?>" 
                                                     class="text-primary">
                                                    <i class="fa fa-bolt"></i> Sign First File
                                                </a>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Available Certificates & Quick Actions -->
                        <div class="col-lg-4">
                            <!-- Available Certificates -->
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Available Certificates</h4>
                                </div>
                                <div class="card-body" id="certificatesList">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading certificates...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Sign Stats -->
                            <div class="card mt-4">
                                <div class="card-header pb-0">
                                    <h4>Signing Statistics</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <small class="text-muted">Today's Signatures</small>
                                        <h5>0</h5>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">This Week</small>
                                        <h5>0</h5>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">This Month</small>
                                        <h5>0</h5>
                                    </div>
                                    <div>
                                        <small class="text-muted">Total Signed Files</small>
                                        <h5><?php echo count($signing_history); ?></h5>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="card mt-4">
                                <div class="card-header pb-0">
                                    <h4>Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="./uploadNew.php" class="btn btn-primary">
                                            <i class="fa fa-upload"></i> Upload & Sign New File
                                        </a>
                                        <a href="./batches.php" class="btn btn-outline-primary">
                                            <i class="fa fa-layer-group"></i> View All Batches
                                        </a>
                                        <a href="./certs.php" class="btn btn-outline-success">
                                            <i class="fa fa-shield-alt"></i> Manage Certificates
                                        </a>
                                        <button class="btn btn-outline-info" id="checkSystemStatus">
                                            <i class="fa fa-heartbeat"></i> Check System Status
                                        </button>
                                    </div>
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
                                    <?php if (empty($signing_history)): ?>
                                        <p class="text-muted">No signing history available.</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Batch #</th>
                                                        <th>File Type</th>
                                                        <th>Amount</th>
                                                        <th>Signed By</th>
                                                        <th>Signed Date</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($signing_history as $history): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($history['batch_number']); ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($history['reference_no']); ?></small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($history['file_type']); ?></td>
                                                        <td>
                                                            <strong><?php echo number_format($history['total_amount'], 2); ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?php echo $history['total_count']; ?> records</small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($history['signed_by_name']); ?></td>
                                                        <td>
                                                            <?php echo date('d/m/Y H:i', strtotime($history['signed_at'])); ?>
                                                            <br>
                                                            <small class="text-muted"><?php echo htmlspecialchars($history['signature_type']); ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?php 
                                                                echo $history['verification_status'] == 'verified' ? 'success' : 
                                                                ($history['verification_status'] == 'failed' ? 'danger' : 'warning');
                                                            ?>">
                                                                <?php echo ucfirst($history['verification_status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php if ($history['signed_file_path'] && file_exists($history['signed_file_path'])): ?>
                                                                <a href="./php/file_handler.php?action=download_signed&id=<?php echo $history['id']; ?>" 
                                                                   class="btn btn-sm btn-success" title="Download Signed">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <a href="./batch-detail.php?id=<?php echo $history['id']; ?>" 
                                                               class="btn btn-sm btn-info" title="View Details">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="./signed-files.php" class="btn btn-outline-primary btn-sm">
                                                View All Signed Files <i class="fa fa-arrow-right"></i>
                                            </a>
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
        // Load certificates
        loadCertificates();
        
        // Refresh queue
        $('#refreshQueue').click(function() {
            location.reload();
        });
        
        // Prepare for signing
        $(document).on('click', '.prepare-sign', function() {
            var batchId = $(this).data('id');
            
            Swal.fire({
                title: 'Re-prepare File?',
                text: "This will reset the file status to 'ready_to_sign'",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, re-prepare'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/batch_handler.php',
                        type: 'POST',
                        data: {
                            prepare_for_signing: '1',
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
        
        // Check system status
        $('#checkSystemStatus').click(function() {
            Swal.fire({
                title: 'Checking System Status...',
                html: '<div class="text-left">' +
                      '<p><i class="fa fa-check text-success"></i> Database Connection: OK</p>' +
                      '<p><i class="fa fa-check text-success"></i> File System: OK</p>' +
                      '<p><i class="fa fa-check text-success"></i> OpenSSL: OK</p>' +
                      '<p><i class="fa fa-check text-success"></i> Certificate Store: OK</p>' +
                      '</div>',
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            });
        });
        
        // Function to load certificates
        function loadCertificates() {
            $.ajax({
                url: './php/batch_handler.php',
                type: 'POST',
                data: { get_certificates: '1' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var html = '';
                        if (response.certificates.length === 0) {
                            html = '<div class="alert alert-warning">' +
                                   '<i class="fa fa-exclamation-triangle"></i> ' +
                                   'No certificates available. <a href="./addCert.php">Add a certificate</a>' +
                                   '</div>';
                        } else {
                            response.certificates.forEach(function(cert, index) {
                                var isDefault = cert.is_default ? '<span class="certificate-badge"><i class="fa fa-star"></i></span>' : '';
                                var isExpired = new Date(cert.valid_to) < new Date();
                                var cardClass = isExpired ? 'certificate-card expired' : 'certificate-card';
                                
                                html += '<div class="' + cardClass + '" data-id="' + cert.id + '">' +
                                        isDefault +
                                        '<h6 class="mb-1">' + cert.certificate_name + '</h6>' +
                                        '<p class="mb-1 text-muted">' + cert.issuer + '</p>' +
                                        '<small class="text-muted">Valid until: ' + cert.valid_to + '</small>' +
                                        '</div>';
                            });
                        }
                        $('#certificatesList').html(html);
                        
                        // Certificate selection
                        $('.certificate-card:not(.expired)').click(function() {
                            $('.certificate-card').removeClass('selected');
                            $(this).addClass('selected');
                            var certId = $(this).data('id');
                            // You can store selected certificate ID for later use
                            sessionStorage.setItem('selected_certificate', certId);
                        });
                        
                        // Select first valid certificate by default
                        $('.certificate-card:not(.expired)').first().click();
                    } else {
                        $('#certificatesList').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#certificatesList').html('<div class="alert alert-danger">Failed to load certificates</div>');
                }
            });
        }
        
        // Auto-refresh queue every 30 seconds
        setInterval(function() {
            $.ajax({
                url: window.location.href,
                type: 'GET',
                success: function(data) {
                    // Extract queue container from response
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;
                    var newQueue = tempDiv.querySelector('#queueContainer');
                    if (newQueue) {
                        document.getElementById('queueContainer').innerHTML = newQueue.innerHTML;
                    }
                }
            });
        }, 30000); // 30 seconds
        
        // Queue item click
        $(document).on('click', '.queue-item', function(e) {
            // Only trigger if not clicking on a button
            if (!$(e.target).closest('a, button').length) {
                var batchId = $(this).data('id');
                window.location.href = './sign-file.php?batch_id=' + batchId;
            }
        });
    });
    </script>
</body>
</html>