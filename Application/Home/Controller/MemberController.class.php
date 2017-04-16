<?php
namespace Home\Controller;
class MemberController extends BaseController 
{
   public function regist()
   {
   		if(IS_POST)
   		{
   			$model = D('Admin/Member');
   			if($model->create(I('post.'), 1))
   			{
   				if($model->add())
   				{
   					$this->success('注册成功，请登录到您的邮件中完成验证！');
   					exit;
   				}
   			}
   			$this->error($model->getError());
   		}
   		// 设置页面标题等信息
   		$this->setPageInfo('会员注册', '会员注册', '会员注册', 1, array('login'));
   		$this->display();
   }
   public function login()
   {
   		if(IS_POST)
   		{
   			$model = D('Admin/Member');
   			if($model->validate($model->_login_validate)->create(I('post.'), 9))
   			{
   				if($model->login())
   				{
   					// 从session中取出有没有要跳回的地址
   					$returnUrl = session('returnUrl');
   					if($returnUrl)
   					{
   						// 从sessoin中删除掉，下次再登录就正常跳到首页
   						session('returnUrl', null);
   						redirect($returnUrl);
   					}
   					else
   						redirect('/');  // 登录成功之后直接跳到首页
   				}
   			}
   			$this->error($model->getError());
   		}
   		// 设置页面标题等信息
   		$this->setPageInfo('会员登录', '会员登录', '会员登录', 1, array('login'));
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
	public function emailchk()
	{
		// 接收会员传回来的验证码
		$code = I('get.code');
		if($code)
		{
			// 把这个验证码到数据库中比较一下即可
			$model = M('Member');
			$email = $model->where(array('email_code'=>array('eq', $code)))->find();
			if($email)
			{
				// 设置这个账号为已验证
				$model->where(array('id'=>array('eq', $email['id'])))->setField('email_code', '');
				$this->success('已经完成验证，现在可以去登录', U('login'));
				exit;
			}
		}
	}
	public function logout()
	{
		session('mid', null);
		session('email', null);
		redirect('/');
	}
	// 判断登录
	public function ajaxChkLogin()
	{
		if(session('mid'))
		{
			$arr = array(
				'ok' => 1,
				'email' => session('email'),
			);
		}
		else
		{
			$arr = array('ok' => 0);
		}
		echo json_encode($arr);
	}
	public function saveAndLogin()
	{
		// 获取AJAX是从哪个页面发过来的
		session('returnUrl', $_SERVER['HTTP_REFERER']);
	}
	public function qqlogin()
	{
		// 调用QQ的两个接口获取openid
		include('./qq/oauth/qq_callback.php');  // 获取openid并保存到session中
		// 查询会员表中哪个会员与这个QQ相关联
		$member = D('Admin/Member');
		$user = $member->field('email')->where(array('openid'=>array('eq', $_SESSION['openid'])))->find();
		// QQ号已经和一个账号关联之后
		if($user)
		{
			// 如果有这个会员就让这个会员为登录状态
			$member->email = $user['email']; // 把账号传给会员模型，登录时要用
			if($member->login(FALSE) === FALSE)
			{
				header('Content-type:text/html;charset=utf-8');
				die($member->getError());
			}
			echo <<<JS
			<script>
			opener.window.location.href='/';
			window.close();
			</script>
JS;
			exit;
		}
		else 
		{
			// 如果是第一次用QQ号登录那么应该显示一个表单引导用户关联一个账号
			redirect(U('login'));
		}
	}
}















