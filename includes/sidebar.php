<div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="assets/images/logo/logo2.png" alt=""></a>
              <div class="back-btn"><i class="fa fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"></i></div>
            </div>
<div class="logo-icon-wrapper">
                <a href="index.html">
                    <div class="icon-box-sidebar"><i data-feather="grid"></i></div>
                </a>
            </div>
<nav class="sidebar-main">
                    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                    <div id="sidebar-menu">
                        <ul class="sidebar-links" id="simple-bar">
                        <li class="back-btn">
                            <div class="mobile-back text-end">
                                <span>Back</span>
                                <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
                            </div>
                        </li>

                        <li class="pin-title sidebar-list">
                            <h6>Pinned</h6>
                        </li>
                        <hr>

                        <!-- DASHBOARD -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="./" target="_blank">
                                <i data-feather="home"></i><span>Dashboard</span>
                            </a>
                        </li>

                        <!-- FILE CREATION & GENERATION -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="file-plus"></i><span>File Creation</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./create-batch.php">Create New Batch</a></li>
                                <li><a href="./upload-sage.php">Convert Sage Data</a></li> <!-- Renamed -->
                                <li><a href="./obdx-upload.php">Upload OBDX File</a></li> <!-- NEW -->
                                <li><a href="./template-select.php">Select Template</a></li>
                                <li><a href="./batch-drafts.php">Draft Batches</a></li>
                            </ul>
                        </li>

                        <!-- CSV UPLOADS & SIGNING -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="file-text"></i><span>File Signing</span> <!-- Renamed -->
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./uploads.php">View All Files</a></li>
                                <li><a href="./uploadNew.php">Upload & Sign CSV</a></li> <!-- Generic CSV -->
                                <li><a href="./generated-files.php">Generated Files</a></li>
                                <li><a href="./sign-queue.php">Signing Queue</a></li>
                            </ul>
                        </li>

                        <!-- FILE TEMPLATES -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="file-type"></i><span>File Templates</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./templates.php">View Templates</a></li>
                                <li><a href="./template-payment.php">Payment File</a></li>
                                <li><a href="./template-remittance.php">Remittance</a></li>
                                <li><a href="./template-foreign.php">Foreign Payment</a></li>
                                <li><a href="./template-salary.php">Salary Payment</a></li>
                            </ul>
                        </li>

                        <!-- VALIDATION & RULES -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="check-circle"></i><span>Validation</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./validation-rules.php">Rules</a></li>
                                <li><a href="./field-validation.php">Field Validation</a></li>
                                <li><a href="./duplicate-check.php">Duplicate Check</a></li>
                            </ul>
                        </li>

                        <!-- CERTIFICATES -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="shield"></i><span>Certificates</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./certs.php">View All</a></li>
                                <li><a href="./addCert.php">Upload New</a></li>
                                <li><a href="./cert-expiry.php">Expiry Monitor</a></li>
                                <li><a href="./sign-history.php">Signing History</a></li>
                            </ul>
                        </li>

                        <!-- AUDIT & REPORTS -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="clipboard"></i><span>Audit & Reports</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./audit-logs.php">Audit Logs</a></li>
                                <li><a href="./activity-log.php">Activity Log</a></li>
                                <li><a href="./reports.php">Reports</a></li>
                                <li><a href="./export-data.php">Data Export</a></li>
                            </ul>
                        </li>

                        <!-- USERS -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="users"></i><span>Users</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./users.php">View All</a></li>
                                <li><a href="./addUser.php">Add New</a></li>
                                <li><a href="./user-roles.php">Roles & Permissions</a></li>
                                <li><a href="./login-history.php">Login History</a></li>
                            </ul>
                        </li>

                        <!-- SYSTEM SETTINGS -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="settings"></i><span>Settings</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./config.php">Configuration</a></li>
                                <li><a href="./system-health.php">System Health</a></li>
                                <li><a href="./backup.php">Backup & Restore</a></li>
                                <li><a href="./api-settings.php">API Settings</a></li>
                            </ul>
                        </li>

                        <!-- HELP & SUPPORT -->
                        <li class="sidebar-list">
                            <i class="fa fa-thumb-tack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <i data-feather="help-circle"></i><span>Help</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="./documentation.php">Documentation</a></li>
                                <li><a href="./file-formats.php">File Formats</a></li>
                                <li><a href="./faq.php">FAQ</a></li>
                                <li><a href="./support.php">Support</a></li>
                            </ul>
                        </li>
                    </ul>
                    </div>
                    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                </nav>