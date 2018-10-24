<?php
namespace Sale\Model;
use Think\Model;
class ChatSourceModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('sourceName','require','请输入资源名称')
	);

	// 自动填充
	protected $_auto=array(
		array('cTime','time',1,'function'),
		array('uTime','time',3,'function')
	);


	protected function _before_insert(&$data,$options){
	}

	protected function _after_insert($data,$options){
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
			$where="c.empId=".session('empId');
		}elseif($permission==3){
			// 团队 主管 
			$teamId=M('Team')->where("teamLeader=".session('empId'))->getField('id');
			$where="e.teamId=".$teamId;
		}elseif($permission==4){
			// 总监
			$teamIds=M('Team')->where("teamManager=".session('empId'))->getField('id',true);
			$where="e.teamId IN (".$teamIds.")";
		}

		if(I('get.startChatDate')) $where.=" AND chatDate>='".I('get.startChatDate')."'";

		if(I('get.endChatDate')) $where.=" AND chatDate<='".I('get.endChatDate')."'";

		if(I('get.empName')){
			$where.=" AND e.empName='".I('get.empName')."' OR e.empNo='".I('get.empName')."'";
		}

		if(I('get.sourceName')){
			$where.=" AND s.sourceName LIKE '%".I('get.sourceName')."%'";
		}

		$count=$this->alias('s')->join('ym_chat c ON c.id=s.chatId')->join('ym_emp e ON s.empId=e.id')->where($where)->count();
		
		$Page=paginate($count,$pagesize);

		$data=$this->alias('s')->field('s.id,c.chatDate,s.cTime,e.empName,s.sourceName,s.beizhu2')->join('ym_chat c ON c.id=s.chatId')->join('ym_emp e ON s.empId=e.id')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		// echo $this->_sql();

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}
	
}