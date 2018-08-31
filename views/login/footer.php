
<script type="text/javascript" src="<?php echo URL; ?>public/js/toastr.min.js"></script>
<?php 
    if(isset($this->publicjs)){
        foreach($this->publicjs as $publicjs){
            echo '<script type="text/javascript" src="'.URL.'public/'.$publicjs.'"></script>';
        }
    } 
	if(isset($this->js)){
		foreach($this->js as $js){
			echo '<script type="text/javascript" src="'.URL.'views/'.$js.'"></script>';
		}
	}
    
	if(isset($_SESSION['message'])){
        echo '<script type="text/javascript">toastr["' . $_SESSION['message_type'] . '"]("' . $_SESSION['message'] . '")</script>';
        //var_dump($this->response);
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    } 
?>
</body>
</html>