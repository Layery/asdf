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
        p(get_class_methods($curl));
    }

}
