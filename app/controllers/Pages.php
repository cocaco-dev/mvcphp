<?php
class Pages extends Controller{
	public function __construct(){
			echo 'construct controller pages';
			
	}
	public function index(){

		$data = ['title' => 'Welcome'];
     
		$this->view('pages/index',$data);
	}
	public function about(){
		$data = ['title' => 'About','description'=>'App to share posts with other users'];
		$this->view('pages/about', $data);
	}
}