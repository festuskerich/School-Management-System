<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paypal extends Student_Controller {

    public $setting = "";

    function __construct() {
        parent::__construct();
        $this->load->helper('file');

        $this->load->library('auth');
        $this->load->library('paypal_payment');
        $this->currency = $this->setting_model->getCurrency();
        $this->setting = $this->setting_model->get();
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('student/paypal', $data);
    }

    function checkout() {

        
        $this->form_validation->set_rules('student_id', 'Student', 'required|trim|xss_clean');
        $this->form_validation->set_rules('total', 'Amount', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                
                'student_id' => form_error('student_id'),
                'amount' => form_error('amount'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }
 
    public function complete() {
 
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $params = $this->session->userdata('params');

            $student_data = $this->student_model->get($params['student_id']);
           
            $amount =number_format((float)($params['fine_amount_balance']+$params['total']), 2, '.', '');
            $data = array();
            $data['student_id'] = $this->input->post('student_id');
            $data['total'] = $amount;
            $data['symbol'] = $params['invoice']->symbol;
            $data['currency_name'] = $params['invoice']->currency_name;
            $data['name'] = $params['name'];
            $data['guardian_phone'] = $student_data['guardian_phone'];
            $payment = array(
            'guardian_phone' => $data['guardian_phone'],
            'name' => $data['name'],
            'description' => "Student Fess",
            'amount' => $data['total'],
            'currency' => $this->currency,
        );
        
        $payment['cancelUrl'] = base_url('students/paypal/getsuccesspayment');
        $payment['returnUrl'] = base_url('students/paypal/getsuccesspayment');
            $response = $this->paypal_payment->payment($payment);
            if ($response->isSuccessful()) {
                
            } elseif ($response->isRedirect()) {
                $response->redirect();
            } else {
                echo $response->getMessage();
            }
        }
    }

    //paypal successpayment
    public function getsuccesspayment() {
        $params = $this->session->userdata('params');
        $student_data = $this->student_model->get($params['student_id']);
        $amount =number_format((float)($params['fine_amount_balance']+$params['total']), 2, '.', '');
        $data = array();
        $data['student_id'] = $params['student_id'];
        $data['total'] = $amount;
        $data['symbol'] = $params['invoice']->symbol;
        $data['currency_name'] = $params['invoice']->currency_name;
        $data['name'] = $params['name'];
        $data['guardian_phone'] = $student_data['guardian_phone'];


          $success_data = array(
            'guardian_phone' => $data['guardian_phone'],
            'name' => $data['name'],
            'description' => "Student Fess",
            'amount' => $data['total'],
            'currency' => $this->currency,
        );

        $success_data['cancelUrl'] = base_url('students/paypal/getsuccesspayment');
        $success_data['returnUrl'] = base_url('students/paypal/getsuccesspayment');
        $response = $this->paypal_payment->success($success_data);

        $paypalResponse = $response->getData();
        if ($response->isSuccessful()) {
            $purchaseId = $_GET['PayerID'];

            if (isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
                if ($purchaseId) {
                    $ref_id = $paypalResponse['PAYMENTINFO_0_TRANSACTIONID'];
                
                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                   
                     $json_array = array(
                        'amount'          =>  $fee_value['amount_balance'],
                        'date'            => date('Y-m-d'),
                        'amount_discount' => 0,
                        'amount_fine'     => $fee_value['fine_balance'],
                        'description'     => "Online fees deposit through Paypal Ref ID: " . $ref_id,
                        'received_by'     => '',
                        'payment_mode'    => 'Paypal',
                    );

                    $insert_fee_data = array(
                        'student_fees_master_id' => $fee_value['student_fees_master_id'],
                        'fee_groups_feetype_id'  => $fee_value['fee_groups_feetype_id'],
                        'amount_detail'          => $json_array,
                    );                 
                   $bulk_fees[]=$insert_fee_data;
                    //========
                    }
                    $send_to     = $params['guardian_phone'];
                    $inserted_id = $this->studentfeemaster_model->fee_deposit_bulk($bulk_fees, $send_to);
                    if ($inserted_id) {
                          redirect(base_url("students/payment/successinvoice"));                     
                    } else {
                      redirect(base_url('students/payment/paymentfailed'));
                    }
                   
                }
            }
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            redirect(base_url("students/payment/paymentfailed"));
        }
    }

}

?>