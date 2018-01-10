<?php
require 'Image.php';
//传入将要操作的图片
$img = new Image('./images/taylor.jpg');
//缩放图片
$img->resize(500,400);
//加盖水印 传入logo图, logo距离原图左边的距离 logo距离原图左边的距离 与透明度 0~1之间的float
$img->mark('./images/logo.jpg', 20, 20, .3);
//显示图片 如传入路径 则保存图片至该路径
$img->show();
//保存图片 若传入路径 则保存图片至该路径 默认保存到./default文件夹下 并且会return 保存后的路径
$img->save();

