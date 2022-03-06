<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CSV Editor</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/csveditor.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./">CSV Editor</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
              <li class="active"><a href="./">CSVを登録</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<?php

    require_once 'config.php';

// Get array of CSV files
$csvpath = SDFE_CSVFolder . "/";
$files = scandir($csvpath); // this is all files in dir

// clean up file list (to exclude)should only include csv files
$csvfiles = array();
foreach ($files as $basename) {
    if(substr($basename, -3)==SDFE_CSVFileExtension) {
        array_push($csvfiles, $basename);
    }
}
// Set first csv file as default csv file to display in edit mode
if(sizeof($csvfiles)>0) {
    $csv = $csvfiles[0];
}
// Override default csv file if a csv file is provided
if(isset($_GET["file"])) {
    $csv = $_GET["file"];
}

// Open CSV file
$filename = SDFE_CSVFolder . "/" . $csv;
$fp = fopen($filename, "r");
$content = trim( fread($fp, filesize($filename)) );
$lines = explode("\n", $content);
fclose($fp);

?>      
      
      
      <div class="container-fluid">
        <div class="row">
<!-- List of CSV files -->
            <div class="col-lg-3 col-md-4 col-sm-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">CSV filer</h3>
                    </div>                
                    <div class="list-group">
<?php 
foreach ($csvfiles as $basename) {
    echo makeCSVFileLink($basename, $csv);
}
?>
                    </div>                
                </div>                
            </div>
<!-- Edit CSV content -->
            <div class="col-lg-9 col-md-8 col-sm-7">
           
<?php
function my_exp_cells( $line ){
	$rows = explode(SDFE_CSVSeparator, $line);
	$cnt = count( $rows );
	for ( $i=0; $i < $cnt; $i++ )
	{
		$r = $rows[$i];
		/*$str_cnt = mb_strlen( $r );
		if ( mb_substr( $r, 0, 1 ) == '"' )
		{
			
		}*/
		$r = str_replace('"', '', $r);
		$rows[$i] = $r;
	}
	return $rows;
}

if(!isset($csv)) {
    
}
else {
    // CSV file is not empty, let's show the content
	$row = my_exp_cells( $lines[0] );
    $columns = sizeof($row);
?>
                <form class="form-inline">
                <h1><?php echo $csv; ?></h1>
                <div id="idTableCont" class="panel panel-default">
                    <table class="table table-hover" id="csvtable">
                        <thead>
                            <tr>
<?php
    // Show header
    for ($columnCnt=0; $columnCnt<$columns; $columnCnt++) {
        echo "<th>" . $row[$columnCnt] . "</th>";
    }
    echo "<th>&nbsp;</th>";
?>
                            </tr>
                        </thead>                        
                        <tbody>
<?php
    // Show content
    for ($lineCnt=1; $lineCnt<sizeof($lines); $lineCnt++)
	{
        $row = my_exp_cells( $lines[$lineCnt] );
        echo makeTableRow($lineCnt, $row, $columns);
    }
?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="#" id="addrow" class="btn btn-default"><i class="fa fa-plus"></i> 行を追加</a>
                </div>
                <hr>
                <div id="idBtnCont">
                    <a href="#" id="cancel" class="btn btn-default"><i class="fa fa-undo"></i> 編集をキャンセル</a>
                    <a href="#" id="save" class="btn btn-primary"><i class="fa fa-save"></i> 編集を保存</a>
                    <a href="#" id="saveFile" class="btn btn-primary"><i class="fa fa-save"></i> CSVをダウンロード</a>
                </div>
                </form>
                <div style="margin-top: 20px;" id="message">
                </div>

<?php
}
?>
            </div>
        </div>
    </div><!-- /.container -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/index.js"></script>
    <script>
		var g_csvfile = "<?php echo $csv;?>";
		var g_columns = "<?php echo $columns;?>";
	</script>
  </body>
</html>
<?php
function makeTableRow($lineCnt, $row, $columns) {
    $h = "<tr rel=\"row\" id=\"row-" . $lineCnt . "\">";
    for ($columnCnt=0; $columnCnt<$columns; $columnCnt++)
	{
		$v = $row[$columnCnt];
		$sz = mb_strlen($v) + 1;
$h.=<<<EOT
<td>
	<label class="input-sizer">
		<input disabled class="cEditInput" type="text" rel="input-{$lineCnt}" onInput="this.parentNode.dataset.value = this.value" size="{$sz}" value="{$v}" title="{$v}">
	</label>
</td>
EOT;
    }
    $h .= "<td>";
    $h .= " <a href=\"#\" rel=\"editrow\" id=\"editrow-" . $lineCnt . "\" title=\"Edit row\" class=\"btn btn-default btn-sm\"><i class=\"fa fa-lock\"></i></a>";
    $h .= " <a href=\"#\" rel=\"deleterow\" id=\"deleterow-" . $lineCnt . "\" title=\"Delete row\" class=\"btn btn-default btn-sm\"><i class=\"fa fa-trash\"></i></a>";
    $h .= "</td>";
    $h .= "</tr>";
    
    return $h;
}
function makeCSVFileLink($basename, $activebasename) {
    // Include CSV files only (defined by extension)
    if(substr($basename, -3)==SDFE_CSVFileExtension) {
        $h = "<a href=\"?file=" . $basename . "\" ";
        $h .= "class=\"list-group-item" . ($basename==$activebasename ? " active" : "") . "\">";
        $h .= $basename . "</a>";
    }
    return $h;
}

?>
