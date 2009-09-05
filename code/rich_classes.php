<?php

class RichTemplate {
    var $id;
    var $name;
    var $before;
    var $after;
    var $element;
    
    function RichTemplate($rt_id, $rt_name = "New Template", $rt_before = "", $rt_after = "", $rt_element = "") {
        $this->id = $rt_id;
        $this->name = $rt_name;
        $this->before = $rt_before;
        $this->after = $rt_after;
        $this->element = $rt_element;
    }

    function update($rt_name = "", $rt_before = "", $rt_after = "", $rt_element = "") {
        $this->name = $rt_name;
        $this->before = $rt_before;
        $this->after = $rt_after;
        $this->element = $rt_element;
    }
}

class RichRelatedTemplates {
    var $seq_id;
    var $templates;
    var $default;
    
    function RichRelatedTemplates() {
        $this->seq_id = 1;
        $this->default = $this->seq_id;
        $this->templates = array();
        $this->add_default_template();
    }

    function get_template($id) {
        foreach ($this->templates as $t) {
            if ($t->id == $id)
                return $t;
        }
        return $this->get_template($this->default);
    }

    function add_default_template() {
        $this->add_template("Default Template",
            "",
            "",
            "&lt;div class=&quot;wp-caption alignleft&quot; style=&quot;width: 175px&quot;&gt;&lt;a href=&quot;{url}&quot;&gt;&lt;img class=&quot;size-thumbnail&quot; src=&quot;{image}&quot; width=&quot;165&quot; height=&quot;95&quot; /&gt;&lt;/a&gt;&lt;p class=&quot;wp-caption-text&quot;&gt;{headline}&lt;/p&gt;&lt;/div&gt;"
        );
    }

    function add_template($rt_name = "New Template", $rt_before = "", $rt_after = "", $rt_element = "") {
        $this->templates[] = new RichTemplate($this->seq_id, $rt_name, $rt_before, $rt_after, $rt_element);
        $this->seq_id++;
    }

    function update_template_from_post($id, $values) {
        $this->update_template($id,
            $values["name"],
            stripslashes(htmlentities($values["before"], ENT_QUOTES, 'UTF-8')),
            stripslashes(htmlentities($values["after"], ENT_QUOTES, 'UTF-8')),
            stripslashes(htmlentities($values["element"], ENT_QUOTES, 'UTF-8')));
    }

    function update_template($id, $rt_name = "", $rt_before = "", $rt_after = "", $rt_element = "") {
        foreach ($this->templates as $tpl) {
            if ($tpl->id == $id) {
                $tpl->update($rt_name, $rt_before, $rt_after, $rt_element);
                break;
            }
        }
    }

    function delete_template($id) {
        $del_id = -1;
        foreach ($this->templates as $orid => $tpl) {
            if ($tpl->id == $id) {
                $del_id = $orid;
                break;
            }
        }
        if ($del_id > -1) unset($this->templates[$del_id]);
    }
}

class RichElement {
    var $id;
    var $post_id;
    var $thumb_id = 0;
    var $permalink;
    var $thumbnail;
    var $headline;
    
    function RichElement($id, $pid, $title, $tid, $thumb, $perma) {
        $this->id = $id;
        $this->post_id = $pid;
        if (wp_get_attachment_url($tid) == $thumb) $this->thumb_id = $tid;
        $this->headline = $title;
        $this->thumbnail = $thumb;
        $this->permalink = $perma;
    }
}

class RichGroup {
    var $id;
    var $seq_id;
    var $name;
    var $thumb_x;
    var $thumb_y;
    var $template;
    var $elements;
    var $action = '';
    
    function RichGroup($gr_id, $gr_name = "Group", $gr_thumb_x = 165, $gr_thumb_y = 95, $gr_template = 1) {
        $this->id = $gr_id;
        $this->seq_id = 1;
        $this->name = $gr_name;
        $this->thumb_x = $gr_thumb_x;
        $this->thumb_y = $gr_thumb_y;
        $this->template = $gr_template;
        $this->elements = array();
    }

    function update($gr_name, $gr_thumb_x, $gr_thumb_y, $gr_template) {
        $this->name = $gr_name;
        $this->thumb_x = $gr_thumb_x;
        $this->thumb_y = $gr_thumb_y;
        $this->template = $gr_template;
    }

    function add_element($pid, $title, $tid, $thumb, $perma) {
        $this->elements[] = new RichElement($this->seq_id, $pid, $title, $tid, $thumb, $perma);
        $this->seq_id++;
    }

    function add_elements($el, $clear = false) {
        if ($clear) {
            $this->elements = array();
            $this->seq_id == 1;
        }
        foreach ($el as $e) {
            $this->elements[] = new RichElement($this->seq_id, $e->post_id, $e->headline, $e->thumb_id, $e->thumbnail, $e->permalink);
            $this->seq_id++;
        }
    }
}

class RichRelatedGroups {
    var $group_id = 1;
    var $groups = array();
    
    function RichRelatedGroups() { }

    function add_group($request) {
        $group = new RichGroup($this->group_id, $request->name, $request->thumb_x, $request->thumb_y, $request->template);
		$group->add_elements($request->elements);
        $this->groups[] = $group;
        $this->group_id++;
        return $group;
    }

    function delete_group($id) {
        $del_id = -1;
        foreach ($this->groups as $orid => $group) {
            if ($group->id == $id) {
                $del_id = $orid;
                break;
            }
        }
        if ($del_id > -1) unset($this->groups[$del_id]);
    }

    function get_group($id) {
        foreach ($this->groups as $group) {
            if ($group->id == $id)
                return $group;
        }
    }

    function edit_group($request) {
        foreach ($this->groups as $group) {
            if ($group->id == $request->id) {
                $group->update($request->name, $request->thumb_x, $request->thumb_y, $request->template);
                $group->add_elements($request->elements, true);
                return $group;
            }
        }
    }
	
	function get_groups_length() {
		return sizeof($this->groups);
	}
}

class RichRelatedQuick {
    var $id = 0;
    var $url = '';
    var $thumb = '';

    function RichRelatedQuick() { }
}

?>
