<?php
defined('ABSPATH') || exit;

require_once __DIR__.'/Model.php';
class Group extends Model {

    public $pivot = null;
    function __construct()
    {
        parent::__construct('key_val_group');   
        $this->pivot = new Model('key_val_group_val');
    }

    /**
     * insert to group
     */
    function insert($data){
        $group = parent::insert($data);
        if($data['name'] == 'tmp' )
            parent::update($group->id, ['name' => $group->id]);
        return $group;
    }


    /**
     * get by list of keys
     *
     * @param int $group
     * @return array
     */
    function getListByGroup($group = null)
    {
        $keyVal = loadModel('KeyVal');
        
        return $keyVal->list(200,['t5.group_id' => $group?$group:null], null, false);
    }

    

   
}