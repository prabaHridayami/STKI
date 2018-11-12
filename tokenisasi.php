<?php

function jumlah_kata($kata){
	$text_token 	= preg_replace("/[^A-Za-z0-9- ]/","", $kata);
	$trim_token 	= trim($text_token);
	$kata_token   	= explode(" ",$text_token);
	$data_token   	= count($kata_token);

	return $data_token;
}

function tokenisasi($kata){
	$text_token 	= preg_replace("/[^A-Za-z0-9- ]/","", $kata);
	$trim_token 	= trim($text_token);
	$kata_token   	= explode(" ",$text_token);
	$data_token   	= array_count_values($kata_token);

	return $data_token;
}

function tokenisasinocount($kata){
	$text_token 	= preg_replace('/[^A-Za-z0-9- ]/','', $kata);
	$trim_token 	= trim($text_token);
	$data_token   	= explode(" ",$text_token);
	// $data_token   	= array_count_values($kata_token);

	return $data_token;
}

function splitkalimat($kata){
	$text_token 	= preg_replace("/[^A-Za-z0-9-. ]/","", $kata);
	// $trim_token 	= trim($text_token);
	$kata_token   	= explode(".",$text_token);
	$data_token   	= $kata_token;

	return $data_token;
}

function splitkalimat1($kata){
	// $text_token 	= preg_replace("/[^A-Za-z0-9-. ]/","", $kata);
	// $trim_token 	= trim($text_token);
	$kata_token   	= explode(".",$text_token);
	$data_token   	= $kata_token;

	return $data_token;
}

function splitparagraf($kata){
	$trim_token 	= trim($kata);
	$kata_token   	= explode("\r\n", $trim_token);
	$data_token   	= $kata_token;

	return $data_token;
}
?>