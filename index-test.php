<?php
/**
 * This is the bootstrap file for test application.
 * This file should be removed when the application is deployed for production.
 */

// change the following paths if necessary
// $yii=dirname(__FILE__).'/../yii/yii.php';
// $config=dirname(__FILE__).'/protected/config/test.php';

// // remove the following line when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);

// require_once($yii);
// Yii::createWebApplication($config)->run();
// 
// 
// 
        // $criteria = new CDbCriteria;
        // $criteria->select = '*';
        // $criteria->order = 't.id desc';
        // $criteria->with = 'room';
        // $model = new CActiveDataProvider('Student',array(
        //     'criteria'=>$criteria,
        //     'pagination'=>array('pageSize'=>10), 
        // ));
        // $this->render('index',array('dataProvider'=>$model));
// 
// 
// 
// 
// 

function p($data,$status=NULL){
    echo '<pre/>';
    if (($data==NULL) || $status)
        var_dump($data);
    else
        print_r($data);
    die;
}

