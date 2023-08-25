<?php
defined('ABSPATH') || exit;

require_once __DIR__.'/Model.php';
class Keys extends Model {

    function __construct()
    {
        parent::__construct('key_val_keys');   
    }
    /**
     * insert into keys and types
     *
     * @param array $data
     * @return object
     */
    function insert($data)
    {
        $tags = $data['tags'];
        unset($data['tags']);
        $type = parent::insert($data);
        
        $model = new Model('key_val_tag_type');
        foreach ($tags as $key => $val) {
            $model->insert([
                'tag_id' => $val,
                'type_id' => $type->id
            ]);
        }
        return $type;
    }
    
    function getTags($key_id){
        $model = new Model('key_val_tag_type');
        $tags = loadModel('Tags');
        return $model->query("SELECT * FROM {$model->table} t1 JOIN {$tags->table} t2 ON t1.tag_id = t2.id WHERE t1.type_id = {$key_id};");
    }

    function __getTags($key_id){
        $in = $this->getTags($key_id);
        $out = "";
        foreach ($in as $key => $val)
            $out .= "{$val->title}({$val->name}) - ";
        return $out;
        // kvdd($in,0);
    }
    
}