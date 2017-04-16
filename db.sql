USE php34;
SET NAMES utf8;

# tinyint : 0~255
# smallint : 0~ 65535
# mediumint : 0~1千6百多万
# int : 0~40多亿
# char 、varchar 、 text容量？
# char    :0~255个字符
# varchar : 0~65535 字节 看表编码，如果是utf8存2万多汉字 gbk存3万多汉字
# text    : 0~65535 字符

# 表尺寸的限制？
# 一个表中所有字段的大小加起来不能超过65535个字节
DROP TABLE IF EXISTS php34_goods;
CREATE TABLE php34_goods
(
	id mediumint unsigned not null auto_increment,
	goods_name varchar(45) not null comment '商品名称',
	cat_id smallint unsigned not null comment '主分类的id',
	brand_id smallint unsigned not null comment '品牌的id',
	market_price decimal(10,2) not null default '0.00' comment '市场价',
	shop_price decimal(10,2) not null default '0.00' comment '本店价',
	jifen int unsigned not null comment '赠送积分',
	jyz int unsigned not null comment '赠送经验值',
	jifen_price int unsigned not null comment '如果要用积分兑换，需要的积分数',
	is_promote tinyint unsigned not null default '0' comment '是否促销',
	promote_price decimal(10,2) not null default '0.00' comment '促销价',
	promote_start_time int unsigned not null default '0' comment '促销开始时间',
	promote_end_time int unsigned not null default '0' comment '促销结束时间',
	logo varchar(150) not null default '' comment 'logo原图',
	sm_logo varchar(150) not null default '' comment 'logo缩略图',
	is_hot tinyint unsigned not null default '0' comment '是否热卖',
	is_new tinyint unsigned not null default '0' comment '是否新品',
	is_best tinyint unsigned not null default '0' comment '是否精品',
	is_on_sale tinyint unsigned not null default '1' comment '是否上架：1：上架，0：下架',
	seo_keyword varchar(150) not null default '' comment 'seo优化[搜索引擎【百度、谷歌等】优化]_关键字',
	seo_description varchar(150) not null default '' comment 'seo优化[搜索引擎【百度、谷歌等】优化]_描述',
	type_id mediumint unsigned not null default '0' comment '商品类型id',
	sort_num tinyint unsigned not null default '100' comment '排序数字',
	is_delete tinyint unsigned not null default '0' comment '是否放到回收站：1：是，0：否',
	addtime int unsigned not null comment '添加时间',
	goods_desc longtext comment '商品描述',
	primary key (id),
	key shop_price(shop_price),
	key cat_id(cat_id),
	key brand_id(brand_id),
	key is_on_sale(is_on_sale),
	key is_hot(is_hot),
	key is_new(is_new),
	key is_best(is_best),
	key is_delete(is_delete),
	key sort_num(sort_num),
	key promote_start_time(promote_start_time),
	key promote_end_time(promote_end_time),
	key addtime(addtime)
)engine=MyISAM default charset=utf8;
#说明：当要使用LIKE 查询并以%开头时，不能使用普通索引，只以使用全文索引，如果使用了全文索引：
#SELECT * FROM php34_goods WHERE MATCH goods_name AGAINST 'xxxx';
# 但MYSQL自带的全文索引不支持中文，所以不能使用MYSQL自带的全文索引功能，所以如果要优化只能使用第三方的全文索引## 引擎，如：sphinx,lucence等。

DROP TABLE IF EXISTS php34_youhui_price;
CREATE TABLE php34_youhui_price
(
	goods_id mediumint unsigned not null comment '商品id',
	youhui_num int unsigned not null comment '数量',
	youhui_price decimal(10,2) not null comment '优惠价格',
	key goods_id(goods_id)
)engine=MyISAM default charset=utf8 comment '商品的优惠价格';

DROP TABLE IF EXISTS php34_goods_cat;
CREATE TABLE php34_goods_cat
(
	goods_id mediumint unsigned not null comment '商品id',
	cat_id smallint unsigned not null comment '分类id',
	key goods_id(goods_id),
	key cat_id(cat_id)
)engine=MyISAM default charset=utf8 comment '商品扩展分类表';

