<?php defined('SITE') or die; ?>
<form action="<?=BASE?>/admin/node/update" method="post" autocomplete="off" class="admin-form update-form">
    <input type="number" name="node_id" value="<?=$node_id?>" placeholder="Node Id" class="add-form__node-id">
    <input type="number" name="parent_id" value="<?=$parent_id?>" placeholder="Parent Id" class="add-form__parent-id">
    <input type="text" name="name" value="<?=$name?>" placeholder="*Name" class="add-form__name">
    <textarea name="description" value="<?=$description?>" placeholder="Description" class="textarea add-form__description"></textarea>
    <button type="submit" class="add-form__save">Update Node</button>
</form>