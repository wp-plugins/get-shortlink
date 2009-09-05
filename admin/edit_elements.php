<?php $field_width = 300; if (RICHRELATED_WPV < 27) $field_width = 190; ?>
<table id="rrpgroup" cellpadding="0" cellspacing="0">
<thead>
    <tr class="header">
        <td><strong>Group Editor</strong></td>
        <td class="headbutton"><a href="javascript:addElement(0, '<?php echo wp_create_nonce('rrp_nonce'); ?>')"><div class="rrpbutton rrpadd">Add Element</div></a></td>
        <td class="headbutton"><a href="javascript:saveGroup('save', '<?php echo wp_create_nonce('rrp_nonce'); ?>')"><div id="rrpbtsave" class="rrpbutton rrpsave">Save Group</div></a></td>
        <td class="headbutton"><a href="javascript:saveGroup('saveas', '<?php echo wp_create_nonce('rrp_nonce'); ?>')"><div id="rrpbtsaveas" class="rrpbutton rrpsave">Save As New</div></a></td>
    </tr>
</thead>
    <tr>
        <td colspan="4" class="groupname">
        Name:<input style="width: 150px; margin-left: 10px; margin-right: 10px;" type="text" id="rrpgroupname" name="rrpgroupname" value="" />
        ID:<input style="width: 30px; margin-left: 10px; margin-right: 10px;" type="text" id="rrpgroupid" name="rrpgroupid" readonly value="" /><?php if (RICHRELATED_WPV < 27) echo "<br />"; ?>
        Thumb:<input style="width: 40px; margin-left: 10px;" type="text" id="rrpgroupx" name="rrpgroupx" value="160" />
        x<input style="width: 40px; margin-right: 10px;" type="text" id="rrpgroupy" name="rrpgroupy" value="100" />
        <?php /*Template:<?php rich_render_templates($tpl) ?>*/ ?></td>
    </tr>
<tr><td colspan="4">
    <table id="rrpelements" cellpadding="0" cellspacing="0">
        <tr id="rrpelementtr-0" class="rrpelementtr rrphidden"><td>
            <div id="rrpel-0" class="rrpelementdiv">
                <input type="hidden" id="rrppostid-0" value="" />
                <input type="hidden" id="rrpthumbid-0" value="" />
                <table>
                    <tr><td><a id="rrpeldel-0" href="javascript:deleteElement(0)"><img src="<?php echo RICHRELATED_URL; ?>gfx/delete.png" /></a></td><td class="tdelleft" style="width:70px"><strong>Element:</strong></td><td style="width:70px">Headline:</td><td><input style="width: <?php echo $field_width; ?>px; margin-left: 10px;" type="text" id="rrpheadline-0" value="" /></td><td><a href="<?php echo RICHRELATED_URL; ?>find-post.php?id=0&mode=both&keepThis=true&TB_iframe=true&height=300&width=400" class="thickbox" title="Find Post And Thumb" id="rrpelfindhead-0"><img id="rrpheadlinesearch-0" src="<?php echo RICHRELATED_URL; ?>gfx/search.png" /></a></td></tr>
                    <tr><td></td><td></td><td style="width:70px">Post URL:</td><td><input style="width: <?php echo $field_width; ?>px; margin-left: 10px;" type="text" id="rrpposturl-0" value="" /></td><td><a id="rrpelfindpost-0" href="<?php echo RICHRELATED_URL; ?>find-post.php?id=0&mode=post&keepThis=true&TB_iframe=true&height=300&width=400" class="thickbox" title="Find Post"><img src="<?php echo RICHRELATED_URL; ?>gfx/search.png" /></a></td></tr>
                    <tr><td></td><td></td><td style="width:70px">Image URL:</td><td><input style="width: <?php echo $field_width; ?>px; margin-left: 10px;" type="text" id="rrpimageurl-0" value="" /></td><td><a id="rrpelfindimage-0" href="<?php echo RICHRELATED_URL; ?>find-image.php?id=0&keepThis=true&TB_iframe=true&height=300&width=400" class="thickbox" title="Find Image"><img src="<?php echo RICHRELATED_URL; ?>gfx/search.png" /></a></td></tr>
                </table>
            </div>
        </td></tr>
    </table>
</td></tr></tbody>
</table>