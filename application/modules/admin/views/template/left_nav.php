<div id="nav">
    <div id="nav-scroll">
        <div class="avatar-slide">

            <span class="easy-chart avatar-chart" data-color="theme-inverse" data-percent="100" data-track-color="rgba(255,255,255,0.1)" data-line-width="5" data-size="118">
                <span class="percent"></span>
                <?= show_image($this->session->userdata['userdata']['user_image'], 'circle'); ?>
            </span>

            <div class="avatar-detail">
                <p><strong>Hi</strong>, <?= $this->session->userdata['userdata']['name']; ?></p>
                <p><a href="#"><?= $this->session->userdata['userdata']['address']; ?></a></p>
                <span>10</span>
            </div>

            <div class="avatar-link btn-group btn-group-justified">
                <a class="btn"  data-toggle="modal" href="#md-notification" title="Notification">
                    <i class="fa fa-bell-o"></i>Notifications<em class="green"></em>
                </a>
            </div>

        </div>

    </div>
</div>