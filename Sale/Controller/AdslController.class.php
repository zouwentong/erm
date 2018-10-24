<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class AdslController extends CommonController
{

    // 列表
    public function index()
    {

          if (I('iCheck')) {
          	 $check=I('iCheck');
          }else{
          	$check=1;

          }
 		 	if (I('month1')) {
 		 		 $month1=I('month1');
 		 	}else{
 		 		$month1=I('get.month1')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,1,date('Y',strtotime(date('Y-m-d')))));

 		 	}if (I('month2')) {
 		 		 $month2=I('month2');
 		 	}else{
 		 		 $month2=I('get.month2')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,date('d',strtotime(date('Y-m-d'))),date('Y',strtotime(date('Y-m-d')))));
 		 	}

		  $where='1';
		  $map='1';
		if ($check==1) {
			$where='1';
		  	$map='1';
		}elseif ($check==2) {
			$where= "c.source in ('端口20','端口22','端口25','端口sem','广点通','广点通12','广点通9','广告资源','媒体1','媒体12','媒体13','媒体14','媒体15','媒体16','媒体17','媒体18','媒体19','媒体2','媒体20','媒体21','媒体22','媒体23','媒体24','媒体3','媒体30','媒体31','媒体39','媒体4','媒体40','媒体41','媒体42','媒体43','媒体45','媒体46','媒体48','媒体49','媒体5','媒体52','媒体6','媒体7','媒体8','媒体9')";
			$map="o.sourceId in ('17','80','18','16','15','79','81','9','21','28','29','30','93','31','32','33','34','90','92','35','36','37','38','22','39','40','95','23','41','42','43','44','45','46','47','48','24','94','25','26','91','27')";
		}elseif ($check==3) {
			$where= "c.source in ('被动','普通','常规TPS','端口sem','其他','用户介绍','进线')";
			$map="o.sourceId in ('8','1','2','89','88','6')";
		}elseif ($check==4) {
		$where= "c.source in ('总部微信','总部微信朋友圈','微信')";
			$map="o.sourceId in ('14','19','4')";
		}
											
		if($month1){
			$where.=" AND c.gTime>='". $month1."'";
		}

		if($month2){
			$where.=" AND c.gTime<='". $month2."'";
		}
		if($month1){
			$map.=" AND c.gTime>='". $month1."'";
		}

		if($month2){
			$map.=" AND c.gTime<='". $month2."'";
		}

    	$data=$this->mysqls($where,$map);
    	
    	 $this->data1=$data[0];
		 $this->data2=$data[1];



//表格统计
	if (I('month1_1')) {
 		 		 $month1_1=I('month1_1');
 		 	}else{
 		 		$month1_1=I('get.month1_1')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,1,date('Y',strtotime(date('Y-m-d')))));

 		 	}if (I('month2_2')) {
 		 		 $month2_2=I('month2_2');
 		 	}else{
 		 		 $month2_2=I('get.month2_2')?:date('Y-m-d',mktime(0,0,0,date('m',strtotime(date('Y-m-d')))-1,date('d',strtotime(date('Y-m-d'))),date('Y',strtotime(date('Y-m-d')))));
 		 	}
		 $where1='1';
		  $map1='1';
		if( $month1_1){
			$where1.=" AND c.gTime>='". $month1_1."'";
		}

		if($month2_2){
			$where1.=" AND c.gTime<='". $month2_2."'";
		}
		if( $month1_1){
			$map1.=" AND c.gTime>='". $month1_1."'";
		}

		if($month2_2){
			$map1.=" AND c.gTime<='". $month2_2."'";
		}

		$sql1='select s.sourceName, count(c.id) count1 from ym_source s  left join ym_emp_customer c on  c.source=s.sourceName
           where '.$where1.' group by s.sourceName';
        $sql2='select s.sourceName, count(o.id) count2 from ym_order o left join ym_source s on o.sourceId=s.id  
        left join ym_emp_customer c on c.customerId=o.customerId
         where '. $map1.' group by s.sourceName';
        $data=M('Source')->query($sql1);
        $datas=M('Source')->query($sql2);
            foreach ($data as $k => $v) {
        		foreach ($datas as $key => $value) {
         			if ($v['sourceName']==$value['sourceName']) {


         		$data1[$k]['sourceName']=$value['sourceName'];
         	   	$data1[$k]['counts']=round($value['count2']/$v['count1'],2)*100;
         			}
        	}

         
         }
 			$data2_2= $this->array_sort($data1, 'counts', SORT_DESC);
			$data3_3=array_slice($data2_2,-3,3); 
			$data4_4=array_slice($data2_2, 0,3);
			$this->assign(data2_2,$data2_2);
			$this->assign(data3_3,$data3_3);
         	$this->assign(data4_4,$data4_4);
       		$this->display();
    }

   
	public function mysqls($where,$map){
 		$sql1='select s.sourceName, count(c.id) count1 from ym_source s  left join ym_emp_customer c on  c.source=s.sourceName
           where '.$where.' group by s.sourceName';
        $sql2='select s.sourceName, count(o.id) count2 from ym_order o left join ym_source s on o.sourceId=s.id  
        left join ym_emp_customer c on c.customerId=o.customerId
         where '. $map.' group by s.sourceName';
         // print_r($sql1);
         // print_r($sql2);
 		$data=M('Source')->query($sql1);
        $datas=M('Source')->query($sql2);
        // print_r($data);
        // print_r($datas);
         foreach ($data as $k => $v) {
        	foreach ($datas as $key => $value) {
         			if ($v['sourceName']==$value['sourceName']) {


         		$data1[]="'".$value['sourceName']."'";
         	   	$data2[]=round($value['count2']/$v['count1'],2)*100;
         			}
        	}

         
         }

         foreach ($data2 as $k2 => $v2) {
         		
         		if ($v2==0) { 
         			unset($data1[$k2]);
         			unset($data2[$k2]);
         	}
         }
        $data1=implode(",",$data1);
      	$data2=implode(",",$data2);
      	$data3=array($data1,$data2);
      	return $data3;

}

  
	public function array_sort($array, $on, $order=SORT_ASC)
	{

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
}










