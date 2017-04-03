<?php

function insert_notification($notification_type_id, $from_id, $notification_message, $url_link, $created_at) {
    $ci = & get_instance();
    $notification_data = array(
        'id' => '',
        'notification_type_id' => $notification_type_id,
        'from_id' => $from_id,
        'notification_message' => $notification_message,
        'url_link' => $url_link,
        'created_at' => date('Y-m-d H:i:s')
    );
    $ci->db->insert('notification', $notification_data);
    $notification_id = $ci->db->insert_id();
    $admin_users = get_admin_users();
    if ($admin_users) {
        foreach ($admin_users as $admin) {
            insert_notification_users($notification_id, $admin->id);
        }
    }
}

function get_notifications($user_id) {
    $ci = & get_instance();
    $ci->db->select('nu.id, up.first_name, up.middle_name, up.last_name, n.notification_message,n.created_at, nt.type, n.url_link, nu.status');
    $ci->db->from('notification_users as nu');
    $ci->db->join('notification as n', 'n.id = nu.notification_id');
    $ci->db->join('notification_type as nt', 'nt.id = n.notification_type_id');
    $ci->db->join('users as u', 'u.id = n.from_id', 'LEFT');
    $ci->db->join('users_profile as up', 'up.user_id = u.id', 'LEFT');
    if ($user_id) {
        $ci->db->where('nu.user_id', $user_id);
    }
    $ci->db->limit(4);
    $ci->db->order_by('n.id', 'DESC');
    $query = $ci->db->get();
    $html = '';
    $html .= '<ul>';
    $i = 1;
    $fa_class = '';
    $im_class = 'bg-inverse';
    foreach ($query->result() as $n) {
        $created_at = elapsed_time($n->created_at);
        if ($n->status == 0) {
            $fa_class = '<i class="fa fa-bell-0"></i>';
            $im_class = 'bg-theme';
        } else if ($n->status == 1) {
            $fa_class = '<i class="fa fa-eye"></i>';
            $im_class = 'bg-warging';
        } else if ($n->status == 2) {
            $fa_class = '<i class="fa fa-check"></i>';
            $im_class = 'bg-theme-inverse';
        } else {
            $fa_class = '<i class="fa fa-cogs"></i>';
        }


        $html .= '<li>';
        $html .= '<section class = "thumbnail-in">';
        $html .= '<div class = "widget-im-tools tooltip-area pull-right">';
        $html .= '<span class="' . $n->id . '">';
        //$html .= '<time class = "timeago" datetime = "' . date('Y-m-d\TH:i:s\Z', $n->created_at) . '">time ago</time>';
        $html .= '<time>' . elapsed_time(strtotime($n->created_at)) . '</time>';
        $html .='</span>';
        $html .='<span>';
        $html .='<a href = "javascript:void(0)" class = "im-action" data-toggle = "tooltip" data-placement = "left" title = "Action"><i class = "fa fa-keyboard-o"></i></a>';
        $html .= '</span>';
        $html .= '</div>';
        $html .= '<h4><a class="rev review notification-rev confirm_review confirm_review_' . $n->id . '" href = "' . base_url() . $n->url_link . '"  data-user="' . active_user_id() . '">' . $n->notification_message . '</a></h4>';
        $html .= '<div class = "im-thumbnail ' . $im_class . '">';
        $html .= $fa_class;
        $html.= '</div>';
        $html .= '<div class = "pre-text">By: <strong>' . get_fullname($n->first_name, $n->middle_name, $n->last_name) . '</strong> </div>';
        $html .= '</section>';
        $html .= '<div class = "im-confirm-group">';
        $html .= '<div class = " btn-group btn-group-justified">';
        if ($n->status == 0) {
            $html .= '<a class = "btn btn-inverse im-confirm confirm_review confirm_review_' . $n->id . '" href = "' . base_url() . $n->url_link . '"  data-user="' . active_user_id() . '"><i class="fa fa-eye"></i> View Now.</a>';
        }
        if ($n->status == 1) {
            $html .= '<a class = "btn btn-inverse im-confirm" href = "javascript:void(0)" data-confirm = "actionNow"><i class="fa fa-checked"></i> Viewed.</a>';
        }
        $html .= '</div>';
        $html .= '</div >';
        $html .= '</li>';
        $i++;
    }

    $html .= '</ul>';
    $html .= '<a href="' . base_url() . 'admin/notification"><button class="btn btn-inverse btn-block btn-lg" title="See More"><i class="fa fa-eye"></i> View All</button></a>';
    return $html;
}

function elapsed_time($timestamp) {
//type cast, current time, difference in timestamps
    $timestamp = (int) $timestamp;
    $current_time = time();
    $diff = $current_time - $timestamp;
//intervals in seconds

    if ($diff == 0)
        return 'just now';
    $intervals = array
        (
        1 => array('year', 31556926),
        $diff < 31556926 => array('month', 2628000),
        $diff < 2629744 => array('week', 604800),
        $diff < 604800 => array('day', 86400),
        $diff < 86400 => array('hour', 3600),
        $diff < 3600 => array('minute', 60),
        $diff < 60 => array('second', 1)
    );

    $value = floor($diff / $intervals[1][1]);
    return $value . ' ' . $intervals[1][0] . ($value > 1 ? 's' : '') . ' ago';
}

function get_admin_users() {
    $ci = & get_instance();
    $query = $ci->db->select('u.id')
            ->from('users as u')
            ->join('groups as g', 'u.group_id = g.id')
            ->where('g.type', 1)
            ->group_by('u.id')
            ->get();
    return $query->result();
}

function insert_notification_users($notification_id, $user_id) {
    $ci = & get_instance();
    $data = array(
        'id' => '',
        'user_id' => $user_id,
        'notification_id' => $notification_id,
        'status' => 0
    );
    $ci->db->insert('notification_users', $data);
}

?>
