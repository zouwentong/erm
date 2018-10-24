<?php
namespace Sale\Model;
use Think\Model;
class OrderModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('name','require','请输入姓名'),
		array('mobile','require','请输入电话号码'),
		array('qq','require','请输入qq号码'),
		array('emNum','require','请输入em号')
	);

	// 自动填充
	protected $_auto=array(
		array('cTime','time',1,'function'),
		array('uTime','time',3,'function')
	);


	protected function _before_insert(&$data,$options){
		$data['unusualReason']='';
		if(I('post.customerId')<1){
			if($customer=M('Customer')->field(true)->where("qq='".trim(I('post.qq'))."' OR qqEmail='".trim(I('post.qq'))."'")->find()){
				$customerId=$customer['id'];

				$data['customerId']=$customer['id'];

				M('Customer')->where('id='.$customer['id'])->save([
					'emNum'=>trim(I('post.emNum')),
					'name'=>trim(I('post.name')),
					'mobile'=>trim(I('post.mobile')),
					'uTime'=>time()
				]);

				// 客户信息表未找到
				if(!$customerInfo=M('CustomerInfo')->where('id='.$customer['id'])->find()){
					M('CustomerInfo')->add([
						'id'=>$customer['id'],
						'province'=>trim(I('post.province')),
						'city'=>trim(I('post.city')),
						'address'=>trim(I('post.address'))
					]);
				}else{
					M('CustomerInfo')->where('id='.$customerInfo['id'])->save([
						'id'=>$customer['id'],
						'province'=>trim(I('post.province')),
						'city'=>trim(I('post.city')),
						'address'=>trim(I('post.address'))
					]);
				}

				// 客服/上拽维护表未找到
				if(!M('CustomerService')->where('customerId='.$customer['id'])->find()){
					M('CustomerService')->add([
						'customerId'=>$customer['id']
					]);
				}

			}else{
				if(!$customer=M('Customer')->field(true)->where("mobile='".trim(I('post.mobile'))."'")->find()){
					$id=M('Customer')->add([
						'emNum'=>trim(I('post.emNum')),
						'name'=>trim(I('post.name')),
						'qq'=>trim(I('post.qq')),
						'mobile'=>trim(I('post.mobile')),
						'cTime'=>time(),
						'uTime'=>time()
					]);
					M('CustomerInfo')->add([
						'id'=>$id,
						'province'=>trim(I('post.province')),
						'city'=>trim(I('post.city')),
						'address'=>trim(I('post.address'))
					]);

					M('CustomerService')->add([
						'customerId'=>$id
					]);

					$data['customerId']=$id;

					$data['isCheck']=4;
					$data['unusualReason'].='|客户池未找到该QQ|';
				}else{
					$data['customerId']=$customer['id'];
					M('CustomerExtend')->add([
						'customerId'=>$customer['id'],
						'extendField'=>'qq',
						'extendValue'=>trim(I('post.qq'))
					]);
				}

				$customerId=$data['customerId'];
			}
		}else{
			$customerId=I('post.customerId');

			$data['customerId']=$customerId;
			M('Customer')->where('id='.I('post.customerId'))->save([
				'emNum'=>trim(I('post.emNum')),
				'name'=>trim(I('post.name')),
				'mobile'=>trim(I('post.mobile')),
				'uTime'=>time()
			]);

			M('CustomerInfo')->where('id='.I('post.customerId'))->save([
				'province'=>trim(I('post.province')),
				'city'=>trim(I('post.city')),
				'address'=>trim(I('post.address'))
			]);

			if(!M('CustomerService')->where('customerId='.I('post.customerId'))->find()){
				M('CustomerService')->add([
					'customerId'=>I('post.customerId')
				]);
			}
		}

		if(!M('EmpCustomer')->where("empId=".session('empId')." AND customerId=".$data['customerId'])->find()){
			M('EmpCustomer')->add([
				'empId'=>session('empId'),
				'customerId'=>$data['customerId']
			]);
		}

		M('EmpCustomer')->where('customerId='.$data['customerId'])->setField('isTrade',1);

		if(I('post.isNew')==5 || I('post.isNew')==6 || I('post.isNew')==7){
			$data['orderNum']=0;
		}

		// 异常单处理
		if(I('post.price')>2000){
			if(session('deptId')==30 || session('deptId')==31 || session('deptId')==32 || session('deptId')==33){
				// 上拽录新增 异常
				if(I('post.isNew')==1){
					$data['isCheck']=2;
					$data['unusualReason'].='|上拽录新增|';
				}
			}else{
				if(I('post.isNew')==5){
					$data['isCheck']=2;
					$data['unusualReason'].='|新增录上拽|';
				}elseif(I('post.isNew')==6){
					$data['isCheck']=2;
					$data['unusualReason'].='|新增录续费|';
				}else{
					$ec=M('EmpCustomer')->alias('ec')->field('e.empNo,ec.empId')->join('ym_emp e ON ec.empId=e.id')->where('ec.customerId='.$customerId.' AND ec.isDelete=0 AND e.deptId NOT IN (26,30,31,32,33) AND ec.fileName <> "" ')->select();
					if(count($ec)>1){
						$data['unusualReason'].='|客户qq存在于多个qq群|';
						$empCustomer=M('Customer')->field('name,qq')->find($customerId);

						foreach($ec as $v){
							if($v['empId']!=session('empId')){
								M('Msg')->add([
					                'typeId'=>1,
					                'content'=>"存在你群的客户".$empCustomer['name']."(qq:".$empCustomer['qq'].")已被成交，如有争议，请联系销售助理。",
					                'status'=>0,
					                'cTime'=>time(),
					                'uTime'=>time(),
					                'sender'=>'88888',
					                'reader'=>M('Emp')->where('id='.$v['empId'])->getField('empNo')
					            ]);
							}
						}
					}
				}
			}
		}else{
			if(I('post.isNew')!=6){
				$data['isNew']=7;
			}

			$data['orderNum']=0;
		}

		if(empty(I('post.sourceId'))){
			if($source=M('EmpCustomer')->where('empId='.session('empId').' AND customerId='.$data['customerId'])->getField('source')){
				$data['sourceId']=M('Source')->where("sourceName='".trim($source)."'")->getField('id');

				if(empty($data['sourceId'])){
					$data['sourceId']=87;
				}
			}else{
				$customer=M('Customer')->where("id=".$data['customerId'])->find();
				if(!empty($customer['source'])){
					$data['sourceId']=M('Source')->where("sourceName='".trim($customer['source'])."'")->getField('id');
				}else{
					$data['sourceId']=M('Order')->where('customerId='.$customer['id'])->order('id asc')->getField('sourceId');
				}

				if(empty($data['sourceId'])){
					$data['sourceId']=87;
				}
			}

			if($data['isCheck']==4 && $data['sourceId']==87){
				$data['unusualReason'].='|来源未知|';
				// $data['isCheck']=4;
			}
		}

		if(session('deptId')==26){
			$data['isCheck']=1;
		}

		S('personalSaleChart'.session('empId'),null);
		S('saleData',null);
	}

	protected function _after_insert($data,$options){
		actionLog('新增订单','Order',$data['id']);

		if(I('post.joinTime') && I('post.chatSource')){
			$joinTime=strtotime(I('post.joinTime'));
	        $orderId=$data['id'];
	        M('OrderAnnual')->add([
                'orderId'=>$orderId,
                'joinTime'=>$joinTime,
                'chatSource'=>I('post.chatSource')
            ]);
		}

	}

	protected function _before_update(&$data,$options){
		// if(I('post.isNew')==1){
		// 	$data['orderNum']=1;
		// }else{
		// 	$data['orderNum']=0;
		// }

		// if(I('post.price')>2000){
		// 	if(session('deptId')==30 || session('deptId')==31 || session('deptId')==32 || session('deptId')==33){
		// 		// 上拽录新增 异常
		// 		if(I('post.isNew')==1){
		// 			$data['isCheck']=2;
		// 			$data['unusualReason'].='|上拽录新增|';
		// 		}
		// 	}else{
		// 		if(I('post.isNew')==5){
		// 			$data['isCheck']=2;
		// 			$data['unusualReason'].='|新增录上拽|';
		// 		}elseif(I('post.isNew')==6){
		// 			$data['isCheck']=2;
		// 			$data['unusualReason'].='|新增录续费|';
		// 		}else{
		// 			$ec=M('EmpCustomer')->alias('ec')->field('e.empNo,ec.empId')->join('ym_emp e ON ec.empId=e.id')->where('ec.customerId='.$customerId.' AND ec.isDelete=0 AND e.deptId NOT IN (26,30,31,32,33) AND ec.fileName <> "" ')->select();
		// 			if(count($ec)>1){
		// 				$data['unusualReason'].='|客户qq存在于多个qq群|';
		// 				$empCustomer=M('Customer')->field('name,qq')->find($customerId);

		// 				foreach($ec as $v){
		// 					if($v['empId']!=session('empId')){
		// 						M('Msg')->add([
		// 			                'typeId'=>1,
		// 			                'content'=>"存在你群的客户".$empCustomer['name']."(qq:".$empCustomer['qq'].")已被成交，如有争议，请联系销售助理。",
		// 			                'status'=>0,
		// 			                'cTime'=>time(),
		// 			                'uTime'=>time(),
		// 			                'sender'=>'88888',
		// 			                'reader'=>M('Emp')->where('id='.$v['empId'])->getField('empNo')
		// 			            ]);
		// 					}
		// 				}
		// 			}
		// 		}
		// 	}
		// }
	}

	protected function _after_delete($data,$options){
		actionLog('删除订单','Order',$data['id']);
	}


	protected function _after_update($data,$option){

	}

	public function lists($order="collectDate DESC",$permission=1,$pagesize=10){
		if($permission==1){
			// 管理员 能看所有订单
			$where="1 AND isCheck <> 2 AND isCancel=2";

			if(session('empNo')=='60123'){
				$where="o.collectDate<'2016-01-01'";
			}
		}elseif($permission==2){
			// 员工 只能看自己的订单
			$where="isCancel=2 AND o.empId=".session('empId');
		}elseif($permission==3){
			// 主管 只能看自己团队的订单
			$teamId=M('Team')->where("teamLeader=".session('empId'))->getField('id');
			$where="isCancel=2  AND e.teamId=".$teamId;
		}elseif($permission==4){
			// 总监 只能看自己团队的订单
			$teamIds=M('Team')->where("teamManager=".session('empId'))->getField('id',true);
			$where="isCancel=2 AND e.teamId IN (".implode(',',$teamIds).")";
		}

    	I('get.startCollectDate') && $where.=" AND collectDate >='".I('get.startCollectDate')."'";
    	I('get.endCollectDate') && $where.=" AND collectDate <='".I('get.endCollectDate')."'";

    	I('get.startAddTime') && $where.=" AND o.cTime >='".strtotime(I('get.startAddTime'))."'";
    	I('get.endAddTime') && $where.=" AND o.cTime <='".strtotime(I('get.endAddTime'))."'";

    	I('get.name') && $where.=" AND c.name = '".I('get.name')."'";
    	I('get.qq') && $where.=" AND c.qq = '".I('get.qq')."'";
    	I('get.mobile') && $where.=" AND c.mobile = '".I('get.mobile')."'";
    	I('get.emNum') && $where.=" AND c.emNum = '".I('get.emNum')."'";
    	I('get.orderNo') && $where.=" AND o.orderNo = '".I('get.orderNo')."'";

    	I('get.empName') && $where.=" AND (e.empName = '".I('get.empName')."' OR e.empNo='".I('get.empName')."')";

    	I('get.isCancel') && $where.=" AND isCancel = ".I('get.isCancel');
    	I('get.isNew') && $where.=" AND isNew = ".I('get.isNew');
    	I('get.sourceId') && $where.=" AND sourceId = ".I('get.sourceId');

    	I('get.productId') && $where.=" AND productId = ".I('get.productId');

    	if($deptId=I('get.deptId')){
    		$deptIds=getSubDeptEmp($deptId,'ONLY_EMPNO',1);
    		$where.=" AND e.deptId IN (".implode(',',$deptIds).")";
    	}


    	$orderBy=I('get.orderby')?I('get.orderby'):'collectDate';
    	$orderWay=I('get.orderway')?I('get.orderway'):'DESC';

    	$order="$orderBy $orderWay";


		$count=$this->alias('o')->field("o.`id`,`empId`,`customerId`,`productId`,`sourceId`,`isNew`,`collectDate`,`price`,`orderNo`,`goodsNum`,`orderNum`,`beizhu`,o.`status`,`isCheck`,`isCancel`,o.`cTime`,o.`uTime`,c.name,c.emNum,c.mobile,c.qq,e.empName,p.productName,r.sourceName,i.address")->join("LEFT JOIN ym_product p ON p.id=o.productId")->join("LEFT JOIN ym_emp e ON e.id=o.empId")->join("LEFT JOIN ym_source r ON r.id=o.sourceId")->join("LEFT JOIN ym_customer c ON c.id=o.customerId")->join("LEFT JOIN ym_customer_info i ON i.id=c.id")->where($where)->count();

		$Page=paginate($count,$pagesize);

		$data=$this->alias('o')->field("o.`id`,o.`empId`,o.unusualReason,o.`customerId`,`productId`,`sourceId`,`isNew`,`collectDate`,`price`,`orderNo`,`goodsNum`,`orderNum`,`beizhu`,o.`status`,`isCheck`,`isCancel`,o.`cTime`,o.`uTime`,c.name,c.emNum,c.mobile,c.qq,e.empName,p.productName,r.sourceName,i.address,i.province,i.city,cs.id chatSourceId,ycs.`serviceId`")->join("LEFT JOIN ym_product p ON p.id=o.productId")->join("LEFT JOIN ym_emp e ON e.id=o.empId")->join("LEFT JOIN ym_source r ON r.id=o.sourceId")->join("LEFT JOIN ym_customer c ON c.id=o.customerId")->join("LEFT JOIN ym_customer_info i ON i.id=c.id")->join("left join ym_chat_source cs ON cs.orderId=o.id AND chatId>0")->join("LEFT JOIN ym_customer_service ycs ON o.customerId=ycs.customerId")->where($where)->group('o.id')->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		echo $this->_sql();
		foreach($data as $k=>$v){
			if($v['isNew']!=1){
				$arr=M('Order')->where('customerId='.$v['customerId'])->order('id ASC')->find();
				$emp=M('Emp')->where('id='.$arr['empId'])->getField('empName');
				$data[$k]['firstCollect']=$arr['collectDate'].'('.$emp.')';
			}else{
				$data[$k]['firstCollect']='-';
			}
				$data[$k]['serviceName']=M("Emp")->where("id=".$v['serviceId'])->getField("empName");

		}

//		echo $this->_sql();

		S('orderData'.session('empId'),$this->alias('o')->field("o.`id`,o.orderNum,o.price")->join("ym_product p ON p.id=o.productId")->join("ym_emp e ON e.id=o.empId")->join("ym_source r ON r.id=o.sourceId")->join("ym_customer c ON c.id=o.customerId")->join("ym_customer_info i ON i.id=c.id")->where($where.' AND isCheck<>2')->order($order)->select());

		//var_dump(S('orderData'.session('empId')));
		return array('pageStr'=>$Page->show(),'data'=>$data);
	}

	public function unusual($order="collectDate DESC",$permission=1,$pagesize=10){
		if($permission==1){
			$where="isCancel=2 AND isCheck=2";
		}elseif($permission==2){
			$where="isCancel=2 AND (isCheck=4 OR sourceId=87)";
		}elseif($permission==4){
			$where="isCancel=2 AND unusualReason LIKE '%客户qq存在于多个qq群%'";
		}

		I('get.name') && $where.=" AND name LIKE '%".I('get.name')."%'";
    	I('get.mobile') && $where.=" AND mobile LIKE '%".I('get.mobile')."%'";
    	I('get.qq') && $where.=" AND qq LIKE '%".I('get.qq')."%'";
    	I('get.emNum') && $where.=" AND emNum LIKE '%".I('get.emNum')."%'";

    	I('get.startCollectDate') && $where.=" AND collectDate >='".I('get.startCollectDate')."'";
    	I('get.endCollectDate') && $where.=" AND collectDate <='".I('get.endCollectDate')."'";

    	I('get.startAddTime') && $where.=" AND o.cTime >='".strtotime(I('get.startAddTime'))."'";
    	I('get.endAddTime') && $where.=" AND o.cTime <='".strtotime(I('get.endAddTime'))."'";

    	I('get.name') && $where.=" AND c.name = '".I('get.name')."'";
    	I('get.qq') && $where.=" AND c.qq = '".I('get.qq')."'";
    	I('get.mobile') && $where.=" AND c.mobile = '".I('get.mobile')."'";
    	I('get.emNum') && $where.=" AND c.emNum = '".I('get.emNum')."'";
    	I('get.orderNo') && $where.=" AND o.orderNo = '".I('get.orderNo')."'";

    	I('get.isCancel') && $where.=" AND isCancel = ".I('get.isCancel');
    	I('get.isNew') && $where.=" AND isNew = ".I('get.isNew');
    	I('get.sourceId') && $where.=" AND sourceId = ".I('get.sourceId');

    	I('get.productId') && $where.=" AND productId = ".I('get.productId');

		$count=$this->alias('o')->field("o.`id`,`empId`,`customerId`,`productId`,`sourceId`,`isNew`,`collectDate`,`price`,`orderNo`,`goodsNum`,`orderNum`,`beizhu`,o.`status`,`isCheck`,`isCancel`,o.`cTime`,o.`uTime`,c.name,c.emNum,c.mobile,c.qq,e.empName,p.productName,r.sourceName,i.address")->join("ym_product p ON p.id=o.productId")->join("ym_emp e ON e.id=o.empId")->join("ym_source r ON r.id=o.sourceId")->join("ym_customer c ON c.id=o.customerId")->join("ym_customer_info i ON i.id=c.id")->where($where)->count();

		$Page=paginate($count,$pagesize);

		$data=$this->alias('o')->field("o.`id`,`empId`,`customerId`,o.unusualReason,`productId`,`sourceId`,`isNew`,`collectDate`,`price`,`orderNo`,`goodsNum`,`orderNum`,`beizhu`,o.`status`,`isCheck`,`isCancel`,o.`cTime`,o.`uTime`,c.name,c.emNum,c.mobile,c.qq,e.empName,p.productName,r.sourceName,i.address,oa.chatSource,oa.joinTime,oa.id orderUnusualId")->join("ym_product p ON p.id=o.productId")->join("ym_emp e ON e.id=o.empId")->join("ym_source r ON r.id=o.sourceId")->join("ym_customer c ON c.id=o.customerId")->join("ym_customer_info i ON i.id=c.id")->join("LEFT JOIN ym_order_annual oa ON oa.orderId=o.id")->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		// echo $this->_sql();
		//

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}

}
