<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('GitHub_lib');
        $this->load->library('twitter');
    }

    function index() {
        $this->load->helper('url');
        $this->load->model('PostModel');
        $this->load->model('ProjectModel');
        $data['users'] = array();
        $data['posts'] = array();
        $data['projects'] = array();

        $users = $this->github_lib->repo_collaborators($this->config->item('default_team'), $this->config->item('default_project'));

        foreach ($users AS $user) {
            $user_info = $this->github_lib->user_info($user->login);
            $user_info->imageLarge = "http://www.gravatar.com/avatar/" . $user_info->gravatar_id . "/?s=120&d=" . site_url();
            $user_info->image = "http://www.gravatar.com/avatar/" . $user_info->gravatar_id . "/?s=48&d=" . site_url();
            array_push($data['users'], $user_info);
        }

        $projects_info = $this->github_lib->user_repos($this->config->item('default_team'));
        $projects_aft = $this->github_lib->user_repos($this->config->item('my_github_username'));

        $merged_projects = array_merge($projects_info, $projects_aft);
        $projects = array();
        $count = 1;
        foreach ($merged_projects as $project) {
            //TODO: implement v3 - this is a fix for v2
            $project->url = "/api/project/user/" . $project->owner->login . "/project/" . $project->name;
            if (in_array($project->name, $this->config->item('github_projects'))) {
                $proj = new ProjectModel($count, $project->homepage, $project->url, $project->name, $project->created_at, $project->pushed_at, $project->language, $project->owner);
                array_push($projects, $proj);
                $count++;
            }
        }
        $data['projects'] = $projects;


        $github_events = $this->github_lib->user_events($this->config->item('my_github_username'));
        $github_commits = $this->github_lib->user_timeline($this->config->item('default_team'), $this->config->item('default_project'));
        $twitter_user_tweets = $this->twitter->user_timeline($this->config->item('twitter_username'));


        /*
         * org -> url for organization
         * type -> MemberEvent, CreateEvent, GollumEvent, IssueEvent, etc...
         * actor -> User Object
         * public -> true or false (1/0)
         * created_at -> date
         * payload -> master_branch, ref_type, description, ref
         * repo -> url, id, name
         * id -> event Id
         */

        foreach ($github_events as $event) {

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

//        foreach ($github_commits as $commit) {
//
//            $user = null;
//            foreach ($data['users'] as $struct) {
//                if ($commit->committer->login == $struct->login) {
//                    $user = $struct;
//                    break;
//                }
//            }
//            $timestamp = strtotime($commit->committed_date);
//            $iso8601 = date('c', $timestamp);
//            $post = new PostModel($commit->committer->name, $timestamp, $iso8601, "Github", $user->image, $commit->message);
//            array_push($data['posts'], $post);
//        }

        foreach ($twitter_user_tweets as $tweet) {
            $timestamp = strtotime($tweet->created_at);
            $iso8601 = date('c', $timestamp);
            $post = new PostModel($tweet->user->name, $timestamp, $iso8601, "Twitter", $tweet->user->profile_image_url, $tweet->text);
            array_push($data['posts'], $post);
        }

        function cmp($a, $b) {
            if ($a->datetime == $b->datetime) {
                return 0;
            }
            return ($a->datetime < $b->datetime) ? 1 : -1;
        }

        usort($data['posts'], 'cmp');


        $this->load->view('default', $data);
    }

    function contact() {




        //just for development
        //error_reporting (E_ALL);
        // Enter your domain without trailing slash
        $config['domain'] = 'http://www.lucaspaulger.com';


        // Would you like to recieve an email if someone contact you? (really recommended!)
        $config['sendmail'] = true;
        // Your email address for the contact form
        $config['emailaddress'] = 'lucas@lucaspaulger.com';

        $this->load->library('email');

        $this->email->from($this->input->post('email'), $this->input->post('name'));
        $this->email->to('lucas@lucaspaulger.com');

        $this->email->subject('Contact From Lucaspaulger.com');
        $this->email->message($this->input->post('msg'));
        $return['success'] = false;
        try {
            $this->email->send();
            $return['success'] = true;
        } catch (Exception $e) {
            $return['success'] = false;
        }


//        $the_email = addslashes($_POST["email"]);
//        $the_name = addslashes(utf8_decode($_POST["name"]));
//        $the_msg = addslashes(utf8_decode($_POST["msg"]));
        //output success as JSON
        echo json_encode($return);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */