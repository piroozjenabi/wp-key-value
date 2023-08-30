<?php
defined('ABSPATH') || exit;

require_once __DIR__ . '/Model.php';
class KeyVal extends Model
{

    function __construct()
    {
        parent::__construct('key_val');
    }

    /**
     * list for key val
     *
     * @return void
     */
    function list($limit = 400, $conditions = null, $keyIds = null, $witIndexes=true)
    {
        if($limit == 1) $limit =100;
        $tagService = loadService('TagService');

        $out = [];
        $table_key = $this->db->prefix . "key_val_keys";
        $table_tags = $this->db->prefix . "key_val_tags";
        $table_tag_val = $this->db->prefix . "key_val_tag_value";

        $groups = loadModel('Group');
        $con = 'WHERE 1=1 ';
        if($conditions)
            foreach ($conditions as $key => $val) {
                $con .= $val === null 
                ? " AND {$key} IS NULL"
                : " AND {$key} = '{$val}'";
            }
        if($keyIds)
            $con .= $keyIds ? " AND t1.key_id in ({$keyIds}) " : '';
            $query = "SELECT  t1.*, t2.*,
                t3.tag_id AS tag_id,t4.name AS tag_name,t4.title AS tag_title, t4.type AS tag_type, 
                t3.value AS tag_value ,t1.id AS vid , t1.created_at AS created_at,
                t1.created_by AS created_by,t1.created_type AS created_type,
                t5.group_id As group_id
                FROM 
                {$this->table} t1 
                JOIN $table_key t2 on t1.key_id=t2.id 
                LEFT JOIN $table_tag_val t3 on t3.key_val_id=t1.id 
                LEFT JOIN $table_tags t4 on t3.tag_id=t4.id 
                LEFT JOIN {$groups->pivot->table} t5 on t5.key_val_id = t1.id
                $con 
                order by t1.created_at desc limit $limit";
                // kvdd($query);
        $list = $this->query($query);

        $i = 1;
        $indexes = [];
        foreach ($list as $key => $val) {
            if (@!$out[$val->vid]){

                $indexes[] = $val->vid;
                $out[$val->vid] = [
                    'id' => $val->key_id,
                    'vid' => $val->vid,
                    'val' => $val->val,
                    'created_at' => $val->created_at,
                    'created_by' => $val->created_by,
                    'created_type' => $val->created_type,
                    'name' => $val->name,
                    'title' => $val->title,
                    'tag_type' => $val->tag_type,
                    'group_id' => $val->group_id,
                ];
            }
                
            @$out[$val->vid]['tags'][$val->tag_id]= [
                'name' => $val->tag_name,
                'title' => $val->tag_title,
                'value' => $val->tag_value,
                'type' => $val->tag_type
            ];
           
            if (@$val->tag_title)
                $out[$val->vid]['tags_print'][$val->tag_id] = "{$val->tag_title}=". $tagService->renderShow($val->tag_value,$val->tag_type);
            // kvdd($out,0);
            $i++;
        }
        if($witIndexes &&  $out)
            $out['indexes'] =$indexes;
            // kvdd($out);
        return $out;
    }

    /**
     * get by list of keys
     *
     * @param Array $list
     * @return void
     */
    function getListByKeys($list=null)
    {
        $keys = loadModel('Keys');
        $keyList = empty($list)
        ? $keys->all()
        : $keys->whereIn('name', $list);
        $ids = [];
        foreach ($keyList as $key => $val) {
            $ids[] = $val->id;
        }
        $idsConditions = '"' . implode('","', $ids) . '"';
        return $this->list(100, '', $idsConditions,false);
    }

    /**
     * insert into key val table
     */
    function insert($data,$type='SITE'){
        $current_user = wp_get_current_user();
        $data['created_type'] = 'API';
        if ($current_user->ID && $current_user->ID != 0) {
            $data['created_by'] = $current_user->ID;
            $data['created_type'] = 'SITE';
        }
        return parent::insert($data);
    }
}
