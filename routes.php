<?php defined('SITE') or die; ?>
<?php
function routes(){

	if(VIRTUAL_PATH === '/'){
		require_once 'components/tree/TreeController.php';
		echo render_template(TreeController::showTree());
	}
	elseif(VIRTUAL_PATH === '/login'){
		require_once 'components/user/UserController.php';
		echo render_template(UserController::login());
	}
	elseif(VIRTUAL_PATH === '/logout'){
		logout();
	}
	elseif(VIRTUAL_PATH === '/api/children'){
		require_once 'components/tree/TreeController.php';
		echo TreeController::getChildren();
	}
	elseif(VIRTUAL_PATH === '/api/rootNodes'){
		require_once 'components/tree/TreeController.php';
		echo TreeController::rootNodes();
	}
	elseif(VIRTUAL_PATH === '/api/node'){
		require_once 'components/tree/TreeController.php';
		echo TreeController::apiCreateNode();
	}
	elseif(VIRTUAL_PATH === '/api/deleteNode'){
		require_once 'components/tree/TreeController.php';
		TreeController::apiDeleteNode();
	}
	elseif(VIRTUAL_PATH === '/admin'){
		require_once 'components/tree/TreeController.php';
		echo render_template(TreeController::admin());
	}
	elseif(VIRTUAL_PATH === '/admin/node/create'){
		require_once 'components/tree/TreeController.php';
		echo render_template(TreeController::createNode());
	}
	elseif(VIRTUAL_PATH === '/admin/node/update'){
		require_once 'components/tree/TreeController.php';
		echo render_template(TreeController::updateNode());
	}
	elseif(VIRTUAL_PATH === '/admin/node/delete'){
		require_once 'components/tree/TreeController.php';
		echo render_template(TreeController::deleteNode());
	}
	else{
		throw new Error404();
	}

}