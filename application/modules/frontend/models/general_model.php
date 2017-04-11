<?php

class General_model extends CI_Model
{
    public function get_slider()
    {
        return $this->db->get('banner')->result();
    }

    public function get_about_us()
    {
        $sql = $this->db->get_where('pages', array('slug' => '#about'))->row();

        if ($sql) {
            return $sql;
        } else {
            return FALSE;
        }
    }

    public function get_item()
    {
        $sql = $this->db->select('id, category_name, category_slug')
            ->from('category')
            ->get()->result();
        $i = 0;
        foreach ($sql as $s) {
            $sql[$i]->items = $this->db->select('p.id, p.name, p.price, p.description, pi.image_id')
                ->from('products as p')
                ->join('products_categories as pc', 'pc.product_id = p.id', 'LEFT')
                ->join('products_images as pi','pi.product_id = p.id','LEFT')
                ->where('pc.category_id', $s->id)
                ->get()->result();
            $i++;
        }

        return $sql;
    }

    public function get_testimonials(){
        return $this->db->get('testimonial')->result();
    }
}