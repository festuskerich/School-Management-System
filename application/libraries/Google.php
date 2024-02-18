<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'third_party/omnipay/vendor/autoload.php';

class Google
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function client()
    {
        $setting      = $this->CI->gmeetsetting_model->get();
        if(!empty($setting)){
        $redirect_uri = site_url('admin/gmeet/authenticate');
        $client       = new \Google_Client();
        $client->setApplicationName("Gmeet Live Class");
        $client->setClientId($setting->api_key);
        $client->setClientSecret($setting->api_secret);
        $client->setRedirectUri($redirect_uri);
        $client->setScopes(['https://www.googleapis.com/auth/calendar']);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        return $client;
        }
    }

    public function createEvent($event_data)
    {

        $client = $this->client();
        $client->setAccessToken($this->CI->session->userdata('google_token'));
        // If there is no previous token or it's expired.
        if (!$client->isAccessTokenExpired()) {
            $refreshTokenSaved = $client->getRefreshToken();
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                return false;
            }
        }
        //==================
        $client->setAccessToken($this->CI->session->userdata('google_token'));
        $post_time    = $event_data['date'];
        $str_end_time = strtotime($post_time) + ($event_data['duration'] * 60);
        $start_time   = gmdate("Y-m-d\TH:i:s", strtotime($post_time));
        $end_time     = gmdate("Y-m-d\TH:i:s", $str_end_time);
        $accessToken  = $client->getAccessToken();
        $service      = new Google_Service_Calendar($client);
        $event        = new Google_Service_Calendar_Event(array(
            'summary'        => $event_data['title'],
            'location'       => 'Jabalpur',
            'description'    => $event_data['description'],
            'start'          => array(
                'dateTime' => $start_time,
                'timeZone' => $event_data['timezone'],
            ),
            'end'            => array(
                'dateTime' => $end_time,
                'timeZone' => $event_data['timezone'],
            ),
            'reminders'      => array(
                'useDefault' => false,
                'overrides'  => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            ),
            "conferenceData" => array(
                "createRequest" => array(
                    "conferenceSolutionKey" => array(
                        "type" => "hangoutsMeet",
                    ),
                    "requestId"             => "123",
                ),
            ),
        ));

        $opts  = array('sendNotifications' => true, 'conferenceDataVersion' => true);
        $event = $service->events->insert('primary', $event, $opts);
        return $event;
    }
}
