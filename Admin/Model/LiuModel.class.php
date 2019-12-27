<?php
//声明命名空间
namespace Admin\Model;
use \Frame\Libs\BaseModel;

//定义最终的文章模型类，并继承基础模型类
final class LiuModel extends BaseModel{

	//受保护的数据表名称
	protected $table = "liu";

	//获取连表查询的分页数据
	public function fetchAllWithJoin($startrow=0,$pagesize=10)
	{
		//构建连表查询的SQL语句
		$sql = "SELECT *  FROM {$this->table} ";
	//	$sql .= "ORDER BY id DESC ";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}
}