
<?php
ini_set('session.save_path', 'tmp');
// Функция My session start с управлением на основе временных меток
function my_session_start() {
    session_start();
    // Не разрешать использование слишком старых идентификаторов сессии
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
        session_start();
    }
}
// Функция My session regenerate id
function my_session_regenerate_id() {
    // Вызов session_create_id() пока сессия активна, чтобы
    // удостовериться в отсутствии коллизий.
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    // ВНИМАНИЕ: Никогда не используйте конфиденциальные строки в качестве префикса!
    $newid = session_create_id('myprefix-');
    // Установка временной метки удаления. Данные активной сессии не должны удаляться сразу же.
    $_SESSION['deleted_time'] = time();
    // Завершение сессии
    session_commit();
    // Убеждаемся в возможности установки пользовательского идентификатора сессии
    // ЗАМЕЧАНИЕ: Вы должны включать опцию use_strict_mode для обычных операций.
    ini_set('session.use_strict_mode', 0);
    // Установка нового пользовательского идентификатора сессии
    session_id($newid);
    // Старт сессии с пользовательским идентификатором
    session_start();
}
// Убеждаемся, что опция use_strict_mode включена.
// Опция use_strict_mode обязательна по соображениям безопасности.
ini_set('session.use_strict_mode', 1);
my_session_start();
// Идентификатор сессии должен генерироваться заново при:
//  - Входе пользователя в систему
//  - Выходе пользователя из системы
//  - По прошествии определённого периода времени
my_session_regenerate_id();

ini_set('session.cookie_domain', '.free74092.cpsite.ru');
session_set_cookie_params(7200, "/", ".free74092.cpsite.ru", false, false);
include 'ConnServer.php';
header('Content-Type: text/html; charset=utf8');
mb_internal_encoding('UTF-8');
$login =filter_var(trim($_POST['login']),FILTER_SANITIZE_STRING);
$password =filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
$sql= "SELECT * FROM Users WHERE Login = '$login' AND Password ='$password'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql , $params, $options );
$aut = sqlsrv_num_rows( $stmt );
if($aut > 0){
  $user = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
  $_SESSION['user']=[
    "id"=>$user['ID_User'],
    "role"=>$user['Role']
  ];
  if($_SESSION['user']['role'] == 'Студент'){
    header("Location: Student.php"); exit();
  }
  else if($_SESSION['user']['role'] == "Преподователь"){
    header('Location: Teacher.php'); exit();
  }
  else if($_SESSION['user']['role'] == "Заведующий отделением"){
    header('Location: HeadDepartament.php'); exit();
  }
  else if($_SESSION['user']['role'] == "Работник учебной части"){
    header('Location: Deansofficeworker.php'); exit();
  }
  else if($_SESSION['user']['role'] == "Администратор"){
    header('Location: Administrator.php'); exit();
  }
}
?>
