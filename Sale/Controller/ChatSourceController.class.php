<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
use Org\Net\Http;

class ChatSourceController extends CommonController {
	public function _initialize(){
		parent::_initialize();
		$this->model=$this->_initModel();
	}

    // 列表
    public function index(){
        $auth=new \Think\Auth;
        if($auth->check('chatAllPermission',session('empId')) || session('empId')==C('CFG_STATIC_MANAGER_ID') ){
            $permission=1;
        }elseif(M('Team')->where("teamLeader=".session('empId'))->find()){
            // 主管
            $permission=3;
        }elseif(M('Team')->where("teamManager=".session('empId'))->find()){
            // 总监
            $permission=4;
        }else{
            // 员工
            $permission=2;
        }
    	$data=$this->model->lists($permission);
        $this->lists=$data['data'];
        $this->page=$data['pageStr'];
    	$this->empty='<tr><td colspan="5" class="text-center">没有记录</td></tr>';
    	$this->display();
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
        	$this->display();
        }
    }

    // 删除
	public function delete(){
        if(IS_POST){
            $source=$this->model->where("id=".I('post.id'))->getField('src');
        	if($this->model->delete(I('post.id',0)) !== FALSE){
                @unlink('.'.$source);
                actionLog('删除聊天记录','ChatSource',I('post.id'));
        		echo json_encode(["error"=>0]);
        	}else{
        		echo json_encode(["error"=>1]);
        	}
        }
    }

    public function download(){
        $id=I('get.id')+0;
        $source=M('ChatSource')->find($id);
        $download=new Http();
        actionLog('下载聊天记录','ChatSource',$id);
        $download->download('.'.$source['src'],$source['sourceName']);
    }

    public function beizhu(){
        if(I('get.type')==1){
            $chatSourceId=I('post.chatSourceId');
            echo json_encode(['error'=>0,'beizhu'=>M('ChatSource')->where('id='.$chatSourceId)->getField('beizhu2')]);
            exit;
        }

        $chatSourceId=I('post.chatSourceId');
        M('ChatSource')->where('id='.$chatSourceId)->setField('beizhu2',I('post.beizhu'));
        echo json_encode(['error'=>0]);
    }

}