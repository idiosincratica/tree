<?php defined('SITE') or die; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<?php require_once 'head.php'; ?>
</head>
<body>
<header class="header">
	<nav class="nav">
		<a href="<?=BASE?>" class="nav__item">Главная</a>
		<?php if(!empty($_SESSION['username'])): ?>
			<a href="<?=BASE?>/admin" class="nav__item">Админка</a>
			<span>Привет,&nbsp;<?=$_SESSION['username']?>!</span>
			<a href="<?=BASE?>/logout" class="nav__item">Покинуть</a>
		<?php else: ?>
			<a href="<?=BASE?>/login" class="nav__item">Вход</a>
		<?php endif; ?>
	</nav>
	<h1 class="header__title"><a href="<?=BASE?>" class="header__title-link">Структура данных</a></h1>
</header>
<div class="wrapper">
	<main class="main">
	<?php
	Doc::print_messages();
	echo $content;
	?>
	</main>
</div>
<footer class="footer"></footer>
</body>
</html>
