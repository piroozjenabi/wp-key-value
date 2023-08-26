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
        $id = $in->name;
        $general =
            ($in->readonly ? ' readonly ' : '') .
            ($in->style ? " style='{$in->style}' " : '') .
            ($in->name ? " id='{$id}' " : '');
        $in->name = "tags[{$in->id}]";
        switch ($in->type) {
            case "wooid":
                $out .= "<input placeholder='{$in->title}' class='input {$in->class}' type='text' name='{$in->name}' {$general} /> ";
                break;
            case "select":
                $tmp = json_decode(trim($in->params));
                $out .= "<select name='{$in->name}' class='select {$in->class}' placeholder='{$in->title}' {$general}>";
                foreach ($tmp as $key => $val)
                    $out .= "<option value='{$key}'>{$val}</option>";
                $out .= "</select>";
                break;
            case "textarea":
                $out .= "<textarea class='textarea {$in->class}' placeholder='{$in->title}' name='{$in->name}' {$general} > </textarea> ";
                break;
            case "file":
                $out .= "<input class='input {$in->class}' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' /> ";
                break;
            case 'api':
                $tmp = json_decode(trim($in->params));
                $data = doCurl($tmp->url);
                $value = $data[1][$tmp->value] ?? $in->default_value ?? null;
                $out .= "<input class='input {$in->class}' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$value}' {$general} /> ";
                break;
            case 'remote_keyval':
                $tmp = json_decode(trim($in->params));
                $tmp->site = trim(trim($tmp->site),'/');
                $tmp->api_key=trim($tmp->api_key);
                $url = "{$tmp->site}/wp-json/key_val/v1/last/?name={$tmp->type}&api-key={$tmp->api_key}";
                $data = doCurl($url,[] ,'GET');
                $value = $data[1]['val'] ?? $in->default_value ?? null;
                $out .= "<input class='input {$in->class}' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$value}' {$general} /> ";
                break;
            case 'formula':
                $tmp = json_decode(trim($in->params));
                $formula = trim($tmp->formula);
                $replacer = str_replace(['+', '-', '/', '*'], '--', $formula);
                $var = "";
                $listener = "";
                foreach (explode('--', $replacer) as $key =>  $val) {
                    $val = str_replace(['(',')','+','-','*','#'], '', $val);
		    if (!$val || strstr($val, 'field') === false ) continue;	
                    $var .= " var $val=parseInt(jQuery('#{$val}').val()); ";
                    $listener .= " jQuery('#{$val}').on('input', function(){ calc{$id}()}); ";
                }

                $formulajs = "
                <script>
                function calc{$id}(){
                    {$var}
                    let val = eval('{$formula}');
                    jQuery('#{$id}').val(val);
                }
                {$listener};
                jQuery('input').on('input',function(){
                    calc{$id}()
                });
                </script>
                ";
                $out .= "
                {$formulajs}
                <input class='input {$in->class}' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' {$general} /> ";
                break;
            default:
                $out .= "<input class='input {$in->class}' placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' value='{$in->default_value}' {$general} /> ";
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
