<?php

class Pages extends CI_Controller {

    var $_table = 'pages';

    public function __construct() {
        parent::__construct();

        check_user_session();

        $this->load->model('navigation_model', 'navigation');
        $this->load->model('page_model', 'page');
    }

    function form_array($values = array()) {
        $form_array['page_info'] = array(
            array(
                'name' => 'title',
                'type' => 'text',
                'value' => isset($values['title']) ? $values['title'] : false,
                'placeholder' => 'Page Title',
                'class' => 'form-control slug_title',
                'label' => 'Title',
                'parsley-required' => 'true'
            ),
            array(
                'name' => 'slug',
                'type' => 'text',
                'value' => isset($values['slug']) ? $values['slug'] : false,
                'class' => 'form-control slug',
                'label' => 'Slug',
            ),
            array(
                'name' => 'class',
                'type' => 'text',
                'value' => isset($values['class']) ? $values['class'] : false,
                'label' => 'Class',
                'class' => 'form-control'
            ),
        );
        $form_array['nav'] = array(
            array(
                'name' => 'navigation_group_id',
                'type' => 'dropdown',
                'option' => $this->navigation->get_group(),
                'selected' => isset($values['navigation_group_id']) ? $values['navigation_group_id'] : false,
                'label' => 'Navigation Group',
                'extra' => 'class="form-control selectpicker nav_group"'
            ),
            array(
                'name' => 'parent',
                'type' => 'dropdown',
                'option' => isset($values['navigation_group_id']) ? $this->navigation->get_child_list($values['navigation_group_id']) : array('' => 'Please Select Navigation Group'),
                'selected' => isset($values['parent']) ? $values['parent'] : false,
                'extra' => 'class="form-control selectpicker nav_parent"',
                'label' => 'Navigation Parent',
            ),
            array(
                'name' => 'target',
                'type' => 'dropdown',
                'option' => $this->navigation->target(),
                'selected' => isset($values['target']) ? $values['target'] : false,
                'label' => 'Target',
                'extra' => 'class="form-control selectpicker"'
            ),
        );
        $form_array['page_image'] = array(
            array(
                'name' => 'userfile',
                'type' => 'upload',
                'class' => 'form-control',
                'label' => 'Page Image',
            )
        );
        if (isset($values['image_id']) && $values['image_id'] != '0'):
            $form_array['page_image'][] = array(
                'name' => '',
                'label' => '',
                'type' => 'image_block',
                'value' => $values['image_id'],
                'status' => '',
                'class' => 'banner_img',
                'option' => array(
                    array(
                        'btn_type' => 'remove_image',
                        'class' => 'fa fa-trash-o',
                        'url' => base_url() . 'admin/pages/remove_image/' . $values['image_id'],
                    )
                )
            );
        endif;
        $form_array['design_stuff'] = array(
            array(
                'name' => 'content',
                'type' => 'textarea',
                'value' => isset($values['content']) ? $values['content'] : '',
                'label' => 'Content',
                'class' => 'form-control ckeditor',
                'id' => 'ckeditor_content'
            ),
        );
        $form_array['css_javascript'] = array(
            array(
                'name' => 'css',
                'type' => 'textarea',
                'value' => isset($values['css']) ? $values['css'] : false,
                'label' => 'CSS',
                'class' => 'form-control'
            ),
            array(
                'name' => 'js',
                'type' => 'textarea',
                'value' => isset($values['js']) ? $values['js'] : false,
                'label' => 'JavaScript',
                'class' => 'form-control'
            ),
        );
        $form_array['seo_stuff'] = array(
            array(
                'name' => 'meta_title',
                'type' => 'textarea',
                'value' => isset($values['meta_title']) ? $values['meta_title'] : false,
                'label' => 'Meta Title',
                'class' => 'form-control'
            ),
            array(
                'name' => 'meta_keywords',
                'type' => 'textarea',
                'value' => isset($values['meta_keywords']) ? $values['meta_keywords'] : false,
                'label' => 'Meta Keywords',
                'class' => 'form-control'
            ),
            array(
                'name' => 'meta_description',
                'type' => 'textarea',
                'value' => isset($values['meta_description']) ? $values['meta_description'] : false,
                'label' => 'Meta Description',
                'class' => 'form-control'
            ),
        );
        $form_array['page_status'] = array(
            array(
                'name' => 'status',
                'type' => 'checkbox',
                'value' => '1',
                'checked' => isset($values['status']) ? $values['status'] : TRUE,
                'class' => 'form-control',
                'label' => 'Active',
            ),
        );
        $form_array['buttons'] = array(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'save',
                'class' => 'btn btn-theme',
                'content' => 'Save & Exit'
            ),
            array(
                'name' => 'submit',
                'type' => 'submit',
                'value' => 'save_edit',
                'class' => 'btn btn-theme',
                'content' => 'Save & Continue Edit'
            ),
            array(
                'name' => 'reset',
                'type' => 'reset',
                'value' => 'Reset',
                'class' => 'btn',
                'onclick' => "$( 'form' ).parsley( 'destroy' )"
            )
        );

