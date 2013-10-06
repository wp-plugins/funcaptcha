<?php 
/*
 * FunCaptcha
 * PHP Integration Library
 *
 * @version 0.0.5
 *
 * Copyright (c) 2013 SwipeAds -- http://www.funcaptcha.co
 * AUTHOR:
 *   Kevin Gosschalk
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */
define("FUNCAPTCHA_SERVER", "funcaptcha.co");

// Define class if it does not already exist
if ( ! class_exists('FUNCAPTCHA')):

	class FUNCAPTCHA {
	// Set defaults for values that can be specified via the config file or passed in via __construct.
	protected $funcaptcha_public_key = '';
	protected $funcaptcha_private_key = '';
	protected $funcaptcha_host = 'funcaptcha.co';
	protected $funcaptcha_challenge_url = '';
	protected $funcaptcha_debug = FALSE;
	protected $funcaptcha_api_type = "wp";
	protected $funcaptcha_plugin_version = "0.3.19";
	protected $funcaptcha_security_level = 0;
	protected $session_token;

	protected $version = '0.0.5';

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{		
		$this->funcaptcha_host = FUNCAPTCHA_SERVER;
		
		if ($this->funcaptcha_host == "")
		{
			$this->msgLog("ERROR", "Warning: Host is not set.");
		}
		else
		{
			$this->msgLog("DEBUG", "Set Host: '$this->funcaptcha_host'");
		}
	}

	/**
	 * Returns FunCaptcha HTML to display in form
	 *
	 * @param string $public_key - FunCaptcha public key
	 * @return string
	 */
	public function getFunCaptcha($public_key)
	{

		$this->funcaptcha_public_key = $public_key;
		if ($this->funcaptcha_public_key == "" || $this->funcaptcha_public_key == null)
		{
			$this->msgLog("ERROR", "Warning: Public key is not set.");
		}
		else
		{
			$this->msgLog("DEBUG", "Public key: '$this->funcaptcha_public_key'");
		}


		//send your public key, your site name, the users ip and browser type.
		$data = array(
			'public_key'		=> $this->funcaptcha_public_key,
			'site' 				=> $_SERVER["SERVER_NAME"],
			'userip'	 		=> $_SERVER["REMOTE_ADDR"],
			'userbrowser'		=> $_SERVER['HTTP_USER_AGENT'],
			'api_type'			=> $this->funcaptcha_api_type,
			'plugin_version'	=> $this->funcaptcha_plugin_version,
			'security_level'	=> $this->funcaptcha_security_level
		);

		//get session token.
		$session = $this->doPostReturnObject('/fc/gt/', $data);
		$this->session_token = $session->token;
		$this->funcaptcha_challenge_url = $session->challenge_url;

		if (!$this->funcaptcha_challenge_url)
		{
			$this->msgLog("ERROR", "Warning: Couldn't retrieve challenge url.");
		}
		else
		{
			$this->msgLog("DEBUG", "Challenge url: '$this->funcaptcha_challenge_url'");
		}
		
		if (!$this->session_token)
		{
			$this->msgLog("ERROR", "Warning: Couldn't retrieve session.");
		}
		else
		{
			$this->msgLog("DEBUG", "Session token: '$this->session_token'");
		}

		if ($this->session_token && $this->funcaptcha_challenge_url && $this->funcaptcha_host) 
		{
			//return html to generate captcha.
			$url = "https://";
			$url.= $this->funcaptcha_host;
			$url.= $this->funcaptcha_challenge_url;
			$url.= "?cache=" . time();
			return "<div id='FunCaptcha'></div><input type='hidden' id='FunCaptcha-Token' name='fc-token' value='" . $this->session_token . "'><script src='". $url ."' type='text/javascript' language='JavaScript'></script>";
		}
		else
		{
			//if failed to connect, display helpful message.
			$style = "padding: 10px; border: 1px solid #b1abb2; background: #f1f1f1; color: #000000;";
			$message = "The CAPTCHA cannot be displayed. This may be a configuration or server problem. You may not be able to continue. Please visit our <a href='http://funcaptcha.co/status' target='_blank'>status page</a> for more information or to contact us.";
			echo "<p style=\"$style\">$message</p>\n";
		}
	}

	/**
	 * Set security level of FunCaptcha
	 *
	 * Possible options are:
	 * 0 - Automatic-- security rises for suspicious users
	 * 20 - Enhanced security-- always use Enhanced security
	 *
	 * See our website for more details on these options
	 *
	 * @param int $security - Security level
	 * @return boolean
	 */
	public function setSecurityLevel($security) {
		$this->funcaptcha_security_level = $security;
		$this->msgLog("DEBUG", "Security Level: '$this->funcaptcha_public_key'");
	}

	/**
	 * Verify if user has solved the FunCaptcha
	 *
	 * @param string $private_key - FunCaptcha private key
	 * @return boolean
	 */
	public function checkResult($private_key) {
		$this->funcaptcha_private_key = $private_key;

		$this->msgLog("DEBUG", ("Session token to check: " . $_POST['fc-token']));

		if ($this->funcaptcha_private_key == "")
		{
			$this->msgLog("ERROR", "Warning: Private key is not set.");
		}
		else
		{
			$this->msgLog("DEBUG", "Private key: '$this->funcaptcha_private_key'");
		}

		if ($_POST['fc-token']) {
			$data = array(
				'private_key' => $this->funcaptcha_private_key,
				'session_token' => $_POST['fc-token']
			);
			$result = $this->doPostReturnObject('/fc/v/', $data);
		}
		else
		{
			$this->msgLog("ERROR", "Unable check the result.  Please check that you passed in the correct public, private keys.");
		}
		return $result->solved;
	}

	/**
	 * Internal function - does HTTPs post and returns result.
	 *
	 * @param string $url_path - server path
	 * @param array $data - data to send
	 * @return object
	 */
	protected function doPostReturnObject($url_path, $data) {
		$result = "";
		$fields_string = "";
		$data_string = "";
		foreach($data as $key=>$value) {
			if (is_array($value)) {
				if ( ! empty($value)) {
					foreach ($value as $k => $v) {
						$data_string .= $key . '['. $k .']=' . $v . '&';
					}
				} else {
					$data_string .= $key . '=&';
				}
			} else {
				$data_string .= $key.'='.$value.'&';
			}
		}
		rtrim($data_string,'&');

		$curl_url = "https://";
		$curl_url.= $this->funcaptcha_host;
		$curl_url.= $url_path;

		// Log it.
		$this->msgLog("DEBUG", "cURl: url='$curl_url', data='$data_string'");

		// Initialize cURL session.
		if ($ch = curl_init($curl_url))
		{
			// Set the cURL options.
			curl_setopt($ch, CURLOPT_POST, count($data));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			// Execute the cURL request.
			$result = curl_exec($ch);			
			curl_close($ch);
			$result = json_decode($result);
		}
		else
		{
			// Log it.
			$this->msgLog("DEBUG", "Unable to enable cURL: url='$curl_url'");
		}

		return $result;
	}

	/**
	 * Determine whether or not cURL is available to use.
	 *
	 * @return boolean
	 */
	protected function can_use_curl()
	{
		if (function_exists('curl_init') and function_exists('curl_exec'))
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Log a message
	 *
	 * @param string $type - type of error
	 * @param string $message - message to log
	 * @return null
	 */
	protected function msgLog($type, $message)
	{

		// Is it an error message?
		if (FALSE !== stripos($type, "error"))
		{
			error_log($message);
		}

		// Build the full message.
		$debug_message = "<p style='padding: 10px; border: 1px solid #2389d1; background: #43c0ff; color: #134276;'><strong>$type:</strong> $message</p>\n";
		
		// Output to screen if in debug mode
		if ($this->funcaptcha_debug)
		{
			echo "$debug_message";
		}
	}

	/**
	 * Debug mode, enables showing output of errors.
	 *
	 * @param boolean $mode debug state
	 */
	public function debugMode($mode)
	{
		$this->funcaptcha_debug = $mode;
	}

}
endif;