<?php

class climatestrike_SubmitSignupForm {

    private $post_prefix;

    function __construct() {
        add_action('wp_ajax_climatestrike_signup_form', array($this, 'climatestrike_signup_form'));
        add_action('wp_ajax_nopriv_climatestrike_signup_form', array($this, 'climatestrike_signup_form'));
    }

    // This function is called when submitting the form via AJAX.
    function climatestrike_signup_form() {   
        $response = $this->submitForm($_POST['data']);

        if(count($response['errors']) > 0) {
            wp_send_json_error( $response );
        } else {
            wp_send_json_success( $response );
        }
        exit;
    }
    
    function submitForm($data) {
        $response = $this->validateForm($data);

        if(!$response['success']) {
            return $response;
        }

        $an = $this->postToActionNetwork($data);
    
        if($an !== true) {
            $response['success'] = false;
            $response['errors']['an'] = $an;
        }

        return $response;
    }

    function validateForm($data) {
        $response = array();
        $response['errors'] = array();
        $response['success'] = false;

        // When using AJAX Form fields are in ['data']
        // If JS is disabled, we can just use $_POST to access form fields.
        /*if(isset($_POST['data'])) {
            $this->post_prefix = $_POST['data'];
        } else {
            $this->post_prefix = $_POST;
        }*/


        if(empty($data['first-name']) || empty($data['last-name']) ) $response['errors']['required'] = "Please fill out all required fields.";

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $response['errors']['email'] = "Invalid email address.";
        }

        if(empty($data['gdpr-consent'])) {
            $response['errors']['gdpr-consent'] = "In compliance with GDPR, you must consent to receiving communications from us.";
        }

        if(!empty($data['join'])) {
            if(empty($data['yob'])) $response['errors']['yob'] = "Please enter your year of birth.";
            else if(!is_numeric($data['yob'])) $response['errors']['yob'] = "Please enter a valid year of birth.";
            else if(date('Y') - intval($data['yob']) > 25) $response['errors']['yob'] = "Sorry! You must be 25 or under to join SYCS.";
        }


        if(count($response['errors']) == 0) {
            $response['success'] = true;
        }

        return $response;
    }

    function postToActionNetwork($data) {
        $data_formatted = $this->formatFieldsForAN($data);

        $header = array(
            'Content-Type: application/json',
            'OSDI-API-Token: ' . CLIMATESTRIKE_AN_API_KEY
        );

        $api_url = "https://actionnetwork.org/api/v2/people/";

        $ch = curl_init();
        
        $options = array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($data_formatted),
            CURLOPT_HTTPHEADER => $header
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if($response_code !== 200) {
            $errors = array(
                'There was a problem subscribing you to the list, please try again or contact us.',
                $response_code,
                $response,
                curl_error()
            );
            return $errors;
        } else {
            return true;
        }

        curl_close($ch);
    }

    function formatFieldsForAN($data) {
        $email = array(
            'address' => $data['email'],
            'primary' => true,
            'status' => 'subscribed'
        );

        /* Must set country to allow AN to infer info from postcode
         * Assume country is GB
         */
        if(isset($data['postcode'])) {
            $address = array(
                'postal_code' => $data['postcode'],
                'country' => 'GB'
            );
        } else {
            $address = array(
                'country' => 'GB'
            ); 
        }

        $custom_fields = array(
            'Phone' => $data['phone-number'],
        );

        if(!empty($data['yob'])) $custom_fields['yob'] = $data['yob'];

        $person = array(
            'given_name' => $data['first-name'],
            'family_name' => $data['last-name'],
            'email_addresses' => array($email),
            'postal_addresses' => array($address),
            'custom_fields' => $custom_fields,
        );

        $rtn = array(
            'person' => $person,
            'add_tags' => array()
        );

        if(!empty($data['join'])) $rtn['add_tags'][] = "member";

        return $rtn;

    }
}

new climatestrike_SubmitSignupForm;
