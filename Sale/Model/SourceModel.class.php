<?php
namespace Sale\Model;
use Think\Model;
class SourceModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('sourceName','require','请输入来源')
	);

	// 自动填充
	protected $_auto=array(
		array('cTime','time',1,'function'),
		array('uTime','time',3,'function')
	);

	protected $_link=array(
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

	public function lists($where=[],$order="rank ASC,id DESC",$pagesize=15){

		$count=$this->where($where)->count();
		
		$Page=paginate($count,$pagesize);

		$data=$this->field(true)->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}
	
}