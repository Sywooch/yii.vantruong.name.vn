<?php
namespace app\modules\admin\models;
use Yii;
use yii\db\Query;
class Suppliers extends Customers
{
	public static function tableQuotations(){
		return '{{%supplier_quotations}}';
	}
	
	/*
	 * Lấy báo giá của đơn vị theo ngày đã nhập 
	 */
	public static function getQuotation($o = [], $c = 0){
		$date = isset($o['date']) ? $o['date'] : date('Y-m-d');
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		if(!check_date_string($date)){
			$date = date('Y-m-d');
		}else{
			$date = ctime(['string'=>$date,'format'=>'Y-m-d']);
		}
		 
		$r = (new Query())->from(['a'=>self::tableQuotations()])
		->where(['and',['a.supplier_id'=>$supplier_id],
				['>','a.state',-2]])
		->andWhere("'$date' BETWEEN a.from_date and a.to_date")		
		->orderBy(['a.is_active'=>SORT_DESC])
		->one();
		if(empty($r) && $c<3){
			$date = date("Y-m-d",mktime(
					0,0,0,
					date('m',strtotime($date)),
					date('d',strtotime($date)),
					date('Y',strtotime($date))-1
			));
			$o['date'] = $date;
			return self::getQuotation($o,++$c);
		}
		return $r;
	}
	
	/*
	 * Lấy nhóm quốc tịch từ địa danh đã chọn
	 */
	
	public static function getNationalityGroup($o= []){
		$nationality_id = isset($o['nationality_id']) ? $o['nationality_id'] : 0;
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;	
				
		$query = (new Query())->from(['a'=>NationalityGroups::tableName()])->where([
				'a.id'=>(new Query())->from(['b'=>NationalityGroups::tableToLocal()])->where([
						'b.local_id'=>$nationality_id
				])->select(['b.group_id']),
				//'a.id'=>(new Query())->from(['b'=>NationalityGroups::tableToSupplier()])->where([
				//		'b.supplier_id'=>$supplier_id
				//])->select(['b.group_id']),
		])->andWhere(['a.id'=>(new Query())->from(['b'=>NationalityGroups::tableToSupplier()])->where([
				'b.supplier_id'=>$supplier_id
				])->select(['b.group_id'])]);
		//view($query->createCommand()->getRawSql()); 
		return $query->one();
	}
	
	/*
	 * Lấy danh sách các mùa / cuối tuần từ ngày đã nhập
	 */
	
