<?php
namespace Sale\Model;
use Think\Model;
class TeamModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('teamName','require','请输入团队名称')
	);

	// 自动填充
	protected $_auto=array(
		array('cTime','time',1,'function'),
		array('uTime','time',3,'function')
	);

	protected function _before_insert(&$data,$options){
		$data['teamLeader']=M('Emp')->where("empName='".I('post.teamLeader')."'")->getField('id');
		$data['teamManager']=M('Emp')->where("empName='".I('post.teamManager')."'")->getField('id');
	}

	protected function _after_insert($data,$options){
	}

	protected function _before_update(&$data,$options){
		$data['teamLeader']=M('Emp')->where("empName='".I('post.teamLeader')."'")->getField('id');
		$data['teamManager']=M('Emp')->where("empName='".I('post.teamManager')."'")->getField('id');

		if(I('post.pic')==''){
			unset($data['pic']);
		}
	}

	protected function _after_delete($data,$options){
	}


	protected function _after_update($data,$option){
		
	}

	public function lists($where=[],$order="id DESC",$pagesize=15){

		$count=$this->where($where)->count();
		
		$Page=paginate($count,$pagesize);

		$data=$this->field("t.id,t.teamName,t.teamDesc,e.empName leader,f.empName manager")->alias('t')->join($this->tablePrefix."emp e ON e.id=t.teamLeader")->join($this->tablePrefix."emp f ON f.id=t.teamManager")->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}

	
}