        return $form_array;
    }

    public function index() {
        $data['title'] = 'Page list';
        $data['label'] = '<strong>Page</strong> list';
        $data['sub_label'] = 'List of Pages';
        $data['list'] = $this->page->get_all();

        $i = 0;
        foreach ($data['list'] as $k => $v):
            $data['list'][$i]->status = display_status($v->status);
            $i++;
        endforeach;

        $data['buttons'] = array(
            array(
                'title' => 'Add New Page',
                'url' => base_url('admin/pages/create'),
                'class' => 'primary',
                'icon' => 'plus'
            )
        );
        $data['fields'] = array(
            'SN',
            'Title',
            'Navigation Group',
            'URL',
            'Status',
            'Action'
        );
        $data['action'] = array(
            array(
                'name' => 'Edit',
                'url' => base_url('admin/pages/edit'),
                'icon' => 'pencil'
            ),
            array(
                'name' => 'Delete',
                'url' => base_url('admin/pages/delete'),
                'icon' => 'trash-o'
            )
        );

        template('list', $data);
    }

    public function create() {
        $data['title'] = 'Create Page';
        $data['form_action'] = 'admin/pages/create';

        $form['form_size'] = '6';
        $form['label'] = 'Page Info';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['page_info'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = 'Navigation';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['nav'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = 'Page Banner';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['page_image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12 design_stuff';
        $form['label'] = 'Main Content';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['design_stuff'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = 'SEO Stuff';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['seo_stuff'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = 'CSS & Javascript';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['css_javascript'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = 'Page Status';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['page_status'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($this->input->post());
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $datas = $this->input->post();

            if (!check_duplication($this->_table, array('slug' => $this->input->post('slug')))):
                set_flashdata(array(
                    'status' => 'danger',
                    'message' => 'Page already exist'
                ));

                redirect('admin/pages/create');
            endif;

            if (!isset($datas['status'])):
                $datas['status'] = 0;
            endif;

            $insert_data['page'] = array(
                'title' => $datas['title'],
                'slug' => $datas['slug'],
                'content' => $datas['content'],
                'css' => $datas['css'],
                'js' => $datas['js'],
                'meta_title' => $datas['meta_title'],
                'meta_keywords' => $datas['meta_keywords'],
                'meta_description' => $datas['meta_description'],
                'status' => $datas['status'],
                'created_on' => strtotime("now"),
            );
            if (!empty($_FILES['userfile']['name'])):
                $insert_data['page']['image_id'] = upload('uploads/page');
            endif;

            $insert_id = save($this->_table, $insert_data['page']);

            $insert_data['nav'] = array(
                'title' => $datas['title'],
                'parent' => $datas['parent'],
                'link_type' => 'page',
                'page_id' => $insert_id,
                'navigation_group_id' => $datas['navigation_group_id'],
                'target' => $datas['target'],
                'class' => $datas['class']
            );

            save('navigation_links', $insert_data['nav']);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Page created successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/pages');
            else:
                redirect('admin/pages/edit/' . $insert_id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function edit($id) {
        $data['title'] = 'Update Page';
        $d = (array) $this->page->get($id);

        $data['form_action'] = 'admin/pages/edit/' . $id;

        $form['form_size'] = '6';
        $form['label'] = 'Page Info';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['page_info'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = 'Navigation';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['nav'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = 'Page Banner';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['page_image'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12 design_stuff';
        $form['label'] = 'Main Content';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['design_stuff'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = 'SEO Stuff';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['seo_stuff'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '6';
        $form['label'] = 'CSS & Javascript';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['css_javascript'];
        $data['form'][] = $this->form_builder->build($form);

        $form['form_size'] = '12';
        $form['label'] = 'Page Status';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['page_status'];
        $data['form'][] = $this->form_builder->build($form);

        $form['label'] = '';
        $form['sub_label'] = '';
        $form['form_components'] = $this->form_array($d);
        $form['form_components'] = $form['form_components']['buttons'];
        $data['form'][] = $this->form_builder->build($form);

        if ($this->input->post()):
            $datas = $this->input->post();

            if (!isset($datas['status'])):
                $datas['status'] = 0;
            endif;

            if (!isset($datas['is_home'])):
                $datas['is_home'] = 0;
            endif;

            $update_data['page'] = array(
                'title' => $datas['title'],
                'slug' => $datas['slug'],
                'content' => $datas['content'],
                'css' => $datas['css'],
                'js' => $datas['js'],
                'meta_title' => $datas['meta_title'],
                'meta_keywords' => $datas['meta_keywords'],
                'meta_description' => $datas['meta_description'],
                'status' => $datas['status'],
                'updated_on' => strtotime("now"),
            );

            if (!empty($_FILES['userfile']['name'])):
                remove_image($d['image_id']);
                $update_data['page']['image_id'] = upload('uploads/page');
            endif;

            $con = array(
                'key' => 'id',
                'value' => $id
            );

            update($this->_table, $con, $update_data['page']);

            $update_data['nav'] = array(
                'title' => $datas['title'],
                'parent' => $datas['parent'],
                'link_type' => 'page',
                'navigation_group_id' => $datas['navigation_group_id'],
                'target' => $datas['target'],
                'class' => $datas['class']
            );

            $con = array(
                'key' => 'page_id',
                'value' => $id
            );
            update('navigation_links', $con, $update_data['nav']);

            set_flashdata(array(
                'status' => 'success',
                'message' => '<strong>Success!</strong> Page updated successfully.'
            ));

            if ($this->input->post('submit') == 'save'):
                redirect('admin/pages');
            else:
                redirect('admin/pages/edit/' . $id);
            endif;
        else:
            template('create', $data);
        endif;
    }

    public function delete($id) {
        $page_banner = $this->page->get($id);

        //delete page
        if (isset($page_banner->image_id) && $page_banner->image_id != '0'):
            remove_image($page_banner->image_id);
        endif;
        $con = array('key' => 'id', 'value' => $id);
        delete($this->_table, $con);

        //delete navigation
        $con = array('key' => 'page_id', 'value' => $id);
        delete('navigation_links', $con);

        set_flashdata(array(
            'status' => 'success',
            'message' => '<strong>Success!</strong> Page deleted successfully.'
        ));

        redirect('admin/pages');
    }

    function remove_image($id) {
        remove_image($id);
        $con = array('key' => 'image_id', 'value' => $id);
        update($this->_table, $con, array('image_id' => ''));

        echo TRUE;
    }

}
