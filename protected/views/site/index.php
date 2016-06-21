<?php
/* @var $this SiteController */
Yii::app()->clientScript->registerCoreScript('jquery');
$this->pageTitle=Yii::app()->name;




?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<a href="index.php?r=site/create"><input type="button" value="添加"></a><br/><br/>
<table>
    <tr><td>班级ID</td><td>班级名称</td><td>学生总数</td><td>操作</td></tr>
    <?php foreach($classList as $v){ ?>
       <tr>
          <td><? echo $v['id'];?></td>
          <td><? echo $v['name'];?></td>
          <td><? echo $v['s_number'];?></td>
          <td>
              <span><?php echo CHtml::link('学生',array('site/View','id'=>$v['id']));?></span>
              <span><?php echo CHtml::link('编辑',array('site/Update','id'=>$v['id']));?></span>
              <span class="del" ><?php echo CHtml::link('删除',array('site/Delete','id'=>$v['id']));?></span>
          </td>
       </tr>
    <?php }?>
</table>


<script type="text/javascript">
  $(".del").click(function(){
     if (!confirm('确认删除?')) {
        return false;
     };
  });
</script>