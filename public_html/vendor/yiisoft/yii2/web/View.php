<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\web;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
/**
 * View represents a view object in the MVC pattern.
 *
 * View provides a set of methods (e.g. [[render()]]) for rendering purpose.
 *
 * View is configured as an application component in [[\yii\base\Application]] by default.
 * You can access that instance via `Yii::$app->view`.
 *
 * You can modify its configuration by adding an array to your application config under `components`
 * as it is shown in the following example:
 *
 * ```php
 * 'view' => [
 *     'theme' => 'app\themes\MyTheme',
 *     'renderers' => [
 *         // you may add Smarty or Twig renderer here
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @property \yii\web\AssetManager $assetManager The asset manager. Defaults to the "assetManager" application
 * component.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class View extends \yii\base\View
{
    /**
     * @event Event an event that is triggered by [[beginBody()]].
     */
    const EVENT_BEGIN_BODY = 'beginBody';
    /**
     * @event Event an event that is triggered by [[endBody()]].
     */
    const EVENT_END_BODY = 'endBody';
    /**
     * The location of registered JavaScript code block or files.
     * This means the location is in the head section.
     */
    const POS_HEAD = 1;
    /**
     * The location of registered JavaScript code block or files.
     * This means the location is at the beginning of the body section.
     */
    const POS_BEGIN = 2;
    /**
     * The location of registered JavaScript code block or files.
     * This means the location is at the end of the body section.
     */
    const POS_END = 3;
    /**
     * The location of registered JavaScript code block.
     * This means the JavaScript code block will be enclosed within `jQuery(document).ready()`.
     */
    const POS_READY = 4;
    /**
     * The location of registered JavaScript code block.
     * This means the JavaScript code block will be enclosed within `jQuery(window).load()`.
     */
    const POS_LOAD = 5;
    /**
     * This is internally used as the placeholder for receiving the content registered for the head section.
     */
    const PH_HEAD = '<![CDATA[YII-BLOCK-HEAD]]>';
    /**
     * This is internally used as the placeholder for receiving the content registered for the beginning of the body section.
     */
    const PH_BODY_BEGIN = '<![CDATA[YII-BLOCK-BODY-BEGIN]]>';
    /**
     * This is internally used as the placeholder for receiving the content registered for the end of the body section.
     */
    const PH_BODY_END = '<![CDATA[YII-BLOCK-BODY-END]]>';

    /**
     * @var AssetBundle[] list of the registered asset bundles. The keys are the bundle names, and the values
     * are the registered [[AssetBundle]] objects.
     * @see registerAssetBundle()
     */
    public $assetBundles = [];
    /**
     * @var string the page title
     */
    public $title;
    /**
     * @var array the registered meta tags.
     * @see registerMetaTag()
     */
    public $metaTags;
    /**
     * @var array the registered link tags.
     * @see registerLinkTag()
     */
    public $linkTags;
    /**
     * @var array the registered CSS code blocks.
     * @see registerCss()
     */
    public $css;
    /**
     * @var array the registered CSS files.
     * @see registerCssFile()
     */
    public $cssFiles;
    /**
     * @var array the registered JS code blocks
     * @see registerJs()
     */
    public $js;
    /**
     * @var array the registered JS files.
     * @see registerJsFile()
     */
    public $jsFiles;

    private $_assetManager;

	public function getAction($_getParam = 'view'){
		$x = getParam($_getParam);
		switch ($_getParam){
			case 'id':
				$x = $x > 0 ? $x : 0;
				break;
			case 'view':
				$x = strlen($x) > 0 ? $x : 'index';
				break;
		}
		return $x;
	}
	public function app(){
		return Yii::$app->zii;
	}
    /**
     * Marks the position of an HTML head section.
     */
    public function head()
    {
        echo self::PH_HEAD;
    }
	public function createUrl($rt, $absolute = false,$o=[]){
	 	return cu($rt,$absolute,$o);
	}
 	
 	public function registerCustomizeCss(){
 		
 		if(Yii::$app->user->can(ROOT_USER) && (in_array(getParam('dev') ,['dev','reload_css','load_user_css']))){ 			
 			(new \MatthiasMullie\Minify\CSS(__RSPATH__ . '/css/site.css'))->minify(__RSPATH__ . '/css/site.min.css');
 			if(in_array(getParam('dev') ,['dev','reload_css'])){
		 		$sources = [
		 				__LIBS_PATH__ . '/themes/css/base.css',
		 				__LIBS_PATH__ . '/themes/css/animate.css',
		 				__LIBS_PATH__ . '/font-awesome/css/font-awesome.min.css',
		 				__LIBS_PATH__ . '/popup/colorbox/colorbox.css',
		 				__LIBS_PATH__ . '/menu/superfish-1.7.4/src/css/superfish.css',
		 				__LIBS_PATH__ . '/slider/slick/slick.css',
		 				__LIBS_PATH__ . '/slider/slick/slick-theme.css',
		 				__LIBS_PATH__ . '/themes/js/jquery-ui.css', 
		 		];		 		 
		 		$minifier = new \MatthiasMullie\Minify\CSS();
		 		$minifier->setMaxImportSize(10000000);
		 		foreach ($sources as $s){
		 			$minifier->add($s);	 			 
		 		}
		 		$minifiedPath = __LIBS_PATH__ . '/c/css/base.min.css';
		 		$minifier->minify($minifiedPath);	 		 
 			}
 		}
 		$this->registerCssFile(__LIBS_DIR__ . '/c/css/base.min.css'); 		
 	}
 	
 	public function registerCustomizeJs(){ 		 		 		
 		if(Yii::$app->user->can(ROOT_USER) && (in_array(getParam('dev') ,['dev','reload_js','load_user_js']))){
 			$this->params['jsfile'][] =  __RSPATH__ . '/js/main.js'; 	 
 			$m = new \MatthiasMullie\Minify\JS();
 			foreach ($this->params['jsfile'] as $j){
 				$m->add($j);
 			}
 			$m->minify(__RSPATH__ . '/js/main.min.js'); 	
 			if(in_array(getParam('dev') ,['dev','reload_js'])){
 			$sources = [
 					__LIBS_PATH__ . '/themes/js/base.js',
 					__LIBS_PATH__ . '/themes/js/fapi.js',
 					__LIBS_PATH__ . '/themes/js/gapi.js',
 					__LIBS_PATH__ . '/themes/js/jquery.number.min.js',
 					__LIBS_PATH__ . '/jquerycookie/jquery.cookie.js',
 					__LIBS_PATH__ . '/menu/superfish-1.7.4/src/js/hoverIntent.js',
 					__LIBS_PATH__ . '/menu/superfish-1.7.4/src/js/superfish.js',
 					__LIBS_PATH__ . '/themes/js/jquery-ui.min.js',
 					__LIBS_PATH__ . '/bootstrap/assets/js/bootstrap-tooltip.js',
 					__LIBS_PATH__ . '/bootstrap/assets/js/moment.min.js',
 					__LIBS_PATH__ . '/bootstrap/assets/js/bootstrap-datetimepicker.min.js',
 					__LIBS_PATH__ . '/bootstrap/colorpicker/dist/js/bootstrap-colorpicker.js',
 					__LIBS_PATH__ . '/bootstrap/3.2.0/js/ie10-viewport-bug-workaround.js',
 					__LIBS_PATH__ . '/lazyload.js',
 					__LIBS_PATH__ . '/modernizr.js',
 					__LIBS_PATH__ . '/slider/slick-master/slick/slick.min.js',
 					__LIBS_PATH__ . '/popup/colorbox/jquery.colorbox.js',
 					__LIBS_PATH__ . '/lazyloadxt/lazy.js', 					 					 				 					 					 					 					 					 					 					 					 					
 			];
 			$minifier = new \MatthiasMullie\Minify\JS();
 			foreach ($sources as $s){
 				$minifier->add($s);
 			} 			
 			$minifiedPath = __LIBS_PATH__ . '/c/js/base.min.js';
 			$minifier->minify($minifiedPath); 	
 			}
 		} 		 
 		$this->registerJsFile(__RSDIR__ . '/js/main.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
 	}
    /**
     * Marks the beginning of an HTML body section.
     */
    public function beginBody()
    {
    	if(!__IS_ADMIN__ && !__IS_DETAIL__ && isset(Yii::$site['seo']['heading1'])){
    		switch (Yii::$site['seo']['heading1']){
    			case 1:
    				echo '<h1 class="site-heading1-entry">'.uh(Yii::$site['seo']['title']).'</h1>';
    				break;
    			case 2:
    				echo '<h1 class="site-heading1-entry">'.uh(Yii::$site['seo']['real_title']).'</h1>';
    				break;
    		}
    		
    	}
        echo self::PH_BODY_BEGIN;
        $this->trigger(self::EVENT_BEGIN_BODY);
    }

    /**
     * Marks the ending of an HTML body section.
     */
    public function getConfigs($code = false, $lang = __LANG__){
    	$langx = $lang == false ? 'all' : $lang;
    	$code = $code !== false ? $code : 'SITE_CONFIGS';
    	if(!isset($_SESSION['config']['adLogin']) && isset($_SESSION['config']['preload'][$code][$langx])
    			&& !empty($_SESSION['config']['preload'][$code][$langx])){
    				return $_SESSION['config']['preload'][$code][$langx];
    	}
    	$sql = "select a.bizrule from site_configs as a where a.code='$code'";
    	$sql .= " and a.sid=".__SID__ ;
    	$sql .= $lang !== false ? " and a.lang='$lang'" : '';
    	$l = djson(Yii::$app->db->createCommand($sql)->queryScalar(),true);    	
    	$_SESSION['config']['preload'][$code][$langx] = $l;
    	return $l;
    }
    public function get_text_auto_load(){
    	$texts = array();
    	if((isset($_SESSION['config']['text_auto_load']))){
    		$texts = $_SESSION['config']['text_auto_load'];
    	}else{
    		$sql = "SELECT a.id,a.value FROM {{%text_translate}} as a where a.auto_load=1 and a.lang='".__LANG__."' ORDER BY a.id";
    		$l = Yii::$app->db->createCommand($sql)->queryAll();
    		if(!empty($l)){
    			foreach ($l as $k=>$v){
    				$texts[$v['id']] = $v['value'];
    			}
    		}
    	}
    	if(!empty($texts)){
    		foreach ($texts as $k=>$v){
    			$_SESSION['text'][$k][__LANG__] = $v;
    		}
    	}
    	return $texts;
    }
    public function endBody()
    {
    	 
    	$identity_field = isset($this->params['identity_field']) ? $this->params['identity_field'] : 'id';
    	$suffix = isset(Yii::$site['seo']['url_config']['suffix']) ? Yii::$site['seo']['url_config']['suffix'] : '';
    	$cfg = array(
    			'is_admin'=>__IS_ADMIN__,
    			'domain_admin'=>__DOMAIN_ADMIN__,
    			'baseUrl'=>removeLastSlashes(Yii::$app->homeUrl),
    			'absoluteUrl'=>removeLastSlashes(Url::home(true)),
    			'adminUrl'=> Url::to([DS.Yii::$app->controller->module->id]),
    			'cBaseUrl'=>removeLastSlashes(__DEFAULT_MODULE__ != 'site' && !__DOMAIN_ADMIN__ ? 
    					($suffix != '' ? str_replace($suffix, '',  Url::to([DS.Yii::$app->controller->module->id.DS],true)) : Url::to([DS.Yii::$app->controller->module->id.DS],true) ) : 
    					Url::home(true)) ,
    			'controller_text'=>defined('__RCONTROLLER__') ? __RCONTROLLER__ : '',
    			'controller'=>Yii::$app->controller->id,
    			'controllerUrl'=>URL_WITH_PATH,
    			'action'=>Yii::$app->controller->action->id,
    			'assets'=>Yii::getAlias('@admin'),
    			'libsDir'=>Yii::getAlias('@libs'), 
    			'wheight'=>'%f%screen.height%f%',
    			'wwidth'=>'%f%screen.width%f%',
    			'get'=>(Yii::$app->request->get()),
    			'request'=>afGetUrl(),
    			'returnUrl'=>afGetUrl([],[$identity_field,'view']),
    			'sid'=>__SID__,
    			'time'=>date("d/m/Y H:i"),
    			'lang'=>__LANG__,
    			'locale'=>__LANG__ == 'vi_VN' ? 'vi' : 'en',
    			'browser'=>getBrowser(),
    			'text'=>$this->get_text_auto_load(),
    			'currency'=>Yii::$app->zii->getUserCurrency(),
    			'facebook_app'=>(isset(Yii::$site['other_setting']['facebook_app']) ? Yii::$site['other_setting']['facebook_app'] : [
    				'appId'=>554071461442224,
    				'version'=>'v2.7'
    			])
    	);
    	echo '<script type="text/javascript">$cfg=' .jsonify($cfg).'</script>';
    	//
    	if(!__IS_ADMIN__ && isset(Yii::$site['livechat']['listItem']) && !empty(Yii::$site['livechat']['listItem'])){
    		echo '<div id="slivechat" class="slivechat">';
    		foreach (Yii::$site['livechat']['listItem'] as $lChat){
    			if(isset($lChat['is_active']) && $lChat['is_active'] == 'on'){
    				echo uh($lChat['embed_code'],2);
    			}
    		}
    		echo '</div>';
    	}
    	//
        $this->trigger(self::EVENT_END_BODY);
        echo self::PH_BODY_END;

        foreach (array_keys($this->assetBundles) as $bundle) {
            $this->registerAssetFiles($bundle);
        }
        if(!__IS_ADMIN__ && isset(Yii::$site['seo']['googleanalystics'])){
        	echo uh(Yii::$site['seo']['googleanalystics'],2);
        }
    }

    /**
     * Marks the ending of an HTML page.
     * @param boolean $ajaxMode whether the view is rendering in AJAX mode.
     * If true, the JS scripts registered at [[POS_READY]] and [[POS_LOAD]] positions
     * will be rendered at the end of the view like normal scripts.
     */
    public function endPage($ajaxMode = false)
    {
        $this->trigger(self::EVENT_END_PAGE);

        $content = ob_get_clean();

        echo strtr($content, [
            self::PH_HEAD => $this->renderHeadHtml(),
            self::PH_BODY_BEGIN => $this->renderBodyBeginHtml(),
            self::PH_BODY_END => $this->renderBodyEndHtml($ajaxMode),
        ]);

        $this->clear();
    }

    /**
     * Renders a view in response to an AJAX request.
     *
     * This method is similar to [[render()]] except that it will surround the view being rendered
     * with the calls of [[beginPage()]], [[head()]], [[beginBody()]], [[endBody()]] and [[endPage()]].
     * By doing so, the method is able to inject into the rendering result with JS/CSS scripts and files
     * that are registered with the view.
     *
     * @param string $view the view name. Please refer to [[render()]] on how to specify this parameter.
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @param object $context the context that the view should use for rendering the view. If null,
     * existing [[context]] will be used.
     * @return string the rendering result
     * @see render()
     */
    public function renderAjax($view, $params = [], $context = null)
    {
        $viewFile = $this->findViewFile($view, $context);

        ob_start();
        ob_implicit_flush(false);

        $this->beginPage();
        $this->head();
        $this->beginBody();
        echo $this->renderFile($viewFile, $params, $context);
        $this->endBody();
        $this->endPage(true);

        return ob_get_clean();
    }

    /**
     * Registers the asset manager being used by this view object.
     * @return \yii\web\AssetManager the asset manager. Defaults to the "assetManager" application component.
     */
    public function getAssetManager()
    {
        return $this->_assetManager ?: Yii::$app->getAssetManager();
    }

    /**
     * Sets the asset manager.
     * @param \yii\web\AssetManager $value the asset manager
     */
    public function setAssetManager($value)
    {
        $this->_assetManager = $value;
    }

    /**
     * Clears up the registered meta tags, link tags, css/js scripts and files.
     */
    public function clear()
    {
        $this->metaTags = null;
        $this->linkTags = null;
        $this->css = null;
        $this->cssFiles = null;
        $this->js = null;
        $this->jsFiles = null;
        $this->assetBundles = [];
    }

    /**
     * Registers all files provided by an asset bundle including depending bundles files.
     * Removes a bundle from [[assetBundles]] once files are registered.
     * @param string $name name of the bundle to register
     */
    protected function registerAssetFiles($name)
    {
        if (!isset($this->assetBundles[$name])) {
            return;
        }
        $bundle = $this->assetBundles[$name];
        if ($bundle) {
            foreach ($bundle->depends as $dep) {
                $this->registerAssetFiles($dep);
            }
            $bundle->registerAssetFiles($this);
        }
        unset($this->assetBundles[$name]);
    }

    /**
     * Registers the named asset bundle.
     * All dependent asset bundles will be registered.
     * @param string $name the class name of the asset bundle (without the leading backslash)
     * @param integer|null $position if set, this forces a minimum position for javascript files.
     * This will adjust depending assets javascript file position or fail if requirement can not be met.
     * If this is null, asset bundles position settings will not be changed.
     * See [[registerJsFile]] for more details on javascript position.
     * @return AssetBundle the registered asset bundle instance
     * @throws InvalidConfigException if the asset bundle does not exist or a circular dependency is detected
     */
    public function registerAssetBundle($name, $position = null)
    {
        if (!isset($this->assetBundles[$name])) {
            $am = $this->getAssetManager();
            $bundle = $am->getBundle($name);
            $this->assetBundles[$name] = false;
            // register dependencies
            $pos = isset($bundle->jsOptions['position']) ? $bundle->jsOptions['position'] : null;
            foreach ($bundle->depends as $dep) {
                $this->registerAssetBundle($dep, $pos);
            }
            $this->assetBundles[$name] = $bundle;
        } elseif ($this->assetBundles[$name] === false) {
            throw new InvalidConfigException("A circular dependency is detected for bundle '$name'.");
        } else {
            $bundle = $this->assetBundles[$name];
        }

        if ($position !== null) {
            $pos = isset($bundle->jsOptions['position']) ? $bundle->jsOptions['position'] : null;
            if ($pos === null) {
                $bundle->jsOptions['position'] = $pos = $position;
            } elseif ($pos > $position) {
                throw new InvalidConfigException("An asset bundle that depends on '$name' has a higher javascript file position configured than '$name'.");
            }
            // update position for all dependencies
            foreach ($bundle->depends as $dep) {
                $this->registerAssetBundle($dep, $pos);
            }
        }

        return $bundle;
    }

    /**
     * Registers a meta tag.
     *
     * For example, a description meta tag can be added like the following:
     *
     * ```php
     * $view->registerMetaTag([
     *     'name' => 'description',
     *     'content' => 'This website is about funny raccoons.'
     * ]);
     * ```
     *
     * will result in the meta tag `<meta name="description" content="This website is about funny raccoons.">`.
     *
     * @param array $options the HTML attributes for the meta tag.
     * @param string $key the key that identifies the meta tag. If two meta tags are registered
     * with the same key, the latter will overwrite the former. If this is null, the new meta tag
     * will be appended to the existing ones.
     */
    public function registerMetaTag($options, $key = null)
    {
        if ($key === null) {
            $this->metaTags[] = Html::tag('meta', '', $options);
        } else {
            $this->metaTags[$key] = Html::tag('meta', '', $options);
        }
    }

    /**
     * Registers a link tag.
     *
     * For example, a link tag for a custom [favicon](http://www.w3.org/2005/10/howto-favicon)
     * can be added like the following:
     *
     * ```php
     * $view->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/myicon.png']);
     * ```
     *
     * which will result in the following HTML: `<link rel="icon" type="image/png" href="/myicon.png">`.
     *
     * **Note:** To register link tags for CSS stylesheets, use [[registerCssFile()]] instead, which
     * has more options for this kind of link tag.
     *
     * @param array $options the HTML attributes for the link tag.
     * @param string $key the key that identifies the link tag. If two link tags are registered
     * with the same key, the latter will overwrite the former. If this is null, the new link tag
     * will be appended to the existing ones.
     */
    public function registerLinkTag($options, $key = null)
    {
        if ($key === null) {
            $this->linkTags[] = Html::tag('link', '', $options);
        } else {
            $this->linkTags[$key] = Html::tag('link', '', $options);
        }
    }

    /**
     * Registers a CSS code block.
     * @param string $css the content of the CSS code block to be registered
     * @param array $options the HTML attributes for the `<style>`-tag.
     * @param string $key the key that identifies the CSS code block. If null, it will use
     * $css as the key. If two CSS code blocks are registered with the same key, the latter
     * will overwrite the former.
     */
    public function registerCss($css, $options = [], $key = null)
    {
        $key = $key ?: md5($css);
        $this->css[$key] = Html::style($css, $options);
    }

    /**
     * Registers a CSS file.
     * @param string $url the CSS file to be registered.
     * @param array $options the HTML attributes for the link tag. Please refer to [[Html::cssFile()]] for
     * the supported options. The following options are specially handled and are not treated as HTML attributes:
     *
     * - `depends`: array, specifies the names of the asset bundles that this CSS file depends on.
     *
     * @param string $key the key that identifies the CSS script file. If null, it will use
     * $url as the key. If two CSS files are registered with the same key, the latter
     * will overwrite the former.
     */
    public function registerCssFile($url, $options = [], $key = null)
    {
        $url = Yii::getAlias($url);
        $key = $key ?: $url;

        $depends = ArrayHelper::remove($options, 'depends', []);

        if (empty($depends)) {
            $this->cssFiles[$key] = Html::cssFile($url, $options);
        } else {
            $this->getAssetManager()->bundles[$key] = Yii::createObject([
                'class' => AssetBundle::className(),
                'baseUrl' => '',
                'css' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'cssOptions' => $options,
                'depends' => (array)$depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }

    /**
     * Registers a JS code block.
     * @param string $js the JS code block to be registered
     * @param integer $position the position at which the JS script tag should be inserted
     * in a page. The possible values are:
     *
     * - [[POS_HEAD]]: in the head section
     * - [[POS_BEGIN]]: at the beginning of the body section
     * - [[POS_END]]: at the end of the body section
     * - [[POS_LOAD]]: enclosed within jQuery(window).load().
     *   Note that by using this position, the method will automatically register the jQuery js file.
     * - [[POS_READY]]: enclosed within jQuery(document).ready(). This is the default value.
     *   Note that by using this position, the method will automatically register the jQuery js file.
     *
     * @param string $key the key that identifies the JS code block. If null, it will use
     * $js as the key. If two JS code blocks are registered with the same key, the latter
     * will overwrite the former.
     */
    public function registerJs($js, $position = self::POS_READY, $key = null)
    {
        $key = $key ?: md5($js);
        $this->js[$position][$key] = $js;
        if ($position === self::POS_READY || $position === self::POS_LOAD) {
            JqueryAsset::register($this);
        }
    }

    /**
     * Registers a JS file.
     * @param string $url the JS file to be registered.
     * @param array $options the HTML attributes for the script tag. The following options are specially handled
     * and are not treated as HTML attributes:
     *
     * - `depends`: array, specifies the names of the asset bundles that this JS file depends on.
     * - `position`: specifies where the JS script tag should be inserted in a page. The possible values are:
     *     * [[POS_HEAD]]: in the head section
     *     * [[POS_BEGIN]]: at the beginning of the body section
     *     * [[POS_END]]: at the end of the body section. This is the default value.
     *
     * Please refer to [[Html::jsFile()]] for other supported options.
     *
     * @param string $key the key that identifies the JS script file. If null, it will use
     * $url as the key. If two JS files are registered with the same key at the same position, the latter
     * will overwrite the former. Note that position option takes precedence, thus files registered with the same key,
     * but different position option will not override each other.
     */
    public function registerJsFile($url, $options = [], $key = null)
    {
        $url = Yii::getAlias($url);
        $key = $key ?: $url;

        $depends = ArrayHelper::remove($options, 'depends', []);

        if (empty($depends)) {
            $position = ArrayHelper::remove($options, 'position', self::POS_END);
            $this->jsFiles[$position][$key] = Html::jsFile($url, $options);
        } else {
            $this->getAssetManager()->bundles[$key] = Yii::createObject([
                'class' => AssetBundle::className(),
                'baseUrl' => '',
                'js' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'jsOptions' => $options,
                'depends' => (array)$depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }

    /**
     * Renders the content to be inserted in the head section.
     * The content is rendered using the registered meta tags, link tags, CSS/JS code blocks and files.
     * @return string the rendered content
     */
    protected function renderHeadHtml()
    {
        $lines = [];
        if (!empty($this->metaTags)) {
            $lines[] = implode("\n", $this->metaTags);
        }

        if (!empty($this->linkTags)) {
            $lines[] = implode("\n", $this->linkTags);
        }
        if (!empty($this->cssFiles)) {
            $lines[] = implode("\n", $this->cssFiles);
        }
        if (!empty($this->css)) {
            $lines[] = implode("\n", $this->css);
        }
        if (!empty($this->jsFiles[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_HEAD]);
        }
        if (!empty($this->js[self::POS_HEAD])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_HEAD]), ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    /**
     * Renders the content to be inserted at the beginning of the body section.
     * The content is rendered using the registered JS code blocks and files.
     * @return string the rendered content
     */
    protected function renderBodyBeginHtml()
    {
        $lines = [];
        if (!empty($this->jsFiles[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_BEGIN]);
        }
        if (!empty($this->js[self::POS_BEGIN])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_BEGIN]), ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    /**
     * Renders the content to be inserted at the end of the body section.
     * The content is rendered using the registered JS code blocks and files.
     * @param boolean $ajaxMode whether the view is rendering in AJAX mode.
     * If true, the JS scripts registered at [[POS_READY]] and [[POS_LOAD]] positions
     * will be rendered at the end of the view like normal scripts.
     * @return string the rendered content
     */
    protected function renderBodyEndHtml($ajaxMode)
    {
        $lines = [];

        if (!empty($this->jsFiles[self::POS_END])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_READY])) {
                $js = "jQuery(document).ready(function () {\n" . implode("\n", $this->js[self::POS_READY]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $js = "jQuery(window).on('load', function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }
}
