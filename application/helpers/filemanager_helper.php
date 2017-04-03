<?php

/*
  | -------------------------------------------------------------------
  | File Manager Helper
  | -------------------------------------------------------------------
  | This file includes every necessary function that can minimize the code
  | and make the programming faster for managing the stuff realeted with files.
 */

//function to upload single file
function upload($path, $extra = '') {
    $ci = & get_instance();

    $config['image_library'] = 'GD2';
    $config['upload_path'] = $path;
    $config['allowed_types'] = 'svg|jpg|jpeg|gif|png';
    $config['max_size'] = '0';
    $config['max_width'] = '0';
    $config['max_height'] = '0';
    $config['maintain_ratio'] = TRUE;
    $config['file_name'] = generate_rand(64);

    $ci->load->library('upload', $config);

    $ci->upload->initialize($config);

    if ($extra == ''):
        $extra = 'userfile';
    endif;

    if ($ci->upload->do_upload($extra)):
        return insert_image_info($ci->upload->data(), $path);
    else:
        return array('error' => $ci->upload->display_errors());
    endif;
}

function upload_files($path, $filename, $extra = '') {
    $ci = & get_instance();

    $config['upload_path'] = $path;
    $config['allowed_types'] = 'txt|text|pdf|doc|docx|DOC|DOCX|PDF';
    $config['max_size'] = '0';
    $config['file_name'] = $filename;
    //dumparray($config);

    $ci->load->library('upload', $config);

    $ci->upload->initialize($config);

    if ($extra == ''):
        $extra = 'userfile';
    endif;

    if ($ci->upload->do_upload($extra)):
        // dumparray($ci->upload->do_upload($extra));
        return insert_image_info($ci->upload->data(), $path);
    else:
        return array('error' => $ci->upload->display_errors());
    endif;
}

//function to upload multiple file
function multi_upload($path, $extra = '') {
    $return = array();
    if ($extra != ''):
        $files = $_FILES[$extra];
    else:
        $files = $_FILES['userfile'];
    endif;

    $ci = & get_instance();

    $config['upload_path'] = $path;
    $config['allowed_types'] = 'svg|jpg|jpeg|gif|png';
    $config['max_size'] = '0';
    $config['max_width'] = '0';
    $config['max_height'] = '0';


    foreach ($files['name'] as $key => $image):
        $config['file_name'] = generate_rand(64);

        $ci->load->library('upload', $config);
        $_FILES['multi_images[]']['name'] = $files['name'][$key];
        $_FILES['multi_images[]']['type'] = $files['type'][$key];
        $_FILES['multi_images[]']['tmp_name'] = $files['tmp_name'][$key];
        $_FILES['multi_images[]']['error'] = $files['error'][$key];
        $_FILES['multi_images[]']['size'] = $files['size'][$key];

        $ci->upload->initialize($config);

        if ($ci->upload->do_upload('multi_images[]')):
            $return['files'][] = insert_image_info($ci->upload->data(), $path);
        else:
            $return['error'] = array('error' => $ci->upload->display_errors());
        endif;

    endforeach;
    return $return;
}

function multi_file_uploads($path, $filename = array(), $extra = '') {
    $return = array();
    if ($extra != ''):
        $files = $_FILES[$extra];
    else:
        $files = $_FILES['userfile'];
    endif;

    $ci = & get_instance();

    $config['upload_path'] = $path;
    $config['allowed_types'] = 'txt|pdf|doc|docx';
    $config['max_size'] = '0';

    $i = 0;
    foreach ($files['name'] as $key => $image):
        $config['file_name'] = $filename[$i];

        $ci->load->library('upload', $config);
        $_FILES['multi_images[]']['name'] = $files['name'][$key];
        $_FILES['multi_images[]']['type'] = $files['type'][$key];
        $_FILES['multi_images[]']['tmp_name'] = $files['tmp_name'][$key];
        $_FILES['multi_images[]']['error'] = $files['error'][$key];
        $_FILES['multi_images[]']['size'] = $files['size'][$key];

        $ci->upload->initialize($config);

        if ($ci->upload->do_upload('multi_images[]')):
            $return['files'][] = insert_image_info($ci->upload->data(), $path);
        else:
            $return['error'] = array('error' => $ci->upload->display_errors());
        endif;

        $i++;
    endforeach;
    return $return;
}

//function to insert file data in database
function insert_image_info($files, $path) {
    $ci = & get_instance();
    $name = explode('.', $files['file_name']);
    if ($ci->uri->segment(1) == 'admin'):
//        $user_id = $ci->session->userdata['userdata']['user_id'];
        $user_id = 1;
    else:
//        $user_id = $ci->session->userdata['client_session']['user_id'];
        $user_id = 1;
    endif;

    $image_array = array(
        'folder_id' => get_parent_folder_id($path),
        'user_id' => $user_id,
        'type' => $files['file_type'],
        'name' => $name[0],
        'filename' => $files['file_name'],
        'path' => $path,
        'description' => '',
        'caption' => '',
        'extension' => $files['file_ext'],
        'width' => $files['image_width'],
        'height' => $files['image_height'],
        'filesize' => $files['file_size'],
        'alt_attribute' => '',
        'date_added' => strtotime("now"),
        'sort' => 0
    );

    $ci->db->insert('files', $image_array);
    $id = $ci->db->insert_id();

    $exp = explode('/', $files['file_type']);
    if ($exp[0] == 'image'):
        if ($path == 'uploads/post'):
            create_thumbnail($files, $path);
        endif;
//        watermark(FCPATH . $path . '/' . $files['file_name']);
    endif;

    return $id;
}

