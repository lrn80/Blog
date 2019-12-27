<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义最终的友情链接模型类，并继承基础模型类
final class LinksModel extends BaseModel{

	//受保护的数据表名称
	protected $table = "link2";
}