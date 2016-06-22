<?
Yii::app()->clientScript->registerCoreScript('jquery');
?>
<div class="form">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <form id="student-form" action="index.php?r=Student/Update&id=<?echo $item->id;?>" method="POST">
    <div class="row">
        *学生姓名: <input type="text" name='Student[name]' id="Student_name" value="<? echo $item->name;?>" />  
    </div>  
    
    <? if ($item->sex == 1){ ?>
     <div class="row" id="sex">
        *性别:　　男:<input type="radio" name="Student[sex]" class="Student_sex" value="1" checked="checked">女:<input type="radio" name="Student[sex]" class="Student_sex" value="2">
     </div>
    
    <? }else{ ?>
    <div class="row" id="sex">
        *性别:　　男:<input type="radio" name="Student[sex]" class="Student_sex" value="1">女:<input type="radio" name="Student[sex]" class="Student_sex" value="2" checked="checked">
    </div>
    <?}?>
    
    <div class="row">
        *年龄:　　<input type="text" name='Student[age]' id="Student_age" value="<?echo $item->age;?>" />  
    </div>  
    
    <div class="row">
        *邮箱: 　　<input type="text" name="Student[email]" id="Student_email" value="<?echo $item->email;?>">
    </div>

    <div class="row">
        *手机号: 　<input type="text" name="Student[mobile]" id="Student_mobile" value="<?echo $item->mobile;?>">
    </div>

    <div class="row">
        QQ: 　　<input type="text" name="Student[qq]" id="Student_qq" value="<?echo $item->qq;?>"/>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Update'); ?>
    </div>
    </form>

</div> 
<script type="text/javascript">
   $("#sex input").click(function(){
      $(this).attr('checked','checked');
      $(this).siblings('input').removeAttr('checked','checked');//attr('check','haha');
   });
</script>