//function to insert file data in database using csv
function insert_image_info_csv($files, $path) {
    $ci = & get_instance();
    $name = explode('.', $files);

    $image_array = array(
        'folder_id' => get_parent_folder_id($path),
        'user_id' => $ci->session->userdata['userdata']['user_id'],
        'type' => 'image/jpeg',
        'name' => $name[0],
        'filename' => $files,
        'path' => $path,
        'description' => '',
        'caption' => '',
        'extension' => '.' . $name[1],
        'width' => '',
        'height' => '',
        'filesize' => '',
        'alt_attribute' => '',
        'date_added' => strtotime("now"),
        'sort' => 0
    );

    $ci->db->insert('files', $image_array);
//    watermark(FCPATH . $path . '/' . $files['file_name']);
    return $ci->db->insert_id();
}

//function to get parent folder id
function get_parent_folder_id($path) {
    $ci = & get_instance();
    $parent_id = 0;

    $sql = $ci->db->get_where('files_folders', array('location' => $path));

    if ($sql->row()):
        $parent_id = $sql->row()->id;
    endif;

    return $parent_id;
}

//function to display image
function show_image($id, $class = NULL, $return_type = '') {
    $ci = & get_instance();
    $return = '<img src="' . base_url('assets/img/noimage.jpg') . '" class="' . $class . '"/>';

    $images = $ci->db->get_where('files', array('id' => $id));

    $image_path = '';
    if ($images->num_rows() > 0):
        $img = $images->row();

        $image_path = base_url() . $img->path . '/' . $img->filename;

        $return = '';
        if ($class != NULL):
            $return .= '<div class="' . $class . '_wrap' . '">';
        endif;
        $return .= '<img  class="' . $class . '" src="' . $image_path . '" alt="' . $img->name . '" caption="' . $img->caption . '" description="' . $img->description . '" />';
        if ($class != NULL):
            $return .= '</div>';
        endif;
    endif;

    if ($return_type == 'echo'):
        echo $return;
    elseif ($return_type == 'background'):
        return $image_path;
    else:
        return $return;
    endif;
}

function get_file($id, $class = NULL, $return_type = 'echo') {
    $ci = & get_instance();

    $files = $ci->db->get_where('files', array('id' => $id));

    $return = '';
    if ($files->num_rows() > 0) {
        $files = $files->row();
        $return = '<div class="' . $class . '_wrap"><a href="' . base_url() . $files->path . '/' . $files->filename . '" target="_blank"><button type="button" class="btn btn-primary"><i class="fa fa-eye"></i> Download/View</button></a></div>';
    }

    if ($return_type == 'echo'):
        echo $return;
    else:
        return $return;
    endif;
}

function remove_image($id) {

    $ci = & get_instance();

    if ($id != '0' && $id != ''):
        $sql = $ci->db->get_where('files', array('id' => $id))->row();
        $filename = $sql->path . '/' . $sql->filename;
        $thumb = $sql->path . '/thumbs/' . $sql->filename;
        if (file_exists($filename)) {
            unlink($filename);
        }
        if (file_exists($thumb)) {
            unlink($thumb);
        }

        $con = array('key' => 'id', 'value' => $id);
        delete('files', $con);
    endif;

    return;
}

//function watermark($path = NULL) {
//    $ci = & get_instance();
//    if ($path) {
//        $config['image_library'] = 'gd2';
//        $config['source_image'] = $path;
//        $config['wm_type'] = 'overlay';
//        $config['wm_overlay_path'] = FCPATH . 'uploads/logo.png';
//        //the overlay image
//        $config['wm_opacity'] = 50;
//        $config['wm_vrt_alignment'] = 'middle';
//        $config['wm_hor_alignment'] = 'right';
//        $ci->load->library('image_lib');
//        $ci->image_lib->initialize($config);
//        $ci->image_lib->watermark();
//    }
//}

function generate_file_name($career, $applicant) {
    $html = get_slug($career) . '_' . get_slug($applicant) . '_' . generate_rand(10);

    return $html;
}

function show_thumb_image($id, $class = NULL, $return_type = 'echo') {
    $ci = & get_instance();
    $return = '<img src="' . base_url('assets/img/noimage.jpg') . '" class="' . $class . '"/>';


    $images = $ci->db->get_where('files', array('id' => $id));

    if ($images->num_rows() > 0):
        $img = $images->row();
        $return = '<div class="' . $class . '_wrap' . '"><img  class="' . $class . '" src="' . base_url() . $img->path . '/thumbs/' . $img->filename . '" title="' . $img->name . '" caption="' . $img->caption . '" description="' . $img->description . '" alt="' . $img->alt_attribute . '"/></div>';
    endif;

    if ($return_type == 'echo'):
        echo $return;
    else:
        return $return;
    endif;
}

function create_thumbnail($files, $path) {
    $ci = & get_instance();

    $thumb_upload_dir = $path . '/' . 'thumbs/';
    if (!is_dir($thumb_upload_dir)) {
        mkdir($thumb_upload_dir);
    }

    $dim = $ci->db->get_where('files_folders', array('location' => $path))->row();

    $width = 250;
    $height = 250;

    if ($dim):
        if ($dim->thumb_width != 0 & $dim->thumb_height != 0):
            $width = $dim->thumb_width;
            $height = $dim->thumb_height;
        endif;
    endif;

    $ci->load->library('image_lib');
    $config1['image_library'] = 'GD2';
    $config1['source_image'] = $files['full_path'];
    $config1['create_thumb'] = FALSE;
    $config1['maintain_ratio'] = FALSE;
    $config1['width'] = $width;
    $config1['height'] = $height;
    $config1['new_image'] = $thumb_upload_dir . $files['file_name'];
    $ci->image_lib->initialize($config1);
    //$ci->image_lib->resize();
    $ci->image_lib->resize();
    $ci->image_lib->clear();

    //die();
}
