<?php
include 'ConnServer.php';
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
$month=1;
$sum_rows=0;
$sum_column=0;
?>
<!DOCTYPE html>
<html lang="ru">
 <head>
  <meta charset="UTF-8">
  <title>Студент</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
 <body class="body-prof">
   <header class="header_sys">
    <img class="logo-sys"src="Images/Logo.png">
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
          <!-- ЦИКЛ ДЛЯ ВЫВОДА ДАТ В ТАБЛИЦУ -->
            <?php
            $sql_date= "SELECT DATEPART(day,Date) as Day FROM Ratings WHERE DATEPART(month,Date)=$month";
            $quer_date = sqlsrv_query( $conn, $sql_date , $params, $options );
            $date = sqlsrv_fetch_array($quer_date,SQLSRV_FETCH_ASSOC);
              do{
              printf ("<div class='data-cell'>%s</div>",$date["Day"]);
              $sum_column=$sum_column+1;
              }
              while ($date = sqlsrv_fetch_array($quer_date,SQLSRV_FETCH_ASSOC));
             ?>
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
            // СОЗДАНИЕ СТОЛБЦОВ С ОЦЕНКАМИ
            do{
              printf ("<div class='column'>");
              $b=$b+1;
              $a=0;
              $sqldis2= "SELECT Discipline.ID_Discipline
               FROM Group_Discipline INNER JOIN Discipline ON (Group_Discipline.ID_Discipline=Discipline.ID_Discipline)
              WHERE Group_Discipline.ID_Group=$Group";
              $qurdis2 = sqlsrv_query( $conn, $sqldis2 , $params, $options );
              $dis2 = sqlsrv_fetch_array($qurdis2,SQLSRV_FETCH_ASSOC);
              // СОЗДАНИЕ КЛЕТОК С ОЦЕНКАМИ
              do{
                // НАХОДИМ ПРЕДМЕТ В ДАННОЙ СТРОКЕ
                // НАХОДИМ ОЦЕНКУ ПО ПРЕДМЕТУ В ТАКУЮ ДАТУ
                $sqlrat= "SELECT Rating FROM Ratings WHERE ID_Student=$id AND ID_Discipline=$dis2[ID_Discipline] AND Date='2022-01-27'";
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
              printf ("</div>");
            }
            while ($b < $sum_column);
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
