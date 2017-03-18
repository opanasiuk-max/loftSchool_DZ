<?php
/**
 * Created by PhpStorm.
 * User: Максим
 * Date: 11.03.2017
 * Time: 20:41
 */
//Домашнее задание №4
//
//Задание #1
//Дан XML файл. Сохраните его под именем data.xml:
//
//Написать скрипт, который выведет всю информацию из этого файла в удобно читаемом виде.
//Представьте, что результат вашего скрипта будет распечатан и выдан курьеру для доставки, разберется ли курьер в этой информации?
//
//Задача #2
//
//    Создайте массив, в котором имеется как минимум 1 уровень вложенности. Преобразуйте его в JSON.  Сохраните как output.json
//Откройте файл output.json. Случайным образом решите изменять данные или нет. Сохраните как output2.json
//Откройте оба файла. Найдите разницу и выведите информацию об отличающихся элементах
//
//Задача #3
//Программно создайте массив, в котором перечислено не менее 50 случайных числел от 1 до 100
//Сохраните данные в файл csv
//Откройте файл csv и посчитайте сумму четных чисел
//
//Задача #4
//С помощью CURL запросить данные по адресу: https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json
//Вывести title и page_id


echo "<h3>Задание #1</h3>";
$xml=simplexml_load_file('data.xml');
$attrOrder=$xml->attributes($xml->PurchaseOrder);


$head="<table width='50%' align='center' border='1'><tr><td><h3>Номер ордера :</h3></td><td><h3><em>".$attrOrder['PurchaseOrderNumber']."</em></h3></td>
<td><h3>Дата ордера :</h3></td><td><h3><em>".$attrOrder['OrderDate']."</em></h3></td></tr></table>";
echo $head;

foreach ($xml->xpath('//Address') as $address){
    echo "<em><b>".$address->attributes()."</b></em><br>".
     "<b>Name : </b><em>".$address->Name."</em><br>".
     "<b>Address : </b><em>".$address->Street."</em><br>".
     "<b>City : </b><em>".$address->City."</em><br>".
     "<b>State : </b><em>".$address->State."</em><br>".
     "<b>Zip : </b><em>".$address->Zip."</em><br>".
     "<b>Country : </b><em>".$address->Country."</em><br>".
     "<br></td>";
}

echo "<b>Descryption : </b><em><b>".$xml->DeliveryNotes."</b></em><br>"."<br>";

foreach ($xml->xpath('//Item') as $item){
    echo "<em><b>".$item->attributes()."</b></em><br>".
        "<b>Product Name : </b><em>".$item->ProductName."</em><br>".
        "<b>Quantity : </b><em>".$item->Quantity."</em><br>".
        "<b>Price : </b><em>".$item->USPrice."</em><br>";
    if (isset($item->Comment)){
        echo "<b>Comment : </b><em>".$item->Comment."</em><br>";
    }
    if (isset($item->ShipDate)){
        echo "<b>ShipDate : </b><em>".$item->ShipDate."</em><br>";
    }
    echo "<br>";
}


echo "<h3>Задание #2</h3><br>";

// создание массива, преобразование в JSON и запись в output.json
$Array=[["декабрь","январь","февраль"],["март","апрель","май"],["июнь","июль","август"],["сентябрь","октябрь","ноябрь"]];
$json1=json_encode($Array);
file_put_contents('output.json', $json1);


// открытие файла output.json
$content=file_get_contents('output.json');
$json2=json_decode($content);


//случайное изменение массива и запись в output2.json
for ($z=0; $z<count($json2); $z++){
    for ($x=0; $x<count($json2[$z]); $x++){
        $q=rand(0,1);
        if ($q==1){
            $json3[$z][$x]=$json2[$z][$x]."New";
        } else {
            $json3[$z][$x]=$json2[$z][$x];
        }
    }
}
//print_r($json3);
$json4=json_encode($json3);
file_put_contents('output2.json', $json4);

//сравнение массивов в файлах

$content1=file_get_contents('output.json');

$jsonNew1=json_decode($content1);

$content2=file_get_contents('output2.json');

$jsonNew2=json_decode($content2);

for ($z=0; $z<count($jsonNew1); $z++){
    for ($x=0; $x<count($jsonNew1[$z]); $x++){
        if ($jsonNew1[$z][$x]!==$jsonNew2[$z][$x]){
            echo "output.json :<b>".$jsonNew1[$z][$x]."</b> ;  output2.json : <em>".$jsonNew2[$z][$x]."</em><br>";
        }
    }
}


echo "<h3>Задание #3</h3>";
    //создание массива и генерация случайных чисел
$array1=[];
for ($i=0; $i<50; $i++){
    $rnd=rand(1, 100);
    array_push($array1, $rnd);
    }
    //запись массива в файл
$file1=fopen('file.csv', 'w');
fputcsv($file1, $array1);
fclose($file1);

    //извлечение массива из файла и суммирование четных значений
$file2=fopen('file.csv', 'r');
$array2=fgetcsv($file2);
//print_r($array2) ;
$sum=0;
$sumCount=0;
for ($j=0; $j<count($array2); $j++){
    if ($array2[$j]%2==0){
        $sum+=$array2[$j];
        $sumCount++;
    }
}
echo "<br><b>Сумма : </b>".$sum."<br>";
echo "<br><b>Количество четных значений : </b>".$sumCount."<br><br>";


echo "<h3>Задание #4</h3>";

$url='https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json';
//Вывести title и page_id


$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
# Убираем вывод данных в браузер. Пусть функция их возвращает а не выводит
echo "<pre>";
$result = curl_exec($ch);
echo count($result[0][0])."<br>";

print_r($result);

//echo json_decode($result);


// загрузка страницы и выдача её браузеру
//curl_exec($ch);
file_put_contents('file.json', json_encode($result));
//file_put_contents('file.json', json_encode(htmlspecialchars($result)));

htmlspecialchars($result);