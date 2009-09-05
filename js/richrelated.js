var rrpGroupActive = { };

Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};

function get_default_element() {
    return { id: 0, post_id: '', thumb_id: '', headline: '', thumbnail: '', permalink: '' };
}

function get_default_group() {
    return { 'action': 'saveas', 'id': 0, 'seq_id': 1, 'name': 'Group', 'thumb_x': 160, 'thumb_y': 100, 'template': 1, 'elements': [] };
}

function insertIntoHtmlEditor(tagtext) {
    var myField = document.getElementById("content");
    if (document.selection) {
        myField.focus();
	sel = document.selection.createRange();
        sel.text = tagtext;
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos) + tagtext + myField.value.substring(endPos, myField.value.length);
    } else myField.value += tagtext;
}

function insertShortcode(id) {
    var tagtext = "[richrelatedpost id=" + id + "]";
    if (!tinyMCE.get('content') || tinyMCE.get('content').isHidden()) {
        insertIntoHtmlEditor(tagtext);
    } else tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
}

function deleteElement(id) {
    var del_id = -1;
    for (var i = 0; i < rrpGroupActive.elements.length; i++) {
        if (rrpGroupActive.elements[i].id == id) {
            del_id = i;
            break;
        }
    }
    rrpGroupActive.elements.remove(del_id);
    jQuery('#rrpelementtr-' + id).remove();
}

function populateElements() {
    for (var i = 0; i < rrpGroupActive.elements.length; i++) {
        var id = rrpGroupActive.elements[i].id;
        rrpGroupActive.elements[i].post_id = jQuery("#rrppostid-" + id).val();
        rrpGroupActive.elements[i].thumb_id = jQuery("#rrpthumbid-" + id).val();
        rrpGroupActive.elements[i].headline = jQuery("#rrpheadline-" + id).val();
        rrpGroupActive.elements[i].thumbnail = jQuery("#rrpimageurl-" + id).val();
        rrpGroupActive.elements[i].permalink = jQuery("#rrpposturl-" + id).val();
    }
}

function addElement(data, nonce) {
    var isOverLimit = false;
    if (data != 0) {
        nid = data.id;
    } else if(rrpGroupActive.elements.length>=10){
        isOverLimit = true;
        alert("Only 10 elements are allowed. Please delete an element from this group before adding another.");
    } else {
        var newElement = get_default_element();
        nid = rrpGroupActive.seq_id;
        rrpGroupActive.seq_id++;
        newElement.id = nid;
        rrpGroupActive.elements[rrpGroupActive.elements.length] = newElement;
    }

    if(!isOverLimit){
        var row = jQuery('#rrpelementtr-0').clone().removeClass("rrphidden");
        jQuery(row).find("#rrpel-0").attr("id", "rrpel-" + nid);
        jQuery(row).find("#rrpeldel-0").attr("href", "javascript:deleteElement(" + nid + ", '" + nonce + "')");
        jQuery(row).find("#rrpeldel-0").attr("id", "rrpeldel-" + nid)
        jQuery(row).find("#rrpelfindhead-0").attr("href", rrp_gfx + "find-post.php?id=" + nid + "&mode=both&keepThis=true&TB_iframe=true&height=300&width=400");
        jQuery(row).find("#rrpelfindhead-0").attr("id", "rrpheadline-" + nid)
        jQuery(row).find("#rrpelfindpost-0").attr("href", rrp_gfx + "find-post.php?id=" + nid + "&mode=post&keepThis=true&TB_iframe=true&height=300&width=400");
        jQuery(row).find("#rrpelfindpost-0").attr("id", "rrpelfindpost-" + nid)
        jQuery(row).find("#rrpelfindimage-0").attr("href", rrp_gfx + "find-image.php?id=" + nid + "&keepThis=true&TB_iframe=true&height=300&width=400");
        jQuery(row).find("#rrpelfindimage-0").attr("id", "rrpelfindimage-" + nid)
        jQuery(row).find("#rrppostid-0").attr("id", "rrppostid-" + nid);
        jQuery(row).find("#rrpthumbid-0").attr("id", "rrpthumbid-" + nid);
        jQuery(row).find("#rrpheadline-0").attr("id", "rrpheadline-" + nid);
        jQuery(row).find("#rrpposturl-0").attr("id", "rrpposturl-" + nid);
        jQuery(row).find("#rrpimageurl-0").attr("id", "rrpimageurl-" + nid);
        jQuery(row).find("#rrpheadlinesearch-0").attr("id", "rrpheadlinesearch-" + nid);
        tb_init(jQuery(row).find("a.thickbox"));

        jQuery(row).attr("id", "rrpelementtr-" + nid);
        jQuery("#rrpelements").append(row);
    }
}

function insertElements(els) {
    jQuery("#rrpelements .rrpelementtr:not(:first)").remove();
    for (var i = 0; i < els.length; i++) {
        addElement(els[i]);
        jQuery("#rrppostid-" + els[i].id).val(els[i].post_id);
        jQuery("#rrpthumbid-" + els[i].id).val(els[i].thumb_id);
        jQuery("#rrpheadline-" + els[i].id).val(els[i].headline);
        jQuery("#rrpposturl-" + els[i].id).val(els[i].permalink);
        jQuery("#rrpimageurl-" + els[i].id).val(els[i].thumbnail);
    }
}

