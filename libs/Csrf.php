<?php
/**
 * A simple CSRF class to protect forms against CSRF attacks. The class uses
 * PHP sessions for storage.
 * 
 * @author Debabrat Sharma
 *
 */
class CSRF
{
	/**
	 * The namespace for the session variable and form inputs
	 * @var string
	 */
	private $namespace;
	/**
	 * Initializes the session variable name, starts the session if not already so,
	 * and initializes the token
	 * 
	 * @param string $namespace
	 */
	public function __construct($namespace = '_csrf_ds'){
		$this->namespace = $namespace;
		if (session_id() === ''){
			session_start();
		}
		$this->ds_setToken();
	}
	/**
	 * Return the token from persistent storage
	 * @return string
	 */
	public function ds_getToken(){
		return $this->ds_readTokenFromStorage();
	}
	/**
	 * Verify if supplied token matches the stored token
	 * 
	 * @param string $userToken
	 * @return boolean
	 */
	public function ds_isTokenValid($userToken){
    	if (hash_equals($this->ds_readTokenFromStorage(), $userToken)) {
    		return true;
    	}else{
    		return false;
    	}
		//return ($userToken === $this->ds_readTokenFromStorage());
	}
	/**
	 * Echoes the HTML input field with the token, and namespace as the
	 * name of the field
	 */
	public function ds_echoInputField(){
		$token = $this->ds_getToken();
		$input="<input type=\"hidden\" name=\"{$this->namespace}\" value=\"{$token}\" />";
		return $input;
	}
	/**
	 * Verifies whether the post token was set, else dies with error
	 */
	public function ds_verifyRequest(){
		if (!$this->ds_isTokenValid($_POST[$this->namespace])){
			//die("CSRF validation failed.");
			return false;
		}else{
			return true;
		}
	}
	/**
	 * Generates a new token value and stores it in persisent storage, or else
	 * does nothing if one already exists in persisent storage
	 */
	private function ds_setToken(){
		$storedToken = $this->ds_readTokenFromStorage();
		if ($storedToken === ''){
			if (function_exists('mcrypt_create_iv')) {
		        $token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		    } else {
		        $token = bin2hex(openssl_random_pseudo_bytes(32));
		    }
			$this->ds_writeTokenToStorage($token);
		}
	}
	/**
	 * Reads token from persistent sotrage
	 * @return string
	 */
	private function ds_readTokenFromStorage(){
		if (isset($_SESSION[$this->namespace])){
			return $_SESSION[$this->namespace];
		}else{
			return '';
		}
	}
	/**
	 * Writes token to persistent storage
	 */
	private function ds_writeTokenToStorage($token){
		$_SESSION[$this->namespace] = $token;
	}
}
?>