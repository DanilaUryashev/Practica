<?php
include 'ConnServer.php';
session_start();
$sum_rows=0;
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$sqldis1= "SELECT concat(Surname,' ',Name)  As FI
    FROM Student WHERE Group_ID=1";
$qurdis1 = sqlsrv_query( $conn, $sqldis1 , $params, $options );
$dis1 = sqlsrv_fetch_array($qurdis1,SQLSRV_FETCH_ASSOC);

  do{
  $sum_rows=$sum_rows+1;
  }
  while ($dis1 = sqlsrv_fetch_array($qurdis1,SQLSRV_FETCH_ASSOC));
  $date= 30;
  $total_cell=$sum_rows*$date;


  $keyrating=0;
$b=0;
   do {
     $sqlstud= "SELECT ID FROM Student WHERE Group_ID=1";
     $qurstud = sqlsrv_query( $conn, $sqlstud , $params, $options );
     $stud= sqlsrv_fetch_array($qurstud,SQLSRV_FETCH_ASSOC);
     $c=0;
     $d=$b+1;
               do {
                 $keyrating=$keyrating+1;
                 $sqlrat= "SELECT Rating FROM Ratings WHERE ID_Discipline=1 AND DATEPART(day,Date)=$d AND DATEPART(month,Date)=8 AND ID_Student=$stud[ID]";
                 $querrat = sqlsrv_query( $conn, $sqlrat , $params, $options );
                 $rat = sqlsrv_fetch_array($querrat,SQLSRV_FETCH_ASSOC);
                 $KeyRating =filter_var(trim($_POST["$keyrating"]),FILTER_SANITIZE_STRING);
                 $DATE="2022-08-$d";
                 if(empty($KeyRating) or isset($rat["Rating"])){

                 }
                 else {
                   $sqlrating= "INSERT INTO Ratings(Rating,Date,ID_Student,ID_Discipline) VALUES ($KeyRating,'$DATE',$stud[ID],1)";
                   $querrating = sqlsrv_query( $conn, $sqlrating , $params, $options );
                 }
                 $c=$c+1;
                 $stud= sqlsrv_fetch_array($qurstud,SQLSRV_FETCH_ASSOC);
               } while ($c < $sum_rows);
     printf ("</div>");
     $b=$b+1;
   } while ($b<$date );
header("Location: Teacher.php"); exit();
?>
<pre>
<?php
printf($DATE)
?></pre>
