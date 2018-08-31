<?php
class View {
	function __construct(){
		//echo "View controller<br/>";
	}
	public function render($name,$noInclude=false){
		if($noInclude == true){
			require 'views/'.$name.'.php';
		}else{
			require 'views/header.php';
			require 'views/'.$name.'.php';
			require 'views/footer.php';
		}
	}
	public function renderAdmin($name,$noInclude=false){
		if($noInclude == true){
			require 'views/'.$name.'.php';
		}else{
			require 'views/header.php';
			require 'views/leftSidebar.php';
			require 'views/'.$name.'.php';
			require 'views/footer.php';
		}
	}
}


?>