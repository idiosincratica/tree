<?php defined('SITE') or die; ?>
<form action="<?=BASE?>/admin/node/create" method="post" autocomplete="off" class="admin-form add-form">
    <input type="number" name="parent_id" value="<?=$parent_id?>" placeholder="Parent Id" class="add-form__parent-id">
    <input type="text" name="name" value="<?=$name?>" placeholder="*Name" class="add-form__name">
    <textarea name="description" value="<?=$description?>" placeholder="Description" class="textarea add-form__description"></textarea>
    <button type="submit" class="add-form__save">Create Node</button>
    <button type="button" class="add-form__save-ajax">Create Node AJAX</button>
</form>