<?php
defined('ABSPATH') || exit;

class Model
{

    public $db;
    public $table;
    function __construct($table)
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $wpdb->prefix . $table;
    }

    /**
     * run query
     *
     * @param string $query
     * @return array
     */
    function query($query): array
    {
        return $this->db->get_results($query);
    }

    /**
     * get all of data
     *
     * @return void
     */
    function all()
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->query($query);
    }

    /**
     * find in query 
     *
     * @param array $conditions
     * @return void
     */
    function find($conditions = [])
    {
        $con = '';
        foreach ($conditions as $key => $val) {
            $con .= "{$key} = '{$val}' AND ";
        }
        $con = substr($con, 0, -4);
        $query = "SELECT * FROM {$this->table} WHERE {$con}";
        return $this->query($query);
    }
    /**
     * find in query 
     *
     * @param array $conditions
     * @return void
     */
    function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = {$id} ";
        return $this->query($query);
    }

    /**
     * update query
     *
     * @param array $data
     * @return void
     */
    function update($id, $data)
    {
        $set = '';
        foreach ($data as $key => $val)
            $set .= " `{$key}` = '{$val}' ,";
        $set = substr($set, 0, -1);
        $updateQuery = "UPDATE {$this->table}
         SET {$set}
         WHERE id = {$id}";
        return $this->query($updateQuery);
    }


    /**
     * update query
     *
     * @param array $data
     * @return object
     */
    function insert($data)
    {
        $insertQuery = "INSERT INTO {$this->table}
        (" . implode(',', array_keys($data)) . ")
        VALUES ('" . implode("','", $data) . "')
        ";
        $this->query($insertQuery);
        
        return $this->findById($this->db->insert_id)[0];
    }

    /**
     * delete query
     *
     * @param array $data
     * @return void
     */
    function delete($id, $fieldName = 'id')
    {
        $deleteQuery = "DELETE FROM {$this->table} where {$fieldName}={$id}";
        return $this->query($deleteQuery);;
    }

    /**
     * get multiple
     *
     * @param [type] $field
     * @param [type] $fields
     * @return void
     */
    function whereIn($field, $fields)
    {
        $fields = explode(",", $fields);

            $fieldsQuery = !$fields
            ? ''
            : "'" . implode("','", $fields) . "'";
        $query = "SELECT * FROM {$this->table} WHERE {$field} in ({$fieldsQuery}) ";
        return $this->query($query);
    }
}
