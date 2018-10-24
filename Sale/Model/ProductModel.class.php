<?php
namespace Sale\Model;
use Think\Model;
class ProductModel extends Model{
	protected $tablePrefix='ym_';

	protected $_validate=array(
		array('productName','require','请输入来源')
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

	// 递归处理栏目
    public static function recursion($product,$html='├──',$pid=0,$level=0){
    	$data=[];
    	foreach($product as $k=>$v){
    		if($v['pid']==$pid){
    			$v['html']=str_repeat($html, $level);
                $v['level']=$level+1;
    			$data[]=$v;
    			unset($product[$k]);
    			$data=array_merge($data,self::recursion($product,$html,$v['id'],$level+1));
    		}
    	}
    	return $data;
    }

    // 递归处理栏目 子栏目放入child
    public static function recursionChildren($product,$id=0){
        $data=array();
        foreach($product as $k=>$v){
            if($v['pid']==$id){
                $v['child']=self::recursionChildren($product,$v['id']);
                $data[]=$v;
            }
        }
        return $data;
    }

    //获得递归处理后的数据
    public function getRecursionProducts($where='id <> 67 AND isDelete=0'){
        $data=$this->where($where)->order('rank')->select();
        if($data)
            $data=self::recursion($data);
        return $data;
    }

	public function lists($where=[],$order="rank ASC,id DESC",$pagesize=15){

		$count=$this->where($where)->count();
		
		$Page=paginate($count,$pagesize);

		$data=$this->field(true)->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		return array('pageStr'=>$Page->show(),'data'=>$data);
	}
	
}