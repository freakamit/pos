<!-- Navigation
    ==========================================-->
<nav id="menu" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span> <span
                    class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
            <a class="navbar-brand page-scroll" href="#page-top"><?= settings('site_name') ?></a></div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php
                foreach (get_navigation('header') as $n) {
                    $link = '';
                    if ($n->link_type == 'uri') {
                        $link = $n->uri;
                    } elseif ($n->link_type == 'url') {
                        $link = base_url('frontend') . '/' . $n->url;
                    } elseif ($n->link_type == 'page') {
                        $link = get_page($n->page_id, 'slug');
                    }
                    echo '<li><a href="' . $link . '" class="page-scroll">' . $n->title . '</a></li>';
                }
                ?>
                <!--                <li><a href="#about" class="page-scroll">About</a></li>-->
                <!--                <li><a href="#restaurant-menu" class="page-scroll">Menu</a></li>-->
                <!--                <li><a href="#portfolio" class="page-scroll">Gallery</a></li>-->
                <!--                <li><a href="#team" class="page-scroll">Chefs</a></li>-->
                <!--                <li><a href="#call-reservation" class="page-scroll">Contact</a></li>-->
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
</nav>