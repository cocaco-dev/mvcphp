<?php
class Pages extends Controller{
	public function __construct(){
			echo 'construct controller pages';
			
	}
	public function index(){
		if(isLoggedIn()){
			redirect('posts');
		}
		$data = ['title' => 'Welcome'];
     
		$this->view('pages/index',$data);
	}
	public function about(){
		$data = ['title' => 'About','description'=>'App to share posts with other users'];
		$this->view('pages/about', $data);
	}
}