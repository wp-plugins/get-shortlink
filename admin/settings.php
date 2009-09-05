<?php

if ($_POST['rrp_action'] == 'save') :
    $options["limit_results"] = $_POST["limit_results"];
    $options["thumb_field"] = $_POST["thumb_field"];
    $options["panel_size"] = $_POST["panel_size"];
    update_option('rich-related-posts-settings', $options);

    ?><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong>Settings saved.</strong></p></div><?php
endif;

?>
<div class="wrap">
<h2>Rich Related Posts: Settings</h2>
<form method="post">
<input type="hidden" id="rrp_action" name="rrp_action" value="save" />
<table class="form-table"><tbody>
<tr><th scope="row">Search</th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150">Limit number of results:</td>
                <td width="200" align="left">
                    <input type="text" value="<?php echo $options["limit_results"]; ?>" id="limit_results" name="limit_results" style="width: 80px; text-align: right;" />
                </td>
            </tr>
            <tr>
                <td width="150">Thumbnail custom field:</td>
                <td width="200" align="left">
                    <input type="text" value="<?php echo $options["thumb_field"]; ?>" id="thumb_field" name="thumb_field" style="width: 80px; text-align: right;" />
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr><th scope="row">Administration</th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="150">Group panel height:</td>
                <td width="200" align="left">
                    <input type="text" value="<?php echo $options["panel_size"]; ?>" id="panel_size" name="panel_size" style="width: 80px; text-align: right;" /> [px]
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<p class="submit"><input type="submit" value="Save Settings" name="rrp_saving" /></p>
</form>
</div>