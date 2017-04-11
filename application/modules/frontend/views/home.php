<!-- Header -->
<header id="header">
    <div class="owl-carousel owl-theme">
        <?php
        $html = '';
        foreach ($slider as $s) {
            $html .= '<div class="item">';
            $html .= '<div class="intro" style="background-image: url(' . show_image($s->image, '', 'background') . ');">';
            $html .= '<div class="overlay">';
            $html .= '<div class="container">';
            $html .= '<div class="row">';
            $html .= '<div class="intro-text">';
            $html .= '<h1>' . $s->primary_tag . '</h1>';
            $html .= '<p>' . $s->secondary_tag . '</p>';
            $html .= '<a href="#' . $s->url . '" class="btn btn-custom btn-lg page-scroll">Discover Story</a> </div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        echo $html;
        ?>
    </div>
</header>
<!-- About Section -->
<div id="about">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="about-img"><img src="<?= show_image($about_us->image_id, '', 'background') ?>"
                                            class="img-responsive" alt=""></div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="about-text">
                    <?= $about_us->content; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restaurant Menu Section -->
<div id="restaurant-menu">
    <div class="section-title text-center center">
        <div class="overlay">
            <h2>Menu</h2>
            <hr>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php
            $html = '';
            foreach ($item as $c) {
                $html .= '<div class="col-xs-12 col-sm-6">';
                $html .= '<div class="menu-section">';
                $html .= '<h2 class="menu-section-title">' . $c->category_name . '</h2>';
                $html .= '<hr>';
                foreach ($c->items as $i) {
                    $html .= '<div class="menu-item">';
                    $html .= '<div class="menu-item-name">' . $i->name . '</div>';
                    $html .= '<div class="menu-item-price">' . show_price(format_price($i->price)) . '</div>';
                    $html .= '<div class="menu-item-description">' . $i->description . '</div>';
                    $html .= '</div>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            echo $html;
            ?>
        </div>
    </div>
</div>

<!-- Portfolio Section -->
<div id="portfolio">
    <div class="section-title text-center center">
        <div class="overlay">
            <h2>Gallery</h2>
            <hr>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="categories">
                <ul class="cat">
                    <li>
                        <ol class="type">
                            <li><a href="#" data-filter="*" class="active">All</a></li>
                            <?php
                            foreach ($item as $i) {
                                echo '<li><a href="#" data-filter=".' . $i->category_slug . '">' . $i->category_name . '</a></li>';
                            }
                            ?>
                        </ol>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="portfolio-items">
                <?php
                $array = array();
                $i = 0;
                foreach ($item as $i) {
                    foreach ($i->items as $it) {
                        $it->category = $i->category_slug;
                        $array[] = $it;
                        $i++;
                    }
                }

                shuffle($array);
                $html = '';
                foreach ($array as $a) {
                    $html .= '<div class="col-sm-6 col-md-3 col-lg-3 ' . $a->category . '">';
                    $html .= '<div class="portfolio-item">';
                    $html .= '<div class="hover-bg">';
                    $html .= '<a href="' . show_image($a->image_id, '', 'background') . '" title="' . $a->name . '"
                                                 data-lightbox-gallery="gallery1">';
                    $html .= '<div class="hover-text">';
                    $html .= '<h4>' . $a->name . '</h4>';
                    $html .= '</div>';
                    $html .= '<img src="' . show_image($a->image_id, '', 'background') . '" class="img-responsive" alt="' . $a->name . '">';
                    $html .= '</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
                echo $html;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div id="team" class="text-center">
    <div class="overlay">
        <div class="container">
            <div class="col-md-10 col-md-offset-1 section-title">
                <h2>Testimonials</h2>
                <hr>
            </div>
            <div class="clearfix"></div>
            <div id="row">
                <div class="owl-carousel owl-theme">
                    <?php
                    $html = '';
                    foreach ($testimonials as $t) {
                        $html .= '<div class="item">';
                        $html .= '<div class="testimonials-wrap">';
                        $html .= '<div class="testimonial-image">' . show_image($t->image, '', '$return') . '</div>';
                        $html .= '<div class="testimonial-content">' . $t->message . '</div>';
                        $html .= '<div class="testimonial-author">' . $t->name . '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    echo $html;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call Reservation Section -->
<div id="call-reservation" class="text-center">
    <div class="container">
        <h2>Want to make a reservation? Call <strong><?= settings('contact'); ?></strong></h2>
    </div>
</div>

<!-- Contact Section -->
<div id="contact" class="text-center">
    <div class="container">
        <div class="section-title text-center">
            <h2>Contact Form</h2>
            <hr>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <form name="sentMessage" id="contactForm" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" id="name" class="form-control" placeholder="Name" required="required">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="email" id="email" class="form-control" placeholder="Email" required="required">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="message" id="message" class="form-control" rows="4" placeholder="Message"
                              required></textarea>
                    <p class="help-block text-danger"></p>
                </div>
                <div id="success"></div>
                <button type="submit" class="btn btn-custom btn-lg">Send Message</button>
            </form>
        </div>
    </div>
</div>

<div id="footer">
    <div class="container text-center">
        <div class="col-md-4">
            <h3>Address</h3>
            <div class="contact-item">
                <?= settings('address'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Opening Hours</h3>
            <div class="contact-item">
                <?= settings('store_time'); ?>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Contact Info</h3>
            <div class="contact-item">
                <p>Phone: <?= settings('contact') ?></p>
                <p>Email: <?= settings('store_email') ?></p>
            </div>
        </div>
    </div>
    <div class="container-fluid text-center copyrights">
        <div class="col-md-8 col-md-offset-2">
            <div class="social">
                <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                </ul>
            </div>
            <p>&copy; All rights reserved.</p>
        </div>
    </div>
</div>