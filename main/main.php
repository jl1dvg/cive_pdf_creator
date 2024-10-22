<?php
ini_set('session.save_path', __DIR__ . '/../sessions');
session_name('mi_sesion');
session_start();
require '../conexion.php';  // Asegúrate de que la conexión esté configurada correctamente

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está logueado
    header('Location: auth_login.html');
    exit();
}

// Obtener la información del usuario logueado
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../images/favicon.ico">

    <title>Asistente CIVE - Dashboard</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="css/horizontal-menu.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/skin_color.css">

</head>

<body class="layout-top-nav light-skin theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    <header class="main-header">
        <div class="inside-header">
            <div class="d-flex align-items-center logo-box justify-content-start">
                <!-- Logo -->
                <a href="index.html" class="logo">
                    <!-- logo-->
                    <div class="logo-lg">
                        <span class="light-logo"><img src="../images/logo-dark-text.png" alt="logo"></span>
                        <span class="dark-logo"><img src="../images/logo-light-text.png" alt="logo"></span>
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
                                    <img src="../images/avatar/avatar-1.png"
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
            <li><a href="#"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Dashboard</a>
                <ul>
                    <li><a href="index.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Patients Dashboard</a></li>
                    <li><a href="index4.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Patients Dashboard 2</a></li>
                    <li><a href="index2.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Doctor Dashboard</a></li>
                    <li><a href="index6.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Doctor Dashboard 2</a></li>
                    <li><a href="index7.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Doctor Dashboard 3</a></li>
                    <li><a href="index3.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Hospital Dashboard</a></li>
                    <li><a href="index5.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Hospital Dashboard 2</a></li>
                </ul>
            </li>
            <li><a href="appointments.html"><i class="icon-Barcode-read"><span class="path1"></span><span
                                class="path2"></span></i>Appointments</a></li>
            <li><a href="#"><i class="icon-Compiling"><span class="path1"></span><span class="path2"></span></i>Patients</a>
                <ul>
                    <li><a href="patients.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Patient list</a></li>
                    <li><a href="patient_details.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Patient Details</a></li>
                </ul>
            </li>
            <li><a href="reports.html"><i class="icon-Settings-1"><span class="path1"></span><span class="path2"></span></i>Reports</a>
            </li>
            <li><a href="#"><i class="icon-Diagnostics"><span class="path1"></span><span class="path2"></span><span
                                class="path3"></span></i>Doctors</a>
                <ul>
                    <li><a href="doctor_list.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Doctor list</a></li>
                    <li><a href="doctors.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Doctor Details</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Air-ballon"><span class="path1"></span><span
                                class="path2"></span></i>Apps</a>
                <ul>
                    <li><a href="extra_calendar.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Calendar</a></li>
                    <li><a href="contact_app.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Contact List</a></li>
                    <li><a href="contact_app_chat.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Chat</a></li>
                    <li><a href="extra_taskboard.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Todo</a></li>
                    <li><a href="mailbox.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Mailbox</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Globe"><span class="path1"></span><span class="path2"></span></i>Widgets</a>
                <ul>
                    <li><a href="widgets_blog.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Blog</a></li>
                    <li><a href="widgets_chart.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Chart</a></li>
                    <li><a href="widgets_list.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>List</a></li>
                    <li><a href="widgets_social.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Social</a></li>
                    <li><a href="widgets_statistic.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Statistic</a></li>
                    <li><a href="widgets_weather.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Weather</a></li>
                    <li><a href="widgets.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Widgets</a></li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Modals</a>
                        <ul>
                            <li><a href="component_modals.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Modals</a></li>
                            <li><a href="component_sweatalert.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Sweet Alert</a></li>
                            <li><a href="component_notification.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Toastr</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Maps</a>
                        <ul>
                            <li><a href="map_google.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Google Map</a></li>
                            <li><a href="map_vector.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Vector Map</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Lock-overturning"><span class="path1"></span><span class="path2"></span></i>Login
                    &amp; Error</a>
                <ul>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Authentication</a>
                        <ul>
                            <li><a href="auth_login.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Login</a></li>
                            <li><a href="auth_register.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Register</a></li>
                            <li><a href="auth_lockscreen.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Lockscreen</a></li>
                            <li><a href="auth_user_pass.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Recover password</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Miscellaneous</a>
                        <ul>
                            <li><a href="error_404.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Error 404</a></li>
                            <li><a href="error_500.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Error 500</a></li>
                            <li><a href="error_maintenance.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Maintenance</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Library"><span class="path1"></span><span class="path2"></span></i>UI</a>
                <ul>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Utilities</a>
                        <ul>
                            <li><a href="ui_grid.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Grid System</a></li>
                            <li><a href="ui_badges.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Badges</a></li>
                            <li><a href="ui_border_utilities.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Border</a></li>
                            <li><a href="ui_buttons.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Buttons</a></li>
                            <li><a href="ui_color_utilities.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Color</a></li>
                            <li><a href="ui_dropdown.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Dropdown</a></li>
                            <li><a href="ui_dropdown_grid.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Dropdown Grid</a></li>
                            <li><a href="ui_progress_bars.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Progress Bars</a></li>
                            <li><a href="ui_ribbons.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Ribbons</a></li>
                            <li><a href="ui_sliders.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Sliders</a></li>
                            <li><a href="ui_typography.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Typography</a></li>
                            <li><a href="ui_tab.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Tabs</a></li>
                            <li><a href="ui_timeline.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Timeline</a></li>
                            <li><a href="ui_timeline_horizontal.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Horizontal Timeline</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Card</a>
                        <ul>
                            <li><a href="box_cards.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>User Card</a></li>
                            <li><a href="box_advanced.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Advanced Card</a></li>
                            <li><a href="box_basic.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Basic Card</a></li>
                            <li><a href="box_color.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Card Color</a></li>
                            <li><a href="box_group.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Card Group</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Icons</a>
                        <ul>
                            <li><a href="icons_fontawesome.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Font Awesome</a></li>
                            <li><a href="icons_glyphicons.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Glyphicons</a></li>
                            <li><a href="icons_material.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Material Icons</a></li>
                            <li><a href="icons_themify.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Themify Icons</a></li>
                            <li><a href="icons_simpleline.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Simple Line Icons</a></li>
                            <li><a href="icons_cryptocoins.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Cryptocoins Icons</a></li>
                            <li><a href="icons_flag.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Flag Icons</a></li>
                            <li><a href="icons_weather.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Weather Icons</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Components</a>
                        <ul>
                            <li><a href="component_bootstrap_switch.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Bootstrap Switch</a>
                            </li>
                            <li><a href="component_date_paginator.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Date Paginator</a>
                            </li>
                            <li><a href="component_media_advanced.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Advanced Medias</a>
                            </li>
                            <li><a href="component_rangeslider.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Range Slider</a>
                            </li>
                            <li><a href="component_rating.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Ratings</a></li>
                            <li><a href="component_animations.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Animations</a></li>
                            <li><a href="extension_fullscreen.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Fullscreen</a></li>
                            <li><a href="extension_pace.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Pace</a></li>
                            <li><a href="component_nestable.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Nestable</a></li>
                            <li><a href="component_portlet_draggable.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Draggable
                                    Portlets</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Box2"><span class="path1"></span><span class="path2"></span></i>Forms & Table</a>
                <ul>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Forms</a>
                        <ul>
                            <li><a href="forms_advanced.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Form Elements</a></li>
                            <li><a href="forms_general.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Form Layout</a></li>
                            <li><a href="forms_wizard.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Form Wizard</a></li>
                            <li><a href="forms_validation.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Form Validation</a></li>
                            <li><a href="forms_mask.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Formatter</a></li>
                            <li><a href="forms_xeditable.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Xeditable Editor</a></li>
                            <li><a href="forms_dropzone.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Dropzone</a></li>
                            <li><a href="forms_code_editor.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Code Editor</a></li>
                            <li><a href="forms_editors.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Editor</a></li>
                            <li><a href="forms_editor_markdown.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Markdown</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Tables</a>
                        <ul>
                            <li><a href="tables_simple.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Simple tables</a></li>
                            <li><a href="tables_data.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Data tables</a></li>
                            <li><a href="tables_editable.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Editable Tables</a></li>
                            <li><a href="tables_color.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Table Color</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Chart-pie"><span class="path1"></span><span
                                class="path2"></span></i>Charts</a>
                <ul>
                    <li><a href="charts_chartjs.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>ChartJS</a></li>
                    <li><a href="charts_flot.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Flot</a></li>
                    <li><a href="charts_inline.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Inline charts</a></li>
                    <li><a href="charts_morris.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Morris</a></li>
                    <li><a href="charts_peity.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Peity</a></li>
                    <li><a href="charts_chartist.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Chartist</a></li>
                    <li><a href="charts_c3_axis.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Axis Chart</a></li>
                    <li><a href="charts_c3_bar.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Bar Chart</a></li>
                    <li><a href="charts_c3_data.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Data Chart</a></li>
                    <li><a href="charts_c3_line.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Line Chart</a></li>
                    <li><a href="charts_echarts_basic.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Basic Charts</a></li>
                    <li><a href="charts_echarts_bar.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Bar Chart</a></li>
                    <li><a href="charts_echarts_pie_doughnut.html"><i class="icon-Commit"><span
                                        class="path1"></span><span class="path2"></span></i>Pie & Doughnut Chart</a>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Selected-file"><span class="path1"></span><span class="path2"></span></i>Pages</a>
                <ul>
                    <li><a href="invoice.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Invoice</a></li>
                    <li><a href="invoicelist.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Invoice List</a></li>
                    <li><a href="extra_app_ticket.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Support Ticket</a></li>
                    <li><a href="extra_profile.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>User Profile</a></li>
                    <li><a href="contact_userlist_grid.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Userlist Grid</a></li>
                    <li><a href="contact_userlist.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Userlist</a></li>
                    <li><a href="sample_faq.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>FAQs</a></li>
                    <li><a href="#"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Extra
                            Pages</a>
                        <ul>
                            <li><a href="sample_blank.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Blank</a></li>
                            <li><a href="sample_coming_soon.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Coming Soon</a></li>
                            <li><a href="sample_custom_scroll.html"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Custom Scrolls</a>
                            </li>
                            <li><a href="sample_gallery.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Gallery</a></li>
                            <li><a href="sample_lightbox.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Lightbox Popup</a></li>
                            <li><a href="sample_pricing.html"><i class="icon-Commit"><span class="path1"></span><span
                                                class="path2"></span></i>Pricing</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#"><i class="icon-Cart2"><span class="path1"></span><span
                                class="path2"></span></i>Ecommerce</a>
                <ul>
                    <li><a href="ecommerce_products.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Products</a></li>
                    <li><a href="ecommerce_cart.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Products Cart</a></li>
                    <li><a href="ecommerce_products_edit.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Products Edit</a></li>
                    <li><a href="ecommerce_details.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Product Details</a></li>
                    <li><a href="ecommerce_orders.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Product Orders</a></li>
                    <li><a href="ecommerce_checkout.html"><i class="icon-Commit"><span class="path1"></span><span
                                        class="path2"></span></i>Products Checkout</a></li>
                </ul>
            </li>
            <li><a href="email_index.html"><i class="icon-Mailbox"><span class="path1"></span><span
                                class="path2"></span></i>Emails</a></li>
        </ul>
    </nav>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xxxl-9 col-xl-8 col-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="d-md-flex align-items-center text-md-start text-center">
                                    <div class="me-md-30">
                                        <img src="../images/svg-icon/color-svg/custom-21.svg" alt="" class="w-150"/>
                                    </div>
                                    <div class="d-lg-flex w-p100 align-items-center justify-content-between">
                                        <div class="me-lg-10 mb-lg-0 mb-10">
                                            <h3 class="mb-0">Today - 20% Discount on Lung Examinations</h3>
                                            <p class="mb-0 fs-16">The Package price includes: consultoin of a
                                                pulmonolgist, spirogrphy, cardiogram</p>
                                        </div>
                                        <div>
                                            <a href="#" class="waves-effect waves-light btn btn-primary text-nowrap">Know
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-20.svg" alt=""
                                                     class="w-120"/>
                                            </div>
                                            <div>
                                                <?php
                                                // Consulta para obtener el total de pacientes
                                                $sql_total_patients = "SELECT COUNT(*) AS total FROM patient_data";
                                                $result_total_patients = $mysqli->query($sql_total_patients);

                                                $total_patients = 0;  // Inicializar en 0 por si no se encuentran registros
                                                if ($result_total_patients->num_rows > 0) {
                                                    $row_total = $result_total_patients->fetch_assoc();
                                                    $total_patients = $row_total['total'];
                                                }
                                                ?>
                                                <h4 class="mb-0">Total Patients</h4>
                                                <h3 class="mb-0"><?php echo $total_patients; ?></h3>
                                                <!-- Mostrar el total de pacientes -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-18.svg" alt=""
                                                     class="w-120"/>
                                            </div>
                                            <div>
                                                <?php
                                                // Consulta para obtener el total de pacientes
                                                $sql_total_users = "SELECT COUNT(*) AS total FROM users";
                                                $result_total_users = $mysqli->query($sql_total_users);

                                                $total_users = 0;  // Inicializar en 0 por si no se encuentran registros
                                                if ($result_total_users->num_rows > 0) {
                                                    $row_total = $result_total_users->fetch_assoc();
                                                    $total_users = $row_total['total'];
                                                }
                                                ?>
                                                <h4 class="mb-0">Total Staffs</h4>
                                                <h3 class="mb-0"><?php echo $total_users; ?></h3>
                                                <!-- Mostrar el total de protocolo -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-19.svg" alt=""
                                                     class="w-120"/>
                                            </div>
                                            <div>
                                                <?php
                                                // Consulta para obtener el total de pacientes
                                                $sql_total_protocolo = "SELECT COUNT(*) AS total FROM protocolo_data";
                                                $result_total_protocolo = $mysqli->query($sql_total_protocolo);

                                                $total_protocolo = 0;  // Inicializar en 0 por si no se encuentran registros
                                                if ($result_total_protocolo->num_rows > 0) {
                                                    $row_total = $result_total_protocolo->fetch_assoc();
                                                    $total_protocolo = $row_total['total'];
                                                }
                                                ?>
                                                <h4 class="mb-0">Total Surgery</h4>
                                                <h3 class="mb-0"><?php echo $total_protocolo; ?></h3>
                                                <!-- Mostrar el total de protocolo -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Tipos de cirugías realizadas</h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="patient_statistics"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Recovery Statistics</h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="recovery_statistics"></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            // Consulta para obtener los 9 registros más recientes ordenados por fecha
                            $sql = "SELECT p.hc_number, p.fname, p.lname, p.lname2, p.fecha_nacimiento, p.ciudad, p.afiliacion, 
                                    pr.fecha_inicio, pr.id, pr.membrete, pr.form_id
                                    FROM patient_data p 
                                    INNER JOIN protocolo_data pr ON p.hc_number = pr.hc_number
                                    ORDER BY pr.fecha_inicio DESC, pr.id DESC
                                    LIMIT 6";
                            $result = $mysqli->query($sql);

                            if ($result->num_rows > 0) {
                                $patients = $result->fetch_all(MYSQLI_ASSOC);  // Guardar todos los datos en un array asociativo
                            } else {
                                $patients = [];  // Si no hay datos
                            }

                            // Consulta para obtener el total de pacientes en la tabla protocolo_data
                            $totalSql = "SELECT COUNT(*) as total FROM protocolo_data";
                            $totalResult = $mysqli->query($totalSql);
                            $totalPatients = $totalResult->fetch_assoc()['total'];  // Guardar el total de pacientes
                            ?>
                            <div class="col-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">Admitted Patient</h4>
                                        <div class="box-controls pull-right">
                                            <div class="lookup lookup-circle lookup-right">
                                                <input type="text" name="s">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                <tr class="bg-info-light">
                                                    <th>No</th>
                                                    <th>Fecha</th>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Edad</th>
                                                    <th>Procedimiento</th>
                                                    <th>Afiliación</th>
                                                    <th>Opciones</th>
                                                </tr>
                                                <?php if (!empty($patients)): ?>
                                                    <?php foreach ($patients as $index => $patient): ?>
                                                        <tr>
                                                            <td><?php echo $index + 1; ?></td>  <!-- Contador -->
                                                            <td><?php echo date('d/m/Y', strtotime($patient['fecha_inicio'])); ?></td>
                                                            <!-- Fecha de cirugía -->
                                                            <td><?php echo $patient['hc_number']; ?></td>
                                                            <!-- Número de historia clínica -->
                                                            <td>
                                                                <strong><?php echo $patient['fname'] . ' ' . $patient['lname'] . ' ' . $patient['lname2']; ?></strong>
                                                            </td>  <!-- Nombre completo -->
                                                            <td>
                                                                <?php
                                                                // Calcular la edad a partir de la fecha de nacimiento
                                                                $birthDate = new DateTime($patient['fecha_nacimiento']);
                                                                $today = new DateTime($patient['fecha_inicio']);
                                                                $age = $today->diff($birthDate)->y;
                                                                echo $age;
                                                                ?>
                                                            </td>
                                                            <td><?php echo $patient['membrete']; ?></td>
                                                            <!-- Procedimiento -->
                                                            <td><?php echo $patient['afiliacion']; ?></td>
                                                            <!-- Afiliación -->
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="edit_protocol.php?form_id=<?php echo $patient['form_id']; ?>&hc_number=<?php echo $patient['hc_number']; ?>"
                                                                       class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                                class="fa fa-pencil"></i></a>
                                                                    <a href="../generate_pdf.php?form_id=<?php echo $patient['form_id']; ?>&hc_number=<?php echo $patient['hc_number']; ?>"
                                                                       class="waves-effect waves-circle btn btn-circle btn-secondary btn-xs"><i
                                                                                class="fa fa-print"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No data available</td>
                                                    </tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="box-footer bg-light py-10 with-border">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0">Total <?php echo $totalPatients; ?> Patient(s)</p>
                                            <a type="button" class="waves-effect waves-light btn btn-primary">View
                                                All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-body px-0 pb-0">
                                        <div class="px-20 bb-1 pb-15 d-flex align-items-center justify-content-between">
                                            <h4 class="mb-0">Recent que..</h4>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button"
                                                        class="waves-effect waves-light btn btn-sm btn-primary-light">
                                                    All
                                                </button>
                                                <button type="button"
                                                        class="waves-effect waves-light mx-10 btn btn-sm btn-primary">
                                                    Unread
                                                </button>
                                                <button type="button"
                                                        class="waves-effect waves-light btn btn-sm btn-primary-light">
                                                    New
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="inner-user-div4">
                                            <div class="d-flex justify-content-between align-items-center pb-20 mb-10 bb-dashed border-bottom">
                                                <div class="pe-20">
                                                    <p class="fs-12 text-fade">14 Jun 2021 <span class="mx-10">/</span>
                                                        01:05PM</p>
                                                    <h4>Addiction blood bank bone marrow contagious disinfectants?</h4>
                                                    <div class="d-flex align-items-center">
                                                        <button type="button"
                                                                class="waves-effect waves-light btn me-10 btn-xs btn-primary-light">
                                                            Read more
                                                        </button>
                                                        <button type="button"
                                                                class="waves-effect waves-light btn btn-xs btn-primary-light">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                       class="waves-effect waves-circle btn btn-circle btn-outline btn-light btn-lg"><i
                                                                class="fa fa-comments"></i></a>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center pb-20 bb-dashed border-bottom">
                                                <div class="pe-20">
                                                    <p class="fs-12 text-fade">17 Jun 2021 <span class="mx-10">/</span>
                                                        02:05PM</p>
                                                    <h4>Triggered asthma anesthesia blood type bone marrow
                                                        cartilage?</h4>
                                                    <div class="d-flex align-items-center">
                                                        <button type="button"
                                                                class="waves-effect waves-light btn me-10 btn-xs btn-primary-light">
                                                            Read more
                                                        </button>
                                                        <button type="button"
                                                                class="waves-effect waves-light btn btn-xs btn-primary-light">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                       class="waves-effect waves-circle btn btn-circle btn-outline btn-light btn-lg"><i
                                                                class="fa fa-comments"></i></a>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center pb-20 mb-10 bb-dashed border-bottom">
                                                <div class="pe-20">
                                                    <p class="fs-12 text-fade">14 Jun 2021 <span class="mx-10">/</span>
                                                        01:05PM</p>
                                                    <h4>Addiction blood bank bone marrow contagious disinfectants?</h4>
                                                    <div class="d-flex align-items-center">
                                                        <button type="button"
                                                                class="waves-effect waves-light btn me-10 btn-xs btn-primary-light">
                                                            Read more
                                                        </button>
                                                        <button type="button"
                                                                class="waves-effect waves-light btn btn-xs btn-primary-light">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                       class="waves-effect waves-circle btn btn-circle btn-outline btn-light btn-lg"><i
                                                                class="fa fa-comments"></i></a>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center pb-20 bb-dashed border-bottom">
                                                <div class="pe-20">
                                                    <p class="fs-12 text-fade">17 Jun 2021 <span class="mx-10">/</span>
                                                        02:05PM</p>
                                                    <h4>Triggered asthma anesthesia blood type bone marrow
                                                        cartilage?</h4>
                                                    <div class="d-flex align-items-center">
                                                        <button type="button"
                                                                class="waves-effect waves-light btn me-10 btn-xs btn-primary-light">
                                                            Read more
                                                        </button>
                                                        <button type="button"
                                                                class="waves-effect waves-light btn btn-xs btn-primary-light">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#"
                                                       class="waves-effect waves-circle btn btn-circle btn-outline btn-light btn-lg"><i
                                                                class="fa fa-comments"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Laboratory test</h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="news-slider owl-carousel owl-sl">
                                            <div>
                                                <div class="d-flex align-items-center mb-10">
                                                    <div class="d-flex flex-column flex-grow-1 fw-500">
                                                        <p class="hover-primary text-fade mb-1 fs-14"><i
                                                                    class="fa fa-link"></i> Shawn Hampton</p>
                                                        <span class="text-dark fs-16">Beta 2 Microglobulin</span>
                                                        <p class="mb-0 fs-14">Marker Test <span
                                                                    class="badge badge-dot badge-primary"></span></p>
                                                    </div>
                                                    <div>
                                                        <div class="dropdown">
                                                            <a data-bs-toggle="dropdown" href="#"
                                                               class="base-font mx-30"><i
                                                                        class="ti-more-alt text-muted"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-import"></i> Import</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-export"></i> Export</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-printer"></i> Print</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-settings"></i> Settings</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-end py-10">
                                                    <div>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light">Details</a>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light">Contact
                                                            Patient</a>
                                                    </div>
                                                    <div>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light"><i
                                                                    class="fa fa-check"></i> Archive</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center mb-10">
                                                    <div class="d-flex flex-column flex-grow-1 fw-500">
                                                        <p class="hover-primary text-fade mb-1 fs-14"><i
                                                                    class="fa fa-link"></i> Johen Doe</p>
                                                        <span class="text-dark fs-16">Keeping pregnant</span>
                                                        <p class="mb-0 fs-14">Prga Test <span
                                                                    class="badge badge-dot badge-primary"></span></p>
                                                    </div>
                                                    <div>
                                                        <div class="dropdown">
                                                            <a data-bs-toggle="dropdown" href="#"
                                                               class="base-font mx-30"><i
                                                                        class="ti-more-alt text-muted"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-import"></i> Import</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-export"></i> Export</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-printer"></i> Print</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-settings"></i> Settings</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-end py-10">
                                                    <div>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light">Details</a>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light">Contact
                                                            Patient</a>
                                                    </div>
                                                    <div>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light"><i
                                                                    class="fa fa-check"></i> Archive</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center mb-10">
                                                    <div class="d-flex flex-column flex-grow-1 fw-500">
                                                        <p class="hover-primary text-fade mb-1 fs-14"><i
                                                                    class="fa fa-link"></i> Polly Paul</p>
                                                        <span class="text-dark fs-16">USG + Consultation</span>
                                                        <p class="mb-0 fs-14">Marker Test <span
                                                                    class="badge badge-dot badge-primary"></span></p>
                                                    </div>
                                                    <div>
                                                        <div class="dropdown">
                                                            <a data-bs-toggle="dropdown" href="#"
                                                               class="base-font mx-30"><i
                                                                        class="ti-more-alt text-muted"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-import"></i> Import</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-export"></i> Export</a>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-printer"></i> Print</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="#"><i
                                                                            class="ti-settings"></i> Settings</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-end py-10">
                                                    <div>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light">Details</a>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light">Contact
                                                            Patient</a>
                                                    </div>
                                                    <div>
                                                        <a href="#"
                                                           class="waves-effect waves-light btn btn-sm btn-primary-light"><i
                                                                    class="fa fa-check"></i> Archive</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxxl-3 col-xl-4 col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Cirugías diarias</h4>
                            </div>
                            <div class="box-body">
                                <div id="total_patient"></div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Report</h4>
                            </div>
                            <div class="box-body">
                                <div class="box no-shadow">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-22.svg" alt=""
                                                     class="w-100"/>
                                            </div>
                                            <div>
                                                <h5 class="mb-5">2nd floor Bathroom had a broken door</h5>
                                                <p class="text-fade">10 minutes ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box no-shadow">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-23.svg" alt=""
                                                     class="w-100"/>
                                            </div>
                                            <div>
                                                <h5 class="mb-5">Brownout In the Administration Room</h5>
                                                <p class="text-fade">15 minutes ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box no-shadow">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-22.svg" alt=""
                                                     class="w-100"/>
                                            </div>
                                            <div>
                                                <h5 class="mb-5">1nd floor Bathroom had a broken Tap</h5>
                                                <p class="text-fade">20 minutes ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Doctor List</h4>
                                <p class="mb-0 pull-right">Today</p>
                            </div>
                            <div class="box-body">
                                <div class="inner-user-div3">
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="../images/avatar/avatar-1.png"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-primary mb-1 fs-16">Dr. Jaylon
                                                Stanton</a>
                                            <span class="text-fade">Dentist</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                        class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="../images/avatar/avatar-10.png"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-danger mb-1 fs-16">Dr. Carla
                                                Schleifer</a>
                                            <span class="text-fade">Oculist</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                        class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="../images/avatar/avatar-11.png"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-success mb-1 fs-16">Dr. Hanna Geidt</a>
                                            <span class="text-fade">Surgeon</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                        class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-30">
                                        <div class="me-15">
                                            <img src="../images/avatar/avatar-12.png"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-info mb-1 fs-16">Dr. Roger George</a>
                                            <span class="text-fade">General Practitioners</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                        class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="me-15">
                                            <img src="../images/avatar/avatar-15.png"
                                                 class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1 fw-500">
                                            <a href="#" class="text-dark hover-warning mb-1 fs-16">Dr. Natalie doe</a>
                                            <span class="text-fade">Physician</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                        class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Inbox</span>
                                                    <span class="badge badge-pill badge-info">5</span>
                                                </a>
                                                <a class="dropdown-item" href="#">Sent</a>
                                                <a class="dropdown-item" href="#">Spam</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Draft</span>
                                                    <span class="badge badge-pill badge-default">1</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <script>document.write(new Date().getFullYear())</script>
        <a href="https://www.consulmed.me/">Consulmed. Empowering EHR & Digital Medical Support</a>. All Rights Reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar">

        <div class="rpanel-title"><span class="pull-right btn btn-circle btn-danger" data-toggle="control-sidebar"><i
                        class="ion ion-close text-white"></i></span></div>  <!-- Create the tabs -->
        <ul class="nav nav-tabs control-sidebar-tabs">
            <li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab" class="active"><i
                            class="mdi mdi-message-text"></i></a></li>
            <li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i
                            class="mdi mdi-playlist-check"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <div class="flexbox">
                    <a href="javascript:void(0)" class="text-grey">
                        <i class="ti-more"></i>
                    </a>
                    <p>Users</p>
                    <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
                </div>
                <div class="lookup lookup-sm lookup-right d-none d-lg-block">
                    <input type="text" name="s" placeholder="Search" class="w-p100">
                </div>
                <div class="media-list media-list-hover mt-20">
                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-success" href="#">
                            <img src="../images/avatar/1.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Tyler</strong></a>
                            </p>
                            <p>Praesent tristique diam...</p>
                            <span>Just now</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-danger" href="#">
                            <img src="../images/avatar/2.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Luke</strong></a>
                            </p>
                            <p>Cras tempor diam ...</p>
                            <span>33 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-warning" href="#">
                            <img src="../images/avatar/3.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Evan</strong></a>
                            </p>
                            <p>In posuere tortor vel...</p>
                            <span>42 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-primary" href="#">
                            <img src="../images/avatar/4.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Evan</strong></a>
                            </p>
                            <p>In posuere tortor vel...</p>
                            <span>42 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-success" href="#">
                            <img src="../images/avatar/1.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Tyler</strong></a>
                            </p>
                            <p>Praesent tristique diam...</p>
                            <span>Just now</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-danger" href="#">
                            <img src="../images/avatar/2.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Luke</strong></a>
                            </p>
                            <p>Cras tempor diam ...</p>
                            <span>33 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-warning" href="#">
                            <img src="../images/avatar/3.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Evan</strong></a>
                            </p>
                            <p>In posuere tortor vel...</p>
                            <span>42 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-primary" href="#">
                            <img src="../images/avatar/4.jpg" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16">
                                <a class="hover-primary" href="#"><strong>Evan</strong></a>
                            </p>
                            <p>In posuere tortor vel...</p>
                            <span>42 min ago</span>
                        </div>
                    </div>

                </div>

            </div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <div class="flexbox">
                    <a href="javascript:void(0)" class="text-grey">
                        <i class="ti-more"></i>
                    </a>
                    <p>Todo List</p>
                    <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
                </div>
                <ul class="todo-list mt-20">
                    <li class="py-15 px-5 by-1">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_1" class="filled-in">
                        <label for="basic_checkbox_1" class="mb-0 h-15"></label>
                        <!-- todo text -->
                        <span class="text-line">Nulla vitae purus</span>
                        <!-- Emphasis label -->
                        <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_2" class="filled-in">
                        <label for="basic_checkbox_2" class="mb-0 h-15"></label>
                        <span class="text-line">Phasellus interdum</span>
                        <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5 by-1">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_3" class="filled-in">
                        <label for="basic_checkbox_3" class="mb-0 h-15"></label>
                        <span class="text-line">Quisque sodales</span>
                        <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_4" class="filled-in">
                        <label for="basic_checkbox_4" class="mb-0 h-15"></label>
                        <span class="text-line">Proin nec mi porta</span>
                        <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5 by-1">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_5" class="filled-in">
                        <label for="basic_checkbox_5" class="mb-0 h-15"></label>
                        <span class="text-line">Maecenas scelerisque</span>
                        <small class="badge bg-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_6" class="filled-in">
                        <label for="basic_checkbox_6" class="mb-0 h-15"></label>
                        <span class="text-line">Vivamus nec orci</span>
                        <small class="badge bg-info"><i class="fa fa-clock-o"></i> 1 month</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5 by-1">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_7" class="filled-in">
                        <label for="basic_checkbox_7" class="mb-0 h-15"></label>
                        <!-- todo text -->
                        <span class="text-line">Nulla vitae purus</span>
                        <!-- Emphasis label -->
                        <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                        <!-- General tools such as edit or delete-->
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_8" class="filled-in">
                        <label for="basic_checkbox_8" class="mb-0 h-15"></label>
                        <span class="text-line">Phasellus interdum</span>
                        <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5 by-1">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_9" class="filled-in">
                        <label for="basic_checkbox_9" class="mb-0 h-15"></label>
                        <span class="text-line">Quisque sodales</span>
                        <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                    <li class="py-15 px-5">
                        <!-- checkbox -->
                        <input type="checkbox" id="basic_checkbox_10" class="filled-in">
                        <label for="basic_checkbox_10" class="mb-0 h-15"></label>
                        <span class="text-line">Proin nec mi porta</span>
                        <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
                        <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash-o"></i>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- ./side demo panel -->
