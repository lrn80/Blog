<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义最终的文章模型类，并继承基础模型类
final class ArticleModel extends BaseModel{

	//受保护的数据表名称
	protected $table = "article";

	//获取文章按月份归档的数据
	public function fetchAllWithMonth()
	{
		//构建查询的SQL语句
		$sql = "SELECT date_format(from_unixtime(addate),'%Y年%m月') AS months,count(id) AS records ";
		$sql .= "FROM {$this->table} ";
		$sql .= "GROUP BY months ";
		$sql .= "ORDER BY months DESC";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//获取连表查询的文章数据
	public function fetchAllWithJoin($where="2>1",$startrow=0,$pagesize=10)
	{
		//构建查询的SQL语句
		$sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY article.id DESC ";
		$sql .= "LIMIT {$startrow},{$pagesize}";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//获取指定id的连表查询的文章数据
	public function fetchOneWithJoin($where="2>1",$orderby="article.id ASC")
	{
		//构建查询的SQL语句
		$sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY {$orderby}";
		//执行SQL语句，并返回结果(一维数组)
		return $this->pdo->fetchOne($sql);
	}

	//更新阅读数
	public function updateRead($id)
	{
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET `read1` = `read1`+1 WHERE id=$id";
		//执行SQL语句，并返回结果
		return $this->pdo->exec($sql);
	}

	//更新点赞数
	public function updatePraise($id)
	{
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET `praise` = `praise`+1 WHERE id=$id";
		//执行SQL语句，并返回结果
		return $this->pdo->exec($sql);
	}
	//点击排行
	public function fetchAllWithPaiHang(){
		$sql="SELECT id,title,read1 FROM {$this->table} ";
		$sql.="ORDER BY read1 DESC";
		return $this->pdo->fetchAll($sql);
	}
}
