<?php
// include(__DIR__ . '/config.php');
// const DATA_PATH_ALL = '../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports/';
// const DATA_PATH_US = '../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports_us/';

// function parseFile(string $filePath, string $fileName)
// {
//     $dbh = new PDO('mysql:host=localhost;dbname=data_analysis', USER_NAME, USER_PASS);

//     $file = fopen($filePath, "r");
//     $data = fgetcsv($file);
//     $columnNamesAndTypes = [];
//     foreach ($data as $key => $columnName)
//     {
//         $data[$key] = '`' . str_replace('/', '_', $data[$key]) . '`';
//         $columnNamesAndTypes[] = $data[$key] . ' VARCHAR(255)';
//     } 
//     $condition = implode(',', $columnNamesAndTypes);
//     $createTableQuery = "
//         CREATE TABLE IF NOT EXISTS `$fileName`
//         (
//             id INT AUTO_INCREMENT PRIMARY KEY,
//             $condition
//         );
//     ";
//     $dbh->prepare($createTableQuery)->execute();

//     $columnNames = implode(',', $data);
//     $insertQueryPattern = "
//         INSERT INTO `$fileName` 
//         ($columnNames)
//         VALUES
//     ";
//     $rows = [];
//     while (!feof($file)) 
//     {
//         $data = fgetcsv($file);
//         if (empty($data))
//             continue;
//         foreach ($data as $key => $value)
//         {
//             $data[$key] = $dbh->quote($data[$key]);
//             if (empty($value) || !isset($value))
//             {
//                 $data[$key] = 'NULL';
//             }
//         }
//         $insertQuery = implode(',', $data);
//         if (!empty($insertQuery))
//             $rows[] = "($insertQuery)";
//     }
//     $insertQueryPattern .= implode(',', $rows);
//     if (!empty($rows))
//         $dbh->prepare($insertQueryPattern)->execute();
//     fclose($file);
// }

// function parseInputRec()
// {
//     $files = scandir(DATA_PATH_ALL);
//     $files2 = scandir(DATA_PATH_US);
//     foreach ($files2 as $fileName)
//     {
//         if (str_ends_with($fileName, '.csv'))
//         {
//             parseFile("../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports_us/" . $fileName, 'csse_covid_19_daily_reports_us' . $fileName);
//         }
//     }
//     foreach ($files as $fileName)
//     {
//         if (str_ends_with($fileName, '.csv'))
//         {
//             parseFile("../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports/" . $fileName, 'csse_covid_19_daily_reports' . $fileName);
//         }
//     }
// }
?>