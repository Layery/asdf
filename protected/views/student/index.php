<?php
/* @var $this SiteController */
Yii::app()->clientScript->registerCoreScript('jquery');
$this->pageTitle=Yii::app()->name;

?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<a href="index.php?r=Student/Create"><input type="button" value="添加"></a><br/><br/>
<table>
    <tr>
        <form action="index.php?r=student/list" method="POST">
           <td>班级:<input type="text" name="roomName" style="width: 50px;"></td>
           <td>姓名: <input type="text" name="studentName" style="width: 50px;"></td>
           <td><input type="submit" value="查询"></td>
        </form>
    </tr>
    <tr><td>学生ID</td><td>所属班级</td><td>姓名</td><td>年龄</td><td>手机</td><td>QQ</td><td>操作</td></tr>
    <?php foreach($students as $v){ ?>
       <tr>
          <td><? echo $v['id'];?></td>
          <td><? print_r($v->room->attributes['name']);?></td>
          <td><? echo $v['name'];?></td>
          <td><? echo $v['age'];?></td>
          <td><? echo $v['mobile'];?></td>
          <td><? echo $v['qq'];?></td>
          <td>
              <span><?php echo CHtml::link('编辑',array('Student/Update','id'=>$v['id']));?></span>
              <span class="del" ><?php echo CHtml::link('删除',array('Student/Delete','id'=>$v['id']));?></span>
          </td>
       </tr>
    <?php }?>
</table>


<script type="text/javascript">
  $(".del").click(function(){
     if (!confirm('确认删除?')) {
        return false;
     }else {
        return true;
     }
  });
</script>











































