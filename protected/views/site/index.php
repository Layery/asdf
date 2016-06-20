<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>


<a href="index.php?r=site/create"><input type="button" value="添加"></a><br/><br/>
<table>
    <tr><input type="text"><td></td><td></td><td></td></tr>
    <tr><td>班级ID</td><td>班级名称</td><td>操作</td></tr>
    <?php foreach($classList as $v){ ?>
       <tr>
          <td><? echo $v['id'];?></td>
          <td><? echo $v['name'];?></td>
          <td>
              <span><?php echo CHtml::link('学生',array('site/view','id'=>$v['id']));?></span>
              <span><?php echo CHtml::link('编辑',array('site/edit','id'=>$v['id']));?></span>
              <span><?php echo CHtml::link('删除',array('site/delet','id'=>$v['id']));?></span>
          </td>
       </tr>
    <?php }?>
</table>
