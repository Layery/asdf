<div class="form">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <form id="student-form" action="index.php?r=Student/Create" method="POST">
    <div class="row">
  班级：<select name="Student[c_id]" id="Student_c_id">
            <option value="0">请选择班级</option>
            <?if (empty($room)) { ?>
               <option value="0">请选择班级</option>
            <?}else{foreach ($room as $v) { ?>
               <option value="<?echo $v['id'];?>"><?echo $v['name'];?></option>
            <?}}?>

        </select>
        &nbsp;&nbsp;&nbsp;<span><? print_r($model->getError('c_id'));?></span>
    </div>
    <div class="row">
        *学生姓名: <input type="text" name='Student[name]' id="Student_name" />
        &nbsp;&nbsp;&nbsp;<span><? print_r($model->getError('name'));?></span>
    </div>  
    
    <div class="row" id="sex">
        *性别:　　男:<input type="radio" name="Student[sex]" class="Student_sex" value="1">女:<input type="radio" name="Student[sex]" class="Student_sex" value="2">&nbsp;&nbsp;&nbsp;
        <span><? print_r($model->getError('sex'));?></span>
    </div>
    
    <div class="row">
        年龄:　　<input type="text" name='Student[age]' id="Student_age" />
        &nbsp;&nbsp;&nbsp;<span><? print_r($model->getError('age'));?></span>
    </div>  
    
    <div class="row">
        *邮箱: 　　<input type="text" name="Student[email]" id="Student_email">
        &nbsp;&nbsp;&nbsp;<span><? print_r($model->getError('email'));?></span>
    </div>

    <div class="row">
        *手机号: 　<input type="text" name="Student[mobile]" id="Student_mobile">
        &nbsp;&nbsp;&nbsp;<span><? print_r($model->getError('mobile'));?></span>
    </div>

    <div class="row">
        *QQ: 　　<input type="text" name="Student[qq]" id="Student_qq" />
        &nbsp;&nbsp;&nbsp;<span><? print_r($model->getError('qq'));?></span>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Create'); ?>
    </div>
    </form>

</div> 