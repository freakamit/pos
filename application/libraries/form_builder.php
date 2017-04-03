<?php

class form_builder {

    function build($data) {
        $html = $this->get_header($data);

        foreach ($data['form_components'] as $d):
            $html .= $this->generate($d);
        endforeach;
        $html .= $this->get_footer();
        return $html;
    }

    function get_header($array) {
        $html = '<div class="col-lg-' . $array['form_size'] . '">';
        $html .= '<section class="panel corner-flip"><header class="panel-heading sm" data-color="theme-inverse">';
        if ($array['label'] !== ''):
            $html .= '<h2>' . ucfirst($array['label']) . '</h2>';
            if ($array['sub_label'] !== ''):
                $html.='<label class="color"><strong><em>' . $array['sub_label'] . '</em></strong></label>';
            endif;
            $html.='</header>';
        endif;
        $html .= '<div class="panel-body">';
        return $html;
    }

    function get_footer() {
        $html = '</section></div>';
        return $html;
    }

    function generate($array) {
        $type = 'form_' . $array['type'];
        if ($array['type'] == 'submit' || $array['type'] == 'reset' || $array['type'] == 'button' || $array['type'] == 'image_block'):
            if ($array['type'] == 'submit'):
                $type = 'form_button';
            endif;
            $html = $this->$type($array);
        else:
            $group_class = '';
            $class = '';
            if (isset($array['input_group'])):
                $input_group = $array['input_group'];
                $array = filter_array($array, array('input_group'));
                $class = 'input-group';
            endif;
            if (isset($array['group_class'])):
                $group_class = $array['group_class'];
            endif;

            if ($array['type'] == 'group'):
                $class = $array['class'];
            endif;

            $html = '<div class="form-group ' . $group_class . '"><label class="control-label">' . ucfirst($array['label']) . '</label><div class="' . $class . '" style="position:relative">';

            if (isset($array['text_option'])):
                foreach ($array['text_option'] as $to):
                    $opt_type = 'form_' . $to['type'];
                    $html .= $this->$opt_type($to);
                    $array = filter_array($array, array('text_option'));
                endforeach;
            endif;
            $html .= $this->$type($array);
            if (isset($array['description'])):
                $html .= '<span class="help-block">' . $array['description'] . '</span>';
            endif;
            if (isset($input_group)):
                $html.='<span class="input-group-btn">';
                foreach ($input_group as $v):
                    $group_type = 'form_' . $v['type'];
                    $html.=$this->$group_type($v);
                endforeach;
                $html.='</span>';
            endif;
            $html .= '</div></div>';
        endif;
        return $html;
    }

    function form_div($array) {
        $html = '';
        $html .= '<div class="' . $array['class'] . '">';
        if (isset($array['selected'])):
            foreach ($array['selected'] as $v):
                $html .= '<p data-course="' . $v->course_id . '">';
                $html .= '<input type="hidden" name="subject[]" value="' . $v->subject_id . '">';
                $html .= '<input type="hidden" name="course[]" value="' . $v->subject_id . '">';
                $html .= $v->subject_name;
                $html .= '<a href="javascript:void(0)" class="unassign">Remove</a>';
                $html .= '</p>';
            endforeach;
        elseif (isset($array['child'])):
            foreach ($array['child'] as $v):
                $html .= $this->generate($v);
            endforeach;
        else:
            $html .= '</div>';
        endif;

        return $html;
    }

    function form_group($array) {
        $html = '';
        foreach ($array['group'] as $v):
            $type = 'form_' . $v['type'];
            $html.=$this->$type($v);
        endforeach;

        return $html;
    }

    function form_html_dropdown($array) {
        $html = '<select name="' . $array['name'] . '" ' . $array['extra'] . '>';
        foreach ($array['option'] as $key => $value):
            $html .= '<optgroup label="' . $key . '">';
            foreach ($value as $k => $v):
                if ($k == $array['selected']):
                    $selected = 'selected';
                else:
                    $selected = '';
                endif;
                $html .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
            endforeach;
            $html .= '</optgroup>';
        endforeach;
        $html .= '</select>';

        return $html;
    }

