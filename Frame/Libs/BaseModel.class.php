<?php
//声明命名空间
namespace Frame\Libs;
use \Frame\Vendor\PDOWrapper;

//定义抽象的基础模型类
abstract class BaseModel{

	//受保护的$pdo对象属性
	protected $pdo = NULL;

	//构造方法
	public function __construct()
	{
		$this->pdo = new PDOWrapper();
	}
	
	//私有的静态的保存不同模型类对象的数组属性
	private static $arrModelObj = array();

	//公共的静态的创建模型类对象的方法
	public static function getInstance()
	{
		//获取静态化方式调用的类名(后期静态绑定类名)
		$modelClassName = get_called_class();

		//判断当前模型类对象是否存在
		/*
			$arrModelObj['\Home\Model\StudentModel'] = 学生模型类对象
			$arrModelObj['\Home\Model\ProductModel'] = 产品模型类对象
		 */
		if(!isset(self::$arrModelObj[$modelClassName])){
			//如果当前模型类对象不存在，则创建并保存它
			self::$arrModelObj[$modelClassName] = new $modelClassName();
		}
		//返回当前模型类的对象
		return self::$arrModelObj[$modelClassName];
	}

	//获取单行数据
	public function fetchOne($where="2>1")
	{
		//构建查询的SQL语句
		$sql = "SELECT * FROM {$this->table} WHERE {$where} LIMIT 1";
		//执行SQL语句，并返回结果
		return $this->pdo->fetchOne($sql);
	}

	//获取多行数据
	public function fetchAll()
	{
		//构建查询的SQL语句
		$sql = "SELECT * FROM {$this->table}";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//删除记录
	public function delete($id)
	{
		//构建删除的SQL语句
		$sql = "DELETE FROM {$this->table} WHERE id={$id}";
		//执行SQL语句，并返回结果(整数)
		return $this->pdo->exec($sql);
	}

	//插入数据
	public function insert($data)
	{
		//构建字符名列表和字段值列表字符串
		$fields = "";
		$values = "";
		foreach($data as $key=>$value)
		{
			$fields .= "$key,";
			$values .= "'$value',";
		}
		//去除字符串结尾的逗号
		$fields = rtrim($fields,",");
		$values = rtrim($values,",");

		//构建插入的SQL语句
		$sql = "INSERT INTO {$this->table}($fields) VALUES($values)";
		//执行SQL语句，并返回结果(整型)
		return $this->pdo->exec($sql);
	}

	//更新数据
	public function update($data,$id)
	{
		//构建更新的字段字符串
		$str = "";
		foreach($data as $key=>$value)
		{
			$str .= "$key='$value',";
		}
		//去除字符串结尾的逗号
		$str = rtrim($str,",");	
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET {$str} WHERE id=$id";
		//执行SQL语句，并返回结果(整型)
		return $this->pdo->exec($sql);		
	}

	//获取记录数
	public function rowCount($where="2>1")
	{
		//构建查询的SQL语句
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		//执行SQL语句，返回记录数
		return $this->pdo->rowCount($sql);
	}
}