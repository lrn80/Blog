<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\LinksModel;

//定义最终的友情链接控制器类，并继承基础控制器类
final class LinksController extends BaseController{

	//显示友情链接首页
	public function index()
	{
		//获取多行数据
		$links = LinksModel::getInstance()->fetchAll();
		//向视图赋值，并显示视图
		$this->smarty->assign("links",$links);
		$this->smarty->display("Links/index.html");
	}

	//显示添加的表单
	public function add()
	{
		$this->smarty->display("Links/add.html");
	}

	//插入数据
	public function insert()
	{
		//获取表单提交值
		$data['domain']		= $_POST['domain'];
		$data['url']		= $_POST['url'];
		$data['orderby']	= $_POST['orderby'];
		//判断数据是否写入成功
		if(LinksModel::getInstance()->insert($data))
		{
			$this->jump("数据插入成功！","?c=Links");
		}else{
			$this->jump("数据插入失败！","?c=Links");
		}
	}

	//显示修改的表单
	public function edit()
	{
		$id = $_GET['id'];
		$link = LinksModel::getInstance()->fetchOne("id={$id}");
		$this->smarty->assign("link",$link);
		$this->smarty->display("Links/edit.html");
	}

	//更新数据
	public function update()
	{
		//获取表单提交值
		$id = $_POST['id'];
		$data['domain']		= $_POST['domain'];
		$data['url']		= $_POST['url'];
		$data['orderby']	= $_POST['orderby'];
		//判断数据是否更新成功
		if(LinksModel::getInstance()->update($data,$id))
		{
			$this->jump("数据更新成功！","?c=Links");
		}else{
			$this->jump("数据更新失败！","?c=Links");
		}
	}

	//删除数据
	public function delete()
	{
		$id = $_GET['id'];
		//判断数据是否删除成功
		if(LinksModel::getInstance()->delete($id))
		{
			$this->jump("数据删除成功！","?c=Links");
		}else{
			$this->jump("数据删除失败！","?c=Links");
		}
	}
}