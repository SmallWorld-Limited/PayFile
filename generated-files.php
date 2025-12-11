<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

// Get generated files
$conn = new db_connect();
$user_id = $_SESSION["bt_user_id"];
$role = $_SESSION["bt_role"];

// Build query based on user role
if ($role == 'admin') {
    $stmt = $conn->connect()->prepare("
        SELECT 
            gf.id,
            gf.file_name,
            gf.file_path,
            gf.file_size,
            gf.mime_type,
            gf.created_at,
            fb.batch_number,
            fb.reference_no,
            ft.name as file_type,
            u.username as generated_by
        FROM generated_files gf
        LEFT JOIN file_batches fb ON gf.batch_id = fb.id
        LEFT JOIN file_types ft ON fb.file_type_id = ft.id
        LEFT JOIN users u ON gf.generated_by = u.user_id
        ORDER BY gf.created_at DESC
    ");
} else {
    $stmt = $conn->connect()->prepare("
        SELECT 
            gf.id,
            gf.file_name,
            gf.file_path,
            gf.file_size,
            gf.mime_type,
            gf.created_at,
            fb.batch_number,
            fb.reference_no,
            ft.name as file_type,
            u.username as generated_by
        FROM generated_files gf
        LEFT JOIN file_batches fb ON gf.batch_id = fb.id
        LEFT JOIN file_types ft ON fb.file_type_id = ft.id
        LEFT JOIN users u ON gf.generated_by = u.user_id
        WHERE gf.generated_by = ?
        ORDER BY gf.created_at DESC
    ");
    $stmt->bind_param('i', $user_id);
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
    <title>JPI Systems - Generated Files</title>
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
                                <h3>Generated Files</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Signing</li>
                                    <li class="breadcrumb-item active">Generated Files</li>
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
                                    <h4>All Generated Files</h4>
                                    <p>Files that have been generated and are ready for signing</p>
                                </div>
                                <div class="card-body">
                                    <?php 
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    
                                    if ($result->num_rows === 0): ?>
                                    <div class="text-center py-5">
                                        <i class="fa fa-file-alt fa-3x text-muted mb-3"></i>
                                        <h4>No Generated Files</h4>
                                        <p class="text-muted">No files have been generated yet. Generate files from batches.</p>
                                        <a href="./create-batch.php" class="btn btn-primary mt-2">
                                            <i class="fa fa-plus"></i> Create Batch
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="generated-files-table" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>ID</th>
                                                    <th>File Name</th>
                                                    <th>Batch #</th>
                                                    <th>Reference</th>
                                                    <th>File Type</th>
                                                    <th>Size</th>
                                                    <th>Generated By</th>
                                                    <th>Generated At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()): 
                                                    $file_size = $row['file_size'] ? round($row['file_size'] / 1024, 2) . ' KB' : 'N/A';
                                                    $created_at = date('Y-m-d H:i', strtotime($row['created_at']));
                                                    $file_exists = file_exists($row['file_path']);
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['batch_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['reference_no']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['file_type']); ?></td>
                                                    <td><?php echo $file_size; ?></td>
                                                    <td><?php echo htmlspecialchars($row['generated_by']); ?></td>
                                                    <td><?php echo $created_at; ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <?php if ($file_exists): ?>
                                                            <a href="./php/file_handler.php?action=download_generated&id=<?php echo $row['id']; ?>" 
                                                               class="btn btn-info btn-sm" title="Download">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                            <button class="btn btn-success btn-sm prepare-sign" 
                                                                    data-file-id="<?php echo $row['id']; ?>"
                                                                    data-batch-id="<?php echo $row['batch_id'] ?? ''; ?>"
                                                                    title="Prepare for Signing">
                                                                <i class="fa fa-signature"></i>
                                                            </button>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ($_SESSION["bt_role"] == 'admin' || $_SESSION["bt_user_id"] == $row['generated_by']): ?>
                                                            <button class="btn btn-danger btn-sm delete-generated" 
                                                                    data-file-id="<?php echo $row['id']; ?>"
                                                                    title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics Card -->
                    <div class="row mt-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-primary">
                                            <i class="fa fa-file-alt"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0"><?php echo $result->num_rows; ?></h5>
                                            <p class="text-muted mb-0">Total Files</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-success">
                                            <i class="fa fa-hdd"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">
                                                <?php 
                                                $size_stmt = $conn->connect()->prepare("SELECT SUM(file_size) as total_size FROM generated_files");
                                                $size_stmt->execute();
                                                $size_result = $size_stmt->get_result();
                                                $size_row = $size_result->fetch_assoc();
                                                echo round($size_row['total_size'] / (1024*1024), 2) . ' MB';
                                                ?>
                                            </h5>
                                            <p class="text-muted mb-0">Total Size</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-info">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">
                                                <?php 
                                                $today_stmt = $conn->connect()->prepare("SELECT COUNT(*) as count FROM generated_files WHERE DATE(created_at) = CURDATE()");
                                                $today_stmt->execute();
                                                $today_result = $today_stmt->get_result();
                                                $today_row = $today_result->fetch_assoc();
                                                echo $today_row['count'];
                                                ?>
                                            </h5>
                                            <p class="text-muted mb-0">Today</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-warning">
                                            <i class="fa fa-file-signature"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">
                                                <?php 
                                                $ready_stmt = $conn->connect()->prepare("
                                                    SELECT COUNT(*) as count 
                                                    FROM file_batches 
                                                    WHERE status = 'generated'
                                                ");
                                                $ready_stmt->execute();
                                                $ready_result = $ready_stmt->get_result();
                                                $ready_row = $ready_result->fetch_assoc();
                                                echo $ready_row['count'];
                                                ?>
                                            </h5>
                                            <p class="text-muted mb-0">Ready to Sign</p>
                                        </div>
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
            // Initialize DataTable
            $('#generated-files-table').DataTable({
                pageLength: 10,
                responsive: true,
                order: [[7, 'desc']] // Sort by created date descending
            });
            
            // Prepare for signing
            $(document).on('click', '.prepare-sign', function () {
                const fileId = $(this).data('file-id');
                const batchId = $(this).data('batch-id');
                
                Swal.fire({
                    title: 'Prepare for Signing?',
                    text: 'This will prepare the file for digital signing.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, prepare it'
                }).then((result) => {
                    if (result.isConfirmed) {
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
                                            window.location.href = './sign-queue.php';
                                        });
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid response from server', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to prepare file', 'error');
                            }
                        });
                    }
                });
            });
            
            // Delete generated file
            $(document).on('click', '.delete-generated', function () {
                const fileId = $(this).data('file-id');
                
                Swal.fire({
                    title: 'Delete File?',
                    text: "This will permanently delete the generated file!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: './php/file_handler.php',
                            type: 'POST',
                            data: {
                                delete_generated: 1,
                                file_id: fileId
                            },
                            success: function(response) {
                                try {
                                    const res = JSON.parse(response);
                                    if (res.id == 1) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: res.mssg,
                                            icon: 'success'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire('Error', res.mssg, 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid response from server', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to delete file', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
    
    <style>
        .stat-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .stat-icon.bg-primary { background-color: #007bff; }
        .stat-icon.bg-success { background-color: #28a745; }
        .stat-icon.bg-info { background-color: #17a2b8; }
        .stat-icon.bg-warning { background-color: #ffc107; }
    </style>
</body>
</html>