<?php

//声明命名空间
namespace Admin\Controller;

use \Frame\Libs\BaseController;
use \Admin\Model\ArticleModel;
use \Admin\Model\CategoryModel;

//定义最终的文章控制器类，并继承基础控制器类
final class BooksController extends BaseController
{

    //显示文章列表数据
    public function index()
    {
        echo "111";
        $this->smarty->display("Book/index.html");
    }

}