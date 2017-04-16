<?php
namespace Home\Controller;
class SearchController extends BaseController 
{
	public function search()
	{
		$catId = I('get.cid');
		$goodsModel = D('Admin/Goods');
		// 到缓存中找有没有价格区间的缓存
		$_price = S('price_'.$catId);
		// 如果缓存中没有就执行以下代码计算一下，然后放到缓存里
		if(!$_price)
		{
			/********************* 计算这个分类下商品的七个价格区间的范围 *****************************/
			$priceSection = 7; // 要分的段数
			// 取出扩展分类下的商品的ID并转化成字符串：1,2,3,4,5
			$gcModel = M('GoodsCat');
			$extGoodsId = $gcModel->field('GROUP_CONCAT(DISTINCT goods_id) goods_id')->where(array('cat_id'=>array('eq', $catId)))->find();
			if($extGoodsId['goods_id'])
				$extGoodsId = " OR id IN({$extGoodsId['goods_id']})";
			else 
				$extGoodsId = '';
			// 主分类和扩展分类下的商品都搜索出来
			// 算法：取出这个分类下商品的最低价和最高价
			$price = $goodsModel->field('MIN(shop_price) minprice,MAX(shop_price) maxprice')->where(array('cat_id'=>array('exp', "=$catId $extGoodsId")))->find();
			// 最低价和最高价分七段
			$_price = array();
			// 计算每个段应该价格区间
			$sprice = ($price['maxprice'] - $price['minprice']) / $priceSection;
			$firstPrice = $price['minprice'];
			for($i=0; $i<$priceSection; $i++)
			{
				if($i < ($priceSection-1))
				{
					$start = floor($firstPrice/10)*10;
					$end = (floor(($firstPrice+$sprice)/10)*10-1);
					// 先判断这个分类下在这个价格段中是否有商品
					$goodsCount = $goodsModel->where(array(
						'shop_price' => array('between',array($start, $end)),
						'cat_id' => array('exp', "=$catId $extGoodsId"),
						'is_on_sale' => array('eq', 1),
						'is_delete' => array('eq', 0),
					))->count();
					$firstPrice+=$sprice;
					if($goodsCount==0)
						continue;
					$_price[] = $start . '-'. $end;
					
				}
				else 
				{
					$start = floor($firstPrice/10)*10;
					$end = ceil($price['maxprice']/10)*10;
					// 先判断这个分类下在这个价格段中是否有商品
					$goodsCount = $goodsModel->where(array(
						'shop_price' => array('between',array($start, $end)),
						'cat_id' => array('eq', $catId),
						'is_on_sale' => array('eq', 1),
						'is_delete' => array('eq', 0),
					))->count();
					$firstPrice+=$sprice;
					if($goodsCount==0)
						continue;
					$_price[] = floor($firstPrice/10)*10 . '-'. ceil($price['maxprice']/10)*10;
				}
			}
			// 把计算好的放到缓存里，下次就可以直接从缓存中获取就不用再计算
			S('price_'.$catId, $_price, 3600);
		}
		// 先读属性的缓存
		$attrData = S('attr_'.$catId);
		if(!$attrData)
		{
			/********************* 可以搜索的属性 *****************************/
			// 取出这个分类下的筛选属性的数据
			$catModel = M('Category');
			$sai = $catModel->field('search_attr_id')->find($catId);
			// 根据筛选属性的ID取出这些属性的名称以及每个属性所拥有的值
			$attrModel = M('Attribute');
			$attrData = $attrModel->field('id,attr_name')->where(array('id'=>array('in', $sai['search_attr_id'])))->select();
			// 循环所有的筛选属性，取出这些属性中有商品的值
			$gaModel = M('GoodsAttr');
			foreach ($attrData as $k => $v)
			{
				// 找出这个属性有商品的值  --> 从商品属性表(一件商品所拥有的属性以及值）
				$attrValues = $gaModel->field('DISTINCT attr_value')->where(array('attr_id'=>array('eq', $v['id'])))->select();
				// 如果这个属性下没有商品,那么就删除这个属性
				if(!$attrValues)
					unset($attrData[$k]);
				else 
					$attrData[$k]['attr_value'] = $attrValues;
			}
			S('attr_'.$catId, $attrData, 3600);
		}
		
		// 取出商品
		$goods = $goodsModel->search_goods();
		
		$this->assign(array(
			'price' => $_price,
			'attrData' => $attrData,
			'goods' => $goods,
		));
		
		// 设置页面的信息
		$this->setPageInfo('搜索页', '搜索页', '搜索页', 0, array('list','common'), array('list'));
		// 显示页面
		$this->display();
	}
    
}




















