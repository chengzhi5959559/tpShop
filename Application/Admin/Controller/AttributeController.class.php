<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class AttributeController extends IndexController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin/Attribute');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p').'&type_id='.I('get.type_id')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	
    	// 接收当前类型的type_id
    	$typeId = I('get.type_id');
    	$this->assign('typeId', $typeId);
    	// 取出所有的类型
    	$typeModel = M('Type');
    	$typeData = $typeModel->select();
    	$this->assign('typeData', $typeData);

		$this->setPageBtn('添加属性', '属性列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin/Attribute');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1), 'type_id'=>I('get.type_id'))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	$model = M('Attribute');
    	$data = $model->find($id);
    	$this->assign('data', $data);
    	
    	// 接收当前类型的type_id
    	$typeId = I('get.type_id');
    	$this->assign('typeId', $typeId);
    	// 取出所有的类型
    	$typeModel = M('Type');
    	$typeData = $typeModel->select();
    	$this->assign('typeData', $typeData);

		$this->setPageBtn('修改属性', '属性列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Admin/Attribute');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1), 'type_id'=>I('get.type_id'))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin/Attribute');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));
    	
    	// 接收当前类型的type_id
    	$typeId = I('get.type_id');
    	$this->assign('typeId', $typeId);
    	// 取出所有的类型
    	$typeModel = M('Type');
    	$typeData = $typeModel->select();
    	$this->assign('typeData', $typeData);

    	// 设置页面中标题和按钮
		$this->setPageBtn('属性列表', '添加属性', U('add?type_id='.$typeId));
    	$this->display();
    }
}