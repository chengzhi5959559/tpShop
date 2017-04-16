-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 04 月 23 日 10:03
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `php34`
--

-- --------------------------------------------------------

--
-- 表的结构 `php34_privilege`
--

CREATE TABLE IF NOT EXISTS `php34_privilege` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(10) NOT NULL COMMENT '模块名称',
  `controller_name` varchar(10) NOT NULL COMMENT '控制器名称',
  `action_name` varchar(20) NOT NULL COMMENT '方法名称',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级权限的ID，0：代表顶级权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限表' AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `php34_privilege`
--

INSERT INTO `php34_privilege` (`id`, `pri_name`, `module_name`, `controller_name`, `action_name`, `parent_id`) VALUES
(1, '商品列表', 'Admin', 'Goods', 'lst', 3),
(2, '添加商品', 'Admin', 'Goods', 'add', 1),
(3, '商品管理', 'null', 'null', 'null', 0),
(4, '修改商品', 'Admin', 'Goods', 'edit', 1),
(5, '删除商品', 'Admin', 'Goods', 'delete', 1),
(6, '商品分类列表', 'Admin', 'Category', 'lst', 3),
(7, '添加分类', 'Admin', 'Category', 'add', 6),
(8, '修改分类', 'Admin', 'Category', 'edit', 6),
(9, '删除分类', 'Admin', 'Category', 'delete', 6),
(10, '权限管理', 'null', 'null', 'null', 0),
(11, '权限列表', 'Admin', 'Privilege', 'lst', 10),
(12, '添加权限', 'Admin', 'Privilege', 'add', 11),
(13, '修改权限', 'Admin', 'Privilege', 'edit', 11),
(14, '删除权限', 'Admin', 'Privilege', 'delete', 11),
(15, '角色列表', 'Admin', 'Role', 'lst', 10),
(16, '添加角色', 'Admin', 'Role', 'add', 15),
(17, '修改角色', 'Admin', 'Role', 'edit', 15),
(18, '删除角色', 'Admin', 'Role', 'delete', 15),
(19, '管理员列表', 'Admin', 'Admin', 'lst', 10),
(20, '添加管理员', 'Admin', 'Admin', 'add', 19),
(21, '修改管理员', 'Admin', 'Admin', 'edit', 19),
(22, '删除管理员', 'Admin', 'Admin', 'delete', 19),
(23, 'ajax修改状态', 'Admin', 'Admin', 'ajaxUpdateIsuse', 19),
(24, '类型列表', 'Admin', 'Type', 'lst', 3),
(25, '添加类型', 'Admin', 'Type', 'add', 24),
(26, '修改类型', 'Admin', 'Type', 'edit', 24),
(27, '删除类型', 'Admin', 'Type', 'delete', 24),
(28, '属性列表', 'Admin', 'Attribute', 'lst', 24),
(29, '添加属性', 'Admin', 'Attribute', 'add', 28),
(30, '修改属性', 'Admin', 'Attribute', 'edit', 28),
(31, '删除属性', 'Admin', 'Attribute', 'delete', 28);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
