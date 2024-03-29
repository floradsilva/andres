<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!class_exists("pinterest_rich_pin_authentication_class")){
	class pinterest_rich_pin_authentication_class{
		
		// Initializing Parameters
		private $appId = "";
		private $appSecId = "";
		private $accessToken = "";
		private $code = "pinterest_rich_pin_code";
		
		/**
		 * Pinterest.com base URL
		 */
		private $PINTEREST_URL = 'https://www.pinterest.com';

		/**
		 * Pinterest.com base URL
		 */
		private $PINTEREST_API_URL = 'https://api.pinterest.com';

		/**
		 * @var boolean
		 */
		public $isLoggedIn = false;

		/**
		 * @var array
		 */
		public $userData = array();

		/**
		 * @var array
		 */
		public $boards = array();

		/**
		 * @var string Pinterest account login
		 */
		private $_login = null;

		/**
		 * @var string Pinterest account password
		 */
		private $_password = null;

		/**
		 * @var string Board ID where the pin should be added to
		 */
		private $_boardId = null;

		/**
		 * @var boolean If true pinterest.com will automatically share new pin on connected facebook account
		 */
		private $_shareFacebook = false;

		/**
		 * @var string Newly created pin ID
		 */
		private $_pinId = null;

		/**
		 * @var string Pinterest App version loaded from pinterest.com
		 */
		private $_appVersion = null;

		/**
		 * @var string CSRF token loaded from pinterest.com
		 */
		private $_csrfToken = null;

		/**
		 * @var array Default requests headers
		 */
		private $_httpHeaders = array();

		/**
		 * @var \GuzzleHttp\Client
		 */
		private $_httpClient = null;

		/**
		 * @var string Pinterest page loaded content
		 */
		protected $_responseContent = null;

		/*
		 * Initialize Guzzle Client and set default variables.
		 */
		public function __construct(){
			// Default HTTP headers for requests
			$this->_httpHeaders = array(
				'Connection' => 'keep-alive',
				'Pragma' => 'no-cache',
				'Cache-Control' => 'no-cache',
				'Accept-Language' => 'en-US,en;q=0.5',
				'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML => like Gecko) Iron/31.0.1700.0 Chrome/31.0.1700.0',
			);
			
			$this->appId = get_option("pinterest_wcrp_app_id");
			$this->appSecId = get_option("pinterest_wcrp_app_secret_id");
			
		}
		
		
		function checkAuth ($appId = "", $appSecId = ""){
			
			$this->appId = $appId;
			$this->appSecId = $appSecId;
			
			$this->_loadContent('/oauth/?response_type=code&client_id='.$this->appId.'scope=read_public,write_public');
			var_dump($this->_responseContent['resource_response']);
			exit;
			
		}
		
		function check_api_login ($appId = "", $secId = ""){
			$this->get_auth_token('/v1/oauth/token?grant_type=authorization_code&client_id='.$this->appId.'&client_secret='.$this->appSecId.'&code='.$this->code);
			var_dump($this->_responseContent['resource_response']);
			exit;
		}
		
		function get_auth_token (){
			
			// $url = '/v1/oauth/token?grant_type=authorization_code&client_id='.$this->appId.'&client_secret='.$this->appSecId.'&code=1234';
			// $api_url = $this->PINTEREST_API_URL.$url;
			$mysiteUrl = urlencode(site_url());
			$url = '/oauth/?response_type=code&redirect_uri='.$mysiteUrl.'&client_id='.$this->appId.'&scope=read_public,write_public&state=768uyFys';
			$api_url = $this->PINTEREST_API_URL.$url;
			
			// Use the curl to get output from api
			$ch = curl_init($api_url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_httpHeaders);
			
			$json = curl_exec($ch);
			curl_close($ch);
			
			$json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json);
			$array = json_decode($json,TRUE);
			
			print("<pre>");
			var_dump($api_url);
			var_dump($array);
			print("</pre>");
			exit;
			
			if(isset($array['responseText']) && $array['responseText'] == "Response was successful"){
				$responseList = $array['responseList'];
				$return = $responseList;
			}
			else if(isset($array['responseType']) && $array['responseType'] == "Error"){
				$return = $array['message'];
			}
			
			var_dump($this->_responseContent['resource_response']);
			exit;
		}
		
		/**
		 * Set Pinterest account login.
		 *
		 * @param string $login
		 * @return \PinterestPinner\Pinner
		 */
		public function setLogin($login){
			$this->_login = $login;

			return $this;
		}

		/**
		 * Set Pinterest account password.
		 *
		 * @param string $password
		 * @return \PinterestPinner\Pinner
		 */
		public function setPassword($password){
			$this->_password = $password;

			return $this;
		}

		/**
		 * Set Pinterest board ID to add pin to.
		 *
		 * @param string $boardId
		 * @return \PinterestPinner\Pinner
		 */
		public function setBoardID($boardId){
			$this->_boardId = $boardId;

			return $this;
		}

		/**
		 * Set pin image URL.
		 *
		 * @param string $image
		 * @return \PinterestPinner\Pinner
		 */
		public function setImage($image){
			$this->_image = $image;

			return $this;
		}
		
		/**
		 * Set pin description.
		 *
		 * @param string $description
		 * @return \PinterestPinner\Pinner
		 */
		public function setDescription($description){
			$this->_description = $description;

			return $this;
		}

		/**
		 * Set pin link.
		 *
		 * @param string $link
		 * @return \PinterestPinner\Pinner
		 */
		public function setLink($link){
			$this->_link = $link;

			return $this;
		}
		
		/**
		 * Get newly created pin ID.
		 *
		 * @return string|boolean
		 */
		public function getPinID(){
			return $this->_pinId ?: false;
		}

		/**
		 * Create a new pin.
		 *
		 * @return string|boolean
		 */
		public function pin(){
			// Reset the pin ID
			$this->_pinId = null;

			$this->_postLogin();
			$this->_postPin();

			$this->_pinId = (is_array($this->_responseContent) and isset($this->_responseContent['resource_response']['data']['id']))
				? $this->_responseContent['resource_response']['data']['id']
				: null;

			$this->_responseContent = null;

			return $this->getPinID();
		}

		/**
		 * Get user's pins.
		 *
		 * @param $boardId
		 * @return array
		 * @throws \PinterestPinner\PinnerException
		 */
		public function getPins($boardId = null){
			$userData = $this->getUserData();
			if (isset($userData['username'])) {
				$response = $this->get_api_response('');
				if ($response->getStatusCode() === 200) {
					$collection = $response->json();
					if (isset($collection['data']['pins'])) {
						if ($boardId) {
							$pins = array();
							foreach ($collection['data']['pins'] as $pin) {
								if ($pin['board']['id'] == $boardId) {
									$pins[] = $pin;
								}
							}
							return $pins;
						}
						return $collection['data']['pins'];
					}
					return array();
				}
			}
			throw new PinnerException('Unknown error while getting pins list.');
		}

		/**
		 * Get user's boards.
		 *
		 * @return array
		 * @throws \PinterestPinner\PinnerException
		 */
		public function getBoards(){
			if (count($this->boards)) {
				return $this->boards;
			}
			
			$this->_loadContent('/v1/boards/?access_token='.$this->accessToken.'&fields=id,name,url');
			$this->boards = array();
			if (
				isset($this->_responseContent['resource_response']['data']['all_boards'])
				and is_array($this->_responseContent['resource_response']['data']['all_boards'])
			) {
				foreach ($this->_responseContent['resource_response']['data']['all_boards'] as $board) {
					if (isset($board['id'], $board['name'])) {
						$this->boards[$board['id']] = $board['name'];
					}
				}
			}
			return $this->boards;
		}
		
		/**
		 * Set cURL url and get the content from curl_exec() call.
		 *
		 * @param string $url
		 * @param array|boolean|null $dataAjax If array - it will be POST request, if TRUE if will be GET, ajax request.
		 * @param string $referer
		 * @return string
		 * @throws \PinterestPinner\PinnerException
		 */
		protected function _loadContent($url){
			$headers = $this->_httpHeaders;
			$response = $this->get_api_response('GET', $url, null, $headers);
			
			$code = (int)substr($response->getStatusCode(), 0, 2);
			if ($code !== 20) {
				throw new PinnerException(
					'HTTP error (' . $url . '): ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase()
				);
			}

			$this->_responseContent = (string)$response->getBody();
			if (substr($this->_responseContent, 0, 1) === '{') {
				$this->_responseContent = @json_decode($this->_responseContent, true);
			}
			$this->_responseHeaders = (array)$response->getHeaders();
		}

		/**
		 * Get Pinterest App Version.
		 *
		 * @return string
		 * @throws \PinterestPinner\PinnerException
		 */
		private function _getAppVersion(){
			if ($this->_appVersion) {
				return $this->_appVersion;
			}

			if (!$this->_responseContent) {
				$this->_loadContent('/login/');
			}

			$appJson = $this->_responseToArray();
			if ($appJson and isset($appJson['context']['app_version']) and $appJson['context']['app_version']) {
				$this->_appVersion = $appJson['context']['app_version'];
				return $this->_appVersion;
			}

			throw new PinnerException('Error getting App Version from "jsInit1" JSON data.');
		}

		/**
		 * Get Pinterest CSRF Token.
		 *
		 * @param string $url
		 * @return string
		 * @throws \PinterestPinner\PinnerException
		 */
		private function _getCSRFToken($url = '/login/'){
			if ($this->_csrfToken) {
				return $this->_csrfToken;
			}

			if (!$this->_responseContent) {
				$this->_loadContent($url);
			}

			if (isset($this->_responseHeaders['Set-Cookie'])) {
				if (is_array($this->_responseHeaders['Set-Cookie'])) {
					$content = implode(' ', $this->_responseHeaders['Set-Cookie']);
				} 
				else {
					$content = (string)$this->_responseHeaders['Set-Cookie'];
				}
				preg_match('/csrftoken=(.*)[\b;\s]/isU', $content, $match);
				if (isset($match[1]) and $match[1]) {
					$this->_csrfToken = $match[1];
					return $this->_csrfToken;
				}
			}

			throw new PinnerException('Error getting CSRFToken.');
		}

		/**
		 * Try to log in to Pinterest.
		 *
		 * @throws \PinterestPinner\PinnerException
		 */
		private function _postLogin(){
			if ($this->isLoggedIn) {
				return;
			}

			$postData = array(
							'data' => json_encode(
										array(
											'options' => array(
												'username_or_email' => $this->_login,
												'password' => $this->_password,
											),
											'context' => new stdClass,
										)
									),
							'source_url' => '/login/',
							'module_path' => 'App()>LoginPage()>Login()>Button(class_name=primary, '
								. 'text=Log In, type=submit, size=large)',
						);
			$this->_loadContent('/resource/UserSessionResource/create/', $postData, '/login/');

			// Force reload CSRF token, it's different for logged in user
			$this->_csrfToken = null;
			$this->_getCSRFToken('/');

			$this->isLoggedIn = true;

			if (
				isset($this->_responseContent['resource_response']['error'])
				and $this->_responseContent['resource_response']['error']
			) {
				throw new PinnerException($this->_responseContent['resource_response']['error']);
			} 
			elseif (
				!isset($this->_responseContent['resource_response']['data'])
				or !$this->_responseContent['resource_response']['data']
			) {
				throw new PinnerException('Unknown error while logging in.');
			}
		}

		/**
		 * Try to create a new pin.
		 *
		 * @throws \PinterestPinner\PinnerException
		 */
		private function _postPin(){
			$postData = array(
				'data' => json_encode(array(
					'options' => array(
						'board_id' => $this->_boardId,
						'description' => $this->_description,
						'link' => $this->_link,
						'share_facebook' => $this->_shareFacebook,
						'image_url' => $this->_image,
						'method' => 'scraped',
					),
					'context' => new stdClass,
				)),
				'source_url' => '/',
				'module_path' => 'App()>ImagesFeedPage(resource=FindPinImagesResource(url='
					. $this->_link . '))>Grid()>GridItems()>Pinnable(url=' . $this->_image
					. ', type=pinnable, link=' . $this->_link . ')#Modal(module=PinCreate())',
			);

			$this->_loadContent('/resource/PinResource/create/', $postData, '/');

			if (
				isset($this->_responseContent['resource_response']['error'])
				and $this->_responseContent['resource_response']['error']
			) {
				throw new PinnerException($this->_responseContent['resource_response']['error']);
			} 
			elseif (
				!isset($this->_responseContent['resource_response']['data']['id'])
				or !$this->_responseContent['resource_response']['data']['id']
			) {
				throw new PinnerException('Unknown error while creating a pin.');
			}
		}

		/**
		 * Get data array from JSON response.
		 *
		 * @return array|bool
		 */
		private function _responseToArray(){
			if (is_string($this->_responseContent)) {
				preg_match(
					'/<script\s*type="application\/json"\s+id=\'jsInit1\'>\s*(\{.+\})\s*<\/script>/isU',
					$this->_responseContent,
					$match
				);
				if (isset($match[1]) and $match[1]) {
					$result = @json_decode($match[1], true);
					if (is_array($result)) {
						return $result;
					}
				}
			}
			return false;
		}

		/**
		 * Make a HTTP request to Pinterest.
		 *
		 * @param string $type
		 * @param string $urlPath
		 * @param null|array $data
		 * @param array $headers
		 * @return \Psr\Http\Message\ResponseInterface
		 */
		private function get_api_response($type = 'GET', $urlPath, $data = null, $headers = array()){
			$response = "";
			
			$accessToken = $this->check_api_login();							// To generate and get the api token
			
			return $response;
		}
	}
}