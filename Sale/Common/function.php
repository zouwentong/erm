<?php
function exportMonthExcel($list,$month='',$num=0,$price=0,$is_admin=0){
	require_once './vendor/PHPExcel/Classes/PHPExcel.php';
		// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");
	// set width    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);  
  
    // 设置行高度    
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
  
    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
    // 字体和样式  
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11); 
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('宋体');  
    $objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getFont()->setBold(true);  
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  
    $objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('A2:P2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  
    // 设置水平居中    
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
  
    //  合并  
    $objPHPExcel->getActiveSheet()->mergeCells('A1:O1');  
  
    // 表头  
    if($month){
        $title='益盟'.$month.'月销售报表 总单数：'.$num.' 总金额：'.$price.'元';
    }else{
        $title='查询订单数据';
    }
    
    $objPHPExcel->setActiveSheetIndex(0)  
            ->setCellValue('A1', $title)  
            ->setCellValue('A2', '来源')  
            ->setCellValue('B2', '讲师')  
            ->setCellValue('C2', '新增')  
            ->setCellValue('D2', '订单状态')
            ->setCellValue('E2', '销售人员')
            ->setCellValue('F2', '日期')  
            ->setCellValue('G2', '客户姓名')  
            ->setCellValue('H2', '产品')  
            ->setCellValue('I2', '金额')
            ->setCellValue('J2', '用户编号')
            ->setCellValue('K2', '订单编号')
            ->setCellValue('L2', 'QQ')  
            ->setCellValue('M2', '手机号码')  
            ->setCellValue('N2', '地址')  
            ->setCellValue('O2', '备注')
            ->setCellValue('P2', '是否上传聊天记录')
            ->setCellValue('Q2', '聊天文件名')
            ->setCellValue('R2', '第一次成交日期')
            ->setCellValue('S2', '主管')
        ->setCellValue('T2', '总监')
    ->setCellValue('U2', '购买数');

  
    // 内容  
    for ($i = 0, $len = count($list); $i < $len; $i++) {  
        switch ($list[$i]['isNew']) {
            case '1':
                $list[$i]['isNew']='';
                break;
            case '2':
                $list[$i]['isNew']='否';
                break;
            case '3':
                $list[$i]['isNew']='是-半单';
                break;
            case '4':
                $list[$i]['isNew']='否-半单';
                break;
            case '5':
                $list[$i]['isNew']='上拽';
                break;
            case '6':
                $list[$i]['isNew']='续费';
                break;
            case '7':
                $list[$i]['isNew']='其他';
                break;
            default:
                break;
        }

        if(!$is_admin){
        	$list[$i]['qq']=subPhone($list[$i]['qq'],3,4);
        	$list[$i]['mobile']=subPhone($list[$i]['mobile']);
        }
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $list[$i]['source']?:$list[$i]['sourceName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $list[$i]['teacherName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $list[$i]['isNew']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $list[$i]['status']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $list[$i]['empName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), $list[$i]['collectDate']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), $list[$i]['name']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), $list[$i]['productName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 3), $list[$i]['price']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 3), ' ' . $list[$i]['emNum']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('K' . ($i + 3), ' ' .$list[$i]['orderNo']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('L' . ($i + 3), ' '.$list[$i]['qq']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('M' . ($i + 3), ' ' .$list[$i]['mobile']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('N' . ($i + 3), $list[$i]['address']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('O' . ($i + 3), ' ' . $list[$i]['beizhu']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('P' . ($i + 3), ' ' . ($list[$i]['orderId']?'√':'×')); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q' . ($i + 3), ' ' . $list[$i]['chatSourceName']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('R' . ($i + 3), ' ' . $list[$i]['firstCollect']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('S' . ($i + 3), $list[$i]['teamLeaderName']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('T' . ($i + 3), $list[$i]['teamManagerName']);
        $objPHPExcel->getActiveSheet(0)->setCellValue('U' . ($i + 3), $list[$i]['count']);
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':Q' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        // $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':P' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);  
    }  

    if($month){
        $filename = '益盟'.$month.'月销售报表.xls';
    }else{
        $filename=$title.'.xls';
    }
	 
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($filename);


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

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
}

function exportPrizeExcel($list,$staffOrder,$staffTrueOrder,$month='',$order_num=0,$total_price=0,$list2='',$tx_price=0){
    set_time_limit(0);
    require_once './vendor/PHPExcel/Classes/PHPExcel.php';
        // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");
    // set width    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
  
    // 设置行高度    
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15);  
  
    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15); 

    $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(15); 

    $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(15);  

  
    // 字体和样式  
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11); 
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('宋体');  
    // $objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(true);  
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  
    // $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
  
    // 设置水平居中    
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    // $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
  
    //  合并  
    $objPHPExcel->getActiveSheet()->mergeCells('A1:A4'); 
    // $objPHPExcel->getActiveSheet()->mergeCells('B1:F1');  
    // $objPHPExcel->getActiveSheet()->mergeCells('B2:F2'); 
    // $objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
  
    // 表头  
    if($month){
        $title='益盟'.$month.'提成';
    }else{
        $title='个人订单';
    }
    
    $objPHPExcel->setActiveSheetIndex(0)   
            ->setCellValue('A1', '订单金额');
  
    // 内容  
    for ($i = 0, $len = count($list); $i < $len; $i++) {  
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 5), ''.$list[$i]['price']);  
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 5) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 5) )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 5))->getFont()->setBold(true); 
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 5)->setRowHeight(20);  
    }

    $arr=array('合计','销售总额','提成金额','财务确认','团队提成','个人奖励','合计','备注');
    for($j=0;$j<8;$j++){
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 5), $arr[$j]);   
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 5) )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 5) )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 5)->setRowHeight(20);
        $i++;
    }

    // $objPHPExcel->getActiveSheet()->getStyle( 'A'.($i + 4).':F'.($i + 4))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    // $objPHPExcel->getActiveSheet()->getStyle( 'A'.($i + 4).':F'.($i + 4))->getFill()->getStartColor()->setARGB('FF808080');

    $start='B';
    foreach($staffOrder as $k=>$staff) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($start)->setWidth(15); 
        $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($start.'1','') 
            ->setCellValue($start.'2', '')  
            ->setCellValue($start.'3', $k)
            ->setCellValue($start.'4', ''); 

        $objPHPExcel->getActiveSheet()->getStyle($start . '1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle($start . '2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle($start . '3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle($start . '4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 

        for ($i = 0, $len = count($list); $i < $len; $i++) {
            $flag=0;
            foreach($staff as $v){
                if($list[$i]['price'] == $v['price']){
                    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 5),$v['num']);  
                    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
                    $flag=1;
                    break;  
                }
            } 
            if($flag===0){
                $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 5),'');  
                $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
        }


        $num=0;
        $price=0.0;
        foreach($staff as $k2=>$v2){
            $price+=$v2['total_price'];
        } 

        foreach($staffTrueOrder[$k] as $k3=>$v3){
            $num+=$v3['num'];
        } 


        $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
         $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
        $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 5), $num); 
        $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 6))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
         $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
        $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 6), $price);

        $start++;
    }


    $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($start.'1','') 
            ->setCellValue($start.'2', '')  
            ->setCellValue($start.'3', '合计')
            ->setCellValue($start.'4', ''); 
    $objPHPExcel->getActiveSheet()->getStyle($start . '1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle($start . '2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle($start . '3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle($start . '4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 5),$order_num);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 6),$total_price);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 6))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 


    $start++;
    $objPHPExcel->getActiveSheet()->getColumnDimension($start)->setWidth(20);
    $objPHPExcel->getActiveSheet()->mergeCells($start.'1:'.$start.'4');
    $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle($start.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($start.'1', '腾讯资源出单统计'); 
     $objPHPExcel->getActiveSheet()->getStyle($start . '1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     $objPHPExcel->getActiveSheet()->getStyle($start . '2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     $objPHPExcel->getActiveSheet()->getStyle($start . '3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     $objPHPExcel->getActiveSheet()->getStyle($start . '4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 

    $total_num=0;
    for ($j = 0, $len = count($list); $j < $len; $j++) {
            $flag=0;
            foreach($list2 as $v){
                if($list[$j]['price'] == $v['price']){
                    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($j + 5),$v['price_num']);  
                    $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                    $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
                    $flag=1;
                    $total_num+=$v['price_num'];
                    break;  
                }
            } 
            if($flag===0){
                $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($j + 5),0);  
                $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            }
    }
    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($j + 5),$total_num);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($j + 6),$tx_price);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 6))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle($start . ($j + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
    

    $filename = '益盟'.$month.'月提成.xls';
     
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($filename);


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

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}

function exportLineExcel($list,$month,$lastmonth,$name='进线'){
	require_once './vendor/PHPExcel/Classes/PHPExcel.php';
		// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
								 ->setLastModifiedBy("Maarten Balliauw")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");
	// set width    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);   
  	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(100); 

    // 设置行高度    
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
  
    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
    // 字体和样式  
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);  
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  
    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  
    // 设置水平居中    
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  
    //  合并  
    $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');  
  
    // 表头  
    $objPHPExcel->setActiveSheetIndex(0)  
            ->setCellValue('A1', '益盟'.$lastmonth.'至'.$month.$name.'资源') 
            ->setCellValue('A2', '日期')  
            ->setCellValue('B2', '产品')  
            ->setCellValue('C2', '客户名称')  
            ->setCellValue('D2', 'EM编号')
            ->setCellValue('E2', '订单编号')
            ->setCellValue('F2', '收款方式')
            ->setCellValue('G2', '实际收款')  
            ->setCellValue('H2', '备注');  
  
    // 内容  
    for ($i = 0, $len = count($list); $i < $len; $i++) {  
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $list[$i]['collectDate']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $list[$i]['productName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $list[$i]['name']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), ' ' . $list[$i]['emNum']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), ' ' . $list[$i]['orderNo']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), '网银');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), $list[$i]['price']);   
        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), $list[$i]['beizhu']); 
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':H' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':H' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);  
    }  

	$filename = '益盟'.$lastmonth.'至'.$month.$name.'.xls'; 
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($filename);


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

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
}

