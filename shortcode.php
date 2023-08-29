<?php
defined('ABSPATH') || exit;

################################# [ACTIONS] ################################# 
add_shortcode('key_val_last', 'get_last_short_code');
add_shortcode('key_val_history', 'get_history_short_code');

add_shortcode('key_val_form_insert', 'key_val_form_insert_short_code');
add_shortcode('key_val_search', 'key_val_search');

add_shortcode('key_val_grouping', 'key_val_grouping');
################################# [FUNCTIONS] ################################# 

function get_last_short_code($atts)
{
    $name = $atts['name']??null;
    $number_format = $atts['number_format']??true;
    $postfix = $atts['postfix']??null;
    $prefix = $atts['prefix']??null;
    $keyVal = loadModel('KeyVal');
    $list = $keyVal->list(1, [
        "t2.name" => $name
    ]);
    $i = array_keys($list)[0];
    $value = $list[$i]['val'];
    if($number_format && $value && is_numeric($value))
        $value = number_format($value);
    return $value?$prefix.$value.$postfix:'';
}

function get_history_short_code($atts)
{
    $name = $atts['name'] ?? null;
    $tags = $atts['tags'] ?? null;

    view('table_short_code','',[
        'name' => $name ,
        'tags' => $tags ,
        'isTagEditable' => !($atts['isTagEditable'] ? true : false),
        'isInline' => !($atts['isInline'] ? true : false),
    ], true);
}

function key_val_form_insert_short_code($atts){
    $name = $atts['name'] ?? null;
    $tags = $atts['tags'] ?? null;
    $showLookupAfterSubmit = $atts['show_lookup-after-submit'] ?? null;
    
    view('form_insert_short_code', '', [
        'name' => $name, 
        'tags' => $tags,
        'showLookupAfterSubmit' => $showLookupAfterSubmit
    ],true);
}

function key_val_search($atts){
    view('search_short_code', '', $atts,true);
}
function key_val_grouping($atts){

    view('grouping_short_code', '', $atts,true);
}
