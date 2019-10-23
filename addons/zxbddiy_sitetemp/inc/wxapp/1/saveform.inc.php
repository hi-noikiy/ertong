<?php 
	defined('IN_IA') or exit('Access Denied');
	global $_W,$_GPC;

	if( $_W['ispost'] ) {

		$content = htmlspecialchars_decode( $_GPC['data'] );

		$data = json_decode( $content,true );

		if( empty( $data ) ) $this->result(1,'请提交数据');

		foreach ( $data as $k => $v ) {
			$item = json_decode( $v,true );
			if( is_array( $item ) ) {
				$type = '';
				$list = array();
				foreach ($item as $vv) {
					if( !empty($vv['att']) ) {
						$type = 'img';
						$list[] = $vv['att'];
					}
				}
				$data[$k] = array(
					'type' => $type,
					'list' => $list,
				);
			}
		}

		$indata = array(
			'uniacid' => $_W['uniacid'],
			'data' => iserializer( $data ),
			'createtime' => TIMESTAMP
		);
		
		$res = pdo_insert('zxbddiy_sitetemp_form',$indata);

		if( $res ){

			// 给管理员发消息
			if( !empty( $this->module['config']['mail'] ) ) {
				$body = array('有人提交了一项表单数据，请查收');
				if( !empty( $data ) ){
					foreach ( $data as $k => $v ) {
						if( is_array( $v ) ){
							if( $v['type'] == 'img' ) {
								$item = $k.'：(图片数据请在后台查看)';
							}else{
								$in = '';
								foreach ($v as $vv ) {
									$in .= $vv.'，';
								}
								$in = trim($in,'，');
								$item = $k.'：'.$in;
							}
						}else{
							$item = $k.'：'.$v;
						}
						$body[] = $item;
					}
				}
				Util::ihttp_email($this->module['config']['mail'], '表单通知', $body);
			}
			
			$this->result(0, '已提交');
		}

		
	}


	
	
	$this->result(1, '提交失败');