<?php

namespace Sale\Controller;
use Common\Controller\CommonController;
class StatisController extends CommonController
{

    public function index()
    {
        //团队销售额 单数 开发率 导流率
        $month1=I('get.month1')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,1,date('Y',strtotime(date('Y-m-d')))));
        $month2=I('get.month2')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,date('d',strtotime(date('Y-m-d'))),date('Y',strtotime(date('Y-m-d')))));
        S('exportMonth1',$month1);
        S('exportMonth2',$month2);
        $month3=I('get.month3')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d'))),1,date('Y',strtotime(date('Y-m-d')))));
        $month4=I('get.month4')?:date('Y-m-d');
        $month5=I('get.month5')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,1,date('Y',strtotime
        (date('Y-m-d')))));
        $month6=I('get.month6')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,date('d',strtotime
        (date('Y-m-d'))),date('Y',strtotime(date('Y-m-d')))));
        $month7=I('get.month7')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d'))),1,date('Y',strtotime
        (date('Y-m-d')))));
        $month8=I('get.month8')?:date('Y-m-d');
        if(empty(I('get.teamId'))){
            $_GET['teamId']=7;
        }
        $teamId=I('get.teamId')?:7;
        S('exportTeamId',$teamId);
        if(empty(I('get.abc'))){
            $_GET['abc']=1;
        }
        $abc=I('get.abc')?:1;

        if($abc==1){//单数
            $where="1";
            $sql="SELECT e.id,e.empName,
                sum(collectDate BETWEEN '$month1' AND '$month2') month1OrderTotalNum,
                sum(CASE WHEN collectDate BETWEEN '$month1' AND '$month2' AND isNew=1 THEN 1 ELSE 0 END) month1OrderNum,
                sum((collectDate BETWEEN '$month1' AND '$month2')*price) month1OrderPrice,
                sum(collectDate BETWEEN '$month3' AND '$month4') month2OrderTotalNum,
                sum(CASE WHEN collectDate BETWEEN '$month3' AND '$month4' AND isNew=1 THEN 1 ELSE 0 END) month2OrderNum,
                sum((collectDate BETWEEN '$month3' AND '$month4')*price) month2OrderPrice
                FROM
                ym_emp e
                LEFT JOIN ym_order o
                ON e.id=o.empId AND collectDate BETWEEN '$month1' AND '$month4'
                WHERE $where AND e.teamId=$teamId
                group by e.id order by month2OrderNum DESC";

        $data=M()->query($sql);

        $categories=array();
        $month1OrderTotalNum=array();
        $month2OrderTotalNum=array();
        print_r($data);
        foreach($data as $k=>$v){
            $categories[]=$v['empName'];
            $month1OrderTotalNum[]=intval($v['month1OrderTotalNum']);
            $month2OrderTotalNum[]=intval($v['month2OrderTotalNum']);
        }

        $optioned=array(
                'title'=>array('text'=>'团队每月销售单数统计'),
                'subtitle'=>array('text'=>'由技术部提供技术支持'),
                'credits'=>array('enabled'=>false),
                'xAxis'=>array('categories'=>$categories),
                'yAxis'=>array('min'=>0, 'title'=>array('text'=>'单数(个)'),'plotLines'=>array(array('value'=>0,'width'=>1,'color'=>'#808080'))),
                'tooltip'=>array('valueSuffix'=>'单'),
                'legend'=>array('layout'=>'vertical','align'=>'right','verticalAlign'=>'middle','borderWidth'=>0),
                 'series'=>array(
                     array('name'=>$month1.'至'.$month2,'data'=>$month1OrderTotalNum),
                     array('name'=>$month3.'至'.$month4,'data'=>$month2OrderTotalNum),
                     )
            );

         //print_r(json_encode($optioned));
          $this->option=json_encode($optioned);
        }elseif($abc==2){//金额
            $where="1";
            $sql="SELECT e.id,e.empName,
                sum((collectDate BETWEEN '$month1' AND '$month2')*price) month1OrderPrice,
                sum((collectDate BETWEEN '$month3' AND '$month4')*price) month2OrderPrice
                FROM
                ym_emp e
                LEFT JOIN ym_order o
                ON e.id=o.empId AND collectDate BETWEEN '$month1' AND '$month4'
                WHERE $where AND e.teamId=$teamId
                group by e.id order by month1OrderPrice DESC";
        $data=M()->query($sql);
        //var_dump($data);
        $categories=array();
        $month1OrderPrice=array();
        $month2OrderPrice=array();
        foreach($data as $k=>$v){
            $categories[]=$v['empName'];
            $month1OrderPrice[]=intval($v['month1OrderPrice']);
            $month2OrderPrice[]=intval($v['month2OrderPrice']);
        }
        $optioned=array(
                'title'=>array('text'=>'团队每月销售金额统计'),
                'credits'=>array('enabled'=>false),
                'subtitle'=>array('text'=>'由技术部提供技术支持'),
                'xAxis'=>array('categories'=>$categories),
                'yAxis'=>array('min'=>0, 'title'=>array('text'=>'金额(元)'),'plotLines'=>array(array('value'=>0,'width'=>1,'color'=>'#808080'))),
                'tooltip'=>array('valueSuffix'=>'元'),
                'legend'=>array('layout'=>'vertical','align'=>'right','verticalAlign'=>'middle','borderWidth'=>0),
                 'series'=>array(
                     array('name'=>$month1.'至'.$month2,'data'=>$month1OrderPrice),
                     array('name'=>$month3.'至'.$month4,'data'=>$month2OrderPrice),
                     )
            );
        $this->option=json_encode($optioned);
        }else{//开发率
            $where="1";
            //分子
           $sql="SELECT e.id,e.empName,
                sum(collectDate BETWEEN '$month1' AND '$month2') month1OrderTotalNum,
                sum(collectDate BETWEEN '$month3' AND '$month4') month2OrderTotalNum
                FROM
                ym_emp e
                LEFT JOIN ym_order o
                ON e.id=o.empId AND collectDate BETWEEN '$month1' AND '$month4'
                WHERE $where AND e.teamId=$teamId
                group by e.id order by month1OrderTotalNum DESC";

            $data=M()->query($sql);
            $map1['gTime']  = array('between',array($month1,$month2));
            $map2['gTime']  = array('between',array($month3,$month4));

            foreach ($data as $key => $value) {
               $data[$key]['danSum1']=M('EmpCustomer')->where($map1)->where('empId='.$value['id'])->count();
               $data[$key]['danSum2']=M('EmpCustomer')->where($map2)->where('empId='.$value['id'])->count();

            }
            foreach($data as $k=>$v){
               $data[$k]['develope1']=round($v['month1OrderTotalNum']/$v['danSum1'],4)*100;
               $data[$k]['develope2']=round($v['month2OrderTotalNum']/$v['danSum2'],4)*100;

            }
            S('exportData',$data);

            $categories=array();
            $develope1=array();
            $develope2=array();
            foreach ($data as $key => $value) {
                $categories[]=$value['empName'];
                $develope1[]=$value['develope1']+0;
                $develope2[]=$value['develope2']+0;
            }

             $optioned=array(
                'title'=>array('text'=>'团队每月销售开发率统计'),
                'credits'=>array('enabled'=>false),
                'subtitle'=>array('text'=>'由技术部提供技术支持'),
                'xAxis'=>array('categories'=>$categories),
                'yAxis'=>array('min'=>0, 'title'=>array('text'=>'%'),'plotLines'=>array(array('value'=>0,'width'=>1,'color'=>'#808080'))),
                'tooltip'=>array('valueSuffix'=>'%'),
                'legend'=>array('layout'=>'vertical','align'=>'right','verticalAlign'=>'middle','borderWidth'=>0),
                 'series'=>array(
                     array('name'=>$month1.'至'.$month2,'data'=>$develope1),
                     array('name'=>$month3.'至'.$month4,'data'=>$develope2),
                     )
            );
        $this->option=json_encode($optioned);

        }
        $this->assign('teamList',M('Team')->field('id,teamName')->select());
        $this->month1=$month1;
        $this->month2=$month2;
        //以团队为单位计算销售金额和单数
        $where3="t.id = $teamId";
        $sql3="SELECT t.id,
                sum(collectDate BETWEEN '$month1' AND '$month2') monthOrderTotalNum3,
                sum((collectDate BETWEEN '$month1' AND '$month2')*price) monthOrderPrice3
                FROM
                ym_emp e
                LEFT JOIN ym_order o
                ON e.id=o.empId AND collectDate BETWEEN '$month1' AND '$month2'
                LEFT JOIN ym_team t
                ON e.teamId=t.id
                WHERE $where3";
        $data3=M()->query($sql3);
        $this->assign('data3',$data3);

