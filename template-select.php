<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
}

$templateMgr = new template_manager();
$templates = $templateMgr->get_all_templates();
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
    <title>JPI Systems - Select Template</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
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
                                <h3>Select Template</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">File Templates</li>
                                    <li class="breadcrumb-item active">Select Template</li>
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
                                    <h4>Available File Templates</h4>
                                    <p>Select a template to create a new file batch</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php if (empty($templates)): ?>
                                        <div class="col-md-12 text-center py-5">
                                            <i class="fa fa-file-alt fa-3x text-muted mb-3"></i>
                                            <h4>No Templates Available</h4>
                                            <p class="text-muted">Contact your administrator to set up file templates.</p>
                                        </div>
                                        <?php else: ?>
                                            <?php foreach ($templates as $template): 
                                                $sample = $templateMgr->get_template_sample($template['id']);
                                            ?>
                                            <div class="col-md-4 mb-4">
                                                <div class="card h-100 template-select-card">
                                                    <div class="card-header">
                                                        <h5 class="mb-0"><?php echo htmlspecialchars($template['name']); ?></h5>
                                                        <small class="text-muted"><?php echo htmlspecialchars($template['code']); ?></small>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text"><?php echo htmlspecialchars($template['description']); ?></p>
                                                        
                                                        <div class="template-meta mb-3">
                                                            <span class="badge badge-light">
                                                                <i class="fa fa-list"></i> <?php echo $template['field_count']; ?> fields
                                                            </span>
                                                            <span class="badge badge-light">
                                                                <i class="fa fa-history"></i> Used <?php echo $template['usage_count']; ?> times
                                                            </span>
                                                        </div>
                                                        
                                                        <?php if ($sample): ?>
                                                        <div class="sample-preview">
                                                            <h6>Sample Format:</h6>
                                                            <div class="code-block">
                                                                <pre><code class="language-csv"><?php echo htmlspecialchars($sample['header']); ?><br><?php echo htmlspecialchars($sample['body']); ?></code></pre>
                                                            </div>
                                                            <small class="text-muted"><?php echo htmlspecialchars($sample['description']); ?></small>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="d-flex justify-content-between">
                                                            <a href="./template-view.php?id=<?php echo $template['id']; ?>" 
                                                               class="btn btn-outline-info btn-sm">
                                                                <i class="fa fa-info-circle"></i> Details
                                                            </a>
                                                            <a href="./create-batch.php?template=<?php echo $template['id']; ?>" 
                                                               class="btn btn-primary btn-sm">
                                                                <i class="fa fa-plus"></i> Use Template
                                                            </a>
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
                    
                    <!-- Quick Create Section -->
                    <div class="row mt-4">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Quick Create</h4>
                                    <p>Quickly create batches for common file types</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="quick-action-card text-center p-3">
                                                <div class="action-icon mb-2">
                                                    <i class="fa fa-money-bill-wave fa-2x text-primary"></i>
                                                </div>
                                                <h5>Payment File</h5>
                                                <p class="text-muted small">Standard payment transactions</p>
                                                <a href="./create-batch.php?type=payment" class="btn btn-outline-primary btn-sm">Create</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="quick-action-card text-center p-3">
                                                <div class="action-icon mb-2">
                                                    <i class="fa fa-exchange-alt fa-2x text-success"></i>
                                                </div>
                                                <h5>Remittance</h5>
                                                <p class="text-muted small">Remittance without PRN</p>
                                                <a href="./create-batch.php?type=remittance" class="btn btn-outline-success btn-sm">Create</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="quick-action-card text-center p-3">
                                                <div class="action-icon mb-2">
                                                    <i class="fa fa-globe fa-2x text-warning"></i>
                                                </div>
                                                <h5>Foreign Payment</h5>
                                                <p class="text-muted small">International payments</p>
                                                <a href="./create-batch.php?type=foreign" class="btn btn-outline-warning btn-sm">Create</a>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="quick-action-card text-center p-3">
                                                <div class="action-icon mb-2">
                                                    <i class="fa fa-users fa-2x text-info"></i>
                                                </div>
                                                <h5>Salary Payment</h5>
                                                <p class="text-muted small">Employee salary payments</p>
                                                <a href="./create-batch.php?type=salary" class="btn btn-outline-info btn-sm">Create</a>
                                            </div>
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
            // Template card hover effect
            $('.template-select-card').hover(
                function() {
                    $(this).addClass('shadow');
                },
                function() {
                    $(this).removeClass('shadow');
                }
            );
            
            // Quick action cards
            $('.quick-action-card').hover(
                function() {
                    $(this).addClass('bg-light');
                },
                function() {
                    $(this).removeClass('bg-light');
                }
            );
        });
    </script>
    
    <style>
        .template-select-card {
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .template-select-card:hover {
            border-color: #007bff;
            transform: translateY(-5px);
        }
        
        .template-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .code-block {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
        }
        
        .quick-action-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .quick-action-card:hover {
            border-color: #007bff;
            transform: translateY(-3px);
        }
        
        .action-icon {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</body>
</html>