DROP TABLE IF EXISTS php34_brand;
CREATE TABLE php34_brand
(
	id smallint unsigned not null auto_increment,
	brand_name varchar(45) not null comment '品牌名称',
	site_url varchar(150) not null comment '品牌网站地址',
	logo varchar(150) not null default '' comment '品牌logo',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '品牌表';

DROP TABLE IF EXISTS php34_category;
CREATE TABLE php34_category
(
	id smallint unsigned not null auto_increment,
	cat_name varchar(30) not null comment '分类名称',
	parent_id smallint unsigned not null default '0' comment '上级分类的ID，0：代表顶级',
	search_attr_id varchar(100) not null default '' comment '筛选选属性ID，多个ID用逗号隔开',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '商品分类表';

########################## RBAC ################################
DROP TABLE IF EXISTS php34_privilege;
CREATE TABLE php34_privilege
(
	id smallint unsigned not null auto_increment,
	pri_name varchar(30) not null comment '权限名称',
	module_name varchar(20) not null comment '模块名称',
	controller_name varchar(20) not null comment '控制器名称',
	action_name varchar(20) not null comment '方法名称',
	parent_id smallint unsigned not null default '0' comment '上级权限的ID，0：代表顶级权限',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '权限表';

DROP TABLE IF EXISTS php34_role_privilege;
CREATE TABLE php34_role_privilege
(
	pri_id smallint unsigned not null comment '权限的ID',
	role_id smallint unsigned not null comment '角色的id',
	key pri_id(pri_id),
	key role_id(role_id)
)engine=MyISAM default charset=utf8 comment '角色权限表';

DROP TABLE IF EXISTS php34_role;
CREATE TABLE php34_role
(
	id smallint unsigned not null auto_increment,
	role_name varchar(30) not null comment '角色名称',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '角色表';

DROP TABLE IF EXISTS php34_admin_role;
CREATE TABLE php34_admin_role
(
	admin_id tinyint unsigned not null comment '管理员的id',
	role_id smallint unsigned not null comment '角色的id',
	key admin_id(admin_id),
	key role_id(role_id)
)engine=MyISAM default charset=utf8 comment '管理员角色表';

DROP TABLE IF EXISTS php34_admin;
CREATE TABLE php34_admin
(
	id tinyint unsigned not null auto_increment,
	username varchar(30) not null comment '账号',
	password char(32) not null comment '密码',
	is_use tinyint unsigned not null default '1' comment '是否启用 1：启用0：禁用',
	primary key (id)
)engine=MyISAM default charset=utf8 comment '管理员';
INSERT INTO php34_admin VALUES(1,'root','bafcbdc80e0ca50e92abe420f506456b',1);


# 角色表 role
#id    role_name
#-------------
# 1        a
# 2        b

# 权限表 privilege
#id    pri_name
#-------------
# 1        a
# 2        b
# 3        c

# a角色拥有bc两个权限
#php34_role_privilege
#role_id   pri_id
#--------------------
#   1        2              -->  1这个角色拥有2这个权限
#   1        3              -->  1这个角色拥有3这个权限

# 有以上五张表之后写SQL取出管理员ID为3的管理员所拥有的所有的权限
# 流程：1. 先取出3这个管理员所在的角色ID 
# SELECT role_id FROM  php34_admin_role WHERE admin_id=3
# 2. 再取出这些角色所拥有的权限的ID 
# SELECT pri_id FROM php34_role_privilege WHERE role_id IN (1上面的结果)
# 3. 再根据权限ID取出这些权限的信息
# SELECT * FROM php34_privilege WHERE id IN(2的结果)
# 最终：
# SELECT * FROM php34_privilege WHERE id IN(
#	SELECT pri_id FROM php34_role_privilege WHERE role_id IN (
#		SELECT role_id FROM  php34_admin_role WHERE admin_id=3
#	)
# )
# 写法二、
# SELECT a.*
#   FROM php34_privilege a,php34_role_privilege b,php34_admin_role c
#    WHERE c.admin_id=3 AND b.pri_id=a.id AND b.role_id=c.role_id
# 写法三、
# SELECT b.*
#  FROM php34_role_privilege a
#   LEFT JOIN php34_privilege b ON a.pri_id=b.id
#   LEFT JOIN php34_admin_role c ON a.role_id=c.id
#    WHERE c.admin_id=3

DROP TABLE IF EXISTS php34_type;
CREATE TABLE php34_type
(
	id tinyint unsigned not null auto_increment,
	type_name varchar(30) not null comment '类型名称',
	primary key(id)
)engine=MyISAM default charset=utf8 comment '商品类型';

DROP TABLE IF EXISTS php34_attribute;
CREATE TABLE php34_attribute
(
	id mediumint unsigned not null auto_increment,
	attr_name varchar(30) not null comment '属性名称',
	attr_type tinyint unsigned not null default '0' comment '属性的类型0：唯一 1：可选',
	attr_option_values varchar(150) not null default '' comment '属性的可选值，多个可选值用，隔开',
	type_id tinyint unsigned not null comment '所在的类型的id',
	primary key(id),
	key type_id(type_id)
)engine=MyISAM default charset=utf8 comment '属性';

DROP TABLE IF EXISTS php34_member_level;
CREATE TABLE php34_member_level
(
	id mediumint unsigned not null auto_increment,
	level_name varchar(30) not null comment '级别名称',
	bottom_num int unsigned not null comment '积分下限',
	top_num int unsigned not null comment '积分上限',
	rate tinyint unsigned not null default '100' comment '折扣率，以百分比，如：9折=90',
	primary key(id)
)engine=MyISAM default charset=utf8 comment '会员级别';

DROP TABLE IF EXISTS php34_member_price;
CREATE TABLE php34_member_price
(
	goods_id mediumint unsigned not null comment '商品的id',
	level_id mediumint unsigned not null comment '级别id',
	price decimal(10,2) not null comment '这个级别的价格',
	key goods_id(goods_id),
	key level_id(level_id)
)engine=MyISAM default charset=utf8 comment '会员级别';

DROP TABLE IF EXISTS php34_goods_pics;
CREATE TABLE php34_goods_pics
(
	id mediumint unsigned not null auto_increment,
	pic varchar(150) not null comment '图片',
	sm_pic varchar(150) not null comment '缩略图',
	goods_id mediumint unsigned not null comment '商品的id',
	primary key(id),
	key goods_id(goods_id)
)engine=MyISAM default charset=utf8 comment '商品图片';

DROP TABLE IF EXISTS php34_goods_attr;
CREATE TABLE php34_goods_attr
(
	id int unsigned not null auto_increment,
	goods_id mediumint unsigned not null comment '商品的id',
	attr_id mediumint unsigned not null comment '属性的id',
	attr_value varchar(150) not null default '' comment '属性的值',
	attr_price decimal(10,2) not null default '0.00' comment '属性的价格',
	primary key(id),
	key goods_id(goods_id),
	key attr_id(attr_id)
)engine=MyISAM default charset=utf8 comment '商品属性';

DROP TABLE IF EXISTS php34_goods_number;
CREATE TABLE php34_goods_number
(
	goods_id mediumint unsigned not null comment '商品的id',
	goods_number int unsigned not null comment '库存量',
	goods_attr_id varchar(150) not null comment '商品属性ID列表-注释：这里的ID保存的是上面php34_goods_attr表中的ID，通过这个ID即可以知道值是什么也可以是知道属性是什么,如果有多个ID组合就用，号隔开保存一个字符串，并且存时要按ID的升序存,将来前台查询库存量时也要先把商品属性ID升序拼成字符串然后查询数据库',
	key goods_id(goods_id)
)engine=InnoDB default charset=utf8 comment '商品库存量';

DROP TABLE IF EXISTS php34_member;
CREATE TABLE php34_member
(
	id mediumint unsigned not null auto_increment,
	email varchar(60) not null comment '会员账号',
	password char(32) not null comment '密码',
	face varchar(150) not null default '' comment '头像',
	addtime int unsigned not null comment '注册时间',
	email_code char(32) not null default '' comment '邮件验证的验证码，当会员验证通过之后，会把这个字段清空，所以如果这个字段为空就说明会员已经通过email验证了',
	jifen int unsigned not null default '0' comment '积分',
	jyz int unsigned not null default '0' comment '经验值',
	openid char(64) not null default '' comment '对应的QQ的openid',
	primary key(id)
)engine=MyISAM default charset=utf8 comment '会员';

/*********************** 评论表 ************************************/

DROP TABLE IF EXISTS php34_comment;
CREATE TABLE php34_comment
(
	id mediumint unsigned not null auto_increment,
	content varchar(1000) not null comment '评论的内容',
	star tinyint unsigned not null default '3' comment '打的分',
	addtime int unsigned not null comment '评论时间',
	member_id mediumint unsigned not null comment '会员ID',
	goods_id mediumint unsigned not null comment '商品的ID',
	used smallint unsigned not null default '0' comment '有用的数量',
	primary key(id),
	key goods_id(goods_id)
)engine=MyISAM default charset=utf8 comment '评论';

DROP TABLE IF EXISTS php34_reply;
CREATE TABLE php34_reply
(
	id mediumint unsigned not null auto_increment,
	content varchar(1000) not null comment '回复的内容',
	addtime int unsigned not null comment '回复时间',
	member_id mediumint unsigned not null comment '会员ID',
	comment_id mediumint unsigned not null comment '评论的ID',
	primary key(id),
	key comment_id(comment_id)
)engine=MyISAM default charset=utf8 comment '回复';

DROP TABLE IF EXISTS php34_clicked_use;
CREATE TABLE php34_clicked_use
(
	member_id mediumint unsigned not null comment '会员ID',
	comment_id mediumint unsigned not null comment '评论的ID',
	primary key (member_id,comment_id) # 因为这两个字段肯定查询时会一起用，不会一个一个查所以直接创建联合索引
)engine=MyISAM default charset=utf8 comment '用户点击过有用的评论';

# 判断1这个会员是否点击过3这个评论
# SELECT COUNT(*) FROM php34_clicked_use WHERE member_id=1 AND comment_id=3

DROP TABLE IF EXISTS php34_impression;
CREATE TABLE php34_impression
(
	id mediumint unsigned not null auto_increment,
	imp_name varchar(30) not null comment '印象的标题',
	imp_count smallint unsigned not null default '1' comment '印象出现的次数',
	goods_id mediumint unsigned not null comment '商品ID',
	primary key(id),
	key goods_id(goods_id)
)engine=MyISAM default charset=utf8 comment '印象';

DROP TABLE IF EXISTS php34_cart;
CREATE TABLE php34_cart
(
	id mediumint unsigned not null auto_increment,
	goods_id mediumint unsigned not null comment '商品ID',
	goods_attr_id varchar(30) not null default '' comment '选择的商品属性ID，多个用，隔开',
	goods_number int unsigned not null comment '购买的数量',
	member_id mediumint unsigned not null comment '会员id',
	primary key(id),
	key member_id(member_id)
)engine=MyISAM default charset=utf8 comment '购物车';

DROP TABLE IF EXISTS php34_order;
CREATE TABLE php34_order
(
	id mediumint unsigned not null auto_increment,
	member_id mediumint unsigned not null comment '会员id',
	addtime int unsigned not null comment '下单时间',
	shr_name varchar(30) not null comment '收货人姓名',
	shr_province varchar(30) not null comment '省',
	shr_city varchar(30) not null comment '市',
	shr_area varchar(30) not null comment '地区',
	shr_tel varchar(30) not null comment '收货人电话',
	shr_address varchar(30) not null comment '收货人地址',
	total_price decimal(10,2) not null comment '定单总价',
	post_method varchar(30) not null comment '发货方式',
	pay_method varchar(30) not null comment '支付方式',
	pay_status tinyint unsigned not null default '0' comment '支付状态，0：未支付 1：已支付',
	post_status tinyint unsigned not null default '0' comment '发货状态，0：未发货 1：已发货 2：已收到货',
	primary key(id),
	key member_id(member_id)
)engine=InnoDB default charset=utf8 comment '定单基本信息';

DROP TABLE IF EXISTS php34_order_goods;
CREATE TABLE php34_order_goods
(
	order_id mediumint unsigned not null comment '定单id',
	member_id mediumint unsigned not null comment '会员id',
	goods_id mediumint unsigned not null comment '商品ID',
	goods_attr_id varchar(30) not null default '' comment '选择的属性的ID，如果有多个用，隔开',
	goods_attr_str varchar(150) not null default '' comment '选择的属性的字符串',
	goods_price decimal(10,2) not null comment '商品的价格',
	goods_number int unsigned not null comment '购买的数量',
	key order_id(order_id),
	key goods_id(goods_id),
	key member_id(member_id)
)engine=InnoDB default charset=utf8 comment '定单商品';










