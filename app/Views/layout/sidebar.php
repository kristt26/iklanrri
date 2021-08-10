<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../../index3.html" class="brand-link">
        <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">RRI</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?=base_url('profile')?>" class="d-block">Alexander Pierce</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if(session()->get('role')=="Admin"):?>
                <li class="nav-item">
                    <a href="<?=base_url('admin/Home')?>" class="nav-link">
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
                <?php endif; ?>

                <!-- Menu Pemesan -->

                <?php if(session()->get('role')=="Pemesan"):?>
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
                <?php endif; ?>

                <?php if(session()->get('role')=="Siaran"):?>
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
                <?php endif; ?>
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