<div class="sticky-toolbar">
    <a href="https://themeforest.net/item/doclinic-medical-responsive-bootstrap-admin-dashboard/32737529"
       data-bs-toggle="tooltip" data-bs-placement="left" title="Buy Now"
       class="waves-effect waves-light btn btn-success btn-flat mb-5 btn-sm" target="_blank">
        <span class="icon-Money"><span class="path1"></span><span class="path2"></span></span>
    </a>
    <a href="https://themeforest.net/user/multipurposethemes/portfolio" data-bs-toggle="tooltip"
       data-bs-placement="left" title="Portfolio" class="waves-effect waves-light btn btn-danger btn-flat mb-5 btn-sm"
       target="_blank">
        <span class="icon-Image"></span>
    </a>
    <a id="chat-popup" href="#" data-bs-toggle="tooltip" data-bs-placement="left" title="Live Chat"
       class="waves-effect waves-light btn btn-warning btn-flat btn-sm">
        <span class="icon-Group-chat"><span class="path1"></span><span class="path2"></span></span>
    </a>
</div>
<!-- Sidebar -->

<div id="chat-box-body">
    <div id="chat-circle" class="waves-effect waves-circle btn btn-circle btn-sm btn-warning l-h-50">
        <div id="chat-overlay"></div>
        <span class="icon-Group-chat fs-18"><span class="path1"></span><span class="path2"></span></span>
    </div>

    <div class="chat-box">
        <div class="chat-box-header p-15 d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <button class="waves-effect waves-circle btn btn-circle btn-primary-light h-40 w-40 rounded-circle l-h-45"
                        type="button" data-bs-toggle="dropdown">
                    <span class="icon-Add-user fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
                <div class="dropdown-menu min-w-200">
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Color me-15"></span>
                        New Group</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Clipboard me-15"><span class="path1"></span><span class="path2"></span><span
                                    class="path3"></span><span class="path4"></span></span>
                        Contacts</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Group me-15"><span class="path1"></span><span class="path2"></span></span>
                        Groups</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Active-call me-15"><span class="path1"></span><span
                                    class="path2"></span></span>
                        Calls</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Settings1 me-15"><span class="path1"></span><span class="path2"></span></span>
                        Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Question-circle me-15"><span class="path1"></span><span class="path2"></span></span>
                        Help</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Notifications me-15"><span class="path1"></span><span
                                    class="path2"></span></span>
                        Privacy</a>
                </div>
            </div>
            <div class="text-center flex-grow-1">
                <div class="text-dark fs-18">Mayra Sibley</div>
                <div>
                    <span class="badge badge-sm badge-dot badge-primary"></span>
                    <span class="text-muted fs-12">Active</span>
                </div>
            </div>
            <div class="chat-box-toggle">
                <button id="chat-box-toggle"
                        class="waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-45"
                        type="button">
                    <span class="icon-Close fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
            </div>
        </div>
        <div class="chat-box-body">
            <div class="chat-box-overlay">
            </div>
            <div class="chat-logs">
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="../images/avatar/2.jpg" class="avatar avatar-lg">
                            </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                            <p class="text-muted fs-12 mb-0">2 Hours</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Hi there, I'm Jesse and you?
                    </div>
                </div>
                <div class="chat-msg self">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">You</a>
                            <p class="text-muted fs-12 mb-0">3 minutes</p>
                        </div>
                        <span class="msg-avatar">
                                <img src="../images/avatar/3.jpg" class="avatar avatar-lg">
                            </span>
                    </div>
                    <div class="cm-msg-text">
                        My name is Anne Clarc.
                    </div>
                </div>
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="../images/avatar/2.jpg" class="avatar avatar-lg">
                            </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                            <p class="text-muted fs-12 mb-0">40 seconds</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Nice to meet you Anne.<br>How can i help you?
                    </div>
                </div>
            </div><!--chat-log -->
        </div>
        <div class="chat-input">
            <form>
                <input type="text" id="chat-input" placeholder="Send a message..."/>
                <button type="submit" class="chat-submit" id="chat-submit">
                    <span class="icon-Send fs-22"></span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Page Content overlay -->
