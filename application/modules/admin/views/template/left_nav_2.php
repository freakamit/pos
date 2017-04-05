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
        <li><a href=""><i class="icon fa fa-list"></i> Reports</a></li>
    </ul>
</nav>