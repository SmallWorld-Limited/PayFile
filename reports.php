<?php 
include './php/admin.php';
if (!isset($_SESSION["bt_user_id"])) {
    header("Location: ./login.php");
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
    <title>JPI Systems - Reports</title>
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
                                <h3>Reports</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Audit & Reports</li>
                                    <li class="breadcrumb-item active">Reports</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <!-- Quick Stats Cards -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="square-after f-w-600 header-text-primary">Total Files<i class="fa fa-circle"> </i></p>
                                            <h4><?php echo (new reports_manager())->get_total_files_count(); ?></h4>
                                        </div>
                                        <div class="d-flex static-widget">
                                            <i class="fa fa-file-text fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-lg-3">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="square-after f-w-600 header-text-success">Total Signed<i class="fa fa-circle"> </i></p>
                                            <h4><?php echo (new reports_manager())->get_signed_files_count(); ?></h4>
                                        </div>
                                        <div class="d-flex static-widget">
                                            <i class="fa fa-file-signature fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-lg-3">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="square-after f-w-600 header-text-warning">Total Users<i class="fa fa-circle"> </i></p>
                                            <h4><?php echo (new reports_manager())->get_total_users_count(); ?></h4>
                                        </div>
                                        <div class="d-flex static-widget">
                                            <i class="fa fa-users fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-lg-3">
                            <div class="card o-hidden">
                                <div class="card-header pb-0">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="square-after f-w-600 header-text-info">Total Amount<i class="fa fa-circle"> </i></p>
                                            <h4><?php echo number_format((new reports_manager())->get_total_amount(), 2); ?> MWK</h4>
                                        </div>
                                        <div class="d-flex static-widget">
                                            <i class="fa fa-money-bill fa-2x text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Monthly Signing Report</h4>
                                </div>
                                <div class="card-body">
                                    <div id="monthly-report-chart"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Generate Report</h4>
                                </div>
                                <div class="card-body">
                                    <form id="generateReportForm">
                                        <div class="form-group">
                                            <label>Report Type</label>
                                            <select class="form-control" id="reportType" required>
                                                <option value="">Select Report Type</option>
                                                <option value="signing_summary">Signing Summary</option>
                                                <option value="user_activity">User Activity</option>
                                                <option value="file_type_distribution">File Type Distribution</option>
                                                <option value="certificate_usage">Certificate Usage</option>
                                                <option value="batch_status">Batch Status Report</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Date Range</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="date" class="form-control" id="reportDateFrom">
                                                </div>
                                                <div class="col-6">
                                                    <input type="date" class="form-control" id="reportDateTo">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Format</label>
                                            <select class="form-control" id="reportFormat">
                                                <option value="html">HTML</option>
                                                <option value="pdf">PDF</option>
                                                <option value="csv">CSV</option>
                                                <option value="excel">Excel</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fa fa-cogs"></i> Generate Report
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>Recent Signed Files</h4>
                                </div>
                                <div class="card-body">
                                    <div class="dt-ext table-responsive theme-scrollbar">
                                        <table class="display dataTable" id="recent-files-table" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>Batch ID</th>
                                                    <th>File Name</th>
                                                    <th>File Type</th>
                                                    <th>Signed By</th>
                                                    <th>Signed Date</th>
                                                    <th>Amount</th>
                                                    <th>Certificate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $reports = new reports_manager();
                                                $reports->list_recent_signed_files();
                                                ?>
                                            </tbody>
                                        </table>
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
        $(document).ready(function () {
            // Initialize DataTable
            $('#recent-files-table').DataTable({
                pageLength: 10,
                order: [[4, 'desc']]
            });
            
            // Monthly Report Chart
            var monthlyData = <?php echo (new reports_manager())->get_monthly_signing_data(); ?>;
            
            var options = {
                chart: {
                    height: 350,
                    type: 'bar',
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [{
                    name: 'Files Signed',
                    data: monthlyData.data
                }],
                xaxis: {
                    categories: monthlyData.categories,
                },
                yaxis: {
                    title: {
                        text: 'Number of Files'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " files"
                        }
                    }
                }
            };
            
            var chart = new ApexCharts(document.querySelector("#monthly-report-chart"), options);
            chart.render();
            
            // Generate Report Form
            $('#generateReportForm').submit(function (e) {
                e.preventDefault();
                
                var reportType = $('#reportType').val();
                var dateFrom = $('#reportDateFrom').val();
                var dateTo = $('#reportDateTo').val();
                var format = $('#reportFormat').val();
                
                if (!reportType) {
                    Swal.fire('Error', 'Please select a report type', 'error');
                    return;
                }
                
                // Show loading
                Swal.fire({
                    title: 'Generating Report',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit via AJAX
                $.ajax({
                    url: './php/report_handler.php',
                    type: 'POST',
                    data: {
                        action: 'generate_report',
                        report_type: reportType,
                        date_from: dateFrom,
                        date_to: dateTo,
                        format: format
                    },
                    success: function (response) {
                        try {
                            var res = JSON.parse(response);
                            if (res.success) {
                                Swal.fire({
                                    title: 'Success',
                                    text: res.message,
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonText: 'Download Report',
                                    cancelButtonText: 'Close'
                                }).then((result) => {
                                    if (result.isConfirmed && res.download_url) {
                                        window.location.href = res.download_url;
                                    }
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'Invalid response from server', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to generate report', 'error');
                    }
                });
            });
        });
    </script>
</body>
</html>