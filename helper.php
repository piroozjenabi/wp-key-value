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

function doCurl($url, $data=[], $type="POST", $header=[]){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
    if($type == 'POST'){
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE );
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
function ApiKeyGen(){
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
function view($page, $title='',$data =[]){
    include 'view/layouts/header.php';
    include "view/pages/{$page}.php";
    include 'view/layouts/footer.php';
}

/**
 * load model 
 *
 * @param string $model
 * @return Object
 */
function loadModel($model):object
{
    require_once __DIR__.'/model/'.$model.'.php';
    return (new $model());
}

/**
 * make slug of integer
 */
function slug($str, $delimiter = '_'){

    return  strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    

} 

function renderTagInput($in){
    $out = ""; 
        switch($in->type){
            case "text":
            case "number":
            case "date":
            case "file":
                $out .= "<input placeholder='{$in->title}' type='{$in->type}' name='{$in->name}' /> ";
            break;
            case "wooid":
                $out .= "<input placeholder='{$in->title}' type='text' name='{$in->name}' /> ";
            break;
            case "select":
                $tmp=json_decode(trim($in->params));
                $out.=" <select name='{$in->name}' placeholder='{$in->title}'>";
                foreach($tmp as $key => $val)
                    $out.="<option value='{$key}'>{$val}</option>";
                $out .="</select>";
            break;

        }

    return $out;
}
