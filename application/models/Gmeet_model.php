<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gmeet_model extends MY_Model
{

    protected $table = "gmeet";

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data, $sections)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $data['session_id'] = $this->current_session;
        $this->db->insert($this->table, $data);
        $inserted_id = $this->db->insert_id();
        if (!empty($sections)) {
            $insert_section = array();
            foreach ($sections as $section_key => $section_value) {
                $insert_section[] = array('gmeet_id' => $inserted_id, 'cls_section_id' => $section_value);
            }
            $this->db->insert_batch('gmeet_sections', $insert_section);
        }
		$message = INSERT_RECORD_CONSTANT . " On ". $this->table ." id " . $inserted_id;
        $action = "Insert";
        $record_id = $inserted_id;
        $this->log($message, $record_id, $action);
			
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function addmeeting($data, $staff)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $data['session_id'] = $this->current_session;
        $this->db->insert('gmeet', $data);
        $insert_id = $this->db->insert_id();
        if (!empty($staff)) {
            $staff_list = array();
            foreach ($staff as $staff_key => $staff_value) {
                $staff_list[] = array('gmeet_id' => $insert_id, 'staff_id' => $staff_value);
            }
            $this->db->insert_batch('gmeet_staff', $staff_list);
        }
		
		$message = INSERT_RECORD_CONSTANT . " On gmeet id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);
			
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function get($id = null)
    {
        $this->db->select('conferences.*,for_create.name as `create_for_name,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,classes.class,sections.section')->from('conferences');
        $this->db->join('staff as for_create', 'for_create.id = conferences.staff_id', 'left');
        $this->db->join('staff as create_by', 'create_by.id = conferences.created_id');
        $this->db->join('classes', 'classes.id = conferences.class_id', 'left');
        $this->db->join('sections', 'sections.id = conferences.section_id', 'left');
        if ($id != null) {
            $this->db->where('conferences.id', $id);
        } else {
            $this->db->order_by('conferences.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }
 
    public function getByStaff($staff_id = null)
    {
        $this->db->select('gmeet.*,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,for_create.employee_id as `for_create_employee_id`,for_create_role.name as `for_create_role_name`,create_by_role.name as `create_by_role_name`,create_by.employee_id as `create_by_employee_id`')->from('gmeet');

        $this->db->join('staff as for_create', 'for_create.id = gmeet.staff_id');
        $this->db->join('staff as create_by', 'create_by.id = gmeet.created_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as `for_create_role`', 'for_create_role.id = staff_roles.role_id');
        $this->db->join('staff_roles as staff_create_by_roles', 'staff_create_by_roles.staff_id = create_by.id');
        $this->db->join('roles as `create_by_role`', 'create_by_role.id = staff_create_by_roles.role_id');
        $this->db->where('gmeet.session_id', $this->current_session);
        if ($staff_id != "") {
            $this->db->where('gmeet.staff_id', $staff_id);
        }

        $this->db->order_by('DATE(`gmeet`.`date`)', 'DESC');
        $this->db->order_by('gmeet.date', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $result_key => $result_value) {
                $result_value->{'classes'} = $this->getClassSectionByGmeetID($result_value->id);
            }
            return $result;
        }
        return false;
    }

    public function getClassSectionByGmeetID($gmeet_id)
    {
        $this->db->select('gmeet_sections.*,classes.class,sections.section')->from('gmeet_sections');
        $this->db->join('class_sections', 'class_sections.id = gmeet_sections.cls_section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('gmeet_sections.gmeet_id', $gmeet_id);
        $this->db->order_by('gmeet_sections.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getStaffMeeting($staff_id = null, $type = 'meeting')
    {

        if ($staff_id != "") {
            $sql   = "SELECT `gmeet`.*, `for_create`.`surname` as `create_for_surname`, `create_by`.`name` as `create_by_name`, `create_by`.`surname` as `create_by_surname` , `create_by_role`.`name` as `create_by_role_name`,`create_by`.`employee_id` as `create_by_employee_id` FROM `gmeet` LEFT JOIN `staff` as `for_create` ON `for_create`.`id` = `gmeet`.`staff_id` JOIN `staff` as `create_by` ON `create_by`.`id` = `gmeet`.`created_id`  JOIN `staff_roles` ON `staff_roles`.`staff_id` = `create_by`.`id` JOIN `roles` as `create_by_role` ON `create_by_role`.`id` = `staff_roles`.`role_id` WHERE `gmeet`.`id` in (SELECT `gmeet`.`id` FROM `gmeet` WHERE `gmeet`.`purpose`='" . $type . "' and created_id= " . $staff_id . " UNION SELECT `gmeet`.`id` FROM `gmeet_staff` INNER JOIN gmeet on gmeet.id=gmeet_staff.gmeet_id  WHERE gmeet_staff.staff_id=" . $staff_id . " order by id desc)";
            $query = $this->db->query($sql);
            return $query->result();
        } else {
            $this->db->select('gmeet.*,for_create.surname as `create_for_surname,create_by.name as `create_by_name`,create_by.surname as `create_by_surname,create_by_role.name as `create_by_role_name`,create_by.surname as `create_for_surname,create_by.employee_id as `create_by_employee_id`')->from('gmeet');
            $this->db->join('staff as for_create', 'for_create.id = gmeet.staff_id', 'left');
            $this->db->join('staff as create_by', 'create_by.id = gmeet.created_id');
            $this->db->join('staff_roles', 'staff_roles.staff_id = create_by.id');
            $this->db->join('roles as `create_by_role`', 'create_by_role.id = staff_roles.role_id');
            $this->db->where('gmeet.purpose', $type);
            $this->db->order_by('DATE(`gmeet`.`date`)', 'DESC');
            $this->db->order_by('gmeet.date', 'DESC');
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('id', $id);
        $this->db->delete('gmeet');
		
		$message = DELETE_RECORD_CONSTANT . " On gmeet id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
		
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getByClassSection($class_id, $section_id)
    {
        $this->db->select('gmeet.*,classes.class,sections.section,for_create.name as `create_for_name`,for_create.surname as `create_for_surname,for_create.employee_id as `for_create_employee_id`,for_create_role.name as `for_create_role_name`')->from('gmeet_sections');
        $this->db->join('gmeet', 'gmeet.id = gmeet_sections.gmeet_id');
        $this->db->join('class_sections', 'class_sections.id = gmeet_sections.cls_section_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->join('staff as for_create', 'for_create.id = gmeet.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = for_create.id');
        $this->db->join('roles as `for_create_role`', 'for_create_role.id = staff_roles.role_id');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        $this->db->where('gmeet.session_id', $this->current_session);
        $this->db->order_by('DATE(`gmeet`.`date`)', 'DESC');
        $this->db->order_by('gmeet.date', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function update($id, $data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('id', $id);
        $query = $this->db->update("gmeet", $data);
		
		$message = UPDATE_RECORD_CONSTANT . " On gmeet id " . $id;
        $action = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);
			
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getAllStaffByArray($staff = array())
    {
        $this->db->select("staff.*,staff_designation.designation,department.department_name as department, roles.id as role_id, roles.name as role");
        $this->db->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");
        $this->db->where_in('staff.id', $staff);
        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getMeetingStaff($gmeet_id)
    {
        $this->db->select('gmeet_staff.*,staff.name as `staff_name`,staff.surname as `staff_surname,roles.name as `role_name`,staff.employee_id')->from('gmeet_staff');
        $this->db->join('staff', 'staff.id = gmeet_staff.staff_id');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id');
        $this->db->join('roles', 'roles.id = staff_roles.role_id');
        $this->db->where('gmeet_id', $gmeet_id);
        $this->db->order_by('gmeet_staff.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getStudentByClassSectionID($class_section_id)
    {
        $this->db->select('student_session.transport_fees,students.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode , students.note, students.religion, students.cast, school_houses.house_name,   students.dob ,students.current_address, students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic , students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = students.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('class_sections', ' class_sections.class_id=classes.id and class_sections.section_id= sections.id');
        $this->db->where_in('class_sections.id', $class_section_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('students.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_urlBygmeetId($id){
        return $this->db->select('gmeet.url,gmeet.title,gmeet.date,gmeet.duration')->from('gmeet')->where('gmeet.id',$id)->get()->row_array();
    }

    public function get_meetingStaff($gmeet_id){
        
        $staff_list=$this->getMeetingStaff($gmeet_id);

        foreach ($staff_list as $key => $value) {
         $staff_ides[]=$value->staff_id;
        }

        return $this->getAllStaffByArray($staff_ides);
         
    }

}
