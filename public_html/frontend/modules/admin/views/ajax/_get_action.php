<?php 
use yii\db\Query;
switch (Yii::$app->request->get('action')){
	
	case 'load_domains':
		$r = [];
		$l = \app\modules\admin\models\Domains::find()
		->where(['>','state',-2])
		->andWhere(['sid'=>__SID__])
		->andFilterWhere(['like','domain',getParam('term')])
		->asArray()->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r[$k]['id'] = $v['domain'];
				$r[$k]['label'] = uh($v['domain']);
				$r[$k]['value'] = uh($v['domain']);
			}
		}
		echo cjson($r);exit;
		break;
	case 'load_dia_danh':
		$r = [];
		$l = \app\modules\admin\models\DeparturePlaces::find()->where(['>','state',-2])
		->andWhere(['sid'=>__SID__])
		->andFilterWhere(['like','name',getParam('term')])
		->asArray()->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r[$k]['id'] = $v['id'];
				$r[$k]['label'] = uh($v['name']);
				$r[$k]['value'] = uh($v['name']);
			}
		}
		echo cjson($r);exit;
		break;
	case 'load_foods':
		$r = [];
		$l = \app\modules\admin\models\Foods::find()->where(['>','state',-2])
		->andFilterWhere(['like','title',getParam('term')])
		->andWhere(['sid'=>__SID__])
		->asArray()->all();
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$r[$k]['id'] = $v['id'];
				$r[$k]['label'] = uh($v['title']);
				$r[$k]['value'] = uh($v['title']);
			}
		}
		echo cjson($r);exit;
		break;
	case 'countNotifis':		
		$noti = (new Query())->from('notifications')->where(['state'=>-1,'sid'=>__SID__])->count(1);
		$r = array('unview'=>$noti,'b'=>2);
		echo cjson($r);exit;
		break; 
	case 'getNotifis':
		 
		$l = (new Query())->from(['a'=>'notifications'])->where(['sid'=>__SID__])->andWhere(['>','state',-2])->limit(15)->orderBy([
				'time'=>SORT_DESC
		])->all();
		$html = '<div class="notification-scroll">';
		if(!empty($l)){
			foreach ($l as $k=>$v){
				$user = app\modules\admin\models\Users::getUserName($v['uid']);
				$html .= '<li class="item-state-'.$v['state'].'">
                      <a class="state-'.$v['state'].'" data-id="'.$v['id'].'" onclick="return changeNotificationState(this);" href="'.($v['link'] != "" ? $v['link'] : '#').'">
                        <span class="image hide"></span>
                        <span>
                          <span>'.$v['title'].'</span>
                          <p class="time fa fa-user">'.(trim($user) != "" ? '<span class="italic underline">' . trim($user) . '</span> - ': '').count_time_post($v['time']).'</p>
                        </span>
                        <span class="message">
						'.uh($v['note']).'
                        </span>
                      </a>
                    </li>';
					
			}
		}else{
			$html .= '<li><a><span><span></span><p class="time fa fa-user"></p></span><span class="message">Bạn chưa có thông báo nào ...</span></a></li>';
		}
		$html .= '</div><li onclick="return false;"><div class="text-center w100"><a><strong>Xem thêm thông báo</strong><i class="fa fa-angle-right"></i></a></div></li>';
		$r = array('html'=>$html);
		Yii::$app->db->createCommand()->update('notifications',['state'=>0],['sid'=>__SID__,'state'=>-1])->execute();
		echo json_encode($r); exit;
		break;
}