<?php

function makePeriod($data) {
	if ($data == 'YEAR(`date`) = YEAR(CURDATE())') {
		return "DATE(`date`) BETWEEN '".date('Y')."-01-01' AND '".date("Y-m-d")."'";
	} elseif ($data == 'QUARTER(`date`) = QUARTER(CURDATE())') {
		return "DATE(`date`) BETWEEN '".date("Y-m-d", strtotime(date('Y')."-".(intdiv(intval(date('m')), 3) + 1)."-01"))."' AND '".date("Y-m-d")."'";
	} elseif ($data == 'MONTH(`date`) = MONTH(CURDATE())') {
		return "DATE(`date`) BETWEEN '".date('Y')."-".date('m')."-01' AND '".date("Y-m-d")."'";
	} elseif ($data == 'WEEK(`date`) = WEEK(CURDATE())') {
		return "DATE(`date`) BETWEEN '".date('Y-m-d', strtotime('-'.(intval(date('w')) - 1).' days'))."' AND '".date("Y-m-d")."'";
	} elseif ($data == 'DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)') {
		return "DATE(`date`) BETWEEN '".date('Y-m-d', strtotime("-1 days"))."' AND '".date('Y-m-d', strtotime("-1 days"))."'";
	} elseif ($data == '`date` >= CURDATE()') {
		return "DATE(`date`) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'";
	} else {
		return $data;
	}
}
