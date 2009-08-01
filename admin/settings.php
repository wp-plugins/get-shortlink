<?php if ($_GET['settings'] == "saved") { ?>
<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e("Settings saved.", "gd-press-tools"); ?></strong></p></div>
<?php } ?>

<div class="gdsr"><div class="wrap">
<h2 class="gdptlogopage">Get Shortlink</h2>
<form method="post">
<table class="form-table" style="margin-bottom: 10px"><tbody>
<tr><th scope="row"><?php _e("Shortening service", "gd-press-tools"); ?></th>
    <td>
        <select name="shortening_service" style="width: 250px">
            <option value="friendly"<?php echo $options["shortening_service"] == 'friendly' ? ' selected="selected"' : ''; ?>>Get Shortlink</option>
            <option value="isgd"<?php echo $options["shortening_service"] == 'isgd' ? ' selected="selected"' : ''; ?>>http://is.gd/</option>
            <option value="digg"<?php echo $options["shortening_service"] == 'digg' ? ' selected="selected"' : ''; ?>>http://digg.com/</option>
            <option value="tinyurl"<?php echo $options["shortening_service"] == 'tinyurl' ? ' selected="selected"' : ''; ?>>http://tinyurl.com/</option>
        </select>
    </td>
</tr>
<tr><th scope="row"><?php _e("Friendly prefix", "gd-press-tools"); ?></th>
    <td>
        <input style="width: 250px;" type="text" name="short_prefix" value="<?php echo $options["short_prefix"]; ?>" />
        <div class="gdsr-table-split"></div>
        This will be added after blog url, before post ID, like this: http://www.example.com/<strong>prf</strong>123
    </td>
</tr>
<tr><th scope="row"><?php _e("Auto placement", "gd-press-tools"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="250" valign="top">
                    <input type="checkbox" name="display_home" id="display_home"<?php if ($options["display_home"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="display_home"><?php _e("For posts displayed on Front Page."); ?></label>
                    <br />
                    <input type="checkbox" name="display_posts" id="display_posts"<?php if ($options["display_posts"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="display_posts"><?php _e("For individual posts."); ?></label>
                    <br />
                    <input type="checkbox" name="display_pages" id="display_pages"<?php if ($options["display_pages"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="display_pages"><?php _e("For individual pages."); ?></label>
                </td>
                <td width="10"></td>
                <td valign="top">
                    <input type="checkbox" name="display_archive" id="display_archive"<?php if ($options["display_archive"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="display_archive"><?php _e("For posts displayed in Archives."); ?></label>
                    <br />
                    <input type="checkbox" name="display_search" id="display_search"<?php if ($options["display_search"] == 1) echo " checked"; ?> /><label style="margin-left: 5px;" for="display_search"><?php _e("For posts displayed on Search results."); ?></label>
                </td>
            </tr>
        </table>
        <div class="gdsr-table-split"></div>
        <select style="width: 250px;" name="insert_location">
            <option value="bottom"<?php echo $options["insert_location"] == 'bottom' ? ' selected="selected"' : ''; ?>><?php _e("Bottom"); ?></option>
            <option value="top"<?php echo $options["insert_location"] == 'top' ? ' selected="selected"' : ''; ?>><?php _e("Top"); ?></option>
            <option value="hidden"<?php echo $options["insert_location"] == 'hidden' ? ' selected="selected"' : ''; ?>><?php _e("Hidden"); ?></option>
        </select>
    </td>
</tr>
<tr><th scope="row"><?php _e("Look &amp; feel", "gd-press-tools"); ?></th>
    <td>
        <table cellpadding="0" cellspacing="0" class="previewtable">
            <tr>
                <td width="100">Button text:</td>
                <td valign="top">
                    <input style="width: 400px;" type="text" name="design_text" value="<?php echo $options["design_text"]; ?>" />
                </td>
            </tr>
        </table>
    </td>
</tr>
</tbody></table>

<input type="submit" class="inputbutton" value="<?php _e("Save Settings"); ?>" name="gesh_saving"/>
</form>
<div class="gdsr-table-split"></div>
<a style="float: left; margin-right: 15px" href="http://www.splicelicio.us/get-shortlink-wordpress-plugin"><img src="<?php echo GETSHORTLINK_URL ?>gfx/upgrade.png" /></a>
<h4 style="margin: 0; padding: 0;">Get Shortlinks Pro Features:</h5>
<ul style="margin-top: 10px; list-style: disc inside;"><li>Gets rid of  "Learn More" link</li>
<li>Includes three layout presets for how the button and text displays in your theme</li></ul>
<li>Also, get color icons that match your theme</li></ul>
<div style="clear: both"></div>
</div></div>
