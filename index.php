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
    <meta name="description" content="JPI File Signing System">
    <meta name="keywords" content="File Signing, CSV, PKCS7, Digital Signature">
    <meta name="author" content="JPI Systems">
    <link rel="icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/favicon/favicon.png" type="image/x-icon">
    <title>JPI File Signing System</title>
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
                  <h3>Dashboard</h3>
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
            
            <!-- Quick Stats Row -->
            <div class="row starter-main">
              
              <!-- Total Files Signed Card -->
              <div class="col-sm-6 col-lg-3">
                <div class="card o-hidden">
                  <div class="card-header pb-0">
                    <div class="d-flex"> 
                      <div class="flex-grow-1"> 
                        <p class="square-after f-w-600 header-text-primary">Files Signed<i class="fa fa-circle"> </i></p>
                        <h4><?php
                          $stat = new signing_stats();
                          echo $stat->getTotalFilesSigned();
                        ?></h4>
                      </div>
                      <div class="d-flex static-widget">
                        <svg class="fill-primary" width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                          <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                          <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                          <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    <div class="progress-widget">
                      <div class="progress sm-progress-bar progress-animate">
                        <div class="progress-gradient-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><span class="animate-circle"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Pending Files Card -->
              <div class="col-sm-6 col-lg-3">
                <div class="card o-hidden product-widget">
                  <div class="card-header pb-0">
                    <div class="d-flex"> 
                      <div class="flex-grow-1"> 
                        <p class="square-after f-w-600 header-text-success">Pending Files<i class="fa fa-circle"> </i></p>
                        <h4><?php
                          $stat = new signing_stats();
                          echo $stat->getPendingFilesCount();
                        ?></h4>
                      </div>
                      <div class="d-flex static-widget">
                        <svg class="fill-success" width="45" height="45" viewBox="0 0 45 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M5.92047 8.49509C5.81037 8.42629 5.81748 8.25971 5.93378 8.20177C7.49907 7.41686 9.01464 6.65821 10.5302 5.89775C14.4012 3.95495 18.2696 2.00762 22.1478 0.0792996C22.3387 -0.0157583 22.6468 -0.029338 22.8359 0.060288C28.2402 2.64315 33.6357 5.24502 39.033 7.84327C39.0339 7.84327 39.0339 7.84417 39.0348 7.84417C39.152 7.90121 39.1582 8.06869 39.0472 8.1375C38.9939 8.17009 38.9433 8.20087 38.8918 8.22984C33.5398 11.2228 28.187 14.2121 22.8385 17.2115C22.5793 17.3572 22.3839 17.3762 22.1131 17.2296C16.7851 14.3507 11.4518 11.4826 6.12023 8.61188C6.05453 8.57748 5.98972 8.53855 5.92047 8.49509Z"></path>
                          <path d="M21.1347 23.3676V38.8321C21.1347 38.958 21.0042 39.0386 20.895 38.9806C20.4182 38.7271 19.9734 38.4918 19.5295 38.2528C14.498 35.5441 9.46833 32.8317 4.43154 30.1339C4.12612 29.97 4.02046 29.7944 4.02224 29.4422C4.03822 26.8322 4.03023 24.2222 4.02934 21.6122C4.02934 21.4719 4.02934 21.3325 4.02934 21.1659C4.02934 21.0428 4.15542 20.9622 4.26373 21.0147C4.35252 21.0581 4.43065 21.0962 4.50434 21.1396C8.18539 23.2888 11.8664 25.438 15.5457 27.5909C16.5081 28.154 17.0622 28.0453 17.7627 27.1464C18.7748 25.8472 19.7896 24.5508 20.8045 23.2535C20.8053 23.2526 20.8062 23.2517 20.8071 23.2499C20.9172 23.1132 21.1347 23.192 21.1347 23.3676Z"></path>
                          <path d="M23.83 23.3784C23.83 23.2019 24.0484 23.1241 24.1567 23.2626C25.2168 24.6178 26.2192 25.9016 27.2233 27.1835C27.8928 28.039 28.4504 28.1494 29.3719 27.6117C33.0521 25.4643 36.7323 23.316 40.4133 21.1686C40.4914 21.1233 40.5713 21.0799 40.6592 21.0337C40.7613 20.9803 40.8856 21.0473 40.8972 21.164C40.9025 21.2184 40.9069 21.2691 40.9069 21.3189C40.9087 23.928 40.9052 26.5371 40.9132 29.1462C40.914 29.4006 40.8421 29.5518 40.6131 29.6794C35.1057 32.7539 29.6037 35.8365 24.099 38.9163C24.0892 38.9218 24.0803 38.9263 24.0706 38.9317C23.9605 38.9879 23.8309 38.9082 23.8309 38.7833L23.83 23.3784Z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    <div class="progress-widget">
                      <div class="progress sm-progress-bar progress-animate">
                        <div class="progress-gradient-success" role="progressbar" style="width: 60%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><span class="animate-circle"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Active Certificates Card -->
              <div class="col-sm-6 col-lg-3">
                <div class="card o-hidden message-widget">
                  <div class="card-header pb-0">
                    <div class="d-flex"> 
                      <div class="flex-grow-1"> 
                        <p class="square-after f-w-600 header-text-secondary">Active Certificates<i class="fa fa-circle"> </i></p>
                        <h4><?php
                          $stat = new signing_stats();
                          echo $stat->getActiveCertificatesCount();
                        ?></h4>
                      </div>
                      <div class="d-flex static-widget">
                        <svg class="fill-secondary" width="44" height="46" viewBox="0 0 44 46" xmlns="http://www.w3.org/2000/svg">
                          <path d="M9.73709 35.2337C6.17884 31.58 4.00316 26.8452 3.49802 21.7377C1.60687 24.237 0.581465 27.3024 0.586192 30.5195C0.589372 32.612 1.03986 34.692 1.89348 36.5729L0.1333 41.9282C-0.169286 42.8488 0.0517454 43.8484 0.7102 44.5369C1.17358 45.0213 1.78451 45.2794 2.4128 45.2794C2.67714 45.2794 2.94458 45.2337 3.2054 45.14L8.32806 43.2997C10.1272 44.1922 12.1167 44.6631 14.1182 44.6665C17.2557 44.6709 20.2418 43.558 22.657 41.5068C17.8005 41.0474 13.2702 38.8615 9.73709 35.2337Z"></path>
                          <path d="M43.8418 35.7427L41.2863 27.9674C42.5181 25.3348 43.1691 22.407 43.1735 19.4611C43.181 14.3388 41.2854 9.49561 37.8357 5.82369C34.3853 2.15096 29.7875 0.0836476 24.889 0.00251856C19.8097 -0.0814855 15.0354 1.93839 11.446 5.69081C7.85665 9.44332 5.92425 14.4346 6.00469 19.7451C6.08229 24.8661 8.05972 29.673 11.5726 33.2803C15.078 36.8798 19.6988 38.861 24.5879 38.8608C24.5975 38.8608 24.6077 38.8608 24.6171 38.8608C27.435 38.8563 30.2356 38.1757 32.7537 36.8879L40.1911 39.5596C40.501 39.671 40.8188 39.7252 41.1329 39.7252C41.8795 39.7252 42.6055 39.4187 43.1563 38.8428C43.9388 38.0247 44.2014 36.8369 43.8418 35.7427ZM26.3834 26.1731H16.7865C16.0633 26.1731 15.477 25.5601 15.477 24.804C15.477 24.0479 16.0633 23.435 16.7865 23.435H26.3833C27.1066 23.435 27.6929 24.048 27.6929 24.804C27.6929 25.5602 27.1067 26.1731 26.3834 26.1731ZM32.3894 20.5426H16.7866C16.0633 20.5426 15.4771 19.9296 15.4771 19.1736C15.4771 18.4176 16.0634 17.8046 16.7866 17.8046H32.3894C33.1127 17.8046 33.6989 18.4176 33.6989 19.1736C33.6989 19.9296 33.1127 20.5426 32.3894 20.5426ZM32.3894 14.912H16.7866C16.0633 14.912 15.4771 14.299 15.4771 13.543C15.4771 12.7869 16.0634 12.1739 16.7866 12.1739H32.3894C33.1127 12.1739 33.6989 12.787 33.6989 13.543C33.6989 14.299 33.1127 14.912 32.3894 14.912Z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    <div class="progress-widget">
                      <div class="progress sm-progress-bar progress-animate">
                        <div class="progress-gradient-secondary" role="progressbar" style="width: 48%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><span class="animate-circle"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Total Users Card -->
              <div class="col-sm-6 col-lg-3">
                <div class="card o-hidden user-widget">
                  <div class="card-header pb-0">
                    <div class="d-flex"> 
                      <div class="flex-grow-1"> 
                        <p class="square-after f-w-600 header-text-info">Total Users<i class="fa fa-circle"> </i></p>
                        <h4><?php
                          $stat = new signing_stats();
                          echo $stat->getTotalUsersCount();
                        ?></h4>
                      </div>
                      <div class="d-flex static-widget">
                        <svg class="fill-info" width="41" height="46" viewBox="0 0 41 46" xmlns="http://www.w3.org/2000/svg">
                          <path d="M17.5245 23.3155C24.0019 23.3152 26.3325 16.8296 26.9426 11.5022C27.6941 4.93936 24.5906 0 17.5245 0C10.4593 0 7.35423 4.93899 8.10639 11.5022C8.71709 16.8296 11.047 23.316 17.5245 23.3155Z"></path>
                          <path d="M31.6878 26.0152C31.8962 26.0152 32.1033 26.0214 32.309 26.0328C32.0007 25.5931 31.6439 25.2053 31.2264 24.8935C29.9817 23.9646 28.3698 23.6598 26.9448 23.0998C26.2511 22.8273 25.6299 22.5567 25.0468 22.2485C23.0787 24.4068 20.5123 25.5359 17.5236 25.5362C14.536 25.5362 11.9697 24.4071 10.0019 22.2485C9.41877 22.5568 8.79747 22.8273 8.10393 23.0998C6.67891 23.6599 5.06703 23.9646 3.82233 24.8935C1.6698 26.5001 1.11351 30.1144 0.676438 32.5797C0.315729 34.6148 0.0734026 36.6917 0.00267388 38.7588C-0.0521202 40.36 0.738448 40.5846 2.07801 41.0679C3.75528 41.6728 5.48712 42.1219 7.23061 42.4901C10.5977 43.2011 14.0684 43.7475 17.5242 43.7719C19.1987 43.76 20.8766 43.6249 22.5446 43.4087C21.3095 41.6193 20.5852 39.4517 20.5852 37.1179C20.5853 30.9957 25.5658 26.0152 31.6878 26.0152Z"></path>
                          <path d="M31.6878 28.2357C26.7825 28.2357 22.8057 32.2126 22.8057 37.1179C22.8057 42.0232 26.7824 46 31.6878 46C36.5932 46 40.57 42.0232 40.57 37.1179C40.57 32.2125 36.5931 28.2357 31.6878 28.2357ZM35.5738 38.6417H33.2118V41.0037C33.2118 41.8453 32.5295 42.5277 31.6879 42.5277C30.8462 42.5277 30.1639 41.8453 30.1639 41.0037V38.6417H27.802C26.9603 38.6417 26.278 37.9595 26.278 37.1177C26.278 36.276 26.9602 35.5937 27.802 35.5937H30.1639V33.2318C30.1639 32.3901 30.8462 31.7078 31.6879 31.7078C32.5296 31.7078 33.2118 32.3901 33.2118 33.2318V35.5937H35.5738C36.4155 35.5937 37.0978 36.276 37.0978 37.1177C37.0977 37.9595 36.4155 38.6417 35.5738 38.6417Z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0">
                    <div class="progress-widget">
                      <div class="progress sm-progress-bar progress-animate">
                        <div class="progress-gradient-info" role="progressbar" style="width: 48%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><span class="animate-circle"></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
            
            <!-- Charts Row -->
            <div class="row">
              <div class="col-lg-4 col-md-6 box-col-33">
                <div class="card o-hidden">
                  <div class="chart-widget-top">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-5">
                          <h6 class="f-w-600 font-primary">Files Signed / Month</h6>
                          <span class="num">
                            <span class="counter"><?php echo (new signing_stats())->getFilesSignedThisMonth(); ?></span>
                            <i class="icon-angle-up f-12 ms-1"></i>
                          </span>
                        </div>
                        <div class="col-7 text-end">
                          <h6 class="num total-value">This Month</h6>
                        </div>
                      </div>
                    </div>
                    <div>
                      <div id="files_signed_chart"></div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-4 col-md-6 box-col-33">
                <div class="card o-hidden">
                  <div class="chart-widget-top">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-7">
                          <h6 class="f-w-600 font-secondary">File Types Distribution</h6>
                          <span class="num">
                            <span class="counter"><?php echo (new signing_stats())->getTotalFileTypes(); ?></span> Types
                          </span>
                        </div>
                        <div class="col-5 text-end">
                          <h6 class="num total-value">Active</h6>
                        </div>
                      </div>
                    </div>
                    <div id="file_types_chart">
                      <div class="flot-chart-placeholder" id="chart-widget-top-second"></div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-4 col-md-12 box-col-33">
                <div class="card o-hidden p-2">
                  <div class="chart-widget-top">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-8">
                          <h6 class="f-w-600 font-success">Activity Last 7 Days</h6>
                          <span class="num">
                            <span class="counter"><?php echo (new signing_stats())->getActivityLast7Days(); ?></span> Files
                          </span>
                        </div>
                        <div class="col-4 text-end">
                          <h6 class="num total-value">Active</h6>
                        </div>
                      </div>
                    </div>
                    <div id="activity_trends">
                      <div class="flot-chart-placeholder" id="chart-widget-top-third"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Recent Files & Quick Actions Row -->
            <div class="row">
              <!-- Recent Files Table -->
              <div class="col-lg-8">
                <div class="card">
                  <div class="card-header pb-0">
                    <h4>Recent Files</h4>
                    <div class="card-header-right">
                      <a href="./uploads.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive theme-scrollbar">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Signed Date</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $recent_files = new signing_stats();
                          $recent_files->getRecentFiles();
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Quick Actions -->
              <div class="col-lg-4">
                <div class="card">
                  <div class="card-header pb-0">
                    <h4>Quick Actions</h4>
                  </div>
                  <div class="card-body">
                    <div class="quick-actions-grid">
                      <a href="./uploadNew.php" class="quick-action-item">
                        <div class="action-icon bg-primary">
                          <i class="fa fa-upload"></i>
                        </div>
                        <h6>Upload & Sign</h6>
                        <p>Upload new CSV file for signing</p>
                      </a>
                      
                      <a href="./certs.php" class="quick-action-item">
                        <div class="action-icon bg-success">
                          <i class="fa fa-shield"></i>
                        </div>
                        <h6>Certificates</h6>
                        <p>Manage signing certificates</p>
                      </a>
                      
                      <a href="./uploads.php" class="quick-action-item">
                        <div class="action-icon bg-info">
                          <i class="fa fa-file-text"></i>
                        </div>
                        <h6>View Files</h6>
                        <p>Browse all uploaded files</p>
                      </a>
                      
                      <a href="./addCert.php" class="quick-action-item">
                        <div class="action-icon bg-warning">
                          <i class="fa fa-plus-circle"></i>
                        </div>
                        <h6>Add Certificate</h6>
                        <p>Add new signing certificate</p>
                      </a>
                    </div>
                    
                    <!-- System Status -->
                    <div class="system-status mt-4">
                      <h6 class="mb-3">System Status</h6>
                      <?php
                      $status = new signing_stats();
                      $system_status = $status->getSystemStatus();
                      
                      foreach ($system_status as $item):
                      ?>
                      <div class="status-item d-flex justify-content-between align-items-center mb-2">
                        <span><?php echo $item['name']; ?></span>
                        <span class="badge badge-<?php echo $item['status'] == 'OK' ? 'success' : 'danger'; ?>">
                          <?php echo $item['status']; ?>
                        </span>
                      </div>
                      <?php endforeach; ?>
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
    <script src="assets/js/chart/apex-chart/moment.min.js"></script>
    <script src="assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="assets/js/counter/jquery.waypoints.min.js"></script>
    <script src="assets/js/counter/jquery.counterup.min.js"></script>
    <script src="assets/js/counter/counter-custom.js"></script>
    <!-- Template js-->
    <script src="assets/js/script.js"></script>
    
    <script>
      // Files Signed Chart
      var filesSignedData = <?php echo (new signing_stats())->getFilesSignedChartData(); ?>;
      
      var optionsFilesChart = {
        chart: {
          toolbar: {
            show: false,
          },
          height: 170,
          type: "area",
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
        },
        yaxis:{
          labels: {
            show: false,
          }
        },
        xaxis: {
          show: false,
          type: "datetime",
          categories: filesSignedData.categories,
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          },
        },
        grid: {
          show: false,
          padding: {
            left: -20,
            right: 0,
            bottom: -25,
          },
        },
        fill: {
          type: "gradient",
          gradient: {
            shade: "light",
            type: "vertical",
            shadeIntensity: 0.4,
            inverseColors: true,
            opacityFrom: 0.8,
            opacityTo: 0.2,
            stops: [0, 100],
          },
        },
        colors: [TivoAdminConfig.primary],
        series: [
          {
            name: "Files Signed",
            data: filesSignedData.data,
          },
        ],
        tooltip: {
          x: {
            format: "MMM yyyy",
          },
        },
      };
      
      var filesSignedChart = new ApexCharts(document.querySelector("#files_signed_chart"), optionsFilesChart);
      filesSignedChart.render();
      
      // File Types Chart
      var fileTypesData = <?php echo (new signing_stats())->getFileTypesChartData(); ?>;
      
      var optionsFileTypesChart = {
        chart: {
          toolbar: {
            show: false,
          },
          height: 170,
          type: "donut",
        },
        dataLabels: {
          enabled: false,
        },
        plotOptions: {
          pie: {
            donut: {
              size: '70%'
            }
          }
        },
        labels: fileTypesData.labels,
        colors: [TivoAdminConfig.primary, TivoAdminConfig.secondary, TivoAdminConfig.success, TivoAdminConfig.info, TivoAdminConfig.warning],
        series: fileTypesData.data,
        legend: {
          show: false,
        },
      };
      
      var fileTypesChart = new ApexCharts(document.querySelector("#file_types_chart"), optionsFileTypesChart);
      fileTypesChart.render();
      
      // Activity Trends Chart
      var activityData = <?php echo (new signing_stats())->getActivityChartData(); ?>;
      
      var optionsActivityChart = {
        chart: {
          toolbar: {
            show: false,
          },
          height: 170,
          type: "line",
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
          width: 3,
        },
        yaxis:{
          labels: {
            show: false,
          }
        },
        xaxis: {
          categories: activityData.categories,
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          },
        },
        grid: {
          show: false,
          padding: {
            left: -20,
            right: 0,
            bottom: -25,
          },
        },
        colors: [TivoAdminConfig.success],
        series: [
          {
            name: "Activity",
            data: activityData.data,
          },
        ],
      };
      
      var activityChart = new ApexCharts(document.querySelector("#activity_trends"), optionsActivityChart);
      activityChart.render();
      
      // Initialize counters
      $('.counter').counterUp({
        delay: 10,
        time: 1000
      });
    </script>
    
  </body>
</html>