function exportLineDetailExcel($list,$staffLine,$staffOrder,$month='',$num=0,$price=0,$name='进线'){
    set_time_limit(0);
    require_once './vendor/PHPExcel/Classes/PHPExcel.php';
        // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");
    // set width    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
  
    // 设置行高度    
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(10);  
  
    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20); 

    $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);  
  
    // 字体和样式  
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11); 
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('宋体');  
    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);  
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  
    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $objPHPExcel->getActiveSheet()->getStyle('A3' . ':F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
  
    // 设置水平居中    
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
  
    //  合并  
    $objPHPExcel->getActiveSheet()->mergeCells('A1:A2'); 
    $objPHPExcel->getActiveSheet()->mergeCells('B1:F1');  
    $objPHPExcel->getActiveSheet()->mergeCells('B2:F2'); 
    $objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
  
    // 表头  
    if($month){
        $title='益盟'.$month.$name.'明细 总单数：'.$num.' 总金额：'.$price.'元';
    }else{
        $title='个人订单';
    }
    
    $objPHPExcel->setActiveSheetIndex(0)   
            ->setCellValue('B3', '数量')  
            ->setCellValue('C3', '金额')  
            ->setCellValue('D3', '成本')
            ->setCellValue('E3', '毛利')
            ->setCellValue('F3', '资源费用'); 
  
    // 内容  
    for ($i = 0, $len = count($list); $i < $len; $i++) {  
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 4), $list[$i]['productName'].' '.$list[$i]['price']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 4), $list[$i]['num']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 4), $list[$i]['total_price']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 4), ''); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 4), '');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 4), '');   
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 4) . ':F' . ($i + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 4) . ':F' . ($i + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 4)->setRowHeight(16);  
    }
    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 4), '总和');  
    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 4), $num);  
    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 4), $price);  
    $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 4), ''); 
    $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 4), '');  
    $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 4), ''); 
    $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 4) . ':F' . ($i + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 4) . ':F' . ($i + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
    $objPHPExcel->getActiveSheet()->getRowDimension($i + 4)->setRowHeight(25);
    $objPHPExcel->getActiveSheet()->getStyle( 'A'.($i + 4).':F'.($i + 4))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle( 'A'.($i + 4).':F'.($i + 4))->getFill()->getStartColor()->setARGB('FF808080');

    $start='H';
    $tmp=$start;
    $end=++$start;
    $start=$tmp;
    foreach($staffLine as $k=>$staff) {
        $objPHPExcel->getActiveSheet()->getStyle($start)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
        $objPHPExcel->getActiveSheet()->getStyle($end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCells($start.'1:'.$end.'1');  
        $objPHPExcel->getActiveSheet()->mergeCells($start.'2:'.$end.'2'); 
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($start.'2',$k) 
            ->setCellValue($start.'3', '数量')  
            ->setCellValue($end.'3', '金额'); 
        $objPHPExcel->getActiveSheet()->getStyle($start.'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
        $objPHPExcel->getActiveSheet()->getStyle( $start.'2:'.$end.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle( $start.'2:'.$end.'2')->getFill()->getStartColor()->setARGB('FF808080');

        $objPHPExcel->getActiveSheet()->getStyle($start.'3' . ':'.$end.'3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
        $objPHPExcel->getActiveSheet()->getStyle($start.'3' . ':'.$end.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
        $objPHPExcel->getActiveSheet()->getStyle($start.'3' . ':'.$end.'3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        for ($i = 0, $len = count($list); $i < $len; $i++) {
            $flag=0;
            foreach($staff as $v){
                if($list[$i]['product'] == $v['product'] && $list[$i]['price'] == $v['price']){
                    $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 4),$v['num']);  
                    $objPHPExcel->getActiveSheet(0)->setCellValue($end . ($i + 4), $v['total_price']);
                    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                    $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
                    $flag=1;
                    break;  
                }
            } 
            if($flag===0){
                $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 4),'');  
                $objPHPExcel->getActiveSheet(0)->setCellValue($end . ($i + 4), '');
                $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
            }
        }

        $num=0;
        $price=0.0;
        foreach($staff as $k2=>$v2){
            $num+=$v2['num'];
            $price+=$v2['total_price'];
        } 


        $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); 
         $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN); 
        $objPHPExcel->getActiveSheet()->getStyle( $start . ($i + 4) . ':'.$end . ($i + 4))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle( $start . ($i + 4) . ':'.$end . ($i + 4))->getFill()->getStartColor()->setARGB('FF808080');
        $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 4), $num);  
        $objPHPExcel->getActiveSheet(0)->setCellValue($end . ($i + 4), $price);

        $i=$i+2;
        foreach($staffOrder[$k] as $v){
                $objPHPExcel->getActiveSheet(0)->setCellValue($start . ($i + 4),$v['price']);  
                $objPHPExcel->getActiveSheet(0)->setCellValue($end . ($i + 4), $v['orderNo']);
                // $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
                // $objPHPExcel->getActiveSheet()->getStyle($start . ($i + 4) . ':'.$end . ($i + 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
                $i++; 
        }

        $start++;
        $start++;
        $start++;
        $tmp=$start;
        $end=++$start;
        $start=$tmp;
    }
    

    $filename = '益盟'.$month.$name.'明细.xls';
     
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($filename);


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

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}

