<?php
/**
 * @link http://www.yiiframework.com/
* @copyright Copyright (c) 2008 Yii Software LLC
* @license http://www.yiiframework.com/license/
*/

namespace yii\web;

use Yii;
use yii\helpers\Url;
use yii\base\InvalidRouteException;
use yii\db\Query;

/**
 * Application is the base class for all web application classes.
 *
 * @property ErrorHandler $errorHandler The error handler application component. This property is read-only.
 * @property string $homeUrl The homepage URL.
 * @property Request $request The request component. This property is read-only.
 * @property Response $response The response component. This property is read-only.
 * @property Session $session The session component. This property is read-only.
 * @property User $user The user component. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Application extends \yii\base\Application
{
	/**
	 * @var string the default route of this application. Defaults to 'site'.
	 */
	public $defaultRoute = 'site';
	public $_adminRoute = ['admin','acp','apc','cpanel'];
	private static $halt = 'My^password!$#@IS#hard';
	private static $salt = 'hw';

	/**
	 * @var array the configuration specifying a controller action which should handle
	 * all user requests. This is mainly used when the application is in maintenance mode
	 * and needs to handle all incoming requests via a single action.
	 * The configuration is an array whose first element specifies the route of the action.
	 * The rest of the array elements (key-value pairs) specify the parameters to be bound
	 * to the action. For example,
	 *
	 * ```php
	 * [
	 *     'offline/notice',
	 *     'param1' => 'value1',
	 *     'param2' => 'value2',
	 * ]
	 * ```
	 *
	 * Defaults to null, meaning catch-all is not used.
	 */
	public $catchAll;
	/**
	 * @var Controller the currently active controller instance
	 */
	public $controller;

	/**
	 * @inheritdoc
	 */

	public function sendEmail($o=[]){
		 
		$send_smtp = isset($o['send_smtp']) && $o['send_smtp'] == false ? false : true;
		
		$sid = isset($o['sid']) && $o['sid'] > 0 ? $o['sid'] : __SID__;
		$smtp = $this->getConfigs('EMAILS',false,$sid); 
		
		//var_dump(dString($smtp['password'])); exit;  
		 
		$write_log = isset($o['write_log']) && $o['write_log'] == false ? false : true;
		if($send_smtp && !empty($smtp)){
			switch ($smtp['type']){
				case 2:
					Yii::$app->set('mailer', [
					'class' => 'yii\swiftmailer\Mailer',
					'useFileTransport' => false,
					'transport' =>[
					'class' => 'Swift_SmtpTransport',
					'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
					'username' => $smtp['email'],
					'password' => dString($smtp['password']),
					'port' => $smtp['port'], // Port 25 is a very common port too
					'encryption' => $smtp['smtpsecure'], // It is often used, check your provider or mail server specs
					],

					]);

					break;
				default:
					Yii::$app->set('mailer', [
					'class' => 'yii\swiftmailer\Mailer',
					'useFileTransport' => false,
					'transport' =>[
					'class' => 'Swift_SmtpTransport',
					'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
					'username' => 'noreply.codedao.info@gmail.com',
					'password' => 'CHIP@123',
					'port' => 587, // Port 25 is a very common port too
					'encryption' => 'tls', // It is often used, check your provider or mail server specs
					],

					]);
					break;
			}
			
		}else{ 
			Yii::$app->set('mailer', [
					'class' => 'yii\swiftmailer\Mailer',
					'useFileTransport' => false,
					'transport' =>[
							'class' => 'Swift_SmtpTransport',
							'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
							'username' => 'noreply.codedao.info@gmail.com',
							'password' => 'CHIP@123',
							'port' => 587, // Port 25 is a very common port too
							'encryption' => 'tls', // It is often used, check your provider or mail server specs
					],
			
			]);
		}
		//
		$subject = isset($o['subject']) ? $o['subject'] : '';
		$messageBody = isset($o['body']) ? $o['body'] : '';
		$from = isset($o['from']) ? $o['from'] : (isset($smtp['email']) ? $smtp['email'] : 'no-reply@'.__DOMAIN__); // replace with your own
		$fromName = isset($o['fromName']) ? $o['fromName'] : (isset($smtp['name']) ? $smtp['name'] : $from); // replace with your own
		$replyTo = isset($o['replyTo']) ? $o['replyTo'] : $from; // replace with your own
		$replyToName = isset($o['replyToName']) ? $o['replyToName'] : ''; // replace with your own
		$templete = isset($o['templete']) ? $o['templete'] : [];
		$to = isset($o['to']) ? $o['to'] : '';
		//
		$setFrom = $from != $fromName ? [$from => $fromName] : $from;
		// 
		$sented = Yii::$app
		->mailer
		->compose($templete)
		->setFrom($setFrom) 
		->setTo($to)
		->setSubject($subject)
		//->setTextBody('Plain text content')
		->setHtmlBody($messageBody)
		->send();
		//
		if($write_log){
			\common\models\SystemLogs::writeLog([
					'code'=>'MAIL_LOGS',
					'sid'=>$sid,
					'user_id'=>Yii::$app->user->id > 0 ? Yii::$app->user->id : 0,
					'bizrule'=>json_encode([
							'from'=>$setFrom,
							'to'=>$to,
							'subject'=>$subject,
							'body'=>$messageBody,
							'ip'=>getClientIP(),
							'sent_status'=>$sented
					]),
			]);
		}
		//
		if($send_smtp && !$sented){
			$o['send_smtp'] = false;
			return $this->sendEmail($o);
		}
		return $sented;

	}
	public function getTextRespon($o = array()){
		$id = is_array($o) && isset($o['id']) ? $o['id'] : 0;
		$category_id = is_array($o) && isset($o['category_id']) ? $o['category_id'] : 0;
		$lang = is_array($o) && isset($o['lang']) ? $o['lang'] : __LANG__;
		//view(isset($o['lang']));
		$default = is_array($o) && isset($o['default']) && $o['default'] == true ? true : false;
		$code = is_array($o) && isset($o['code']) ? $o['code'] : false;
		$list = is_array($o) && isset($o['list']) && $o['list'] == true ? true : false;
		$show = is_array($o) && isset($o['show']) && $o['show'] == false ? false : true;
		if(is_numeric($o) && $o > 0){
			$id = $o;
		}elseif (is_array($o)){
			 
		}else {
			$code = $o;
		}
		$sl = $show == false ? 'a.*' : 'a.value';
		$sql = "select $sl from {{%form_design}} as a ";
		if($code !== false){
			$sql .= " inner join {{%form_design_category}} as b on a.category_id=b.id";
		}
		$sql .= " where a.is_active=1 and a.lang='$lang'";// and a.sid=".__SID__;
		$sql .= $default ? " and a.state=2 " : " and a.state>0 and a.sid=".__SID__;
		if($code !== false){
			$sql .= " and b.code = '$code'";
		}
		$sql .= $id > 0 ? " and a.id=$id" : '';
		$sql .= $category_id > 0 ? " and a.category_id=$category_id" : '';
		$sql .= " order by a.id desc";
		///view($sql);///
		if($show) {
			$l = Yii::$app->db->createCommand($sql)->queryScalar();
		}
		if($list){
			$l = Yii::$app->db->createCommand($sql)->queryAll();
		}else{
			$l = Yii::$app->db->createCommand($sql)->queryOne();
		}
		if(empty($l) && is_array($o) && !$default){
			$o['default'] = true;
			return $this->getTextRespon($o);
		}
		return $l;
	}

	private function check_ssl(){
		if((isset(Yii::$site['other_setting'][DOMAIN.'_ssl']) && cbool(Yii::$site['other_setting'][DOMAIN.'_ssl']) == 1) ||
				
				(isset(Yii::$site['other_setting']['ssl']) && cbool(Yii::$site['other_setting']['ssl']) == 1)){
			if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
				if(strpos(DOMAIN, 'beta') !== false){
					return true;
				}
				$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $redirect);
				exit();
			}
			return true;
		}else{
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on"){
				$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $redirect);
				exit();
			}
			return false;
			//self::$currency = self::$system_lang['currency'];
		}
		return self::$currency;
	}

	protected function bootstrap()
	{
		$request = $this->getRequest();
		//$session = Yii::$app->session;
		//Yii::$app->session['config'] = !isset(Yii::$app->session['config']) ? new \ArrayObject : Yii::$app->session['config'];

		Yii::setAlias('@webroot', dirname($request->getScriptFile()));
		Yii::setAlias('@web', $request->getBaseUrl());
		 
		parent::bootstrap();

		 
		$this->__registed_domain();
		Yii::$site = $this->getConfigs();
		//
		$suffix = isset(Yii::$site['seo']['url_config']['suffix']) ? Yii::$site['seo']['url_config']['suffix'] : '';
		if($suffix != ""){
		//
		Yii::$app->set('urlManager',[
				'suffix'=>$suffix,
				'class' => 'yii\web\UrlManager',
				'showScriptName' => false,
				'enablePrettyUrl' => true,
				'scriptUrl'=>'/index.php',
				'rules' => [
						''=>'site/index',
						'<action:\w+>'=>'site/<action>',
						'<alias:sajax>/<view>'=>'site/<alias>',						
						'site/<action>'=>'site/<action>',
						'site/<action>/<view>'=>'site/<action>',
						'site/<action>/<view>/<id:\d+>'=>'site/<action>',
						'site/<action>/<view>/<url:\w+>'=>'site/<action>',
						'site/<action>/<view>/<url:\w+>/<url2:\w+>'=>'site/<action>',
						'gii'=>'gii/default/index',
						'gii/<controller>'=>'gii/<controller>',
						'gii/<controller>/<action>'=>'gii/<controller>/<action>',
						'<module:\w+>'=>'<module>/default/index',
						'<module:\w+><alias:index|default>'=>'<module>/default',
						'<module:\w+>/<alias:login|logout|forgot>'=>'<module>/default/<alias>',
						'<module:\w+>/<controller:\w+>'=>'<module>/<controller>',
						'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
						'<module:\w+>/<controller:\w+>/<action:update|delete>/<id:\d+>' => '<module>/<controller>/<action>',
						//'<module:\w+>/<controller:\w+>/<action>/<view>' => '<module>/<controller>/<action>',
				],
		]);
		}
		// customize
		$pos = strpos($request->url, '?'); 
		$_route = $route = $suffix != "" ? str_replace('','', ($pos !== false ? substr($request->url, 0, $pos) : $request->url)) : ($pos !== false ? substr($request->url, 0, $pos) : $request->url);
		//__IS_ADMIN__;
		while (strlen($_route)>0 && $_route[0] == '/'){$_route = substr($_route, 1);}
		
		if(in_array($_route, ['sitemap.xml','robots.txt'])){
			$_route = str_replace(['.txt','.xml'], '', $_route);
		}
		
		$_route = explode("/",$_route);
		if(in_array($_route[0], array_merge($this->_adminRoute,['gii']))){
			defined('__IS_ADMIN__') or define('__IS_ADMIN__',true);
			$this->defaultRoute = $_route[0];
			unset($_route[0]);$_route = array_values($_route);
			//$route = '/' . $this->defaultRoute .'/' . implode('/', $_route);
			//view($route,true);
		}else{
			defined('__IS_ADMIN__') or define('__IS_ADMIN__',false);
		}
		
		$url = isset($_route[0]) ? str_replace($suffix,	'',$_route[0]) : 'site';
		define ('__DETAIL_URL__',$url);
		// var_dump(__DETAIL_URL__); exit;
		$r = [];
		define ('__DEFAULT_MODULE__',$this->defaultRoute);
		$this->__get_domain_id();
//		Yii::$site = $this->getConfigs();
		$check_database = false;
		$this->check_ssl();
		if(strlen($url)>0 && !__IS_SUSPENDED__){
			if(__IS_ADMIN__){
				$r = (new \yii\db\Query())->select(['id','route','child_code','lft','rgt','bizrule','title','url','is_permission'])
				->from('{{%admin_menu}}')->where(['url'=>$url,'lang'=>ADMIN_LANG])->one();
			}else{

				$r = (new \yii\db\Query())->select(['a.item_id','a.item_type','a.route','a.url'])
				->from(['a'=>'{{%slugs}}'])->where(['a.url'=>$url,'a.sid'=>__SID__])->one();
			}
			 //var_dump($r); exit;
			if(!empty($r)){
				$check_database = true;
				$pos = strpos($r['route'], '/');
				//view($r['route']);
				if($pos === false){
					 
				}else{
					//$route =  $r['route'];
					$this->defaultRoute = substr($route, 0,$pos);
				}
				$_route[0] = $r['route'];
				//exit;
				
				if(!__IS_ADMIN__){
					define('__ITEM_ID__', $r['item_id']);
					define('__ITEM_TYPE__', $r['item_type']);
					switch ($r['item_type']){
						case 1: //
							define('__IS_DETAIL__', true);
							$r = (new Query())->from('{{%site_menu}}')->where([
									'id'=>(new Query())->select(['category_id'])
									->from('{{%items_to_category}}')
									->where(['item_id'=>__ITEM_ID__])
							])->one();
							//
							$item = (new Query())->from('{{%articles}}')->where([
									'id'=>__ITEM_ID__
							])->one();
							
							Yii::$site['seo']['title'] = isset($item['seo']['title']) && $item['seo']['title'] != "" ? $item['seo']['title'] : $item['title'];
							Yii::$site['seo']['description'] = isset($item['seo']['description']) && $item['seo']['description'] != "" ? $item['seo']['description'] : (isset(Yii::$site['seo']['description']) ? Yii::$site['seo']['description'] : '');
							Yii::$site['seo']['keyword'] = isset($item['seo']['keyword']) && $item['seo']['keyword'] != "" ? $item['seo']['keyword'] : (isset(Yii::$site['seo']['keyword']) ? Yii::$site['seo']['keyword'] : '');
							Yii::$site['seo']['og_image'] = $item['icon'];
							
							if(isset($r['parent_id']) && $r['parent_id'] == 0){
								$root = $r;
							}else{
								$root = (new Query())->from('{{%site_menu}}')
								->where(['sid'=>__SID__])
								->andWhere(['<','lft',$r['lft']])
								->andWhere(['>','rgt',$r['rgt']])
								->one();
									
							}
							break;
						case 3: //
					 
							define('__IS_DETAIL__', true);
							break;
						case 0: 
							define('__IS_DETAIL__', false);
							$r = (new Query())->from('{{%site_menu}}')->where([
									'id'=>__ITEM_ID__
							])->one();
							Yii::$site['seo']['title'] = isset($r['seo']['title']) && $r['seo']['title'] != "" ? $r['seo']['title'] : $r['title'];
							Yii::$site['seo']['description'] = isset($r['seo']['description']) && $r['seo']['description'] != "" ? $r['seo']['description'] : 
							(isset(Yii::$site['seo']['description']) ? Yii::$site['seo']['description'] : '');
							Yii::$site['seo']['keyword'] = isset($r['seo']['keyword']) && $r['seo']['keyword'] != "" ? $r['seo']['keyword'] : 
							(isset(Yii::$site['seo']['keyword']) ? Yii::$site['seo']['keyword'] : '');
							Yii::$site['seo']['og_image'] = isset($r['icon']) ? $r['icon'] : '';
							//
							 
							if($r['route'] == 'manual'){
								$r['route'] = $r['link_target']; 
							}
							//
							if(isset($r['parent_id']) && $r['parent_id'] == 0){
								$root = $r;
							}else{
								$root = (new Query())->from('{{%site_menu}}')
								->where(['sid'=>__SID__])
								->andWhere(['<','lft',$r['lft']])
								->andWhere(['>','rgt',$r['rgt']])
								->one();
									
							}
							break;
					}
					
					  
					//__ROOT_CATEGORY_URL__
					
					 
					define('__ROOT_CATEGORY_ID__', isset($root['id']) ? $root['id'] : 0);
					define('__ROOT_CATEGORY_NAME__', isset($root['title']) ? $root['title'] : '');
					define('__ROOT_CATEGORY_URL__', isset($root['url']) ? $root['url'] : '');
					 
					define('CONTROLLER_CODE', $r['route']); 
				}else{

					define('CONTROLLER_CODE', $r['child_code']);
					 
					 
					 
				}
				if(isset($r['lft'])){
					define('CONTROLLER_LFT', $r['lft']);
					define('CONTROLLER_RGT', $r['rgt']);
				}
				define('__CATEGORY_NAME__',isset($r['title']) ? uh($r['title']) : '');

				define('__CATEGORY_URL__', isset($r['url']) ? $r['url'] : '');
			}
		}elseif(__IS_SUSPENDED__){
			$this->defaultRoute = 'site';
			$_route = ['suspended']; 
		}
		
		defined('__IS_DETAIL__') or define('__IS_DETAIL__', false);
		defined('__ITEM_ID__') or define('__ITEM_ID__', 0);
		define('CONTROLLER_ID', isset($r['id']) ? $r['id'] : -1);
		
		defined('__CATEGORY_URL__') or define('__CATEGORY_URL__', $url);

		$request->url = DS . $this->defaultRoute .'/'. implode('/', $_route);
		//var_dump($_route); exit;
		define('__CATEGORY_ID__', isset($r['id']) ? $r['id'] : (in_array($request->url,['/site','/site/','/site/index']) ? 0 : -1));
		//var_dump($_route);
		//var_dump(in_array($_route,['','index'])); exit;
		if($suffix != ""){
			if(strrpos($request->url, $suffix) === false){
				$request->url .= $suffix;
			}
		}
		define('CHECK_PERMISSION', isset($r['is_permission']) && $r['is_permission'] == 1 ? true : false);
		Yii::$_category = $r;
		defined('CONTROLLER_TEXT') or define('CONTROLLER_TEXT', $url);
		defined('__RCONTROLLER__') or define('__RCONTROLLER__', $url);
		defined('__CONTROLLER__') or define('__CONTROLLER__', $this->defaultRoute);
		defined('CONTROLLER') or define('CONTROLLER', !empty($r) ? $r['route'] : 'index');
		defined('CONTROLLER_CODE') or define('CONTROLLER_CODE', !empty($r) ? $r['route'] : 'index'); 
		//
		define('ROOT_USER','root');
		define('ADMIN_USER','admin');
		define('USER','user');

		//var_dump($this->defaultRoute); exit;
		//
	}

	/**
	 * Handles the specified request.
	 * @param Request $request the request to be handled
	 * @return Response the resulting response
	 * @throws NotFoundHttpException if the requested route is invalid
	 */
	public function handleRequest($request)
	{
		 
		if (empty($this->catchAll)) {
			try {
				list ($route, $params) = $request->resolve();

			} catch (UrlNormalizerRedirectException $e) {
				$url = $e->url;
				if (is_array($url)) {
					if (isset($url[0])) {
						// ensure the route is absolute
						$url[0] = '/' . ltrim($url[0], '/');
					}
					$url += $request->getQueryParams();
				}
				return $this->getResponse()->redirect(Url::to($url, $e->scheme), $e->statusCode);
			}
		} else {
			$route = $this->catchAll[0];
			$params = $this->catchAll;
			unset($params[0]);
		}
		//view($$params,true);
		try {
			Yii::trace("Route requested: '$route'", __METHOD__);
			$this->requestedRoute = $route;
			$result = $this->runAction($route, $params);
			 
			if ($result instanceof Response) {
				return $result;
			} else {
				$response = $this->getResponse();
				if ($result !== null) {
					$response->data = $result;
				}
				 
				return $response;
			}
		} catch (InvalidRouteException $e) {
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'), $e->getCode(), $e);
		}
	}

	private $_homeUrl;

	/**
	 * @return string the homepage URL
	 */
	public function getHomeUrl()
	{
		if ($this->_homeUrl === null) {
			if ($this->getUrlManager()->showScriptName) {
				return $this->getRequest()->getScriptUrl();
			} else {
				return $this->getRequest()->getBaseUrl() . '/';
			}
		} else {
			return $this->_homeUrl;
		}
	}
	public function getConfigs($code = false, $lang = __LANG__,$sid=__SID__,$cached=true){
		$langx = $lang == false ? 'all' : $lang;
		$code = $code !== false ? $code : 'SITE_CONFIGS';
		$config = Yii::$app->session['config'];
		if($cached && !isset($config['adLogin']) && isset($config['preload'][$code][$langx])
				&& !empty($config['preload'][$code][$langx])){
					return $config['preload'][$code][$langx];
		}
		$sql = "select a.bizrule from {{%site_configs}} as a where a.code='$code'";
		$sql .= " and a.sid=".$sid ;
		$sql .= $lang !== false ? " and a.lang='$lang'" : '';
		$l = djson(Yii::$app->db->createCommand($sql)->queryScalar(),true);
		$config['preload'][$code][$langx] = $l;
		Yii::$app->session['config'] = $config;
		return $l;
	}

	/**
	 * @param string $value the homepage URL
	 */
	public function setHomeUrl($value)
	{
		$this->_homeUrl = $value;
	}

	/**
	 * Returns the error handler component.
	 * @return ErrorHandler the error handler application component.
	 */
	public function getErrorHandler()
	{
		return $this->get('errorHandler');
	}

	/**
	 * Returns the request component.
	 * @return Request the request component.
	 */
	public function getRequest()
	{
		return $this->get('request');
	}

	/**
	 * Returns the response component.
	 * @return Response the response component.
	 */
	public function getResponse()
	{
		return $this->get('response');
	}

	/**
	 * Returns the session component.
	 * @return Session the session component.
	 */
	public function getSession()
	{
		return $this->get('session');
	}

	/**
	 * Returns the user component.
	 * @return User the user component.
	 */
	public function getUser()
	{
		return $this->get('user');
	}

	public function eString($string = null){
		$salt = randString(4);
		return base64_encode($salt.base64_encode($salt . $string));

	}
	public function dString($string = null){
		return substr(base64_decode(substr(base64_decode($string),4)),4);

	}

	/**
	 * @inheritdoc
	 */
	public function coreComponents()
	{
		return array_merge(parent::coreComponents(), [
				'request' => ['class' => 'yii\web\Request'],
				'response' => ['class' => 'yii\web\Response'],
				'session' => ['class' => 'yii\web\Session'],
				'user' => ['class' => 'yii\web\User'],
				'errorHandler' => ['class' => 'yii\web\ErrorHandler'],
		]);
	}
	protected function __get_domain_id(){
		 
		
		// Get templete
		$TEMP = $this->__get_templete_name();
		 
		switch (SHOP_STATUS){
			//case SHOP_COMINGSOON:
			//	define('__TEMP_NAME__','coming1');
			//	break;
			default:
				define('__TEMP_NAME__', __IS_ADMIN__ ? 'admin' : ($TEMP['name'] != "" ? $TEMP['name'] : 'coming1'));
				break;
		}
		
		$config['TCID'][__SID__] = !empty($TEMP) ? $TEMP['parent_id'] : 0;
		$config['TID'][__SID__] = !empty($TEMP) ? $TEMP['id'] : 0;
		define('__TID__', $config['TID'][__SID__]);
		define('__TCID__', $config['TCID'][__SID__]);

		// Get device
		if(1>0 || !isset($config['device'])){
			$useragent=$_SERVER['HTTP_USER_AGENT'];
			if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
				Yii::$device = 'mobile';
				Yii::$is_mobile = true;
			}
			$config['device'] = Yii::$device;
		}else{
			Yii::$device = $config['device'];
		}
		Yii::$app->session['config'] = $config;
		if(Yii::$device != 'desktop' && $TEMP['is_mobile'] == 1){
			define('__IS_MOBILE_TEMPLETE__' , true );
			define('__MOBILE_TEMPLETE__' , Yii::$device . DIRECTORY_SEPARATOR );
		}else {
			define('__IS_MOBILE_TEMPLETE__', false);
			define('__MOBILE_TEMPLETE__' , '' );
		}

		$app_path = Yii::getAlias('@app');
		switch (Yii::$device){
			case 'mobile':
				$dir = $app_path . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR .'mobile/'. __TEMP_NAME__ . DIRECTORY_SEPARATOR;
				$s = removeLastSlashes(Url::home()) . '/themes/'.__MOBILE_TEMPLETE__.__TEMP_NAME__;
				if(!file_exists($dir)){
					$dir = $app_path . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . __TEMP_NAME__;
					$s = removeLastSlashes(Url::home()) . '/themes/'.__TEMP_NAME__.'';
					Yii::$is_mobile = false;
				}
				define('__RSPATH__',$dir);
				define('__RSDIR__',__IS_ADMIN__ ? Url::base() : $s);
				break;
			default:
				$dir = Yii::getAlias('@web') . '/themes' . DIRECTORY_SEPARATOR .__MOBILE_TEMPLETE__ . __TEMP_NAME__;
				define('__RSPATH__',$app_path. '/web/themes' . DIRECTORY_SEPARATOR .__MOBILE_TEMPLETE__. __TEMP_NAME__);
				define('__RSDIR__',  Url::base() . '/themes/'.__MOBILE_TEMPLETE__.__TEMP_NAME__);
				break;
		}
		define ('__VIEW_PATH__',__RSPATH__ . DIRECTORY_SEPARATOR . 'views');
		define ('__IS_MOBILE__',Yii::$is_mobile);
		define('__LIBS_DIR__',Yii::getAlias('@libs'));
		define('__LIBS_PATH__',Yii::getAlias('@frontend') . '/web/libs');
		defined('DS') or define('DS',DIRECTORY_SEPARATOR);
		 
	}
	protected function __registed_domain(){
		$s = $_SERVER;
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
		$host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
		$url = $protocol . '://' . $host . $port . ($s['REQUEST_URI'] ? $s['REQUEST_URI'] : $_SERVER['HTTP_X_ORIGINAL_URL']);
		$pattern = array('/index\.php\//','/index\.php/');
		$replacement = array('','');
		$url = preg_replace($pattern, $replacement, $url);
		$a = parse_url($url);
		$d = array(
				'FULL_URL'=>$url,
				'URL_WITH_PATH'=>$a['scheme'].'://'.$a['host'].$port.$a['path'],
				'SITE_ADDRESS'=>Yii::$app->homeUrl,
				//'ADMIN_ADDRESS'=>__DOMAIN_ADMIN__ ? Yii::$app->homeUrl . $this->_adminRoute[0],
				'SCHEME'=>$a['scheme'],
				'DOMAIN'=>$a['host'],
				"__DOMAIN__"=>$a['host'],
				'DOMAIN_NOT_WWW'=>str_replace('www.','',$a['host']),
		);
		foreach($d as $k=>$v){
			defined($k) or define($k,$v);
		}
		// Get SID
		$config = Yii::$app->session['config'];
		//$command = Yii::$app->db->createCommand("SELECT a.sid,b.code,a.is_admin FROM {{%domain_pointer}} as a inner join {{%shops}} as b on a.sid=b.id where a.domain='".__DOMAIN__."'");
		$r = (new \yii\db\Query())->select(['a.sid','b.status','b.code','a.is_admin','a.module','b.to_date'])
		->from(['a'=>'{{%domain_pointer}}'])
		->innerJoin(['b'=>'{{%shops}}'],'a.sid=b.id')
		->where(['a.domain'=>__DOMAIN__])->one();
		//$r = $command->queryOne();
		//var_dump($r);exit;
		$dma = false;
		if(!empty($r)){
			define ('SHOP_TIME_LEFT',countDownDayExpired($r['to_date']));
			define ('SHOP_TIME_LIFE',($r['to_date']));
			define ('SHOP_STATUS',($r['status']));
				
			define ('__SID__',(float)$r['sid']);
			define ('__SITE_NAME__',$r['code']);
			 
			$defaultModule = $r['module'] != "" ? $r['module'] : $this->defaultRoute;
			/*
			 *
			 * */
			//	view(__DEFAULT_MODULE__,true);exit;

			$pos = strpos($this->request->url, '/sajax');
			if($pos === false){
				$this->defaultRoute = $defaultModule;
			}
			if($r['is_admin'] == 1){
				define('__IS_ADMIN__',true);
				//define('__DOMAIN_ADMIN__',true);
				$dma = true; 
				//$pos = strpos($this->request->url, '/sajax');
				//if($pos === false)
				//$this->defaultRoute = 'admin';
				//var_dump($this->defaultRoute); exit;
			}else{
				//define('__DOMAIN_ADMIN__',false);
			}
		}else{
			//var_dump( __IS_ADMIN__); 
			//exit;
			define ('__SID__',0);
		}
		define('__DOMAIN_ADMIN__',$dma);
		define('__IS_SUSPENDED__',\common\models\Suspended::checkSuspended());
		define('ADMIN_ADDRESS',__DOMAIN_ADMIN__ ? Yii::$app->homeUrl : Yii::$app->homeUrl .  $this->_adminRoute[0]);
		// Set language
		$config = Yii::$app->session['config'] ;
		defined('ROOT_LANG') or define("ROOT_LANG",'vi_VN');
		defined('SYSTEM_LANG') or define("SYSTEM_LANG",'vi_VN');
		
		if(isset($config['language'])){
		
		}else{
			$default_lang = \app\modules\admin\models\AdLanguage::getUserDefaultLanguage();
			if(empty($default_lang)){
				$default_lang = ['code'=>'vi_VN','name'=>'Tiếng Việt','country_code'=>'vn'];
			}
			$language = ['language'=>$default_lang,'default_language'=>$default_lang];
			$config = $language;
		
		}
		defined('__LANG__') or define("__LANG__",$config['language']['code']);
		defined('DEFAULT_LANG') or define("DEFAULT_LANG",$config['default_language']['code']);
		defined('ADMIN_LANG') or define("ADMIN_LANG",SYSTEM_LANG);
	}
	protected function __get_templete_name(){
		$config = Yii::$app->session['config'];

		if(isset($config['templete'][__LANG__]['name']) && $config['templete'][__LANG__]['name'] != ""){
			 
			return $config['templete'][__LANG__];
		}else{ //exit;
			$sql = "select a.* from {{%templetes}} as a inner join temp_to_shop as b on a.id=b.temp_id and b.state=1 and b.sid=".__SID__ ." and b.lang='".__LANG__."'";
			$command = Yii::$app->db->createCommand($sql);
			$r = $command->queryOne();
			if(empty($r)){
				$sql = "select a.* from {{%templetes}} as a inner join temp_to_shop as b on a.id=b.temp_id and b.state=1 and b.sid=".__SID__ ;
				$command = Yii::$app->db->createCommand($sql);
				$r = $command->queryOne();
			}
			$config['templete'][__LANG__] = $r;

			Yii::$app->session['config'] = $config;
			return $r;
		}
	}
	public function updateSlugs($sid = __SID__){
		// menu
		$query = new yii\db\Query;
		$query->from(['{{%site_menu}}'])->select(['id','url','type','sid'])->where(['>','state',-2]);
		if($sid > 0){
			$query->andWhere(['sid'=>$sid]);
		}
		$l = $query->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				if((new yii\db\Query)->from('slugs')->where(['url'=>$v['url'],'sid'=>$v['sid']])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('slugs',['url'=>$v['url'],'route'=>$v['type'],'item_id'=>$v['id'],'sid'=>$v['sid'],'item_type'=>0])->execute();
				}
			}
		}
		// menu
		$query = new yii\db\Query;
		$query->from(['{{%articles}}'])->select(['id','url','type','sid'])->where(['>','state',-2]);
		if($sid > 0){
			$query->andWhere(['sid'=>$sid]);
		}
		$l = $query->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				if((new yii\db\Query)->from('slugs')->where(['url'=>$v['url'],'sid'=>$v['sid']])->count(1) == 0){
					Yii::$app->db->createCommand()->insert('slugs',['url'=>$v['url'],'route'=>$v['type'],'item_id'=>$v['id'],'sid'=>$v['sid'],'item_type'=>1])->execute();
				}
			}
		}
	}
}
 