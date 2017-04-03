<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta information -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <!-- Title-->
        <title><?php echo $title; ?></title>
        <!-- Favicons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('assets'); ?>/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('assets'); ?>/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('assets'); ?>/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets'); ?>/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="<?php echo base_url('assets'); ?>/ico/favicon.ico">
        <!-- CSS Stylesheet-->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets'); ?>/css/bootstrap/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets'); ?>/css/bootstrap/bootstrap-themes.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets'); ?>/css/style.css" />

        <!-- Styleswitch if  you don't chang theme , you can delete -->
        <link type="text/css" rel="alternate stylesheet" media="screen" title="style1" href="<?php echo base_url('assets'); ?>/css/styleTheme1.css" />
        <link type="text/css" rel="alternate stylesheet" media="screen" title="style2" href="<?php echo base_url('assets'); ?>/css/styleTheme2.css" />
        <link type="text/css" rel="alternate stylesheet" media="screen" title="style3" href="<?php echo base_url('assets'); ?>/css/styleTheme3.css" />
        <link type="text/css" rel="alternate stylesheet" media="screen" title="style4" href="<?php echo base_url('assets'); ?>/css/styleTheme4.css" />

    </head>
    <body class="full-lg">
        <div id="wrapper">

            <div id="loading-top">
                <div id="canvas_loading"></div>
                <span>Checking...</span>
            </div>

            <div id="main">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="account-wall">
                                <section class="align-lg-center">
                                    <div class="site-logo" style="background:url(<?= show_image(settings('userfile'),'','background')?>) no-repeat center center; background-size: 75px 75px;"></div>
                                    <h1 class="login-title"><span>wel</span>come <small> <?php echo $title; ?> Administration Panel</small></h1>
                                </section>
                                <?php echo form_open($action, $attributes); ?>
                                <section>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                        <?php echo form_input($username); ?>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-key"></i></div>
                                        <?php echo form_password($password); ?>
                                    </div>
                                    <?php echo form_submit($submit); ?>
                                </section>
                                <?php echo form_close(); ?>
                                <a href="#" class="footer-link">&copy; 2016 <?= settings('site_name')?> &trade; </a>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jquery Library -->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/jquery.ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/bootstrap/bootstrap.min.js"></script>
        <!-- Modernizr Library For HTML5 And CSS3 -->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/modernizr/modernizr.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/mmenu/jquery.mmenu.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/styleswitch.js"></script>
        <!-- Library 10+ Form plugins-->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/form/form.js"></script>
        <!-- Datetime plugins -->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/datetime/datetime.js"></script>
        <!-- Library Chart-->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/chart/chart.js"></script>
        <!-- Library  5+ plugins for bootstrap -->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/pluginsForBS/pluginsForBS.js"></script>
        <!-- Library 10+ miscellaneous plugins -->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/plugins/miscellaneous/miscellaneous.js"></script>
        <!-- Library Themes Customize-->
        <script type="text/javascript" src="<?php echo base_url('assets'); ?>/js/caplet.custom.js"></script>
        <script type="text/javascript">
            $(function () {
                //Login animation to center 
                function toCenter() {
                    var mainH = $("#main").outerHeight();
                    var accountH = $(".account-wall").outerHeight();
                    var marginT = (mainH - accountH) / 2;
                    if (marginT > 30) {
                        $(".account-wall").css("margin-top", marginT - 15);
                    } else {
                        $(".account-wall").css("margin-top", 30);
                    }
                }
                toCenter();
                var toResize;
                $(window).resize(function (e) {
                    clearTimeout(toResize);
                    toResize = setTimeout(toCenter(), 500);
                });

                //Canvas Loading
                var throbber = new Throbber({size: 32, padding: 17, strokewidth: 2.8, lines: 12, rotationspeed: 0, fps: 15});
                throbber.appendTo(document.getElementById('canvas_loading'));
                throbber.start();

                //Set note alert
                setTimeout(function () {
                    $.notific8('Hello Admin , Please enter your <strong>Username</strong> and <strong>Password</strong> to  access account.', {sticky: true, horizontalEdge: "top", theme: "inverse", heading: "ADMIN LOGIN"})
                }, 1000);


                $("#form-signin").submit(function (event) {
                    event.preventDefault();
                    var main = $("#main");
                    //scroll to top
                    main.animate({
                        scrollTop: 0
                    }, 500);
                    main.addClass("slideDown");

                    // send username and password to php check login
                    var request = $.ajax({
                        url: "admin/check_login",
                        data: $(this).serialize(),
                        type: "POST",
                        dataType: 'json',
                    });
                    request.done(function (msg) {
                        if (msg == 1) {
                            setTimeout(function () {
                                $("#loading-top span").text("Redirect to account page...")
                            }, 1500);
                            setTimeout(function () {
                                $.notific8('Please Wait.. Redirecting to <strong>Account Page</strong>', {sticky: true, horizontalEdge: "top", theme: "success", heading: "LOGIN SUCESS"})
                            }, 1000);
                            setTimeout("window.location.href='<?php echo base_url(); ?>admin/dashboard'", 3100);
                        } else {
                            $("#loading-top span").text("Invalid Username or Password");
                            setTimeout(function () {
                                $.notific8('Invalid <strong>Username</strong> or <strong>Password</strong>. Please Check try again', {sticky: true, horizontalEdge: "top", theme: "warning", heading: "INVALID LOGIN"})
                            }, 1000);

                        }
                    });
                });
            });
        </script>
    </body>
</html>
