<?php defined('SITE') or die; ?>
<form action="<?=BASE?>/admin/node/delete" method="post" autocomplete="off" class="admin-form delete-form">
    <input type="number" name="node_id" value="<?=$node_id?>" placeholder="Node Id" class="delete-form__node-id">
    <button type="submit" class="delete-delete">Delete Node</button>
</form>