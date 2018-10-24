<?php
namespace Sale\Model;
use Think\Model;
class ChatModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('date','require','请输入聊天日期')
	);

	// 自动填充
	protected $_auto=array(
		array('cTime','time',1,'function'),
		array('uTime','time',3,'function')
	);


	protected function _before_insert(&$data,$options){
		if($data=M('Chat')->where("chatDate='".I('post.chatDate')."' AND empId=".session('empId'))->find()){
            $chatId=$data['id'];

            if(I('post.sourceIds')){
                $sourceIds=I('post.sourceIds');
                $orderId=M('Order')->where("orderNo='".trim(I('post.orderNo'))."'")->getField('id');
                M('ChatSource')->where("id =  $sourceIds ")->save(array('chatId'=>$chatId,'empId'=>session('empId'),'orderId'=>$orderId));
            }
            echo json_encode(array('error'=>0,'msg'=>"聊天记录已上传成功！"));exit;
        }

        $data['empId']=session('empId');
        $data['chatDate']=I('post.chatDate');
        $data['cTime']=time();
	}

	protected function _after_insert($data,$options){

		$sourceIds=I('post.sourceIds');

		$orderId=M('Order')->where("orderNo='".trim(I('post.orderNo'))."'")->getField('id');
		M('ChatSource')->where("id = $sourceIds")->save(array('chatId'=>$data['id'],'empId'=>$data['empId'],'orderId'=>$orderId));
	}

	protected function _before_update(&$data,$options){
	}

	protected function _after_delete($data,$options){
	}

	protected function _after_update($data,$option){
		
	}

	public function lists($permission=1,$order="id DESC",$pagesize=10){
		if($permission==1){
			// 能看所有
			$where="1";
		}elseif($permission==2){
			// 员工 只能看自己
			$where="empId=".session('empId');
		}elseif($permission==3){
			// 团队 主管 
			$teamId=M('Team')->where("teamLeader=".session('empId'))->getField('id');
			$where="e.teamId=".$teamId;
		}elseif($permission==4){
			// 总监
			$teamIds=M('Team')->where("teamManager=".session('empId'))->getField('id',true);
			$where="e.teamId IN (".$teamIds.")";
		}

		if(I('get.empName')){
			$where.=" AND e.empName='".I('get.empName')."' OR e.empNo='".I('get.empName')."'";
		}

		if(I('get.startChatDate')) $where.=" AND chatDate>='".I('get.startChatDate')."'";

		if(I('get.endChatDate')) $where.=" AND chatDate<='".I('get.endChatDate')."'";

		$count=$this->alias('c')->join('ym_emp e ON c.empId=e.id')->where($where)->count();
		
		$Page=paginate($count,$pagesize);

		$data=$this->alias('c')->field('c.id,c.chatDate,c.cTime,e.empName')->join('ym_emp e ON c.empId=e.id')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}
	
}