<?php
// Consulta SQL para obtener el número de procedimientos por día
$sql = "SELECT DATE(fecha_inicio) as fecha, COUNT(*) as total_procedimientos 
        FROM protocolo_data 
        GROUP BY DATE(fecha_inicio)
        ORDER BY fecha DESC 
        LIMIT 12";  // Limitar a los últimos 12 días

$result = $mysqli->query($sql);

$fechas = [];
$procedimientos_por_dia = [];  // Renombrar esta variable para evitar conflicto

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fechas[] = date('Y-m-d', strtotime($row['fecha']));
        $procedimientos_por_dia[] = $row['total_procedimientos'];
    }
} else {
    $fechas = ['No data'];
    $procedimientos_por_dia = [0];  // Asegurarse de no usar la misma variable dos veces
}

// Convertir los arrays a formato JSON
$fechas_json = json_encode($fechas);
$procedimientos_dia_json = json_encode($procedimientos_por_dia);

// Obtener el mes y año actual
$current_month = date('m');
$current_year = date('Y');

// Consulta SQL para contar los procedimientos por membrete en el mes actual
$sql = "SELECT membrete, COUNT(*) as total_procedimientos 
        FROM protocolo_data 
        WHERE MONTH(fecha_inicio) = '$current_month' AND YEAR(fecha_inicio) = '$current_year'
        GROUP BY membrete";