function resetGroup() {
    jQuery("#rrpgroupname").val("Group");
    jQuery("#rrpgroupid").val("0");
    jQuery("#rrpelements .rrpelementtr:not(:first)").remove();
}

function addNewGroup() {
    jQuery("#divelements").css("display", "block");
    resetGroup();
    rrpGroupActive = get_default_group();
}

function deleteGroup(group_id, nonce) {
    jQuery.getJSON(rrp_url, { action: 'delete', id: group_id, _ajax_nonce: nonce }, function() {
        jQuery("#rrpgroup-" + group_id).remove();
    });
}

function editGroup(group_id, nonce) {
    jQuery("#divelements").css("display", "block");
    jQuery.getJSON(rrp_url, { action: 'get', id: group_id, _ajax_nonce: nonce }, function(json){
        rrpGroupActive = json;
        jQuery("#rrpgroupid").val(json.id);
        jQuery("#rrpgroupname").val(json.name);
        jQuery("#rrpgroupx").val(json.thumb_x);
        jQuery("#rrpgroupy").val(json.thumb_y);
        jQuery("#rrpgrouptpl").selectOptions(json.template);
        insertElements(json.elements)
    });
}

function find_headline_relation(id, nonce) {
    var headline = jQuery("#rrpheadline-" + id).val();
    jQuery("#rrpheadlinesearch-" + id).attr("src", rrp_gfx + "gfx/loading.gif");
    jQuery.ajax({type: 'post', dataType: 'json', url: rrp_url, data: { action: 'quick', headline: headline, _ajax_nonce: nonce }, success: function(json) {
        jQuery("#rrpheadlinesearch-" + id).attr("src", rrp_gfx + "gfx/search.png");
        if (json.id > 0) {
            jQuery("#rrppostid-" + id).val(json.id);
            jQuery("#rrpposturl-" + id).val(json.url);
            if (json.thumb != null) jQuery("#rrpimageurl-" + id).val(json.thumb);
        }
    }});
}

function saveGroup(action, nonce) {
    jQuery("#rrpbt"+action).removeClass("rrpsave");
    jQuery("#rrpbt"+action).addClass("rrploading");
    rrpGroupActive.action = action;
    
    rrpGroupActive.id = jQuery("#rrpgroupid").val();
    rrpGroupActive.name = jQuery("#rrpgroupname").val();
    rrpGroupActive.thumb_x = jQuery("#rrpgroupx").val();
    rrpGroupActive.thumb_y = jQuery("#rrpgroupy").val();
    rrpGroupActive.template = jQuery("#rrpgrouptpl").val();
    populateElements();
	
    var groupLen = 0;
    jQuery.ajax({type: 'post', dataType: 'json', url: rrp_url, data: { action: 'getlength', req: jQuery.toJSON(rrpGroupActive), _ajax_nonce: nonce }, success: function(json){
        groupLen = parseFloat(json);

        var isOverLimit = true;
        if (groupLen < 10) {
            isOverLimit = false;
        }
        // rrpGroupActive.id = 0 when saving a new post only. Test for adding 11th group or editing 1 of 10 groups.
        if(rrpGroupActive.id > 0 && groupLen < 11 && action == 'save') {
            isOverLimit = false;
        }
        if(!isOverLimit){
            jQuery.ajax({type: 'post', dataType: 'json', url: rrp_url, data: { action: action, req: jQuery.toJSON(rrpGroupActive), _ajax_nonce: nonce }, success: function(json){
                var old_id = rrpGroupActive.id;
                rrpGroupActive.id = json.id;
                jQuery("#rrpbt"+action).removeClass("rrploading");
                jQuery("#rrpbt"+action).addClass("rrpsave");
                jQuery("#rrpgroupid").val(json.id);
                if ((action == 'saveas') || (action == 'save' && old_id == 0)) {
                    var row = jQuery('#rrpgroup-0').clone().removeClass("rrphidden");
                    var right = "<a href=\"javascript:insertShortcode(" + json.id + ")\"><img src=\"" + rrp_gfx + "gfx/insert.png\" /></a>";
                    right = right + "<a href=\"javascript:deleteGroup(" + json.id + ", '" + nonce + "')\"><img src=\"" + rrp_gfx + "gfx/delete.png\" /></a>";
                    right = right + "<a href=\"javascript:editGroup(" + json.id + ", '" + nonce + "')\"><img src=\"" + rrp_gfx + "gfx/edit.png\" /></a>";
                    jQuery(row).find(".tdleft").html("<strong>[" + json.id + "]</strong> <span id=\"rrpgname-" + json.id + "\">" + json.name + "</span>");
                    jQuery(row).find(".tdright").html(right);
                    jQuery(row).attr("id", "rrpgroup-" + json.id);
                    jQuery("#rrpgroups").append(row);
                } else {
                    jQuery("#rrpgname-" + rrpGroupActive.id).html(json.name);
                }
            }});
        } else {
            jQuery("#rrpbt"+action).removeClass("rrploading");
            alert("No more than 10 groups allowed. Please delete a group to add another.");
        }
    }});
}