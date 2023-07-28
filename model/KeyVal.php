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
    function list($limit = 400, $conditions = null, $keyIds = null)
    {
        $out = [];
        $table_key = $this->db->prefix . "key_val_keys";
        $table_tags = $this->db->prefix . "key_val_tags";
        $table_tag_val = $this->db->prefix . "key_val_tag_value";

        $con = 'WHERE 1=1 ';
        foreach ($conditions as $key => $val) {
            $con .= " AND {$key} = '{$val}'";
        }
        $con .= $keyIds ? " AND t1.key_id in ({$keyIds}) " : '';
        $list = $this->query(
            "SELECT  t1.*, t2.*,
                t4.id AS tag_id,t4.name AS tag_name,t4.title AS tag_title,
                t3.value AS tag_value ,t1.id AS vid , t1.created_at AS created_at
                FROM 
                {$this->table} t1 
                JOIN $table_key t2 on t1.key_id=t2.id 
                LEFT JOIN $table_tag_val t3 on t3.key_val_id=t1.id 
                LEFT JOIN $table_tags t4 on t3.tag_id=t4.id 
                $con 
                order by t1.created_at desc limit $limit"
        );

        $i = 1;
        
        foreach ($list as $key => $val) {
            if (@!$out[$val->vid])
                $out[$val->vid] = [
                    'id' => $val->key_id,
                    'vid' => $val->vid,
                    'val' => $val->val,
                    'created_at' => $val->created_at,
                    'name' => $val->name,
                    'title' => $val->title
                ];

            @$out[$val->vid]['tags'][$val->tag_id] = [
                'name' => $val->tag_name,
                'title' => $val->tag_title,
                'value' => $val->tag_value
            ];
            if (@$val->tag_title)
                $out[$val->vid]['tags_print'][$val->tag_id] = "{$val->tag_title}={$val->tag_value}";
            $i++;
        }

        return $out;
    }

    /**
     * get by list of keys
     *
     * @param Array $list
     * @return void
     */
    function getListByKeys($list)
    {
        $keys = loadModel('Keys');
        $keyList = $keys->whereIn('name', $list);
        $ids = [];
        foreach ($keyList as $key => $val) {
            $ids[] = $val->id;
        }
        $idsConditions = '"' . implode('","', $ids) . '"';
        return $this->list(100, '', $idsConditions);
    }
}