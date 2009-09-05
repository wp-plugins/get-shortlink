<?php

function rich_render_templates($templates, $name = "rrpgrouptpl", $id = "rrpgrouptpl", $selected = -1) {
    ?>
    <select id="<?php echo $id; ?>" name="<?php echo $name; ?>">
    <?php
    foreach ($templates->templates as $tpl) {
        if ($selected == -1) $selected = $templates->default;
        $select = ($tpl->id == $selected) ? ' selected="selected"' : $select = "";
        echo '<option value="'.$tpl->id.'"'.$select.'>'.$tpl->name.'</option>';
    }
    ?>
    </select>
    <?php
}

function get_closest_post($headline, $thumb_field) {
    global $wpdb;
    $response = new RichRelatedQuick();
    if ($headline != '') {
        $sql = $wpdb->prepare("select ID from %s where post_status = 'publish' and post_title like '%s' limit 0, 1", $wpdb->posts, "%".$headline."%");
        $id = intval($wpdb->get_var($sql));
        if ($id > 0) {
            $response->id = $id;
            $response->url = get_permalink($id);
            $response->thumb = get_post_meta($id, $thumb_field, true);
        }
    }
    return $response;
}

?>