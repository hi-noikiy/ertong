<?php



class model_cart {

	public $openid;
	public $cartArray = array();
	
	
	public function __construct( $openid ) {
		global $_W;
		$this->openid = $openid;

		$cart = self::getCart( $openid );
		$this->cartArray = $cart;
	}
	

	public function addCart($id,$num,$ruleid) {
		global $_W;
		if ($this->checkItem($id,$ruleid)) { // 检测商品是否存在
			$this->modifyCart($id,$num,$ruleid,'add'); // 已存在修改
			return true;
		}

		$cart = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $this->openid,
			'gid' => $id,
			'num' => $num,
			'ruleid' => $ruleid,
		);
		$res = pdo_insert('zxbddiy_sitetemp_cart',$cart);
		$id = pdo_insertid();
		if( $res ){
			$cart['id'] = $id;
			array_push($this->cartArray, $cart);
			Util::deleteCache( 'cart', $this->openid );
		}

	}
	
	//批量删除，不能使用modifyCart循环批量删除，因为cookie需要刷新一次获取一次，如果循环删除始终获取的是初始的值，而不是修改后的值
	public function deleteManyCart($idarray){
		if(!is_array($idarray)) return false;
		$this->cartArray = $this->cartView(); // 把数据读取并写入数组		
		foreach ($this->cartArray as $item) {
			foreach($idarray as $k => $v){
				if($item['id'] == $v) unset($this->cartArray[$v]);
			}
		}
		return $this->save();
	}	
	
	
	static function getCart( $openid ){
		global $_W;
		
		$cache = Util::getCache('cart',$openid);
		if( empty( $cache ) ){
			$where = array('uniacid'=>$_W['uniacid'],'openid'=>$openid);
			$cache = pdo_getall('zxbddiy_sitetemp_cart',$where);
			
			if( !empty( $cache ) ){
				Util::setCache('cart',$openid,$cache);
			}
		}
		return $cache;
	}

	/**
	 * 修改购物车里的商品
	 *
	 * @param int $id 商品id
	 * @param int $count 商品数量
	 * @param int $type 修改类型 add：加 minus:减 edit:修改 clear:清空
	 * @return 如果修改失败，则返回false
	 */
	public function modifyCart($id, $num,$ruleid, $type = '') {
		
		if ( !is_array( $this->cartArray ) ) return false;
		if ($id < 1) {
			return false;
		}
		foreach ($this->cartArray as &$item) {
			if ($item['gid'] === $id) {
				switch ($type) {
					case 'add': // 添加数量 一般$Count为1
						$item['num'] += $num;
						pdo_update('zxbddiy_sitetemp_cart',array('num'=>$item['num']),array('id'=>$item['id']));
						Util::deleteCache('cart', $this->openid );
						break;
					case 'minus': // 减少数量
						$item['num'] -= $num;
						if( $item['num'] > 0 ){
							pdo_update('zxbddiy_sitetemp_cart',array('num'=>$item['num']),array('id'=>$item['id']));
							Util::deleteCache('cart', $this->openid );
						}else{
							unset( $item );
							pdo_delete('zxbddiy_sitetemp_cart',array('id'=>$item['id']));
							Util::deleteCache('cart', $this->openid );
						}

						break;
					case 'edit': // 修改数量
						if ($num <= 0) {
							unset( $item );
							pdo_delete('zxbddiy_sitetemp_cart',array('id'=>$item['id']));
							Util::deleteCache('cart', $this->openid );
							break;
						} else {
							$item['num'] = $num;
							pdo_update('zxbddiy_sitetemp_cart',array('num'=>$num),array('id'=>$item['id']));
							Util::deleteCache('cart', $this->openid );
							break;
						}
					case 'clear': //删除商品
						unset( $item );
						pdo_delete('zxbddiy_sitetemp_cart',array('id'=>$item['id']));
						Util::deleteCache('cart', $this->openid );
						break;
					default:
						break;
				}
			}
		}
		
	}
	/**
	 * 清空购物车
	 *
	 */
	public function removeAll() {
		global $_W;
		pdo_delete('zxbddiy_sitetemp_cart',array('uniacid'=>$_W['uniacid'],'openid'=>$this->openid));
		$this->cartArray = array();
		Util::deleteCache('cart', $this->openid );
	}
	
	
	
	/**
	 * 检查购物车是否有商品
	 *
	 * @return bool 如果有商品，返回true，否则false
	 */
	public function checkCart() {
		
		if (count( $this->cartArray ) < 1) {
			return false;
		}
		return true;
	}

	/**
	 * 统计商品数量
	 *
	 * @return int
	 */
	public function cartCount() {
		return count( $this->cartArray );
	}
	
	/**
	 * 检查购物车商品是否存在
	 *
	 * @param int $id
	 * @return bool 如果存在 true 否则false
	 */
	private function checkItem($gid,$ruleid) {
		
		if( !empty( $this->cartArray ) ){
			foreach ( $this->cartArray as $v ) {
				if( $v['gid'] == $gid && $ruleid == $v['ruleid'] ) return true;
			}
		}

		return false;
	}
}
?>