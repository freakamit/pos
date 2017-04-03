<div id="main">
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/dasboard'); ?>">Home</a></li>
        <?php
        $url = $this->uri->segment('2');
        if ($url):
            echo '<li><a href="' . base_url("admin/" . $url) . '">' . ucwords(str_replace('_', ' ', $url)) . '</a></li>';
        endif;
        ?>
        <?php
        $url = $this->uri->segment('3');
        if ($url):
            echo '<li><a href="' . base_url("admin/" . $url) . '">' . ucwords(str_replace('_', ' ', $url)) . '</a></li>';
        endif;
        ?>
    </ol>
    <!-- //breadcrumb-->

