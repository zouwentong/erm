<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class TeamAreaController extends CommonController {
	public function _initialize(){
		parent::_initialize();
		$this->model=$this->_initModel();
	}

    // 列表
    public function index(){
    	$map=[];
    	I('get.areaName') && $map['areaName']=array("like","%".I('get.areaName')."%");
    	$this->lists($map);
    	$this->empty='<tr><td colspan="4" class="text-center">没有记录</td></tr>';
    	$this->display();
    }

    // 添加
    public function add(){
        if(IS_POST){
        	if($this->model->create()){
        		if($this->model->add()!==FALSE){
        			echo json_encode(["error"=>0,"data"=>array_merge(I('post.'),['id'=>$id])]);
        		}else{
        			echo json_encode(["error"=>1]);
        		}
        	}else{
        		echo json_encode(["error"=>-1,"msg"=>$this->model->getError()]);
        	}
        }else{
        	$this->display();
        }
    }

    // 编辑
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
        }else{
        	$this->assign($this->model->alias('a')->field('a.id,a.areaName,e.empName areaLeader')->join('LEFT JOIN '.C('DB_PREFIX').'emp e ON a.areaLeader=e.id')->where('a.id='.I('get.id',0))->find());
        	$this->display();
        }
    }

    // 删除
	public function delete(){
        if(IS_POST){
        	if($this->model->delete(I('post.id',0)) !== FALSE){
        		echo json_encode(["error"=>0]);
        	}else{
        		echo json_encode(["error"=>1]);
        	}
        }
    }

    protected function lists($where=[],$order="id DESC"){
        $data=$this->model->lists($where,$order);
        $this->lists=$data['data'];
        $this->page=$data['pageStr'];
    }


}