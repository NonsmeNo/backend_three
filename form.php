<?php
header('Content-Type: text/html; charset=UTF-8'); //кодировка для браузера



if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    exit();
}

// При наличии ошибок завершаем работу скрипта
if (empty($_POST['name'])) {
  print('Введите имя &#128532;<br/>');
  exit();
}

if (empty($_POST['email'])) {
    print('Введите E-mail &#128554;<br/>');
    exit();
  }
  
  if (empty($_POST['biography']))
  {
      print('Добавьте вашу биографию &#128559;<br>');
      exit();
  }

  if (empty($_POST['gender']))
  {
      print('Выберите пол &#128577;<br>');
      exit();
  }

  
  if (empty($_POST['limbs']))
  {
      print('Выберите число конечностей &#128550;<br>');
      exit();
  }

  if (empty($_POST['agree']))
  {
    print('Вы не ознакомились с контрактом 	&#128576;<br>');
    exit();
  }

  if (empty($_POST['ability']))
  {
      print('Выберите сверхспособности &#128550;<br>');
      exit();
  }

  $date1="2004-01-01";
  $date=$_POST['birth'];

  if ($date>=$date1)
  {
    print('Выбрана некорректная дата &#128559;<br>');
    exit();
  }

  // Сохранение в базу данных.
$db = new PDO('mysql:host=localhost;dbname=u52945', 'u52945', '3219665',
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


  //подготовленный запрос
try {
    $stmt = $db->prepare("INSERT INTO application (name, email, biography, gender, limbs, birth) 
    VALUES (:name, :email, :biography, :gender, :limbs, :birth)");
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':biography', $_POST['biography']);
    $stmt->bindParam(':gender', $_POST['gender']);
    $stmt->bindParam(':limbs', $_POST['limbs']);
    $stmt->bindParam(':birth', $_POST['birth']);
    $stmt->execute();
    $application_id = $db->lastInsertId();

    foreach ($_POST['ability'] as $ability)
    {
        $stmt = $db->prepare("INSERT INTO application_ability (application_id, ability_id)
        VALUES (:application_id, (SELECT id FROM ability WHERE name=:ability_name))");
        $stmt->bindParam(':application_id', $application_id);
        $stmt->bindParam(':ability_name', $ability);
        $stmt->execute();
    }
    
  }
  catch(PDOException $e){
    print('ошибка при отправке данных: ' . $e->getMessage());
    exit();
  }
  print('Данные отправлены &#128516;');
