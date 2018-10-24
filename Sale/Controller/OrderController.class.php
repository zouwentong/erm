<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
use Org\Net\Http;
class OrderController extends CommonController {
	public function _initialize(){
		parent::_initialize();
		$this->model=$this->_initModel();
	}

    // 订单列表
    public function index(){
        $auth=new \Think\Auth;
        if($auth->check('orderAllPermission',session('empId')) || session('empId')==C('CFG_STATIC_MANAGER_ID') || session('empId')==32  ){
            $permission=1;

            $this->assign('deptList', getAllDept());
        }elseif(M('Team')->where("teamManager=".session('empId'))->find()){
            // 总监
            $permission=4;
        }elseif(M('Team')->where("teamLeader=".session('empId'))->find()){
            // 主管
            $permission=3;
        }else{
            // 员工
            $permission=2;
        }
         //dump($permission);
    	$data=$this->model->lists('id DESC',$permission);
        $this->lists=$data['data'];
        $this->page=$data['pageStr'];
        $this->firstProduct=M('Product')->field('id,productName')->where("pid=0")->select();
        $this->sourceList=M('Source')->field("id,sourceName")->where('isShow=1')->order("rank ASC,id DESC")->select();
        $this->teacherList=M('Teacher')->field("id,teacherName")->where('isShow=1')->order("rank ASC,id DESC")->select();
    	$this->empty='<tr><td colspan="10" class="text-center">没有记录</td></tr>';

        if($orderby=I('get.orderby')){
            $this->orderby=$orderby;
        }else{
            $this->orderby='collectDate';
        }
        if($orderway=I('get.orderway')){
            if($orderway=='asc'){
                $this->orderway='desc';
                $this->icon='<i class="fa fa-angle-up"></i>';
            }else{
                $this->orderway='asc';
                $this->icon='<i class="fa fa-angle-down"></i>';
            }
        }else{
            $this->orderway='asc';
            $this->icon='<i class="fa fa-angle-down"></i>';
        }

        // dump($data);

        $this->permission=$permission;

        if(!empty(S('orderData'.session('empId')))){
            foreach(S('orderData'.session('empId')) as $v){
                $orderNum+=$v['orderNum'];
                $orderPrice+=$v['price'];
            }

            $this->orderNum1=count(S('orderData'.session('empId')))-$orderNum;

            $this->orderNum=$orderNum;
            $this->orderPrice=$orderPrice;
        }


    	$this->display();
    }

    // 添加订单
    public function addNew(){
        if(IS_POST){
            // if(empty(I('post.customerId'))){
            //     $qq=trim(I('post.qq'));

            //     if(!M('Customer')->where("qq='$qq' OR qqEmail='$qq'")->find()){
            //         echo json_encode(['error'=>-2]);exit;
            //     }

            //     if(I('post.price')<2000 && I('post.is_new')==1){
            //         echo json_encode(['error'=>-3]);exit;
            //     }
            // }
            $empId=I("post.empId");
            $res=M("EmpTeamDept")->where("empId=".$empId)->order('cTime desc')->find();
            if($res){
                $teamId=$res['teamId'];
                $deptId=$res['deptId'];
            }else{
                $teamId=M("Emp")->where("id=".$empId)->getField('teamId');
                $deptId=M("Emp")->where("id=".$empId)->getField('deptId');
            }

        	if($this->model->create()){
                $this->model->teamId=$teamId;
                $this->model->deptId=$deptId;
        		if($this->model->add()!==FALSE){
                    S('saleData',null);
        			echo json_encode(["error"=>0]);
        		}else{
        			echo json_encode(["error"=>1]);
        		}
        	}else{
        		echo json_encode(["error"=>-1,"msg"=>$this->model->getError()]);
        	}
        }else{
            $this->firstProduct=M('Product')->field('id,productName')->where("pid=0 AND id <> 67")->select();
            $this->teacherList=M('Teacher')->field("id,teacherName")->where('isShow=1')->order("rank ASC,id DESC")->select();
            $this->sourceList=M('Source')->field("id,sourceName")->where('isShow=1')->order("rank ASC,id DESC")->select();

            // 常用老师
            $this->usingTeacher=M('Order')->alias('o')->field('DISTINCT(teacherId),teacherName')->join('ym_teacher t ON o.teacherId=t.id')->where('empId='.session('empId'))->order('o.id DESC')->limit(4)->select();

            // 常用来源
            $this->usingSource=M('Order')->alias('o')->field('DISTINCT(sourceId),sourceName')->join('ym_source s ON o.sourceId=s.id')->where('empId='.session('empId'))->order('o.id DESC')->limit(4)->select();

            // 常用产品
            $this->usingProduct=M('Order')->alias('o')->field('DISTINCT(productId),productName,pid')->join('ym_product p ON o.productId=p.id')->where('empId='.session('empId'))->order('o.id DESC')->limit(4)->select();

            if($customerId=I('get.customerId')){
                $this->customer=M('Customer')->alias('c')->join("ym_customer_info i ON i.id=c.id")->where('c.id='.$customerId)->find();
            }
        	$this->display('add');
        }
    }

