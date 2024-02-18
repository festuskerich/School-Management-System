<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gmeetsetting_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        $this->db->select('*');
        $this->db->from('gmeet_settings');
        $this->db->order_by('gmeet_settings.id');
        $query = $this->db->get();
        return $query->row();
    }

    public function add($data)
    {

        $this->db->trans_begin();

        $q = $this->db->get('gmeet_settings');

        if ($q->num_rows() > 0) {
            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('gmeet_settings', $data);
			$message = UPDATE_RECORD_CONSTANT . " On gmeet settings id " . $results->id;
            $action = "Update";
            $record_id = $results->id;
            $this->log($message, $record_id, $action);
			
        } else {

            $this->db->insert('gmeet_settings', $data);
			$id = $this->db->insert_id();
			$message = INSERT_RECORD_CONSTANT . " On gmeet settings id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

}
