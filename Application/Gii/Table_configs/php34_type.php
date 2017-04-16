<?php
return array(
	'tableName' => 'php34_type',    // 表名
	'tableCnName' => '类型',  // 表的中文名
	'moduleName' => 'Admin',  // 代码生成到的模块
	'digui' => 0,             // 是否无限级（递归）
	'diguiName' => '',        // 递归时用来显示的字段的名字，如cat_name（分类名称）
	'pk' => 'id',    // 表中主键字段名称
	/************** 生成的权限数据放到哪个顶级权限下 ******************/
	'topPriName' => '商品管理',    // 顶级权限的名称
	/********************* 要生成的模型文件中的代码 ******************************/
	'insertFields' => "array('type_name')",
	'updateFields' => "array('id','type_name')",
	'validate' => "
		array('type_name', 'require', '类型名称不能为空！', 1, 'regex', 3),
		array('type_name', '1,30', '类型名称的值最长不能超过 30 个字符！', 1, 'length', 3),
	",
	/********************** 表中每个字段信息的配置 ****************************/
	'fields' => array(
		'type_name' => array(
			'text' => '类型名称',
			'type' => 'text',
			'default' => '',
		),
	),
);