function exportPrizeExcel2($list,$list2,$month){
    require_once './vendor/PHPExcel/Classes/PHPExcel.php';
        // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->createSheet();
    $objPHPExcel->setactivesheetindex(0);

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");
    // set width    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(80);  
  
    // 设置行高度    
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
  
    // $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
    // 字体和样式  
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11); 
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('宋体');  
    // $objPHPExcel->getActiveSheet()->getStyle('A1:R2')->getFont()->setBold(true);  
    // $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  
    // $objPHPExcel->getActiveSheet()->getStyle('A2:R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // $objPHPExcel->getActiveSheet()->getStyle('A2:R2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
  
    // 设置水平居中     
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
    $objPHPExcel->getActiveSheet()->getStyle('Q')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('R')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);          
  
    //  合并  
    // $objPHPExcel->getActiveSheet()->mergeCells('A1:R1');  

    
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '工号')  
            ->setCellValue('B1', '部门')  
            ->setCellValue('C1', '项目经理')  
            ->setCellValue('D1', '归属组')  
            ->setCellValue('E1', '订单归属人')
            ->setCellValue('F1', '物流订单编号')
            ->setCellValue('G1', '物流产品名称')  
            ->setCellValue('H1', '备货日期')  
            ->setCellValue('I1', '客户')  
            ->setCellValue('J1', '数量')
            ->setCellValue('K1', '产品销售价格')
            ->setCellValue('L1', '业务员业绩')
            ->setCellValue('M1', '提成比例/单价')  
            ->setCellValue('N1', '金额类型')  
            ->setCellValue('O1', '购买类型')  
            ->setCellValue('P1', '当月一次性发放')
            ->setCellValue('Q1', '延迟发放')
            ->setCellValue('R1', '备注');  
  
    // 内容  
    for ($i = 0, $len = count($list); $i < $len; $i++) {  
        switch ($list[$i]['isNew']) {
            case '1':
                $list[$i]['isNew']='新增';
                break;
            default:
                $list[$i]['isNew']='非新增';
                break;
        }

        // if(!$is_admin){
        //     $list[$i]['qq']=subPhone($list[$i]['qq'],3,4);
        //     $list[$i]['mobile']=subPhone($list[$i]['mobile']);
        // }
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 2), $list[$i]['empNo']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 2), $list[$i]['areaName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 2), $list[$i]['teamManager']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 2), $list[$i]['teamLeader']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 2), $list[$i]['empName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 2), $list[$i]['orderNo']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 2), $list[$i]['productName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 2), $list[$i]['collectDate']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 2), $list[$i]['name']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 2), ' ' . $list[$i]['goodsNum']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('K' . ($i + 2), ' ' .$list[$i]['price']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('L' . ($i + 2), ' '.$list[$i]['price']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('M' . ($i + 2), ' ');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('N' . ($i + 2), ' ');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('O' . ($i + 2), ' '.$list[$i]['isNew'] ); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('P' . ($i + 2), ' ' ); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('Q' . ($i + 2), ' ' ); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('R' . ($i + 2), ' ' . $list[$i]['beizhu']); 
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 1) . ':R' . ($i + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        // $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':P' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 1)->setRowHeight(16);  
    }  


    $filename = '益盟'.$month.'业务员业绩提成报表.xls';

    $objPHPExcel->getActiveSheet()->setTitle($filename);


    $objPHPExcel->createSheet();
    $objPHPExcel->setactivesheetindex(1);

    $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', '序号')  
            ->setCellValue('B1', '部门')  
            ->setCellValue('C1', '业务性质')  
            ->setCellValue('D1', '组别')  
            ->setCellValue('E1', '职位')
            ->setCellValue('F1', '姓名')
            ->setCellValue('G1', '员工编码')  
            ->setCellValue('H1', '业务员业绩')  
            ->setCellValue('I1', '新增单数')  
            ->setCellValue('J1', '提成比例'); 

    for ($i = 0, $len = count($list2); $i < $len; $i++) {  

        // if(!$is_admin){
        //     $list[$i]['qq']=subPhone($list[$i]['qq'],3,4);
        //     $list[$i]['mobile']=subPhone($list[$i]['mobile']);
        // }
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 2), $i+1);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 2), $list2[$i]['areaName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 2), '业务员');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 2), $list2[$i]['teamLeader']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 2), '-');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 2), $list2[$i]['empName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 2), $list2[$i]['empNo']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 2), $list2[$i]['orderPrice']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 2), $list2[$i]['orderNum']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 2), ' ');  

        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 1) . ':J' . ($i + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        // $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':P' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 2)->setRowHeight(16);  
    }

    $objPHPExcel->getActiveSheet()->setTitle('益盟'.$month.'奖金汇总表.xls');
     
    // Rename worksheet
    

    $filename = '益盟'.$month.'提成报表.xls';
    

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

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}

