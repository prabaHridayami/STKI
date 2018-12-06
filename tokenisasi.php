<?php

function jumlah_kata($kata){
	$text_token 	= preg_replace("/[^A-Za-z0-9- ]/","", $kata);
	$trim_token 	= trim($text_token);
	$kata_token   	= explode(" ",$text_token);
	$data_token   	= count($kata_token);

	return $data_token;
}

function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

function tokenisasicorp($kata){
	// $line_break = preg_replace("/[\n\r]/"," ",$kata);
	// $text_token = preg_replace("/[^A-Za-z0-9-.?! ]/","", $line_break);
	$doc_split   	= explode(".",$kata);
	// $doc_split = multiexplode(array(".","?","!"),$line_break);
	
	return $doc_split;
}

function tokenisasi2($kata){
	$line_break = preg_replace("/[\n\r]/"," ",$kata);
	// $text_token = preg_replace("/[^A-Za-z0-9-.?! ]/","", $line_break);
	$doc_split   	= explode(".",$line_break);
	// $doc_split = multiexplode(array(".","?","!"),$line_break);
	
	return $doc_split;
}

function tokenisasi3($kata){
	$line_break = preg_replace("/[\n\r]/","",$kata);
	$text_token = preg_replace("/[^A-Za-z0-9- ]/","", $line_break);
	$doc_split  = explode(" ",$text_token);	
	return $doc_split;
}

function tokenisasi($kata){
	$line_break 	= preg_replace("/[\n\r]/"," ",$kata);
	$text_token 	= preg_replace("/[^A-Za-z0-9- ]/","", $line_break);
	$trim_token 	= trim($text_token);
	$kata_token   	= explode(" ",$text_token);
	$data_token   	= array_count_values($kata_token);

	return $data_token;
}

function tokenisasinocount($kata){
	$line_break = preg_replace("/[\n\r]/"," ",$kata);
	$text_token 	= preg_replace('/[^A-Za-z0-9- ]/','', $line_break);
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
	$line_break 	= preg_replace("/[\n\r]/","",$kata);
	// $trim_token 	= trim($text_token);
	$kata_token   	= explode(".",$line_break);
	$data_token   	= $kata_token;

	return $data_token;
}

function splitkalimat2($kata){
	$line_break = preg_replace("/[\n\r]/","",$kata);
	$text_token = preg_replace("/[^A-Za-z0-9-. ]/","", $line_break);
	// $doc_split   	= explode(".",$text_token);
	$doc_split = multiexplode(array("."),$text_token);
	
	return $doc_split;
}


function splitparagraf($kata){
	$trim_token 	= trim($kata);
	$kata_token   	= explode("\r\n", $trim_token);
	$data_token   	= $kata_token;

	return $data_token;
}
?>