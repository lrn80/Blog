<?php
//声明命名空间
namespace Admin\Model;
use \Frame\Libs\BaseModel;

//定义最终的用户模型类，并继承基础模型类
final class UserModel extends BaseModel{

	//受保护的数据表名称
	protected $table = "user";
}