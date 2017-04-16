<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class RoleController extends IndexController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin/Role');
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
    	
    	// 取出所有的权限
    	$priModel = D('Privilege');
    	$priData = $priModel->getTree();
    	$this->assign('priData', $priData);

		$this->setPageBtn('添加角色', '角色列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin/Role');
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
    	$model = M('Role');
    	$data = $model->find($id);
    	$this->assign('data', $data);
    	
    	// 取出所有的权限
    	$priModel = D('Privilege');
    	$priData = $priModel->getTree();
    	$this->assign('priData', $priData);
    	// 取出当前角色所拥有的权限的ID
    	$rpModel = M('RolePrivilege');
    	$rpData = $rpModel->field('GROUP_CONCAT(pri_id) pri_id')->where(array('role_id'=>array('eq', $id)))->find();
    	$this->assign('pri_id', $rpData['pri_id']);

		$this->setPageBtn('修改角色', '角色列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Admin/Role');
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
    	$model = D('Admin/Role');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		$this->setPageBtn('角色列表', '添加角色', U('add'));
    	$this->display();
    }
}