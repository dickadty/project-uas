<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear">
                            <span class="block m-t-xs">
                                <strong class="font-bold"><?php echo $_SESSION['nama'] ?></strong>
                            </span>
                            <span class="text-muted text-xs block"><?php echo $_SESSION['role'] ?> <b class="caret"></b></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="login?logout=1">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>

            <?php
            $role = $_SESSION['role'];
            if ($role === 'Panitia' || $role === 'Administrator'): ?>
                <li>
                    <a href='<?php echo "$basePath/dashboard" ?>'><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li>
                    <a href='<?php echo "$basePath/qurban" ?>'><i class="fa fa-user-circle" aria-hidden="true"></i> <span class="nav-label">Data Peserta</span></a>
                </li>
                <li>
                    <a href='<?php echo "$basePath/keuangan" ?>'><i class="fa fa-dollar"></i> <span class="nav-label">Keuangan</span></a>
                </li>
                <li>
                    <a href='<?php echo "$basePath/pembagian" ?>'><i class="fa fa-handshake-o" aria-hidden="true"></i> <span class="nav-label">Pembagian Daging</span></a>
                </li>
                <li>
                    <a href="qrcode"><i class="fa fa-plus-square"></i> <span class="nav-label">QR Code</span></a>
                </li>
                <li>
                    <a href="laporan.html"><i class="fa fa-file-pdf-o"></i> <span class="nav-label">Laporan</span></a>
                </li>
            <?php else: ?>
                <li>
                    <a href='<?php echo "$basePath/dashboard" ?>'><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li>
                    <a href="qrcode"><i class="fa fa-plus-square"></i> <span class="nav-label">QR Code</span></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
