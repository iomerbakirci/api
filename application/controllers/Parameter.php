<?php

/*
 *
 *	CITY
 *
 */
$app->map('/parameter/city', function($id) use ($app) {
	$tbl = new TblCity();
	$cities = $tbl->select();

	$res = array('status' => 'success', 'cities' => $cities);
	$app->view()->setData('result', $res);
})->via("GET", "POST");