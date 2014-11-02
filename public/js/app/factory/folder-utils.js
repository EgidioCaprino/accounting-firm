app.factory('FolderUtils', function() {
    return {
        children: function(folders, idParent) {
            if (idParent === undefined) {
                idParent = null;
            }
            var children = [];
            folders.forEach(function(folder) {
                if (folder.id_parent === idParent) {
                    children.push(folder);
                }
            });
            return children;
        },
        findIndexById: function(folders, idFolder) {
            for (var i = 0; i < folders.length; ++i) {
                if (folders[i].id_folder === idFolder) {
                    return i;
                }
            }
            return -1;
        },
        findById: function(folders, idFolder) {
            var index = this.findIndexById(folders, idFolder);
            if (index >= 0) {
                return folders[index];
            }
            return null;
        },
        pathTo: function(folders, target) {
            var pathArray = [target];
            var current = target;
            while (current.id_parent !== null) {
                var parent = this.findById(folders, current.id_parent);
                pathArray.push(parent);
                current = parent;
            }
            pathArray.reverse();
            return pathArray;
        },
        root: function(folders) {
            var root = null;
            folders.forEach(function(folder) {
                if (!folder.public && folder.id_parent === null) {
                    if (root !== null) {
                        throw new Error("Found more then one root folder");
                    }
                    root = folder;
                }
            });
            return root;
        }
    };
});