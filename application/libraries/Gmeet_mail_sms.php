<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gmeet_mail_sms {

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('mailer');
        $this->config_mailsms = $this->CI->config->item('mailsms');
    }

    public function mailsms($send_for, $sender_details) {

        $chk_mail_sms = $this->CI->customlib->sendMailSMS($send_for);


        if (!empty($chk_mail_sms)) {
            if ($send_for == "gmeet_online_classes") {

                $this->sendGmeetOnlineClass($chk_mail_sms, $sender_details, $chk_mail_sms['template'],$chk_mail_sms['template_id']);
            } elseif ($send_for == "gmeet_online_meeting") {

                $this->sendGmeetMeeting($chk_mail_sms, $sender_details, $chk_mail_sms['template'],$chk_mail_sms['template_id']);
            }elseif ($send_for == "gmeet_online_meeting_start") {

                $this->sendGmeetMeeting($chk_mail_sms, $sender_details, $chk_mail_sms['template'],$chk_mail_sms['template_id']);
            }elseif ($send_for == "gmeet_online_classes_start") {

                $this->sendGmeetOnlineClass($chk_mail_sms, $sender_details, $chk_mail_sms['template'],$chk_mail_sms['template_id']);
            }
        }
    }

    public function sendGmeetOnlineClass($chk_mail_sms, $student_details, $template,$template_id) {

        $student_guardian_sms_list = array();
        $student_sms_list = array();
        $student_email_list = array();
        $student_guardian_email_list = array();
        $student_notification_list = array();
        $student_guardian_notification_list = array();
        $sms_detail = $this->CI->smsconfig_model->getActiveSMS();
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $title = $student_details['title'];
            $date = $student_details['date'];
            $duration = $student_details['duration'];
            $class_section_id = $student_details['class_section_id'];
            $student_list = $this->CI->gmeet_model->getStudentByClassSectionID($class_section_id);

            if (!empty($student_list)) {
                foreach ($student_list as $student_key => $student_value) {

                    if ($student_value['parent_app_key'] != "") {
                        $student_guardian_notification_list[] = array(
                            'app_key' => $student_value['parent_app_key'],
                            'class' => $student_value['class'],
                            'section' => $student_value['section'],
                            'title' => $title,
                            'date' => $date,
                            'duration' => $duration,
                            'admission_no' => $student_value['admission_no'],
                            'student_name' => $student_value['firstname'] . " " . $student_value['lastname'],
                        );
                    }

                    if ($student_value['app_key'] != "") {
                        $student_notification_list[] = array(
                            'app_key' => $student_value['app_key'],
                            'class' => $student_value['class'],
                            'section' => $student_value['section'],
                            'title' => $title,
                            'date' => $date,
                            'duration' => $duration,
                            'admission_no' => $student_value['admission_no'],
                            'student_name' => $student_value['firstname'] . " " . $student_value['lastname'],
                        );
                    }

                    if ($student_value['email'] != "") {
                        $student_email_list[$student_value['email']] = array(
                            'class' => $student_value['class'],
                            'section' => $student_value['section'],
                            'title' => $title,
                            'date' => $date,
                            'duration' => $duration,
                            'admission_no' => $student_value['admission_no'],
                            'student_name' => $student_value['firstname'] . " " . $student_value['lastname'],
                        );
                    }
                    if ($student_value['guardian_email'] != "") {
                        $student_guardian_email_list[$student_value['guardian_email']] = array(
                            'class' => $student_value['class'],
                            'section' => $student_value['section'],
                            'title' => $title,
                            'date' => $date,
                            'duration' => $duration,
                            'admission_no' => $student_value['admission_no'],
                            'student_name' => $student_value['firstname'] . " " . $student_value['lastname'],
                        );
                    }

                    if ($student_value['mobileno'] != "") {
                        $student_sms_list[$student_value['mobileno']] = array(
                            'class' => $student_value['class'],
                            'section' => $student_value['section'],
                            'title' => $title,
                            'date' => $date,
                            'duration' => $duration,
                            'admission_no' => $student_value['admission_no'],
                            'student_name' => $student_value['firstname'] . " " . $student_value['lastname'],
                        );
                    }
                    if ($student_value['guardian_phone'] != "") {
                        $student_guardian_sms_list[$student_value['guardian_phone']] = array(
                            'class' => $student_value['class'],
                            'section' => $student_value['section'],
                            'title' => $title,
                            'date' => $date,
                            'duration' => $duration,
                            'admission_no' => $student_value['admission_no'],
                            'student_name' => $student_value['firstname'] . " " . $student_value['lastname'],
                        );
                    }
                }

                if ($student_email_list) {
                    if($chk_mail_sms['mail']){
                     $this->sentOnlineClassStudentMail($student_email_list, $template);
                    }
                }
                if ($student_guardian_email_list) {
                    if($chk_mail_sms['mail']){
                    $this->sentOnlineClassStudentMail($student_guardian_email_list, $template);
                 }
                }

                if ($student_sms_list) {
                    
                    if($chk_mail_sms['sms'] && !empty($sms_detail)){
                      $this->sentOnlineClassStudentSMS($student_sms_list, $template,$template_id);
                    }
                }
                if ($student_guardian_sms_list) {

                    if($chk_mail_sms['sms'] && !empty($sms_detail)){
                    $this->sentOnlineClassStudentSMS($student_guardian_sms_list, $template, $template_id);
                }
                }

                if (!empty($student_notification_list)) {
                    if($chk_mail_sms['notification']){
                    $this->sentOnlineClassStudentNotification($student_notification_list, $template);
                }
                }

                if (!empty($student_guardian_notification_list)) {
                    if($chk_mail_sms['notification']){
                    $this->sentOnlineClassStudentNotification($student_guardian_notification_list, $template);
                }
                }
            }
        }
    }

    public function sentOnlineClassStudentMail($detail, $template) {

        if (!empty($this->CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template);

                    $subject = "Online Class";
                    $this->CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentOnlineClassStudentSMS($detail, $template,$template_id) {

        $sms_detail = $this->CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {

            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template,$sms_detail->type);

                    $subject = "Online Class";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->CI->load->library('clickatell', $params);

                        try {
                            $result = $this->CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {
                                
                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode' => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token' => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number' => $sms_detail->contact,
                        );

                        $this->CI->load->library('twilio', $params);

                        $from = $sms_detail->contact;
                        $to = $send_to;
                        $message = $msg;
                        $response = $this->CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey' => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                            'templateid' => $template_id,
                        );
                        $this->CI->load->library('msgnineone', $params);
                        $this->CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'sernderid' => $sms_detail->senderid,
                            'password' => $sms_detail->password,
                        );
                        $this->CI->load->library('smscountry', $params);
                        $this->CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash' => $sms_detail->password,
                        );
                        $this->CI->load->library('textlocalsms', $params);
                        $this->CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'custom') {
                        $this->CI->load->library('customsms');
                        $from = $sms_detail->contact;
                        $to = $send_to;
                        $message = $msg;
                        $this->CI->customsms->sendSMS($to, $message);
                    } else {
                        
                    }
                }
            }
        }
    }

    public function sentOnlineClassStudentNotification($detail, $template) {
        $this->CI->load->library('pushnotification');
        foreach ($detail as $student_key => $student_value) {
            $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template);

            $push_array = array(
                'title' => 'Online Class',
                'body' => $msg,
            );

            if ($student_value['app_key'] != "") {
                $this->CI->pushnotification->send($student_value['app_key'], $push_array, "mail_sms");
            }
        }
    }

    public function sendGmeetMeeting($chk_mail_sms, $staff_details, $template,$template_id) {

        $staff_sms_list = array();
        $staff_email_list = array();

        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms']) {

            if (!empty($staff_details)) {
                foreach ($staff_details as $staff_key => $staff_value) {

                    if ($staff_value['email'] != "") {
                        $staff_email_list[$staff_value['email']] = array(
                            'title' => $staff_value['title'],
                            'date' => $staff_value['date'],
                            'duration' => $staff_value['duration'],
                            'employee_id' => $staff_value['employee_id'],
                            'department' => $staff_value['department'],
                            'designation' => $staff_value['designation'],
                            'name' => $staff_value['name'],
                            'contact_no' => $staff_value['contact_no'],
                            'email' => $staff_value['email'],
                        );
                    }

                    if ($staff_value['contact_no'] != "") {
                        $staff_sms_list[$staff_value['contact_no']] = array(
                            'title' => $staff_value['title'],
                            'date' => $staff_value['date'],
                            'duration' => $staff_value['duration'],
                            'employee_id' => $staff_value['employee_id'],
                            'department' => $staff_value['department'],
                            'designation' => $staff_value['designation'],
                            'name' => $staff_value['name'],
                            'contact_no' => $staff_value['contact_no'],
                            'email' => $staff_value['email'],
                        );
                    }
                }

                if ($staff_email_list) {
                    $this->sentOnlineMeetingStaffMail($staff_email_list, $template);
                }

                if ($staff_sms_list) {

                    $this->sentOnlineMeetingStaffSMS($staff_sms_list, $template,$template_id);
                }
            }
        }
    }

    public function sentOnlineMeetingStaffMail($detail, $template) {

        if (!empty($this->CI->mail_config)) {
            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($detail[$staff_key], $template);
                    $subject = "Online Gmeet Meeting";
                    $this->CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function getOnlineMeetingStaffContent($staff_detail, $template,$sms_detail_type) {
        foreach ($staff_detail as $key => $value) {
            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }
            }

            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function getOnlineClassStudentContent($student_detail, $template,$sms_detail_type=null) {

        foreach ($student_detail as $key => $value) {
            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }
            }
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function sentOnlineMeetingStaffSMS($detail, $template,$template_id) {

        $sms_detail = $this->CI->smsconfig_model->getActiveSMS();

        if (!empty($sms_detail)) {

            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($detail[$staff_key], $template,$sms_detail->type);
                    
                    $subject = "Online Gmeet Meeting";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->CI->load->library('clickatell', $params);

                        try {
                            $result = $this->CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {
                                
                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode' => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token' => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number' => $sms_detail->contact,
                        );

                        $this->CI->load->library('twilio', $params);

                        $from = $sms_detail->contact;
                        $to = $send_to;
                        $message = $msg;
                        $response = $this->CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey' => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                            'templateid' => $template_id,
                        );
                        $this->CI->load->library('msgnineone', $params);
                        $this->CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'sernderid' => $sms_detail->senderid,
                            'password' => $sms_detail->password,
                        );
                        $this->CI->load->library('smscountry', $params);
                        $this->CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash' => $sms_detail->password,
                        );
                        $this->CI->load->library('textlocalsms', $params);
                        $this->CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'custom') {
                        $this->CI->load->library('customsms');
                        $from = $sms_detail->contact;
                        $to = $send_to;
                        $message = $msg;
                        $this->CI->customsms->sendSMS($to, $message);
                    } else {
                        
                    }
                }
            }
        }
    }

}
