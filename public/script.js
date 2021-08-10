
/* global Tree, sendRequest */

window.sendRequest = function(method, url, callback, postParams) {
    var oReq,
        arr,
        name,
        s;
    oReq = new XMLHttpRequest();
    oReq.onreadystatechange = function() {
        if (this.readyState === 4 && typeof callback == 'function') {
            callback(this.responseText, this.status);
        }
    };
    oReq.open(method, url);

    if (method === 'POST') {
        oReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    if (typeof postParams !== 'object' || postParams === null) {
        oReq.send();
    } else {
        arr = [];
        for (name in postParams) {
            arr.push(name + '=' + encodeURIComponent(postParams[name]));
        }
        s = arr.join('&');
        oReq.send(s);
    }
};

window.Tree = {};

Tree.getChildren = function(id, callback) {
    sendRequest('GET', 'api/children?parent_id=' + id, function(data) {
        var children = JSON.parse(data);
        callback(children);
    });
};

Tree.getRootNodes = function(callback) {
    sendRequest('GET', 'api/rootNodes', function(data) {
        var children = JSON.parse(data);
        callback(children);
    });
};

Tree.NameEl = function(name, onClickListener) {
    var nameEl = document.createElement('span');
    nameEl.classList.add('tree__node-name');
    nameEl.innerText = name;
    if (typeof onClickListener == 'function') {
        nameEl.addEventListener('click', onClickListener);
    }
    return nameEl;
};

Tree.ExpandButton = function(onClickListener) {
    var button = document.createElement('button');
    button.classList.add('tree__expand-button');
    button.innerText = '+';
    button.addEventListener('click', onClickListener);
    return button;
};

Tree.Children = function(children, settings) {
    // Returns an element that contains all the children of one parent
    var i,
        ul = document.createElement('ul');
    ul.classList.add('tree__children');
    for (i = 0; i < children.length; i++) {
        ul.appendChild(Tree.TreeNode(children[i], settings));
    }
    return ul;
};

Tree.insertExpandButton = function(treeNode, id, state, settings) {
    var button = Tree.ExpandButton(function() {
        Tree.getChildren(id, function(children) {
            var ul = Tree.Children(children, settings);
            treeNode.appendChild(ul);
            state.children = ul;
            button.remove();
        });
    });
    treeNode.appendChild(button);
    state.appendButton = button;
};

Tree.TreeNode = function(data, settings) {
    var treeNode = document.createElement('li'),
        state = {},
        nameEl;
    treeNode.classList.add('tree__node');

    // Place name element
    if (settings && settings.descriptionBlock && data.description) {
        nameEl = Tree.NameEl(data.name, function(ev) {
            if (ev.target !== ev.currentTarget) {
                return;
            }
            settings.descriptionBlock.innerText = data.description;
        });
    } else {
        nameEl = Tree.NameEl(data.name);
    }
    state.nameEl = nameEl;
    treeNode.appendChild(nameEl);

    // Place expand button element
    if (data.is_parent) {
        Tree.insertExpandButton(treeNode, data.id, state, settings);
    }

    // Hook that provides an extension point for the object
    if (settings && settings.onCreateEnd) {
        settings.onCreateEnd({treeNode: treeNode, data: data, state: state});
    }
    return treeNode;
};
