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

    function users_get() {
        $this->load->model('UserModel');
        $users_info = array();
        if (!$this->get('team')) {
            $users = $this->github_lib->repo_collaborators($this->config->item('default_team'), $this->config->item('default_project'));
            foreach($users AS $user){
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
    
    function projects_get(){
        $this->load->model('ProjectModel');
        $projects_array = array();
        if (!$this->get('user')) {
            $projects_info = $this->github_lib->user_repos($this->config->item('default_team'));
        }
        else{
            $projects_info = $this->github_lib->user_repos($this->get('user'));
        }
        
        $count = 1;
        foreach($projects_info as $project){
            //TODO: implement v3 - this is a fix for v2
            $project->url = "/api/project/user/".$project->owner."/project/".$project->name;
            $proj = new ProjectModel($count,$project->homepage, $project->url, $project->name, $project->created_at, $project->pushed_at, $project->language, $project->owner);
            array_push($projects_array,$proj);
            $count ++;
        }
        
        if ($projects_array) {
            $this->response($projects_array, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any projects for that user!'), 404);
        }
    }
    
    function project_get(){
        $this->load->model('ProjectModel');
        if (!$this->get('user') || !$this->get('project')) {
            $this->response(NULL, 400);
        }
        else{
            $project_info = $this->github_lib->repo_info($this->get('user'),$this->get('project'));
        }
        
        if ($project_info) {
            $this->response($project_info, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find the project for that user!'), 404);
        }
    }
    
    function projectCommits_get(){
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
        }
        else{
            $project_commits = $this->github_lib->user_timeline($this->get('user'),$this->get('project'));
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
    
    
    /* TASK API SECTION ********************************************** */

    function task_get() {
        $this->load->model('TaskModel');

        if (!$this->get('id')) {
            $this->response(NULL, 400);
        }
        $task = $this->TaskModel->task_get($this->get('id'));
        if ($task) {
            $this->response($task, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Task could not be found'), 404);
        }
    }
    
    //task update
    function task_put() {
        $this->load->model('TaskModel');

        $this->load->library('Task');
        $task = new Task();

        $task->id = $this->put('id');
        $task->name = $this->put('name');
        
        
        if($this->put('status') !== false){
            $task->status = $this->put('status');
        }
        
        if($this->put('assignedTo') !== false){
            $task->assignedTo = $this->put('assignedTo');
        }
        
        if($this->put('notes') !== false){
            $task->notes = $this->put('notes');
        }
        
        if($this->put('dateDue') !== false){
            $task->dateDue = $this->put('dateDue');
        }
        
        if (!$this->put('id')) {
            $this->response(array('error' => 'id is required for an update'), 400);
        }

        $statusCode = $this->TaskModel->task_update($task);
        if ($statusCode === 200) {
            $this->response($task, 200); // 200 being the HTTP response code
        }else if($statusCode === 406){
            $this->response(array('error' => 'Missing mandatory field (name)'), 406);
        }
        else {
            $this->response(array('error' => 'Task could not be found'), 404);
        }
    }
    
    //task create
    function task_post() {
        $this->load->model('TaskModel');
        $this->load->library('Task');
        $task = new Task();
        if (!$this->post('name')){
            $this->response(array('error'=> 'Task Requires a name'), 406);
        } else {
            $task->name = $this->post('name');
        }
        if($this->post('assignedTo') !== false){
            $task->assignedTo = $this->post('assignedTo');
        }
        if($this->post('notes') !== false){
            $task->notes = $this->post('notes');
        }
        
        if($this->post('dateDue') !== false){
            $task->dateDue = $this->post('dateDue');
        }
        
        $taskResponse = $this->TaskModel->task_create($task);
        $this->response($taskResponse, 200);
    }

    function task_delete() {
        $this->load->model('TaskModel');
        $isDeleted = $this->TaskModel->task_delete($this->delete('id'));
        if ($isDeleted == true) {
            $this->response(array('message' => 'Task ' . $this->get('id') . ' successfully deleted'), 200);
        } else {
            $this->response(array('error' => 'Task could not be found'), 404);
        }
    }

    function tasks_get() {
        $this->load->model('TaskModel');

        if (!$this->get('username')) { // get all non-deleted tasks
            $tasks = $this->TaskModel->tasks_get();
        } else { //get tasks for username
            $tasks = $this->TaskModel->userTasks_get($this->get('username'));
        }

        if ($tasks) {
            $this->response($tasks, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found or no tasks available for that user.'), 404);
        }
    }

}