<?php if (isset($modal)): ?>
    <div id="md-effect" class="modal fade" tabindex="-1" data-width="450">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
            </button>
            <h4 class="modal-title"><?= $modal['title']; ?></h4>
        </div>
        <!-- //modal-header-->
        <div class="modal-body">
            <?php
            if (isset($modal['form'])):
                echo form_open_multipart($modal['form']['action'], $modal['form']['attributes']);
                ?>
                <input type="hidden" name="id" value="<?= $modal['form']['value']['id']; ?>">
                <input type="hidden" name="type" value="<?= $modal['form']['value']['type']; ?>">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="input-group">
                        <div class="form-control uneditable-input" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-inverse btn-file">
                            <span class="fileinput-new">SELECT FILE TO UPLOAD</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" multiple="" name="userfile[]">
                        </span>
                        <a href="#" class="input-group-addon  btn btn-default fileinput-exists"
                           data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
                <input type="submit" class="btn btn-success" value="Upload & Set Delivered">
                <?php
                echo form_close();
            endif;
            ?>
        </div>
        <!-- //modal-body-->
    </div>
<?php endif; ?>

<?php if (isset($geolocation)): ?>
<div id="geo-location" class="modal fade" tabindex="-1" data-width="450">
    <div class="modal-header bg-inverse bd-inverse-darken">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title"><?= $geolocation['title']; ?></h4>
    </div>
    <!-- //modal-header-->
    <div class="modal-body">
        <?php
        if (isset($geolocation['form'])):
            echo form_open_multipart($geolocation['form']['action'], $geolocation['form']['attributes']);
            ?>
            <p id="geo-message"></p>
            <div class="form-group">Latitude: <input type="text" value=" " class="geolocation_latitude form-control"
                                                     name="latitude"></div>
            <div class="form-group">Longitude: <input type="text" value=" " class="geolocation_longitude form-control"
                                                      name="longitude"></div>
            <input type="hidden" name="order_id" value="<?php echo $geolocation['form']['value']['order_id']; ?>">
            <input type="hidden" name="address_book_id"
                   value="<?php echo $geolocation['form']['value']['address_book_id']; ?>">
            <div class="form-group">
                <input type="submit" value="Save" name="submit" class="save-geolocation btn btn-success">
            </div>
            <?php
            echo form_close();
        endif;
        ?>
    </div>


    <script>

    </script>
    <?php
    endif;
    ?>

    <div id="md-delete" class="modal fade" tabindex="-1" data-width="450">
        <div class="modal-header bg-inverse bd-inverse-darken">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
            </button>
            <h4 class="modal-title">Delete Confirm?</h4>
        </div>
        <!-- //modal-header-->
        <div class="modal-body">
            <p>Are You Sure you want to delete?</p>
            <a href="" class="btn btn-danger delete_confirm">Confirm</a>
            <a href="javascript:void(0)" class="btn btn-info delete_cancel">Cancel</a>
        </div>
        <!-- //modal-body-->
    </div>

    <div id="md-confirm" class="modal md-slideUp fade" tabindex="-1" data-width="450">
        <div class="modal-header bg-danger bg-danger">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
            </button>
            <h4 class="modal-title"></h4>
        </div>
        <!-- //modal-header-->
        <div class="modal-body">
            <p class="alert-message-modal"></p>
            <a href="" class="btn btn-danger confirm_btn">Confirm</a>
            <a href="javascript:void(0)" class="btn btn-info confirm_cancel">Cancel</a>
        </div>
        <!-- //modal-body-->
    </div>

    <div id="md-alert" class="modal md-slideUp fade" tabindex="-1" data-width="450">
        <div class="modal-header bg-danger bg-danger">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i>
            </button>
            <h4 class="modal-title"></h4>
        </div>
        <!-- //modal-header-->
        <div class="modal-body">
            <p class="alert-message-modal"></p>
        </div>
        <!-- //modal-body-->
    </div>