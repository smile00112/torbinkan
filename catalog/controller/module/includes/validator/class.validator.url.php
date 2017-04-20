<?php

class validator_url extends validator {
	
	public function validate($value) {
		
		$validate = TRUE;
		
		if ($value != '') {
			$validate = $this->valid_url($value, TRUE);
		}
		
		return $validate;	
	}
	
	private function valid_url($url, $absolute = FALSE) {
		
		if ($absolute) {
			return (bool) preg_match("
			/^
			  (?:ftp|https?|feed):\/\/                                # Look for ftp, http, https or feed schemes
			  (?:                                                     # Userinfo (optional) which is typically
				(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*      # a username or a username and password
				(?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@          # combination
			  )?
			  (?:
				(?:[a-z0-9\-\.]|%[0-9a-f]{2})+                        # A domain name or a IPv4 address
				|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\])         # or a well formed IPv6 address
			  )
			  (?::[0-9]+)?                                            # Server port number (optional)
			  (?:[\/|\?]
				(?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})   # The path and query (optional)
			  *)?
			$/xi", $url);
		} else {
			return (bool) preg_match("/^(?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})+$/i", $url);
		}
		
	}
	
}
?>