$result = $mysqli->query($sql);

$membretes = [];
$procedimientos_por_membrete = [];  // Renombrar esta variable también

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $membretes[] = $row['membrete'];
        $procedimientos_por_membrete[] = $row['total_procedimientos'];
    }
} else {
    $membretes = ['No data'];
    $procedimientos_por_membrete = [0];
}

// Convertir los arrays a formato JSON
$membretes_json = json_encode($membretes);
$procedimientos_membrete_json = json_encode($procedimientos_por_membrete);

// Consulta SQL para contar los procedimientos por afiliación en el mes actual
$sql = "SELECT p.afiliacion, COUNT(*) as total_procedimientos
        FROM protocolo_data pr
        INNER JOIN patient_data p ON pr.hc_number = p.hc_number
        WHERE MONTH(pr.fecha_inicio) = '$current_month' AND YEAR(pr.fecha_inicio) = '$current_year'
        GROUP BY p.afiliacion";

$result = $mysqli->query($sql);

$afiliaciones = [];
$procedimientos_por_afiliacion = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $afiliaciones[] = $row['afiliacion'];  // Guardar las afiliaciones
        $procedimientos_por_afiliacion[] = $row['total_procedimientos'];  // Total de procedimientos por afiliación
    }
} else {
    $afiliaciones = ['No data'];
    $procedimientos_por_afiliacion = [0];
}

