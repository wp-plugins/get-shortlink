<table id="rrpgroups" cellpadding="0" cellspacing="0">
    <thead>
        <tr class="header">
            <td><strong>Groups</strong></td>
            <td class="headbutton"><a href="javascript:addNewGroup()"><div class="rrpbutton rrpadd">Add Group</div></a></td>
        </tr>
    </thead>
    <tbody>
    <tr class="rrphidden" id="rrpgroup-0"><td class="tdleft"></td><td class="tdright"></td><tr>

<?php

$tr_class = "";
foreach ($groups->groups as $group) {
    echo '<tr class="'.$tr_class.'" id="rrpgroup-'.$group->id.'">';
        echo '<td class="tdleft"><strong>['.$group->id.']</strong> <span id="rrpgname-'.$group->id.'">'.$group->name.'</span></td>';
        echo '<td class="tdright">';
            echo '<a href="javascript:insertShortcode('.$group->id.')" title="Insert Shortcode"><img src="'.RICHRELATED_URL.'gfx/insert.png" /></a>';
            echo '<a href="javascript:deleteGroup('.$group->id.', \''.wp_create_nonce('rrp_nonce').'\')" title="Delete Group"><img src="'.RICHRELATED_URL.'gfx/delete.png" /></a>';
            echo '<a href="javascript:editGroup('.$group->id.', \''.wp_create_nonce('rrp_nonce').'\')" title="Edit Group"><img src="'.RICHRELATED_URL.'gfx/edit.png" /></a>';
        echo '</td>';
    echo '<tr>';
    if ($tr_class == "") $tr_class = "alternate ";
    else $tr_class = "";
}

?>

    </tbody>
</table>