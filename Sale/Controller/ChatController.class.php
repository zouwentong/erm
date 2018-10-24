<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class ChatController extends CommonController {
	public function _initialize(){
		parent::_initialize();
		$this->model=$this->_initModel();
	}

    // 列表
    public function index(){
    	$this->lists();
    	$this->empty='<tr><td colspan="5" class="text-center">没有记录</td></tr>';
    	$this->display();
    }

    // 添加
    public function add(){
        if(IS_POST){

            if(!$order=M('Order')->where("orderNo='".I('post.orderNo')."' AND empId=".session('empId'))->find()){
                echo json_encode(["error"=>2]);exit;
            }

            if(!empty($order)){
                if(M('ChatSource')->where('orderId='.$order['id'])->find()){
                    echo json_encode(["error"=>3]);exit;
                }
            }

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
            if($orderId=I('get.orderId')){
                if(M('ChatSource')->where('orderId='.$orderId)->find()){
                   $this->error("您已经上传过该订单的聊天记录，请不要重复上传");
                }
                $order=M('Order')->alias('o')->field('o.*,c.name,p.productName')->join('ym_customer c ON o.customerId=c.id')->join("ym_product p ON p.id=o.productId")->where('o.id='.$orderId)->find();
                $this->orderNo=$order['orderNo'];
                $this->filename=date('Y年m月d日',strtotime($order['collectDate'])).'-'.$order['name'].'-'.$order['orderNo'].'-'.$order['productName'].'-'.round($order['price']);
                $this->collectDate=$order['collectDate'];
            }
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

    protected function lists(){
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
    }

    public function detail(){
        if(IS_AJAX && IS_POST){
            $id=I('post.id');
            $sourceList=M('ChatSource')->alias('s')->field('s.sourceName,e.empName,c.chatDate')->join('ym_chat c ON c.id=s.chatId')->join('ym_emp e ON e.id=s.empId')->where("s.chatId=".$id)->select();
            echo json_encode($sourceList);
        }
    }

    public function upload(){
        set_time_limit(0);

        if($_FILES['Filedata']){
            if(!preg_match('/\d{4}年\d{2}月\d{2}日-.*-\w+-.*-\d+\.(mht|doc|docx|rar|zip)$/',$_FILES['Filedata']['name'])){
                echo json_encode(array('error'=>'系统检测到文件名不合法，请核对'));exit;
            }
        }

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 104857600 ;// 设置附件上传大小
        $upload->exts = array('mht','doc','docx','rar','zip');// 设置附件上传类型
        $upload->rootPath = './data/uploads/'; // 设置附件上传根目录
        $upload->savePath = 'chat_source/'.date('Y-m-d').'/'; // 设置附件上传（子）目录
        $upload->autoSub = false;


        // 上传文件
        $info = $upload->upload(array('pic'=>$_FILES['Filedata']));
        if(!$info) {// 上传错误提示错误信息
            echo json_encode(array('error'=>$upload->getError()));
        }else{// 上传成功
            foreach($info as $file){
                // $content='';
                // if($file['ext']=='mht'){
                //     // $data=file_get_contents('./data/uploads/'.$file['savepath'].$file['savename']);
                //     // $data=substr($data,strpos($data,'<html xmlns='),strpos($data,'</body></html>'));
                //     // $content=strip_tags($data);
                // }

                $sourceId=M('ChatSource')->add(array(
                    'sourceName'=>$_FILES['Filedata']['name'],
                    'src'=>'/data/uploads/'.$file['savepath'].$file['savename'],
                    'cTime'=>time(),
                    'uTime'=>time()
                ));

                echo json_encode(array('error'=>0,'sourceId'=>$sourceId,'path'=>$_FILES['Filedata']['name']));
            }
        }
    }

}