    // 编辑订单
 	public function edit(){
        if(IS_POST){
        	if($this->model->create()){
        		if($this->model->save()!==FALSE){
        			echo json_encode(["error"=>0,"data"=>I('post.')]);
        		}else{
        			echo json_encode(["error"=>1]);
        		}
        	}else{
        		echo json_encode(["error"=>-1,"msg"=>$this->model->getError()]);
        	}
            $orderId=I("post.id");
            $sourceName=M("ChatSource")->where("orderId=".$orderId)->getField("sourceName");
            $order=M('Order')->alias('o')->field('o.*,c.name,p.productName')->join('ym_customer c ON o.customerId=c.id')->join("ym_product p ON p.id=o.productId")->where('o.id='.$orderId)->find();
            $filename=date('Y年m月d日',strtotime($order['collectDate'])).'-'.$order['name'].'-'.$order['orderNo'].'-' .$order['productName'].'-'.round($order['price']).'.'.'mht';
            if($filename!=$sourceName){
                $data['sourceName']=$filename;
                M("ChatSource")->where("orderId=".$orderId)->save($data);
            }

        }else{
            $order=$this->model->find(I('get.id',0));

            // dump($order);

        	$this->assign($order);
            $this->sourceList=M('Source')->field('id,sourceName')->where("isShow=1")->order("rank ASC,id DESC")->select();
            $this->firstProduct=M('Product')->field('id,productName')->where("pid=0")->select();
            $this->firstProductId=M('Product')->where("id=".$order['productId'])->getField('pid');
            $this->teacherList=M('Teacher')->field("id,teacherName")->where('isShow=1')->order("rank ASC,id DESC")->select();
        	$this->display();
        }
    }

    // 删除订单
	public function delete(){
        if(IS_POST){
        	if($this->model->delete(I('post.id')) !== FALSE){
        		echo json_encode(["error"=>0]);
        	}else{
        		echo json_encode(["error"=>1]);
        	}
        }
    }

    // 退单订单
    public function cancel(){
        if(IS_POST){
            if($this->model->where("id=".I('post.id'))->setField('isCancel',1) !== FALSE){
                echo json_encode(["error"=>0]);
            }else{
                echo json_encode(["error"=>1]);
            }
        }
    }

