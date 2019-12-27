<?php
//声明命名空间
namespace Home\Controller;
use \Frame\Libs\BaseController;
use \Home\Model\LiuModel;
final class LiuController extends BaseController{
    //插入数据
	public function insert()
	{
        //获取表单提交数据
        $data['comment']		    = $_POST['comment'];
        $data['Cus_name']		    = $_POST['Cus_name'];
        $data['Cus_email']		    = $_POST['Cus_email'];
		$data['addtime']			= time();
		//插入数据
		if(LiuModel::getInstance()->insert($data))
		{
			$this->jump("留言添加成功！","?c=index&a=gbook");
		}
	}
}