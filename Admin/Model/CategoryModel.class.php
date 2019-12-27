<?php
//声明命名空间
namespace Admin\Model;
use \Frame\Libs\BaseModel;

//定义最终的文章分类模型类，并继承基础模型类
final class CategoryModel extends BaseModel{

	//受保护的数据表名称
	protected $table = "category";

	//获取无限级分类数据
	public function categoryList($arrs,$level=0,$pid=0)
	{
		//静态变量，用来保存结果数组
		//静态变量：函数或方法执行完毕，该变量不销毁
		//静态变量：只在第1次调用函数或方法时，初始化一次，以后不再初始化
		//$arrs代表原始分类数据；$level代表菜单层级；$pid代表上层的id
		static $categorys = array();

		//循环原始分类数据
		foreach($arrs as $arr)
		{
			//如果当前菜单的pid等于参数$pid，给新数组添加新元素
			if($arr['pid']==$pid)
			{
				$arr['level'] = $level; //菜单层级
				$categorys[] = $arr; //将添加了菜单层级的元素，追加新数组中

				//方法的递归调用
				$this->categoryList($arrs,$level+1,$arr['id']);
			}
		}

		//返回无限级分类数组
		return $categorys;
	}
}