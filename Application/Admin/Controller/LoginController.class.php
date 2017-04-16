<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller 
{
	public function login()
	{
		if(IS_POST)
		{
			$model = D('Admin');
			// 使用validate方法来指定使用模型中的哪个数组做为验证规则，默认是使用$_validate
			// 我这里把登录的规则和添加修改管理员的规则分成了两个，所以这里要指定使用哪个
			// 7我们自己规定，代表登录说明这个表单是登录的表单
			if($model->validate($model->_login_validate)->create('',  7))
			{
				if(TRUE === $model->login())
					redirect(U('Admin/Index/index')); // 直接跳转可以不提示信息
			}
			$this->error($model->getError());
		}
		$this->display();
	}
	// 生成验证码的图片
	public function chkcode()
	{
		$Verify = new \Think\Verify(array(
			'length' => 2,
			'useNoise' => FALSE,
		));
		$Verify->entry();
	}
}














