<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class GoodsController extends IndexController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		// 设置这个脚本可以执行的时间，单位：秒，0：代表一直执行到结束，默认30秒
    		set_time_limit(0);
//    		header('Content-Type:text/html;charset=utf-8;');
//    		var_dump($_FILES);
//			var_dump($pics);
//    		die;
    		$model = D('Admin/Goods');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	
    	// 取出所有的商品类型
    	$typeModel = M('Type');
    	$typeData = $typeModel->select();
    	$this->assign('typeData', $typeData);
    	// 取出所有的商品分类
    	$catModel = D('Category');
    	$catData = $catModel->getTree();
    	$this->assign('catData', $catData);
    	// 取出所有的品牌
    	$brandModel = M('Brand');
    	$brandData = $brandModel->select();
    	$this->assign('brandData', $brandData);
    	// 取出所有的会员级别
    	$mlModel = M('MemberLevel');
    	$mlData = $mlModel->select();
    	$this->assign('mlData', $mlData);

		$this->setPageBtn('添加商品', '商品列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
//    		header('Content-Type:text/html;charset=utf-8');
//    		var_dump($_POST);die;
    		$model = D('Admin/Goods');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	
    	// 取出所有的商品类型
    	$typeModel = M('Type');
    	$typeData = $typeModel->select();
    	$this->assign('typeData', $typeData);
    	// 取出所有的商品分类
    	$catModel = D('Category');
    	$catData = $catModel->getTree();
    	$this->assign('catData', $catData);
    	// 取出所有的品牌
    	$brandModel = M('Brand');
    	$brandData = $brandModel->select();
    	$this->assign('brandData', $brandData);
    	// 取出所有的会员级别
    	$mlModel = M('MemberLevel');
    	$mlData = $mlModel->select();
    	$this->assign('mlData', $mlData);
    	
    	// 取出要修改的商品的基本信息
    	$model = M('Goods');
    	$data = $model->find($id);
    	$this->assign('data', $data);
    	// 取出当前商品扩展分类的数据
    	$gcModel = M('GoodsCat');
    	$extCatId = $gcModel->field('cat_id')->where(array('goods_id'=>array('eq', $id)))->select();
    	$this->assign('extCatId', $extCatId);
    	// 取出当前商品会员价格的数据
    	$mpModel = M('MemberPrice');
    	$_mpData = $mpModel->where(array('goods_id'=>array('eq', $id)))->select();
    	// 先把二维转一维
    	$mpData = array();
    	foreach ($_mpData as $k => $v)
    	{
    		$mpData[$v['level_id']] = $v['price'];
    	}
    	$this->assign('mpData', $mpData);
    	// 取出当前商品的属性数据
    	$gaModel = M('GoodsAttr');
    	// SELECT a.*,b.attr_name FROM php34_goods_attr a LEFT JOIN php34_attribute b ON a.attr_id=b.id
    	$gaData = $gaModel->field('a.*,b.attr_name,b.attr_type,b.attr_option_values')->alias('a')->join('LEFT JOIN php34_attribute b ON a.attr_id=b.id')->where(array('a.goods_id'=>array('eq', $id)))->order('a.attr_id ASC')->select();
    	/**************************** 取出当前商品属性不存在的后添加的新的属性 **************************/
		// 循环属性数组取出当前商品已经拥有的属性ID
		$attr_id = array();
		foreach ($gaData as $k => $v)
		{
			$attr_id[] = $v['attr_id'];
		}
		$attr_id = array_unique($attr_id);
		// 取出当前类型下的后添加的新属性
		$attrModel = M('Attribute');
		$otherAttr = $allAttrId = $attrModel->field('id attr_id,attr_name,attr_type,attr_option_values')->where(array('type_id'=>array('eq', $data['type_id']), 'id'=>array('not in', $attr_id)))->select();
		if($otherAttr)
		{
			// 把新的属性和原属性合并起来
			$gaData = array_merge($gaData, $otherAttr);
			// 重新根据attr_id字段重新排序这个合并之后二维数组
			usort($gaData, 'attr_id_sort');
		}

    	$this->assign('gaData', $gaData);
    	// 取出当前商品的图片
    	$gpModel = M('GoodsPics');
    	$gpData = $gpModel->where(array('goods_id'=>array('eq', $id)))->select();
    	$this->assign('gpData', $gpData);

		$this->setPageBtn('修改商品', '商品列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    // 回收站
    public function recyclelst()
    {
    	$model = D('Admin/Goods');
    	$data = $model->search(20, 1);
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		$this->setPageBtn('商品回收站', '商品列表', U('lst'));
    	$this->display();
    }
    // 还原商品
    public function restore()
    {
    	$model = M('Goods');
    	$model->where(array(
    		'id' => array('eq', I('get.id')),
    	))->setField('is_delete', 0);
    	$this->success('操作成功！', U('recyclelst', array('p' => I('get.p', 1))));
    }
    // 放入回收站
    public function recycle()
    {
    	$model = M('Goods');
    	$model->where(array(
    		'id' => array('eq', I('get.id')),
    	))->setField('is_delete', 1);
    	$this->success('操作成功！', U('lst', array('p' => I('get.p', 1))));
    }
    public function delete()
    {
    	$model = D('Admin/Goods');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin/Goods');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		$this->setPageBtn('商品列表', '添加商品', U('add'));
    	$this->display();
    }
    // AJAX获取属性根据类型的ID
    public function ajaxGetAttr()
    {
    	$typeId = I('get.type_id');
    	$attrModel = M('Attribute');
    	// 根据类型ID取属性
    	$attrData = $attrModel->where(array('type_id'=>array('eq', $typeId)))->order('id ASC')->select();
		echo json_encode($attrData);
    }
    // AJAX删除图片
    public function ajaxDelImage()
    {
    	$picId = I('get.pic_id');
    	$gpModel = M('GoodsPics');
    	// 先取出图片的路径
    	$pic = $gpModel->field('pic,sm_pic')->find($picId);
    	// 把图片从硬盘上删除
    	deleteImage($pic);
    	// 再从数据库中把图片的数据也删除掉
    	$gpModel->delete($picId);
    }
    // AJAX删除商品属性
    public function ajaxDelGoodsAttr()
    {
    	$gaid = I('get.gaid');
    	$gaModel = M('GoodsAttr');
    	$gaModel->delete($gaid);
    }
    // 库存量
    public function goods_number()
    {
    	// 接收商品的ID
    	$goodsId = I('get.id');
    	if(IS_POST)
    	{
    		$gai = I('post.goods_attr_id');
    		$gn = I('post.goods_number');
    		$gnModel = M('GoodsNumber');
    		// 先清原来设置的库存量的数据
    		$gnModel->where(array('goods_id'=>array('eq', $goodsId)))->delete();
    		// 先计算两个数组的比例是多少
    		$rate = count($gai) / count($gn);
    		$_i = 0; // 从ID的数组中的第几个开始拿
    		// 循环每个库存量插入到库存量表
    		foreach ($gn as $k => $v)
    		{
    			// 把每次拿过来的ID先放到这个数组中
    			$_arr = array();
    			// 到ID的数组中拿$rate个
    			for($i=0; $i<$rate; $i++)
    			{
    				$_arr[] = $gai[$_i];  // 拿出ID放到数组中
    				$_i++;  // 下次拿下一个
    			}
    			// 升序排序数组
    			sort($_arr);
    			// 拼成字符串
    			$_arr = implode(',', $_arr);
    			$gnModel->add(array(
    				'goods_id' => $goodsId,
    				'goods_number' => $v,
    				'goods_attr_id' => $_arr,  // 升序排好的id的字符串
    			));
    		}
    		$this->success('设置成功！');
    		exit;
    	}
    	// 根据商品ID取出这件商品同一个属性有多个值的属性
    	// 原理：先取出这件商品有多个值的属性ID，再套SQL只取出这些有些属性的值 的记录
    	// 还要连属性表取出属性的名称，因为表单中要用
    	$sql = 'SELECT a.*,b.attr_name FROM php34_goods_attr a 
    	  LEFT JOIN php34_attribute b ON a.attr_id=b.id
    	  WHERE attr_id IN(
    		SELECT attr_id FROM php34_goods_attr WHERE goods_id='.$goodsId.' GROUP BY attr_id HAVING count(*) > 1
    	) AND a.goods_id='.$goodsId;
    	$db = M();
    	$_attr = $db->query($sql);
    	// 处理这个数组，把属性相同的放到一起
    	$attr = array();  // 新数组
    	foreach ($_attr as $k => $v)
    	{
    		$attr[$v['attr_id']][] = $v;
    	}
    	
    	$this->assign('attr', $attr);
    	
    	// 先取出当前商品已经设置过了库存量数据
    	$gnModel = M('GoodsNumber');
    	$gnData = $gnModel->where(array('goods_id'=>array('eq', $goodsId)))->select();
    	$this->assign('gnData', $gnData);
    	
    	$this->setPageBtn('库存设置', '商品列表', U('lst?p='.I('get.p')));
    	$this->display();
    }
}




















