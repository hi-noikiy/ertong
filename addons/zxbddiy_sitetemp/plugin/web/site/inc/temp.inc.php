<?php 
	global $_W,$_GPC;

	if(checksubmit('import')) {
		set_time_limit(0);

		$uploadfile = refile( $_FILES );
		
		if (empty($uploadfile)) itoast('请选择要导入的文件！','','error');
		
		$handle = fopen($uploadfile, 'r'); 
		
		$content = fread( $handle,99999999 );
		fclose($handle); //关闭指针

		if( empty( $content ) )  itoast('请上传正确的模板文件','','error');
		$data = iunserializer( $content );
		if( !is_array( $data ) )  itoast('请上传正确的模板文件','','error');


		load()->func('file');
		load()->func('communication');
		load()->model('material');

		if( !empty( $data['temp'] ) ) {
			$tdata = array(
				'uniacid' => $_W['uniacid'],
				'createtime' => TIMESTAMP,
				'name' => $data['temp']['name'],
				'type' => $data['temp']['type'],
				'img' => $data['temp']['img'],
				'showqr' => $data['temp']['showqr'],
			);

			if( !empty( $data['temp']['img'] ) ) {
				$url = saveImage( $data['temp']['img'] );
				if( $url ) $tdata['img'] = $url;
			}
			if( !empty( $data['temp']['showqr'] ) ) {
				$url = saveImage( $data['temp']['showqr'] );
				if( $url ) $tdata['showqr'] = $url;
			}

			$res = pdo_insert('zxbddiy_sitetemp_temp',$tdata);
			if( $res ) {
				$tid = pdo_insertid();
			}else{
				itoast('导入失败','','error');
			}

		}else{
			itoast('模板数据不正确','','error');
		}


		if( !empty( $data['bar'] ) ) {

			$bdata = array(
				'uniacid' => $_W['uniacid'],
				'createtime' => TIMESTAMP,
				'data' => $data['bar']['data'],
				'tempid' => $tid,
			);
			if( !empty( $bdata['data'] ) ) {
				$bardata = iunserializer( $bdata['data'] );
				if( !empty( $bardata ) ) {
					foreach ( $bardata['data'] as &$v ) {

						if( !empty( $v['img'] ) ) {
							$url = saveImage( $v['img'] );
							if( $url ) $v['img'] = $url;
						}
						if( !empty( $v['actimg'] ) ) {
							$url = saveImage( $v['actimg'] );
							if( $url ) $v['actimg'] = $url;
						}

					}
					unset( $v );
					$bdata['data'] = iserializer( $bardata );
				}
			}
			pdo_insert('zxbddiy_sitetemp_bar',$bdata);
		}
		
		if( !empty( $data['page'] ) ) {
			foreach ($data['page'] as $v) {
				
				$pdata = array(
					'uniacid' => $_W['uniacid'],
					'createtime' => TIMESTAMP,
					'name' => $v['name'],
					'tempid' => $tid,
				);

				if( !empty( $v['params'] ) ) {
					$params = iunserializer( $v['params'] );
					if( !empty( $params ) ) {

						if( !empty( $params['basic'] ) ) {
							if( !empty( $params['basic']['shareimg'] ) ) {
								$url = saveImage( $params['basic']['shareimg'] );
								if( $url ) $params['basic']['shareimg'] = $url;
							}

							if( !empty( $params['basic']['bgimg'] ) ) {
								$url = saveImage( $params['basic']['bgimg'] );
								if( $url ) $params['basic']['bgimg'] = $url;
							}

							if( !empty( $params['basic']['fimg'] ) ) {
								$url = saveImage( $params['basic']['fimg'] );
								if( $url ) $params['basic']['fimg'] = $url;
							}

							if( !empty( $params['basic']['fimagesdata'] ) ) {

								foreach ($params['basic']['fimagesdata'] as &$vv) {
									if( !empty( $vv['url'] ) ) {
										$url = saveImage( $vv['url'] );
										if( $url ) $vv['url'] = $url;
									}
								}
								unset( $vv );
							}
						}

						if( !empty( $params['data'] ) ) {
							
							foreach ($params['data'] as &$vv) {
								
								// 数组图片
								if( in_array($vv['name'], array('nav','slide','image','list')) ) {
									if( !empty( $vv['params']['data'] ) ) {
										foreach ($vv['params']['data'] as $kkk => $vvv) {
											
											if( !empty( $vvv['img'] ) ) {
												$url = saveImage( $vvv['img'] );
												if( $url ) $vv['params']['data'][$kkk]['img'] = $url;
											}


											// 打开图片
											if( !empty( $vvv['imagesdata'] ) ) {
												foreach ($vvv['imagesdata'] as $kkkk => $vvvv) {
													if( !empty( $vvvv['url'] ) ) {
														$url = saveImage( $vvvv['url'] );
														if( $url ) $vv['params']['data'][$kkk]['imagesdata'][$kkkk]['url'] = $url;
													}
												}
											}

										}
									}
								}

								// 背景图片
								if( in_array($vv['name'], array('article','image','nav','card','title','form','btn','toshop','appoint')) ) {

									if( !empty( $vv['params']['bgimg'] ) ) {
										$url = saveImage( $vv['params']['bgimg'] );
										if( $url ) $vv['params']['bgimg'] = $url;
									}
								}

								// 打开图片
								if( in_array($vv['name'], array('card','title','fix','toshop','btn')) ) {
									if( !empty( $vv['params']['imagesdata'] ) ) {
										foreach ($vv['params']['imagesdata'] as $kkk => $vvv) {
											if( !empty( $vvv['url'] ) ) {
												$url = saveImage( $vvv['url'] );
												if( $url ) $vv['params']['imagesdata'][$kkk]['url'] = $url;
											}
										}
									}

									if( $vv['name'] == 'btn' ) {
										if( !empty( $vv['params']['data'][0] ) ) {
											foreach ($vv['params']['data'][0] as $vvvv) {
												if( !empty( $vvvv['url'] ) ) {
													$url = saveImage( $vvvv['url'] );
													if( $url ) $vv['params']['data'][0]['url'] = $url;
												}
											}
										}
									}
								}


								if( $vv['name'] == 'title' ) {
									if( !empty( $vv['params']['leftimg'] ) ) {
										$url = saveImage( $vv['params']['leftimg'] );
										if( $url ) $vv['params']['leftimg'] = $url;
									}
									if( !empty( $vv['params']['fontimg'] ) ) {
										$url = saveImage( $vv['params']['fontimg'] );
										if( $url ) $vv['params']['fontimg'] = $url;
									}
									if( !empty( $vv['params']['rightimg'] ) ) {
										$url = saveImage( $vv['params']['rightimg'] );
										if( $url ) $vv['params']['rightimg'] = $url;
									}									
								}

								if( $vv['name'] == 'video' ) {
									if( !empty( $vv['params']['thumb'] ) ) {
										$url = saveImage( $vv['params']['thumb'] );
										if( $url ) $vv['params']['thumb'] = $url;
									}
								}

								if( $vv['name'] == 'fix' ) {
									if( !empty( $vv['params']['img'] ) ) {
										$url = saveImage( $vv['params']['img'] );
										if( $url ) $vv['params']['img'] = $url;
									}
								}

							}
							unset( $vv );
						}

					}
				}
				$pdata['params'] = iserializer( $params );
				pdo_insert('zxbddiy_sitetemp_page',$pdata);

			}
		}
		itoast('已经导入','','success');
	}

	function saveImage($url){
		global $_W;

		$material = material_network_to_local($url, $_W['uniacid'], $_W['uid'], 'image');
		if (!is_error($material)) {
			return $material['url'];
		}else{
			return false;
		}
	}

	model_temp::initTemp();
	$tempsort = model_tempsort::getSort();
	
	if( $_GPC['op'] == 'my' || $_GPC['op'] == 'sys' || $_GPC['op'] == 'module' ){
		$set = model_sysset::getSet();
				
		if( $_GPC['op'] == 'my' ){
			$topbar = topbal::mytempList();

			$where = array('uniacid'=>$_W['uniacid'],'issystem'=>0);

			if( !empty( $_GPC['tname'] ) ) $where['name@'] = $_GPC['tname'];

			$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_temp',$where,$_GPC['page'],8,' `isact` DESC ,`issystem` ASC,`number` DESC ',false,true,' * ');
		
		}elseif( $_GPC['op'] == 'sys' ){

			$topbar = topbal::tempList();

			$page = intval( $_GPC['page'] ) > 0 ? intval( $_GPC['page'] ) : 0;
			$sortid = intval( $_GPC['csort'] ) > 0 ? intval( $_GPC['csort'] ) : 0;
			
			$cloudlist = model_cloud::getTemp($page,$sortid,'list');
			$cloudsort = $cloudlist['status'] == 200 && !empty( $cloudlist['sortlist'] ) ? $cloudlist['sortlist'] : false;
			
			
			if( $cloudlist['status'] != 200 ){
				
	             true;
				
			}

			if( empty( $_GPC['csort'] ) ) {

				$auth = model_auth::authList();
				$where = array('issystem'=>1,'issetsystem'=>1);
				if( !empty( $_GPC['sort'] ) ) $where['sort'] = intval( $_GPC['sort'] );

				$str = ' AND ( `type` = 0 ';
				if( !empty( $auth['isshop'] ) ) $str .= ' OR `type` = 1 ';
				if( !empty( $auth['isappoint'] ) ) $str .= ' OR `type` = 2 ';
				if( !empty( $auth['isdesk'] ) ) $str .= ' OR `type` = 3 ';
				$str .= ')';

				$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_temp',$where,$_GPC['page'],8,' `isact` DESC ,`issystem` ASC,`number` DESC ',false,true,' * ',$str,'',false);

			}elseif( $cloudlist['status'] == 200 ){	// 云模板
				
				$list = array();
				foreach ($cloudlist['data'] as $k => $v) {
					$list[] = array(
						'id' => $v['id'],
						'uniacid' => 0,
						'name' => $v['name'],
						'img' => $v['thumb'],
						'issystem' => 1,
						'issetsystem' => 1,
						'iscloud' => 1,
					);
				}
				

			}

			
			/*if( empty( $auth['isshop'] ) ) {
				foreach ( $info[0] as $k => $v ) {
					if( $v['type'] == 1 ) {
						unset( $info[0][$k] );
					}
				}
			}*/


		}elseif( $_GPC['op'] == 'module' ){ // 模块模板

			if( $_W['role'] != 'founder' ) die;
			
			/*$where = array('issystem'=>1,'issetsystem'=>0);
			$info = Util::getAllDataInSingleTable('zxbddiy_sitetemp_temp',$where,$_GPC['page'],8,' `isact` DESC ,`issystem` ASC,`number` DESC ',false,true,' * ','','',false);*/

			$all = model_systemp::getTemp();
			$sortlist = $all['sortlist'];
			$num = $all['num'];
			$list = $all['temp'];

			$pager = pagination($num, $_GPC['page'], 8);

		}
		
		if( $_GPC['op'] != 'module' && empty( $_GPC['csort'] ) ) {

			$list = $info[0];
			$pager = $info[1];

			if( !empty( $list ) ) {
				foreach ( $list as $k => &$vv ) {
					$vv['urlimg'] = $this->createWebUrl('img',array('op'=>'temp','tid'=>$vv['id']));
				}
				unset( $vv );
			}

		}

	}
	
	if($_GPC['op'] == 'delete'){

		$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['id']));
		if( $temp['issystem'] == 1 ) itoast('此模板不能删除','','error');
		if( $temp['isact'] == 1 ) itoast('使用中的模板不能删除','','error');

		$res = WebCommon::deleteSingleData($_GPC['id'],'zxbddiy_sitetemp_temp');
		if($res) itoast('删除成功','','success');
	}

	if($_GPC['op'] == 'down'){

		if( $_W['role'] != 'founder' ) die;

		$temp = pdo_get('zxbddiy_sitetemp_temp',array('id'=>$_GPC['tid']));
		if( empty($temp) ) itoast('模板不存在','','error');
		$temp['img'] = tomedia( $temp['img'] );
		$temp['showqr'] = tomedia( $temp['showqr'] );

		$bar = pdo_get('zxbddiy_sitetemp_bar',array('tempid'=>$_GPC['tid']));
		$page = pdo_getall( 'zxbddiy_sitetemp_page',array('tempid'=>$_GPC['tid']) );

		$data = array(
			'temp' => $temp,
			'bar' => $bar,
			'page' => $page,
		);

		$data = iserializer( $data );

		Header( "Content-type:  application/octet-stream "); 
		Header( "Accept-Ranges:  bytes "); 
		header( "Content-Disposition:  attachment;  filename= {$temp['name']}.temp"); 
		//readfile($data);
		echo $data;

		die;
	}

	
	function refile($f){
		return $f['inputfile']['tmp_name'];
	}


	include $this->pTemplate('temp');