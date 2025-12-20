<div class="collapse navbar-collapse" id="sidenav-collapse-main">
    <ul class="navbar-nav">
        <li class="nav-item">
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.dashboard') > -1; ?>
            <a class="nav-link<?php echo $active ? ' active' : '' ?>" href="<?php echo route('admin.dashboard') ?>">
                <i class="ni ni-tv-2 text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
            </a>
        </li>
        <?php

        if(Permissions::hasPermission('pages', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.pages') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo $active ? ' active' : '' ?>" href="<?php echo route('admin.pages') ?>">
                    <i class="fas fa-columns text-info"></i>
                    <span class="nav-link-text">Pages</span>
                </a>
            </li>
            <li class="nav-item">
                <?php $active = strpos(request()->route()->getAction()['as'], 'admin.pages.home') > -1; ?>
                <a class="nav-link<?php echo $active ? ' active' : '' ?>" href="<?php echo route('admin.pages.home') ?>">
                    <i class="ni ni-tv-2 text-primary"></i>
                    <span class="nav-link-text">Home Page</span>
                </a>
            </li>
        <?php endif;
        ?>


        <?php /*if(Permissions::hasPermission('blogs', 'listing') || Permissions::hasPermission('blog_categories', 'listing')): ?>
        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.blog') > -1; ?>
        <li class="nav-item">
            <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="#submenu_blogs" data-toggle="collapse">
                <i class="fas fa-blog text-yellow"></i>
                <span class="nav-link-text">Blogs</span>
            </a>
            <ul class="list-unstyled submenu collapse<?php echo ($active ? ' show' : '') ?>" id="submenu_blogs">
                <?php $active = strpos(request()->route()->getAction()['as'], 'admin.blogs.categories') > -1;?>
                <?php if(Permissions::hasPermission('blogs', 'listing')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo (!$active ? ' active' : '') ?>" href="<?php echo route('admin.blogs') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-yellow"></i>
                            <span class="status">Blogs</span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Permissions::hasPermission('blog_categories', 'listing')): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.blogs.categories') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-yellow"></i>
                            <span class="status">Categories</span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif;*/ ?>


        <?php if(Permissions::hasPermission('users', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.users') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.users') ?>">
                    <i class="fas fa-users text-cyan"></i>
                    <span class="nav-link-text">Customers</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('ware_houses', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.wareHouses') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.wareHouses') ?>">
                    <i class="fas fa-warehouse"></i>
                    <span class="nav-link-text">Ware House</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('suppliers', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.suppliers') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.suppliers') ?>">
                    <i class="fas fa-handshake text-cyan"></i>
                    <span class="nav-link-text">Suppliers</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('schools', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.schools') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.schools') ?>">
                    <i class="fas fa-school"></i>
                    <span class="nav-link-text">Schools</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('shops', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.shops') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.shops') ?>">
                    <i class="fas fa-bags-shopping text-teal"></i>
                    <span class="nav-link-text">Shops</span>
                </a>
            </li>
        <?php endif;?>
        <?php if(Permissions::hasPermission('products', 'listing') || Permissions::hasPermission('product_categories', 'listing')): ?>
        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.products') > -1; ?>
        <li class="nav-item">
            <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="#submenu_products" data-toggle="collapse">
                <i class="fab fa-product-hunt text-pink"></i>
                <span class="nav-link-text">Products</span>
            </a>
            <ul class="list-unstyled submenu collapse<?php echo ($active ? ' show' : '') ?>" id="submenu_products">
                <?php $activeProducts = strpos(request()->route()->getAction()['as'], 'admin.products') > -1;?>
                <?php $activeUniforms = strpos(request()->route()->getAction()['as'], 'admin.uniforms') > -1;?>
                <?php $activeCategories = strpos(request()->route()->getAction()['as'], 'admin.products.categories') > -1;?>
                <?php $activeSubCategories = strpos(request()->route()->getAction()['as'], 'admin.products.subCategories') > -1;?>
                <?php if(Permissions::hasPermission('products', 'listing')): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo (!$activeProducts ? ' active' : '') ?>" href="<?php echo route('admin.products') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-pink"></i>
                            <span class="status">Products</span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Permissions::hasPermission('uniforms', 'listing')): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo (!$activeProducts ? ' active' : '') ?>" href="<?php echo route('admin.uniforms') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-pink"></i>
                            <span class="status">Uniforms</span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(Permissions::hasPermission('product_categories', 'listing')): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($activeCategories ? ' active' : '') ?>" href="<?php echo route('admin.products.categories') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-pink"></i>
                            <span class="status">Categories</span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(Permissions::hasPermission('product_categories', 'listing')): ?>
                <li class="nav-item">
                    <a class="nav-link<?php echo ($activeSubCategories  ? ' active' : '') ?>" href="<?php echo route('admin.products.subCategories') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-pink"></i>
                            <span class="status">Sub Categories</span>
                        </span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>
        <?php /*if(Permissions::hasPermission('offers', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.contactUs') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.offers') ?>">
                    <i class="fas fa-badge-percent text-black"></i>
                    <span class="nav-link-text">Offers</span>
                </a>
            </li>
        <?php endif;*/ ?>
        <?php if(Permissions::hasPermission('brands', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.brands') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.brands') ?>">
                    <i class="fas fa-globe text-orange"></i>
                    <span class="nav-link-text">Brands</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('coupons', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.coupons') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.coupons') ?>">
                    <i class="fas fa-percent text-green"></i>
                    <span class="nav-link-text">Coupons</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('gift_voucher', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.gift_voucher') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.gift_voucher') ?>">
                    <i class="fas fa-percent text-green"></i>
                    <span class="nav-link-text">Gift Voucher</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('orders', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.orders') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.orders') ?>">
                    <i class="fas fa-shopping-cart text-teal"></i>
                    <span class="nav-link-text">Orders</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('logo_prices', 'update')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.logoPrices') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.logoPrice') ?>">
                    <i class="fas fa-tag text-purple"></i>
                    <span class="nav-link-text">Logo Prices</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('staff', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.staff') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.staff') ?>">
                    <i class="fas fa-user-tie text-purple"></i>
                    <span class="nav-link-text">Staff</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('colours', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.colours') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.colours') ?>">
                    <i class="fas fa-palette text-red"></i>
                    <span class="nav-link-text">Colours</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('sizes', 'update')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.size') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.size') ?>">
                    <i class="fas fa-ruler text-yellow"></i>
                    <span class="nav-link-text">Size chart</span>
                </a>
            </li>
        <?php endif; ?>
        <?php /*if(Permissions::hasPermission('ratings', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.ratings') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.ratings') ?>">
                    <i class="fas fa-star text-blue"></i>
                    <span class="nav-link-text">Rating</span>
                </a>
            </li>
        <?php endif;*/ ?>
        <?php if(Permissions::hasPermission('sliders', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.sliders') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.sliders') ?>">
                    <i class="fas fa-sliders-h text-coffee"></i>
                    <span class="nav-link-text">Slider</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('contact_us', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.contactUs') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.contactUs') ?>">
                    <i class="fas fa-envelope text-purple"></i>
                    <span class="nav-link-text">Contact Us</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if(Permissions::hasPermission('reports', 'listing')): ?>

        <li class="nav-item">
            <a class="nav-link" href="#reports" data-toggle="collapse">
                <i class="fab fa-product-hunt text-pink"></i>
                <span class="nav-link-text">Reports</span>
            </a>
            <ul class="list-unstyled submenu collapse" id="reports">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('admin.orders.exportOrders') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-pink"></i>
                            <span class="status">Orders Report</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('admin.orders.exportLogos') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-pink"></i>
                            <span class="status">Logos Report</span>
                        </span>
                    </a>
                </li>

            </ul>
        </li>
        <?php endif; ?>

        <?php if(Permissions::hasPermission('sliders', 'listing')): ?>
            <?php $active = strpos(request()->route()->getAction()['as'], 'admin.menu.add') > -1; ?>
            <li class="nav-item">
                <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.menu.add') ?>">
                    <i class="fas fa-ellipsis-h text-coffee"></i>
                    <span class="nav-link-text">Menu</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <!-- Divider -->
    <hr class="my-3">
    <?php if(AdminAuth::isAdmin()): ?>
    <!-- Heading -->
    <h6 class="navbar-heading p-0 text-muted">
        <span class="docs-normal">Others</span>
    </h6>
    <?php endif; ?>
    <!-- Navigation -->
    <ul class="navbar-nav mb-md-3">
        <?php if(AdminAuth::isAdmin()): ?>
        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.settings.home') > -1 || strpos(request()->route()->getAction()['as'], 'admin.searchSugessions') > -1; ?>

        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.emailTemplates') > -1; ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $active ? ' active' : '' ?>" href="<?php echo route('admin.emailTemplates') ?>">
                <i class="ni ni-email-83"></i>
                <span class="nav-link-text">Email Templates</span>
            </a>
        </li>
        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.admins') > -1; ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $active ? ' active' : '' ?>" href="<?php echo route('admin.admins') ?>">
                <i class="fas fa-users"></i>
                <span class="nav-link-text">Manage Admins</span>
            </a>
        </li>
        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.activities') > -1; ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $active ? ' active' : '' ?>" href="#submenu_activites" data-toggle="collapse">
                <i class="ni ni-bullet-list-67"></i>
                <span class="nav-link-text">Activities</span>
            </a>
            <ul class="list-unstyled submenu collapse<?php echo ($active ? ' show' : '') ?>" id="submenu_activites">
                <li class="nav-item">
                    <a class="nav-link<?php echo (request()->route()->getAction()['as'] == 'admin.activities.logs' ? ' active' : '') ?>" href="<?php echo route('admin.activities.logs') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-gray"></i>
                            <span class="status">Activity Logs</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php echo (request()->route()->getAction()['as'] == 'admin.activities.emails' ? ' active' : '') ?>" href="<?php echo route('admin.activities.emails') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-gray"></i>
                            <span class="status">Email Logs</span>
                        </span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('admin.activities.pages') ?>">
                        <span class="badge badge-dot mr-4">
                            <i class="bg-gray"></i>
                            <span class="status">Page Logs</span>
                        </span>
                    </a>
                </li> -->
            </ul>

        </li>
        <?php $active = strpos(request()->route()->getAction()['as'], 'admin.settings') > -1; ?>
        <li class="nav-item">
            <a class="nav-link<?php echo ($active ? ' active' : '') ?>" href="<?php echo route('admin.settings') ?>">
                <i class="ni ni-settings"></i>
                <span class="nav-link-text">Settings</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>
