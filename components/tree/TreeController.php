<?php defined('SITE') or die; ?>
<?php

class TreeController extends Controller{
    static function showTree(){
        Doc::set_title('Дерево данных');
        Doc::set_description('Это дерево данных которое можно раскрывать и смотреть');
        return self::render('tree-view.php');
    }

    static function getChildren(){
        require_once 'TreeModel.php';
        header("Content-type:application/json;");
        return json_encode(TreeModel::getChildren($_GET['parent_id']), JSON_PRETTY_PRINT);
    }

    static function rootNodes(){
        require_once 'TreeModel.php';
        header("Content-type:application/json;");
        return json_encode(TreeModel::getChildren(null), JSON_PRETTY_PRINT);
    }

    static function apiDeleteNode(){
        restricted_access();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            require_once 'TreeModel.php';
            header("Content-type:application/json;");
            if(!empty($_GET['id']) and TreeModel::isValidNodeId($_GET['id'])){
                TreeModel::deleteNode($_GET['id']);
            }
            else{
                header('HTTP/1.1 400 Bad Request');
            }
        }
        else{
            header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    static function apiCreateNode(){
        restricted_access();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $parent_id = !empty($_POST['parent_id']) ? prep($_POST['parent_id']) : NULL;
            $name = isset($_POST['name']) ? prep($_POST['name']) : '';
            $description = isset($_POST['description']) ? prep($_POST['description']) : '';

            require_once 'TreeModel.php';
            $node = compact('parent_id', 'name', 'description');

            if(TreeModel::isValidNewNode($node)){
                try{
                    $id = TreeModel::createNode($node);
                    if($id === false){
                        throw new Error500();
                    }
                    $node['id'] = $id;
                    header("Content-type:application/json;");
                    return json_encode($node, JSON_PRETTY_PRINT);
                }
                catch(PDOException $e){
                    throw new Error500();
                }
            }
            else{
                throw new Error400();
            }
        }
    }

    static function admin(){
        restricted_access();
        Doc::set_title('Админка');
        Doc::set_description('Админка дерева');
        
        if(isset($_GET['created'])){
            Doc::add_message('Нода добавлена');
        }
        
        if(isset($_GET['updated'])){
            Doc::add_message('Нода отредактирована');
        }
        
        if(isset($_GET['deleted'])){
            Doc::add_message('Нода удалена');
        }

        $forms = self::render('admin-create-node-view.php',
            ['parent_id' => '', 'name' => '', 'description' => '']) .
            self::render('admin-update-node-view.php',
            ['node_id' => '', 'parent_id' => '', 'name' => '', 'description' => '']) .
            self::render('admin-delete-node-view.php', ['node_id' => '']);
        return self::render('admin-tree-view.php', ['forms' => $forms]);
    }

    static function createNode(){
        restricted_access();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $parent_id = !empty($_POST['parent_id']) ? prep($_POST['parent_id']) : NULL;
            $name = isset($_POST['name']) ? prep($_POST['name']) : '';
            $description = isset($_POST['description']) ? prep($_POST['description']) : '';

            require_once 'TreeModel.php';
            $node = compact('parent_id', 'name', 'description');

            if(TreeModel::isValidNewNode($node)){
                try{
                    TreeModel::createNode($node);
                    header('Location: '.BASE.'/admin?created');
                    exit;
                }
                catch(PDOException $e){
                    Doc::add_error('Ошибка сохранения данных');
                }
            }
            else{
                Doc::add_error('Есть неправильно заполненные поля');
                Doc::set_title('Сохранение нод');
                return self::render('admin-create-node-view.php',
                ['parent_id' => $parent_id, 'name' => $name, 'description' => $description]);
            }
        }
    }

    static function updateNode(){
        restricted_access();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $node_id = isset($_POST['node_id']) ? prep($_POST['node_id']) : '';
            $parent_id = !empty($_POST['parent_id']) ? prep($_POST['parent_id']) : NULL;
            $name = isset($_POST['name']) ? prep($_POST['name']) : '';
            $description = isset($_POST['description']) ? prep($_POST['description']) : '';

            require_once 'TreeModel.php';
            $node = compact('node_id', 'parent_id', 'name', 'description');

            if(TreeModel::isValidExistingNode($node)){
                try{
                    TreeModel::updateNode($node);
                    header('Location: '.BASE.'/admin?updated');
                    exit;
                }
                catch(PDOException $e){
                    Doc::add_error('Ошибка сохранения данных');
                }
            }
            else{
                Doc::add_error('Есть неправильно заполненные поля');
                Doc::set_title('Редактирование нод');
                return self::render('admin-update-node-view.php',
                ['node_id' => $node_id, 'parent_id' => $parent_id, 'name' => $name, 'description' => $description]);
            }
        }
    }

    static function deleteNode(){
        restricted_access();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $node_id = !empty($_POST['node_id']) ? prep($_POST['node_id']) : NULL;

            require_once 'TreeModel.php';

            if(TreeModel::isValidNodeId($node_id)){
                try{
                    TreeModel::deleteNode($node_id);
                    header('Location: '.BASE.'/admin?deleted');
                    exit;
                }
                catch(PDOException $e){
                    Doc::add_error('Ошибка сохранения данных');
                }
            }
            else{
                Doc::add_error('Поле заполнено неправильно');
                Doc::set_title('Удаление ноды');
                return self::render('admin-delete-node-view.php', ['node_id' => $node_id]);
            }
        }
    }    
}