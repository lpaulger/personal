<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of projectmodel
 *
 * @author lpaulger
 */
class ProjectModel extends CI_Model {
    
    var $id;
    var $html_url;
    var $url;
    var $name;
    var $owner;
    var $created_at;
    var $pushed_at;
    var $language;
    
    function __construct($id = '', $html_url = '/', $url = '', $name = '', $created_at = '', $pushed_at = '', $language = 'N/A', $owner = '') {
        // Call the Model constructor
        parent::__construct();
        
        $this->id = $id;
        $this->html_url = $html_url;
        $this->url = $url;
        $this->name = $name;
        $this->updated_at = $created_at;
        $this->pushed_at = $pushed_at;
        $this->language = $language;
        $this->owner = $owner;
    }
}