<?php
namespace Gii\Controller;
use Think\Controller;
define('GII_PATH', APP_PATH.'Gii/');
define('GII_CONFIG_PATH', GII_PATH.'Table_configs/');
define('GII_TEMPLATE_PATH', GII_PATH.'Code_templates/');
class IndexController extends Controller 
{
	public function index()
	{
		if(IS_POST)
		{
			/********************************** 生成表的配置文件 **********************/
			$type = I('post.type');
			if($type == 2)
			{
				$this->makeConfigFile();
				exit;
			}
			/********************************** 生成代码 ***************************/
			$configName = I('post.config_name');
			if(!$configName)
				$this->error('配置文件名称不能为空！');
			if(!file_exists(GII_CONFIG_PATH.$configName))
				$this->error('配置文件不存在！');
			$config = include(GII_CONFIG_PATH.$configName);
			// 表名转成tp中的名字
			$tpName = $this->_dbName2TpName($config['tableName']);
			// 1.生成对应模块的目录结构
			$moduleDir = APP_PATH.$config['moduleName'];
			$cDir = APP_PATH.$config['moduleName'].'/Controller';
			$mDir = APP_PATH.$config['moduleName'].'/Model';
			$vDir = APP_PATH.$config['moduleName'].'/View';
			$v1Dir = APP_PATH.$config['moduleName'].'/View/'.$tpName;
			if(!is_dir($moduleDir))
				mkdir($moduleDir, 0777);
			if(!is_dir($cDir))
				mkdir($cDir, 0777);
			if(!is_dir($mDir))
				mkdir($mDir, 0777);
			if(!is_dir($vDir))
				mkdir($vDir, 0777);
			if(!is_dir($v1Dir))
				mkdir($v1Dir, 0777);
			// 2. 在view目录下生成对应的表单add.html
			ob_start();
			include(GII_TEMPLATE_PATH . 'add.html');
			$str = ob_get_clean();
			file_put_contents($v1Dir.'/add.html', $str);
			// 3. 生成控制器文件
			ob_start();
			include(GII_TEMPLATE_PATH.'Controller.php');
			$str = ob_get_clean();
			file_put_contents($cDir.'/'.$tpName.'Controller.class.php', "<?php\r\n".$str);
			// 4. 生成模型文件
			ob_start();
			include(GII_TEMPLATE_PATH.'Model.php');
			$str = ob_get_clean();
			file_put_contents($mDir.'/'.$tpName.'Model.class.php', "<?php\r\n".$str);
			// 5. 生成lst.html页面
			ob_start();
			include(GII_TEMPLATE_PATH.'lst.html');
			$str = ob_get_clean();
			file_put_contents($v1Dir.'/lst.html', $str);
			// 6. 生成edit.html页面
			ob_start();
			include(GII_TEMPLATE_PATH.'edit.html');
			$str = ob_get_clean();
			file_put_contents($v1Dir.'/edit.html', $str);
			/*************************** 插入相应的权限 ****************************/
			// 先从配置文件中取出顶级权限的名称是什么
			$topPriName = $config['topPriName'];
			// 取出这个顶级权限的ID
			$priModel = M('Privilege');
			$topPriId = $priModel->field('id')->where(array('pri_name'=>array('eq', $topPriName)))->find();
			
			// 先判断有没有列表的权限
			$has = $priModel->field('id')->where(array('pri_name'=>array('eq', $config['tableCnName'] . '列表')))->count();
			if($has == 0)
			{
				// 在这个顶级权限下添加一个XX列表的权限
				$lisPriId = $priModel->add(array(
					'pri_name' => $config['tableCnName'] . '列表',
					'parent_id' => $topPriId['id'],
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'lst',
				));
				// 在列表权限下再添加三个权限
				$priModel->add(array(
					'pri_name' => '添加'.$config['tableCnName'],
					'parent_id' => $lisPriId,
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'add',
				));
				$priModel->add(array(
					'pri_name' => '修改'.$config['tableCnName'],
					'parent_id' => $lisPriId,
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'edit',
				));
				$priModel->add(array(
					'pri_name' => '删除'.$config['tableCnName'],
					'parent_id' => $lisPriId,
					'module_name' => $config['moduleName'],
					'controller_name' => $tpName,
					'action_name' => 'delete',
				));
			}
			
			$this->success('代码生成成功！');
			exit;
		}
		$this->display();
	}
	private function _dbName2TpName($tableName)
	{
		$tableName = explode('_', $tableName);
		unset($tableName[0]);
		$tableName = array_map('ucfirst', $tableName);
		return implode($tableName);
	}
	public function makeConfigFile()
	{
		$db = M();
		$tableName = I('post.config_name');
		if($tableName)
		{
			$tableName = explode(',', $tableName);
			foreach ($tableName as $___v)
			{
				// 取出表的信息
				$_tableInfo = $db->query("show table status WHERE Name LIKE '$___v'");
				if($_tableInfo)
				{
					$_tableInfo = $_tableInfo[0];
					// 取出表的字段
					$_tableFields = $db->query("SHOW FULL FIELDS FROM $___v");
					$tpName = $this->_dbName2TpName($___v);
					ob_start();
					include(GII_TEMPLATE_PATH . 'config.php');
					$str = ob_get_clean();
					file_put_contents(GII_CONFIG_PATH.$___v.'.php', "<?php\r\n".$str);
				}
				else 
					$this->error('表不存在！');
			}
			$this->success('成功！');
			exit;
		}
		else 
			$this->error('请输入表名！');
	}
}