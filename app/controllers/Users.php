<?php 
class Users extends Controller{
	public function __construct(){
		echo ' User construct call';
		$this->userModel = $this->model('User');

	}

	public function register(){
		// check post

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			// process form
			// sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			$data = ['name' => trim($_POST['name']), 'email' => trim($_POST['email']), 'password' => trim($_POST['password']), 'confirm_password' => trim($_POST['confirm_password']), 'name_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''];

			//validate email
			if(empty($data['email'])){
				$data['email_err'] = 'please enter email';
			}
			else{
				//check email on db
				if($this->userModel->findUserByEmail($data['email'])){
					$data['email_err'] = 'email already taken';
				}
			}
			//validate name
			if(empty($data['name'])){
				$data['name_err'] = 'please enter name';
			}
			//validate password
			if(empty($data['password'])){
				$data['password_err'] = 'please enter password';
			}elseif(strlen($data['password']) < 6){
				$data['password_err'] = 'password must be at least 6 char';
			}
			//validate  confirm password
			if(empty($data['confirm_password'])){
				$data['confirm_password_err'] = 'please confirm password';
			}
			else{
				if($data['password'] != $data['confirm_password']){
					$data['confirm_password_err'] = 'passwords do not match';
				}
			}
			// make sure errors are empty
			if(empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
				// validate

				//die('success');
				//echo 'se valido esta wea';
				//hash password
				$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
				//register user
				if($this->userModel->register($data)){
					flash('register_success', 'you are registered and can log in');
					redirect('users/login');
				}
				else{
					die('algo salió mal');
				}
			}
			else{
				//load view with errors
				$this->view('users/register', $data);
			}
		}
		else{
			// init data
			$data = ['name' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 'name_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''];
			// load view 
			$this->view('users/register', $data);
		}	
			
	}
	public function login(){
     	// Check for POST
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
      		// sanitize POST data
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

			$data = ['email' => trim($_POST['email']), 'password' => trim($_POST['password']),'email_err' => '', 'password_err' => ''];
			//validate email
			if(empty($data['email'])){
				$data['email_err'] = 'please enter email';
			}
			// validate passowrd
			if(empty($data['password'])){
				$data['password_err'] = 'please enter password';
			}

			// check for user/email
			if($this->userModel->findUserByEmail($data['email'])){
				// user found
			}
			else{
				// user no found
				$data['email_err'] = 'no user found';
			}
			// errors empty
			if(empty($data['email_err']) && empty($data['password_err'])){
				// validate
				$loggedInUser = $this->userModel->login($data['email'], $data['password']);
				if($loggedInUser){
					//create session
					$this->createUserSession($loggedInUser);
				}
				else{
					$data['password_err'] = 'password incorrect';
					$this->view('users/login', $data);
				}
			}
			else{
				//load view with errors
				$this->view('users/login', $data);
			}
      	} 
      	else{
        	// Init data
        	$data =['email' => '', 'password' => '','email_err' => '','password_err' => '',];
        	
        	// Load view
        	$this->view('users/login', $data);
      	}
     }
     public function createUserSession($data){
     	$_SESSION['user_id'] = $data->id;
     	$_SESSION['user_email'] = $data->email;
     	$_SESSION['user_name'] = $data->name;
     	redirect('posts');
     }
     public function logout(){
     	unset($_SESSION['user_id']);
     	unset($_SESSION['user_email']);
     	unset($_SESSION['user_name']);
     	session_destroy();
     	redirect('users/login');
     }
     

}