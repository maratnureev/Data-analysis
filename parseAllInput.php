<?php
include(__DIR__ . '/config.php');
const DATA_PATH_ALL = '../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports/';
const DATA_PATH_US = '../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports_us/';

function parseFile(string $fileName)
{
    $dbh = new PDO('mysql:host=localhost;dbname=data_analysis', USER_NAME, USER_PASS);
    $FIPS_FIELD = "fips";
    $ADMIN_FIELD = "admin2";
    $PROVINCE_STATE_FIELD = "province_state";
    $COUNTRY_REGION_FIELD = "country_region";
    $LAST_UPDATE_FIELD = "last_update";
    $LAT_FIELD = "lat";
    $LONG_FIELD = "long_";
    $CONFIRMED_FIELD = "confirmed";
    $DEATHS_FIELD = "deaths";
    $RECOVERED_FIELD = "recovered";
    $ACTIVE_FIELD = "active";
    $COMBINED_KEY_FIELD = "combined_key";
    $INCIDENT_RATE_FIELD = "incident_rate";
    $CASE_FATALITY_RATIO_FIELD = "case_fatality_ratio";
    $insertQueryPattern = "
        INSERT INTO `csse_covid_19_data` 
        ($FIPS_FIELD, $ADMIN_FIELD, $PROVINCE_STATE_FIELD, $COUNTRY_REGION_FIELD, 
        $LAST_UPDATE_FIELD, $LAT_FIELD, $LONG_FIELD, $CONFIRMED_FIELD, $DEATHS_FIELD, 
        $RECOVERED_FIELD, $ACTIVE_FIELD, $COMBINED_KEY_FIELD, $INCIDENT_RATE_FIELD, $CASE_FATALITY_RATIO_FIELD)
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
        if (count($data) < 14)
            continue; 
        foreach ($data as $key => $value)
        {
            $data[$key] = $dbh->quote($data[$key]);
            if (empty($value) || !isset($value))
            {
                $data[$key] = 'NULL';
            }
        }

        $rows[] = "($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13])";
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
            parseFile("../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports/" . $fileName, $tableName);
        }
    }

    foreach ($files as $fileName)
    {
        $tableName = 'csse_covid_19_data_us';
        if (str_ends_with($fileName, '.csv'))
        {
            parseFile("../COVID-19-master/csse_covid_19_data/csse_covid_19_daily_reports_us/" . $fileName. $tableName);
        }
    }
}
?>