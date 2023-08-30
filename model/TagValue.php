<?php
defined('ABSPATH') || exit;

require_once __DIR__.'/Model.php';
class TagValue extends Model {

    function __construct()
    {
        parent::__construct('key_val_tag_value');   
    }

    /**
     * insert bulk
     *
     * @param int $key_val_id
     * @param array $data
     * @return void
     */
    function insertBulk($key_val_id,$data){
        foreach($data as $key => $value){
            if(!$value) continue;
            if(!$key) continue;
            
            $this->insert([
                'key_val_id' => $key_val_id,
                'tag_id' => intval($key),
                'value' => $value
            ]);
        }
    }   
    
    
    function updateOrInsert($data){
        $find = parent::find([$data]);
        return $find 
        ? parent::update($data['id'],$data)
        : parent::insert($data);
    }
}