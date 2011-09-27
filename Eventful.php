<?php
	
	/**
	 * @author James Brooks <james@bluebaytravel.co.uk
	 * @package Eventful
	 * @version 0.2
	 */

	class Eventful {
		/**
		 * URI of API
		 * @access public
		 * @var string
		 */
		public $api_root = 'http://api.eventful.com';

		/**
		 * Application key (provided by http://api.eventful.com)
		 * @access public
		 * @var string
		 */
		public $app_key = null;

		/**
		 * Username
		 * @access private
		 * @var string
		 */
		private $user = null;

		/**
		 * Password
		 * @access private
		 * @var string
		 */
		private $password = null;

		/**
		 * User Authentication Key
		 * @access private
		 * @var string
		 */
		private $user_key = null;

		/**
		 * Latest request URI
		 * @access private
		 * @var string
		 */
		private $request_uri = null;

		/**
		 * Latest response data
		 * @access public
		 * @var string
		 */
		public $response_data = null;

		/**
		 * Create a new client
		 * @param string 	app_key
		 */
		function __construct($app_key) {
			$this->app_key = $app_key;
		}

		/**
		 * Login and verify the user connection.
		 * @param string 	user
		 * @param string 	pass
		 */
		function login($user, $password) {
			$this->user = $user;
			
			$this->call('users/login', array( ));
			$data = $this->response_data;
			$nonce = $data['nonce'];

			$response = md5($nonce . ":" . md5($password));

			$args = array(
				'nonce'		=> $nonce,
				'response'	=> $response
			);
		
			$r = $this->call('users/login', $args);

			$this->user_key = (string) $r->user_key;

			return true;
		}

		/**
		 * Call a method of the Eventful API
		 * @param string 	method
		 * @param mixed 	arguments 	optional
		 */
		function call($method, $args = array()) {
			$method = trim($method, '/ ');

			$url = $this->api_root . '/rest/' . $method;
			$this->request_uri = $url;

			$post_args = array(
				'app_key'  => $this->app_key,
				'user'     => $this->user,
				'user_key' => $this->user_key
			);

			foreach ($args as $key => $value) {
				if(is_array($value)) {
					foreach($value as $instance) {
						$post_args[$key] = $instance;
					}
				}else{
					$post_args[$key] = $value;
				}
			}

			$fields_string = "";
			foreach($post_args as $key=>$value) { $fields_string .= $key . '=' . urlencode($value) . "&"; }
			$fields_string = rtrim($fields_string, '&');

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->request_uri);
			curl_setopt($ch, CURLOPT_POST, count($post_args));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return data instead of display to std out

			$cResult = curl_exec($ch);
			$this->response_data = $cResult;

			curl_close($ch);

			// Process result to XML
			$data = new SimpleXMLElement($cResult);

			if($data->getName() === 'error') {
				$error = $data['string'] .  ": " . $data->description;
				$code = $data['string'];
				return false;
			}

			return $data;
		}
	}

?>