    function form_text($array) {
        return form_input($array);
    }

    function form_password($array) {
        return form_password($array);
    }

    function form_hidden($array) {
        return form_hidden($array);
    }

    function form_upload_old($array) {
        $html = '<div class="fileinput fileinput-new" data-provides="fileinput">'
                . '<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">'
                . '<img data-src="assets/plugins/holder/holder.js/100%x100%/text:Preview" alt="...">'
                . '</div>'
                . '<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>'
                . '<div>'
                . '<span class="btn btn-default btn-file">'
                . '<span class="fileinput-new">Select image</span>'
                . '<span class="fileinput-exists">Change</span>'
                . form_upload($array)
                . '</span>'
                . '<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">'
                . '<i class="fa fa-trash-o"></i> Remove'
                . '</a>'
                . '</div>';
        return $html;
    }

    function form_upload($array) {
        $array = filter_array($array, array('class'));
        $html = '<div class="fileinput fileinput-new" data-provides="fileinput">'
                . '<div class="input-group">'
                . '<div class="form-control uneditable-input" data-trigger="fileinput">'
                . '<i class="glyphicon glyphicon-file fileinput-exists"></i>'
                . '<span class="fileinput-filename"></span>'
                . '</div>'
                . '<span class="input-group-addon btn btn-inverse btn-file">'
                . '<span class="fileinput-new">SELECT FILE TO UPLOAD</span>'
                . '<span class="fileinput-exists">Change</span>'
                . form_upload($array)
                . '</span>'
                . '<a href="#" class="input-group-addon  btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>'
                . '</div>'
                . '</div>';
        return $html;
    }

    function form_textarea($array) {
        return form_textarea($array);
    }

    function form_dropdown($array) {
        return form_dropdown($array['name'], $array['option'], $array['selected'], $array['extra']);
    }

    function form_multiselect($array) {
        return form_multiselect($array['name'], $array['option'], $array['selected'], $array['extra']);
    }

    function form_checkbox($array) {
        $html = '<div class="ios-switch theme-inverse pull-left"><div class="switch">';
        $html.=form_checkbox($array);
        $html.='</div></div><div class="clearfix"></div>';
        return $html;
    }

    function form_radio($array) {
        $html = '';
        $html .= '<ul class = "iCheck" data-style = "square" data-color = "green">';
        $html .= '<li><label>' . form_radio($array) . $array['sub_label'] . '</label></li>';
        $html .= '</ul>';

        return $html;
    }

    function form_datetime($array) {
        unset($array['type']);
        $format = 'dd MM yyyy';
        $class = 'form_date';
        if (isset($array['time'])):
            $format = 'dd MM yyyy - HH:ii p';
            $class = 'form_datetime';
        endif;
        $html = '<div class = "input-group date ' . $class . '" data-picker-position = "bottom-left" data-date-format = "' . $format . '">';
        $html .= form_input($array);
        $html .= '<span class = "input-group-btn"><button class = "btn btn-default" type = "button"><i class = "fa fa-times"></i></button><button class = "btn btn-default" type = "button"><i class = "fa fa-calendar"></i></button></span>';
        $html .= '</div>';

        return $html;
    }

    function form_submit($array) {
        return form_submit($array);
    }

    function form_reset($array) {
        return form_reset($array);
    }

    function form_button($array) {
        return form_button($array);
    }

    function form_caption($array) {
        return '<div ' . $array['extra'] . '></div>';
    }

    function form_image_block($array) {
        if (!isset($array['input'])):
            $array['input'] = TRUE;
        else:
            $array['input'] = FALSE;
        endif;

        $html = '<div>';
        $checked = '';
        $html .= show_image($array['value'], $array['class'], 'return');
        if ($array['status'] == 1):
            $checked = 'checked';
        endif;
        if ($array['input'] == TRUE):
            if (isset($array['option'])):
                foreach ($array['option'] as $o):
                    $html .= '<div><a href="' . $o['url'] . '" class="' . $o['btn_type'] . '"><i class="' . $o['class'] . '"></i></a></div>';
                endforeach;
            endif;
        endif;
        $html .= '</div>';
        return $html;
    }

}