// Convertir los arrays a formato JSON para el uso en JavaScript
$afiliaciones_json = json_encode($afiliaciones);
$procedimientos_por_afiliacion_json = json_encode($procedimientos_por_afiliacion);
?>

<!-- Vendor JS -->
<script src="js/vendors.min.js"></script>
<script src="js/pages/chat-popup.js"></script>
<script src="../assets/icons/feather-icons/feather.min.js"></script>

<script src="../assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
<script src="../assets/vendor_components/OwlCarousel2/dist/owl.carousel.js"></script>

<!-- Doclinic App -->
<script src="js/jquery.smartmenus.js"></script>
<script src="js/menus.js"></script>
<script src="js/template.js"></script>
<script>
    var fechas = <?php echo $fechas_json; ?>;
    var procedimientos_dia = <?php echo $procedimientos_dia_json; ?>;  // Usar nombre único
    var membretes = <?php echo $membretes_json; ?>;
    var procedimientos_membrete = <?php echo $procedimientos_membrete_json; ?>;  // Usar nombre único
    var afiliaciones = <?php echo $afiliaciones_json; ?>;
    var procedimientos_por_afiliacion = <?php echo $procedimientos_por_afiliacion_json; ?>;
</script>
<script src="js/pages/dashboard3.js?v=<?php echo time(); ?>"></script>
</body>
</html>

