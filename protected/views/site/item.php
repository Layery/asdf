<?

// print_r($item->attributes['id']);

// print_r($item->attributes['name']);



?>


<form action="index.php?r=site/edit" method="POST">
id　　　: <?echo $item['id'];?><br/><br/>
<input type="hidden" name="id" value="<?echo $item['id'];?>">
班级名称: <input type="text" name="name" value="<?echo $item['name'];?>"><br/>
<input type="submit" value = "提交">
</form>