    // 异常单
    public function unusual(){
        // $auth=new \Think\Auth;
        // if($auth->check('unusualCheckPermission',session('empId')) || session('empId')==C('CFG_STATIC_MANAGER_ID') ){
        //     $permission=1;
        // }else{
        //     $permission=2;
        // }
        //
        $type=I('get.type')?:2;

        if($type==3){
            $customerId=I('post.customerId');
            $ec=M('EmpCustomer')->alias('ec')->field('e.empNo,e.empName,ec.qqQun,ec.gTime,ec.source')->join('ym_emp e ON ec.empId=e.id')->where('ec.customerId='.$customerId.' AND ec.fileName <> "" AND e.deptId NOT IN (26,30,31,32,33)')->select();
            $html='';
            foreach($ec as $v){
                $html.="<p>员工:{$v['empName']} qq群:{$v['qqQun']} 申请时间:{$v['gTime']} 端口:{$v['source']}</p>";
            }

            echo $html;exit;
        }

        $data=$this->model->unusual('id DESC',$type);
        $this->lists=$data['data'];
        $this->page=$data['pageStr'];
        $this->permission=$permission;
        $this->empty='<tr><td colspan="11" class="text-center">没有记录</td></tr>';
        $this->type=$type;
        $this->display();
    }

    public function pass(){
        if(IS_AJAX && IS_POST){
            $orderId=I('post.orderId');

            $order=M('Order')->where('id='.$orderId)->find();


            $source=M('Source')->where('id='.$order['sourceId'])->getField('sourceName');
            M('Customer')->where('id='.$order['customerId'])->setField('source',$source);

            M('Order')->where('id='.$orderId)->setField('isCheck',1);

            actionLog('订单审核通过','Order',$orderId);

            M('Msg')->add([
                'typeId'=>1,
                'content'=>'恭喜，您的异常订单（订单编号：'.$order['orderNo'].'）已经通过审核，请尽快上传聊天记录',
                'status'=>0,
                'cTime'=>time(),
                'uTime'=>time(),
                'sender'=>session('empNo'),
                'reader'=>M('Emp')->where('id='.$order['empId'])->getField('empNo')
            ]);
            echo json_encode(['error'=>0]);
        }
    }

    public function unpass(){
        if(IS_AJAX && IS_POST){
            $orderId=I('post.orderId');
            M('Order')->where('id='.$orderId)->setField('isCheck',3);
            actionLog('订单审核不通过','Order',$orderId);
            echo json_encode(['error'=>0]);
        }
    }

    public function checkOrdersn(){
        if(IS_AJAX){
            $fieldId=I('get.fieldId');
            $fieldValue=I('get.fieldValue');
            if(M('Order')->where("orderNo='$fieldValue'")->getField('id')){
                echo json_encode([$fieldId,false]);
            }else{
                echo json_encode([$fieldId,true]);
            }
        }
    }

    public function export(){
        @ini_set('memory_limit','512M');
        $orderIds=S('orderData'.session('empId'));
        foreach($orderIds as $v){
            $ids[]=$v['id'];
        }
        $where['o.id']=array('in',$ids);
        $list=M('Order')->alias('o')->field("o.`id`,`empId`,`customerId`,`productId`,`sourceId`,`isNew`,`collectDate`,`price`,`orderNo`,`goodsNum`,`orderNum`,`beizhu`,o.`status`,`isCheck`,`isCancel`,o.`cTime`,o.`uTime`,c.name,c.emNum,c.mobile,c.qq,e.empName,p.productName,r.sourceName,i.address")->join("ym_product p ON p.id=o.productId")->join("ym_emp e ON e.id=o.empId")->join("ym_source r ON r.id=o.sourceId")->join("ym_customer c ON c.id=o.customerId")->join("ym_customer_info i ON i.id=c.id")->where($where)->order('o.id DESC')->select();
        //var_dump($list);die;
        foreach($list as $k=>$v){
            if($v['isNew']!=1){
                $arr=M('Order')->where('customerId='.$v['customerId'])->order('id ASC')->find();
                $emp=M('Emp')->where('id='.$arr['empId'])->getField('empName');
                $list[$k]['firstCollect']=$arr['collectDate'].'('.$emp.')';
            }else{
                $list[$k]['firstCollect']='-';
            }
            //$arrs=M('Order')->where('customerId='.$v['customerId'])->order('id ASC')->find();
           $v['empId'] && $teamId=M('Emp')->where('id='.$v['empId'])->getField('teamId');
           $teamId && $teamLeaderId=M('Team')->where('id='.$teamId)->getField('teamLeader');
           $teamId && $teamManagerId=M('Team')->where('id='.$teamId)->getField('teamManager');
           $teamLeaderId && $teamLeaderName=M('Emp')->where('id='.$teamLeaderId)->getField('empName');
           $teamLeaderId && $teamManagerName=M('Emp')->where('id='.$teamManagerId)->getField('empName');
            $list[$k]['teamLeaderName']=$teamLeaderName;
            $list[$k]['teamManagerName']=$teamManagerName;
            $count=M("Order")->where("customerId=".$v['customerId'])->count();
            $list[$k]['count']=$count;
        }
        //var_dump($list);die;
        $auth=new \Think\Auth;
        if($auth->check('qqAndMobileNotEncypt',session('empId')) || session('empId')==C('CFG_STATIC_MANAGER_ID') ){
            $is_admin=1;
        }else{
            $is_admin=0;
        }

        actionLog('订单导出','Order',0);

        exportMonthExcel($list,'',0,0,$is_admin);
    }

