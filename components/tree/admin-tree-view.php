<?php defined('SITE') or die; ?>
<h2>Админка</h2>
<div class="admin-forms"><?=$forms?></div>
<div class="description">
    <h3 class="description__title">Description</h3>
    <div class="description__inner"></div>
</div>
<div id="tree_root"></div>
<script src="<?=BASE?>/public/script.js"></script>
<script src="<?=BASE?>/public/scriptAdmin.js"></script>
<script>
window.addEventListener('load', function(){
    TreeAdmin.init();
});

</script>