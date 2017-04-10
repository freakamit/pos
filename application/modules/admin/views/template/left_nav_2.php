<nav id="menu">
    <ul>
        <li><a href="<?= base_url('admin/dashboard'); ?>"><i class="icon fa fa-dashboard"></i> Dashboard </a></li>
        <li><span><i class="icon fa fa-gears"></i> Settings</span>
            <ul>
                <li><a href="<?= base_url('admin/settings?module=general'); ?>"><i class="icon fa fa-gear"></i> General Settings </a></li>
                <li><a href="<?= base_url('admin/settings?module=charges'); ?>"><i class="icon fa fa-gear"></i> Charges Settings </a></li>
            </ul>
        </li>
        <li><span><i class="icon fa fa-users"></i> User Management</span>
            <ul>
                <li class="Label label-lg">Admin User</li>
                <li><a href="<?= base_url('admin/groups'); ?>"><i class="icon fa fa-user"></i> Admin Users Group</a></li>
                <li><a href="<?= base_url('admin/users'); ?>"><i class="icon fa fa-user"></i> Admin Users</a></li>

                <li class="Label label-lg">Customers</li>
                <li><a href="<?= base_url('admin/customers'); ?>"><i class="icon fa fa-user"></i> Customer User</a></li>
            </ul>
        </li>
        <li><a href="<?= base_url('admin/category');?>"><i class="icon fa fa-list"></i> Category</a></li>
        <li><a href="<?= base_url('admin/product');?>"><i class="icon fa fa-list"></i> Items</a></li>
        <li><a href="<?= base_url('admin/order/create');?>"><i class="icon fa fa-list"></i> Order Management</a></li>
        <li><a href="<?= base_url('admin/order');?>"><i class="icon fa fa-list"></i> Order History</a></li>
        <li><a href="<?= base_url('admin/reports');?>"><i class="icon fa fa-list"></i> Reports</a></li>

        <li><span><i class="icon fa fa-list"></i> Content Management</span>
            <ul>
                <li class="Label label-lg">Navigation</li>
                <li><a href="<?= base_url('admin/navigation_groups')?>"><i class="icon fa fa-list"></i> Navigation Groups</a></li>
                <li><a href="<?= base_url('admin/navigation');?>"><i class="icon fa fa-list"></i> Navigation Links</a></li>

                <li class="Label label-lg">Banner</li>
                <li><a href="<?= base_url('admin/banner');?>"><i class="icon fa fa-image"></i> Banner </a></li>

                <li class="Label label-lg">Page</li>
                <li><a href="<?= base_url('admin/pages');?>"><i class="icon fa fa-list"></i> Page</a></li>

                <li class="Label label-lg">FAQ</li>
                <li><a href=""><i class="icon fa fa-list"></i> FAQ</a></li>

                <li class="Label label-lg">Advertisement</li>
                <li><a href=""><i class="icon fa fa-list"></i> Advertisement</a></li>

                <li class="Label label-lg">Testimonials</li>
                <li><a href=""><i class="icon fa fa-list"></i> Testimonials</a></li>
            </ul>
        </li>
    </ul>
</nav>