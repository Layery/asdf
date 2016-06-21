<div class="form">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <form id="student-form" action="index.php?r=Student/Create" method="POST">
    <div class="row">
  班级：<select name="Student[c_id]" id="Student_c_id">
            <?if (empty($room)) { ?>
               <option value="0">请选择班级</option>
            <?}else{foreach ($room as $v) { ?>
               <option value="<?echo $v['id'];?>"><?echo $v['name'];?></option>
            <?}}?>

        </select>
    </div>
    <div class="row">
        学生姓名: <input type="text" name='Student[name]' id="Student_name" />  
    </div>  
    
    <div class="row" id="sex">
        性别:　　男:<input type="radio" name="Student[sex]" class="Student_sex" value="1">女:<input type="radio" name="Student[sex]" class="Student_sex" value="2">
    </div>
    
    <div class="row">
        年龄:　　<input type="text" name='Student[age]' id="Student_age" />  
    </div>  
    
    <div class="row">
        邮箱: 　　<input type="text" name="Student[email]" id="Student_email">
    </div>

    <div class="row">
        手机号: 　<input type="text" name="Student[mobile]" id="Student_mobile">
    </div>

    <div class="row">
        QQ: 　　<input type="text" name="Student[qq]" id="Student_qq" />
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Create'); ?>
    </div>
    </form>

</div> 