<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class TeamController extends CommonController {
    public function _initialize(){
        parent::_initialize();
        $this->model=$this->_initModel();
    }

    // 列表
    public function index(){
        $map=[];
        I('get.teamName') && $map['teamName']=array("like","%".I('get.teamName')."%");
        $this->lists($map);
        $this->empty='<tr><td colspan="5" class="text-center">没有记录</td></tr>';
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
            $this->teamArea=M('TeamArea')->select();
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
            $this->assign($this->model->field("t.id,t.pic,t.teamName,t.teamDesc,t.areaId,e.empName leader,f.empName manager")->alias('t')->join(C('DB_PREFIX')."emp e ON e.id=t.teamLeader")->join(C('DB_PREFIX')."emp f ON f.id=t.teamManager")->where('t.id='.I('get.id',0))->find());
            $this->teamArea=M('TeamArea')->select();
            $this->display();
        }
    }

    // 删除
    public function delete(){
        if(IS_POST){
            $pic=$this->model->where('id='.I('post.id',0))->getField('pic');
            @unlink($pic);
            if($this->model->delete(I('post.id',0)) !== FALSE){
                M('Emp')->where('teamId='.I('post.id'))->setField('teamId',0);
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

    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 10485760 ;// 设置附件上传大小
        $upload->exts = array('jpg','png','gif');// 设置附件上传类型
        $upload->rootPath = './data/uploads/'; // 设置附件上传根目录
        $upload->savePath = 'team/'; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload(array('pic'=>$_FILES['Filedata']));
        if(!$info) {// 上传错误提示错误信息
            echo json_encode(array('error'=>$upload->getError()));
        }else{// 上传成功
            echo json_encode(array('error'=>0,'path'=>'/data/uploads/'.$info['pic']['savepath'].$info['pic']['savename']));
        }
    }

    public function checkEmp(){
        if(IS_AJAX){
            $fieldId=I('get.fieldId');
            $fieldValue=I('get.fieldValue');
            if(M('Emp')->where("empName='$fieldValue'")->getField('id')){
                echo json_encode([$fieldId,true]);
            }else{
                echo json_encode([$fieldId,false]);
            }
        }
    }

    public  function searchEmp(){
        if(IS_AJAX){
            $k=I('post.k','');
            $emp=M('Emp')->field('id,empName')->where("empName LIKE '$k%' OR empNo LIKE '$k%'")->limit(8)->select();
            if(empty($emp)){
                echo json_encode(['error'=>-1]);
            }else{
                echo json_encode(['error'=>0,'data'=>$emp]);
            }
        }
    }

    public function getTeamMember(){
        if(IS_AJAX && IS_POST){
            $teamId=I('post.teamId');
            $where=" teamId=$teamId ";
            $emp=M('Emp')->field('id,empName,status,teamId,deptId')->where($where)->select();
            $emp2=M("EmpTeamDept")->alias('d')->field('d.empId,d.teamId,e.status,e.empName,e.id')->where("d.teamId="
                .$teamId)->join('left join ym_emp e on e.id=d.empId')->select();
            $emp3=array_merge($emp,$emp2);
            // echo "<pre>";
            // print_r($emp3);
            foreach($emp3 as $v){
                $emp4[]=$v;
            }



            foreach($emp3 as $k=>$v){
                foreach($emp2 as $k2=>$v2){
                    if($v['id']==$v2['empId']){
                        $emp3[$k]['isPost']=2;//黄色
                    }else{
                        $emp3[$k]['isPost']=1;//红色
                    }
                }
//                if($v['id']==)
//                $newteamId=M("EmpTeamDept")->where("empId=".$v['id'])->order('cTime desc')->getField('teamId');
//                $oldteamId=M("Emp")->where("id=".$v['id'])->getField('teamId');
//                if($newteamId==$oldteamId){
//                    $emp3[$k]['isPost']=1;//红色
//                }else{
//                    $emp3[$k]['isPost']=2;//黄色
//                }
            }

            if(empty($emp3)){
                echo json_encode(['error'=>-1]);
            }else{
                echo json_encode(['error'=>0,'data'=>$emp3]);
            }

        }
    }

    public function delTeamMember(){
        if(IS_AJAX && IS_POST){
            $empId=I('post.empId');
            M('Emp')->where('id='.$empId)->setField('teamId',0);
            echo json_encode(['error'=>0]);
        }
    }

    public function addTeamMember(){
        if(IS_AJAX && IS_POST){
            $empId=I('post.empId');
            $teamId=I('post.teamId');
            if($teamId==M('Emp')->where('id='.$empId)->getField('teamId')){
                 echo json_encode(['error'=>-1]);exit;
            }
            if(!M("EmpTeamDept")->where('id='.$empId)->find()){//第一次转移
                $obj=M("Emp")->where('id='.$empId)->find();
                $data['empId']=$obj['id'];
                $data['empNo']=$obj['empNo'];
                $data['teamId']=$obj['teamId'];;//原来团队
                $data['deptId']=$obj['deptId'];
                $data['cTime']=time();
                M("EmpTeamDept")->add($data);
            }
            $obj=M("Emp")->where('id='.$empId)->find();
            $data['empId']=$obj['id'];
            $data['empNo']=$obj['empNo'];
            $data['teamId']=$teamId;
            $data['deptId']=0;
            $data['cTime']=time()+5;
            M("EmpTeamDept")->add($data);
            M('Emp')->where('id='.$empId)->setField('teamId',$teamId);
            echo json_encode(['error'=>0]);

        }
    }
}
