<?php
//app core class
//Crea URL y 
class Core{
	protected $currentController = 'Pages';
	protected $currentMethod = 'index';
	protected $params = [];

	public function __construct(){
		//print_r($this->getUrl()); //test
		$url = $this->getUrl();

		//buscar en controladores
		if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
			// si existe, set as current controller
			$this->currentController = ucwords($url[0]);
			//unset 0 index
			unset($url[0]);
		}
		//require the controller
		require_once '../app/controllers/' . $this->currentController . '.php';
		//instanciar el controlador
		$this->currentController = new $this->currentController;
		//revisar segunda parte de la url
		if(isset($url[1])){
			if(method_exists($this->currentController, $url[1])){
				$this->currentMethod = $url[1];
				unset($url[1]);
				
			}
		}
		//get parametros
		//$this->params = $url ? array_values($url) : [];
		//test print_r($url);	
		 if(isset($url)){
     		$this->params = array_values($url);
     		} 
     		else{
     			$this->params = [];
 			}
			
		//call a callback with array parameter
		call_user_func_array([$this->currentController,$this->currentMethod], $this->params);
		

	}
	public function getUrl(){
		//echo $_GET['url'];
		$url = $_GET['url'];
		if(isset($_GET['url'])){
			$url = rtrim($_GET['url'],'/'); //elimina espacios
			$url = filter_var($url, FILTER_SANITIZE_URL); //elimina carateres invalidos de una URL
			$url = explode('/', $url);  //convierte string en array segun el caracter delimitador (/)
			return $url;

		}
	}
}