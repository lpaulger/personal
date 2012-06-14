<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author Philip Sturgeon
 * @created 31/03/2009
 * @updated 29/12/2009
 * @info http://develop.github.com/
 */
class GitHub_lib {

    // CodeIgniter instance
    private $CI;

    function __construct($url = '') {
        $this->CI = & get_instance();
        log_message('debug', 'GitHub class initialized');
    }

    /**
     * Grab all issues for a specific repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @param	string - the state of the issues to pull (open/closed)
     * @return	object - an object with all the repository's issues
     */
    public function project_issues($user = '', $repo = '', $state = 'open') {
        $responce = $this->_fetch_data('https://api.github.com/repos/' . $user . '/' . $repo . '/issues');

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }

    /**
     * Grab the info for a repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @return	object - an object with all the repository's info
     */
    public function repo_info($user = '', $repo = '') {
        $responce = $this->_fetch_data('https://api.github.com/repos/' . $user . '/' . $repo);

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }

    /**
     * Grab all collaborators for a specific repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @return	object - an object with all the repository's collaborators
     */
    public function repo_collaborators($user = '', $repo = '') {
        $responce = $this->_fetch_data('https://api.github.com/repos/' . $user . '/' . $repo . '/collaborators');

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }

    /**
     * Grab the info for a specific user
     * 
     * @access	public
     * @param	string - a GitHub user
     * @return	object - an object with all the user's info
     */
    public function user_info($user = '') {
        $responce = $this->_fetch_data('https://api.github.com/users/' . $user);

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }
    
    /*
     * Grab the public events for a user
     * 
     * @access public
     * @param string - github user
     * @return array - array of event objects
     */
    public function user_events($user = ''){
        $responce = $this->_fetch_data('https://api.github.com/users/'.$user.'/events');

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }

    /**
     * Grab the info for repos of a specific user
     * 
     * @access	public
     * @param	string - a GitHub user
     * @return	object - an object with all the user's repos info
     */
    public function user_repos($user = '') {
        $response = $this->_fetch_data('https://api.github.com/users/'.$user.'/repos');
        if (empty($response)) {
            return FALSE;
        }

        return $response;
    }

    

    /**
     * Grab all commits by a user to a specific repository
     * 
     * @access	public
     * @param	string - a GitHub user
     * @param	string - a repository name
     * @param	string - the branch name (master by default)
     * @return	object - an object with all the branch's commits
     */
    public function user_timeline($user = '', $repo = '', $branch = 'master') {
        $responce = $this->_fetch_data('https://api.github.com/repos/' . $user . '/' . $repo . '/commits');

        if (empty($responce)) {
            return FALSE;
        }

        return $responce;
    }

    /**
     * Fetch the data from GitHub
     * 
     * @access	private
     * @param	string - the API URL to use
     * @return	object - a decoded JSON object with all the information (FALSE if nothing is returned)
     */
    private function _fetch_data($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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

// END GitHub class