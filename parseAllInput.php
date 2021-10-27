<?php
include(__DIR__ . '/config.php');
const DATA_PATH_ALL = '../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports/';
const DATA_PATH_US = '../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports_us/';

const ALL_COLUMN_LIST = [
    "fips",
    "admin2",
    "province_state",
    "country_region",
    "last_update",
    "lat",
    "long_",
    "confirmed",
    "deaths",
    "recovered",
    "active",
    "combined_key",
    "incident_rate",
    "case_fatality_ratio",
];

const US_COLUMN_LIST = [
    "province_state",
    "country_region",
    "last_update",
    "lat",
    "long_",
    "confirmed",
    "deaths",
    "recovered",
    "active",
    "fips",
    "incident_rate",
    "Total_Test_Results",
    "People_Hospitalized",
    "case_fatality_ratio",
    "UID",
    "ISO3",
    "Testing_Rate",
    "Hospitalization_Rate",
];

function parseFile(string $fileName, string $tableName, array $columns)
{
    $dbh = new PDO('mysql:host=localhost;dbname=data_analysis', USER_NAME, USER_PASS);
    $columnString = implode(",", $columns);
    $insertQueryPattern = "
        INSERT INTO $tableName 
        ($columnString)
        VALUES
    ";
    $file = fopen($fileName, "r");
    $data = fgetcsv($file);
    $rows = [];
    
    while (!feof($file)) 
    {
        $data = fgetcsv($file);
        if (empty($data))
            continue;
        if (count($data) != count($columns))
            continue; 
        foreach ($data as $key => $value)
        {
            $data[$key] = $dbh->quote($data[$key]);
            if (empty($value) || !isset($value))
            {
                $data[$key] = 'NULL';
            }
        }
        $dataStr = implode(',', $data);
        $rows[] = "($dataStr)";
        echo count($data) . PHP_EOL;
    }
    $insertQueryPattern .= implode(',', $rows);
    if (!empty($rows))
        $dbh->prepare($insertQueryPattern)->execute();
    fclose($file);
}

function parseInputRec()
{
    $files = scandir(DATA_PATH_ALL);
    foreach ($files as $fileName)
    {
        $tableName = 'csse_covid_19_data';
        if (str_ends_with($fileName, '.csv'))
        {
            parseFile("../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports/" . $fileName, $tableName, ALL_COLUMN_LIST);
        }
    }
    $files = scandir(DATA_PATH_US);
    foreach ($files as $fileName)
    {
        $tableName = 'csse_covid_19_data_us';
        if (str_ends_with($fileName, '.csv'))
        {
            parseFile("../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports_us/" . $fileName, $tableName,US_COLUMN_LIST);
        }
    }
}
?>