<?php
namespace Home\Controller;
class IndexController extends BaseController 
{
	// 商品详情页
	public function goods()
	{
		// 接收商品的ID
		$goodsId = I('get.id');
		// 取出商品的基本信息
		$goodsModel = M('Goods');
		$info = $goodsModel->find($goodsId);
		// 取出商品的图片
		$gpModel = M('GoodsPics');
		$gpData = $gpModel->where(array('goods_id'=>array('eq', $goodsId)))->select();
		/*********** 取出商品属性 ******************/
		// 取出商品的单选属性
		$gaModel = M('GoodsAttr');  // php34_goods_attr
		$_gaData1 = $gaModel->field('a.*,b.attr_name')->alias('a')->join('LEFT JOIN php34_attribute b ON a.attr_id=b.id')->where(array('a.goods_id'=>array('eq', $goodsId), 'b.attr_type'=>array('eq', 1)))->select();
		$gaData1 = array(); // 处理之后的数组结构
		// 处理二维变三维（把属性相同的放到一起）
		foreach ($_gaData1 as $k => $v)
		{
			$gaData1[$v['attr_name']][] = $v;
		}
		
		// 取出商品的唯一的属性
		$gaData2 = $gaModel->field('a.*,b.attr_name')->alias('a')->join('LEFT JOIN php34_attribute b ON a.attr_id=b.id')->where(array('a.goods_id'=>array('eq', $goodsId), 'b.attr_type'=>array('eq', 0)))->select();

		// 把取出来的数据assign到页面中
		$this->assign(array(
			'info' => $info,
			'gpData' => $gpData,
			'gaData1' => $gaData1,
			'gaData2' => $gaData2,
		));
		
		// 设置页面的信息
		$this->setPageInfo($info['goods_name'] . '-商品详情页', $info['seo_keyword'], $info['seo_description'], 0, array('goods','common','jqzoom'), array('goods','jqzoom-core'));
		// 显示页面
		$this->display();
	}
    public function index()
    {	
    	$goodsModel = D('Admin/Goods');
    	// 获取疯狂抢购的商品
    	$goods1 = $goodsModel->getPromoteGoods();
    	$goods2 = $goodsModel->getHot();
    	$goods3 = $goodsModel->getBest();
    	$goods4 = $goodsModel->getNew();
    	
    	$this->assign(array(
	    	'goods1' => $goods1,
	    	'goods2' => $goods2,
	    	'goods3' => $goods3,
	    	'goods4' => $goods4,
    	));
    	
    	// 设置页面的
    	// 参数1 ： 标题
    	// 参数2：设置meta标签中的关键字
    	// 参数3：设置meta标签中的描述
    	// 参数4: 分类树是否展开、1:展开 0：折叠
    	// 参数5：设置页面中需要包含的CSS文件
    	// 参数6：设置页面中需要包含的JS文件
    	$this->setPageInfo('首页', '关键字', '描述', 1, array('index'), array('index'));
    	$this->display();
    }
    // 计算会员价格
    public function ajaxGetPrice()
    {
    	// 计算会员价格
    	$goodsId = I('get.goods_id');
    	$goodsModel = D('Admin/Goods');
    	/**
    	 * 我们在COOKIE中保存一个一维数组，存最近浏览过了10件商品的ID
    	 * COOKIE中只能保存字符串，如何保存一个数组？
    	 * 答：必须要序列化。（当要把一个复杂的数据类型【数组、对象等】持久化【写入文件，或者数据库长久保存起来】时）
    	 */
    	$recentDisplay = isset($_COOKIE['recentDisplay']) ? unserialize($_COOKIE['recentDisplay']) : array();
    	//var_dump($recentDisplay);die;
    	// 把刚刚浏览的这件商品的ID放到这个数组中的最前面
    	array_unshift($recentDisplay, $goodsId);
    	// 去复
    	$recentDisplay = array_unique($recentDisplay);
    	// 如果超过10个就只保留前10个
    	if(count($recentDisplay) > 10)
    		$recentDisplay = array_slice($recentDisplay, 0, 10);
    	// setcookie的第四个参数【跨目录】：
    	/**
    	 *  /a/b/c.php中 setcookie('name', 'tom');
    	 *  /d.php 中  echo $_COOKIE['name'];   -->  输出空，读不出来上面定义的COOKIE，因为COOKIE默认只有在定义这个COOKIE的文件所在的目录以及子目录下的文件才可以访问,可以通过第四个参数设置这个COOKIE可以被访问的目录，如果希望全站都能正常使用COOKIE可以设置为 /
    	 * 
    	 * setcookie的第五个参数【跨域】：
    	 * www.34.com/a.php 定义了一个：setcookie('name', 'tom');
    	 * item.34.com/b.php echo $_COOKIE['name']; 默认只能同一个域下的COOKIE才能访问，可以通过设置第五个参数实现跨域
    	 * 
    	 * 
    	 * 扩展：如何实现AJAX跨域访问数据？
    	 */
    	
    	//var_dump($recentDisplay);die;
    	// 把处理好的数组保存回COOKIE中
    	$aMonth = 30 * 86400;
    	$data = serialize($recentDisplay);
    	setcookie('recentDisplay', $data, time() + $aMonth, '/', '34.com');
    	
    	echo $goodsModel->getMemberPrice($goodsId);
    }
    // 取出最近浏览过的商品的信息
    public function ajaxGetRecentDisGoods()
    {
    	// 先从COOKIE中取出最近浏览过的商品的ID
    	$recentDisplay = isset($_COOKIE['recentDisplay']) ? unserialize($_COOKIE['recentDisplay']) : array();
    	if($recentDisplay)
    	{
    		// 再根据商品的ID取出商品的信息
    		$goodsModel = M('Goods');
    		$recentDisplay_str = implode(',', $recentDisplay);
    		$goods = $goodsModel->field('id,goods_name,sm_logo')->where(array('id'=> array('in', $recentDisplay)))->order("INSTR(',$recentDisplay_str,',CONCAT(',',id,','))")->select();
    		echo json_encode($goods);
    	}
    }
    public function ajaxGetComment()
    {
    	$ret = array('login' => 0);
    	$mid = session('mid');
    	if($mid)
    	{
    		$ret['login'] = 1;
    	}
		echo json_encode($ret);
    }
}




















