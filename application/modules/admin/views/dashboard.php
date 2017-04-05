<div id="content">

    <div class="row">
        <div class="col-md-4">
            <div class="well bg-info">
                <div class="widget-tile">
                    <section>
                        <h5><strong>REGISTERED</strong> CUSTOMERS </h5>
                        <h2><?= $customers ?></h2>
                        <div class="progress progress-xs progress-white progress-over-tile">
                            <div class="progress-bar  progress-bar-white" aria-valuetransitiongoal="8590"
                                 aria-valuemax="10000"></div>
                        </div>
                        <label class="progress-label label-white"> <a href="<?= base_url('admin/customers') ?>">VIEW
                                NOW</a> </label>
                    </section>
                    <div class="hold-icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well bg-warning">
                <div class="widget-tile">
                    <section>
                        <h5><strong>NUMBER OF</strong> ITEMS </h5>
                        <h2><?= $items ?></h2>
                        <div class="progress progress-xs progress-white progress-over-tile">
                            <div class="progress-bar  progress-bar-white" aria-valuetransitiongoal="478"
                                 aria-valuemax="1000"></div>
                        </div>
                        <label class="progress-label label-white"><a href="<?= base_url('admin/products'); ?>">VIEW
                                NOW</a></label>
                    </section>
                    <div class="hold-icon"><i class="fa fa-cutlery"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well bg-theme">
                <div class="widget-tile">
                    <section>
                        <h5><strong>TODAY'S</strong> SALES </h5>
                        <h2><?= show_price(format_price($sales)); ?></h2>
                        <div class="progress progress-xs progress-white progress-over-tile">
                            <div class="progress-bar  progress-bar-white" aria-valuetransitiongoal="97584"
                                 aria-valuemax="300000"></div>
                        </div>
                        <label
                            class="progress-label label-white">Today: <?= user_format_date(strtotime('now')); ?></label>
                    </section>
                    <div class="hold-icon"><i class="fa fa-inr"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- //content > row-->
    <div class="row">

        <div class="col-lg-8">
            <section class="panel corner-flip">
                <div class="widget-chart bg-lightseagreen bg-gradient-green">
                    <h2>Current Week Sales</h2>
                    <table class="flot-chart" data-type="lines" data-tick-color="rgba(255,255,255,0.2)"
                           data-width="100%" data-height="220px">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="color : #FFF;">Test</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($sales_chart as $s):
                            echo '<tr>';
                            echo '<th>'.$s['date'].'</th>';
                            echo '<th>'.$s['total'].'</th>';
                            echo '</tr>';
                        endforeach;
                        ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-lg-4">
            <section class="panel">
                <div class="widget-clock">
                    <div id="clock"></div>
                </div>
            </section>
        </div>

    </div>

</div>