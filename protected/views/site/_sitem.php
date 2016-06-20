<!-- 
<? foreach($data->students as $v){ ?>
   <div class="view"><? echo $v->attributes['name']; ?></div><br/>
<?}?> -->
    

<table>
    <tr><input type="text"><td></td><td></td><td></td></tr>
    <tr><td>学生ID</td><td>学生名称</td><td>年龄</td></tr>
    <?php foreach($data->students as $v){ ?>
       <tr>
          <td><? echo $v['id'];?></td>
          <td><? echo $v->attributes['name']; ?></td>
          <td><? echo $v->attributes['age']; ?></td>
       </tr>
    <?php }?>
</table>




