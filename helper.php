<?php
defined('ABSPATH') || exit;

/**
 * error log and debug
 *
 * @param array $in
 * @param boolean $die if true die system
 * @return void
 */
function kvdd($in, $die = true)
{
    // if ($_SERVER['REMOTE_ADDR'] == '185.186.242.170') {
    $line = @debug_backtrace()[0]["line"];
    $file = @debug_backtrace()[0]["file"];
    $id = "dump" . rand(1, 1000);
    echo "<div  style='width:auto;background:#01579B;color:#fff;text-align:center;cursor:pointer' onclick='$(\"#$id\").toggle()' > DUMP $file  :$line  </div> ";
    echo "<pre id='$id' style='margin:0 10px;padding:10px;border:1px solid #ccc;direction:ltr;overflow:auto;'>";
    var_dump($in);
    echo "</pre>";
    if ($die) die();
    // }
}

function doCurl($url, $data = [], $type = "POST", $header = [])
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
    if ($type == 'POST') {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        $header[] = 'Content-Type:application/json';
    }


    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return [
        $httpcode == 200,
        json_decode($result, JSON_UNESCAPED_UNICODE),
        $httpcode,
        $result
    ];
}

/**
 * function for render api key
 *
 * @return String
 */
function ApiKeyGen()
{
    return implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
}

/**
 * function for load view
 *
 * @param string $page
 * @param string $title
 * @param array $data
 * @return void
 */
function view($page, $title = '', $data = [],$disableCP=false)
{
    include 'view/layouts/header.php';
    include "view/pages/{$page}.php";
    if(!$disableCP)
        include 'view/layouts/footer.php';
}

/**
 * load model 
 *
 * @param string $model
 * @return Object
 */
function loadModel($model): object
{
    require_once __DIR__ . '/model/' . $model . '.php';
    return (new $model());
}

/**
 * load service 
 *
 * @param string $service
 * @return Object
 */
function loadService($service): object
{
    require_once __DIR__ . '/service/' . $service . '.php';
    return (new $service());
}

/**
 * make slug of integer
 */
function slug($str, $delimiter = '_')
{

    return  strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
}

function renderTagInput($in)
{
  
}
/**
 * return show tag html
 */
function renderTagShow($val, $type = 'text')
{
    switch ($type) {
        case 'file':
            $url = wp_get_attachment_url($val);
            return "<a href='{$url}' class='link' target='__blank'>view</a>";
        case 'color':
            return "<span style='background:{$val};padding:3px;text-shadow:0px 0px 2px #fff;border-radius:3px'> {$val} </span>";
        default:
            return $val;
    }
}

/**
 * upload file 
 *
 * @param string $name
 * @return array
 */
function upload(String $name): array
{
    $atachId = media_handle_upload($name, 0);
    return $atachId
        ? [1, $atachId]
        : [0, 'error in upload'];
}


function loadStyle($name, $file) {
    wp_enqueue_style('wp-key-val'.$name, plugin_dir_url(__FILE__) . 'view/style/'.$file);  
}

function old($name, $default=null){
    return $_POST[$name]?? $_POST[$name]??$default;
}