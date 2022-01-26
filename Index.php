<!DOCTYPE html>
<html lang="ru">
 <head>
  <meta charset="UTF-8">
  <title>Форма авторизации</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
 <body>
   <header class="header">
     <a><img class="logo"src="Images/Logo.png"></a><a class="header-autor">Успеваймость студентов</a>
   </header>
<form action="check.php" class="conteiner"method="post">
  <h1 class="text-login">Вход в систему</h1>
  <a class="logpas-text">Логин</a>
  <input type="text" class="form-control"name="login">
  <a class="logpas-text">Пароль</a>
  <input type="text" class="form-control"name="password">
  <button type="submit"class="button-enter text-enter">Войти</button>
</form>
 </body>
</html>
