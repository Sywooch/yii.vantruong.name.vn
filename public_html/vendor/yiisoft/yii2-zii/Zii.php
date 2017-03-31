<?php
namespace yii\zii;
global $table_lft;
$table_lft= 0;
use Yii;
use yii\db\Query;
use app\modules\admin\models\Siteconfigs;
use app\models\Articles;
use app\models\Slugs;
use app\models\SiteMenu;
use app\models\Filters;
class Zii extends yii\base\Object
{
	public function __construct(){
		
	}
	public function countTable($table, $con = []){
		return (new Query())->from($table)->where($con)->count(1);
	}
	public static function getUserCurrency(){
		if(0>1 && isset($_SESSION['config']['currency'])){
			return $_SESSION['config']['currency'];
		}else{
			$v = Siteconfigs::getItem('SITE_CONFIGS',__LANG__);
			//view($v,true);
			if(isset($v['other_setting']['currency'])){
				$_SESSION['config']['currency'] = $v['other_setting']['currency'];
				return $_SESSION['config']['currency'];
			}		
		}
	}
	
	public function getUserLanguages(){
		return \app\modules\admin\models\AdLanguage::getList(['is_active'=>1]);
	}
	public function calcDistancePrice($o=[]){
		//
		$price = $t = 0;
		//
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0; 
		$vehicle_id = isset($o['vehicle_id']) ? $o['vehicle_id'] : 0;
		$distance_id = isset($o['distance_id']) ? $o['distance_id'] : 0;
		$exchange_rate = isset($o['exchange_rate']) ? $o['exchange_rate'] : 0;
		$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
		//
		$distance_item = \app\modules\admin\models\Distances::getItem($distance_id);
		//
		$item = [];
		if($item_id>0){
			//
			if((new Query())->from('tours_programs_suppliers_prices')->where([
					'supplier_id'=>$supplier_id,
					'vehicle_id'=>$vehicle_id,
					'item_id'=>$item_id,
					'service_id'=>$distance_id,
			])->count(1) == 0){
				$t = 1; $item = [];
			}else{
				$item = (new Query())->from(['a'=>'tours_programs_suppliers_prices'])
				->innerJoin(['b'=>'vehicles_categorys'],'b.id=a.vehicle_id')
				->where([
					'supplier_id'=>$supplier_id,
					'vehicle_id'=>$vehicle_id,
					'item_id'=>$item_id,
					'service_id'=>$distance_id,
				])->one();
			}
		}
			//
		$price_type = !empty($item) ? $item['price_type'] : 0; // Giá chặng
		$item = !empty($item) ? $item : (new Query())->from('distances_to_prices')->where([
				'distance_id'=>$distance_id,
				'vehicle_id'=>$vehicle_id,
				'parent_id'=>$supplier_id,
		])->one();
			//
			
			//
		if(!empty($item)){
			if ($item['currency'] > 1){
				$item['price1'] = $item['price1'] * ($exchange_rate > 0 ? $exchange_rate : $this->getExchangeRate($item['currency']));
			}
			$price = $item['price1'];
			
		}		
		
		//
		if($price == 0){
			$price_type = 1; // Giá km
			$item = (new Query())->from(['a'=>'vehicles_to_prices'])->where([
					//	'distance_id'=>$distance_id,
					'a.item_id'=>$vehicle_id,
					'a.parent_id'=>$supplier_id,
			])
			->innerJoin(['b'=>'vehicles_categorys'],'b.id=a.item_id')
			->andWhere(['>','a.pmax',$distance_item['distance']-1])
			->andWhere(['<','a.pmin',$distance_item['distance']+1])
			->select(['a.*','b.*','id'=>'b.id'])
			->one();
			//view($item); 
			if(!empty($item)){
				if ($item['currency'] > 1){
					$item['price1'] = $item['price1'] * ($exchange_rate > 0 ? $exchange_rate : $this->getExchangeRate($item['currency']));
				}
				$price = $item['price1'];
			}
		}
		//
		if(!empty($item) && isset($item['distance'])){
			$distance_item['distance'] = $item['distance'] ;
		}else{
			$distance_item['distance'] = $price_type == 0 ? 1 : $distance_item['distance'] ;
		}
		
		//
		if($t == 1){
			Yii::$app->db->createCommand()->insert('tours_programs_suppliers_prices',[
					'supplier_id'=>$supplier_id,
					'vehicle_id'=>$vehicle_id,
					'item_id'=>$item_id,
					'service_id'=>$distance_id,
					'price1'=>$price,
					'price_type'=>$price_type,
					'distance'=>$distance_item['distance']
			])->execute();
		}
		//
		return [
				'vehicle'=>$item,
				'distance'=>$distance_item,
				'supplier'=>\app\modules\admin\models\Customers::getItem($supplier_id),
				'price'=>$price,
				'total_price'=>($price * $distance_item['distance']),
				'price_type'=>$price_type
		];
	}
	
	
	public function generateSitemap($o = []){
		$lastmod = isset($o['lastmod']) ? $o['lastmod'] : '';
		$freq = isset($o['freq']) ? $o['freq'] : '';
		$priority = isset($o['priority']) ? $o['priority'] : '';
		$updateDatabase = isset($o['updateDatabase']) && $o['updateDatabase'] === true ? true : false;
		//
		$html = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
		$query = new Query();
		$query->from(['a'=>'slugs'])
		//->innerJoin(['b'=>'articles'],'a.item_id=b.id')
		->where(['a.sid'=>__SID__])
		->andWhere(['>','a.state',-2])
		->andWhere(['not in','a.rel',['nofollow']])
		;
		foreach ($query->all() as $k=>$v){
			$html .= '
<url>
  <loc>'.cu([DS.$v['url']],true).'</loc>'.($lastmod != "" ? '
  <lastmod>'.$lastmod.'</lastmod>' : '').''.($freq != "" ? '
  <changefreq>'.$freq.'</changefreq>' : '').'
</url>';
		}
		$html .= '
</urlset>';
		//
		if($updateDatabase){
			Siteconfigs::updateSiteConfigs('seo/sitemap', $html);
		}
		return $html;
	}
	
	public function getTourProgramSuppliers($id){
		$query = new Query();
		$query->select(['a.*',
		'place_id'=>(new Query())->select('place_id')->from('customers_to_places')->where('customer_id=a.id')->limit(1)		
		])->from(['a'=>'customers'])
		//->leftJoin(['b'=>'tours_programs_to_suppliers'],'a.id=b.supplier_id')
		->where(['in','a.id',(new Query())->select('supplier_id')->from('tours_programs_to_suppliers')->where(['item_id'=>$id])])
		//->andWhere(['b.item_id'=>$id])
		;
		return $query->orderBy(['a.name'=>SORT_ASC])->all();
	}
	
