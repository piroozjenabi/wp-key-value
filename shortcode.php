<?php
defined('ABSPATH') || exit;

################################# [ACTIONS] ################################# 
add_shortcode('key_val_last', 'get_last_short_code');
add_shortcode('key_val_history', 'get_history_short_code');

add_shortcode('key_val_form_insert', 'key_val_form_insert_short_code');
add_shortcode('key_val_search', 'key_val_search');
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
    view('table_short_code','',[
        'name' => $name , 
        'isTagEditable' => !($atts['isTagEditable'] ? true : false),
        'isInline' => !($atts['isInline'] ? true : false),
    ], true);
}

function key_val_form_insert_short_code($atts){
    $name = $atts['name'] ?? null;
    view('form_insert_short_code', '', ['name' => $name],true);
}

function key_val_search($atts){
    view('search_short_code', '', $atts,true);
}
