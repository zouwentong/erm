<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
use Sale\Model\ProductModel;
class ProductController extends CommonController {
	public function _initialize(){
		parent::_initialize();
		$this->model=$this->_initModel();
	}

    // 列表
    public function index(){
    	$map=[];
    	$this->lists($map);
        $this->products=ProductModel::recursionChildren($this->model->where('id <> 67')->order('rank asc')->select());
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
            $this->productList=M('Product')->field('id,productName')->where('pid=0 AND id<>67')->order('rank asc')->select();
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
        	$this->assign($this->model->find(I('get.id',0)));
            $this->productList=M('Product')->field('id,productName')->where('pid=0 AND id<>67')->order('rank asc')->select();
        	$this->display();
        }
    }

    // 删除
	public function delete(){
        if(IS_POST){
            if($this->model->where('pid='.I('post.id',0))->find()){
                echo json_encode(["error"=>-1,'msg'=>'该产品含有子产品，不能删除']);
                exit;
            }

            if($this->model->where('id='.I('post.id',0))->setField('isDelete',1) !== FALSE){
        		echo json_encode(["error"=>0]);
        	}else{
        		echo json_encode(["error"=>1]);
        	}
        }
    }

    public function setFolder(){
        if(IS_POST){
            $id=I('post.id');
            $isFolder=I('post.isFolder');
            M('Product')->where('id='.$id)->setField('isFolder',$isFolder);
            echo '';exit;
        }
    }

    protected function lists($where=[],$order="id DESC"){
        $data=$this->model->lists($where,$order);
        $this->lists=$data['data'];
        $this->page=$data['pageStr'];
    }

    public function getChildren(){
        if(IS_AJAX && IS_POST){
            $id=I('post.id');
            $products=M('Product')->field('id,productName')->where('pid='.$id)->order('rank asc')->select();
            if(empty($products)){
                echo json_encode(['error'=>1]);
            }else{
                echo json_encode(['error'=>0,'products'=>$products]);
            }
        }
    }
}