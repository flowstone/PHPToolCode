<!DOCTYPE html>
<html>
<body>
<head>
<title>分页测试页面</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="bootstrap.css" />
</head>

	<?php

		require 'Page.class.php';
		$page = new Page();
		$page->_page_now = isset($_GET['page']) ? $_GET['page'] : 1;
		$page->_page_size = 5;
		require 'DAOMySQLi.class.php';
		$info = array(
		'host' => 'localhost',
		'user' => 'root',
		'pwd' => '123456789',
		'db' => 'ecshop',
		'port' => '3306',
		'charset' => 'utf8'
		);
		$mysqli = DAOMySQLi::getSingleton($info);
		$sql = "SELECT COUNT(*) AS num FROM `ecs_goods`";
		$row = $mysqli->fetchOne($sql);
		$page->_total = $row['num'];
		$page->_url = 'testpage.php';

		$page_start = ($page->_page_now-1) * $page->_page_size;
		$page_size = $page->_page_size;
		$sql2 = "SELECT goods_id,goods_name,shop_price FROM `ecs_goods` LIMIT $page_start,$page_size";
		$shop_list = $mysqli->fetchAll($sql2);
		$page_html = $page->create();
		
		require 'showShop.html';
	?>
</body>
</html>
