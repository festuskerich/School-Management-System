<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gmeet extends Admin_Controller
{

    public $gmeet_version = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->library('gmeet_mail_sms');
        $this->load->library('Google');
        $this->load->model(array('gmeet_model', 'gmeethistory_model', 'gmeetsetting_model'));
        $this->load->config('gmeet-config');
        $this->gmeet_version = $this->config->item('version');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        if ($this->router->fetch_method() != "index") {
            $this->auth->addonchk('ssglc', site_url('admin/gmeet'));
        }

    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('gmeet_setting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'gmeet');
        $this->session->set_userdata('sub_menu', 'gmeet/gmeet_setting');
        $data              = array();
        $data['title']     = 'Gmeet Setting';
        $data['version']   = $this->gmeet_version;
        $addon_ver         = $this->config->item('addon_ver');
        $data['addon_ver'] = $addon_ver;

        $setting = $this->gmeetsetting_model->get();
        if (empty($setting)) {
            $setting             = new stdClass();
            $setting->api_key    = "";
            $setting->api_secret = "";
            $setting->use_api    = 0;
        }

        $data['setting'] = $setting;
        $this->form_validation->set_rules('use_api', $this->lang->line('use_google_api'), 'required|trim|xss_clean');
        if ($this->input->post('use_api')) {
            $this->form_validation->set_rules('api_key', $this->lang->line('api_key'), 'required|trim|xss_clean');
            $this->form_validation->set_rules('api_secret', $this->lang->line('api_secret'), 'required|trim|xss_clean');
        }
        if ($this->form_validation->run() === true) {

            $data_insert = array(
                'api_key'    => $this->input->post('api_key'),
                'api_secret' => $this->input->post('api_secret'),
                'use_api'    => $this->input->post('use_api'),
            );
            $this->gmeetsetting_model->add($data_insert);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/gmeet');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/gmeet/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function timetable()
    {
        //======
        $userdata = array(
            'back_url' => current_url(),
        );
        $this->session->set_userdata($userdata);
        //======
        $this->session->set_userdata('top_menu', 'gmeet');
        $this->session->set_userdata('sub_menu', 'gmeet/live_class');
        $data             = array();
        $role             = json_decode($this->customlib->getStaffRole());
        $gmeet_setting    = $this->gmeetsetting_model->get();
        $data['auth_url'] = "";

        $class = $this->class_model->get();
        if (!empty($gmeet_setting) && $gmeet_setting->use_api) {
            $client           = $this->google->client();
            $auth_url         = $client->createAuthUrl();
            $data['auth_url'] = $auth_url;
        }
        $data['classlist']       = $class;
        $data['role']            = $role;
        $staff_id                = $this->customlib->getStaffID();
        $data['logged_staff_id'] = $staff_id;
        $data['gmeet_setting']   = $gmeet_setting;
        $data['link_status']     = 0;
        if (!empty($gmeet_setting) && $gmeet_setting->use_api && !$this->session->has_userdata('google_token')) {
            $data['link_status'] = 1;
        }
        if ($role->id == 2) {
            $stafflist         = $this->staff_model->getEmployee(2);
            $data['stafflist'] = $stafflist;
            $data['timetable'] = array();
            $days              = $this->customlib->getDaysname();
            $data['gmeets']    = $this->gmeet_model->getByStaff($this->customlib->getStaffID());
            $userdata          = $this->customlib->getUserData();
            $role_id           = $userdata["role_id"];
            $condition         = "";
            foreach ($days as $day_key => $day_value) {
                $timetableid = "";
                $concate     = "no";
                if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                    $myclasssubjects = $this->subjecttimetable_model->getByStaffClassTeacherandDay($this->customlib->getStaffID(), $day_key);

                    if (!empty($myclasssubjects[0]->timetable_id)) {
                        $timetableid = $myclasssubjects[0]->timetable_id;
                        $concate     = "yes";
                    }
                }

                $mysubjects = $this->subjecttimetable_model->getByTeacherSubjectandDay($this->customlib->getStaffID(), $day_key);
                if (!empty($mysubjects[0]->timetable_id)) {
                    if ($concate == 'yes') {
                        $timetableid = $timetableid . "," . $mysubjects[0]->timetable_id;
                    } else {
                        $timetableid = $mysubjects[0]->timetable_id;
                    }
                }

                if ($timetableid == '') {
                    $condition = " and subject_timetable.id in(0) ";
                } else {
                    $condition = " and subject_timetable.id in(" . $timetableid . ") ";
                }
                $data['timetable'][$day_value] = $this->subjecttimetable_model->getSyllabussubject($staff_id, $day_key, $condition);
            }
        } else {

            $data['gmeets'] = $this->gmeet_model->getByStaff();
        }

        $this->load->view('layout/header');
        if ($role->id == 2) {
            $this->load->view('admin/gmeet/timetable', $data);
        } else {
            $roles         = $this->role_model->get();
            $data['roles'] = $roles;
            $this->load->view('admin/gmeet/stafftimetable', $data);
        }
        $this->load->view('layout/footer');
    }

    public function delete($id)
    {
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $this->lang->line('delete_message') . '</div>');
        $this->gmeet_model->remove($id);
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    public function addByOther()
    {
        $response      = array();
        $gmeet_setting = $this->gmeetsetting_model->get();

        $this->form_validation->set_rules('title', $this->lang->line('class_title'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('class_date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id[]', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('staff_id', $this->lang->line('staff'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('class_duration_minutes'), 'required|trim|xss_clean');
        if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
            $this->form_validation->set_rules('url', 'URL', 'required|trim|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $form_error = array(
                'title'      => form_error('title'),
                'date'       => form_error('date'),
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id[]'),
                'staff_id'   => form_error('staff_id'),
                'role_id'    => form_error('role_id'),
                'duration'   => form_error('duration'),

            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $form_error['url'] = form_error('url');
            }
            $response = array('status' => 0, 'error' => $form_error);
        } else {
            $status = true;

            $insert_array = array(
                'staff_id'    => $this->input->post('staff_id'),
                'title'       => $this->input->post('title'),
                'date'        => date('Y-m-d H:i:s', $this->customlib->dateTimeformat($this->input->post('date'))),
                'duration'    => $this->input->post('duration'),
                'created_id'  => $this->customlib->getStaffID(),
                'description' => $this->input->post('description'),
                'timezone'    => $this->customlib->getTimeZone(),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $insert_array['url'] = $this->input->post('url');
            } else {
                $event = $this->google->createEvent($insert_array);
                if ($event) {

                    $insert_array['url']      = $event->hangoutLink;
                    $insert_array['type']     = 'google_api';
                    $insert_array['api_data'] = json_encode($event);
                } else {
                    $status = false;
                }
            }
            if ($status) {
                $is_affected = $this->gmeet_model->add($insert_array, $this->input->post('section_id[]'));
                if ($is_affected) {
                    //==============
                    $sender_details = array('class_section_id' => $this->input->post('section_id[]'), 'title' => $this->input->post('title'), 'date' => $this->input->post('date'), 'duration' => $this->input->post('duration'));
                    $this->gmeet_mail_sms->mailsms('gmeet_online_classes', $sender_details);
                    //================
                    $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
                } else {
                    $response = array('status' => 0, 'error' => array('Something went wrong.'));
                }
            } else {
                $response = array('status' => 0, 'error' => array('Something went wrong.'));
            }
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function addByClassTeacher()
    {
        $response      = array();
        $gmeet_setting = $this->gmeetsetting_model->get();
        $this->form_validation->set_rules('title', $this->lang->line('class_title'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('class_date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id[]', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('staff_id', $this->lang->line('staff'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('class_duration_minutes'), 'required|trim|xss_clean');
        if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
            $this->form_validation->set_rules('url', 'URL', 'required|trim|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $data = array(
                'title'      => form_error('title'),
                'date'       => form_error('date'),
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id[]'),
                'staff_id'   => form_error('staff_id'),
                'duration'   => form_error('duration'),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $form_error['url'] = form_error('url');
            }
            $response = array('status' => 0, 'error' => $data);
        } else {
            $status       = true;
            $insert_array = array(
                'staff_id'    => $this->input->post('staff_id'),
                'title'       => $this->input->post('title'),
                'date'        => date('Y-m-d H:i:s', $this->customlib->dateTimeformat($this->input->post('date'))),
                'duration'    => $this->input->post('duration'),
                'created_id'  => $this->customlib->getStaffID(),
                'description' => $this->input->post('description'),
                'timezone'    => $this->customlib->getTimeZone(),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $insert_array['url'] = $this->input->post('url');
            } else {
                $event = $this->google->createEvent($insert_array);
                if ($event) {

                    $insert_array['url']      = $event->hangoutLink;
                    $insert_array['type']     = 'google_api';
                    $insert_array['api_data'] = json_encode($event);
                } else {
                    $status = false;
                }
            }
            if ($status) {
                $is_affected = $this->gmeet_model->add($insert_array, $this->input->post('section_id[]'));
                if ($is_affected) {
                    //==============
                    $sender_details = array('class_section_id' => $this->input->post('section_id[]'), 'title' => $this->input->post('title'), 'date' => $this->input->post('date'), 'duration' => $this->input->post('duration'));
                    $this->gmeet_mail_sms->mailsms('gmeet_online_classes', $sender_details);
                    //================
                    $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
                } else {
                    $response = array('status' => 0, 'error' => array('Something went wrong.'));
                }
            } else {
                $response = array('status' => 0, 'error' => array('Something went wrong.'));
            }

        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function add()
    {
        $response      = array();
        $gmeet_setting = $this->gmeetsetting_model->get();
        $this->form_validation->set_rules('title', $this->lang->line('class_title'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('class_date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_id[]', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('class_duration_minutes'), 'required|trim|xss_clean');

        if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
            $this->form_validation->set_rules('url', 'URL', 'required|trim|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $data = array(
                'title'      => form_error('title'),
                'date'       => form_error('date'),
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id[]'),
                'duration'   => form_error('duration'),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $form_error['url'] = form_error('url');
            }
            $response = array('status' => 0, 'error' => $data);
        } else {
            $status = true;

            $insert_array = array(
                'staff_id'    => $this->customlib->getStaffID(),
                'title'       => $this->input->post('title'),
                'date'        => date('Y-m-d H:i:s', $this->customlib->dateTimeformat($this->input->post('date'))),
                'duration'    => $this->input->post('duration'),
                'created_id'  => $this->customlib->getStaffID(),
                'description' => $this->input->post('description'),
                'timezone'    => $this->customlib->getTimeZone(),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $insert_array['url'] = $this->input->post('url');
            } else {

                if (!empty($gmeet_setting) && $gmeet_setting->use_api && !$this->session->has_userdata('google_token')) {
                    $status = false;
                } else {
                    $event = $this->google->createEvent($insert_array);

                    if ($event) {
                        $insert_array['url']      = $event->hangoutLink;
                        $insert_array['type']     = 'google_api';
                        $insert_array['api_data'] = json_encode($event);
                    } else {
                        $status = false;
                    }
                }

            }
            if ($status) {
                $is_affected = $this->gmeet_model->add($insert_array, $this->input->post('section_id[]'));
                if ($is_affected) {
                    //==============
                    $sender_details = array('class_section_id' => $this->input->post('section_id[]'), 'title' => $this->input->post('title'), 'date' => $this->input->post('date'), 'duration' => $this->input->post('duration'));
                    $this->gmeet_mail_sms->mailsms('gmeet_online_classes', $sender_details);
                    //================
                    $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
                } else {
                    $response = array('status' => 0, 'error' => array('Something went wrong.'));
                }
            } else {
                $response = array('status' => 0, 'error' => array('Something went wrong.'));
            }
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function meeting()
    {
        $this->session->set_userdata('top_menu', 'gmeet');
        $this->session->set_userdata('sub_menu', 'gmeet/live_meeting');
        //======
        $userdata = array(
            'back_url' => current_url(),
        );
        $this->session->set_userdata($userdata);
        //======
        $data             = array();
        $gmeet_setting    = $this->gmeetsetting_model->get();
        $data['auth_url'] = "";
        if (!empty($gmeet_setting) && $gmeet_setting->use_api) {
            $client           = $this->google->client();
            $auth_url         = $client->createAuthUrl();
            $data['auth_url'] = $auth_url;
        }
        $role                  = json_decode($this->customlib->getStaffRole());
        $data['role']          = $role;
        $data['gmeet_setting'] = $gmeet_setting;
        $data['link_status']   = 0;
        if (!empty($gmeet_setting) && $gmeet_setting->use_api && !$this->session->has_userdata('google_token')) {
            $data['link_status'] = 1;
        }
        $data['logged_staff_id'] = $this->customlib->getStaffID();
        if ($role->id == 7) {
            $data['meetings'] = $this->gmeet_model->getStaffMeeting();
        } else {
            $data['meetings'] = $this->gmeet_model->getStaffMeeting($data['logged_staff_id']);
        }
        $data['staffList'] = $this->staff_model->get();
        $this->load->view('layout/header');
        $this->load->view('admin/gmeet/meeting', $data);
        $this->load->view('layout/footer');
    }

    public function addMeeting()
    {
        $response      = array();
        $gmeet_setting = $this->gmeetsetting_model->get();
        $this->form_validation->set_rules('title', $this->lang->line('meeting') . ' ' . $this->lang->line('title'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('meeting') . ' ' . $this->lang->line('date'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('meeting_duration_minutes'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('staff[]', $this->lang->line('staff'), 'required|trim|xss_clean');
        if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
            $this->form_validation->set_rules('url', $this->lang->line('url'), 'required|trim|xss_clean');
        }
        if ($this->form_validation->run() == false) {
            $form_error = array(
                'title'    => form_error('title'),
                'date'     => form_error('date'),
                'staff[]'  => form_error('staff[]'),
                'duration' => form_error('duration'),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $form_error['url'] = form_error('url');
            }
            $response = array('status' => 0, 'error' => $form_error);
        } else {
            //=======
            $status       = true;
            $insert_array = array(
                'title'       => $this->input->post('title'),
                'date'        => date('Y-m-d H:i:s', $this->customlib->dateTimeformat($this->input->post('date'))),
                'duration'    => $this->input->post('duration'),
                'created_id'  => $this->customlib->getStaffID(),
                'description' => $this->input->post('description'),
                'purpose'     => 'meeting',
                'timezone'    => $this->customlib->getTimeZone(),
            );
            if (empty($gmeet_setting) || !$gmeet_setting->use_api) {
                $insert_array['url'] = $this->input->post('url');
            } else {
                $event = $this->google->createEvent($insert_array);
                if ($event) {

                    $insert_array['url']      = $event->hangoutLink;
                    $insert_array['type']     = 'google_api';
                    $insert_array['api_data'] = json_encode($event);
                } else {
                    $status = false;
                }
            }
            if ($status) {
                $staff       = $this->input->post('staff[]');
                $is_affected = $this->gmeet_model->addmeeting($insert_array, $staff);
                if ($is_affected) {
                    $staff_mail_sms_list = $this->gmeet_model->getAllStaffByArray($staff);
                    if (!empty($staff_mail_sms_list)) {
                        $sender_details = array();
                        foreach ($staff_mail_sms_list as $staff_mail_sms_list_key => $staff_mail_sms_list_value) {
                            $sender_details[] = array(
                                'title'       => $this->input->post('title'),
                                'date'        => $this->input->post('date'),
                                'duration'    => $this->input->post('duration'),
                                'employee_id' => $staff_mail_sms_list_value->employee_id,
                                'department'  => $staff_mail_sms_list_value->department,
                                'designation' => $staff_mail_sms_list_value->designation,
                                'name'        => ($staff_mail_sms_list_value->surname == "") ? $staff_mail_sms_list_value->name : $staff_mail_sms_list_value->name . " " . $staff_mail_sms_list_value->surname,
                                'contact_no'  => $staff_mail_sms_list_value->contact_no,
                                'email'       => $staff_mail_sms_list_value->email,
                            );
                        }
                        $this->gmeet_mail_sms->mailsms('gmeet_online_meeting', $sender_details);
                    }
                    $response = array('status' => 1, 'message' => $this->lang->line('success_message'));
                } else {
                    $response = array('status' => 0, 'error' => array('Something went wrong.'));
                }
            } else {
                $response = array('status' => 0, 'error' => array('Something went wrong.'));
            }
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function getcredential()
    {
        $response                    = array();
        $staff                       = $this->staff_model->get($this->customlib->getStaffID());
        $response['zoom_api_key']    = $staff['zoom_api_key'];
        $response['zoom_api_secret'] = $staff['zoom_api_secret'];
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function chgstatus()
    {
        $response = array();

        $this->form_validation->set_rules('gmeet_id', $this->lang->line('id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('chg_status', $this->lang->line('status'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'gmeet_id'   => form_error('gmeet_id'),
                'chg_status' => form_error('chg_status'),
            );
            $response = array('status' => 0, 'error' => $data);
        } else {
            $insert_array = array(
                'status' => $this->input->post('chg_status'),
            );
            $insert_id = $this->gmeet_model->update($this->input->post('gmeet_id'), $insert_array);
            $response  = array('status' => 1, 'message' => $this->lang->line('update_message'));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    public function meeting_report()
    {
        $this->session->set_userdata('top_menu', 'gmeet');
        $this->session->set_userdata('sub_menu', 'gmeet/meeting_report');
        $data                    = array();
        $staff_id                = $this->customlib->getStaffID();
        $data['logged_staff_id'] = $staff_id;
        $data['meetingList']     = $this->gmeethistory_model->getmeeting();
        $this->load->view('layout/header');
        $this->load->view('admin/gmeet/meeting_report', $data);
        $this->load->view('layout/footer');
    }

    public function class_report()
    {
        $this->session->set_userdata('top_menu', 'gmeet');
        $this->session->set_userdata('sub_menu', 'gmeet/class_report');
        $data['title']           = 'Class Report';
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $staff_id                = $this->customlib->getStaffID();
        $data['logged_staff_id'] = $staff_id;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        } else {
            $class_id              = $this->input->post('class_id');
            $section_id            = $this->input->post('section_id');
            $data['class_id']      = $class_id;
            $data['section_id']    = $section_id;
            $data['liveclassList'] = $this->gmeethistory_model->getclass($class_id, $section_id);
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/gmeet/class_report', $data);
        $this->load->view('layout/footer', $data);
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
            $staff_id    = $this->customlib->getStaffID();
            $data_insert = array(
                'gmeet_id' => $this->input->post('id'),
                'staff_id' => $staff_id,
            );
            $this->gmeethistory_model->updatehistory($data_insert, 'staff');
            $array = array('status' => 1, 'error' => '');
            echo json_encode($array);
        }
    }

    public function getViewerList()
    {
        $recordid     = $this->input->post('recordid');
        $type         = $this->input->post('type');
        $data['type'] = 'staff';
        if (isset($type)) {
            $data['type']         = $type;
            $class_id             = $this->input->post('class_id');
            $section_id           = $this->input->post('section_id');
            $data['viewerDetail'] = $this->gmeethistory_model->getLiveStudent($recordid, $class_id, $section_id);
        } else {
            $data['viewerDetail'] = $this->gmeethistory_model->getMeetingStaff($recordid);
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['page']        = $this->load->view('admin/gmeet/_partialviewerlist', $data, true);
        echo json_encode($data);
    }

    public function getMeetingStaff()
    {
        $id                   = $this->input->post('id');
        $data['viewerDetail'] = $this->gmeet_model->getMeetingStaff($id);
        $data['page']         = $this->load->view('admin/gmeet/_partialmeetingstaff', $data, true);
        echo json_encode($data);
    }

    public function authenticate()
    {
        $client   = $this->google->client();
        $back_url = $this->session->userdata('back_url');
        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $token    = $client->getAccessToken();
            $userdata = array(
                'google_token' => $token,
            );
            $this->session->set_userdata($userdata);
            redirect($back_url, 'refresh');
        } else {
            redirect($back_url, 'refresh');
        }
    }

    public function start($id, $type)
    {
        $result = $this->gmeet_model->get_urlBygmeetId($id);

        if ($type == 'meeting') {

            $staff_mail_sms_list = $this->gmeet_model->get_meetingStaff($id);
            if (!empty($staff_mail_sms_list)) {
                $sender_details = array();
                foreach ($staff_mail_sms_list as $staff_mail_sms_list_key => $staff_mail_sms_list_value) {
                    $sender_details[] = array(
                        'title'       => $result['title'],
                        'date'        => $result['date'],
                        'duration'    => $result['duration'],
                        'employee_id' => $staff_mail_sms_list_value->employee_id,
                        'department'  => $staff_mail_sms_list_value->department,
                        'designation' => $staff_mail_sms_list_value->designation,
                        'name'        => ($staff_mail_sms_list_value->surname == "") ? $staff_mail_sms_list_value->name : $staff_mail_sms_list_value->name . " " . $staff_mail_sms_list_value->surname,
                        'contact_no'  => $staff_mail_sms_list_value->contact_no,
                        'email'       => $staff_mail_sms_list_value->email,
                    );
                }
                $this->gmeet_mail_sms->mailsms('gmeet_online_meeting_start', $sender_details);
            }
        } else {

            $class_section = $this->gmeet_model->getClassSectionByGmeetID($id);
            foreach ($class_section as $key => $value) {
                $sender_details = array(
                    'class_section_id' => $value->cls_section_id,
                    'title'            => $result['title'],
                    'date'             => $result['date'],
                    'duration'         => $result['duration'],
                );

                $this->gmeet_mail_sms->mailsms('gmeet_online_classes_start', $sender_details);
            }

        }

        $url = $result['url'];

        header("Location: $url");

    }
}
