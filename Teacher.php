<?php
include 'ConnServer.php';
session_start();
$idt=$_SESSION['user']["id"];
$sql= "SELECT * FROM Teacher WHERE ID='$idt'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $params, $options );
$teach = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
$_SESSION['useraut']=[
  "surname"=>$teach['Surname'],
  "name"=>$teach['Name']
];
?>
<!DOCTYPE html>
<html lang="ru">
 <head>
  <meta charset="UTF-8">
  <title>Преподователь</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
 <body class="body-prof">
   <header class="header_sys">
    <img class="logo-sys"src="Images/Logo.png">
    <p class="text-logo">Успеваемость студентов</p>
    <p class="Role">Преподователь</p>
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
        </div>
        <button type="submit" class="button-filter">Найти</button>
     </form>
     <div class="table">
       <div class="data-block">
         <div class="select_date">
           <a href="" class="btn"></a>  Сентябрь  <a href="mounth/Student1.php" class="btn">></a>
         </div>
         <!-- ЦИКЛ ДЛЯ ВЫВОДА ДАТ В ТАБЛИЦУ -->
           <?php
           $a=0;
           do {
             $a=$a+1;
             printf ("<div class='teacher_data-cell'>%s</div>",$a);
           } while ($a < 30);
            ?>
            <div class='data-cell'><p class="avg-text">Средняя</p></div>
       </div>
       <div class="rating">
       <div class="discip-block">
         <!-- ЦИКЛ ДЛЯ ВЫВОДА ПРЕДМЕТОВ В ТАБЛИЦУ -->
         <?php
           printf ("<div class='discip-cell'></div>");
           $sum_rows=1
          ?>
        </div>
        <div class="rating-block">
          <!-- ЦИКЛ ДЛЯ ВЫВОДА ОЦЕНОК В ТАБЛИЦУ -->
        <?php
           $b=0;
           // СОЗДАНИЕ СТОЛБЦОВ С ОЦЕНКАМИ
           do{
             printf ("<div class='column'>");
                   printf ("<div class='teacher_cell-rating'></div>");
                   $b=$b+1;
                 }
               while ($b < $sum_rows);
             printf ("</div>");
             // СРЕДНЯЯ ОЦЕНКА
           ?>
           <?php
           printf ("<div class='column'>");
           $c=0;
           do{
             printf ("<div class='teacher_cell-rating'></div>");
             $c=$c+1;
           }
           while ($c < $sum_rows );
           printf ("</div>");
            ?>
        </div>
       </div>
     </div>
   </main>
   <footer class="footer">
     <p class="text-footer text_1">© 2022-2025 Новосибирская область</p>
   </footer>



 </body>
</html>
