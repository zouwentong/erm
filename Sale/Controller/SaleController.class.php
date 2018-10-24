<?php
namespace Sale\Controller;
use Common\Controller\CommonController;

class SaleController extends CommonController {
    protected $month;

	public function _initialize(){
		parent::_initialize();
        $this->month=I('get.month')?trim(I('get.month')):date('Y-m');
	}

    // 公司月销售
    public function index(){

    	// 公司销售详情
        $this->currentMonth=$this->month;
        $company=M()->query("select sum(orderNum) orderNum,sum(price) orderPrice from ym_order o WHERE isCancel=2 AND isCheck <> 2 AND date_format(collectDate,'%Y-%m')='{$this->month}'");
        // echo M()->_sql();
    	$this->companyOrderNum=$company[0]['orderNum'];
    	$this->companyOrderPrice=$company[0]['orderPrice'];

    	// 营运中心
    	$hz=M()->query("select sum(orderNum) orderNum,sum(price) orderPrice from ym_order o JOIN ym_emp e ON e.id=o.empId JOIN ym_team t ON t.id=e.teamId WHERE t.areaId IN (6) AND isCancel=2 AND isCheck <> 2 AND date_format(collectDate,'%Y-%m')='{$this->month}'");
    	$this->hzOrderNum=$hz[0]['orderNum'];
    	$this->hzOrderPrice=$hz[0]['orderPrice'];

    	// 服务拓展中心
    	$ah=M()->query("select sum(orderNum) orderNum,sum(price) orderPrice from ym_order o JOIN ym_emp e ON e.id=o.empId JOIN ym_team t ON t.id=e.teamId WHERE t.areaId IN (3) AND isCancel=2 AND isCheck <> 2 AND date_format(collectDate,'%Y-%m')='{$this->month}'");
    	$this->ahOrderNum=$ah[0]['orderNum'];
    	$this->ahOrderPrice=$ah[0]['orderPrice'];

    	// 网络事业中心
    	$wx=M()->query("select sum(orderNum) orderNum,sum(price) orderPrice from ym_order o JOIN ym_emp e ON e.id=o.empId JOIN ym_team t ON t.id=e.teamId WHERE t.areaId IN (5) AND isCancel=2 AND isCheck <> 2 AND date_format(collectDate,'%Y-%m')='{$this->month}'");
    	$this->wxOrderNum=$wx[0]['orderNum'];
    	$this->wxOrderPrice=$wx[0]['orderPrice'];

    	// 最后更新时间
    	$this->lastUpdate=M('Order')->order('id DESC')->getField('cTime');

		$this->display();
    }

    public function team(){

        session_write_close();
        // 公司团队销售信息
        $teamList=M()->query("select t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_emp e JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id LEFT JOIN ym_order o ON e.id=o.empId AND date_format(collectDate,'%Y-%m')='{$this->month}' AND isCheck <> 2 where 1  group by teamId order by orderNum DESC,orderPrice DESC ");
        $levelTime=date("Y-m-d",strtotime("+1months",strtotime($this->month)));
        $levelTimes=strtotime($levelTime)+19*3600*24;
        foreach($teamList as &$v){
            $v['empNum']=M('Emp')->where("from_unixtime('enterTime','%Y-%m')<>{$this->month} AND levelTime<{$levelTime} AND teamId=".$v['teamId'])->count();
        }

        $this->teamList=$teamList;
        $this->display();
    }

    public function pull(){
        session_write_close();
        // 上拽个人销售榜 服务拓展中心
        $szp=1;
        $pagesize=10;
        $limit=(($szp-1)*$pagesize.",".$pagesize); 
        $this->szList=M()->query("select e.empName,e.pic headPic,t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_order o JOIN ym_emp e ON o.empId=e.id JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id where date_format(collectDate,'%Y-%m')='{$this->month}' AND t.areaId IN (3) AND isCheck <> 2 group by empId order by orderPrice DESC,orderNum DESC LIMIT $limit");
        $this->display();
    }

    public function anhui(){
        session_write_close();
        // 营运中心个人销售榜
        $ahp=1;
        $pagesize=10;
        $limit=(($ahp-1)*$pagesize.",".$pagesize); 
        $this->ahList=M()->query("select e.empName,e.pic headPic,t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_order o JOIN ym_emp e ON o.empId=e.id JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id where date_format(collectDate,'%Y-%m')='{$this->month}' AND t.areaId IN (6) AND isCheck <> 2 group by empId order by orderPrice DESC,orderNum DESC LIMIT $limit");
        $this->display();
    }

    public function central(){
        session_write_close();
        // 网络事业中心
        $hzp=1;
        $pagesize=10;
        $limit=(($hzp-1)*$pagesize.",".$pagesize);
        $this->hzList=M()->query("select e.empName,e.pic headPic,t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_order o JOIN ym_emp e ON o.empId=e.id JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id where date_format(collectDate,'%Y-%m')='{$this->month}' AND t.areaId IN (5) AND isCheck <> 2 group by empId order by orderPrice DESC,orderNum DESC LIMIT $limit");
        $this->display();
    }

    public function weixin(){
        session_write_close();
    	// 微信个人销售榜                                                                                                                                                    
        $wxp=1;
        $pagesize=10;
        $limit=(($wxp-1)*$pagesize.",".$pagesize);                                                                                                           
        $this->wxList=M()->query("select e.empName,e.pic headPic,t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_order o JOIN ym_emp e ON o.empId=e.id JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id where date_format(collectDate,'%Y-%m')='{$this->month}' AND t.areaId IN (5) AND isCheck <> 2 group by empId order by orderPrice DESC,orderNum DESC LIMIT $limit");
        $this->display();
    }
}