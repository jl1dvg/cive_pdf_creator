<!-- header.php -->
<?php
// Define la URL base de tu sitio web
define('BASE_URL', 'https://cive.consulmed.me/');
?>
<header class="main-header">
    <div class="inside-header">
        <div class="d-flex align-items-center logo-box justify-content-start">
            <!-- Logo -->
            <a href="https://cive.consulmed.me/main/main.php" class="logo">
                <!-- logo-->
                <div class="logo-lg">
                    <span class="light-logo"><img src="../../images/logo-dark-text.png" alt="logo"></span>
                    <span class="dark-logo"><img src="../../images/logo-light-text.png" alt="logo"></span>
                </div>
            </a>
        </div>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div class="app-menu">
                <ul class="header-megamenu nav">
                    <li class="btn-group d-lg-inline-flex d-none">
                        <div class="app-menu">
                            <div class="search-bx mx-5">
                                <form>
                                    <div class="input-group">
                                        <input type="search" class="form-control" placeholder="Search"
                                               aria-label="Search" aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button class="btn" type="submit" id="button-addon3"><i
                                                        class="icon-Search"><span class="path1"></span><span
                                                            class="path2"></span></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="navbar-custom-menu r-side">
                <ul class="nav navbar-nav">
                    <!-- User Account-->
                    <li class="dropdown user user-menu">
                        <a href="#"
                           class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow"
                           data-bs-toggle="dropdown" title="User">
                            <div class="d-flex pt-1">
                                <div class="text-end me-10">
                                    <p class="pt-5 fs-14 mb-0 fw-700 text-primary"><?php echo htmlspecialchars($username); ?></p>
                                    <small class="fs-10 mb-0 text-uppercase text-mute">Admin</small>
                                </div>
                                <img src="../../images/avatar/avatar-1.png"
                                     class="avatar rounded-10 bg-primary-light h-40 w-40" alt=""/>
                            </div>
                        </a>
                        <ul class="dropdown-menu animated flipInX">
                            <li class="user-body">
                                <a class="dropdown-item" href="extra_profile.html"><i
                                            class="ti-user text-muted me-2"></i> Profile</a>
                                <a class="dropdown-item" href="auth_login.html"><i
                                            class="ti-lock text-muted me-2"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                    <li class="btn-group nav-item d-lg-inline-flex d-none">
                        <a href="#" data-provide="fullscreen"
                           class="waves-effect waves-light nav-link full-screen btn-warning-light"
                           title="Full Screen">
                            <i class="icon-Position"></i>
                        </a>
                    </li>
                    <!-- Notifications -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="waves-effect waves-light dropdown-toggle btn-info-light"
                           data-bs-toggle="dropdown" title="Notifications">
                            <i class="icon-Notification"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                        <ul class="dropdown-menu animated bounceIn">
                            <li class="header">
                                <div class="p-20">
                                    <div class="flexbox">
                                        <div>
                                            <h4 class="mb-0 mt-0">Notifications</h4>
                                        </div>
                                        <div>
                                            <a href="#" class="text-danger">Clear All</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu sm-scrol">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-info"></i> Curabitur id eros quis nunc
                                            suscipit blandit.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-warning text-warning"></i> Duis malesuada justo eu
                                            sapien elementum, in semper diam posuere.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-danger"></i> Donec at nisi sit amet tortor
                                            commodo porttitor pretium a erat.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-shopping-cart text-success"></i> In gravida mauris et
                                            nisi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user text-danger"></i> Praesent eu lacus in libero
                                            dictum fermentum.
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user text-primary"></i> Nunc fringilla lorem
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user text-success"></i> Nullam euismod dolor ut quam
                                            interdum, at scelerisque ipsum imperdiet.
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all</a>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li class="btn-group nav-item">
                        <a href="#" data-toggle="control-sidebar" title="Setting"
                           class="waves-effect full-screen waves-light btn-danger-light">
                            <i class="icon-Settings1"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</header>
<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox"/>
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span> Toggle main menu visibility
    </label>

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        <li><a href="<?php echo BASE_URL; ?>main/main.php"><i class="icon-Layout-4-blocks"><span
                            class="path1"></span><span class="path2"></span></i>Dashboard</a>
        </li>
        <li><a href="<?php echo BASE_URL; ?>main/patients.php"><i class="icon-Compiling"><span
                            class="path1"></span><span class="path2"></span></i>Pacientes</a>
            <ul>
                <li><a href="<?php echo BASE_URL; ?>main/patients.php"><i class="icon-Commit"><span
                                    class="path1"></span><span
                                    class="path2"></span></i>Lista Pacientes</a></li>
                <li><a href="patient_details.html"><i class="icon-Commit"><span class="path1"></span><span
                                    class="path2"></span></i>Detalle de pacientes</a></li>
            </ul>
        </li>
        <li><a href="<?php echo BASE_URL; ?>main/repots/solicitudes.php"><i class="icon-Settings-1"><span
                            class="path1"></span><span class="path2"></span></i>Reportes</a>
            <uL>
                <li><a href="<?php echo BASE_URL; ?>main/repots/qx_reports.php"><i class="icon-Commit"><span
                                    class="path1"></span><span
                                    class="path2"></span></i>Reporte de Protocolos</a></li>
                <li><a href="<?php echo BASE_URL; ?>main/repots/solicitudes.php"><i class="icon-Commit"><span
                                    class="path1"></span><span
                                    class="path2"></span></i>Solicitudes de Cirugía</a></li>
            </uL>
        </li>
        <li><a href="<?php echo BASE_URL; ?>main/editors/protocolos_templates_list.php"><i class="icon-Air-ballon"><span
                            class="path1"></span><span class="path2"></span></i>Editor de Protocolos</a>
        </li>
    </ul>
</nav>