<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model 
{
	protected $insertFields = array('goods_name','cat_id','brand_id','market_price','shop_price','jifen','jyz','jifen_price','promote_price','promote_start_time','promote_end_time','is_hot','is_new','is_best','is_on_sale','seo_keyword','seo_description','type_id','sort_num','is_delete','goods_desc','is_promote');
	protected $updateFields = array('id','goods_name','cat_id','brand_id','market_price','shop_price','jifen','jyz','jifen_price','promote_price','promote_start_time','promote_end_time','is_hot','is_new','is_best','is_on_sale','seo_keyword','seo_description','type_id','sort_num','is_delete','goods_desc','is_promote');
	protected $_validate = array(
		array('goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3),
		array('goods_name', '1,45', '商品名称的值最长不能超过 45 个字符！', 1, 'length', 3),
		array('cat_id', 'require', '主分类的id不能为空！', 1, 'regex', 3),
		array('cat_id', 'number', '主分类的id必须是一个整数！', 1, 'regex', 3),
		array('brand_id', 'number', '品牌的id必须是一个整数！', 2, 'regex', 3),
		array('market_price', 'currency', '市场价必须是货币格式！', 2, 'regex', 3),
		array('shop_price', 'currency', '本店价必须是货币格式！', 2, 'regex', 3),
		array('jifen', 'require', '赠送积分不能为空！', 1, 'regex', 3),
		array('jifen', 'number', '赠送积分必须是一个整数！', 1, 'regex', 3),
		array('jyz', 'require', '赠送经验值不能为空！', 1, 'regex', 3),
		array('jyz', 'number', '赠送经验值必须是一个整数！', 1, 'regex', 3),
		array('jifen_price', 'require', '如果要用积分兑换，需要的积分数不能为空！', 1, 'regex', 3),
		array('jifen_price', 'number', '如果要用积分兑换，需要的积分数必须是一个整数！', 1, 'regex', 3),
		array('promote_price', 'currency', '促销价必须是货币格式！', 2, 'regex', 3),
		//array('promote_start_time', '/^20\d{2}-(0|1)\d-[0-3]\d$/', '促销开始时间格式不正确！', 2, 'regex', 3),
		//array('promote_end_time', '/^20\d{2}-(0|1)\d-[0-3]\d$/', '促销结束时间格式不正确！', 2, 'regex', 3),
		array('is_hot', 'number', '是否热卖必须是一个整数！', 2, 'regex', 3),
		array('is_new', 'number', '是否新品必须是一个整数！', 2, 'regex', 3),
		array('is_best', 'number', '是否精品必须是一个整数！', 2, 'regex', 3),
		array('is_on_sale', 'number', '是否上架：1：上架，0：下架必须是一个整数！', 2, 'regex', 3),
		array('seo_keyword', '1,150', 'seo优化_关键字的值最长不能超过 150 个字符！', 2, 'length', 3),
		array('seo_description', '1,150', 'seo优化_描述的值最长不能超过 150 个字符！', 2, 'length', 3),
		array('type_id', 'number', '商品类型id必须是一个整数！', 2, 'regex', 3),
		array('sort_num', 'number', '排序数字必须是一个整数！', 2, 'regex', 3),
		array('is_delete', 'number', '是否放到回收站：1：是，0：否必须是一个整数！', 2, 'regex', 3),
	);
	// 后台的商品列表时搜索的方法
	public function search($pageSize = 20, $isDelete = 0)
	{
		/**************************************** 搜索 ****************************************/
		// 是否是回收站的商品
		$where['is_delete'] = array('eq', $isDelete);
		if($goods_name = I('get.goods_name'))
			$where['goods_name'] = array('like', "%$goods_name%");
		if($cat_id = I('get.cat_id'))
			$where['cat_id'] = array('eq', $cat_id);
		if($brand_id = I('get.brand_id'))
			$where['brand_id'] = array('eq', $brand_id);
		$shop_pricefrom = I('get.shop_pricefrom');
		$shop_priceto = I('get.shop_priceto');
		if($shop_pricefrom && $shop_priceto)
			$where['shop_price'] = array('between', array($shop_pricefrom, $shop_priceto));
		elseif($shop_pricefrom)
			$where['shop_price'] = array('egt', $shop_pricefrom);
		elseif($shop_priceto)
			$where['shop_price'] = array('elt', $shop_priceto);
		$is_hot = I('get.is_hot');
		if($is_hot != '' && $is_hot != '-1')
			$where['is_hot'] = array('eq', $is_hot);
		$is_new = I('get.is_new');
		if($is_new != '' && $is_new != '-1')
			$where['is_new'] = array('eq', $is_new);
		$is_best = I('get.is_best');
		if($is_best != '' && $is_best != '-1')
			$where['is_best'] = array('eq', $is_best);
		$is_on_sale = I('get.is_on_sale');
		if($is_on_sale != '' && $is_on_sale != '-1')
			$where['is_on_sale'] = array('eq', $is_on_sale);
		if($type_id = I('get.type_id'))
			$where['type_id'] = array('eq', $type_id);
		$addtimefrom = I('get.addtimefrom');
		$addtimeto = I('get.addtimeto');
		if($addtimefrom && $addtimeto)
			$where['addtime'] = array('between', array(strtotime("$addtimefrom 00:00:00"), strtotime("$addtimeto 23:59:59")));
		elseif($addtimefrom)
			$where['addtime'] = array('egt', strtotime("$addtimefrom 00:00:00"));
		elseif($addtimeto)
			$where['addtime'] = array('elt', strtotime("$addtimeto 23:59:59"));
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->field('a.*,IFNULL(SUM(b.goods_number),0) gn')->alias('a')->join('LEFT JOIN php34_goods_number b ON a.id=b.goods_id')->where($where)->group('a.id')->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
		// 获取当前时间存到数据库中
		$data['addtime'] = time();
		// 把促销时间转化成时间戳
		if($data['is_promote'] == 1)
		{
			$data['promote_start_time'] = strtotime($data['promote_start_time']);
			$data['promote_end_time'] = strtotime($data['promote_end_time']);
		}
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0)
		{
			$ret = uploadOne('logo', 'Admin', array(
				array(150, 150, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['logo'] = $ret['images'][0];
				$data['sm_logo'] = $ret['images'][1];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
	}
	// 商品基本信息插入到数据库中之后
	protected function _after_insert($data, $option)
	{
		/**************** 处理商品的扩展分类 ********************/
		$eci = I('post.ext_cat_id');
		if($eci)
		{
			$gcModel = M('GoodsCat');
			foreach ($eci as $v)
			{
				// 如果分类为空就跳过处理下一个
				if(empty($v))
					continue;
				$gcModel->add(array(
					'goods_id' => $data['id'],
					'cat_id' => $v,
				));
			}
		}
		/************* 处理会员价格 ************************/
		$mp = I('post.mp');
		if($mp)
		{
			$mpModel = M('MemberPrice');
			foreach ($mp as $k => $v)
			{
				if(empty($v))
					continue ;
				$mpModel->add(array(
					'goods_id' => $data['id'],
					'level_id' => $k,
					'price' => $v,
				));
			}
		}
		/******************** 处理商品属性的数据 *********************/
		$ga = I('post.ga');
		$ap = I('post.attr_price');
		if($ga)
		{
			$gaModel = M('GoodsAttr');
			foreach ($ga as $k => $v)
			{
				foreach ($v as $k1 => $v1)
				{
					if(empty($v1))
						continue ;
					$price = isset($ap[$k][$k1]) ? $ap[$k][$k1] : '';
					$gaModel->add(array(
						'goods_id' => $data['id'],
						'attr_id' => $k,
						'attr_value' => $v1,
						'attr_price' => $price,
					));
				}
			}
		}
		/************************* 处理商品图片的代码 ***********************/
		// 判断有没有图片
		if(hasImage('pics'))
		{
			$gpModel = M('GoodsPics');
			// 批量上传之后的图片数组，改造成每个图片一个一维数组的形式
			$pics = array();
			foreach ($_FILES['pics']['name'] as $k => $v)
			{
				if($_FILES['pics']['size'][$k] == 0)
					continue ;
				$pics[] = array(
					'name' => $v,
					'type' => $_FILES['pics']['type'][$k],
					'tmp_name' => $_FILES['pics']['tmp_name'][$k],
					'error' => $_FILES['pics']['error'][$k],
					'size' => $_FILES['pics']['size'][$k],
				);
			}
			// 在后面调用uploadOne方法时会使用$_FILES数组上传图片，所以我们要把我们处理好的数组赎给$_FILES这样上传时使用的就是我们处理好的数组
			$_FILES = $pics;
			// 循环每张图片一个一个的上传
			foreach ($pics as $k => $v)
			{
				$ret = uploadOne($k, 'Goods', array(
					array(150, 150)
				));
				if($ret['ok'] == 1)
				{
					$gpModel->add(array(
						'goods_id' => $data['id'],
						'pic' => $ret['images'][0],     // 原图存到pic字段
						'sm_pic' => $ret['images'][1],  // 第一个缩略图存到sm_pic字段中
					));
				}
			}
		}
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
		/************* 判断商品类型有没有被修改， 如果修改了类型，那么就删除原来的商品属性 ***************/
		// 先取出原来的类型是什么
		if(I('post.old_type_id') != $data['type_id'])
		{
			// 删除当前商品所有之前的属性
			$gaModel = M('GoodsAttr');
			$gaModel->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		}
		// 如果没有勾选促销价格就手动设置为更新成0
		if(!isset($_POST['is_promote']))
			$data['is_promote'] = 0;
		else 
		{
			$data['promote_start_time'] = strtotime(I('post.promote_start_time'));
			$data['promote_end_time'] = strtotime(I('post.promote_end_time'));
		}
		if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0)
		{
			$ret = uploadOne('logo', 'Admin', array(
				array(150, 150, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['logo'] = $ret['images'][0];
				$data['sm_logo'] = $ret['images'][1];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			deleteImage(array(
				I('post.old_logo'),
				I('post.old_sm_logo'),
	
			));
		}
	}
	// 在修改了商品表的基本信息之后
	protected function _after_update($data, $option)
	{
		/**************** 处理商品的扩展分类 ********************/
		$eci = I('post.ext_cat_id');
		$gcModel = M('GoodsCat');
		// 先清除商品原扩展分类数据
		$gcModel->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		// 如果有新的数据就再添加一遍
		if($eci)
		{
			foreach ($eci as $v)
			{
				// 如果分类为空就跳过处理下一个
				if(empty($v))
					continue;
				$gcModel->add(array(
					'goods_id' => $option['where']['id'],
					'cat_id' => $v,
				));
			}
		}
		/************* 处理会员价格 ************************/
		$mpModel = M('MemberPrice');
		$mpModel->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		$mp = I('post.mp');
		if($mp)
		{
			foreach ($mp as $k => $v)
			{
				if(empty($v))
					continue ;
				$mpModel->add(array(
					'goods_id' => $option['where']['id'],
					'level_id' => $k,
					'price' => $v,
				));
			}
		}
		/************************* 处理商品图片的代码 ***********************/
		// 判断有没有图片
		if(hasImage('pics'))
		{
			$gpModel = M('GoodsPics');
			// 批量上传之后的图片数组，改造成每个图片一个一维数组的形式
			$pics = array();
			foreach ($_FILES['pics']['name'] as $k => $v)
			{
				if($_FILES['pics']['size'][$k] == 0)
					continue ;
				$pics[] = array(
					'name' => $v,
					'type' => $_FILES['pics']['type'][$k],
					'tmp_name' => $_FILES['pics']['tmp_name'][$k],
					'error' => $_FILES['pics']['error'][$k],
					'size' => $_FILES['pics']['size'][$k],
				);
			}
			// 在后面调用uploadOne方法时会使用$_FILES数组上传图片，所以我们要把我们处理好的数组赎给$_FILES这样上传时使用的就是我们处理好的数组
			$_FILES = $pics;
			// 循环每张图片一个一个的上传
			foreach ($pics as $k => $v)
			{
				$ret = uploadOne($k, 'Goods', array(
					array(150, 150)
				));
				if($ret['ok'] == 1)
				{
					$gpModel->add(array(
						'goods_id' => $option['where']['id'],
						'pic' => $ret['images'][0],     // 原图存到pic字段
						'sm_pic' => $ret['images'][1],  // 第一个缩略图存到sm_pic字段中
					));
				}
			}
		}
		/****************************** 修改商品属性的代码 ***************************/
		// 处理新属性
		$ga = I('post.ga');
		$ap = I('post.attr_price');
		$gaModel = M('GoodsAttr');
		if($ga)
		{
			foreach ($ga as $k => $v)
			{
				foreach ($v as $k1 => $v1)
				{
					if(empty($v1))
						continue ;
					$price = isset($ap[$k][$k1]) ? $ap[$k][$k1] : '';
					$gaModel->add(array(
						'goods_id' => $option['where']['id'],
						'attr_id' => $k,
						'attr_value' => $v1,
						'attr_price' => $price,
					));
				}
			}
		}
		// 处理原属性
		$oldga = I('post.old_ga');
		$oldap = I('post.old_attr_price');
		// 循环所更新一遍所有的旧属性
		foreach ($oldga as $k => $v)
		{
			foreach ($v as $k1 => $v1)
			{
				// 要修改的字段
				$oldField = array('attr_value' => $v1);
				// 如果有对应的价格就把价格也修改
				if(isset($oldap[$k]))
					$oldField['attr_price'] = $oldap[$k][$k1];
				$gaModel->where(array('id'=>array('eq', $k1)))->save($oldField);
			}
		}
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
		$images = $this->field('logo,sm_logo')->find($option['where']['id']);
		deleteImage($images);
		/****************************** 先删除商品的其他的信息 ********************************/
		// 扩展分类
		$model = M('GoodsCat');
		$model->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		// 会员价格
		$model = M('MemberPrice');
		$model->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		// 商品属性
		$model = M('GoodsAttr');
		$model->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		// 商品库存量
		$model = M('GoodsNumber');
		$model->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
		// 商品图片
		$model = M('GoodsPics');
		// 先取出图片的路径
		$pics = $model->field('pic,sm_pic')->where(array('goods_id'=>array('eq', $option['where']['id'])))->select();
		// 循环每个图片进行删除
		foreach ($pics as $p)
		{
			deleteImage($p);
		}
		$model->where(array('goods_id'=>array('eq', $option['where']['id'])))->delete();
	}
	/************************************ 其他方法 ********************************************/
	// 获取当前正处在促销期间的商品
	public function getPromoteGoods($limit = 5)
	{
		$now = time();
		return $this->field('id,goods_name,promote_price,sm_logo')->where(array(
			'is_on_sale' => array('eq', 1),  // 上架
			'is_delete' => array('eq', 0),   // 不在回收站
			'is_promote' => array('eq', 1),  // 促销的商品
			'promote_start_time' => array('elt', $now),
			'promote_end_time' => array('egt', $now),
		))->limit($limit)->order('sort_num ASC')->select();
	}
	// 最新的
	public function getNew($limit = 5)
	{
		$now = time();
		return $this->field('id,goods_name,shop_price,sm_logo')->where(array(
			'is_on_sale' => array('eq', 1),  // 上架
			'is_delete' => array('eq', 0),   // 不在回收站
			'is_new' => array('eq', 1),  // 最新
		))->limit($limit)->order('sort_num ASC')->select();
	}
	public function getHot($limit = 5)
	{
		$now = time();
		return $this->field('id,goods_name,shop_price,sm_logo')->where(array(
			'is_on_sale' => array('eq', 1),  // 上架
			'is_delete' => array('eq', 0),   // 不在回收站
			'is_hot' => array('eq', 1),  // 热卖
		))->limit($limit)->order('sort_num ASC')->select();
	}
	public function getBest($limit = 5)
	{
		$now = time();
		return $this->field('id,goods_name,shop_price,sm_logo')->where(array(
			'is_on_sale' => array('eq', 1),  // 上架
			'is_delete' => array('eq', 0),   // 不在回收站
			'is_best' => array('eq', 1),  // 精品
		))->limit($limit)->order('sort_num ASC')->select();
	}
	// 计算会员价格
	public function getMemberPrice($goodsId)
	{
		$now = time();
		// 先判断是否有促销价格
		$price = $this->field('shop_price,is_promote,promote_price,promote_start_time,promote_end_time')->find($goodsId);
		if($price['is_promote'] == 1 && ($price['promote_start_time'] < $now && $price['promote_end_time'] > $now))
		{
			return $price['promote_price'];
		}
		// 如果会员没登录直接使用本店价
		$memberId = session('mid');
		if(!$memberId)
			return $price['shop_price'];
		// 计算会员价格
		$mpModel = M('MemberPrice');
		$mprice = $mpModel->field('price')->where(array('goods_id'=>array('eq', $goodsId), 'level_id'=>array('eq', session('level_id'))))->find();		
		// 如果有会员价格就直接使用会员价格
		if($mprice)
			return $mprice['price'];
		else 
			// 如果没有设置会员价格，就按这个级别的折扣率来算
			return session('rate') * $price['shop_price'];
	}
	/**
	 * 转化商品属性ID为商品属性字符串
	 *
	 */
	public function convertGoodsAttrIdToGoodsAttrStr($gaid)
	{
		if($gaid)
		{
			$sql = 'SELECT GROUP_CONCAT( CONCAT( b.attr_name,  ":", a.attr_value ) SEPARATOR  "<br />" ) gastr FROM php34_goods_attr a LEFT JOIN php34_attribute b ON a.attr_id = b.id WHERE a.id IN ('.$gaid.')';
			$ret = $this->query($sql);
			return $ret[0]['gastr'];
		}
		else 
			return '';
	}
	// 前台商品搜索功能使用的方法
	public function search_goods()
	{
		/******************* 搜索 ********************/
		$where = array(
			'a.is_on_sale' => array('eq', 1),
			'a.is_delete' => array('eq', 0),
		);
		// 如果传了分类ID
		$catId = I('get.cid');
		if($catId)
		{
			// 取出扩展分类下的商品的ID并转化成字符串：1,2,3,4,5
			$gcModel = M('GoodsCat');
			$extGoodsId = $gcModel->field('GROUP_CONCAT(DISTINCT goods_id) goods_id')->where(array('cat_id'=>array('eq', $catId)))->find();
			if($extGoodsId['goods_id'])
				$extGoodsId = " OR a.id IN({$extGoodsId['goods_id']})";
			else 
				$extGoodsId = '';
			// 主分类和扩展分类下的商品都搜索出来
			$where['a.cat_id'] = array('exp', "=$catId $extGoodsId");
		}
		// 价格搜索
		$price = I('get.price');
		if($price)
		{
			$price = explode('-', $price);
			$where['a.shop_price'] =array('between', array($price[0], $price[1]));
		}
		// 商品属性的搜索
		$sa = I('get.search_attr');
		if($sa)
		{
			$gaModel = M('GoodsAttr');
			$sa = explode('.', $sa);
			// 先定义一个数组：第一个满足条件的属性ID
			$_att1 = null;
			// 循环每个属性
			/**
			 * 找出满足每个属性条件的商品ID列表
			 * 12寸： 3,4,6,7,9
			独立： 3,5,9,76
			345G: 3,5
			$arr= array('3,4,6,7,9','3,5,9,76','3,5');
			 */
			// 现在要找出满足所有的条件的商品ID就是把上面的ID字符串取交集
			foreach ($sa as $k => $v)
			{
				if($v != '0')
				{
					$_v = explode('-', $v);
					// 到商品属性表中搜索有这个属性以及值的商品的ID,并返回字符串1,2,3,4
					$_attrGoodsId = $gaModel->field('GROUP_CONCAT(goods_id) goods_id')->where(array(
						'attr_id' => $_v[1],
						'attr_value' => $_v[0],
					))->find();
					$_attrGoodsId = $_attrGoodsId['goods_id'];
					// 如果是第一个就先保存起来
					if($_att1 === null)
						$_att1 = explode(',', $_attrGoodsId);
					else 
					{
						// 如果$_attr1不为空，保存的就是上一次满足条件的商品ID,那么就和这次取交集
						$_attrGoodsId = explode(',', $_attrGoodsId);
						$_att1 = array_intersect($_att1, $_attrGoodsId);
						// 如果已经是空了就直接退出不用再比较了，肯定没交集
						if(empty($_att1))
							break;
					}
				}
			}
			// $_attr1保存的就是满所有条件 的商品的ID
			if($_att1)
				$where['a.id'] = array('in', $_att1);
			else 
				$where['a.id'] = array('eq', 0);  // 如果没有满足条件的商品就直接设置为一个搜索不出来的条件
		}
		/****************** 排序 ********************/
		$orderBy = 'xl';  // 排序字段
		$orderWay = 'DESC'; // 排序方式
		// 接收用户传的排序参数
		$ob = I('get.ob');
		$ow = I('get.ow');
		if($ob && in_array($ob, array('xl','shop_price','pl','addtime')))
		{
			$orderBy = $ob;
			// 如果是根据价格排序，才接收ow变量
			if($ob == 'shop_price' && $ow && in_array($ow, array('asc', 'desc')))
				$orderWay = $ow;
		}
		/******************* 翻页 ***********************/
		// 取出总的记录数
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, 24);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		
		/*********************** 取商品 **********************/
		/**
		 * SELECT a.id,a.goods_name,IFNULL(SUM(b.goods_number),0) xl,(SELECT count(id) FROM php34_comment c WHERE c.goods_id=a.id) pl
 FROM php34_goods a
  LEFT JOIN php34_order_goods b
   ON (a.id=b.goods_id AND b.order_id IN(SELECT id FROM php34_order WHERE pay_status=1))
      GROUP BY a.id
       ORDER BY xl ASC
       总结：因为如果使用两个都外链（left join）那么取出的结构会互相影响，所以销量用的LEFT JOIN 而评论数用的子查询，这样没有互相影响
		 */
		$data['data'] = $this->field('a.id,a.goods_name,sm_logo,shop_price,IFNULL(SUM(b.goods_number),0) xl,(SELECT count(id) FROM php34_comment c WHERE c.goods_id=a.id) pl')->alias('a')->join('LEFT JOIN php34_order_goods b ON (a.id=b.goods_id AND b.order_id IN(SELECT id FROM php34_order WHERE pay_status=1))')->where($where)->group('a.id')->order("$orderBy $orderWay")->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
}
























