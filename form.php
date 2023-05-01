<?php
header('Content-Type: text/html; charset=UTF-8'); //кодировка для браузера



if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    print('беда<br/>');
    exit();
}

// При наличии ошибок завершаем работу скрипта
if (empty($_POST['name'])) {
  print('Введите имя<br/>');
  exit();
}

if (empty($_POST['email'])) {
    print('Введите E-mail<br/>');
    exit();
  }
  
  if (empty($_POST['gender']))
  {
      print('Выберите пол<br>');
      exit();
  }

  if (empty($_POST['biography']))
  {
      print('Добавьте вашу биографию<br>');
      exit();
  }
  
  if (empty($_POST['limbs']))
  {
      print('Выберите число конечностей<br>');
      exit();
  }

  if (empty($_POST['agree']))
{
    print('Вы не ознакомились с контрактом<br>');
    exit();
}

$date1='2023-03-23';
$date2=$_POST['date'];
 if (strtotime($date1)<= strtotime($date2))
{
  print('Выбрана некорректная дата<br>');
  exit();
}

  // Сохранение в базу данных.
$user = 'u52945';
$password = '3219665'; 
$db = new PDO('mysql:host=localhost;dbname=u52945', $user, $password,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


  //подготовленный запрос
try {
    $stmt = $db->prepare("INSERT INTO application (name, email, biography, gender, limbs, birth) 
    VALUES (:name, :email, :biography, :gender, :limbs, :birth)");
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':biography', $_POST[':biography']);
    $stmt->bindParam(':gender', $_POST['gender']);
    $stmt->bindParam(':limbs', $_POST['limbs']);
    $stmt->bindParam(':birth', $_POST['birth']);
    $stmt->execute();
    $app_id = $db->lastInsertId(); //последний идентификатор

    foreach ($_POST['ability'] as $ability) // цикл foreach() в PHP для перебора значений поля множественного выбора и вставки выбранных способностей в БД
    {
        $stmt = $db->prepare("INSERT INTO application_ability2 (application_id, ability_id)
        VALUES (:application_id, (SELECT id FROM ability WHERE name=:ability_name))");
        $stmt->bindParam(':application_id', $app_id);
        $stmt->bindParam(':ability_name', $ability);
        $stmt->execute();
    }
    
  }
  catch(PDOException $e){
    print('ошибка при отправке данных: ' . $e->getMessage());
    exit();
  }
  print('Данные отправлены &#128516;');
