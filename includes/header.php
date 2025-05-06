<?php include_once '../config/config.php'; ?>

<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
        <form role="search" class="navbar-form-custom" action="search_results.html">
            <div class="form-group">
                <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
            </div>
        </form>
    </div>

    <ul class="nav navbar-top-links navbar-right">
        <li><span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span></li>
        <!-- Mailbox -->
        <li class="dropdown">
            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
            </a>
            <ul class="dropdown-menu dropdown-messages">
                <!-- Messages List -->
            </ul>
        </li>
        <!-- Notification -->
        <li class="dropdown">
            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
            </a>
            <ul class="dropdown-menu dropdown-alerts">
                <!-- Alerts List -->
            </ul>
        </li>

        <!-- Logout -->
        <li>
            <a href="login.html">
                <i class="fa fa-sign-out"></i> Log out
            </a>
        </li>

        <!-- Sidebar Header -->
        <li>
            <a class="right-sidebar-toggle">
                <i class="fa fa-tasks"></i>
            </a>
        </li>
    </ul>
</nav>
