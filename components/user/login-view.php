<?php defined('SITE') or die; ?>
<form action="<?=BASE?>/login" method="post" class="admin-form login-form">
    <input type="text" name="username" value="<?=$username?>" placeholder="Username">
    <input type="password" name="password" value="<?=$password?>" placeholder="Password">
    <button type="submit">Login</button>
</form>