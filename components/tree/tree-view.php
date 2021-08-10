<?php defined('SITE') or die; ?>

<div class="description">
    <h3 class="description__title">Description</h3>
    <div class="description__inner"></div>
</div>

<script src="<?=BASE?>/public/script.js"></script>
<div id="tree_root"></div>
<script>
window.addEventListener('load', function(){
    Tree.getRootNodes(function(children){
        var settings = {
            descriptionBlock: document.getElementsByClassName('description__inner')[0]
        };
        var root = document.getElementById('tree_root');
        root.appendChild(Tree.Children(children, settings));
    });
});
</script>
