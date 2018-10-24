<?php
namespace Sale\Model;
use Think\Model;
class TeamAreaModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('areaName','require','请输入区域')
	);

	// 自动填充
	protected $_auto=array(
		array('cTime','time',1,'function'),
		array('uTime','time',3,'function')
	);

	protected $_link=array(
	);

	protected function _before_insert(&$data,$options){
		$data['areaLeader']=M('Emp')->where("empName='".I('post.areaLeader')."'")->getField('id');
	}

	protected function _after_insert($data,$options){
	}

	protected function _before_update(&$data,$options){
		$data['areaLeader']=M('Emp')->where("empName='".I('post.areaLeader')."'")->getField('id');
	}

	protected function _after_delete($data,$options){
	}


	protected function _after_update($data,$option){
		
	}

	public function lists($where=[],$order="rank ASC,id DESC",$pagesize=15){

		$count=$this->where($where)->count();
		
		$Page=paginate($count,$pagesize);

		$data=$this->field('a.id,a.areaName,e.empName leader')->alias('a')->join('LEFT JOIN '.$this->tablePrefix.'emp e ON a.areaLeader=e.id')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}
	
}