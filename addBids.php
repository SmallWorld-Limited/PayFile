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
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="hallmark systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>Hallmark Bid Tracker</title>
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
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism.css">
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
        <div class="header-wrapper row m-0">
          <div class="header-logo-wrapper col-auto p-0">
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
            <div class="logo-header-main"><a href="index.html"><img class="img-fluid for-light img-100" src="assets/images/logo/logo2.png" alt=""><img class="img-fluid for-dark" src="assets/images/logo/logo.png" alt=""></a></div>
          </div>
          <div class="left-header col horizontal-wrapper ps-0">
            <div class="left-menu-header">
              <ul class="app-list">
                <li class="onhover-dropdown">
                  <div class="app-menu"> <i data-feather="folder-plus"></i></div>
                </li>
              </ul>
            </div>
          </div>
          <div class="nav-right col-6 pull-right right-header p-0">
            <ul class="nav-menus">
              <li class="serchinput">
                <div class="serchbox"><i data-feather="search"></i></div>
                <div class="form-group search-form">
                  <input type="text" placeholder="Search here...">
                </div>
              </li>
              <li>
                <div class="mode"><i class="fa fa-moon-o"></i></div>
              </li>
              <li class="onhover-dropdown">
                <div class="notification-box"><i data-feather="bell"></i></div>
                <ul class="notification-dropdown onhover-show-div">
                  <li><i data-feather="bell">            </i>
                    <h6 class="f-18 mb-0">Notitications</h6>
                  </li>
                  <li>
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0"><i data-feather="truck"></i></div>
                      <div class="flex-grow-1">
                        <p><a href="template/order-history.html">Delivery processing </a><span class="pull-right">6 hr</span></p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0"><i data-feather="shopping-cart"></i></div>
                      <div class="flex-grow-1">
                        <p><a href="template/cart.html">Order Complete</a><span class="pull-right">3 hr</span></p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0"><i data-feather="file-text"></i></div>
                      <div class="flex-grow-1">
                        <p><a href="template/invoice-template.html">Tickets Generated</a><span class="pull-right">1 hr</span></p>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0"><i data-feather="send"></i></div>
                      <div class="flex-grow-1">
                        <p><a href="template/email_inbox.html">Delivery Complete</a><span class="pull-right">45 min</span></p>
                      </div>
                    </div>
                  </li>
                  <li><a class="btn btn-primary" href="javascript:void(0)">Check all notification</a></li>
                </ul>
              </li>
              <li class="maximize"><a href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize-2"></i></a></li>
              <li class="profile-nav onhover-dropdown">
                <div class="account-user"><i data-feather="user"></i></div>
                <ul class="profile-dropdown onhover-show-div">
                  <li><a href="#"><i data-feather="user"></i><span>Account</span></a></li>
                  <li><a href="#"><i data-feather="mail"></i><span>Inbox</span></a></li>
                  <li><a href="#"><i data-feather="settings"></i><span>Settings</span></a></li>
                  <li><a href="./logout.php"><i data-feather="log-in"> </i><span>Log out</span></a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Page Header Ends-->
      <!-- Page Body Start-->
      <div class="page-body-wrapper horizontal-menu">
        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper">
          <div>
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid for-light" src="assets/images/logo/logo.png" alt=""></a>
              <div class="back-btn"><i class="fa fa-angle-left"></i></div>
              <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"></i></div>
            </div>
            <div class="logo-icon-wrapper"><a href="index.html">
                <div class="icon-box-sidebar"><i data-feather="grid"></i></div></a></div>
            <nav class="sidebar-main">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                  <li class="back-btn">
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  <li class="pin-title sidebar-list">
                    <h6>Pinned</h6>
                  </li>
                  <hr>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="./" target="_blank"><i data-feather="home"></i><span>Dashboard</span></a></li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#"><i data-feather="anchor"></i><span>Bids</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="./bids.php">View All</a></li>
                      <li><a href="./addBids.php">Add New</a></li>
                    </ul>
                  </li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#"><i data-feather="anchor"></i><span>Users</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="./users">View All</a></li>
                      <li><a href="./addUser.php">Add New</a></li>
                    </ul>
                  </li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#"><i data-feather="anchor"></i><span>Workflows</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="./workflows.php">View All</a></li>
                      <li><a href="./addWorkFlow.php">Add New</a></li>
                    </ul>
                  </li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="#"><i data-feather="anchor"></i><span>Bid Stages</span></a>
                    <ul class="sidebar-submenu">
                      <li><a href="./stages.php">View All</a></li>
                      <li><a href="./addStages.php">Add New</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </nav>
          </div>
        </div>
        <!-- Page Sidebar Ends-->
        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6">
                  <h3>Add Bids</h3>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active"></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <!-- Content-->
              <div class="col-sm-12 col-xl-12">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card">
                      <div class="card-header pb-0">
                        <h4>Default Form Layout</h4><span>Using the <a href="#">card</a> component, you can extend the default collapse behavior to create an accordion.</span>
                      </div>
                      <div class="card-body">
                      <form class="theme-form">
                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="bidTitle">Bid Title</label>
                          <input class="form-control" id="bidTitle" name="bid_title" type="text" placeholder="Enter bid title" required>
                        </div>

                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="bidDescription">Bid Description</label>
                          <textarea class="form-control" id="bidDescription" name="bid_description" rows="3" placeholder="Enter bid description"></textarea>
                        </div>

                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="submissionDeadline">Submission Deadline</label>
                          <input class="form-control" id="submissionDeadline" name="submission_deadline" type="date" required>
                        </div>

                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="clientName">Client Name</label>
                          <input class="form-control" id="clientName" name="client_name" type="text" placeholder="Enter client name" required>
                        </div>

                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="department">Department</label>
                          <select class="form-control" id="department" name="department_id" required>
                            <option value="">Select Department</option>
                            <!-- You can populate these options dynamically -->
                            <option value="1">Sales</option>
                            <option value="2">Marketing</option>
                            <option value="3">Finance</option>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="bidDocuments">Bid Documents</label>
                          <input class="form-control" id="bidDocuments" name="bid_documents[]" type="file" multiple>
                        </div>

                        <div class="mb-3">
                          <label class="col-form-label pt-0" for="createdBy">Created By</label>
                          <select class="form-control" id="createdBy" name="created_by" required>
                            <option value="">Select User</option>
                            <!-- Dynamically populated list of users -->
                            <option value="1">John Doe</option>
                            <option value="2">Jane Smith</option>
                          </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Bid</button>
                      </form>

                      </div>
                      <div class="card-footer text-end">
                        <button class="btn btn-primary">Submit</button>
                        <button class="btn btn-secondary">Cancel</button>
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
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 p-0 footer-left">
                <p class="mb-0">Copyright © 2024 Hallmark Limited. All rights reserved.</p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- latest jquery-->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap js-->
    <script src="assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
    <script src="assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
    <script src="assets/js/scrollbar/simplebar.js"></script>
    <script src="assets/js/scrollbar/custom.js"></script>
    <!-- Sidebar jquery-->
    <script src="assets/js/config.js"></script>
    <script src="assets/js/sidebar-menu.js"></script>
    <script src="assets/js/prism/prism.min.js"></script>
    <script src="assets/js/clipboard/clipboard.min.js"></script>
    <script src="assets/js/custom-card/custom-card.js"></script>
    <script src="assets/js/typeahead/handlebars.js"></script>
    <script src="assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="assets/js/typeahead/typeahead.custom.js"></script>
    <script src="assets/js/typeahead-search/handlebars.js"></script>
    <script src="assets/js/typeahead-search/typeahead-custom.js"></script>
    <!-- Template js-->
    <script src="assets/js/script.js"></script>
    <script src="assets/js/theme-customizer/customizer.js">  </script>
    <!-- login js-->
    <script>
      $(document).ready(function () {
          document.querySelectorAll('.bid_validate').forEach(form => {
              form.addEventListener('submit', event => {
                  if (!form.checkValidity()) {
                      event.preventDefault();
                      event.stopPropagation();
                  } else {
                      event.preventDefault();
                      // Capture form data
                      let bid_title = $("#bid_title").val();
                      let bid_description = $("#bid_description").val();
                      let submission_deadline = $("#submission_deadline").val();
                      let client_name = $("#client_name").val();
                      let department_id = $("#department_id").val();

                      // AJAX request to submit bid
                      $.ajax({
                          type: "POST",
                          url: "./php/bid_management.php",
                          data: {
                              add_bid: "1",
                              bid_title: bid_title,
                              bid_description: bid_description,
                              submission_deadline: submission_deadline,
                              client_name: client_name,
                              department_id: department_id
                          },
                          success: function (response) {
                              try {
                                  let res = JSON.parse(response);
                                  if (res.id == 1) {
                                      Swal.fire({
                                          title: "Success",
                                          text: res.mssg,
                                          icon: res.type
                                      });
                                      setTimeout(() => {
                                          window.location = './bids.php';
                                      }, 2000);
                                  } else {
                                      Swal.fire({
                                          title: "Error",
                                          text: res.mssg,
                                          icon: res.type
                                      });
                                  }
                              } catch (error) {
                                  console.log(error);
                                  Swal.fire({
                                      title: "Error",
                                      text: "System Error",
                                      icon: "error"
                                  });
                              }
                          },
                          error: function (xhr, status, error) {
                              Swal.fire({
                                  title: "Error",
                                  text: "AJAX request failed: " + error,
                                  icon: "error"
                              });
                          }
                      });
                  }
                  form.classList.add('was-validated');
              }, false);
          });
      });
    </script>

  </body>
</html>