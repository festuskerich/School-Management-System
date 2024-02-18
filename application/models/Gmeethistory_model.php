<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gmeethistory_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function updatehistory($data, $type) {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('gmeet_id', $data['gmeet_id']);
        if ($type == "student") {
            $this->db->where('student_id', $data['student_id']);
        } elseif ($type == "staff") {
            $this->db->where('staff_id', $data['staff_id']);
        }

        $q = $this->db->get('gmeet_history');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            $total_hit = $row->total_hit + 1;
            $data['total_hit'] = $total_hit;
            $this->db->where('id', $row->id);
            $this->db->update('gmeet_history', $data);
			$message = UPDATE_RECORD_CONSTANT . " On gmeet history id " . $row->id;
            $action = "Update";
            $record_id = $row->id;
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('gmeet_history', $data);
			$id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On gmeet history id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getmeeting() {
        $sql = "SELECT gmeet.*,(SELECT COUNT(*) FROM gmeet_history WHERE gmeet_history.gmeet_id=gmeet.id) as `total_viewers`,`create_by`.`name` as `create_by_name`, `create_by`.`surname` as `create_by_surname`  FROM `gmeet` JOIN `staff` as `create_by` ON `create_by`.`id` = `gmeet`.`created_id` WHERE purpose='meeting' and status=2 ORDER BY DATE(`gmeet`.`date`) DESC, `gmeet`.`date` ASC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getclass($class_id, $section_id) {
        $sql = "SELECT gmeet.*,gmeet_sections.id as gmeet_section_id,create_by.employee_id as create_bystaffid,for_create.employee_id as for_creatstaffid,(SELECT COUNT(*) FROM gmeet_history INNER JOIN students on students.id=gmeet_history.student_id INNER JOIN student_session on student_session.student_id=students.id WHERE student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id= " . $this->db->escape($section_id) . " and gmeet_history.gmeet_id=gmeet.id) as `total_viewers`,`create_by`.`name` as `create_by_name`, `create_by`.`surname` as `create_by_surname`,`for_create`.`name` as `for_create_name`, `for_create`.`surname` as `for_create_surname`,roles.name as `create_by_role_name`,for_create_role.name as `create_for_role_name` FROM `gmeet` JOIN `staff` as `create_by` ON `create_by`.`id` = `gmeet`.`created_id` JOIN `staff` as `for_create` ON `for_create`.`id` = `gmeet`.`staff_id` INNER JOIN staff_roles on staff_roles.staff_id=`gmeet`.`created_id` INNER join roles on roles.id =staff_roles.role_id INNER JOIN staff_roles as `for_create_staff_role` on for_create_staff_role.staff_id=`gmeet`.`staff_id` INNER join roles as `for_create_role`on for_create_role.id =for_create_staff_role.role_id INNER JOIN gmeet_sections on gmeet.id=gmeet_sections.gmeet_id INNER JOIN class_sections on class_sections.id =gmeet_sections.cls_section_id WHERE purpose='class' and status=2 and gmeet.session_id=" . $this->current_session . " and class_sections.class_id = " . $this->db->escape($class_id) . " and class_sections.section_id= " . $this->db->escape($section_id) . " ORDER BY DATE(`gmeet`.`date`) DESC, `gmeet`.`date` DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getMeetingStaff($gmeet_id) {
        $this->db->select('gmeet_history.*,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,roles.name as `role_name`,for_create.employee_id')->from('gmeet_history');
        $this->db->join('staff as for_create', 'for_create.id = gmeet_history.staff_id');
        $this->db->join('staff_roles', 'staff_roles.id = for_create.id');
        $this->db->join('roles', 'roles.id = staff_roles.role_id');
        $this->db->where('gmeet_id', $gmeet_id);
        $this->db->order_by('gmeet_history.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getLiveStudent($gmeet_id,$class_id,$section_id) {
        $this->db->select('gmeet_history.*,student_session.class_id,student_session.section_id,students.admission_no, students.roll_no,students.admission_date,students.firstname,  students.lastname,students.middlename,students.image,students.mobileno, students.email,students.father_name')->from('gmeet_history');
        $this->db->join('students', 'students.id = gmeet_history.student_id');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('gmeet_id', $gmeet_id);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->order_by('gmeet_history.id');
        $query = $this->db->get();
        return $query->result();
    }

}
