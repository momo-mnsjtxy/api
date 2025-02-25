<?php
$data = [];
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 2097152)   // 小于 200 kb
&& in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
    	$data['code'] = 0;
    	$data['msg'] = "错误：: " . $_FILES["file"]["error"];
    }
    else
    {
    	$data['code'] = 1;
        $data['name'] = $_FILES["file"]["name"];
        $data['type'] = $_FILES["file"]["type"];
        $data['size'] = ($_FILES["file"]["size"] / 1024);
        $data['tmp_name'] = $_FILES["file"]["tmp_name"];
        if (0/*file_exists("images/" . $_FILES["file"]["name"])*/)
        {
            echo $_FILES["file"]["name"] . " 文件已经存在。 ";
        }
        else
        {
            $filename = rand(10000,99999).time().$_FILES["file"]["name"];
            move_uploaded_file($_FILES["file"]["tmp_name"], "./images/" . $filename);
            $data['url'] = "https://api.gqink.cn/tool/upload/images/" . $filename;
        }
    }
}
else
{
	$data['msg'] = "Error";
}
header("content-type:application/json;charset=utf-8");
echo json_encode($data);
?>