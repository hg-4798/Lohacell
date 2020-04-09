<?php
/**
 * 상품처리 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$Product = new PRODUCT;
$category = new CATEGORYLIST;
$mode = $_POST['mode'];
$act = $_POST['act'];

// pre($_POST);exit;
if($mode == 'product') { //상품처리
	if($act == 'stock') { //옵션별 재고 및 정보변경(예약재고,옵션품절등처리
	}
	else if($act == 'size') {
	}
	else { //상품정보 등록&수정
		$tbl = 'tblproduct';

		//$escape_data= 'productname,prkeyword,content,content_m';
		Common::escapeData();


		$max_quantity = ($_POST['max_quantity_type'] == 'unlimit')? -1 :$_POST['max_quantity'];
		$delivery = $_POST['delivery'];

		$consumerprice = str_replace(',','',$_POST['consumerprice']);
		$sellprice = str_replace(',','',$_POST['sellprice']);
		$sellprice_dc_rate = round(($consumerprice-$sellprice)*100/$consumerprice);

		//추가구매상품
		$add_product_arr = array_unique(array_filter(explode(',',$_POST['add_product'])));
		$option_type = $_POST['option_type']; //옵션유형(N:일반, C:컬러칩)
		$record = array(
			'prodcode' => $_POST['prodcode'],
			'productname'=>$_POST['productname'],
			'pr_type'=>$_POST['pr_type'],
			'soldout'=>$_POST['soldout'],
			'consumerprice'=>$consumerprice,
			'sellprice'=>$sellprice,
			'sellprice_dc_rate'=>$sellprice_dc_rate,
			'endprice'=>$sellprice,
			'endprice_dc_rate'=>$sellprice_dc_rate,
			'line_code' => $_POST['line_code'],
			'prkeyword'=>$_POST['prkeyword'],
			'min_quantity'=>$_POST['min_quantity'],
			'max_quantity'=>$max_quantity,
			'deli'=>$delivery['deli'],
			'deli_select'=>($delivery['deli_select'])?$delivery['deli_select']:0,
			'deli_price'=>($delivery['deli_price'])?$delivery['deli_price']:0,
			'deli_qty'=>($delivery['deli_qty'])?$delivery['deli_qty']:0,
			'icon'=>@implode(',',$_POST['icon']),
			'option_use'=>($_POST['option_use'])?1:0,
			'content'=>htmlspecialchars($_POST['content'], ENT_QUOTES),
			'content_m'=>htmlspecialchars($_POST['content_m'], ENT_QUOTES),
			'regdate'=>date('Y-m-d H:i:s'),
			'modifydate'=>date('Y-m-d H:i:s'),
			'add_product_use' => $_POST['add_product_use'],
			'add_product' => implode(',',$add_product_arr),
			'option_type'=>$option_type,
			'phrase_ad'=>$_POST['phrase_ad'],
			'phrase_etc'=>$_POST['phrase_etc'],
			'property_use'=>$_POST['property_use'], //상품정보고시노출여부
			'use_imgurl'=>($_POST['use_imgurl'])?$_POST['use_imgurl']:'N',
			'naver_display'=>$_POST['naver_display']?$_POST['naver_display']:'N' //네이버 지식쇼핑 노출여부 추가 bshan
		);


		if(isset($_POST['quantity'])) {
			$record['quantity'] = $_POST['quantity'];
		}


		$categorylink = explode(',',$_POST['categorylink']);//선택카테고리

		if($_POST['use_imgurl'] =='Y') { //이미지경로저장
			$image_url = $_POST['image_url'];
			$record = array_merge($record,$image_url);
		}
		else {
			foreach(array('maximage','minimage','tinyimage') as $col) {
				list($path, $del) = explode('#',$_POST['image_old'][$col]);
				if($del == 'del') {
					@unlink(DOC_ROOT.$path);
					$record[$col] = '';
				}
			}
		}


		if($act == 'insert') {
			$prodcode_cnt = $Product->getDupProdCode($_POST['prodcode']);
			$prodcode_cnt = (int)$prodcode_cnt;
			if($prodcode_cnt){
				return_json(false, '상품모델명이 중복 되었습니다.', 'prodcode_cnt');
			}
			$productcode = $Product->createProductcode($categorylink[0]);
			$record['productcode'] = $productcode;

			$sql = sqlInsert($record, $tbl);
			$rs = $Product->adodb->Execute($sql);
		}
		else if($act == 'update') {
			unset($record['prodcode']);
			$productcode = $_POST['productcode'];
			$sql = sqlUpdate($record, $tbl, array('productcode'=>$productcode));
			$rs = $Product->adodb->Execute($sql);
		}

		if($rs) {
			//기간할인처리
			if($act == 'update') $Product->sync_timesale($productcode);

			//카테고리 저장
			if($_POST['categorylink']) {
				if($act == 'update') {
					$Product->adodb->Execute("UPDATE tblproductlink SET is_delete='Y' WHERE c_productcode='{$productcode}'"); //삭제플레그 임시 업데이트
				}
				$c_date = date('YmdHis');
				$c_maincate = 1;
				foreach($categorylink as $k=>$c_category) {
					$sql = "INSERT INTO tblproductlink (c_productcode, c_category, c_maincate, c_date, c_date_1, c_date_2, c_date_3, c_date_4) VALUES ('{$productcode}','{$c_category}','{$c_maincate}', '{$c_date}','{$c_date}','{$c_date}','{$c_date}','{$c_date}') ON CONFLICT (c_productcode, c_category) DO UPDATE SET is_delete='N'";
					$Product->adodb->Execute($sql);
				}
				$Product->adodb->Execute("DELETE FROM tblproductlink WHERE c_productcode='{$productcode}' AND is_delete='Y'");
			}
			else {
				$Product->adodb->Execute("DELETE FROM tblproductlink WHERE c_productcode='{$productcode}'"); //삭제플레그 임시 업데이트
			}

			//이미지 저장
			if($_POST['use_imgurl']=='Y' && $_POST['image_etc_url']) {
				//기타이미지
				extract($_POST['image_etc_url']);
				$sql = "INSERT INTO tblmultiimages (productcode, primg01, primg02, primg03, primg04, primg05, primg06, primg07, primg08, primg09, primg10) VALUES 
						('{$productcode}','{$primg01}','{$primg02}','{$primg03}','{$primg04}','{$primg05}','{$primg06}','{$primg07}','{$primg08}','{$primg09}','{$primg10}') 
						ON CONFLICT (productcode) DO UPDATE SET primg01='{$primg01}',primg02='{$primg02}',primg03='{$primg03}',primg04='{$primg04}',primg05='{$primg05}',primg06='{$primg06}',primg07='{$primg07}',primg08='{$primg08}',primg09='{$primg09}',primg10='{$primg10}'";
				$Product->adodb->Execute($sql);
			}
			else {
				include_once $Dir."lib/upload.class.php";
				$image_dir = DIRECTORY_SEPARATOR.ImageDir.$productcode.'/';

				//상품기본이미지 업로드
				if(!empty($_FILES['image_file'])) {
					$f = array_arrange($_FILES['image_file']);
					foreach($f as $k=>$v) {
						$handle = new upload($v);

						if ($handle->uploaded) {
							$handle->file_name_body_add = '_'.date('YmdHis');
							$file_rs = $handle->process(DOC_ROOT.$image_dir);

							if ($handle->processed) {
								$path = $image_dir.$handle->file_dst_name;
								$Product->adodb->Execute("UPDATE tblproduct SET {$k}='$path' WHERE productcode='{$productcode}'");

								//echo DOC_ROOT.$old['icon_'.$k];
								@unlink(DOC_ROOT.$_POST['image_old'][$k]);
								$handle->clean();
							} else {
							}
						}
					}
				}

				//상품기타이미지 업로드
				if(!empty($_FILES['image_etc_file'])) {
					$f_etc = array_arrange($_FILES['image_etc_file']);
					//pre($f_etc);
					foreach($f_etc as $k=>$v) {
						$handle = new upload($v);
						if ($handle->uploaded) {
							$handle->file_name_body_add = '_'.date('YmdHis');
							$file_rs = $handle->process(DOC_ROOT.$image_dir);

							if ($handle->processed) {
								$path = $image_dir.$handle->file_dst_name;

								$sql = "INSERT INTO tblmultiimages (productcode, {$k}) VALUES  ('{$productcode}','{$path}') ON CONFLICT (productcode) DO UPDATE SET {$k}='{$path}'";
								$Product->adodb->Execute($sql);
								$handle->clean();
							} else {
							}
						}
						else {
							echo 'error';
						}
					}
				}
			}

			//승인상태 - 승인상태가 변경된경우 혹은 신규등록인경우 로그저장
			if($_POST['display'] != $_POST['display_old'] || $act == 'insert') {
				$admin_id = $_ShopInfo->id; //최종수정자아이디(관리자)
				$admin_name = $_ShopInfo->name; //최종수정자이름(관리자)

				$record = array(
					'productcode'=>$productcode,
					'admin_id'=>$admin_id,
					'admin_name'=>$admin_name,
					'status_before'=>$_POST['display_old'],
					'status_after'=>$_POST['display'],
					'date_insert'=>TIMESTAMP
				);
				$sql = sqlInsert($record, 'tblproduct_display_log');

				$Product->adodb->Execute($sql);

				//승인날짜 업데이트
				$sql = sqlUpdate(array('selldate'=>TIMESTAMP, 'display'=>$_POST['display']), $tbl, array('productcode'=>$productcode));
				$Product->adodb->Execute($sql);
			}

			//상품정보고시 저장
			$tbl_property = $Product->tbls['product_property'];
			$Product->adodb->Execute("UPDATE {$tbl_property} SET is_delete='Y' WHERE productcode='{$productcode}'");
			$property_success = true;
			
			$property = array_arrange($_POST['property']);
			foreach($property as $v) {
				if($v['idx'] == 'new') {
					$record_property = array(
						'productcode'=>$productcode,
						'name'=>$v['name'],
						'contents'=>$v['conts'],
						'is_delete'=>'N',
						'is_open'=>'Y',
						'date_insert'=>NOW,
						'date_update'=>NOW
					);
					$sql = sqlInsert($record_property, $tbl_property);
				}
				else {
					$record_property = array(
						'name'=>$v['name'],
						'contents'=>$v['conts'],
						'is_delete'=>'N',
						'is_open'=>'Y',
						'date_update'=>NOW
					);
					$sql = sqlUpdate($record_property, $tbl_property, array('productcode'=>$productcode, 'idx'=>$v['idx']));
				}

				$rs_property = $Product->adodb->Execute($sql);
				if(!$rs_property) $property_success = false;
			}

			/*
			if($_POST['property_use'] =='Y') {
			}
			else {
				//$Product->adodb->Execute("DELETE FROM {$tbl_property} WHERE productcode='{$productcode}'");
			}
			*/

			if($property_success) {
				$Product->adodb->Execute("DELETE FROM {$tbl_property} WHERE is_delete='Y' AND productcode='{$productcode}'");
			}


			//옵션 저장
			$tbl_option = $Product->tbls['product_option'];
			
			// pre($option_arr);
			$success_option = true;


			if($_POST['pr_type'] == '4') { //추가구매상품 옵션등록 - 추가구매상품은 옵션등록이 없으므로 1row로 처리
				$option_rs = $Product->getOptionList("productcode='{$productcode}'");
				$option_row = $option_rs[0];

				$option_new = $_POST['option']; //설정값

				$record_option = array(
					'sort'=>1,
					'option_name'=>'추가구매상품',
					'option_color'=>'',
					'option_soldout'=>'N',
					'option_use'=>'Y',
					'delete_yn'=>'N'
				);

				if($option_row) { //수정
					$option_quantity = $option_row['option_quantity_sales']+$option_new['option_quantity']; //옵션별 총재고 = 판매수량(기존) + 현재고수량(설정)
					$record_option['option_quantity'] = $option_quantity;
					$record_option['date_update'] = NOW;

					$where = array(
						'productcode'=>$productcode,
						'option_num'=>$option_row['option_num']
					);

					$sql = sqlUpdate($record_option, $tbl_option, $where);
				}
				else {
					$record_option['productcode'] = $productcode;
					$record_option['option_quantity'] = $option_new['option_quantity']; //옵션별재고
					$record_option['date_insert'] = 'NOW';

					$sql = sqlInsert($record_option, $tbl_option);
				}

				$rs_option = $Product->adodb->Execute($sql);
				if(!$rs_option) {
					return_json(true, '상품정보는 저장되었으나 옵션저장에 실패하였습니다.');
				}
			}
			else {
				if($_POST['option_use']  && is_array($_POST['option'])) {
					$option_arr = array_arrange($_POST['option']);
	
	
					//임시삭제플래그 업데이트
					$Product->adodb->Execute("UPDATE {$tbl_option} SET delete_yn='Y' WHERE productcode='{$productcode}' AND option_quantity_sales<1");
	
					if($option_type == 'C') { 
						$colorchip_pair = $Product->getColorPair(); //컬러칩정보(code=name)
					}
	
					$sort = 0;
					foreach($option_arr as $opt_row) {
						$sort++;
						switch($opt_row['display']) {
							case 'soldout': //품절표시
								$option_use = 'Y'; //표시
								$option_soldout = 'Y'; //품절
								break;
							case 'show': //정상판매중
								$option_use = 'Y'; //표시
								$option_soldout = 'N'; //품절아님
								break;
							case 'hide':
								$option_use = 'N'; //숨김
								$option_soldout = 'N'; //품절아님
								break;
						}
	
						if($option_type == 'C') { //컬러코드인경우 옵션코드(옵션명)값 변경
							$option_color = $opt_row['option_color']; //칼러코드입력
							$option_name = $colorchip_pair[$option_color]; //칼러명입력
						}
						else {
							$option_name = $opt_row['option_name'];
						}
						$record_option = array(
							'sort'=>$sort,
							'option_name'=>$option_name,
							'option_color'=>$option_color,
							'option_soldout'=>$option_soldout,
							'option_use'=>$option_use,
							'delete_yn'=>'N'
						);
	
						if($opt_row['option_num'] == 'new') {
	
							$record_option['productcode'] = $productcode;
							$record_option['option_quantity'] = $opt_row['option_quantity']; //옵션별재고
							$record_option['date_insert'] = 'NOW';
	
							$sql = sqlInsert($record_option, $tbl_option);
						}
						else {
							$option_quantity = $opt_row['option_quantity_sales']+$opt_row['option_quantity']; //옵션별 총재고 = 판매수량 + 현재고수량
							$record_option['option_quantity'] = $option_quantity;
							$record_option['date_update'] = NOW;
	
							$where = array(
								'productcode'=>$productcode,
								'option_num'=>$opt_row['option_num']
							);
	
							$sql = sqlUpdate($record_option, $tbl_option, $where);
						}
					
						$rs_option = $Product->adodb->Execute($sql);
						if(!$rs_option) {
							$success_option = false;
						}
					}
	
					if($success_option) {
						$Product->adodb->Execute("DELETE FROM {$tbl_option} WHERE delete_yn='Y' AND option_quantity_sales<1"); //옵션삭제처리
					}
					else {
						return_json(true, '상품정보는 저장되었으나 옵션저장에 실패하였습니다.');
					}
				}
			}
			

			return_json(true, '저장되었습니다.');

		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
			
	}
	
}
else if($mode == 'category') { //카테고리정보
	if($act == 'get_children') { //자식카테고리 추출
		$children = $category->getChildren($_POST['parent_code']);
		return_json(true, '', $children);
	}
}
else if($mode == 'list') { //상품목록
	if($act == 'batch') { //일괄처리(product_list.php)
		$set_arr = array();
		foreach($_POST['batch'] as $field=>$v) {
			if(!$v) continue;
			$set_arr[] = "{$field} = '{$v}'";
			if($field == 'display') { //승인상태 일괄변경인경우 승인날짜 업데이트
				$set_arr[] = "selldate = '".TIMESTAMP."'";
			}
		}

		$set = implode(', ',$set_arr);
		$sql = "UPDATE tblproduct SET {$set} WHERE productcode IN ('".implode("','",$_POST['checked'])."')";

		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			//승인상태로그 등록
			if($_POST['batch']['display']) {
				$admin_id = $_ShopInfo->id; //최종수정자아이디(관리자)
				$admin_name = $_ShopInfo->name; //최종수정자이름(관리자)
				foreach($_POST['checked'] as $productcode) {
					$record = array(
						'productcode'=>$productcode,
						'admin_id'=>$admin_id,
						'admin_name'=>$admin_name,
						'status_before'=>'batch',
						'status_after'=>$_POST['batch']['display'],
						'date_insert'=>TIMESTAMP
					);
					$sql = sqlInsert($record, $cfg_tbl['product_display_log']);
					$Product->adodb->Execute($sql);
				}
			}
			return_json(true,'변경되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}

	}
	else if($act == 'batch_image') { //이미지 일괄로드
		$dir = "https://guessimg.azureedge.net/images/";
		$image_type = array(
			'maximage'=>'_B',
			'minimage'=>'_M',
			'tinyimage'=>'_M',
			'over_minimage'=>'_O'
		);
		$image_type_etc = array();

		$success = true;
		$productcode = $_POST['productcode'];
		if(!is_array($productcode)) $productcode = array($productcode);
		foreach($productcode as $prcode) {
			$row = $Product->adodb->getRow("SELECT prodcode FROM tblproduct WHERE productcode='{$prcode}'");
			$prodcode = substr(strtoupper($row['prodcode']),0,8);

			$url = DIR_CDN.substr($prodcode,0,2).DIRECTORY_SEPARATOR.$prodcode.DIRECTORY_SEPARATOR.$prodcode;
			$record = array();

			//기본이미지
			foreach($image_type AS $k=>$v) {
				$path = $url.$v.'.jpg';

				/*
				$fp = @fopen($path, "r"); 
				if ($fp === false) continue;
				*/
				$file_header = @get_headers($path);
				//pre($file_header);
				if(strpos($file_header[0],'404')!==false) continue;
				$record[$k] = $path;
			}

			$sql = sqlUpdate($record, $cfg_tbl['product'], array('productcode'=>$prcode));
			$rs = $Product->adodb->Execute($sql);
			if(!$rs) $success = false;

			//기타이미지
			$record = array('productcode'=>$prcode);
			$tbl_etc = $cfg_tbl['product_image'];

			

			foreach(range(2,20) as $v) {
				$path = $url.'_'.$v.'.jpg';

				$file_header = @get_headers($path);
				if(strpos($file_header[0],'404')!==false) $path = '';
				
				
				$k = 'primg'.str_pad($v,'2','0',STR_PAD_LEFT);
				$sql = "INSERT INTO {$tbl_etc} (productcode,{$k}) VALUES ('{$prcode}','{$path}') ON CONFLICT (productcode) DO UPDATE SET {$k}='{$path}'";

				$rs = $Product->adodb->Execute($sql);
				if(!$rs) {
					//echo $sql;
					$success = false;
				}
			}


			if(!$rs) $success = false;
		}
		
		if($rs) {
			return_json(true, '이미지가 저장되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else if($act == 'batch_excel') { //엑셀간편업데이트
		if(!$_FILES['excel']) return_json(false,'엑셀파일을 업로드해주세요.');

		//파일업로드
		include_once $Dir."lib/upload.class.php";
		$dir = DIRECTORY_SEPARATOR.DataDir.'temporary/';

		$handle = new upload($_FILES['excel']);
		if ($handle->uploaded) {
			$handle->file_new_name_body = 'excelupload_'.date('YmdHis');
			$file_rs = $handle->process(DOC_ROOT.$dir);
			if ($handle->processed) {
				$path = $dir.$handle->file_dst_name;
				$handle->clean();
			} else {
				return_json(false,'');
			}
		}


		//엑셀파싱
		//$path = '/data/temporary/excelupload-20180615113648.xlsx'; //기본양식
		//$path = '/data/temporary/excelupload-20180615142659.xlsx'; //커스텀양식

		include $Dir.'lib/PHPExcel/Classes/PHPExcel.php';
		$obj = PHPExcel_IOFactory::load(DOC_ROOT.$path);
		$sheet_data = $obj->getSheet(0)->toArray(null,true,true,true);
		$header = array_shift($sheet_data);
		$cnt_data = count($sheet_data);
		if($cnt_data > 1000) {
			return_json(false,'한번에 업데이트할수 있는 상품수는 최대 1000개입니다.');
		}


		$format = array(
			'ERP 코드'=>array('column'=>'prodcode','tbl'=>'product'),
			'쇼핑몰상품명'=>array('column'=>'productname','tbl'=>'product', 'default'=>''),
			'판매가'=>array('column'=>'sellprice','tbl'=>'product','default'=>''),
			'승인상태'=>array('column'=>'display','tbl'=>'product', 'default'=>'R'),
			'판매상태'=>array('column'=>'soldout','tbl'=>'product', 'default'=>'S'),
			'상품이미지 대'=>array('column'=>'maximage','tbl'=>'product', 'default'=>''),
			'상품이미지 중'=>array('column'=>'minimage','tbl'=>'product', 'default'=>''),
			'상품이미지 소'=>array('column'=>'tinyimage','tbl'=>'product', 'default'=>''),
			'리스트 롤오버 이미지'=>array('column'=>'over_minimage','tbl'=>'product', 'default'=>''),
			'기타이미지2'=>array('column'=>'primg02','tbl'=>'product_image', 'default'=>''),
			'기타이미지3'=>array('column'=>'primg03','tbl'=>'product_image', 'default'=>''),
			'기타이미지4'=>array('column'=>'primg04','tbl'=>'product_image', 'default'=>''),
			'기타이미지5'=>array('column'=>'primg05','tbl'=>'product_image', 'default'=>''),
			'기타이미지6'=>array('column'=>'primg06','tbl'=>'product_image', 'default'=>''),
			'기타이미지7'=>array('column'=>'primg07','tbl'=>'product_image', 'default'=>''),
			'기타이미지8'=>array('column'=>'primg08','tbl'=>'product_image', 'default'=>''),
			'기타이미지9'=>array('column'=>'primg09','tbl'=>'product_image', 'default'=>''),
			'기타이미지10'=>array('column'=>'primg10','tbl'=>'product_image', 'default'=>''),
			'네이버 지식쇼핑'=>array('column'=>'naver_display','tbl'=>'product', 'default'=>'N'),
			'퀵배송 가능여부'=>array('column'=>'o2o_yn','tbl'=>'product','default'=>'N'),
			//'임직원할인율 적용'=>array('column'=>'staff_dc_yn','tbl'=>'product', 'default'=>'N'),
			'최소구매수량'=>array('column'=>'min_quantity','tbl'=>'product', 'default'=>'1'),
			'최대구매수량'=>array('column'=>'max_quantity','tbl'=>'product','default'=>'-1')
			//'쿠폰사용불가'=>array('column'=>'except_coupon','tbl'=>'product','default'=>'N'),
			//'상세동영상사용'=>array('column'=>'movie_yn','tbl'=>'product', 'default'=>'N'),
			//'상세동영상URL'=>array('column'=>'movie_url','tbl'=>'product', 'default'=>''),
			//'소재정보ERP연동'=>array('column'=>'material_type', 'tbl'=>'product','default'=>''),
			//'소재정보'=>array('column'=>'material', 'tbl'=>'product','default'=>''),
			//'실측사이즈'=>array('column'=>'size_reference','tbl'=>'product','detaul'=>'')
		);


		$success = true;


		//ERP코드값 유무체크
		$unique_key = key($format);
		if(!in_array($unique_key, $header)) {
			return_json(false, "[$unique_key]가 누락되었습니다.", array());
		}

		$format_custom = array();
		foreach($header as $k=>$fname) {
			if(!$fname) continue;
			if(array_key_exists($fname, $format) === false) {
				$success = false;
				$msg = "[{$fname}]필드명이 유효하지 않습니다.";
				break;
			}

			$format_custom[$k] = $format[$fname];
			$format_custom[$k]['name'] = $fname;
		}

		$error = array();
		foreach($sheet_data as $k=>$v) {
			$record = array();

			$prodcode = array_shift($v); //A행데이터는 ERP코드
			$productcode = $Product->adodb->getOne("SELECT productcode FROM ".$Product->tbls['product']." WHERE prodcode='{$prodcode}'");
			if(!$productcode) { //상품코드가 없는경우
				$error['A'.$k] = 'ERP코드 오류';
				continue;
			}

			foreach($v as $k2=>$v2) {
				if(array_key_exists($k2, $format_custom) === false) continue;

				extract($format_custom[$k2]);
				if($column == 'productname' && !$v2) {
					$error[$k2.($k+2)] = '['.$name.']항목은 빈칸일수 없습니다.';
					continue 2;
				}

				if($column == 'sellprice' && (!$v2 || $v2<1)) {
					$error[$k2.($k+2)] = '['.$name.']항목값이 빈칸이거나 1보다 작습니다.';
					continue 2;
				}

				if($column == 'size_reference') {
					if(mb_substr($v2,0,3) !='사이즈') {
						$error[$k2.($k+2)] = '['.$name.']실측사이즈 정보 입력시 첫번째는 무조건 "사이즈" 정보가 입력되어야 합니다.';
						continue 2;
					}

					$size_reference = array();
					$size_row = explode('^',$v2);
					foreach($size_row as $size) {
						list($label, $size_value) = explode(':', $size);
						$size_reference[] = array('label'=>$label, 'value'=>$size_value);
					}
					$v2 = serialize($size_reference);
				}

				if($column == 'material_type') {
					$v2 = ($v2 == 'Y')?'ERP':'DIRECT';	
				}

				$v2 = trim($v2);
				if(!$v2) $v2 = $default;
				
				$record[$tbl][$column] = $v2;

			}


			//상품정보업데이트
			if($record['product']){
				$product_sql = sqlUpdate($record['product'], $Product->tbls['product'], array('productcode'=>$productcode));
				//echo $product_sql;exit;
				$product_rs = $Product->adodb->Execute($product_sql);

				if(!$product_rs) {
					$error[$k] = '업데이트 오류';
					continue;
				}
			}
			

			//이미지정보업데이트
			if($record['product_image']) {
				$product_image_sql = sqlUpdate($record['product_image'], $Product->tbls['product_image'], array('productcode'=>$productcode));
				//echo $product_image_sql;
				$product_image_rs = $Product->adodb->Execute($product_image_sql);
				if(!$product_image_rs) {
					$error[$k] = '업데이트 오류';
					continue;
				}
			}
		}

		//@unlink(DOC_ROOT.$path);//EXCEL파일 삭제
		//파일로그작성
		
		if($success) {
			//$Product->sync();

			
			$cnt_error = count($error);
			$cnt_success = $cnt_data-$cnt_error;

			$result = array('cnt'=>array('total'=>$cnt_data,'success'=>$cnt_success, 'error'=>$cnt_error), 'error'=>$error);
			$Product->log('상품일괄간편수정',serialize($result),__FILE__.':line '.__LINE__,$path);

			return_json(true,'일괄 간편수정이 완료되었습니다.',$result);
		}
		else {
			$Product->log('상품일괄간편수정','failure',__FILE__.':line '.__LINE__,$path);
			return_json(false,$msg);
		}
	}
}
else if($mode == 'timesale') { //기간할인
	$tbl = 'tblproduct_timesale';
	if($act == 'register') {

		$idx = $_POST['idx'];
		$productcode_arr = array_unique(array_filter(explode(',',$_POST['productcode']))); //상품중복제거
		$productcodes = implode(',',$productcode_arr);
		$sale_rate = $_POST['sale_rate'];
		$date_start = $_POST['date_start'].' 00:00:00';
		$date_end = $_POST['date_end'].' 23:59:59';
		$sale_subject = $_POST['sale_subject'];
		$sale_week = implode(',',$_POST['sale_week']);
		$sale_rate_type = $_POST['sale_rate_type'];
		$display = $_POST['display'];
		$admin_id = $_ShopInfo->id; //최종수정자아이디(관리자)


		$record = array(
			'sale_rate'=>$sale_rate, 
			'date_start'=>$date_start, 
			'date_end'=>$date_end, 
			'productcodes'=>$productcodes, 
			'date_update'=>NOW, 
			'admin_id'=>$admin_id,
			'sale_subject'=>$sale_subject,
			'sale_week'=>$sale_week,
			'sale_rate_type'=>$sale_rate_type,
			'display'=>$display
		);

		if($idx) {
			$sql = sqlUpdate($record, $tbl, array('idx'=>$idx));
		}
		else {
			$record['date_insert'] = NOW;
			$sql = sqlInsert($record, $tbl);
		}

		$rs = $Product->adodb->Execute($sql);

		if($rs) {
			$Product->sync_timesale();//동기화
			return_json(true, '저장되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else if($act == 'delete') {
		$idx = $_POST['idx'];
		$sql = "DELETE FROM {$tbl} WHERE idx='{$idx}'";
		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			//$Product->sync_timesale();//동기화
			return_json(true, '삭제되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
}
else if($mode == 'staff') { //임직원
	if($act == 'sale') { //임직원 할인율 적용

		$tbl = 'tblconfig';
		$staff_dc_rate = $_POST['staff_dc_rate'];

		$sql = "UPDATE {$tbl} SET field_value='{$staff_dc_rate}' WHERE field='staff_dc_rate'";
		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			return_json(true, '적용되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}

	}
}
else if($mode == 'batch') {

	if(is_array($_POST['checked'])) {
		$productcode_arr = array_unique(array_filter($_POST['checked'])); //선택상품중복제거
		$productcodes = implode("','",$productcode_arr);
	}
	

	//아이콘 일괄변경
	if($act == 'icon') {
		$tbl = 'tblproduct';
		if(empty($_POST['icon'])) {
			$icon = '';
		}
		else {
			$icon = implode(',',array_unique($_POST['icon']));
		}


		$sql = "UPDATE {$tbl} SET icon='{$icon}' WHERE productcode IN('{$productcodes}')";
		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			return_json(true, '적용되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}

	//카테고리 일괄변경
	else if($act == 'category') {
		$tbl = 'tblproductlink';
		$cmd = $_POST['cmd'];


		$c_category = array_pop(array_filter($_POST['category']));
		$c_date = date('YmdHis');
		$c_maincate = 1;
		$success = true;
		if($cmd == 'add') { //카테고리추가(상품복사)
			foreach ($productcode_arr as $prcode) {
				$sql = "INSERT INTO {$tbl} (c_productcode, c_category, c_maincate, c_date, c_date_1, c_date_2, c_date_3, c_date_4) VALUES ('{$prcode}','{$c_category}','{$c_maincate}', '{$c_date}','{$c_date}','{$c_date}','{$c_date}','{$c_date}') ON CONFLICT (c_productcode, c_category) DO UPDATE SET c_maincate='{$c_maincate}', c_date='{$c_date}', c_date_1='{$c_date}', c_date_2='{$c_date}', c_date_3='{$c_date}', c_date_4='{$c_date}'";
				//echo $sql;exit;
				$rs = $Product->adodb->Execute($sql);
				if(!$rs) $success = false;
			}
		}
		else {
			foreach ($productcode_arr as $prcode) {
				$sql = "DELETE FROM {$tbl} WHERE c_productcode='{$prcode}'";
				$deleted = $Product->adodb->Execute($sql);
				if($deleted) {
					$sql = "INSERT INTO {$tbl} (c_productcode, c_category, c_date, c_date_1, c_date_2, c_date_3, c_date_4) VALUES('{$prcode}','{$c_category}','{$c_date}','{$c_date}','{$c_date}','{$c_date}','{$c_date}')";
					$rs = $Product->adodb->Execute($sql);
					if(!$rs) $success = false;
				}
			}
		}

		if($success) {
			return_json(true, '적용되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}

	//배송비 일괄변경
	else if($act == 'delivery') {
		extract($_POST['batch']);
		$tbl = 'tblproduct';
		if($deli == 2) {
			if($deli_select != 2) $deli_qty = '0';
		}
		else {
			$deli_select = $deli_price = $deli_qty = '0';
		}

		$sql = "UPDATE {$tbl} SET deli='{$deli}', deli_select='{$deli_select}', deli_price='{$deli_price}', deli_qty='{$deli_qty}' WHERE productcode IN('{$productcodes}')";
		$rs = $Product->adodb->Execute($sql);

		if($rs) {
			return_json(true, '적용되었습니다.');
		}
		else {
			//echo $sql;
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else if($act == 'reserve') {
		$option = $_POST['option'];
		if(!is_array($option)) return_json(false,'잠시 후에 다시 시도해주세요.');
		$tbl = $cfg_tbl['product_option'];

		$success = true;
		BeginTrans();
		foreach($option as $option_num => $row) {
			$where = array('option_num'=>$option_num);
			$record = array(
				'reserve_yn'=>(isset($row['reserve_yn']))?$row['reserve_yn']:'N',
				'reserve_quantity'=>$row['reserve_quantity'],
				'option_soldout'=>(isset($row['option_soldout']))?$row['option_soldout']:'N'
			);
			$sql = sqlUpdate($record, $tbl, $where);
			$rs = $Product->adodb->Execute($sql);
			if(!$rs) {
				$success = false;
				echo $sql;
			}
		}

		if($success) {
			CommitTrans();
			return_json(true,'저장되었습니다.');
		}
		else {
			RollbackTrans();
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
		
	}
	else if($act == 'reserve_excel') {
		if(!$_FILES['excel']) return_json(false,'엑셀파일을 업로드해주세요.');

		//파일업로드
		include_once $Dir."lib/upload.class.php";
		$dir = DIRECTORY_SEPARATOR.DataDir.'temporary/'.date('Ymd').'/';

		$handle = new upload($_FILES['excel']);
		if ($handle->uploaded) {
			$handle->file_new_name_body = 'reserve_'.date('YmdHis');
			$file_rs = $handle->process(DOC_ROOT.$dir);
			if ($handle->processed) {
				$path = $dir.$handle->file_dst_name;
				$handle->clean();
			} else {
				return_json(false,'');
			}
		}


		//엑셀파싱
		//$path = '/data/temporary/excelupload-20180615113648.xlsx'; //기본양식
		//$path = '/data/temporary/excelupload-20180615142659.xlsx'; //커스텀양식
		
		include $Dir.'lib/PHPExcel/Classes/PHPExcel.php';
		$obj = PHPExcel_IOFactory::load(DOC_ROOT.$path);
		$sheet_data = $obj->getSheet(0)->toArray(null,true,true,true);
		$header = array_shift($sheet_data);
		$cnt_data = count($sheet_data);
		if($cnt_data > 1000) {
			return_json(false,'한번에 업데이트할수 있는 데이터수는 최대 1000개입니다.');
		}


		$format = array(
			'ERP 코드'=>array('column'=>'prodcode','tbl'=>''),
			'컬러'=>array('column'=>'option_color','tbl'=>'', 'default'=>''),
			'사이즈'=>array('column'=>'option_name','tbl'=>'','default'=>''),
			'예약판매 여부'=>array('column'=>'reserve_yn','tbl'=>'product_option', 'default'=>'N'),
			'예약판매 재고'=>array('column'=>'reserve_quantity','tbl'=>'product_option','default'=>''),
			'품절'=>array('column'=>'option_soldout','tbl'=>'product_option', 'default'=>'N')
		);


		$success = true;


		//ERP코드값 유무체크
		$unique_key = array('ERP 코드','컬러','사이즈');
		$diff = array_diff($unique_key, $header);
		if(count($diff)>0) {
			return_json(false, "필수값[".implode(',',$diff)."]이 누락되었습니다.", array());
		}

		

		$format_custom = array();
		foreach($header as $k=>$fname) {
			if(!$fname) continue;
			if(array_key_exists($fname, $format) === false) {
				$success = false;
				$msg = "[{$fname}]필드명이 유효하지 않습니다.";
				break;
			}

			$format_custom[$k] = $format[$fname];
			$format_custom[$k]['name'] = $fname;
		}

		$error = array();

		

		foreach($sheet_data as $k=>$v) {
			//$k = $key+1;
			$record = array();
			

			$prodcode = array_shift($v); //A행데이터는 ERP코드
			$option_color = array_shift($v); //B행데이터는 옵션(컬러)
			$option_size = array_shift($v); //C행데이터는 옵션(사이즈)
			$productcode = $Product->adodb->getOne("SELECT productcode FROM ".$Product->tbls['product']." WHERE prodcode='{$prodcode}'");
		
			if(!$productcode) { //상품코드가 없는경우
				$error['A'.($k+1)] = 'ERP코드 오류';
				continue;
			}

			$option_num = $Product->adodb->getOne("SELECT option_num FROM ".$Product->tbls['product_option']." WHERE productcode='{$productcode}' AND option_name='{$option_size}' AND option_color='{$option_color}'");
			if(!$option_num) { //상품코드가 없는경우
				$error['B'.($k+1)] = '옵션정보 오류(존재하지 않는 옵션정보입니다.)';
				continue;
			}


			foreach($v as $k2=>$v2) {
				if(array_key_exists($k2, $format_custom) === false) continue;

				extract($format_custom[$k2]);
				if(!$tbl) continue;

				if($column == 'reserve_quantity' && (!$v2 || $v2<1)) {
					$error[$k2.($k+2)] = '['.$name.']항목값이 빈칸이거나 1보다 작습니다.';
					continue;
				}

				$v2 = trim($v2);
				if(!$v2) $v2 = $default;
				
				$record[$tbl][$column] = $v2;
			}


			//옵션정보업데이트
			if($record['product_option']) {
				$product_option_sql = sqlUpdate($record['product_option'], $Product->tbls['product_option'], array('option_num'=>$option_num));
				//echo $product_option_sql;
				$product_option_rs = $Product->adodb->Execute($product_option_sql);
				if(!$product_option_rs) {
					$error[$k] = '업데이트 오류';
					continue;
				}
			}
		}

		
		//@unlink(DOC_ROOT.$path);//EXCEL파일 삭제
		//파일로그작성
		
		if($success) {
			$cnt_error = count($error);
			$cnt_success = $cnt_data-$cnt_error;
			

			$result = array('cnt'=>array('total'=>$cnt_data,'success'=>$cnt_success, 'error'=>$cnt_error), 'error'=>$error);
			$Product->log('예약판매일괄수정',serialize($result),__FILE__.':line '.__LINE__,$path);

			return_json(true,'예약재고수정이 완료되었습니다.',$result);
		}
		else {
			$Product->log('예약판매일괄수정','failure',__FILE__.':line '.__LINE__,$path);
			return_json(false,$msg);
		}

	}
}else if($mode == 'product_line') { //라인설정

	$tbl = 'tblproduct_line';
	if($act == 'line'){
		$line_idx = $_POST['line_idx'];
		$line_name = $_POST['line_name'];
		$line_code = $_POST['line_code'];
		$display = $_POST['display'];
		//print_r($_POST);exit;
		if ($line_idx > 0) {
			//기존정보
			$sql = "UPDATE {$tbl} SET line_name='{$line_name}', display='{$display}' WHERE idx='{$line_idx}'";
			//echo $sql;exit;
			$rs = $Product->adodb->Execute($sql);
			$msg = '수정되었습니다.';
		} else {
			$line_code_cnt = $Product->adodb->getOne("SELECT count(line_code) AS cnt FROM {$tbl} WHERE line_code='{$line_code}'");

			if ($line_code_cnt > 0) { //라인코드가 있는경우
				return_json(false, '중복 된 라인코드가 있습니다.');
			}
			$sql = "INSERT INTO {$tbl} (line_name, line_code, display, date_insert) VALUES('{$line_name}','{$line_code}','{$display}', NOW() )";
			//echo $sql;exit;
			$rs = $Product->adodb->Execute($sql);
			$line_idx = $Product->adodb->insert_Id();
			$msg = '등록되었습니다.';
		}
		if ($rs) {
			return_json(true, $msg);
		} else {
			return_json(false, '잠시 후에 다시 시도해주세요.');
		}

	}else if($act == 'delete') {
		$line_code = $_POST['line_code'];
		$line_code_cnt = $Product->getLineCode($line_code);
		if($line_code_cnt > 0){
			return_json(false,'라인 적용 상품이 1개 이상일 경우 삭제 불가능합니다. 상품목록에서 라인 적용 해지 후 삭제해주세요.');
		}
		$sql = "DELETE FROM {$tbl} WHERE idx='{$line_code}'";
		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			//$Product->sync_timesale();//동기화
			return_json(true, '삭제되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
}
else if($mode == 'batch_price') { //가격일괄변경
	$tbl = $Product->tbls['product'];
	$sale_type = $_POST['sale_type'];
	$pr_type = $_POST['pr_type'];
	if($sale_type == 'all') {
		$sale_rate = (100-$_POST['sale_all'])/100;
		$sql = "UPDATE {$tbl} SET sellprice=(consumerprice*$sale_rate) WHERE pr_type='{$pr_type}'";
		$rs = $Product->adodb->Execute($sql);
	}
	else {
		pre($_POST);
	}

	if($rs) {
		//기간할인 적용
		//일괄가격할인 로그
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode == 'config') {
	if($act == 'refresh') {
		$rs = $Product->setConfig('product','refresh', time());
		if($rs) return_json(true,'새로고침되었습니다.');
		else return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}

?>
