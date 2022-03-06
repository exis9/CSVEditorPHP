<?php
if ( !isset($_POST['fname']) || !isset($_POST['head']) )
	die('-1');

$fname = escapeFileName( trim( $_POST['fname'] ) );
$head = escapeCell( trim( $_POST['head'] ) );

$path = '../csv/'.$fname;
if ( !file_exists($path) )
{
	file_put_contents($path, $head);
	@chmod($path, 0777);
	die('0');
}
die('-2');


function escapeCell($s){
	$s = str_replace("\n", '', $s);
	$s = str_replace('"', '', $s);
	$s = str_replace(',', '","', $s);
	return '"'.$s.'"' . "\n";
}

function escapeFileName($s){
	$s = str_replace('/', '', $s);
	$s = str_replace('..', '', $s);
	$s = str_replace('\\', '', $s);
	$s = str_replace('&', '', $s);
	$s = str_replace(':', '', $s);
	$s = str_replace('-', '', $s);
	$s = str_replace('=', '', $s);
	$s = str_replace('+', '', $s);
	$s = str_replace('<', '', $s);
	$s = str_replace('>', '', $s);
	$s = str_replace('$', '', $s);
	$s = str_replace('^', '', $s);
	return $s;
}