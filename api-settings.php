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
    <title>JPI Systems - API Settings</title>
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
                                <h3>API Settings</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./"><i data-feather="home"></i></a></li>
                                    <li class="breadcrumb-item">Settings</li>
                                    <li class="breadcrumb-item active">API Settings</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <!-- API Overview -->
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>API Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-success me-3">
                                            <i data-feather="check-circle" class="font-success" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3><span class="badge badge-success">Active</span></h3>
                                            <span class="f-w-600">API Service</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Total Calls</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-primary me-3">
                                            <i data-feather="activity" class="font-primary" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3 id="totalCalls">0</h3>
                                            <span class="f-w-600">This Month</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>API Keys</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-warning me-3">
                                            <i data-feather="key" class="font-warning" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3 id="totalKeys">0</h3>
                                            <span class="f-w-600">Active Keys</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Error Rate</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="font-danger me-3">
                                            <i data-feather="alert-circle" class="font-danger" style="width: 40px; height: 40px;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h3 id="errorRate">0%</h3>
                                            <span class="f-w-600">Last 24h</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- API Configuration -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>API Configuration</h4>
                                </div>
                                <div class="card-body">
                                    <form id="apiConfigForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">API Base URL</label>
                                                    <input class="form-control" type="text" id="api_base_url" name="api_base_url" 
                                                           value="<?php echo rtrim($_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']), '/') . '/api/'; ?>" readonly>
                                                    <small class="form-text text-muted">Base URL for API endpoints</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">API Version</label>
                                                    <input class="form-control" type="text" id="api_version" name="api_version" value="v1">
                                                    <small class="form-text text-muted">Current API version</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Rate Limit (per minute)</label>
                                                    <input class="form-control" type="number" id="rate_limit" name="rate_limit" value="60">
                                                    <small class="form-text text-muted">Maximum requests per minute per API key</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Enable API Logging</label>
                                                    <select class="form-control" id="enable_logging" name="enable_logging">
                                                        <option value="1">Enabled</option>
                                                        <option value="0">Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">API Authentication</label>
                                                    <select class="form-control" id="api_auth" name="api_auth">
                                                        <option value="api_key">API Key</option>
                                                        <option value="jwt">JWT Token</option>
                                                        <option value="both">Both</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Enable CORS</label>
                                                    <select class="form-control" id="enable_cors" name="enable_cors">
                                                        <option value="1">Enabled</option>
                                                        <option value="0">Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Allowed Origins (for CORS)</label>
                                                    <textarea class="form-control" id="allowed_origins" name="allowed_origins" 
                                                              rows="3" placeholder="Enter allowed origins, one per line"></textarea>
                                                    <small class="form-text text-muted">Leave empty to allow all origins</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <div class="text-end mt-3">
                                                        <button class="btn btn-secondary" type="button" onclick="resetApiConfig()">Reset</button>
                                                        <button class="btn btn-primary" type="submit">Save Configuration</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- API Keys Management -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>API Keys Management</h4>
                                    <button class="btn btn-sm btn-primary" onclick="generateApiKey()">
                                        <i data-feather="plus" class="me-2"></i> Generate New Key
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive theme-scrollbar">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>API Key</th>
                                                    <th>Name</th>
                                                    <th>Created</th>
                                                    <th>Last Used</th>
                                                    <th>Calls</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="apiKeysList">
                                                <!-- API Keys will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- API Endpoints -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>API Endpoints</h4>
                                </div>
                                <div class="card-body">
                                    <div class="accordion" id="apiEndpointsAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                    <strong>File Upload & Signing</strong>
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#apiEndpointsAccordion">
                                                <div class="accordion-body">
                                                    <div class="endpoint-item mb-3">
                                                        <code>POST /api/v1/upload</code>
                                                        <p class="mt-2 mb-1">Upload and sign a file via API</p>
                                                        <small class="text-muted">Requires: API Key, File (multipart/form-data)</small>
                                                    </div>
                                                    <div class="endpoint-item mb-3">
                                                        <code>GET /api/v1/files</code>
                                                        <p class="mt-2 mb-1">Get list of uploaded files</p>
                                                        <small class="text-muted">Returns: JSON array of files</small>
                                                    </div>
                                                    <div class="endpoint-item">
                                                        <code>GET /api/v1/files/{id}/download</code>
                                                        <p class="mt-2 mb-1">Download signed file</p>
                                                        <small class="text-muted">Returns: Signed file</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    <strong>Certificate Management</strong>
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#apiEndpointsAccordion">
                                                <div class="accordion-body">
                                                    <div class="endpoint-item mb-3">
                                                        <code>GET /api/v1/certificates</code>
                                                        <p class="mt-2 mb-1">Get available certificates</p>
                                                    </div>
                                                    <div class="endpoint-item mb-3">
                                                        <code>POST /api/v1/certificates/verify</code>
                                                        <p class="mt-2 mb-1">Verify a certificate</p>
                                                    </div>
                                                    <div class="endpoint-item">
                                                        <code>GET /api/v1/certificates/{id}/status</code>
                                                        <p class="mt-2 mb-1">Check certificate status</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    <strong>System Status</strong>
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#apiEndpointsAccordion">
                                                <div class="accordion-body">
                                                    <div class="endpoint-item mb-3">
                                                        <code>GET /api/v1/health</code>
                                                        <p class="mt-2 mb-1">Check system health</p>
                                                    </div>
                                                    <div class="endpoint-item">
                                                        <code>GET /api/v1/stats</code>
                                                        <p class="mt-2 mb-1">Get system statistics</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- API Testing -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4>API Testing</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Select Endpoint</label>
                                                <select class="form-control" id="testEndpoint">
                                                    <option value="/api/v1/health">GET /api/v1/health</option>
                                                    <option value="/api/v1/files">GET /api/v1/files</option>
                                                    <option value="/api/v1/certificates">GET /api/v1/certificates</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="col-form-label">API Key</label>
                                                <input class="form-control" type="text" id="testApiKey" placeholder="Enter API key for testing">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Response</label>
                                                <pre id="apiResponse" style="height: 200px; background: #f8f9fa; padding: 15px; border-radius: 5px; overflow: auto;">{
    "message": "Test response will appear here"
}</pre>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-end">
                                                <button class="btn btn-success" onclick="testApiEndpoint()">
                                                    <i data-feather="play" class="me-2"></i> Test API Endpoint
                                                </button>
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
    
    <!-- Generate API Key Modal -->
    <div class="modal fade" id="generateApiKeyModal" tabindex="-1" role="dialog" aria-labelledby="generateApiKeyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateApiKeyModalLabel">Generate New API Key</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="generateKeyForm">
                        <div class="form-group">
                            <label class="col-form-label">Key Name</label>
                            <input class="form-control" type="text" id="key_name" name="key_name" 
                                   placeholder="e.g., Production API Key" required>
                            <small class="form-text text-muted">Give this key a descriptive name</small>
                        </div>
                        <div class="form-group mt-3">
                            <label class="col-form-label">Expiration</label>
                            <select class="form-control" id="key_expiration" name="key_expiration">
                                <option value="never">Never Expires</option>
                                <option value="7">7 Days</option>
                                <option value="30">30 Days</option>
                                <option value="90">90 Days</option>
                                <option value="365">1 Year</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label class="col-form-label">Permissions</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm_read" name="permissions[]" value="read" checked>
                                <label class="form-check-label" for="perm_read">Read Access</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm_write" name="permissions[]" value="write">
                                <label class="form-check-label" for="perm_write">Write Access</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm_admin" name="permissions[]" value="admin">
                                <label class="form-check-label" for="perm_admin">Admin Access</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="button" onclick="createApiKey()">Generate Key</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php include "includes/scripts.php"; ?>
    
    <script>
        // Load API statistics
        function loadApiStats() {
            $.ajax({
                url: './php/api_handler.php?action=get_stats',
                success: function(response) {
                    try {
                        var stats = JSON.parse(response);
                        $('#totalCalls').text(stats.total_calls.toLocaleString());
                        $('#totalKeys').text(stats.total_keys);
                        $('#errorRate').text(stats.error_rate + '%');
                    } catch (e) {
                        console.error('Error loading API stats:', e);
                    }
                }
            });
        }
        
        // Load API keys list
        function loadApiKeys() {
            $.ajax({
                url: './php/api_handler.php?action=get_keys',
                success: function(response) {
                    $('#apiKeysList').html(response);
                }
            });
        }
        
        // Generate new API key
        function generateApiKey() {
            $('#generateKeyForm')[0].reset();
            $('#generateApiKeyModal').modal('show');
        }
        
        // Create API key
        function createApiKey() {
            $.ajax({
                url: './php/api_handler.php?action=generate_key',
                method: 'POST',
                data: $('#generateKeyForm').serialize(),
                success: function(response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire({
                                title: 'API Key Generated!',
                                html: '<strong>Your new API Key:</strong><br>' +
                                      '<code style="background: #f8f9fa; padding: 10px; display: block; margin: 10px 0;">' + 
                                      res.api_key + '</code>' +
                                      '<small class="text-danger">Save this key now! It will not be shown again.</small>',
                                icon: 'success',
                                showConfirmButton: true,
                                confirmButtonText: 'Copy Key'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    navigator.clipboard.writeText(res.api_key);
                                    Swal.fire('Copied!', 'API key copied to clipboard', 'info');
                                }
                            });
                            $('#generateApiKeyModal').modal('hide');
                            loadApiKeys();
                            loadApiStats();
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error!', 'Failed to generate API key', 'error');
                    }
                }
            });
        }
        
        // Revoke API key
        function revokeApiKey(keyId) {
            Swal.fire({
                title: 'Revoke API Key?',
                text: "This will immediately disable this API key",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, revoke it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/api_handler.php?action=revoke_key',
                        method: 'POST',
                        data: { key_id: keyId },
                        success: function(response) {
                            try {
                                var res = JSON.parse(response);
                                if (res.success) {
                                    Swal.fire('Revoked!', 'API key has been revoked', 'success');
                                    loadApiKeys();
                                    loadApiStats();
                                } else {
                                    Swal.fire('Error!', res.message, 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error!', 'Failed to revoke API key', 'error');
                            }
                        }
                    });
                }
            });
        }
        
        // Test API endpoint
        function testApiEndpoint() {
            var endpoint = $('#testEndpoint').val();
            var apiKey = $('#testApiKey').val();
            
            if (!apiKey) {
                Swal.fire('Warning!', 'Please enter an API key for testing', 'warning');
                return;
            }
            
            $('#apiResponse').text('Testing...');
            
            $.ajax({
                url: endpoint,
                headers: {
                    'X-API-Key': apiKey
                },
                success: function(response) {
                    $('#apiResponse').text(JSON.stringify(response, null, 2));
                },
                error: function(xhr) {
                    $('#apiResponse').text('Error: ' + xhr.status + ' ' + xhr.statusText + '\n\n' + xhr.responseText);
                }
            });
        }
        
        // Save API configuration
        $('#apiConfigForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: './php/api_handler.php?action=save_config',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.success) {
                            Swal.fire('Success!', 'API configuration saved', 'success');
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error!', 'Failed to save configuration', 'error');
                    }
                }
            });
        });
        
        // Reset API configuration
        function resetApiConfig() {
            Swal.fire({
                title: 'Reset Configuration?',
                text: "This will reset all API settings to default",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: './php/api_handler.php?action=reset_config',
                        success: function(response) {
                            location.reload();
                        }
                    });
                }
            });
        }
        
        // Initial load
        $(document).ready(function() {
            loadApiStats();
            loadApiKeys();
        });
    </script>
</body>
</html>