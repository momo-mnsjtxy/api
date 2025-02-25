<?
header('Content-type:application/json;charset=UTF-8');
$res = @file_get_contents('https://c.m.163.com/ug/api/wuhan/app/data/list-total');
$GET_JSON = json_decode(trim($res,chr(239).chr(187).chr(191)),true);
$arr = [
	'lastUpdateTime' => $GET_JSON['data']['lastUpdateTime'],
	'today' => [
		'confirm' => $GET_JSON['data']['chinaTotal']['today']['confirm'],
		'suspect' => $GET_JSON['data']['chinaTotal']['today']['suspect'],
		'heal' => $GET_JSON['data']['chinaTotal']['today']['heal'],
		'dead' => $GET_JSON['data']['chinaTotal']['today']['dead'],
		'severe' => $GET_JSON['data']['chinaTotal']['today']['severe'],
		'storeConfirm' => $GET_JSON['data']['chinaTotal']['today']['storeConfirm'],
	],
	'total' => [
		'confirm' => $GET_JSON['data']['chinaTotal']['total']['confirm'],
		'suspect' => $GET_JSON['data']['chinaTotal']['total']['suspect'],
		'heal' => $GET_JSON['data']['chinaTotal']['total']['heal'],
		'dead' => $GET_JSON['data']['chinaTotal']['total']['dead'],
		'severe' => $GET_JSON['data']['chinaTotal']['total']['severe'],
		'storeConfirm' => $GET_JSON['data']['chinaTotal']['total']['confirm']-$GET_JSON['data']['chinaTotal']['total']['heal']-$GET_JSON['data']['chinaTotal']['total']['dead'],
	]
];
print_r(json_encode($arr));
?>