//时间1 month5,6  时间2 month7,8  部门各团队销售额和单数
        if(empty(I('get.areaId'))){
            $_GET['areaId']=5;
        }
        $areaId=I('get.areaId')?:5;
        if(empty(I('get.def'))){
            $_GET['def']=1;
        }
        $def=I('get.def')?:1;
        $teamIdArr=M("Team")->field('id')->where('areaId='.$areaId)->select();
        foreach ($teamIdArr as $k=>$v){
            $teamIdArrs[]=$v['id'];
        }
        $teamIdStr=implode(",",$teamIdArrs);
       //单数
        if($def==1){
            $where="e.teamId in ($teamIdStr)";
            $sql="SELECT t.teamName,
				sum(collectDate BETWEEN '$month5' AND '$month6') month3OrderTotalNum,
    			sum(CASE WHEN collectDate BETWEEN '$month5' AND '$month6' AND isNew=1 THEN 1 ELSE 0 END) month3OrderNum,
				sum(collectDate BETWEEN '$month7' AND '$month8') month4OrderTotalNum,
				sum(CASE WHEN collectDate BETWEEN '$month7' AND '$month8' AND isNew=1 THEN 1 ELSE 0 END) month4OrderNum
				FROM
				ym_emp e
				LEFT JOIN ym_order o
				ON e.id=o.empId AND collectDate BETWEEN '$month5' AND '$month8'
				LEFT JOIN ym_team t
				ON e.teamId=t.id
				WHERE $where
				group by e.teamId order by month4OrderNum DESC";
            $data=M()->query($sql);
            $categories=array();
            $month3OrderTotalNum=array();
            $month4OrderTotalNum=array();
            foreach ($data as $k => $v) {
                $categories[]=$v['teamName'];
                $month3OrderTotalNum[]=intval($v['month3OrderTotalNum']);
                $month4OrderTotalNum[]=intval($v['month4OrderTotalNum']);
            }

            $optioned=array(
                'chart'=>array('type'=>'column'),
                'credits'=>array('enabled'=>false),
                'title'=>array('text'=>'部门每月销售单数统计'),
                'subtitle'=>array('text'=>'由技术部提供技术支持'),
                'xAxis'=>array('categories'=>$categories),
                'yAxis'=>array('min'=>0, 'title'=>array('text'=>'单数(个)'),'plotLines'=>array(array('value'=>0,'width'=>1,'color'=>'#716089'))),
                'tooltip'=>array('valueSuffix'=>'单'),
                'legend'=>array('layout'=>'vertical','align'=>'right','verticalAlign'=>'middle','borderWidth'=>0),
                 'series'=>array(
                     array('name'=>$month5.'至'.$month6,'data'=>$month3OrderTotalNum),
                     array('name'=>$month7.'至'.$month8,'data'=>$month4OrderTotalNum),
                     )
            );
        $this->option2=json_encode($optioned);
        }elseif($def==2){//金额
            $where="e.teamId in ($teamIdStr)";
            $sql="SELECT t.teamName,
                sum((collectDate BETWEEN '$month5' AND '$month6')*price) month3OrderPrice,
                sum((collectDate BETWEEN '$month7' AND '$month8')*price) month4OrderPrice
                FROM
                ym_emp e
                LEFT JOIN ym_order o
                ON e.id=o.empId AND collectDate BETWEEN '$month5' AND '$month8'
                LEFT JOIN ym_team t
                ON e.teamId=t.id
                WHERE $where
                group by e.teamId order by month3OrderPrice DESC";
            $data=M()->query($sql);
             $categories=array();
            $month3OrderPrice=array();
            $month4OrderPrice=array();
            foreach ($data as $k => $v) {
                $categories[]=$v['teamName'];
                $month3OrderPrice[]=intval($v['month3OrderPrice']);
                $month4OrderPrice[]=intval($v['month4OrderPrice']);
            }
            $optioned=array(
                'chart'=>array('type'=>'column'),
                'credits'=>array('enabled'=>false),
                'title'=>array('text'=>'部门每月销售金额统计'),
                'subtitle'=>array('text'=>'由技术部提供技术支持'),
                'xAxis'=>array('categories'=>$categories),
                'yAxis'=>array('min'=>0, 'title'=>array('text'=>'金额(元)'),'plotLines'=>array(array('value'=>0,'width'=>1,'color'=>'#716089'))),
                'tooltip'=>array('valueSuffix'=>'元'),
                'legend'=>array('layout'=>'vertical','align'=>'right','verticalAlign'=>'middle','borderWidth'=>0),
                 'series'=>array(
                     array('name'=>$month5.'至'.$month6,'data'=>$month3OrderPrice),
                     array('name'=>$month7.'至'.$month8,'data'=>$month4OrderPrice),
                     )
            );
        $this->option2=json_encode($optioned);
        }else{//开发率
            //分子
            $where="e.teamId in ($teamIdStr)";
            $sql="SELECT t.teamName,e.id,e.teamId,
                sum(collectDate BETWEEN '$month5' AND '$month6') month3OrderTotalNum,
                sum(CASE WHEN collectDate BETWEEN '$month5' AND '$month6' AND isNew=1 THEN 1 ELSE 0 END) month3OrderNum,
                sum(collectDate BETWEEN '$month7' AND '$month8') month4OrderTotalNum,
                sum(CASE WHEN collectDate BETWEEN '$month7' AND '$month8' AND isNew=1 THEN 1 ELSE 0 END) month4OrderNum
                FROM
                ym_emp e
                LEFT JOIN ym_order o
                ON e.id=o.empId AND collectDate BETWEEN '$month5' AND '$month8'
                LEFT JOIN ym_team t
                ON e.teamId=t.id
                WHERE $where
                group by e.teamId order by month4OrderNum DESC";
            $data=M()->query($sql);
            $map3['gTime']  = array('between',array($month5,$month6));
            $map4['gTime']  = array('between',array($month7,$month8));
            // var_dump($data);
            foreach($data as $k=>$v){
                $empIdArr=M("Emp")->field('id')->where("teamId='".$v['teamId']."'")->select();
                foreach ($empIdArr as $k1 => $v1) {
                    $data[$k]['empIdArrs'][]=$v1['id'];
                }
            }
            // var_dump($data);
            $map3['gTime']  = array('between',array($month5,$month6));
            $map4['gTime']  = array('between',array($month7,$month8));
            foreach ($data as $key => $value) {
               $empIdStr=implode(",",$value['empIdArrs']);
               $data[$key]['danSum1']=M('EmpCustomer')->where($map3)->where("empId in ($empIdStr)")->count();
               $data[$key]['danSum2']=M('EmpCustomer')->where($map4)->where("empId in ($empIdStr)")->count();
            }
             foreach($data as $k=>$v){
               $data[$k]['develope1']=round($v['month3OrderTotalNum']/$v['danSum1'],4)*100;
               $data[$k]['develope2']=round($v['month4OrderTotalNum']/$v['danSum2'],4)*100;
            }
            $categories=array();
            $arr1=array();
            $arr2=array();
            foreach ($data as $key => $value) {
                $categories[]=$value['teamName'];
                $arr1[]=$value['develope1']+0;
                $arr2[]=$value['develope2']+0;
            }
            // var_dump($data);
             $optioned=array(
                'chart'=>array('type'=>'column'),
                'title'=>array('text'=>'部门每月销售开发率统计'),
                'credits'=>array('enabled'=>false),
                'subtitle'=>array('text'=>'由技术部提供技术支持'),
                'xAxis'=>array('categories'=>$categories),
                'yAxis'=>array('min'=>0, 'title'=>array('text'=>'%'),'plotLines'=>array(array('value'=>0,'width'=>1,'color'=>'#808080'))),
                'tooltip'=>array('valueSuffix'=>'%'),
                'legend'=>array('layout'=>'vertical','align'=>'right','verticalAlign'=>'middle','borderWidth'=>0),
                 'series'=>array(
                     array('name'=>$month5.'至'.$month6,'data'=>$arr1),
                     array('name'=>$month7.'至'.$month8,'data'=>$arr2),
                     )
            );
        $this->option2=json_encode($optioned);
        }
        //部门销售额和单数
        $where2="t.areaId = $areaId";
        $sql2="SELECT t.id,
				sum(collectDate BETWEEN '$month5' AND '$month6') monthOrderTotalNum,
				sum((collectDate BETWEEN '$month5' AND '$month6')*price) monthOrderPrice
				FROM
				ym_emp e
				LEFT JOIN ym_order o
				ON e.id=o.empId AND collectDate BETWEEN '$month5' AND '$month6'
				LEFT JOIN ym_team t
				ON e.teamId=t.id
				WHERE $where2";
        $data2=M()->query($sql2);
        //var_dump($data2);
        $this->assign('data',$data);
        $this->assign('data2',$data2);
        $teamAreaList=M("TeamArea")->field('id,areaName')->select();
        $this->assign("teamAreaList",$teamAreaList);
        $this->month3=$month3;
        $this->month4=$month4;
        $this->month5=$month5;
        $this->month6=$month6;
        $this->month7=$month7;
        $this->month8=$month8;
        $this->dataed=date("Y-m-d,H:i:s",time());
        $this->display();

    }
    public function export(){
        $list=S("exportData");
        $month1=S('exportMonth1');
        $month2=S('exportMonth2');
        $teamId=S('exportTeamId');
        $teamName=M("Team")->where('id='.$teamId)->getField('teamName');
        Vendor('PHPExcel.Classes.PHPExcel');
        $objPHPExcel = new \PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '销售人员')
            ->setCellValue('B1', '申请数')
            ->setCellValue('C1', '单数')
            ->setCellValue('D1', '开发率');


        // 内容
        for ($i = 0, $len = count($list); $i < $len; $i++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 2), ' '.$list[$i]['empName']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 2), ' '.$list[$i]['danSum1']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 2), ' '.$list[$i]['month1OrderTotalNum']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 2), ' '.$list[$i]['develope1'].'%');

        }

        // dump($objPHPExcel);exit('34343');

        $filename="$month1 至 $month2 $teamName 开发率.xls";

        // Rename worksheet
        // $objPHPExcel->getActiveSheet()->setTitle($filename);


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}