	public function chooseVehicleAuto($o = []){
		/* 
		 * Số lượng khách
		 * Quốc tịch khách
		 * 
		 */
		$total_pax = isset($o['total_pax']) ? $o['total_pax'] : 0;
		$totalPax = isset($o['totalPax']) ? $o['totalPax'] : $total_pax;
		$nationality = isset($o['nationality']) ? $o['nationality'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$place_id = isset($o['place_id']) ? $o['place_id'] : 0;
		$auto = isset($o['auto']) ? $o['auto'] : 0;
		$update = isset($o['update']) ? $o['update'] : 0;
		$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
		// Check quốc tịch - 
		$pax_type = \app\modules\admin\models\Local::getTypeByNationality($nationality);
		$selected_car = [];
		if(isConfirm($auto)){		
		
		// Lấy danh sách xe
		$query = new Query();
		$query->from(['a'=>'vehicles_categorys'])->where(['>','a.state',-2])->andWhere(['a.type'=>$pax_type])
		->andWhere(['<=','a.pmin',$totalPax])
		->andWhere(['in','a.id',(new Query())->from(['vehicles_to_cars'])->where(['parent_id'=>$supplier_id,'is_active'=>1,'is_default'=>1])->select('vehicle_id')])		
		->orderBy(['a.pmax'=>SORT_DESC]);
		$listCar = $query->all();
		$totalCar = 0; 
		if(!empty($listCar)){
			foreach ($listCar as $k=>$v){
												
				$t = (int)($totalPax/$v['pmax']);
				if($t > $totalCar && $totalCar>0){
					break;
				}
				$totalCar = $t;
				$selected_car[0] = $v;
				$selected_car[0]['quantity'] = $t;
			}
		}
		//
		$du_khach = $totalPax - ($selected_car[0]['quantity'] * $selected_car[0]['pmax']);
		if($du_khach < $selected_car[0]['pmax']){
			if($du_khach > ($selected_car[0]['quantity'] * $selected_car[0]['factor'])){
				$selected_car[0]['quantity'] ++;
			}
		}else{
			$selected_car[0]['quantity'] ++;
		}
		// Cập nhật cơ sở dữ liệu
		if(isConfirm($update)){
			Yii::$app->db->createCommand()->delete('tours_programs_to_suppliers',['supplier_id'=>$supplier_id,'item_id'=>$item_id])->execute();
			Yii::$app->db->createCommand()->insert('tours_programs_to_suppliers',
					[
							'supplier_id'=>$supplier_id,
							'item_id'=>$item_id,
							'vehicle_id'=>$selected_car[0]['id'],
							'quantity'=>$selected_car[0]['quantity'],
							'type_id'=>TYPE_ID_VECL
					]
					)->execute();
		}
		
		 
		}else{
			$query = new Query();
			$query->from(['a'=>'vehicles_categorys'])
			->innerJoin(['b'=>'tours_programs_to_suppliers'],'a.id=b.vehicle_id')
			->where(['>','a.state',-2])			
			->andWhere(['a.type'=>$pax_type,'b.item_id'=>$item_id,'supplier_id'=>$supplier_id])
			->select(['a.*','b.quantity','maker_title'=>(new Query())->select('title')->from('vehicles_makers')->where('id=a.maker_id')])
			;
			$selected_car = $query->all();
		}
		
		return $selected_car;
	}
	
	public static function getCurrencyByCode($id = 'VND'){
		$list = self::getUserCurrency();
			
		if(isset($list['list']) && !empty($list['list'])){
			foreach ($list['list'] as $k=>$v){
				if($v['code'] == $id){
					return $v;
					break;
				}
			}
		}
		//return \app\modules\admin\models\Currency::getItem($id);
	}
	
	public static function getCurrency($id = 1){
		$list = self::getUserCurrency();
			
		if(isset($list['list']) && !empty($list['list'])){
			foreach ($list['list'] as $k=>$v){
				if($v['id'] == $id){
					return $v;
					break;
				}
			}
		}
		return \app\modules\admin\models\Currency::getItem($id);
	}
	
	
	
	public static function getDefaultCurrency(){
		$list = self::getUserCurrency();
		if(isset($list['list']) && !empty($list['list'])){
			foreach ($list['list'] as $k=>$v){
				if(isset($list['default']) && $list['default'] == $v['id']){
					return $v;
					break;
				}
			}
		}
	}
	
	public function showDropdownCurrency($o = []){
		
	}
	
	public static function showLang($lang = DEFAULT_LANG){
		$query = (new Query())->from('languages')->select('title');
		if(!is_numeric($lang)){
			$query->where(['code'=>$lang]);
		}else{
			$query->where(['id'=>$lang]);
		}
		//var_dump($query->createCommand()->getSql());
		return $query->scalar();
	}
	
	public static function showPrice($price = 0,$currency = -1, $showSymbol = true){
		$text_translate = 2;
		if(is_array($price)){
			
			$text_translate = isset($price['text_contact']) ? $price['text_contact'] : $text_translate;
			$price = isset($price['price']) ? $price['price'] : 0;
		}
		$currency = $currency == -1 ? self::getDefaultCurrency() : self::getCurrency($currency);
		if(!is_numeric($price)) $price = cprice($price);
		if(!($price>0)){
			//f[products][prices][zero][vi_VN]
			$controller_code = is_array($price) && isset($price['controller_code']) ? $price['controller_code'] : 
			(defined('CONTROLLER_CODE') ? CONTROLLER_CODE : false);
			//view(Yii::$site[$controller_code]);
			if(isset(Yii::$site[$controller_code]['prices']['zero'][__LANG__]) && Yii::$site[$controller_code]['prices']['zero'][__LANG__] != ""){
				return uh(Yii::$site[$controller_code]['prices']['zero'][__LANG__]);
			}
			return getTextTranslate($text_translate); 
		}
		switch ($currency['display_type']){
			case 2: $symbol = $currency['symbol'];break;
			default: $symbol = $currency['code']; break;
		}
		if($currency['display'] == -1){
			$pre = $symbol;		
			$after = '';
		}else{
			$pre = '';
			$after = $symbol;
		}
		if(!$showSymbol) {
			$pre = $after = '';
		}
		return $pre . number_format($price,$currency['decimal_number']) . $after;
	}
	
	public static function showPrices($price = 0,$currency = -1, $showSymbol = true){
		$currency = $currency == -1 ? self::getDefaultCurrency() : self::getCurrency($currency);
		if(!is_numeric($price)) $price = cprice($price);
		if(!($price>0)){
			return getTextTranslate(2);
		}
		switch ($currency['display_type']){
			case 2: $symbol = $currency['symbol'];break;
			default: $symbol = $currency['code']; break;
		}
		if($currency['display'] == -1){
			$pre = $symbol;
			$after = '';
		}else{
			$pre = '';
			$after = $symbol;
		}
		if(!$showSymbol) {
			$pre = $after = '';
		}
		return $pre . number_format($price,$currency['decimal_number']) . $after;
	}
	
	public function showCurrency($id=1, $display_type = false){
		$list = self::getUserCurrency();	
		//view($list);
		if(isset($list['list']) && !empty($list['list'])){
			foreach ($list['list'] as $k=>$v){
				if($v['id'] == $id){					
					break;
				}
			}
			switch ($display_type){
				case 3:
					return $v['decimal_number'];
					break;
				case 2: return $v['symbol'];break;
				case 1: return $v['code'];break; 
				 
			}
			if(isset($list['display_type'])){
			switch ($list['display_type']){
				case 3: 
					return $v['decimal_number']; 
					break;
				case 2: return $v['symbol'];break;
				default: return $v['code']; break;
			}
			}
			return $v['code'];
		}		 
	}
	public function getOrderBy(){
		return [
			 
				1=>'Tên / tiêu đề (a-z)',
				2=>'Tên / tiêu đề (z-a)',
				3=>'Thời gian (tăng)',
				4=>'Thời gian (giảm)',
				5=>'Giá (tăng)',
				6=>'Giá (giảm)',
				100=>'Ngẫu nhiên',
		];
	}
	public function get_incurred_charge_type(){
		return array(
				array('id'=>0,'title'=>'Tính giá trực tiếp'),
				array('id'=>1,'title'=>'Tính giá phát sinh (%)'),
				array('id'=>2,'title'=>'Phụ thu tiền mặt'),
				//array('id'=>8,'title'=>'Tàu thuyền'),
		);
	}
	public function get_unit_prices(){
		return array(
				array('id'=>1,'title'=>'Phòng [Xe vận chuyển]'),
				array('id'=>2,'title'=>'Khách'),
				array('id'=>3,'title'=>'Đoàn'),
				//array('id'=>4,'title'=>'Tàu thuyền'),
		);
	}
	public function get_customer_type_code(){
		return array(
				array('id'=>0,'title'=>'--',),
				array('id'=>20,'title'=>'Doanh nghiệp tư nhân',),
				array('id'=>21,'title'=>'Doanh nghiệp nhà nước',),
				array('id'=>22,'title'=>'Công ty cổ phần'),
				array('id'=>23,'title'=>'Công ty TNHH',),
				array('id'=>24,'title'=>'Công ty hợp danh',),
				array('id'=>25,'title'=>'Công ty liên doanh',),
				array('id'=>26,'title'=>'Hợp tác xã',),
				array('id'=>27,'title'=>'Cá nhân',),
		);
	}
	//
	
	public function getCategorys($o=[]){
		return \app\models\SiteMenu::getList($o);
	}
	
	public function getBootstrapMenu($o = []){
		$html = '<nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Project name</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>
              <li><a href="../navbar-static-top/">Static top</a></li>
              <li><a href="../navbar-fixed-top/">Fixed top</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>';
		return $html;
	}
	
	public function getMenuItem($o=[]){
		$key = isset($o['key']) ? $o['key'] : false;
		$maxLevel = isset($o['maxLevel']) && $o['maxLevel'] > 0 && $o['maxLevel'] < 8 ? $o['maxLevel'] : 8;
		$attrs = isset($o['attribute']) ? $o['attribute'] : (isset($o['attrs']) ? $o['attrs'] : []); 
		$listItem = isset($o['listItem']) ? $o['listItem'] : 
		\app\models\SiteMenu::getList([
				'key'=>$key
		]);
		$m = ''; $cLevel = 0;
		if($cLevel < $maxLevel && !empty($listItem)){
			$m .= '<ul ';
			if(!empty($attrs)){
				foreach($attrs as $a=>$t){
					$m .= $a .'="'.$t.'" ';
				}
			}			$m .= '>';
			$cLevel = 1;
			$m .= isset($o['firstItem']) ? $o['firstItem'] : '';
			foreach ($listItem as $k=>$v){
				// Check child
				$l1 = \app\models\SiteMenu::getList([
					'parent_id'=>$v['id']
				]);
				
				$li1Class = !empty($l1) ? (isset($o['li1WithChildClass']) ? $o['li1WithChildClass'] : '') : (isset($o['li1NotChildClass']) ? $o['li1NotChildClass'] : '');  
				
				$liActive = isset($o['activeClass']) && isset($o['activeClass']['li']) && in_array($v['url'],Yii::$app->request->get()) ? $o['activeClass']['li'] : '';
				$aActive = isset($o['activeClass']) && isset($o['activeClass']['a']) && in_array($v['url'],Yii::$app->request->get()) ? $o['activeClass']['a'] : '';
				$m .= '<li data-id="'.$v['id'].'" data-child="'.count($l1).'" class="li-child li-child-'.$k.' li-level-'.$cLevel.' '. $liActive.' '.(isset($o['li1Class']) ? $o['li1Class'] : '').' '.$li1Class.'">';
				$link = $v['type'] == 'link' ? $v['link_target'] : cu([DS.$v['url']]);				
				$m .= '<a '.(isset($v['rel']) ? ' rel="'.$v['rel'].'"' : '').' '.(isset($v['target']) ? ' target="'.$v['target'].'"' : '').' '.($link != '#' ? 'href="'.$link.'"' : 'role="none"').'  class="'.$aActive.'">';
				//$m .= $hTag[0];
				$m .= uh($v['title']);
				//$m .= $eTag[0];
				$m .= '</a>';
				if($cLevel < $maxLevel && !empty($l1)){
					$cLevel = 2;
					
					$m .= '<ul ';
					if(isset($o['ul2Attr']) && !empty($o['ul2Attr'])){
						foreach ($o['ul2Attr'] as $a=>$t){
							$m .= $a .'="'.$t.'" ';
						}
					}
					$m .= '>';
					foreach ($l1 as $k1=>$v1){
						$l2 = \app\models\SiteMenu::getList([
								'parent_id'=>$v1['id']
						]);
						$link = $v1['type'] == 'link' ? $v1['link_target'] : cu([DS.$v1['url']]);
						$m .= '<li data-id="'.$v1['id'].'" data-child="'.count($l2).'" class="li-child li-child-'.$k1.' li-level-'.$cLevel.' '.(isset($o['li2Class']) ? $o['li2Class'] : '').'">';
						$m .= '<a '.(isset($v1['rel']) ? ' rel="'.$v1['rel'].'"' : '').' '.(isset($v1['target']) ? ' target="'.$v1['target'].'"' : '').' '.($link != '#' ? 'href="'.$link.'"' : 'role="none"').'>';
						//$m .= $hTag[0];
						$m .= uh($v1['title']);
						//$m .= $eTag[0];
						$m .= '</a>';
						
						if($cLevel < $maxLevel && !empty($l2)){
							$cLevel = 3;
								
							$m .= '<ul >';
							foreach ($l2 as $k2=>$v2){
								$l3 = \app\models\SiteMenu::getList([
										'parent_id'=>$v2['id']
								]);
								$link = $v2['type'] == 'link' ? $v2['link_target'] : cu([DS.$v2['url']]);
								$m .= '<li data-id="'.$v2['id'].'" data-child="'.count($l3).'" class="li-child li-child-'.$k2.' li-level-'.$cLevel.'">';
								$m .= '<a '.(isset($v2['rel']) ? ' rel="'.$v2['rel'].'"' : '').' '.(isset($v2['target']) ? ' target="'.$v2['target'].'"' : '').' '.($link != '#' ? 'href="'.$link.'"' : 'role="none"').'>';
								//$m .= $hTag[0];
								$m .= uh($v2['title']);
								//$m .= $eTag[0];
								$m .= '</a>';
								
								if($cLevel < $maxLevel && !empty($l3)){
									$cLevel = 4;
								
									$m .= '<ul >';
									foreach ($l3 as $k3=>$v3){
										$l4 = \app\models\SiteMenu::getList([
												'parent_id'=>$v3['id']
										]);
										$link = $v3['type'] == 'link' ? $v3['link_target'] : cu([DS.$v3['url']]);
										$m .= '<li data-id="'.$v3['id'].'" data-child="'.count($l4).'" class="li-child li-child-'.$k3.' li-level-'.$cLevel.'">';
										$m .= '<a '.(isset($v3['rel']) ? ' rel="'.$v3['rel'].'"' : '').' '.(isset($v3['target']) ? ' target="'.$v3['target'].'"' : '').' '.($link != '#' ? 'href="'.$link.'"' : 'role="none"').'>';
										//$m .= $hTag[0];
										$m .= uh($v3['title']);
										//$m .= $eTag[0];
										$m .= '</a>';
								
								
										$m.= '</li>';
									}
									$m .= '</ul>';
								}
						
						
								$m.= '</li>';
							}
							$m .= '</ul>';
						}
						
						$m.= '</li>';
					}
					$m .= '</ul>';
				}
				
				$m.= '</li>';
			}
			$m .= '</ul>';
		}
		return $m; 
	}
	
	public function getAdvert($o = []){
		$type = isset($o['type']) && is_numeric($o['type']) ? $o['type'] : -1;
		$category_id = isset($o['category_id']) && is_numeric($o['category_id']) ? $o['category_id'] : -1;
		$box_id = isset($o['box_id']) && is_numeric($o['box_id']) ? $o['box_id'] : -1;
		$lang = isset($o['lang']) ? $o['lang'] : __LANG__;
		$code = isset($o['code']) ? $o['code'] : false;
		$index = isset($o['index']) ? $o['index'] : false;
		$is_all = isset($o['is_all']) ? $o['is_all'] : -1;
		if($index){
			if($category_id  == -1){
				$category_id = __CATEGORY_ID__;
			}
		}
		$orderBy = isset($o['orderBy']) ? $o['orderBy'] : ['a.position'=>SORT_ASC,'a.title'=>SORT_ASC];
		$query = (new Query())
		->select(['a.*'])
		->from(['a'=>'{{%adverts}}'])
		->where(['a.is_active'=>1])
		->andWhere(['>','a.state',-2]);
		if($lang !== false){
			$query->andWhere(['a.lang'=>$lang]);
		}
		if($is_all == 1 && $code !== false){
			
		}else{
			$query->andWhere(['a.sid'=>__SID__]);
		}
		if($code !== false){
			$type = -1;
			$query->addSelect(['category_title'=>'b.title'])
			->innerJoin(['b'=>'{{%adverts_category}}'],'a.type=b.id')
			->andWhere(['b.code'=>$code,'b.is_active'=>1,'b.is_'.Yii::$device=>1]);
			if($is_all == 1){
				$query->andWhere(['b.is_all'=>1,'b.sid'=>0]);
			}
		}
		if($type > -1){
			$query->andWhere(['a.type'=>$type]);
		}
		if($category_id > -1){
			$query->andWhere(['a.category_id'=>$category_id]);
		}
		if($box_id > -1){
			$query->andWhere(['a.box_id'=>$box_id]);
		}
		
		 
		return $query->orderBy($orderBy)->all();
		
	}
	public function getArticles($o = []){
		$rs = $vb = [];
		$count = $limit = 0; $p = 1; $key = false;
		 
		/* Check option
		 * 
		 */
		$key = isset($o['key']) ? $o['key'] : '';
		$id = isset($o['id']) && $o['id'] > 0 ? $o['id'] : 0;
		$box_id = isset($o['box_id']) && $o['box_id'] > 0 ? $o['box_id'] : 0;
		$box_code = isset($o['box_code']) ? $o['box_code'] : '';
		$box = isset($o['box']) && is_array($o['box']) ? $o['box'] : [];
		$url = isset($o['url']) && $o['url'] != "" ? $o['url'] : defined('__DETAIL_URL__') ? __DETAIL_URL__ : '';
		 
		$type =  isset($o['type']) ? $o['type'] : CONTROLLER;
		$category = isset($o['category']) ? $o['category'] :
		(defined('__CATEGORY_ID__') ? __CATEGORY_ID__ : 0);
		$category_id = isset($o['category_id']) ? $o['category_id'] : $category;
		$departure = isset($o['departure']) ? $o['departure'] : -1;
		$price_range = isset($o['price_range']) ? $o['price_range'] : '';
		$other =  isset($o['other']) ? $o['other'] : false;
		$detail = isset($o['detail']) && $o['detail'] == true ? true : false;
		$search = isset($o['search']) && $o['search'] == true ? true : false;
		$sort_subtitle = isset($o['sort_subtitle']) && $o['sort_subtitle'] == true ? true : false;
		$tabs = isset($o['tabs']) && $o['tabs'] == true ? true : false;
		$check_dateprice = isset($o['check_dateprice']) && $o['check_dateprice'] == false ? false : true;
		$sort = isset($o['sort']) ? $o['sort'] : false;
		$attr = isset($o['attr']) ? $o['attr'] : false;
		$box = !empty($box) ? $box : (strlen($key)>0 ?  \app\models\Box::getBox($key) : []);
		//view($box);
		$check_box_code = isset($o['check_box_code']) && $o['check_box_code'] == true ? true : false;
		if($check_box_code && empty($box)) return false;
		//
		$p = isset($o['p']) && $o['p'] > 1 ? $o['p'] : 1;
		//view($o);
		$limit = isset($o['limit']) && $o['limit'] > 0 ? $o['limit'] : 0;
		$limit = $limit > 0 ? $limit : (!empty($box) && $box['limit'] > 0 ?  $box['limit'] : $limit);
		$limit = $limit > 0 ? $limit : 12;
		$action_detail = isset($o['action_detail']) ? $o['action_detail']  : '';
		$count = isset($o['count']) && $o['count'] == true ? true : false;
		$igrone = isset($o['igrone']) ? $o['igrone'] : false;
		$offset = ($p-1) * $limit;
		$filter_tour_group = isset($o['filter_tour_group']) && $o['filter_tour_group'] > -1 ? $o['filter_tour_group'] : -1;
		// Check type		 
		if($type == 'auto'){
			//$type = $category_id > 0 ?  $this->_get_category_type($category,$url) : $this->_get_article_type($id,$url);
			$type = Slugs::getRoute($url, $category_id > 0 ? $category_id : $id,-1);
		}
		
		$vb = $box;
		if($box_id > 0){
			$vb =\app\models\Box::getBox($box_id);
		}
		
		// Check dl tu box
		if(!empty($vb)){
			$c = $result = array();
			$limit = $vb['limit'];
		
		
			if(isset($vb['form']) && $vb['form'] != ""){
				$type = $vb['form'];
			}
			if(isset($vb['attr']) && !empty($vb['attr'])){
				$attr = $vb['attr'];
			}
			if($vb['menu_id'] > 0){
				$m = \app\models\SiteMenu::getItem($vb['menu_id']);
				if(!empty($m)){
					$type = $m['type'];
					$action_detail = isset($m['action_detail']) && $m['action_detail'] != "" ? $m['action_detail'] : $action_detail;
					$category_id = $vb['menu_id'];
				}
			}
		
			if(isset($vb['articles_list']) && !empty($vb['articles_list'])){
				$c = $vb['articles_list'];
		
			} else{
				$c = isset($m['listItem']) && !empty($m['listItem']) ? $m['listItem'] : $c;
					
			}
			if(isset($vb['filter_by']) && !empty($vb['filter_by'])){
				$filters = $vb['filter_by'];
			}
		
		}
		//
		$query = (new Query())->select(['a.*'])->from(['a'=>Articles::tableName()])->where(['a.is_active'=>1,'a.sid'=>__SID__,'a.lang'=>__LANG__])->andWhere(['>','a.state',-2]);
		//
		switch ($type){
			case 'tours':
				$query->addSelect(['b.*'])
				->innerJoin(['b'=>'{{%tours_attrs}}'],'a.id=b.item_id');
				break;
		}
		//
		
		switch ($action_detail){
			case '{get_all_tour_1}': // Du lich trong nuoc
				//$where = "b.tour_type=1";
				$query->andWhere(['b.tour_type'=>1]);
				break;
			case '{get_all_tour_2}': // Du lich nuoc ngoai
				//$where = "b.tour_type=2";
				$query->andWhere(['b.tour_type'=>2]);
				break;
			case '{get_hot_item}': // Get item hot
				$attr = 'is_hot';
				break;
			default:
				if($detail){
					if($id > 0){
						$query->andWhere(['a.id'=>$id]);
					}else{
						$query->andWhere(['a.url'=>$url]);
					}
					 
				}else{
					$query->andWhere(['a.type'=>$type]);
					if($category_id > 0){
						$subQuery = (new Query())->select(['item_id'])->from(['{{%items_to_category}}'])->where([
								'in','category_id',SiteMenu::getAllChildID($category_id)
						]);
						if(isset($c['listItem']) && !empty($c['listItem'])){
							$subQuery->orWhere(['in','id',$c['listItem']]);
						}
						$query->andWhere(['a.id'=>$subQuery]);
					}
				}
				//view($category_id);
				//view(SiteMenu::getAllChildID($category_id));
				//$where = $detail ? ($id > 0 ? "a.id=$id" : "a.url='$url'") : " a.type='$type'" . ($category_id > 0 ?
				//" and (a.id in(select item_id from {$this->table('items_to_category')} where category_id in(".implode(',',Zii::$CRouter->_get_all_child_id($category,array(),0,array('type'=>$type))).")) ".(isset($c['listItem']) && !empty($c['listItem']) ? " or id in(".implode(',',$c['listItem']).")" : "" ).")" : "");
				break;
		}
		//view($attr);
		// Check Attr
		if($attr !== false){
			if(is_array($attr) && !empty($attr)){
				$vtx = array();
				foreach ($attr as $kt=>$at){
					$vt = $this->_get_item_id_by_attr($at);
					$existed = array();
					if(!empty($vt)){
						foreach ($vt as $vv){
							if($kt == 0){
								$vtx[] = $vv['item_id'];
							}
							$existed[] = $vv['item_id'];
		
						}
					}
					if(!empty($vtx)){
						foreach ($vtx as $kt=>$vt){
							if(!in_array($vt, $existed)){
								unset($vtx[$kt]);
							}
						}
					}
				}
				if(empty($vtx)){
					$vtx = array(0);
				}
				$query->andWhere(['in','a.id',$vtx]);
				//$where .= " and a.id in(".implode(',', $vtx).")";
			}else{
				$subQuery = (new Query())->select('g.item_id')->from(['g'=>'{{%articles_to_attrs}}'])
				->innerJoin(['h'=>Articles::tableName()],'g.item_id=h.id')
				->where(['h.sid'=>__SID__,'g.state'=>1,'g.attr_id'=>$attr]);
				$query->andWhere(['in','a.id',$subQuery]);
				//$where .= " and a.id in(select item_id from {$this->table('articles_to_attrs')} as a inner join {$this->table('articles')} 
				//as b on a.item_id=b.id and b.sid=".__SID__." where a.state=1 and a.attr_id='".$attr."')";
			}
		}
		//
		
		if($search){
			$q = isset($o['q']) ? $o['q'] : getParam('q');
			if(strlen($q) > 1){
				$query->andWhere("a.code like '%$q%' or a.url like '%".unMark($q)."%' or a.short_title like '%".$q."%'");
				//$where .= " and (a.code like '%$q%' or a.url like '%".unMark($q)."%' or a.short_title like '%".$q."%')";
			}
		}
		//
		if($other != false){
			$query->andWhere(['not in','a.id',$other]);
		}
		// Filters
		
		$filter_tour_type = isset($o['filter_tour_type']) ? $o['filter_tour_type'] : '';
		$filter_location = isset($o['filter_location']) ? $o['filter_location'] : '';
		$filter_category = isset($o['filter_category']) ? $o['filter_category'] : '';
		$filter_radio = isset($o['filter_radio']) ? $o['filter_radio'] : '';
		$filters_xx = isset($o['filters']) ? $o['filters'] : '';
		if($filter_location != "" && !is_array($filter_location)){
			$filter_location = explode(',', $filter_location);
		}
		if($filters_xx != "" && !is_array($filters_xx)){
			$filters_xx = explode(',', $filters_xx);
		}
		if(is_array($filter_location) && !empty($filter_location)){
			foreach ($filter_location as $a){
				if($a > 0) $filters[] = $a;
			}
		}
		if(is_array($filters_xx) && !empty($filters_xx)){
			foreach ($filters_xx as $a){
				if($a > 0) $filters[] = $a;
			}
		}
		if($filter_radio != "" && !is_array($filter_radio)){
			$filter_radio = explode(',', $filter_radio);
		}
		if(is_array($filter_radio) && !empty($filter_radio)){
			foreach ($filter_radio as $a){
				if($a > 0) $filters[] = $a;
			}
		}
		
		if($filter_tour_type != "" && !is_array($filter_tour_type)){
			$filter_tour_type = explode(',', $filter_tour_type);
		}
		$filter_tour_type_value = 0;
		if(is_array($filter_tour_type) && count($filter_tour_type) == 1){
			if($filter_tour_type[0] > 0){
				$filters[] = $filter_tour_type[0];
				$ftx = Filters::getItem($filter_tour_type[0]);// $this->getFilters(array('id'=>$filter_tour_type[0],'parent_id'=>-1,'query'=>'Row'));
				if(!empty($ftx)){
					$filter_tour_type_value = $ftx['value'];
				}
			}
		}elseif(is_array($filter_tour_type) && !empty($filter_tour_type)){
			foreach ($filter_tour_type as $a){
				if($a > 0) $filters[] = $a;
			}
		}
		if(!empty($filters)){
			$fArrays = Filters::getFilters(array('id'=>$filters,'parent_id'=>-1,'select'=>'a.id,a.menu_id',));
			///view($fArrays);
			$f1 = $f2 = array();
			if(!empty($fArrays)){
				foreach ($fArrays as $f){
					if($f['menu_id'] > 0){
						$fxs = SiteMenu::getAllChildID($f['menu_id']);// Zii::$CRouter->_get_all_child_id($f['menu_id']);
						if(!empty($fxs)){
							foreach ($fxs as $fx){
								$f1[] = $fx;
							}
						}
					}else{
						$f2[] = $f['id'];
					}
				}
			}
			if(!empty($f1)){
				$query->andWhere(['in','a.id',(new Query())->select('item_id')->from('items_to_category')->where(['in','category_id',$f1])->groupBy('item_id')]);
			}
			if(!empty($f2)){
				$query->andWhere(['in','a.id',(new Query())->select('item_id')->from('articles_to_filters')->where(['in','filter_id',$f2])]);
			}
			//$where .= !empty($f1) ? " and a.id in (select item_id from {$this->table('items_to_category')} where category_id in (".implode(',', $f1).") group by item_id)" : '';
			//$where .= !empty($f2) ? " and a.id in (select item_id from {$this->table('articles_to_filters')} where filter_id in (".implode(',', $f2)."))" : '';
			///view($where);
		}
		
		if($filter_category != "" && !is_array($filter_category)){
			$filter_category = explode(',', $filter_category);
		}
		if(is_array($filter_category) && !empty($filter_category)){
			$query->andWhere(['in','a.id',(new Query())->select('item_id')->from('items_to_category')->where(['in','category_id',SiteMenu::getAllChildID($filter_category)])]);
			//$where .= " and (a.id in(select item_id from {$this->table('items_to_category')} where category_id in(".implode(',', Zii::$CRouter->_get_all_child_id($filter_category)).")))";
		}
		
		// Check price range
		$price_range = $price_range != "" ? explode(';', $price_range) : array();
		switch (__LANG__){
			case 'vi_VN':
				$price_begin = 1000;
				$price_end = 20000;
				$factor = 1000;
				break;
			default:
				$price_begin = 100;
				$price_end = 10000;
				$factor = 1;
				break;
		}
		//
		$p1 = $p2 = 0;
		if(!empty($price_range) && count($price_range) > 1){
			$p1 = $price_range[0] > $price_begin ? $price_range[0] : 0;
			$p2 = $price_range[1] > $price_begin ? $price_range[1] : 0;
			$p2 = $p2 == $price_end ? 0 : $p2;
			if($p2>$p1){
				$query->andWhere(['between','a.price2',$p1*$factor,$p2*$factor]);
				//$where .= " and (a.price2 between $p1*$factor and $p2*$factor)";
			}
		}
		//
		switch ($type){
			case 'tours':
				$tstart = isset($o['start']) ? $o['start'] : getParam('start');
				$destination = isset($o['destination']) ? $o['destination'] : getParam('destination');
				$tour_group = isset($o['tour_group']) ? $o['tour_group'] : getParam('tour_group');
				$tour_type = isset($o['tour_type']) ? $o['tour_type'] : getParam('tour_type');
				$date_departure = isset($o['date_departure']) ? $o['date_departure'] : '';
				$time = isset($o['time']) ? $o['time'] : getParam('time');
				$rating_service = isset($o['rating_service']) ? $o['rating_service'] : -1;
				$filters_tour_type = isset($o['filters_tour_type']) ? $o['filters_tour_type'] : -1;
				$time = explode('-', $time);
				$d = isset($time[0]) && $time[0] > 0 ? $time[0] : 0;
				$n = isset($time[1]) && $time[1] > 0 ? $time[1] : 0;
					
				$price = isset($o['price']) ? $o['price'] : getParam('price');
				$price = explode('-', $price);
				$pfr = isset($price[0]) && $price[0] > 0 ? $price[0] : 0;
				$pto = isset($price[1]) && $price[1] > 0 ? $price[1] : 999999;
					
				$pfr = $pfr * 1000000; $pto = $pto * 1000000;
		
				//$where .= $tstart > 0 ? " and b.start=$tstart" : '';
				if($tstart>0){
					$query->andWhere(['b.start'=>$tstart]);
				}
				if($destination>0){
					$query->andWhere(['in','a.id',(new Query())->select('item_id')->from('tours_to_destinations')->where(['destination_id'=>$destination,'type'=>0])]);
				}
				if($tour_group>0){
					$query->andWhere(['b.tour_group'=>$tour_group]);
				}
				if($tour_type>0){
					$query->andWhere(['b.tour_type'=>$tour_type]);
				}
				if($d>0){
					$query->andWhere(['b.day'=>$d]);
				}
				if($n>0){
					$query->andWhere(['b.night'=>$night]);
				}
				if($pfr>0){
					$query->andWhere(['between','a.price2',$pfr,$pto]);
				}
				//$where .= $destination > 0 ? " and a.id in(select item_id from {$this->table('tours_to_destinations')} where destination_id=$destination and type=0)" : '';
				//$where .= $tour_group > 0 ? " and b.tour_group=$tour_group" : '';
				//$where .= $tour_type > 0 ? " and b.tour_type=$tour_type" : '';
				//$where .= $d > 0 ? " and b.day=$d" : '';
				//$where .= $n > 0 ? " and b.night=$n" : '';
				//$where .= $pfr > 0 ? " and (a.price2 between $pfr and $pto)" : '';
				if(is_numeric($departure) && $departure>0){
					$query->andWhere(['or',['b.start'=>$departure]],['a.id'=>(new Query())->select('item_id')->from('tours_to_destinations')->where(['destination_id'=>$departure,'type'=>2])]);
					//$where .= " and (b.start=$departure or a.id in(select item_id from {$this->table('tours_to_destinations')} where destination_id=$departure and type=2)) ";
				}
		
				if($check_dateprice && check_date_string($date_departure)){
					//view($date_departure);
					$date_departure_time = convertTime($date_departure,'Y-m-d',1);
					//$where .= " and (
					//a.id in(
					//select item_id from {$this->table('item_to_prices')} where 
					//(type=1 and UNIX_TIMESTAMP(time) between $date_departure_time and ".($date_departure_time+86400).")
        			//		OR (type=5 and group_id = ".date("w",$date_departure_time)."))
        			//		OR b.tour_style in(2,3)
        			//				)";
					$query->andWhere([
							'in','a.id',(new Query())->select('item_id')->from('item_to_prices')->where([
									'type'=>1,['between','UNIX_TIMESTAMP(time)',$date_departure_time,($date_departure_time+86400)]
							])->orWhere(['and','type=5','group_id='.date("w",$date_departure_time)])
							->orWhere(['b.tour_style',[2,3]])
					]);
				}
				//echo $where;
				break;
			default:
				break;
		}
		//
		//view($query->createCommand()->getRawSql());
		$count = $query->count(1);
		//
		switch ($sort){
			case 1: // Mới nhất				 
				$query->orderBy(['a.time'=> SORT_DESC]);
				break;
			case 2: // Giá cao - thấp
				//$order = ' a.price2 desc,a.position, a.time DESC';
				$query->orderBy(['a.price2'=>SORT_DESC,'a.position'=>SORT_ASC,'a.time'=>SORT_DESC]);
				break;
			case 3: // Giá thấp - cao
				//$order = ' a.price2 asc,a.position, a.time DESC';
				$query->orderBy(['a.price2'=>SORT_ASC,'a.position'=>SORT_ASC,'a.time'=>SORT_DESC]);
				break;
			case 4: // Tên a - z
				//$order = ($sort_subtitle ? ' a.short_title asc,a.title asc, a.position, a.time DESC' : ' a.title asc, a.position, a.time DESC') ;
				$query->orderBy($sort_subtitle ? ['a.short_title'=>SORT_ASC,'a.title'=>SORT_ASC,'a.position'=>SORT_ASC,'a.time'=>SORT_DESC] : ['a.title'=>SORT_ASC,'a.position'=>SORT_ASC,'a.time'=>SORT_DESC]);
				break;
			case 5: // Tên z - a
				$query->orderBy($sort_subtitle ? ['a.short_title'=>SORT_DESC,'a.title'=>SORT_DESC,'a.position'=>SORT_ASC,'a.time'=>SORT_DESC] : ['a.title'=>SORT_DESC,'a.position'=>SORT_ASC,'a.time'=>SORT_DESC]);
				break;
		
		
			default:
				$query->orderBy(['a.position'=>SORT_ASC,'a.time'=>SORT_DESC]);
				break;
		}
		
		$query->addSelect(['post_by_name'=>'concat(z.lname, \' \' , z.fname)']);
		$query->leftJoin(['z'=>'{{%users}}'],'a.owner=z.id');
		
		 
		$query->offset($offset);
		if($limit>0) $query->limit($limit);
		
		$rs = $query->all();
		
		if($detail && !empty($rs)){
			if($tabs){
				$rs[0]['tabs'] = $this->getDetailTabs($rs[0]['id']);
			}
			return $rs[0];
		}
		return array(
				'listItem'=>$rs,
				'totalItem'=>$count,
				'total_record'=>$count,
				'total_records'=>$count,
				'total_pages'=> $limit > 0 ? ceil($count/$limit) : 1,
				'totalPage'=> $limit > 0 ? ceil($count/$limit) : 1,
				'p'=>$p,'key'=>$key,
				'limit'=>$limit,
				'box'=>$vb
		);
	}
	private static function _get_item_id_by_attr($attr = ''){		
		return (new Query())
		->select('a.item_id')
		->from(['a'=>'{{%articles_to_attrs}}'])
		->innerJoin(['b'=>'{{%articles}}'],'a.item_id=b.id')
		->where([
				'b.sid'=>__SID__,
				'a.state'=>1,
				'a.attr_id'=>$attr
		])->all();
	}
	////////////
	public function getBoxIndex($o = array()){
		if(!is_array($o)){
			$type = $o;
			$o = array();
		}
		$attr = isset($o['attr']) ? $o['attr'] : false;
		$type = isset($o['type']) ? $o['type'] : 'products';
		$module = isset($o['module']) ? $o['module'] : 'index';
		$list_box = \app\models\Box::getBoxIndex($module);
			
		$action_detail = '';
		$result = [];
		if(!empty($list_box)){
			foreach($list_box as $kb=>$vb){
				//$result['box'] = $vb;
				$result[$vb['code']] = $this->getArticles(['box'=>$vb,'category_id'=>0]);
			}
		}
		return $result;
	}
	
	public function getBoxCode($o = array()){
		$module = '';
		if(!is_array($o)){
			$module = $o;
			$o = array();
		}
		$attr = isset($o['attr']) ? $o['attr'] : false;
		//$type = isset($o['type']) ? $o['type'] : 'products';
		$module = isset($o['code']) ? $o['code'] : $module;
		$list_box = \app\models\Box::getBox($module);
		return $this->getArticles(['box'=>$list_box,'category_id'=>0]); 
		 
	}
	
	public function get_tree_menu(){
		$l = SiteMenu::get_tree_menu();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$l[$k]['link'] = cu(DS.$v['url']);
			}
		}
		return $l;
	}
	
	//
	public function updateCart($action = 'add',$id=0,$amount=1){
		$amount = $amount > 0 ? $amount : 1;
	
		$c = isset($_SESSION[__SITE_NAME__]['cart']) ? $_SESSION[__SITE_NAME__]['cart'] : array($id=>array('amount'=>0,'total'=>0,'price'=>0));
		if(!isset($c[$id])) $c[$id]=array('amount'=>0,'total'=>0,'price'=>0);
		$item = Articles::getItem($id);
		 
		switch($action){
			case 'add':
				$c[$id]['amount'] += $amount;
				break;
			case 'update':
				$c[$id]['amount'] = $amount;
				break;
			case 'delete':
				$c[$id]['amount'] = 0;
				unset($c[$id]);
				break;
		}
		if(!empty($c) && isset($c[$id])){
			if($item === false){
				$c[$id]['amount'] = 0;
				unset($c['cart'][$id]);
				return false;
			}else{
				$c[$id]['price']= $item['price2'];
				$c[$id]['total']= $item['price2'] * $c[$id]['amount'];
				$c[$id]['item'] = $item;
			}
		}
		if(!empty($c)){
			foreach($c as $k=>$v){
				if($v['amount']==0 || $v['total']==0){
					// unset($c[$k]);
				}
			}
		}
		$_SESSION[__SITE_NAME__]['cart'] = $c;
		return true;
	}
	
	
	 
	public function getCart(){
		$c = isset($_SESSION[__SITE_NAME__]['cart']) ? $_SESSION[__SITE_NAME__]['cart'] : array(0=>array('amount'=>0,'total'=>0,'price'=>0));
	
		$listItem = array();
		$totalItem = 0;
		$totalPrice = 0;
		if(!empty($c)){
			foreach($c as $k=>$v){
				if($k>0){
					$listItem[] = $v;
					$totalItem ++;
					$totalPrice += $v['total'];
				}
			}
		}
	
		$cart = array(
				'totalItem'=>$totalItem,
				'totalPrice'=>$totalPrice,
				'listItem'=>$listItem,
		);
	
		return $cart;
	}
	public function unsetCart(){
		$_SESSION[__SITE_NAME__]['cart'] = null;
		unset($_SESSION[__SITE_NAME__]['cart']);		
	}
	public function sendEmail($o=[]){
		return Yii::$app->sendEmail($o);
	}
	public function getConfigs($code = false, $lang = __LANG__,$sid=__SID__,$cached=true){
		return Yii::$app->getConfigs($code,$lang,$sid,$cached);
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
		 
		$query = (new Query())->from(['a'=>'{{%form_design}}'])->where(['a.is_active'=>1,'a.lang'=>$lang]);
		if($show == false){
			$query->select(['a.*']);
		}else{
			$query->select(['a.value']);
		}
		if($code !== false){
			$query->innerJoin(['b'=>'{{%form_design_category}}'],'a.category_id=b.id');
			$query->andWhere(['b.code'=>$code]);
		} 
		if($id>0) $query->andWhere(['a.id'=>$id]);
		if($category_id>0) $query->andWhere(['a.category_id'=>$category_id]);
		if($default){
			$query->andWhere(['a.state'=>2]);
		}else{
			$query->andWhere(['and', 'a.sid=' . __SID__,['>','a.state',-2]]);			
		}
		$query->orderBy(['a.title'=>SORT_ASC]);
		if($show) {
			$l = $query->scalar();
			//$l = Zii::$db->queryScalar($sql);
		}
		if($list){
			$l = $query->all();
			//$l = Zii::$db->queryAll($sql);
		}else{
			$l = $query->one();
			//$l = Zii::$db->queryRow($sql);
		}
		if(empty($l) && is_array($o) && !$default){
			$o['default'] = true;
			return $this->getTextRespon($o);
		}
		return $l;
	}
	
	public function getDetailTabs($id = 0, $return_mode = 0){
		 
		$l = (new Query())->from('{{%tab_details}}')->where(['item_id'=>$id])->all();
		 
		switch($return_mode){
			case 1:
				// return id only
				$rs = array();
				if(!empty($l)){
					foreach($l as $k=>$v){
						$rs[] = $v['id'];
					}
				}
				return $rs;
				break;
			default: return $l; break;
		}
	}
	
	public function genCustomerCode($type_id = TYPE_ID_CUS){
		$pre = '';
		switch ($type_id){
			case 0: // Học viên
				$pre = 'ST';
				break;
			case 2: // Giáo viên
				$pre = 'TE';
				break;
			case 3: // Trợ giảng
				$pre = 'TA';
				break;
		}
		$code = $pre . danhso(rand(1,999999));
		while ((new Query)->from('customers')->where(array('code'=>$code,'sid'=>__SID__))->count(1) > 0){
			$code = $pre . danhso(rand(1,999999));
		}
		return $code;
	}
	
	
	public function getCVideos($o = array()){
		$limit = isset($o['limit']) ? $o['limit'] : 15;
		$sql = "select * from cvideos as a where a.state>-2 and a.sid=".__SID__ . " and a.lang='".__LANG__."'";
		$sql .= " order by a.position, a.time desc";
		$sql .= $limit > 0 ? " limit $limit" : '';
		return Yii::$app->db->createCommand($sql)->queryAll();
	}
	public function getLocals(){
		 
	}
	public function getBox($code = ''){
		return \app\models\Box::getBox($code);
	}
	// Database
	public function insert($table, $data, $id = 'id'){
		Yii::$app->db->createCommand()->insert($table,$data)->execute();
		if($id !== false){
			return (new Query())->select('max('.$id.')')->from($table)->scalar();
		}
		
	}
	public function update($table, $data, $condition){
		return Yii::$app->db->createCommand()->update($table,$data,$condition)->execute();
	}
	
	public function get_exrate($o = array()){
		$from = isset($o['from']) ? $o['from'] : 0;
		$to = isset($o['to']) ? $o['to'] : 0;
		$time = isset($o['time']) ? $o['time'] : false;
		$from = is_numeric($from) ? $from : $this->get_id_from_code($from);
		$to = is_numeric($to) ? $to : $this->get_id_from_code($to);
		if(!is_numeric($from)){
			$c = $this->getCurrencyByCode($from);
			$from = $c['id'];
		}
		if(!is_numeric($to)){
			$c = $this->getCurrencyByCode($to);
			$to = $c['id'];
		}
		
		$time = check_date_string($time) ? ctime(array('string'=>$time ,'return_type'=>1)) : false;
		$sql = "select a.to_currency,a.value,a.from_date from exchange_rate as a where a.from_currency=$from";
		$sql .= $to > 0 ? " and a.to_currency=$to" : "";
		$sql .= $time !== false ? " and DAYOFYEAR(a.from_date)=".date('z',$time) . " and YEAR(a.from_date)=" . date('Y',$time) : '';
		$sql .= " order by a.from_date desc";
		if(isset($o['return']) && $o['return'] == 'last'){
			$sql .= " limit 1";
			return Yii::$app->db->createCommand($sql)->queryOne();
		}
		return Yii::$app->db->createCommand($sql)->queryAll();
	}
	 
	public function getListContractType(){
		$sql = "select id,name from `contract_type` where state>0 and sid=".__SID__;
		$sql .= " order by name";
		$l = Zii::$db->queryAll($sql);
		return $l;
	}
	
	public function getExchangeRate($from = 2, $to = 1,$o = []){
		if($from == $to) return 1;
		$query = (new Query())->select(['value'])
		->from('exchange_rate')
		->where(['from_currency'=>$from,'to_currency'=>$to])
		->orderBy(['from_date'=>SORT_DESC])->limit(1); 
		if(isset($o['time']) && check_date_string($o['time'])){
			$expression = new \yii\db\Expression('UNIX_TIMESTAMP(from_date)');
			$query->andWhere(['<',$expression,strtotime($o['time'])]);
		}
		//return $query->getSql();
		return $query->scalar();
	}
	
	
	public function getServicePrice($price = 0, $o = []){
		$from = isset($o['from']) ? $o['from'] : 1;
		$to = isset($o['to']) ? $o['to'] : 1;
		$item_id = isset($o['item_id']) ? $o['item_id'] : 0;		
		//
		if($from == $to) return [
				'price'=>$price,
				'decimal'=>$this->showCurrency($to,3),
				'changed'=>false
		];
		//
		return [
				'price'=>$price * $this->getItemExchangeRate($o),
				'decimal'=>$this->showCurrency($to,3),
				'changed'=>true,
				'old_price'=>$this->showPrice($price, $from),
		];
	}
	
	public function getItemExchangeRate($o = []){
		$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
		$from = isset($o['from']) ? $o['from'] : 1;
		$to = isset($o['to']) ? $o['to'] : 1;
		$time = isset($o['time']) ? $o['time'] : date("Y-m-d H:i:s");
		$query = (new Query())
		->from('tours_programs_exchange_rate')
		->where(['from_currency'=>$from,'to_currency'=>$to, 'item_id'=>$item_id]);
		$item = $query->one();
		if(!empty($item)){
			return $item['value'];
		}
		return $this->getExchangeRate($from,$to,$o);
	}
	public function getServiceDetailPrices($o = []){
		//\\//\\ *.* //\\//\\
		$day = isset($o['day']) ? $o['day'] : -1;
		$time = isset($o['time']) ? $o['time'] : -1;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$item_id = isset($o['item_id']) ? $o['item_id'] : 0;
		$service_id = isset($o['service_id']) ? $o['service_id'] : 0;
		$type_id = isset($o['type_id']) ? $o['type_id'] : 0;
		$state = isset($o['state']) ? $o['state'] : 1;
		
		$total_pax = isset($o['total_pax']) ? $o['total_pax'] : 0;
		$nationality = isset($o['nationality']) ? $o['nationality'] : 0;
		$loadDefault = true;
		//\\//\\ *.* //\\//\\
		if($item_id>0){
			// Lấy giá đã lưu riêng
			
			$query = (new Query())->from(['a'=>'tours_programs_services_prices'])
			->where([
					'a.item_id'=>$item_id,
					//'supplier_id'=>$supplier_id,
					'a.state'=>$state,
					//'day'=>$day,
					//'time'=>$time,
					'a.type_id'=>$type_id
			]);
			//
			if($day > -1){
				$query->andWhere(['a.day'=>$day]);
			}
			if($time > -1){
				$query->andWhere(['a.time'=>$time]);
			}
			//
			if($service_id>0){
				$query->andWhere(['a.service_id'=>$service_id]);
			}
			//
			if($supplier_id>0){
				$query->andWhere(['a.supplier_id'=>$supplier_id]);
			}
			
			//
			$r = $service_id > 0 ? $query->one() : $query->all();
			if(!empty($r)){
				$loadDefault = false;
			}
		}
		if($loadDefault){
			// Lấy giá từ hệ thống		
						
			$r = [];
			
			switch ($type_id){
				case TYPE_ID_SCEN : // Vé tham quan
					$r = \app\modules\admin\models\Tickets::getPrice([
						'ticket_id'=>$service_id,
						'nationality'=>$nationality 
					]);
					$r['quantity'] = $total_pax;
					break;
				case TYPE_ID_HOTEL: // Khách sạn
					$r['currency'] = 1;
					$r['price1'] = 0;
					break;
			}
		}
			
		$r['quantity'] = isset($r['quantity']) ? $r['quantity'] : $total_pax;
		return $r;
	}
	
	public function getTablePrice($code,$price_type=1){
		switch ($code){
			case TYPE_ID_HOTEL:
			case TYPE_ID_SHIP_HOTEL:
				return '{{%rooms_to_prices}}';
				break;
			case TYPE_ID_REST:
				return '{{%menus_to_prices}}';
				break;	
			case TYPE_ID_VECL:
				return $price_type == 2 ? '{{%distances_to_prices}}' : '{{%vehicles_to_prices}}';
				break;
				
				
				
				
		}
		return false;
	}
	// File manager
	public function mkDir($path){
		if(!file_exists($path)){
			return @mkdir($path,0755,true);
		}
		return false;
	}
	
	public function getBoxCss($box){
		$b = $this->getBox($box);
		if(!empty($b)){
			$css = '<style type="text/css">';
			if(isset($b['css']) && !empty($b['css'])){
				
			}
			$css .= '</style>';
		}
		return false;
	}
	
	public function showTextDetail($text = '',$id = 0){
		return str_replace([
				'{LICH_KHOI_HANH}',
				'{LICH_KHAI_GIANG}',
				 
		],[
				//$this->get_departure_scheduler($id),
				'',
				$this->getLichKhaiGiang()
		], $text);
	}
	
	public function getLichKhaiGiang(){
		$b = load_model('branches');
		$l = $b->getLichKG(array('is_active'=>1));
		$html = '<div class="row">';
		if(!empty($l)){
			foreach ($l as $k=>$v){
				//if(isset($v['lich_kg']) && !empty($v['lich_kg'])){
				$html .= '<div class="ibox7 col-sm-12 col-xs-12"><p class="title7 clear"><b class="upper">'.uh($v['name']).' :</b> '.uh($v['address']).'</p></div>
<div class="col-sm-12 col-xs-12 ovs ibox7">
<table class="table lichkg vmiddle table-hover table-bordered f12px"> <thead>
<tr>
<th class="center w100p">Buổi học</th>
<th class="center w50p">Ca</th>
<th class="center w200p">Tên lớp</th>
<th class="center w300p"> Khai giảng</th>
<th class="center">Lịch học </th>
<th class="center">Học phí</th>
<th class="center">Đăng ký</th>
		
 </tr> </thead> <tbody>';
				if(isset($v['class']) && !empty($v['class'])){
					foreach ($v['class'] as $k1=>$v1){
						$html .= '<tr><td class="center">'.uh($v1['date_part']).'</td>
<td class="center">'.uh($v1['time_part']).'</td>
<td class="center bold">'.uh($v1['name']).'</td>
<td class="center">'.uh($v1['begin_text']).'</td>
<td class="center">'.uh($v1['calendar']).'</td>
<td class="center bold red">'.number_format($v1['price']).$this->showCurrency($v1['currency']).'</td>
<td class="center"><a target="_blank" class="areg btn btn-link" href="'.uh($v1['reg_link']).'">Đăng ký ngay</a></td></tr> ';
					}
				}
				$html .= '</tbody></table></div>';
				//}
			}
		}
		$html .= '</div>';
		return $html;
	}
	
	
	public function getUrl($url = '',$cate_id = 0){
		$url_link = '';
		$item = (new Query())->from('slugs')->where(['url'=>$url,'sid'=>__SID__])->andWhere(['>','state',-2])->one();
		$url_type = isset(Yii::$site['seo']['url_config']['type']) ? Yii::$site['seo']['url_config']['type'] : 2;
		 if($url_type == 2){
		 	return cu([DS. $item['url']]);
		 }
		if(!empty($item)){
			if($item['item_type'] == 0) {// menu
				$item_id = $item['item_id'];
			}else{
				$item_id = $cate_id > 0 ? $cate_id : (new Query())->select('category_id')->from('items_to_category')->where(['item_id'=>$item['item_id']])->scalar();
			}
			//
			
			
			switch ($url_type){
				case 1: // Full
					$c = [];
					foreach (\app\models\Slugs::getAllParent($item_id) as $k=>$v){
						view($v['url']);
						$c[] = $v['url'];
					}
					if($item['item_type'] == 1) {
						$c[] = $url;
					} 
					return cu([DS . implode('/', $c)]);
					break;
				case 3: // 1 cate					
					$c = [(new Query())->select('url')->from('site_menu')->where(['id'=>$item_id])->scalar()];					
					if($item['item_type'] == 1) {
						$c[] = $url;
					}
					return cu([DS . implode('/', $c)]);
					break;
				default:
					return cu([DS. $item['url']]);
					break;
			}
			
			 
		}
		 
	}
	public function updateAllUrlLink(){
		$l = (new Query())->from('site_menu')->where(['sid'=>__SID__])->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				Yii::$app->db->createCommand()->update('site_menu',[
						'url_link'=>$this->getUrl($v['url'])
				],[
						'id'=>$v['id']
				])->execute();
			}
		}
		//
		$l = (new Query())->from('articles')->where(['sid'=>__SID__])->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				Yii::$app->db->createCommand()->update('articles',[
						'url_link'=>$this->getUrl($v['url'])
				],[
						'id'=>$v['id']
				])->execute();
			}
		}
	}
	
	public function getVehiclePrice($o = []){
		//
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0; 
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		//
	}
	
	private function count_all_child($table, $id = 0,$sid = 0,$c = 0){
		//$m = Yii::$app->db->createCommand("select a.id,a.parent_id from $table as a where a.parent_id=$id" )->queryAll();
		$m = (new Query())->select(['id','parent_id'])->from($table)->where(['parent_id'=>$id]+($sid>0 ? ['sid'=>$sid] : []))->all();
		$c += count($m);
		if(!empty($m)){
			foreach ($m as $k=>$v){
				$c = $this->count_all_child($table,$v['id'],$sid,$c);
			}
		}
		return $c;
	}
	public function update_table_lft($o = [] ){		
		global $table_lft;
		@set_time_limit(0);
		@ini_set('mysql.connect_timeout','0');
		@ini_set('max_execution_time', '0'); 
		$table = $o['table'];
		$id = isset($o['id']) ? $o['id'] : 0;
		$lftx = isset($o['lftx']) ? $o['lftx'] : [];
		$sid = isset($o['sid']) ? $o['sid'] : 0;
		$level = isset($o['level']) && $o['level'] == true ? true : false;
		foreach ((new Query())->from($table)->where(['parent_id'=>$id]+($sid>0 ? ['sid'=>$sid] : []))->orderBy(['title'=>SORT_ASC])->all() as $k=>$v){
			$lftx[] = $table_lft;
			$lft_c = $table_lft;
			$childs = $this->count_all_child($table,$v['id'],$sid);
				
			if($childs > 0){
				$rgt = ($childs* 2) + 1 + $table_lft;
					
			}else{
				$rgt = ++$table_lft;
					//	if($k == count($l)-1) ++$local_lft;
			}
			$lftx[] = $rgt;
			while(in_array($table_lft, $lftx)){
				$table_lft++;
			}								
			if($level && $v['parent_id']>0){
				$level1 = Yii::$app->db->createCommand("select a.level from $table as a where a.id=".$v['parent_id'])->queryScalar();			 
				Yii::$app->db->createCommand()->update($table,['level'=>$level1+1],['id'=>$v['id']])->execute();
			}				
			$rgt_c = $rgt;
			Yii::$app->db->createCommand()->update($table,[
					'lft'=>$lft_c,
					'rgt'=>$rgt_c
			],array('id'=>$v['id']))->execute();
				 
			$o['id'] = $v['id'];
			$o['lftx'] = $lftx;
			$this->update_table_lft($o);
		}
	}
	
	public function parseCountry($id = 0){
		$query= (new Query())->select(['id','lft','rgt','title','level','type_id'])->from(\app\modules\admin\models\Local::tableName());
		if($id>0){
			$query->where(['id'=>$id]);
		}else {
			$query->where(['is_default'=>1,'parent_id'=>0]);
		}
		$item = $query->one();
		//var_dump($item); 
		if(!empty($item)){
			$r = (new Query())->select(['id','lft','rgt','title','level','type_id'])->from(\app\modules\admin\models\Local::tableName())->where([
					'and',
					['<','lft',$item['lft']],
					['>','rgt',$item['rgt']]					
			])->orderBy(['lft'=>SORT_ASC])->all();
			if(!empty($r)){
				$r[] = $item;
			}else{
				$r[0] = $item;
			}
			return [
					'country'=>$r[0],
					'province'=>isset($r[1]) ? $r['1'] : ['id'=>'-1','title'=>'-','type_id'=>0],
					'district'=>isset($r[2]) ? $r['2'] : ['id'=>'-1','title'=>'-','type_id'=>0],
					'ward'=>isset($r[3]) ? $r['3'] : ['id'=>'-1','title'=>'-','type_id'=>0],
			];
		}
		return false;
	}
	public function getMember($id = 0){
		$query = (new Query())
		->from(['a'=>\app\modules\admin\models\Customers::tableName()])
		->where(['a.sid'=>__SID__,'a.id'=>$id])
		->andWhere(['>','a.state',-2]);
		return $query->one();
	}
	
	public function getMembers($o = []){
		//$type_id = isset($o['type_id']) ? $o['type_id'] : TYPE_ID_CUS;
		$limit = isset($o['limit']) && is_numeric($o['limit']) ? $o['limit'] : 30;
		$filter_text = isset($o['filter_text']) ? $o['filter_text'] : '';
		$p = isset($o['p']) && is_numeric($o['p']) ? $o['p'] : 1;
		$count  = isset($o['count']) && $o['count'] == false ? false   : true;
		$offset = ($p-1) * $limit;
		$order_by = isset($o['order_by']) ? $o['order_by'] : ['a.position'=>SORT_ASC, 'a.fname'=>SORT_ASC];
		$parent_id = isset($o['parent_id']) ? $o['parent_id'] : -1;
		$is_active = isset($o['is_active']) ?  $o['is_active'] : 1;
		 
		$not_in = isset($o['not_in']) ? $o['not_in'] : [];
		$in = isset($o['in']) ? $o['in'] : [];
		if(!is_array($in) && $in != "") $in = explode(',', $in);
		if(!is_array($not_in) && $not_in != "") $not_in = explode(',', $not_in);
		//view($o,true);
		$type_id = isset($o['type_id']) ? $o['type_id'] : TYPE_ID_CUS ;
		$place_id = isset($o['place_id']) ? $o['place_id'] : 0;
		$local_id = isset($o['local_id']) ? $o['local_id'] : 0;
		$query = (new Query())
		->from(['a'=>\app\modules\admin\models\Customers::tableName()])
		//->leftJoin(['b'=>self::tableLocal()])
		->where(['a.sid'=>__SID__])
		->andWhere(['>','a.state',-2]);
		//->andWhere(['in','a.type_code',3]);
		if(strlen($filter_text) > 0){
			$query->andFilterWhere(['or',
					['like', 'a.name', $filter_text],
					['like', 'a.short_name', $filter_text],
					]);
		}
		if(is_numeric($type_id) && $type_id > -1){
			$query->andWhere(['in','a.type_id',$type_id]);
		}
		if(is_numeric($is_active) && $is_active > -1){
			$query->andWhere(['a.is_active'=>$is_active]);
		}
		if(!empty($in)){
			$query->andWhere(['a.id'=>$in]);
		}
		if(!empty($not_in)){
			$query->andWhere(['not in','a.id',$not_in]);
		}
		if($place_id > 0){
			$query->andWhere(['in','a.id',(new Query())
					->select('customer_id')
					->from('customers_to_places')
					->where(['place_id'=>$place_id])]);
		}
		if($local_id > 0){
			$query->andWhere(['a.local_id'=>$local_id]);
		}
		 
		$c = 0;
		if($count){
			$c = $query->count(1);
		}
		$query->select(['a.*'])
		->orderBy($order_by)
		->offset($offset)
		->limit($limit);
		//view($query->createCommand()->getSql());
		$l = $query->all();
		return [
				'listItem'=>$l,
				'total_records'=>$c,
				'total_pages'=>ceil($c/$limit),
				'limit'=>$limit,
				'p'=>$p,
		];
	}
	
	
	
	
	public function getDateInfo($date = ''){
		if(!check_date_string($date)){
			$date = date('Y-m-d');
		}else{
			$date = ctime(['string'=>$date,'format'=>'Y-m-d']);
		}
		//
		
	}
	
	
	
}






















