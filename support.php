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
    <meta name="description" content="JPI Signing System - Support">
    <meta name="keywords" content="support, contact, helpdesk">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI Systems - Support</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
    <style>
        .support-section {
            margin-bottom: 30px;
        }
        .contact-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            height: 100%;
            transition: transform 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
        }
        .contact-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #24695c, #1a4d43);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }
        .ticket-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-open {
            background-color: #e8f4f1;
            color: #24695c;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-closed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .system-status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }
        .status-operational {
            background-color: #28a745;
            animation: pulse 2s infinite;
        }
        .status-degraded {
            background-color: #ffc107;
        }
        .status-outage {
            background-color: #dc3545;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .knowledge-item {
            padding: 15px;
            border-left: 3px solid #24695c;
            background-color: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 0 5px 5px 0;
        }
    </style>
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
                                <h3>Support</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Help</li>
                                    <li class="breadcrumb-item active">Support</li>
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
                                    <h4>Technical Support Center</h4>
                                    <p>Get help with technical issues, report problems, or contact our support team</p>
                                </div>
                                <div class="card-body">
                                    
                                    <!-- System Status -->
                                    <div class="support-section">
                                        <h5 class="mb-3">System Status</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6>Overall System Status</h6>
                                                                <div class="d-flex align-items-center mt-2">
                                                                    <span class="system-status-indicator status-operational"></span>
                                                                    <span class="fw-bold text-success">Operational</span>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <small>Last updated: Just now</small><br>
                                                                <small>Uptime: 99.9%</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>Component Status</h6>
                                                        <div class="row mt-3">
                                                            <div class="col-6">
                                                                <div class="mb-2">
                                                                    <span class="system-status-indicator status-operational"></span>
                                                                    <small>Web Application</small>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <span class="system-status-indicator status-operational"></span>
                                                                    <small>Database</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="mb-2">
                                                                    <span class="system-status-indicator status-operational"></span>
                                                                    <small>File Signing</small>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <span class="system-status-indicator status-operational"></span>
                                                                    <small>Certificate Service</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Options -->
                                    <div class="support-section">
                                        <h5 class="mb-4">Contact Support</h5>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 mb-4">
                                                <div class="contact-card">
                                                    <div class="contact-icon">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <h6>Email Support</h6>
                                                    <p>For non-urgent queries and detailed issues</p>
                                                    <div class="mt-3">
                                                        <a href="mailto:support@jpisystems.com" class="btn btn-outline-primary btn-sm">
                                                            <i class="fa fa-paper-plane"></i> Send Email
                                                        </a>
                                                    </div>
                                                    <p class="mt-3 mb-0">
                                                        <small><i class="fa fa-clock text-muted"></i> Response: 24-48 hours</small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-4">
                                                <div class="contact-card">
                                                    <div class="contact-icon">
                                                        <i class="fa fa-phone"></i>
                                                    </div>
                                                    <h6>Phone Support</h6>
                                                    <p>For urgent issues requiring immediate attention</p>
                                                    <div class="mt-3">
                                                        <a href="tel:+265991234567" class="btn btn-outline-primary btn-sm">
                                                            <i class="fa fa-phone"></i> Call Now
                                                        </a>
                                                    </div>
                                                    <p class="mt-3 mb-0">
                                                        <small><i class="fa fa-clock text-muted"></i> Hours: 8AM-5PM (Mon-Fri)</small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-4">
                                                <div class="contact-card">
                                                    <div class="contact-icon">
                                                        <i class="fa fa-ticket"></i>
                                                    </div>
                                                    <h6>Support Ticket</h6>
                                                    <p>Create a ticket for tracking complex issues</p>
                                                    <div class="mt-3">
                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ticketModal">
                                                            <i class="fa fa-plus-circle"></i> Create Ticket
                                                        </button>
                                                    </div>
                                                    <p class="mt-3 mb-0">
                                                        <small><i class="fa fa-clock text-muted"></i> Tracking: Ticket ID provided</small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 mb-4">
                                                <div class="contact-card">
                                                    <div class="contact-icon">
                                                        <i class="fa fa-comments"></i>
                                                    </div>
                                                    <h6>Live Chat</h6>
                                                    <p>Instant chat support for quick questions</p>
                                                    <div class="mt-3">
                                                        <button class="btn btn-outline-primary btn-sm" onclick="startLiveChat()">
                                                            <i class="fa fa-comment"></i> Start Chat
                                                        </button>
                                                    </div>
                                                    <p class="mt-3 mb-0">
                                                        <small><i class="fa fa-clock text-muted"></i> Available: 9AM-4PM</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Knowledge Base -->
                                    <div class="support-section">
                                        <h5 class="mb-4">Quick Solutions</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6>Common Issues & Solutions</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="knowledge-item">
                                                            <strong>File upload failing?</strong>
                                                            <p class="mb-0"><small>Check file size (max 10MB), format (CSV), and required fields.</small></p>
                                                        </div>
                                                        <div class="knowledge-item">
                                                            <strong>Certificate not working?</strong>
                                                            <p class="mb-0"><small>Verify certificate is in PEM format, not expired, and password is correct.</small></p>
                                                        </div>
                                                        <div class="knowledge-item">
                                                            <strong>Can't download signed file?</strong>
                                                            <p class="mb-0"><small>Check file permissions and browser settings. Try different browser.</small></p>
                                                        </div>
                                                        <div class="knowledge-item">
                                                            <strong>Slow performance?</strong>
                                                            <p class="mb-0"><small>Clear browser cache, check internet connection, or try during off-peak hours.</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6>Support Resources</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="list-group list-group-flush">
                                                            <a href="./documentation.php" class="list-group-item list-group-item-action">
                                                                <i class="fa fa-book text-primary me-2"></i>
                                                                Complete Documentation
                                                            </a>
                                                            <a href="./file-formats.php" class="list-group-item list-group-item-action">
                                                                <i class="fa fa-file-text text-success me-2"></i>
                                                                File Format Specifications
                                                            </a>
                                                            <a href="./faq.php" class="list-group-item list-group-item-action">
                                                                <i class="fa fa-question-circle text-info me-2"></i>
                                                                Frequently Asked Questions
                                                            </a>
                                                            <a href="#" class="list-group-item list-group-item-action" onclick="downloadUserGuide()">
                                                                <i class="fa fa-download text-warning me-2"></i>
                                                                Download User Guide (PDF)
                                                            </a>
                                                            <a href="#" class="list-group-item list-group-item-action" onclick="showVideoTutorials()">
                                                                <i class="fa fa-video text-danger me-2"></i>
                                                                Video Tutorials
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Support Team -->
                                    <div class="support-section">
                                        <h5 class="mb-4">Support Team</h5>
                                        <div class="row">
                                            <div class="col-md-4 mb-4">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="avatar avatar-lg mb-3">
                                                            <img class="rounded-circle" src="https://ui-avatars.com/api/?name=John+Doe&background=24695c&color=fff" alt="John Doe">
                                                        </div>
                                                        <h6>John Doe</h6>
                                                        <p class="text-muted mb-2">Technical Support Lead</p>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="mailto:john.doe@jpisystems.com" class="btn btn-sm btn-outline-secondary me-1">
                                                                <i class="fa fa-envelope"></i>
                                                            </a>
                                                            <a href="tel:+265991111111" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-phone"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-4">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="avatar avatar-lg mb-3">
                                                            <img class="rounded-circle" src="https://ui-avatars.com/api/?name=Jane+Smith&background=24695c&color=fff" alt="Jane Smith">
                                                        </div>
                                                        <h6>Jane Smith</h6>
                                                        <p class="text-muted mb-2">Certificate Specialist</p>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="mailto:jane.smith@jpisystems.com" class="btn btn-sm btn-outline-secondary me-1">
                                                                <i class="fa fa-envelope"></i>
                                                            </a>
                                                            <a href="tel:+265992222222" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-phone"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-4">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <div class="avatar avatar-lg mb-3">
                                                            <img class="rounded-circle" src="https://ui-avatars.com/api/?name=David+Wilson&background=24695c&color=fff" alt="David Wilson">
                                                        </div>
                                                        <h6>David Wilson</h6>
                                                        <p class="text-muted mb-2">File Processing Expert</p>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="mailto:david.wilson@jpisystems.com" class="btn btn-sm btn-outline-secondary me-1">
                                                                <i class="fa fa-envelope"></i>
                                                            </a>
                                                            <a href="tel:+265993333333" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-phone"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Emergency Contact -->
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Emergency Support</h5>
                                            <p class="mb-2">For critical system outages or security incidents outside business hours</p>
                                            <div class="mt-3">
                                                <a href="tel:+265994444444" class="btn btn-danger">
                                                    <i class="fa fa-phone"></i> Emergency Hotline: +265 994 444 444
                                                </a>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted">
                                                <small>Available 24/7 for critical issues only</small>
                                            </p>
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

    <!-- Support Ticket Modal -->
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">Create Support Ticket</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ticketForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Issue Type</label>
                                    <select class="form-select" id="issueType" required>
                                        <option value="">Select issue type</option>
                                        <option value="file_upload">File Upload Problem</option>
                                        <option value="certificate">Certificate Issue</option>
                                        <option value="signing">File Signing Error</option>
                                        <option value="download">Download Problem</option>
                                        <option value="performance">Performance Issue</option>
                                        <option value="bug">System Bug</option>
                                        <option value="feature">Feature Request</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select class="form-select" id="priority" required>
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" class="form-control" id="ticketSubject" placeholder="Brief description of the issue" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="ticketDescription" rows="5" placeholder="Please provide detailed description of the issue, steps to reproduce, and any error messages..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachments (Optional)</label>
                            <input class="form-control" type="file" id="ticketAttachment" multiple>
                            <small class="form-text text-muted">You can attach screenshots, error logs, or sample files (max 5MB total)</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notifyEmail" checked>
                                <label class="form-check-label" for="notifyEmail">
                                    Send email notifications about ticket updates
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="button" onclick="submitTicket()">Submit Ticket</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "includes/scripts.php"; ?>
    
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Check system status
            checkSystemStatus();
        });
        
        function checkSystemStatus() {
            // Simulated system status check
            $.ajax({
                url: './php/system-status.php',
                method: 'GET',
                success: function(response) {
                    // Update status indicators
                    // This would be implemented with actual API calls
                },
                error: function() {
                    // Handle error
                }
            });
        }
        
        function startLiveChat() {
            Swal.fire({
                title: 'Live Chat',
                html: `
                    <div class="text-center">
                        <i class="fa fa-comments fa-3x text-primary mb-3"></i>
                        <p>Our live chat service is currently offline.</p>
                        <p>Please use email or phone support for immediate assistance.</p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
        
        function submitTicket() {
            var issueType = $('#issueType').val();
            var priority = $('#priority').val();
            var subject = $('#ticketSubject').val();
            var description = $('#ticketDescription').val();
            
            if (!issueType || !subject || !description) {
                Swal.fire({
                    title: 'Incomplete Form',
                    text: 'Please fill all required fields',
                    icon: 'warning'
                });
                return;
            }
            
            // Simulate ticket submission
            var ticketId = 'TKT-' + Math.floor(Math.random() * 1000000);
            
            Swal.fire({
                title: 'Ticket Submitted!',
                html: `
                    <div class="text-center">
                        <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                        <p>Your support ticket has been created successfully.</p>
                        <p><strong>Ticket ID:</strong> ${ticketId}</p>
                        <p>You will receive an email confirmation shortly.</p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                $('#ticketModal').modal('hide');
                $('#ticketForm')[0].reset();
            });
        }
        
        function downloadUserGuide() {
            Swal.fire({
                title: 'Download User Guide',
                html: `
                    <div class="text-center">
                        <i class="fa fa-file-pdf fa-3x text-danger mb-3"></i>
                        <p>The user guide is currently being updated.</p>
                        <p>Please check back soon or contact support for documentation.</p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
        
        function showVideoTutorials() {
            Swal.fire({
                title: 'Video Tutorials',
                html: `
                    <div class="text-center">
                        <i class="fa fa-video fa-3x text-primary mb-3"></i>
                        <p>Video tutorials are coming soon!</p>
                        <p>In the meantime, please refer to the documentation and FAQ sections.</p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
        }
        
        // Auto-populate user info in ticket form
        $('#ticketModal').on('show.bs.modal', function() {
            var userEmail = '<?php echo $_SESSION["bt_email"] ?? "Not available"; ?>';
            var userName = '<?php echo $_SESSION["bt_username"] ?? "Not available"; ?>';
            
            $('#ticketDescription').val(`User: ${userName}\nEmail: ${userEmail}\n\nIssue details:\n`);
        });
    </script>
</body>
</html>