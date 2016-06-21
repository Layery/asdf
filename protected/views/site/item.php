
<form action="index.php?r=site/Update&id=<?echo $item['id'];?>" method="POST">
id　　　: <?echo $item['id'];?><br/><br/>
<input type="hidden" name="id" value="<?echo $item['id'];?>">
班级名称: <input type="text" name="name" value="<?echo $item['name'];?>"><br/>
<input type="submit" name="sub" value = "提交">
</form>




