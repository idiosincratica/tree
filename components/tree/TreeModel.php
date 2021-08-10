<?php defined('SITE') or die; ?>
<?php

class TreeModel{
    static function clearTree(){
        restricted_access();
        $dbh = db::get();

        $query = 'delete from tree';
        $dbh->query($query);
    }

    static function createNode($data){
        restricted_access();
        $dbh = db::get();
        
        $parent_id = $data['parent_id'] ?? NULL;
        $query = 'insert into tree set name=:name, description=:description, parent_id=:parent_id';
        $sth = $dbh->prepare($query);
        $sth->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':parent_id' => $parent_id,
        ]);
        return $dbh->lastInsertId();
    }

    static function updateNode($data){
        restricted_access();
        $dbh = db::get();
        
        $parent_id = $data['parent_id'] ?? NULL;
        $query = 'update tree set name=:name, description=:description, parent_id=:parent_id where id=:id';
        $sth = $dbh->prepare($query);
        $sth->execute([
            ':id' => $data['node_id'],
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':parent_id' => $parent_id,
        ]);
    }

    static function deleteNode($id){
        restricted_access();
        $dbh = db::get();
        $query = 'delete from tree';
        if(is_null($id)){
            $dbh->query($query);
        }
        else{
            $sth = $dbh->prepare($query.' where id=:id');
            $sth->execute([':id' => $id]);
        }
    }

    static function getChildren($parent_id, $with_parentage=true){
        $dbh = db::get();

        $wp = $with_parentage ? ", (select count(*) from tree t2 where t2.parent_id=t1.id) as children_count" : '';
        $query = "select id, name, description, parent_id$wp from tree t1 where parent_id";
        if(is_null($parent_id)){
            $sth = $dbh->query($query.' is null');
            $res = $sth->fetchAll();
        }
        else{
            $sth = $dbh->prepare($query.'=:parent_id');
            $sth->execute([
                ':parent_id' => $parent_id,
            ]);
            $res = $sth->fetchAll();
        }
        if($with_parentage){
            foreach($res as &$node){
                $node['is_parent'] = (bool)$node['children_count'];
            }
        }
        return $res;
    }

    // Validators

    static function isValidNewNode($data){
        extract($data);
        return (int)$parent_id == $parent_id and $name!='';
    }

    static function isValidExistingNode($data){
        extract($data);
        return (int)$node_id == $node_id and (int)$parent_id == $parent_id and $name!='';
    }

    static function isValidNodeId($parent_id){
        return (int)$parent_id == $parent_id;
    }
}