<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class CurlController extends Controller
{
    public function actionIndex()
    {
        $curl = new MyCurl();
        $url = 'http://www.taobao.com';
        $curl->init();
        $rs = $curl->put($url,'哈哈哈',array('k'=>'value'));
        p($rs);

    }

}