    public function orderUnusual(){
        if(IS_AJAX){
            $orderId=I('post.orderId');
            if(M('OrderAnnual')->where('orderId='.$orderId)->find()){
                echo json_encode(['error'=>1]);exit;
            }else{
                echo json_encode(['error'=>0]);exit;
            }
        }

        if(IS_POST){
            $joinTime=strtotime(I('post.joinTime'));
            $orderId=I('post.orderId');
            if($_FILES['chatSource']['tmp_name']){
                $info=$this->upload(array('chatSource'=>$_FILES['chatSource']));
                if ($info){
                    $chatSource='./data/uploads/'.$info['chatSource']['savepath'].$info['chatSource']['savename'];
                    M('OrderAnnual')->add([
                        'orderId'=>$orderId,
                        'joinTime'=>$joinTime,
                        'chatSource'=>$chatSource
                    ]);
                }else {
                    $this->error('上传文件格式错误！');
                }

            }
            $this->redirect('index');
        }
    }

    public function chatSourceUpload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 10485760 ;// 设置附件上传大小
        $upload->exts = array('mht','jpg','png','gif');// 设置附件上传类型
        $upload->rootPath = './data/uploads/'; // 设置附件上传根目录
        $upload->savePath = 'annual/'; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload(array('pic'=>$_FILES['Filedata']));
        if(!$info) {// 上传错误提示错误信息
            echo json_encode(array('error'=>$upload->getError()));
        }else{// 上传成功
            echo json_encode(array('error'=>0,'path'=>'./data/uploads/'.$info['pic']['savepath'].$info['pic']['savename']));
        }
    }

    protected function upload($file){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 104857600 ;// 设置附件上传大小
        $upload->exts = array('mht');// 设置附件上传类型
        $upload->rootPath = './data/uploads/'; // 设置附件上传根目录
        $upload->savePath = 'annual/'; // 设置excel上传（子）目录
        // 上传文件
        $info = $upload->upload($file);
        return $info;
    }

    public function mht(){
        $id=I('get.id');
        $chatSource=M('OrderAnnual')->where('id='.$id)->getField('chatSource');
        $download=new Http();
        $download->download($chatSource);
    }

    public function checkUnusual(){
        $isNew=I('post.isNew');

        if(session('deptId')==26){
            echo json_encode(['error'=>0]);exit;
        }

        if(in_array(session('deptId'), [30,31,32,33])){
            // 上拽录新增 异常
            if(I('post.isNew')==1){
                echo json_encode(['error'=>1]);exit;
            }
        }else{
            if(I('post.isNew')==5 || I('post.isNew')==6){
                echo json_encode(['error'=>1]);exit;
            }
        }

        echo json_encode(['error'=>0]);exit;
    }

}
