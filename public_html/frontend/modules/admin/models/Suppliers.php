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
	public static function getQuotation($date = '', $supplier_id = 0){
		if(!check_date_string($date)){
			$date = date('Y-m-d');
		}else{
			$date = ctime(['string'=>$date,'format'=>'Y-m-d']);
		}
		 
		return (new Query())->from(['a'=>self::tableQuotations()])
		->where(['and',['a.supplier_id'=>$supplier_id],
				['>','a.state',-2]])
		->andWhere("'$date' BETWEEN a.from_date and a.to_date")		
		->orderBy(['a.is_active'=>SORT_DESC])
		->one();
	}
	
	/*
	 * Lấy nhóm quốc tịch từ địa danh đã chọn
	 */
	
	public static function getNationalityGroup($nationality_id = 0, $supplier_id = 0){
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
		
		// Danh sách cuối tuần, ngày thường
		
		$thu_trong_tuan = date('w',strtotime($date));
		//
		$sub_query = (new Query())->from(['c'=>Seasons::tableWeekend()])
				->innerJoin(['d'=>Seasons::tableToSuppliers()],'c.id=d.season_id')
				->where([
						'd.supplier_id'=>$supplier_id,
						'd.type_id'=>[SEASON_TYPE_WEEKEND,SEASON_TYPE_WEEKDAY],
				])
				->andWhere("$thu_trong_tuan between c.from_date and c.to_date")
				
				->select('d.parent_id');
		if($time_id > -1){
			$t = configPartTime()[$time_id];
			$sub_query->andWhere(['<=','c.from_time',$t['from_time']])
			->andWhere(['>=','c.to_time',$t['to_time']]);
			
			
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
		
		
		
		return $r;
	}
	
}