<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model 
{
	// 注册时表单中允许提交的字符
	protected $insertFields = array('email', 'password', 'cpassword', 'chkcode', 'mustclick');
	// 登录时使用的表单验证规则
	public $_login_validate = array(
		array('email', 'require', 'email不能为空！', 1),
		array('email', 'email', 'email格式不正确！', 1),
		array('password', 'require', '密码不能为空！', 1),
		array('password', '6,20', '密码必须是6-20位的字符！', 1, 'length'),
		array('chkcode', 'require', '验证码不能为空！', 1),
		array('chkcode', 'chk_chkcode', '验证码不正确！', 1, 'callback'),
	);
	// 注册时的表单验证规则
	protected $_validate = array(
		array('mustclick', 'require', '必须同意注册协议才能注册！', 1),
		array('email', 'require', 'email不能为空！', 1),
		array('email', 'email', 'email格式不正确！', 1),
		array('password', 'require', '密码不能为空！', 1),
		array('password', '6,20', '密码必须是6-20位的字符！', 1, 'length'),
		array('cpassword', 'password', '两次密码输入不一致！', 1, 'confirm'),
		array('chkcode', 'require', '验证码不能为空！', 1),
		array('chkcode', 'chk_chkcode', '验证码不正确！', 1, 'callback'),
		array('email', '', 'email已经被注册过了！', 1, 'unique'),
	);
	public function chk_chkcode($code)
	{
		 $verify = new \Think\Verify();
		 return $verify->check($code);
	}
	// 在会员记录插入到数据库之前
	protected function _before_insert(&$data, $option)
	{
		$data['addtime'] = time();  // 注册的当前时间
		// 生成验证email用的验证码
		$data['email_code'] = md5(uniqid());
		// 先把会员的密码加密
		$data['password'] = md5($data['password'] . C('MD5_KEY'));
	}
	// 在会员注册成功之后
	protected function _after_insert($data, $option)
	{
		// heredoc的语法
		$content =<<<HTML
		<p>欢迎您成为本站会员，请点击以下链接地址完成email验证。</p>
		<p><a href="http://www.34.com/index.php/Home/Member/emailchk/code/{$data['email_code']}">点击完成验证</a></p>
HTML;
		// 把生成的验证码发到会员的邮箱中
		sendMail($data['email'], 'php34网email验证', $content);
	}
	// 现在这个方法可以不用密码也能登录，这个功能在QQ登录时要用
	public function login($usePassword = TRUE)
	{
		$email = $this->email;
		if($usePassword)
			$password = $this->password;
		$user = $this->where(array('email'=>array('eq', $email)))->find();
		if($user)
		{
			// 判断是否已经通过email验证
			if(empty($user['email_code']))
			{
				// 如果需要登录登录才验证密码否则无须输入密码
				if($usePassword)
				{
					if($user['password'] != md5($password . C('MD5_KEY')))
					{
						$this->error = '密码不正确！';
						return FALSE;
					}
				}
					session('mid', $user['id']);
					session('email', $user['email']);
					session('jyz', $user['jyz']);
					// 取出当前登录会员所在的级别ID和这个级别的折扣率
					$mlModel = M('MemberLevel');
					$ml = $mlModel->field('id,rate')->where("{$user['jyz']} BETWEEN bottom_num AND top_num")->find();
					session('level_id', $ml['id']);
					session('rate', $ml['rate']/100);
					// 把购物车中的数据从COOKIE移动到数据库
					$cartModel = D('Admin/Cart');
					$cartModel->moveDataToDb();
					// 如果有openid就绑定到这个账号上
					if(isset($_SESSION['openid']))
					{
						$this->where(array('id'=>array('eq', $user['id'])))->setField('openid', $_SESSION['openid']);
						unset($_SESSION['openid']);
					}
					return TRUE;
			}
			else 
			{
				$this->error = '账号还没有通过email验证！';
				return FALSE;
			}
		}
		else 
		{
			$this->error = '账号不存在！';
			return FALSE;
		}
	}
}














