<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>UBold - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="pro-user-name ml-1">
                            Options <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="fe-user"></i>
                            <span>My Account</span>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="fe-settings"></i>
                            <span>Settings</span>
                        </a>


                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="/logout" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </a>

                    </div>
                </li>
            </ul>
            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="fe-menu"></i>
                    </button>
                </li>
            </ul>
            <!-- LOGO -->
            <div class="logo-box">
                <a href="index.html" class="logo text-center">
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt height="50px">
                        <!-- <span class="logo-lg-text-light">UBold</span> -->
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-sm-text-dark">U</span> -->
                        <img src="assets/images/logo-light.png" alt="" height="24">
                    </span>
                </a>
            </div>

        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <div class="slimscroll-menu">

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul class="metismenu" id="side-menu">

                        <li class="menu-title">Navigation</li>

                        <li>
                            <a href="/">
                                <i class="fe-pocket"></i>
                                <span> Kanban System</span>
                            </a>
                        </li>

                    </ul>

                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        @yield('content')

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <script type="text/javascript">
                            document.write(new Date().getFullYear());
                        </script> &copy; mFLow powered by <a href="https://www.leanacademysl.com/" target="_blank"> <img
                                src="assets/images/logo-light.png" alt="" height="24"></a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

    </div>
    <!-- END wrapper -->


    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Jquery Ui js -->
    <script src="assets/libs/jquery-ui/jquery-ui.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    

    <script src="assets/js/cuskanban.js"></script>


</body>

</html>