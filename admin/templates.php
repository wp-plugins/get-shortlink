<?php

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "new":
            $tpl->add_template();
            break;
        case "update":
            $gdsr_items = $_POST["gdsr_item"];
            $edit_items = $_POST["edit_item"];
            if (count($gdsr_items) > 0) {
                foreach ($gdsr_items as $delete_id)
                    $tpl->delete_template($delete_id);
            }
            else $gdsr_items = array();
            foreach ($edit_items as $key => $value) {
                if (!in_array($key, $gdsr_items))
                    $tpl->update_template_from_post($key, $value);
            }
            $tpl->default = $_POST["richdefault"];
            break;
    }
    update_option('rich-related-posts-templates', $tpl);
}

$url = $_SERVER['REQUEST_URI'];
$url_pos = strpos($url, "&action=");
if (!($url_pos === false))
    $url = substr($url, 0, $url_pos);

$edit_url = $url."&action=";

?>

<script>
function newTemplate() {
    window.location = "<?php echo $edit_url; ?>new";
}
function checkAll(form) {
    for (i = 0, n = form.elements.length; i < n; i++) {
        if(form.elements[i].type == "checkbox" && !(form.elements[i].getAttribute('onclick', 2))) {
            if(form.elements[i].checked == true)
                form.elements[i].checked = false;
            else
                form.elements[i].checked = true;
        }
    }
}
</script>
<div class="wrap">
<h2>Rich Related Posts: Template</h2>
<form id="richtemplates" method="post" action="<?php echo $edit_url; ?>update">
<?php /*
<div class="tablenav">
    <div class="alignleft"><input onclick="newTemplate()" class="button-secondary delete" title="New" style="width: 100px" type="button" value="Add New Template" /></div>
</div>
*/ ?>
<br class="clear"/>

<table class="widefat">
    <thead>
        <tr>
            <th width="200" scope="col">Name</th>
            <th scope="col">Elements</th>
        </tr>
    </thead>
    <tbody>

<?php

    $tr_class = "";
    foreach ($tpl->templates as $t) {
		echo '<input type="hidden" name="richdefault" value="'.$t->id.'" />';
        echo '<tr id="tpl-'.$t->id.'" class="'.$tr_class.' author-self status-publish" valign="top">';			
            echo '<td><input type="text" name="edit_item['.$t->id.'][name]" value="'.$t->name.'" /></td>';
            echo '<td>';
                echo '<table class="rrptpl">';
                echo '<tr><td class="tplelleft">Before:</td><td class="tplelright"><input type="text" name="edit_item['.$t->id.'][before]" value="'.wp_specialchars($t->before).'" /></td></tr>';
                echo '<tr><td class="tplelleft">Element:</td><td class="tplelright"><input type="text" name="edit_item['.$t->id.'][element]" value="'.wp_specialchars($t->element).'" /></td></tr>';
                echo '<tr><td class="tplelleft">After:</td><td class="tplelright"><input type="text" name="edit_item['.$t->id.'][after]" value="'.wp_specialchars($t->after).'" /></td></tr>';
                echo '</table>';
            echo '</td>';
        echo '</tr>';
        if ($tr_class == "") $tr_class = "alternate ";
        else $tr_class = "";
    }

?>

    </tbody>
</table>
<p class="submit"><input type="submit" value="Save" name="gdsr_saving" style="width: 100px" /></p>
<h3>Supported Template Tags:</h3>
<p>
    <strong>{image}</strong> : thumbnail url<br />
    <strong>{url}</strong> : post url<br />
    <strong>{headline}</strong> : headline text<br />
</p>
</form>
</div>