<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller 
{
	public function __construct()
	{
		// 先调用父类的构造函数
		parent::__construct();
		// 获取当前管理员的ID
		$adminId = session('id');
		// 验证登录
		if(!$adminId)
			redirect(U('Admin/Login/login'));
		// 验证当前管理员是否有权限访问这个页面
		// 1. 先获取当前管理员将要访问的页面	 - TP带三个常量
		$url = MODULE_NAME .'/'. CONTROLLER_NAME . '/' . ACTION_NAME;
		// 查询数据库判断当前管理员有没有访问这个页面的权限
		$where = 'module_name="'.MODULE_NAME.'" AND controller_name="'.CONTROLLER_NAME.'" AND action_name="'.ACTION_NAME.'"';
		
		// 任何人只要登录了就可以进入后台
		if(CONTROLLER_NAME == 'Index')
			return TRUE;
		
		if($adminId == 1)
			$sql = 'SELECT COUNT(*) has FROM php34_privilege WHERE '.$where;
		else 
			$sql = 'SELECT COUNT(a.role_id) has
			  FROM php34_role_privilege a
			   LEFT JOIN php34_privilege b ON a.pri_id=b.id
			   LEFT JOIN php34_admin_role c ON a.role_id=c.role_id
			    WHERE c.admin_id='.$adminId.' AND '.$where;
		$db = M();
		$pri = $db->query($sql);
		if($pri[0]['has'] < 1)
			$this->error('无权访问！');
	}
	public function index()
	{
		$this->display();
	}
	public function menu()
	{
		$adminId = session('id');
		/********** 取出当前管理员所拥有的前两级的权限 ************/
		// 取出当前管理员所有的权限
		if($adminId == 1)
			$sql = 'SELECT * FROM php34_privilege';
		else 
			$sql = 'SELECT b.*
			  FROM php34_role_privilege a
			   LEFT JOIN php34_privilege b ON a.pri_id=b.id
			   LEFT JOIN php34_admin_role c ON a.role_id=c.role_id
			    WHERE c.admin_id='.$adminId;
		$db = M();
		$pri = $db->query($sql);
		$btn = array();  // 放前两级的权限
		// 从所有的权限中取出前两级的权限
		foreach ($pri as $k => $v)
		{
			// 找顶级权限
			if($v['parent_id'] == 0)
			{
				// 再循环把这个顶级权限的子权限
				foreach ($pri as $k1 => $v1)
				{
					if($v1['parent_id'] == $v['id'])
					{
						$v['children'][] = $v1;
					}
				}
				$btn[] = $v;
			}
		}
		$this->assign('btn', $btn);
		$this->display();
	}
	public function top()
	{
		$this->display();
	}
	public function main()
	{
		$this->display();
	}
	public function setPageBtn($title, $btnName, $btnLink)
	{
		$this->assign('_page_title', $title);
		$this->assign('_page_btn_name', $btnName);
		$this->assign('_page_btn_link', $btnLink);
	}
}














