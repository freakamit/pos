<div id="content">
    <?php
    if ($this->session->flashdata('alert')):
        echo $this->session->flashdata('alert');
    endif;
    ?>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading sm">
                    <h2><?php echo $label; ?></h2>
                    <label class="color"><em><strong><?php echo $sub_label; ?></strong></em></label>

                    <?php
                    if (isset($buttons)):
                        ?>
                        <div class="header-button">
                            <?php
                            foreach ($buttons as $button):
                                $btn_extra = '';
                                if (isset($button['btn_extra'])):
                                    foreach ($button['btn_extra'] as $k => $v):
                                        $btn_extra .= $k . '="' . $v . '" ';
                                    endforeach;
                                endif;
                                echo '<a href="' . $button['url'] . '" class="btn btn-' . $button['class'] . '" style="margin-right:15px" ' . $btn_extra . '><i class="fa fa-' . $button['icon'] . '"></i> ' . $button['title'] . '</a>';
                            endforeach;
                            ?>
                        </div>
                        <?php
                    endif;
                    ?>


                    <?php
                    if (isset($extra)):
                        echo '<div class="table-responsive">';
                        echo '<table class="table no-border" border="0" cellpadding="0" cellspacing="0" border="0">';
                        foreach ($extra as $d):
                            echo '<tr>';
                            foreach ($d as $k => $v):
                                echo '<td><span>' . $k . ' : </span>' . $v . '</td>';
                            endforeach;
                            echo '</tr>';
                        endforeach;
                        echo '</table>';

                        echo '</div>';
                    endif;
                    ?>
                </header>


                <div class="panel-body">
                    <div class="table-responsive">
                        <?php
                        if (isset($bulk_action)):
                            echo form_open($bulk_action, '');
                        endif;
                        ?>
                        <table cellpadding="0" cellspacing="0" border="0"
                               class="table table-bordered table-striped table-hover"
                               id="data-table">
                            <thead>
                            <tr>
                                <?php
                                if (isset($bulk_action)):
                                    echo '<th class="no-sort"><input type="checkbox" class="check_all"></th>';
                                endif;
                                foreach ($fields as $field):
                                    echo '<th>' . $field . '</th>';
                                endforeach;
                                ?>
                            </tr>
                            </thead>
                            <tbody align="center">
                            <?php
                            $html = '';
                            $i = 1;
                            foreach ($list as $l):
                                $html .= '<tr>';
                                if (isset($bulk_action)):
                                    $html .= '<td><input type="checkbox" name="bulk[]" class="checkboxopt" value="' . $l->id . '"></td>';
                                else:
                                    $html .= '<td>' . $i++ . '</td>';
                                endif;
                                foreach ($l as $k => $v):
                                    if ($k == 'id'):
                                    else:
                                        $html .= '<td>' . $v . '</td>';
                                    endif;
                                endforeach;

                                if (isset($action)):
                                    $html .= '<td><span class="tooltip-area">';
                                    foreach ($action as $a):
                                        if ($a['name'] == 'Delete'):
                                            $html .= '<a href="javascript:void(0)" data-url="' . $a['url'] . '/' . $l->id . '" class="btn btn-default btn-sm delete-btn" title="" data-original-title="' . $a['name'] . '"><i class="fa fa-' . $a['icon'] . '"></i></a>';
                                        else:
                                            $html .= '<a href="' . $a['url'] . '/' . $l->id . '" class="btn btn-default btn-sm" title="" data-original-title="' . $a['name'] . '"><i class="fa fa-' . $a['icon'] . '"></i></a>';
                                        endif;
                                    endforeach;
                                    $html .= '</span></td>';
                                endif;
                                $html .= '</tr>';
                            endforeach;
                            echo $html;
                            ?>
                            </tbody>
                        </table>
                        <?php
                        if (isset($bulk_action)):
                            $html = '';
                            $html .= '<div class="list-bulk-option">';
                            $html .= '<div class="list-bulk">';
                            $html .= '<select class="selectpicker" name="bulk_action">';
                            $html .= '<option>Choose..</option>';
                            $html .= '<option value="1">Active</option>';
                            $html .= '<option value="0">In-Active</option>';
                            $html .= '</select>';
                            $html .= '</div>';
                            $html .= '<div class="list-bulk-btn">';
                            $html .= '<button type="submit" class="btn btn-success">GO</button>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= form_close();
                            echo $html;
                        endif;
                        ?>
                    </div>
                </div>

            </section>
        </div>
    </div>
</div>
</div>
<?php
//dumparray($extra);
if (isset($latitude) && isset($longitude)):
    ?>
    <script>
        function myMap() {
            var latitude = '<?php echo $latitude; ?>';
            var longitude = '<?php echo $longitude; ?>';
            var map_canvas = document.getElementById('map_canvas');
            var map_options = {
                center: new google.maps.LatLng(latitude, longitude),
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(map_canvas, map_options)
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFWmzZqMgutIfyU_pElZsrw8PPRo-Tlko&callback=myMap"
            async defer></script>

    <style>
        #map_canvas {
            width: 600px;
            height: 400px;
        }
    </style>
<?php endif; ?>