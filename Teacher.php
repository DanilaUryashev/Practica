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
$month=8;
$sum_rows=0;
$sum_column=0;
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
            <option class="input_filter" value="1">Математика</option>
         </select>
         </div>
          <div class="block-filter">
          <p class="searc-dis">Группа:</p>
          <select class="input_filter input_discip" name="Disciplin_search">
             <option class="input_filter" value="1">ПР-315</option>
          </select>
          </div>
        </div>
        <button type="submit" class="button-filter">Найти</button>
     </form>
     <form action="InsertCell.php" class="table" method="post">
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
         <!-- ЦИКЛ ДЛЯ ВЫВОДА CТУДЕНТОВ В ТАБЛИЦУ -->
         <?php

           $sum_rows=0;
           $sqldis1= "SELECT concat(Surname,' ',Name)  As FI
               FROM Student WHERE Group_ID=1";
           $qurdis1 = sqlsrv_query( $conn, $sqldis1 , $params, $options );
           $dis1 = sqlsrv_fetch_array($qurdis1,SQLSRV_FETCH_ASSOC);
             do{
             printf ("<div class='discip-cell'>%s</div>",$dis1["FI"]);
             $sum_rows=$sum_rows+1;
             }
             while ($dis1 = sqlsrv_fetch_array($qurdis1,SQLSRV_FETCH_ASSOC));
          ?>
        </div>
        <div class="rating-block">
          <!-- ЦИКЛ ДЛЯ ВЫВОДА ОЦЕНОК В ТАБЛИЦУ -->
        <?php

           $b=0;
           $keyrating=0;
           do {
             printf ("<div class='teacher_column'>");
             $sqlstud= "SELECT ID FROM Student WHERE Group_ID=1";
             $qurstud = sqlsrv_query( $conn, $sqlstud , $params, $options );
             $stud= sqlsrv_fetch_array($qurstud,SQLSRV_FETCH_ASSOC);
             $c=0;
             $d=$b+1;
                       do {
                         $keyrating=$keyrating+1;
                         $sqlrat= "SELECT Rating FROM Ratings WHERE ID_Discipline=1 AND DATEPART(day,Date)=$d AND DATEPART(month,Date)=$month AND ID_Student=$stud[ID]";
                         $querrat = sqlsrv_query( $conn, $sqlrat , $params, $options );
                         $rat = sqlsrv_fetch_array($querrat,SQLSRV_FETCH_ASSOC);
                         if(isset($rat["Rating"])){
                           printf ("<input type='text' class='teacher_cell-rating' value='%s' name='%s'></input>",$rat["Rating"],$keyrating);
                         }
                         else {
                           printf ("<input type='text' class='teacher_cell-rating' name='%s'></input>",$keyrating);
                         }
                         $c=$c+1;
                         $stud= sqlsrv_fetch_array($qurstud,SQLSRV_FETCH_ASSOC);
                       } while ($c < $sum_rows);
             printf ("</div>");
             $b=$b+1;
           } while ($b < 30);
             // СРЕДНЯЯ ОЦЕНКА
           ?>
           <?php
           printf ("<div class='column'>");
           $c=0;
           $sqlstud= "SELECT ID FROM Student WHERE Group_ID=1";
           $qurstud = sqlsrv_query( $conn, $sqlstud , $params, $options );
           $stud= sqlsrv_fetch_array($qurstud,SQLSRV_FETCH_ASSOC);
           do{

             $sqlavg= "SELECT ROUND(AVG(CONVERT(float,Rating)),2) AS avg FROM Ratings WHERE Rating <>'Н' AND Rating <>'н' AND ID_Student=$stud[ID] AND ID_Discipline=1 AND DATEPART(month,Date)=$month";
             $queravg = sqlsrv_query( $conn, $sqlavg , $params, $options );
             $avg = sqlsrv_fetch_array($queravg,SQLSRV_FETCH_ASSOC);
             printf ("<div class='cell-rating'>%s</div>",$avg["avg"]);
             $c=$c+1;

           }
           while ($c < $sum_rows and $stud= sqlsrv_fetch_array($qurstud,SQLSRV_FETCH_ASSOC));
           printf ("</div>");
            ?>

        </div>

       </div>
         <button type="submit" class="button-save">Сохранить</button>

     </form action="InsertCell.php">
     <footer class="teacher-footer">
       <p class="text-footer text_1">© 2022-2025 Новосибирская область</p>
     </footer>
   </main>




 </body>
</html>
