<?php

class Hash {

	/**
	 * Function to create hashed/salted data from original input
	 * @param  [string] $algo [The algorithm(md5,sha1,whirlpool,etc)]
	 * @param  [string] $data [The data to be encoded]
	 * @param  [string] $salt [The salt to remain same throughout the system]
	 * @return [string]       [The hashed/salted data]
	 */
	public static function createHash($algo,$data,$salt) {
		$context=hash_init($algo,HASH_HMAC,$salt);
		hash_update($context, $data);

		return hash_final($context);
	}

}

?>