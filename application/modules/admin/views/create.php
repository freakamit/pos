<div id="content">
<?php
if ($this->session->flashdata('alert')):
    echo $this->session->flashdata('alert');
endif;
?>
    <div class="row">
    <?php
$action = $form_action;
$attr   = 'id="form" data-parsley-validate';

echo form_open_multipart($action, $attr);
?>
	<?php
foreach ($form as $f):
    echo $f;
endforeach;
?>
    <?php
echo form_close();
?>
        </div>
    </div>
</div>