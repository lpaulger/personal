<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('GitHub_lib');
        $this->load->library('Github/Client');
        $github = new Github_Client();
    }

    function user_get() {
        $this->load->model('UserModel');
        if (!$this->get('username')) {
            $this->response(NULL, 400);
        }
        //$user = $this->UserModel->user_get($this->get('username'));
        $user = $this->github_lib->user_info($this->get('username'));

        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    function userEvents_get() {
        $this->load->model('UserModel');
        $this->load->model('PostModel');
        if (!$this->get('username')) {
            $this->response(NULL, 400);
        }
        
        $data['users'] = array();
        $data['posts'] = array();
        $users = $this->github_lib->repo_collaborators($this->config->item('default_team'), $this->config->item('default_project'));

        foreach ($users AS $user) {
            $user_info = $this->github_lib->user_info($user->login);
            $user_info->imageLarge = "http://www.gravatar.com/avatar/" . $user_info->gravatar_id . "/?s=120&d=" . site_url();
            $user_info->image = "http://www.gravatar.com/avatar/" . $user_info->gravatar_id . "/?s=48&d=" . site_url();
            array_push($data['users'], $user_info);
        }
        
        //$user = $this->UserModel->user_get($this->get('username'));
        $userEvents = $this->github_lib->user_events($this->get('username'));

        if ($userEvents) {

            foreach ($userEvents as $event) {

                $post->title = "default title";
                $post->datetime = strtotime($event->created_at);
                $post->iso8601 = date('c', $post->datetime);
                $post->source = "Github";
                $post->image = ""; //default image
                $post->message = "default message";
                $shouldPost = false;
                $user = null;
                foreach ($data['users'] as $struct) {
                    if ($event->actor->login == $struct->login) {
                        $user = $struct;
                        $post->image = $user->image;
                        break;
                    }
                }

                switch ($event->type) {
                    case "PushEvent":
                        $post->source = "Github - Push";
                        $post->title = $event->repo->name;
                        $message = "";
                        foreach ($event->payload->commits as $commit) {
                            $message .= $commit->message . ". ";
                        }
                        $post->message = $message;
                        $shouldPost = true;
                        break;
                    case "WatchEvent":
                        $post->source = "Github - Watch";
                        $post->title = "I'm watching " . $event->repo->name;
                        $post->message = "";
                        $shouldPost = true;
                        break;
                    case "MemberEvent":
                        //Triggered when a user is added as a collaborator to a repository.
                        $post->title = "";
                        $post->message = "";
                        break;
                    case "CreateEvent":
                        //Represents a created repository, branch, or tag.
                        $post->source = "Github - Create";
                        $post->title = ($event->payload->description == '') ? $event->repo->name : $event->payload->description;
                        $post->message = $event->repo->url;
                        $shouldPost = true;
                        break;
                    case "GollumEvent":
                        //wiki event
                        $title = "";
                        $message = "";
                        $post->source = "Github - Wiki";
                        foreach ($event->payload->pages as $page) {
                            $title = $page->title . "($page->action)";
                            $message = "I " . $page->action . " the " . $page->page_name . " at " . $page->html_url;
                        }
                        $post->title = $title;
                        $post->message = $message;
                        $shouldPost = true;
                        break;
                    case "IssuesEvent":
                        $post->title = $event->payload->action . " - " . $event->payload->issue->title;
                        $post->message = $event->payload->issue->body;
                        $post->source = "Github - Issue";
                        $post->datetime = strtotime($event->payload->issue->updated_at);
                        $post->iso8601 = date('c', $post->datetime);
                        $shouldPost = true;
                        break;
                    case "FollowEvent":
                        $post->title = "I'm following " . $event->payload->target->name . "!";
                        $post->message = 'Check them out at ' . $event->payload->target->html_url;
                        $post->source = "Github - Follow";
                        $post->datetime = strtotime($event->created_at);
                        $post->iso8601 = date('c', $post->datetime);
                        $shouldPost = true;
                        break;
                    case "IssueCommentEvent":
                        //Currently don't show
                        $shouldPost = false;
                        break;
                    default:
                        //other event type that isn't handled yet
                        $post->title = $event->type;
                        $post->message = "uhh is this a new event?";
                        $shouldPost = true;
                }

                if ($shouldPost === true) {

                    $post->message = preg_replace('@((https?|http)://[^\s]+)@sm', '<a class="event-link" href="$1">$1</a>', $post->message);

                    $this_post = new PostModel($post->title, $post->datetime, $post->iso8601, $post->source, $post->image, $post->message);
                    array_push($data['posts'], $this_post);
                }                
            }
            $this->response($data['posts'], 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    function users_get() {
        $this->load->model('UserModel');
        $users_info = array();
        if (!$this->get('team')) {
            $users = $this->github_lib->repo_collaborators($this->config->item('default_team'), $this->config->item('default_project'));
            foreach ($users AS $user) {
                $user_info = $this->github_lib->user_info($user);
                array_push($users_info, $user_info);
            }
        } else {
            //TODO: build this when the need for other repo users come into play other than the defaults
            $users = $this->github_lib->repo_info($this->config->item('default_team'), $this->config->item('default_project'));
            $this->response(NULL, 400);
        }

        if ($users_info) {
            $this->response($users_info, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

    /*
     * Project API Section
     */

    function projects_get() {
        $this->load->model('ProjectModel');
        $projects_array = array();
        if (!$this->get('user')) {
            $projects_info = $this->github_lib->user_repos($this->config->item('default_team'));
        } else {
            $projects_info = $this->github_lib->user_repos($this->get('user'));
        }

        $count = 1;
        foreach ($projects_info as $project) {
            //TODO: implement v3 - this is a fix for v2
            $project->url = "/api/project/user/" . $project->owner . "/project/" . $project->name;
            $proj = new ProjectModel($count, $project->homepage, $project->url, $project->name, $project->created_at, $project->pushed_at, $project->language, $project->owner);
            array_push($projects_array, $proj);
            $count++;
        }

        if ($projects_array) {
            $this->response($projects_array, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any projects for that user!'), 404);
        }
    }

    function project_get() {
        $this->load->model('ProjectModel');
        if (!$this->get('user') || !$this->get('project')) {
            $this->response(NULL, 400);
        } else {
            $project_info = $this->github_lib->repo_info($this->get('user'), $this->get('project'));
        }

        if ($project_info) {
            $this->response($project_info, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find the project for that user!'), 404);
        }
    }

    function projectCommits_get() {
        $this->load->model('ProjectModel');
        $this->load->model('PostModel');
        $posts_array = array();
        $data['users'] = array();
        $users = $this->github_lib->repo_collaborators($this->get('user'), $this->get('project'));

        foreach ($users AS $user) {
            $user_info = $this->github_lib->user_info($user->login);
            $user_info->image = "http://www.gravatar.com/avatar/" . $user_info->gravatar_id . "/?s=48&d=" . site_url();
            array_push($data['users'], $user_info);
        }

        if (!$this->get('user') || !$this->get('project')) {
            $this->response(NULL, 400);
        } else {
            $project_commits = $this->github_lib->user_timeline($this->get('user'), $this->get('project'));
        }

        foreach ($project_commits as $commit) {

            $user = null;
            foreach ($data['users'] as $struct) {
                if ($commit->committer->login == $struct->login) {
                    $user = $struct;
                    break;
                }
            }
            $timestamp = strtotime($commit->commit->committer->date);
            $iso8601 = date('c', $timestamp);
            $post = new PostModel($commit->committer->login, $timestamp, $iso8601, "Github", $user->image, $commit->commit->message);
            array_push($posts_array, $post);
        }

        if ($posts_array) {
            $this->response($posts_array, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find the project for that user!'), 404);
        }
    }

}