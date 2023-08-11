<?php
class TagService
{

    /**
     * render getter for tags
     *
     * @param object $in
     * @return string
     */
    function renderGet(Object $in): string
    {
        $out = "";
        switch ($in->type) {

            case "wooid":
                $out .= "<input placeholder='{$in->title}' class='input' type='text' name='{$in->name}' /> ";
                break;
            case "select":
                $tmp = json_decode(trim($in->params));
                $out .= "<select name='{$in->name}' class='select' placeholder='{$in->title}'>";
                foreach ($tmp as $key => $val)
                    $out .= "<option value='{$key}'>{$val}</option>";
                $out .= "</select>";
                break;
            case "textarea":
                $out .= "<textarea class='textarea' placeholder='{$in->title}' name='{$in->name}' > </textarea> ";
                break;
            case "file":
                $out .= "<input class='input' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' /> ";
                break;
            case 'api':
                $tmp = json_decode(trim($in->params));
                $data = doCurl($tmp->url);
                $value = $data[1]->{$tmp->value}?? $in->default_value??null;
                $out .= "<input class='input' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$value}' /> ";
                break;
            case 'remote_keyval':
                $tmp = json_decode(trim($in->params));
                $url= "http://{$tmp->site}/wp-json/key_val/v1/last/?name={$tmp->key}";
                $data = doCurl($url);
                $value = $data[1]->{$tmp->val}?? $in->default_value??null;
                $out .= "<input class='input' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$value}' /> ";
                break;
            case 'formula':
                $tmp = json_decode(trim($in->params));
                $formulajs="";
                $out .= "<input class='input' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$value}' /> ";
                break;
            default:
                $out .= "<input class='input' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$in->default_value}' /> ";
                break;
        }

        return $out;
    }

    /**
     * show tags
     *
     * @param string $val
     * @param string $type
     * @return string
     */
    function renderShow(String $val, String $type = 'text'): string
    {
        switch ($type) {
            case 'file':
                $url = wp_get_attachment_url($val);
                return "<a href='{$url}' class='button link is-small' target='__blank'>view</a>";
            case 'color':
                return "<span style='background:{$val};padding:3px;text-shadow:0px 0px 2px #fff;border-radius:3px'> {$val} </span>";
            default:
                return $val;
        }
    }
}