function exportPrizeExcel3($list,$month){
    require_once './vendor/PHPExcel/Classes/PHPExcel.php';
        // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                 ->setLastModifiedBy("Maarten Balliauw")
                                 ->setTitle("Office 2007 XLSX Test Document")
                                 ->setSubject("Office 2007 XLSX Test Document")
                                 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                 ->setKeywords("office 2007 openxml php")
                                 ->setCategory("Test result file");
    // set width    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);

    // 字体和样式  
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11); 
    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('宋体');  
    $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);  
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  
    // $objPHPExcel->getActiveSheet()->getStyle('A2:R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    // 
    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
    $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
    $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')  
            ->setCellValue('B1', '部门')  
            ->setCellValue('C1', '业务性质')  
            ->setCellValue('D1', '组别')  
            ->setCellValue('E1', '职位')
            ->setCellValue('F1', '姓名')
            ->setCellValue('G1', '员工编码')  
            ->setCellValue('H1', '业务员业绩')  
            ->setCellValue('I1', '新增单数')  
            ->setCellValue('J1', '提成比例');
 
  
    // 内容  
    for ($i = 0, $len = count($list); $i < $len; $i++) {  

        // if(!$is_admin){
        //     $list[$i]['qq']=subPhone($list[$i]['qq'],3,4);
        //     $list[$i]['mobile']=subPhone($list[$i]['mobile']);
        // }
        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 2), $i+1);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 2), $list[$i]['areaName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 2), '业务员');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 2), $list[$i]['teamLeader']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 2), '-');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 2), $list[$i]['empName']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 2),' ');  
        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 2), $list[$i]['orderPrice']);  
        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 2), $list[$i]['orderNum']); 
        $objPHPExcel->getActiveSheet(0)->setCellValue('J' . ($i + 2), ' ');  

        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 1) . ':J' . ($i + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  
        // $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':P' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);  
        $objPHPExcel->getActiveSheet()->getRowDimension($i + 2)->setRowHeight(16);  
    }  

    if($month){
        $filename = '益盟'.$month.'奖金汇总表.xls';
    }
     
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($filename);


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

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}