<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gmeet extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('gmeet_model', 'gmeethistory_model'));
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Gmeet');
        $data                  = array();
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $list                  = $this->gmeet_model->getByClassSection($student_current_class->class_id, $student_current_class->section_id);
        $data['conferences']   = $list;
        $this->load->view('layout/student/header');
        $this->load->view('user/gmeet/timetable', $data);
        $this->load->view('layout/student/footer');
    }

    public function add_history()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $student_id  = $this->customlib->getStudentSessionUserID();
            $data_insert = array(
                'gmeet_id'   => $this->input->post('id'),
                'student_id' => $student_id,
            );
            $this->gmeethistory_model->updatehistory($data_insert, 'student');
            $array = array('status' => 1, 'error' => '');
            echo json_encode($array);
        }
    }

    public function join($id)
    {
        $zoom_api_key    = "";
        $zoom_api_secret = "";
        $leaveUrl        = "user/conference";
        $live            = $this->gmeet_model->get($id);
        if ($live->api_type == "global") {
            $zoomsetting = $this->zoomsetting_model->get();
            if (!empty($zoomsetting)) {
                $zoom_api_key    = $zoomsetting->zoom_api_key;
                $zoom_api_secret = $zoomsetting->zoom_api_secret;
            }
        } else {
            $staff           = $this->staff_model->get($live->created_id);
            $zoom_api_key    = $staff['zoom_api_key'];
            $zoom_api_secret = $staff['zoom_api_secret'];
        }
        $meetingID                = json_decode($live->return_response)->id;
        $data['zoom_api_key']     = $zoom_api_key;
        $data['zoom_api_secret']  = $zoom_api_secret;
        $data['meetingID']        = $meetingID;
        $data['meeting_password'] = $live->password;
        $data['leaveUrl']         = $leaveUrl;
        $data['title']            = $live->title;
        $data['host']             = ($live->create_for_surname == "") ? $live->create_for_name : $live->create_for_name . " " . $live->create_for_surname;
        $student_id  = $this->customlib->getStudentSessionUserID();
        $data_insert = array(
            'conference_id' => $id,
            'student_id'    => $student_id,
        );

        $this->gmeethistory_model->updatehistory($data_insert, 'student');
        $this->load->view('user/gmeet/join', $data);
    }

    public function getlivestatus()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'id' => form_error('id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $conference_id = $this->input->post('id');
            $live          = $this->gmeet_model->get($conference_id);
            //========

            if ($live->api_type == "global") {
                $zoomsetting = $this->zoomsetting_model->get();
                if (!empty($zoomsetting)) {

                    $zoom_api_key    = $zoomsetting->zoom_api_key;
                    $zoom_api_secret = $zoomsetting->zoom_api_secret;
                }
            } else {
                $staff           = $this->staff_model->get($live->created_id);
                $zoom_api_key    = $staff['zoom_api_key'];
                $zoom_api_secret = $staff['zoom_api_secret'];
            }
            $params = array(
                'zoom_api_key'    => $zoom_api_key,
                'zoom_api_secret' => $zoom_api_secret,
            );
            $this->load->library('zoom_api', $params);
            $meetingID               = json_decode($live->return_response)->id;
            $api_Response            = $this->zoom_api->getMeeting($meetingID);
            $data['api_Response']    = $api_Response;
            $staff_id                = $this->customlib->getStaffID();
            $data['logged_staff_id'] = $staff_id;
            $data['live']            = $live;
            $data['page']            = $this->load->view('user/gmeet/_livestatus', $data, true);
            $array                   = array('status' => '1', 'page' => $data['page']);
            echo json_encode($data);
            //=====
        }
    }
}
