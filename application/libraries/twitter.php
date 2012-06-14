<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of twitter
 *
 * @author lpaulger
 */
class twitter {

    var $type = 'json';
    var $user_agent = 'Lucas Paulger Twitter-CodeIgniter';
    var $api_location = 'api.twitter.com/1';
    var $username;
    var $password;
    var $auth;
    var $user;
    var $last_error;
    var $friends_timeline;
    var $replies;
    var $friends;
    var $followers;
    var $direct_messages;
    var $sent_direct_messages;
    var $favorites;

    function user_timeline($username = '') {


        $responce = $this->_fetch($this->api_location . '/statuses/user_timeline.json?screen_name=' . $username);

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }

    function _fetch($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $returned = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status == '200') {
            return json_decode($returned);
        } else {
            return false;
        }
    }

}

?>
