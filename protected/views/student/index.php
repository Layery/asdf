<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
    'pager' => array(
        'class' => 'CLinkPager', // 定义要调用的分页类
        'firstPageLabel'=>'首页',
        'lastPageLabel' => '末页',
        'prevPageLabel' =>'上一页',
        'nextPageLabel'=>'下一页',

    ),
)); ?>


