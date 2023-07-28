<?php
defined('ABSPATH') || exit;

################################# [ACTIONS] ################################# 

add_action('rest_api_init', function () {
    register_rest_route(
        'key_val/v1',
        '/test',
        ['methods' => 'GET', 'callback' => 'test',]


    );
    register_rest_route(
        'key_val/v1',
        '/last',
        ['methods' => 'GET', 'callback' => 'get_last',]


    );

    register_rest_route(
        'key_val/v1',
        '/history',
        ['methods' => 'GET', 'callback' => 'get_history',]


    );
});

################################# [FUNCTIONS] ################################# 

function test(){
    return [
        'data' => [ ],
        'msg' => 'test is ok .'
    ];
}

function get_last(WP_REST_Request $request)
{
    $params = $request->get_params();
    $name = $params['name']??'';
    $apiKey = $params['api-key']??'';

    //check api key
    $keys = loadModel('Keys');
    $key = $keys->find(['name' => $name])[0];
    if(!$key)
        return ['error' => 'Key not found !'];
    if(isset($key->api_key) && $key->api_key && $key->api_key != $apiKey)
        return ['error' => 'api-key is not valid'];
    $keyVal = loadModel('KeyVal');
    $result = $keyVal->list(1,[
        "t2.name" => $name
    ]);
    
    return $result[array_keys($result)[0]]??['val' => '0', 'msg' => 'data not found'];
}

function get_history(WP_REST_Request $request)
{
    $params = $request->get_params();
    $name = $params['name'] ?? '';
    $apiKey = $params['api-key'] ?? '';

    //check api key
    $keys = loadModel('Keys');
    $key = $keys->find(['name' => $name])[0];
    if (!$key)
        return ['error' => 'Key not found !'];
    if (isset($key->api_key) && $key->api_key && $key->api_key != $apiKey)
        return ['error' => 'api-key is not valid'];

    $keyVal = loadModel('KeyVal');
    return $keyVal->list(50, [
        "t2.name" => $name
    ]);
}
