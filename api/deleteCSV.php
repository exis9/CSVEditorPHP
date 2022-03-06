<?php
if ( !isset($_POST['fname']) )
	die('-1');

$fname = trim( escapeFileName( $_POST['fname'] ) );

//$path = $_SERVER['DOCUMENT_ROOT'] . '/CSVEditor/csv/'.$fname;

$path = '../csv/'.$fname;
if ( file_exists($path) )
{
	@chmod($path, 0777);
	unlink($path);
	die('0');
}
die('-2');

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