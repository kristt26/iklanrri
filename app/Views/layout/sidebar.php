<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../../index3.html" class="brand-link navbar-rri">
        <img src="../../dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">RRI Nusantara</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : '../../dist/img/avatar6.png'?>"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?=base_url('profile')?>"
                    class="d-block"><?=isset($_SESSION['nama']) ? $_SESSION['nama'] : $_SESSION['fullname']?></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if (session()->get('role') == "Admin"): ?>
                <li class="nav-item">
                    <a href="<?=base_url('admin/home')?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('admin/layanan')?>" class="nav-link">
                        <i class="nav-icon fa fa-rss"></i>
                        <p>Layanan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('admin/tarif')?>" class="nav-link">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Tarif</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('admin/users')?>" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('admin/statusbayar')?>" class="nav-link">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>Status Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('admin/iklantayang')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Iklan Tayang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('admin/jadwal')?>" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Jadwal Siaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void()" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=base_url('admin/laporan/iklan')?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pemasangan Iklan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url('admin/laporan/pendapatan')?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pendapatan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>

                <!-- Menu Pemesan -->

                <?php if (session()->get('role') == "Pemesan"): ?>
                <li class="nav-item">
                    <a href="<?=base_url('home')?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('iklan')?>" class="nav-link">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>
                            Pasang Iklan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('jadwal')?>" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Jadwal Siaran</p>
                    </a>
                </li>
                <?php endif;?>

                <?php if (session()->get('role') == "Siaran"): ?>
                <li class="nav-item">
                    <a href="<?=base_url('siaran/home')?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('siaran/order')?>" class="nav-link">
                        <i class="nav-icon fas fa-bookmark"></i>
                        <p>
                            Order
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('siaran/jadwal')?>" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Jadwal Siaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?=base_url('siaran/iklantayang')?>" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Iklan Tayang</p>
                    </a>
                </li>
                <?php endif;?>
            </ul>
        </nav>
        <hr style="background-color: #666d75;">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?=base_url('auth/logout')?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>