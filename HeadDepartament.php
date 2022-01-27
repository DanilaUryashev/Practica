<?php
include 'ConnServer.php';
session_start();
$id=$_POST($_SESSION['user']["id"]);
$sql= "SELECT * FROM Departament_Head WHERE ID='$id'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $params, $options );
$Dep = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
$_SESSION['useraut']=[
  "surname"=>$Dep['Surname'],
  "name"=>$Dep['Name']
];
?>
<!DOCTYPE html>
<html lang="ru">
 <head>
  <meta charset="UTF-8">
  <title>Заведующий отделением</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
 <body class="body-prof">
   <header class="header_sys">
    <img class="logo-sys"src="Images/Logo.png">
    <p class="text-logo">Успеваемость студентов</p>
    <p class="Role">Заведующий отделением</p>
    <p class="Username"><?= $_SESSION['useraut']['surname']?> <?= $_SESSION['useraut']['name']?></p>
    <div class="line"></div>
    <a href="../" class="exit">Выйти</a>
  </header>
   <main>
     <div class="navigation">
        <div class="button-navigation ">
          <p class="buttontext">Журнал</p>
        </div>
     </div>
     <form class="searc-form">
       <div class="searc_filter">
         <div class="block-filter">
         <p class="searc-dis">Предмет:</p>
         <select class="input_filter input_discip" name="Disciplin_search">
            <option class="input_filter" value="1">Все</option>
         </select>
         </div>
          <div class="block-filter">
          <p class="searc-dis">Группа:</p>
          <select class="input_filter input_discip" name="Disciplin_search">
             <option class="input_filter" value="1">Все</option>
          </select>
          </div>
          <div class="block-filter">
          <p class="searc-dis">Студент:</p>
          <select class="input_filter input_discip" name="Disciplin_search">
             <option class="input_filter" value="1">Все</option>
          </select>
          </div>
        </div>
        <button type="submit" class="button-filter">Найти</button>
     </form>
   </main>
   <footer class="footer">
     <p class="text-footer text_1">© 2022-2025 Новосибирская область</p>
   </footer>



 </body>
</html>
