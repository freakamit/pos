<div id="content">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading sm">
                    <h3><?php echo $title; ?></h3>
                </header>
                <div class="panel-body">
                    <?php
                    if ($this->session->flashdata('alert')):
                        echo $this->session->flashdata('alert');
                    else:
                        redirect('admin/dashboard');
                    endif;
                    ?>
                </div>
            </section>
        </div>
    </div>
</div>
</div>