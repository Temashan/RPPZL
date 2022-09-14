<?php
function getData()
{
    $curl = curl_init();

    curl_setopt_array($curl,
        [
            CURLOPT_URL => "https://yahoo-weather5.p.rapidapi.com/weather?location=sunnyvale&format=json&u=f",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER =>
                [
                "X-RapidAPI-Host: yahoo-weather5.p.rapidapi.com",
                "X-RapidAPI-Key: 6c451d246bmsh3032dfbeb659d30p1b05b3jsneab8ec20b58b"
                ],
        ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err)
    {
        echo "cURL Error #:" . $err;
        return null;
    }
    else
    {
        return $response;
    }
}

function getResult($properties)
{
    global $result;
    foreach ($properties as $key=>$property)
        {
            if (is_object($property))
            {
                getResult(get_mangled_object_vars($property));
            }
            elseif (is_array($property))
            {
                foreach ($property as $value)
                {
                    getResult(get_mangled_object_vars($value));
                }
            }
            else
            {
                $result[$key]=$property;
            }
        }
}

$response=getData();
if ($response==null)
{
    echo "Ошибка получения данных";
}
else
{
    $obj=json_decode($response);
    $properties=get_mangled_object_vars($obj);
    $result=array();
    getResult($properties);
    $fp=fopen("result.txt","w");
    foreach ($result as $key=>$value)
    {
        fwrite($fp,$key . " " . $value . "\n");
    }
    fclose($fp);
    echo "Проверьте текстовый файл";
}


