<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of postmodel
 *
 * @author lpaulger
 */

class PostModel extends CI_Model {
    
    var $title;
    var $datetime;
    var $iso8601;
    var $source;
    var $image;
    var $message;
    
    function __construct($title = '', $datetime = '', $iso8601 = '', $source = '', $image = '', $message = '') {
        // Call the Model constructor
        parent::__construct();
        
        $this->title = $title;
        $this->datetime = $datetime;
        $this->iso8601 = $iso8601;
        $this->source = $source;
        $this->image = $image;
        $this->message = $message;
    }
}