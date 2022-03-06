<?php
define("SDFE_CSVSeparator", ",");           // Separator
define("SDFE_CSVLineTerminator", "\n");     // Line termination
define("SDFE_CSVFolder", "csv");            // The folder for csv files. Must exist!
define("SDFE_CSVFolderBackup", "csvbackup"); // The folder for backup files. Must exist!
define("SDFE_CSVFileExtension", "csv");     // The csv file extension


// Currently in Japanese (You can change all used texts here!)
define("TEXT_newCSV", "CSVを新規作成");			// button (Create New CSV)
define("TEXT_addLine", "行を追加");				// button (Add New Line)
define("TEXT_editCancel", "編集をキャンセル");	 // button (Cancel)
define("TEXT_editSave", "編集を保存");			// button (Save)
define("TEXT_downloadCSV", "CSVをダウンロード"); // button (Download CSV)
define("TEXT_deleteCSV", "CSVを削除");			// button (Delete CSV)

// alert: Please input the name of CSV
define("TEXT_inputCSVName", "CSVの名前を入力してください");
// alert: Please input the column names for this CSV (e.g. column1,column2,column3)
define("TEXT_inputCSVColumns", 'CSVの各項目名を<b>「,」</b>区切りで入力してください<br><br>（例：column1,column2,column3）');
// alert: Saved!
define("TEXT_savedCSV", '保存しました！');
// alert: Do you want to delete this CSV?
define("TEXT_deleteConfirmCSV", 'このCSVを削除しますか？');
// alert: Deleted!
define("TEXT_deletedCSV", '削除しました！');
// alert: Failed to delete...
define("TEXT_failDeleteCSV", '削除に失敗しました..');