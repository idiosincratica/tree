
/* global Tree, TreeAdmin, sendRequest, insertAfter */

window.insertAfter = function(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
};

window.TreeAdmin = {};

TreeAdmin.nodeInsertId = function(data) {
    var infoEl = document.createElement('span');
    data.state.infoEl = infoEl;
    infoEl.innerText = 'id: ' + data.data.id;
    infoEl.classList.add('tree__node-id');
    insertAfter(infoEl, data.state.nameEl);
};

TreeAdmin.UpdateButton = function(data, updateForm) {
    var button = document.createElement('span');
    button.innerHTML = '&#9998;';
    button.classList.add('tree__update-button');
    button.addEventListener('click', function() {
        var els = updateForm.elements,
            d = data.data;
        els.node_id.value = d.id;
        els.name.value = d.name;
        els.parent_id.value = d.parent_id;
        els.description.value = d.description;
    });
    return button;
};

TreeAdmin.nodeInsertUpdateButton = function(data, updateForm) {
    var updateButton = TreeAdmin.UpdateButton(data, updateForm);
    insertAfter(updateButton, data.state.infoEl);
    data.state.updateButton = updateButton;
};

TreeAdmin.DeleteButton = function(data) {
    var button = document.createElement('span');
    button.innerHTML = '&Cross;';
    button.classList.add('tree__delete-button');
    button.addEventListener('click', function() {
        sendRequest('POST', 'api/deleteNode?id=' + data.data.id, function(_, status) {
            if (status === 200) {
                data.treeNode.remove();
            }
        });
    });
    return button;
};

TreeAdmin.nodeInsertDeleteButton = function(data) {
    var deleteButton = TreeAdmin.DeleteButton(data);
    insertAfter(deleteButton, data.state.updateButton);
};

TreeAdmin.createNodeAjax = function(form, settings) {
    // Collect form data
    var els = form.elements,
        params = {
            name: els.name.value,
            parent_id: els.parent_id.value,
            description: els.description.value
        };

    sendRequest('POST', 'api/node', function(data, status) {
        var parentNodeData,
            parentNodeChildren,
            node,
            treeNode;

        if (status !== 200) {
            alert('Ошибка, код ' + status);
            return;
        }

        // Modify the tree
        node = JSON.parse(data);
        treeNode = Tree.TreeNode(node, settings);
        if (node.parent_id === null) {
            window.state.root.appendChild(treeNode);
        } else {
            parentNodeData = window.state.treeModel[node.parent_id];
            parentNodeChildren = parentNodeData.state.children;
            if (parentNodeChildren) {
                parentNodeChildren.appendChild(treeNode);
            } else if (!parentNodeData.state.appendButton) {
                parentNodeData.data.is_parent = true;
                TreeAdmin.insertExpandButton(parentNodeData.treeNode, node.parent_id, parentNodeData.state, settings);
            }
        }
    }, params);
};

TreeAdmin.init = function() {
    var creationForm,
        ajaxCreationButton,
        settings = {
            onCreateEnd: function(data) {
                TreeAdmin.nodeInsertId(data);
                TreeAdmin.nodeInsertUpdateButton(data, document.getElementsByClassName('update-form')[0]);
                TreeAdmin.nodeInsertDeleteButton(data);
                window.state.treeModel[data.data.id] = data;
            },
            descriptionBlock: document.getElementsByClassName('description__inner')[0]
        };
    window.state = {treeModel: []};

    // Insert the tree to the page
    Tree.getRootNodes(function(children) {
        var rootNodes = Tree.Children(children, settings),
            root = document.getElementById('tree_root');
        window.state.root = rootNodes;
        root.appendChild(rootNodes);
    });

    // Connect ajax creation form with the tree
    creationForm = document.getElementsByClassName('add-form')[0];
    ajaxCreationButton = creationForm.getElementsByClassName('add-form__save-ajax')[0];
    ajaxCreationButton.addEventListener('click', function() {
        TreeAdmin.createNodeAjax(creationForm, settings);
    });
};