	public static function getSeasons($o = []){
		$date = isset($o['date']) ? $o['date'] : date('Y-m-d'); 
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$time_id = isset($o['time_id']) ? $o['time_id'] : -1;
		if(!check_date_string($date)){
			$date = date('Y-m-d');
		}else{
			$date = ctime(['string'=>$date,'format'=>'Y-m-d']);
		}
		 
		// Danh sách mùa lấy theo ngày $date
		$query = (new Query())->from(['a'=>Seasons::tableCategory()])
		->innerJoin(['b'=>Seasons::table_category_to_supplier()],'a.id=b.season_id')
		->where(['and',
				['a.sid'=>__SID__,
						'b.supplier_id'=>$supplier_id,
						'a.type_id'=>[SEASON_TYPE_SERVICE],
						//'b.price_type'=>[0]
				],
				['>','a.state',-2]
				
		])->andWhere([
				'a.id'=>(new Query())->from(['c'=>Seasons::tableName()])
				->innerJoin(['d'=>Seasons::tableToSuppliers()],'c.id=d.season_id')
				->where([
						'd.supplier_id'=>$supplier_id,
						'd.type_id'=>[SEASON_TYPE_SERVICE]
				])
				->andWhere("'$date' between c.from_date and c.to_date")
				->select('d.parent_id')
		]);
		 	
		$r['seasons'] = $query->orderBy(['b.price_type'=>SORT_ASC])->all();
		$r['direct_seasons_prices'] = $r['incurred_seasons_prices'] = [];
		//
		//view($r['seasons']);
		
		// Danh sách cuối tuần, ngày thường 
		
		$thu_trong_tuan = date('w',strtotime($date));
		$ctt = $thu_trong_tuan == 0 ? 7 : $thu_trong_tuan;
		//
		$sub_query = (new Query())->from(['c'=>Seasons::tableWeekend()])
				->innerJoin(['d'=>Seasons::tableToSuppliers()],'c.id=d.season_id')
				->where([
						'd.supplier_id'=>$supplier_id,
						'd.type_id'=>[SEASON_TYPE_WEEKEND,SEASON_TYPE_WEEKDAY],
				]);
				if($thu_trong_tuan == 0){
					$sub_query->andWhere("($thu_trong_tuan between c.from_date and c.to_date) or ($ctt between c.from_date and c.to_date)");
				}else{
					$sub_query->andWhere("$thu_trong_tuan between c.from_date and c.to_date");
				}
				
				
				$sub_query->select('d.parent_id');
		if($time_id > -1){
			$t = configPartTime()[$time_id];
			$sub_query
			->andWhere("
					1=(case when c.to_date = $ctt then
					(case when
					c.to_time >= '".$t['to_time']."'
					and c.from_time <= '".$t['from_time']."'
					then 1 else 0 end
					)
					 
					else 1
					end)
			")
			//->andWhere(['<=','c.from_time',$t['from_time']])
			//->andWhere(['>=','c.to_time',$t['to_time']])
			;
			
			
		}
		//
		$query = (new Query())->from(['a'=>Seasons::tableCategory()])
		->innerJoin(['b'=>Seasons::table_category_to_supplier()],'a.id=b.season_id')
		->where(['and',
				['a.sid'=>__SID__,
						'b.supplier_id'=>$supplier_id,
						'a.type_id'=>[SEASON_TYPE_WEEKEND,SEASON_TYPE_WEEKDAY],
						//'b.price_type'=>[0]
				],
				['>','a.state',-2]
		
		])
		
		->andWhere([
				'a.id'=>$sub_query 
		])
		
		;
		//view($query->createCommand()->getRawSql());
		
		$r['week_day'] = $query->orderBy(['b.price_type'=>SORT_ASC])->all();
		$r['week_day_prices'] = $query->andWhere(['b.price_type'=>[0]])->one();
		
		// Danh sách buổi trong ngày
		
		$thu_trong_tuan = date('w',strtotime($date));
		//
		$sub_query = (new Query())->from(['c'=>Seasons::tableWeekend()])
		->innerJoin(['d'=>Seasons::tableToSuppliers()],'c.id=d.season_id')
		->where([
				'd.supplier_id'=>$supplier_id,
				'd.type_id'=>[SEASON_TYPE_TIME],
		])
		->andWhere("$thu_trong_tuan between c.from_date and c.to_date")
		
		->select('d.parent_id');
		if($time_id > -1){		 
			$sub_query->andWhere(['c.part_time'=>$time_id])	;								
		}
		//
		$query = (new Query())->from(['a'=>Seasons::tableCategory()])
		->innerJoin(['b'=>Seasons::table_category_to_supplier()],'a.id=b.season_id')
		->where(['and',
				['a.sid'=>__SID__,
						'b.supplier_id'=>$supplier_id,
						'a.type_id'=>[SEASON_TYPE_TIME],
						//'b.price_type'=>[0]
				],
				['>','a.state',-2]
		
		])
		
		->andWhere([
				'a.id'=>$sub_query
		])
		
		;
		//view($query->createCommand()->getRawSql());
		
		$r['time_day'] = $query->orderBy(['b.price_type'=>SORT_ASC])->all();
		$r['time_day_prices'] = $query->andWhere(['b.price_type'=>[0]])->one(); 
		// Tổng hợp dữ liệu
		$rx = array_merge($r['seasons'],$r['week_day'],$r['time_day']);
		 
		if(!empty($r['seasons'])){ $cPriceType0 = 0;
		foreach ($r['seasons'] as $k=>$v){
		
			if($v['price_type'] == 0){
				if($cPriceType0 == 0){
					$r['seasons_prices'] = $v;++$cPriceType0;
				}
				$r['direct_seasons_prices'][] = $v;
			}else{
				$r['incurred_seasons_prices'][] = $v;
			}
			if(!isset($r['seasons_price_type_'.$v['price_type']])){
				$r['seasons_price_type_'.$v['price_type']][0] = $v;
			}else{
				$r['seasons_price_type_'.$v['price_type']][] = $v;
			}
		
		}
		}
		 
		//view(self::getDirectSeason(['season_id'=>7,'supplier_id'=>$supplier_id]));
		
		$directSeasons = isset($r['seasons_price_type_0']) ? $r['seasons_price_type_0'] : [];
		
		if(!empty($r['seasons_price_type_1'])){
			foreach ($r['seasons_price_type_1'] as $i){
				foreach (self::getDirectSeason([
						'season_id'=>$i['id'],
						'supplier_id'=>$supplier_id,
						'default_direct'=>isset($r['seasons_price_type_0']) ? $r['seasons_price_type_0'] : [],
				]) as $x){
					$directSeasons[] = $x;
				}				 				 
			}
		}
		$r['season_direct_prices'] = $directSeasons; 
		return $r;
	}
	
	public static function getDirectSeason($o = []){
		//
		$season_id = isset($o['season_id']) ? $o['season_id'] : 0;
		$default_direct = isset($o['default_direct']) ? $o['default_direct'] : [];
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		$price_incurred1 = isset($o['price_incurred1']) ? $o['price_incurred1'] : 1;
		$price_incurred2 = isset($o['price_incurred2']) ? $o['price_incurred2'] : 0;
		$last_season_id = isset($o['last_season_id']) ? $o['last_season_id'] : 0;
		$unit_price = isset($o['unit_price']) ? $o['unit_price'] : 0;
		//
		 
		//
		$seasons = Seasons::getUserSeasons($season_id,$supplier_id);
		//view($seasons);
		if(!empty($seasons)){
			foreach ($seasons as $k => $season){
				if($season['price_type'] == 1){
					// tinh gia truc tiep
					$season['last_season_id'] = $season['id'];
						
					$o['last_season_id'] = $last_season_id = $season['id'];
					if($season['parent_id'] > 0){
						//view($season['price_incurred']);
						$price_incurred1 *= 1 + ($season['price_incurred']/100);
						//view($price_incurred1);
						$o['season_id'] = $season['parent_id'];
						$o['price_incurred1'] = $price_incurred1;
				
						return self::getDirectSeason($o);
					}else{
						if(!empty($default_direct)){
							foreach ($default_direct as $c=>$d){
								$d['price_incurred1'] = isset($season['price_incurred1']) ? $season['price_incurred1'] : 1;
								$d['last_season_id'] = isset($season['last_season_id']) ? $season['last_season_id'] : 0;
								$d['price_incurred2'] = isset($season['price_incurred2']) ? $season['price_incurred2'] : 0;
								$default_direct[$c] = $d;
							}
						}
						return $default_direct;
					}
				}
				$season['price_incurred1'] = $price_incurred1;
				$season['last_season_id'] = $last_season_id;
				$season['price_incurred2'] = $price_incurred2;
				$seasons[$k] = $season;
			}
		}else{
			
		}
		 
		//view($seasons);
		return $seasons;
	}
	
	/*
	 * Lấy dánh sách nhóm khách / Phòng	 
	 */
	public function getGuestGroup($o = []){
		$supplier_id = isset($o['supplier_id']) ? $o['supplier_id'] : 0;
		// Tổng số phòng (tính từ tổng số khách)
		$total_pax = isset($o['total_pax']) ? $o['total_pax'] : 0;
		$query = (new Query())->from(['a'=>'rooms_groups'])->where([
				'a.sid'=>__SID__,'a.parent_id'=>$supplier_id
		])
		->andWhere("$total_pax between a.pmin and a.pmax")
		->one();
		return $query;
	}
	
	/*
	 * 	 
	 */
	
	 
	
}