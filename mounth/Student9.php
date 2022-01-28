<?php
include '../ConnServer.php';
session_start();
$id=$_SESSION['user']["id"];
// ПРИМЕР SQL ЗАПРОСА
$sql= "SELECT * FROM Student WHERE ID='$id'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $params, $options );
$stud = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
// ПРИМЕР SQL ЗАПРОСА
$_SESSION['useraut']=[
  "surname"=>$stud['Surname'],
  "name"=>$stud['Name'],
  "group"=>$stud['Group_ID']
];
$Group=$_SESSION['useraut']['group'];
$sqldis= "SELECT Discipline.Name
FROM Group_Discipline INNER JOIN Discipline ON (Group_Discipline.ID_Discipline=Discipline.ID_Discipline)
WHERE Group_Discipline.ID_Group=$Group";
$qurdis = sqlsrv_query( $conn, $sqldis , $params, $options );
$dis = sqlsrv_fetch_array($qurdis,SQLSRV_FETCH_ASSOC);
$month=5;
$sum_rows=0;
$sum_column=0;
?>
<!DOCTYPE html>
<html lang="ru">
 <head>
  <meta charset="UTF-8">
  <title>Студент</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
 <body class="body-prof">
   <header class="header_sys">
    <img class="logo-sys"src="../Images/Logo.png">
    <p class="text-logo">Успеваемость студентов</p>
    <p class="Role">Студент</p>
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
            <!-- ЦИКЛ ДЛЯ ВЫВОДА ПРЕДМЕТОВ В SELECT -->
            <?php
            do//открываем цикл
            {
            //присваеваем переменной f(фамилия) - 1-ю записи из массива
            printf ("<option>%s</option>",$dis["Name"]);//тут скрипт находит %s и вставляет в него переменную f, потом вторую %s и т.д.
            }
            while($dis = sqlsrv_fetch_array($qurdis,SQLSRV_FETCH_ASSOC));//здесь мы переходим на слудующую запись в базе
            ?>
         </select>
          </div>
        </div>
        <button type="submit" class="button-filter">Найти</button>
     </form>
      <div class="table">
        <div class="data-block">
          <div class="select_date">
            <a href="Student8.php" class="btn"><</a>Май<a href="Student10.php"class="btn">></a>
          </div>
          <!-- ЦИКЛ ДЛЯ ВЫВОДА ДАТ В ТАБЛИЦУ -->
            <?php
            $sql_date= "SELECT DATEPART(day,Date) as Day FROM Ratings WHERE DATEPART(month,Date)=$month ORDER BY Date ASC";
            $quer_date = sqlsrv_query( $conn, $sql_date , $params, $options );
            $date = sqlsrv_fetch_array($quer_date,SQLSRV_FETCH_ASSOC);
            $old_date1=0;
              do{
                if($old_date1==$date){

            }
            else{
              printf ("<div class='data-cell'>%s</div>",$date["Day"]);
            }
              $sum_column=$sum_column+1;
              $old_date1=$date;
              }
              while ($date = sqlsrv_fetch_array($quer_date,SQLSRV_FETCH_ASSOC));
             ?>
             <div class='data-cell'><p class="avg-text">Средняя</p></div>
        </div>
        <div class="rating">
        <div class="discip-block">
          <!-- ЦИКЛ ДЛЯ ВЫВОДА ПРЕДМЕТОВ В ТАБЛИЦУ -->
          <?php
          $sqldis1= "SELECT Discipline.Name
           FROM Group_Discipline INNER JOIN Discipline ON (Group_Discipline.ID_Discipline=Discipline.ID_Discipline)
          WHERE Group_Discipline.ID_Group=$Group";
          $qurdis1 = sqlsrv_query( $conn, $sqldis1 , $params, $options );
          $dis1 = sqlsrv_fetch_array($qurdis1,SQLSRV_FETCH_ASSOC);
            do{
            printf ("<div class='discip-cell'>%s</div>",$dis1["Name"]);
            $sum_rows=$sum_rows+1;
            }
            while ($dis1 = sqlsrv_fetch_array($qurdis1,SQLSRV_FETCH_ASSOC));
           ?>
         </div>
         <div class="rating-block">
           <!-- ЦИКЛ ДЛЯ ВЫВОДА ОЦЕНОК В ТАБЛИЦУ -->
         <?php
            $b=0;
            $old_date=0;
            $sql_date1= "SELECT DATEPART(day,Date) as Day FROM Ratings WHERE DATEPART(month,Date)=$month ORDER BY Date ASC";
            $quer_date1 = sqlsrv_query( $conn, $sql_date1 , $params, $options );
            // СОЗДАНИЕ СТОЛБЦОВ С ОЦЕНКАМИ
            do{
              $date1 = sqlsrv_fetch_array($quer_date1,SQLSRV_FETCH_ASSOC);
              printf ("<div class='column'>");
              $b=$b+1;
              $a=0;
              if($old_date==$date1){

              }
              else{
                $sqldis2= "SELECT Discipline.ID_Discipline
                 FROM Group_Discipline INNER JOIN Discipline ON (Group_Discipline.ID_Discipline=Discipline.ID_Discipline)
                WHERE Group_Discipline.ID_Group=$Group";
                $qurdis2 = sqlsrv_query( $conn, $sqldis2 , $params, $options );
                $dis2 = sqlsrv_fetch_array($qurdis2,SQLSRV_FETCH_ASSOC);
                // СОЗДАНИЕ КЛЕТОК С ОЦЕНКАМИ
                do{
                  // НАХОДИМ ПРЕДМЕТ В ДАННОЙ СТРОКЕ
                  // НАХОДИМ ОЦЕНКУ ПО ПРЕДМЕТУ В ТАКУЮ ДАТУ
                  $sqlrat= "SELECT Rating FROM Ratings WHERE ID_Student=$id AND ID_Discipline=$dis2[ID_Discipline] AND DATEPART(day,Date)=$date1[Day] AND DATEPART(month,Date)=$month";
                  $querrat = sqlsrv_query( $conn, $sqlrat , $params, $options );
                  $rat = sqlsrv_fetch_array($querrat,SQLSRV_FETCH_ASSOC);
                  if(isset($rat["Rating"])){
                    printf ("<div class='cell-rating'>%s</div>",$rat["Rating"]);
                  }
                  else {
                    printf ("<div class='cell-rating'></div>");
                  }
                  $a=$a+1;
                  $dis2 = sqlsrv_fetch_array($qurdis2,SQLSRV_FETCH_ASSOC);
                }
                while ($a < $sum_rows);
              }
              printf ("</div>");
              $old_date=$date1;
            }
            while ($b < $sum_column );
            ?>
            <?php
            printf ("<div class='column'>");
            $c=0;
            $sqldis3= "SELECT Discipline.ID_Discipline
               FROM Group_Discipline INNER JOIN Discipline ON (Group_Discipline.ID_Discipline=Discipline.ID_Discipline)
              WHERE Group_Discipline.ID_Group=$Group";
            $qurdis3 = sqlsrv_query( $conn, $sqldis3 , $params, $options );
            $dis3 = sqlsrv_fetch_array($qurdis3,SQLSRV_FETCH_ASSOC);
            do{

              $sqlavg= "SELECT ROUND(AVG(CONVERT(float,Rating)),2) AS avg FROM Ratings WHERE Rating <>'Н' AND Rating <>'н' AND ID_Student=$id AND ID_Discipline=$dis3[ID_Discipline] AND DATEPART(month,Date)=$month";
              $queravg = sqlsrv_query( $conn, $sqlavg , $params, $options );
              $avg = sqlsrv_fetch_array($queravg,SQLSRV_FETCH_ASSOC);
              printf ("<div class='cell-rating'>%s</div>",$avg["avg"]);
              $c=$c+1;

            }
            while ($c < $sum_rows and $dis3 = sqlsrv_fetch_array($qurdis3,SQLSRV_FETCH_ASSOC));
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
