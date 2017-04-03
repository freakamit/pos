<div id="header">
    <div class="logo-area clearfix">
        <a href="#" class="logo"></a>
    </div>
    <!-- //logo-area-->
    <div class="tools-bar">
        <ul class="nav navbar-nav nav-main-xs">
            <li><a href="#" class="icon-toolsbar nav-mini"><i class="fa fa-bars"></i></a></li>
        </ul>
        <ul class="nav navbar-nav nav-top-xs hidden-xs tooltip-area">
            <li class="h-seperate"></li>
            <li><a href="<?= base_url(); ?>" data-toggle="tooltip" title="View front end" data-container="body"  data-placement="bottom"><i class="fa fa-laptop"></i></a></li>
            <li class="h-seperate"></li>
            <li><a href="#"> <?php echo settings('site_name'); ?> </a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right tooltip-area">
            <li><a href="#" class="nav-collapse avatar-header" data-toggle="tooltip" title="Show / hide  menu" data-container="body" data-placement="bottom">
                    <?= show_image($this->session->userdata['userdata']['user_image'], 'circle'); ?>
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">
                    <em><strong>Hi</strong>, <?= $this->session->userdata['userdata']['name']; ?> </em> <i class="dropdown-icon fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu pull-right icon-right arrow">
                    <li><a href="<?= base_url('admin/users/logout') ?>"><i class="fa fa-sign-out"></i> Signout </a></li>
                </ul>
            </li>
            <li class="visible-lg">
                <a href="#" class="h-seperate fullscreen" data-toggle="tooltip" title="Full Screen" data-container="body"  data-placement="left">
                    <i class="fa fa-expand"></i>
                </a>
            </li>
        </ul>
    </div>
    <!-- //tools-bar-->
</div>