/* global onZoomIn, onZoomOut, onZoomFit, onZoomReset, onDownload */

var TYPE_FAMILYTREE = "familytree";
var TYPE_TIMELINE = "timeline";

// The coordinate system is either {depth, offset} or {offset, depth}
// depending on the type of map we're generating
var DEPTH_COORD = {};
DEPTH_COORD[TYPE_TIMELINE] = "X";
DEPTH_COORD[TYPE_FAMILYTREE] = "Y";

var OFFSET_COORD = {};
OFFSET_COORD[TYPE_FAMILYTREE] = "X";
OFFSET_COORD[TYPE_TIMELINE] = "Y";

var PARENTS = 0;
var PARENT_ID = 1;

var g_MapItems = null;
var g_ArchiveItems = null;
var g_SubMapItems = null;
var g_Options = {sub: false};
var g_ClashedItems = [];
var g_Map = null;

// The offsets
var g_Offsets = {
    width_min: 0, 
    width_max: 0, 
    height_min: 0, 
    height_max: 0
};

function setMapItems (map) {
    g_Map = map;
    
    var parent = {
        id: parseInt(map.id, 10),
        name: map.name,
        meaning_name: map.hasOwnProperty('meaning_name') ? map.meaning_name : null,
        descr: map.hasOwnProperty('descr') ? map.descr : null,
        aka: map.hasOwnProperty('aka') ? map.aka : null,
        gender: map.hasOwnProperty('gender') ? map.gender : null,
        date: map.hasOwnProperty('date') ? map.date : null,
        length: map.hasOwnProperty('length') ? map.length : null,
        book_start_id: map.hasOwnProperty('book_start_id') ? map.book_start_id : null, 
        book_start_chap: map.hasOwnProperty('book_start_chap') ? map.book_start_chap : null,
        book_start_vers: map.hasOwnProperty('book_start_vers') ? map.book_start_vers : null,
        book_end_id: map.hasOwnProperty('book_end_id') ? map.book_end_id : null,
        book_end_chap: map.hasOwnProperty('book_end_chap') ? map.book_end_chap : null,
        book_end_vers: map.hasOwnProperty('book_end_vers') ? map.book_end_vers : null,
        parent_id: "-1",
        gen: 0,
        gen_index: 0,
        level: 1,
        notes: map.hasOwnProperty("parent_notes") ? map.parent_notes : [],
        root: true
    };
    
    // Set the initial items
    g_MapItems = [parent].concat(map.items);
    
    // Convert the generations and levels to integers if they are strings
    g_MapItems.forEach(function(item) {
        item.gen = parseInt(item.gen, 10);
        item.level = parseInt(item.level, 10);
                
        // The notes should be ordered per item
        item.notes = item.notes ? item.notes : 
                    map.notes["activity" + item.id] ? 
                    map.notes["activity" + item.id] : 
                    map.notes["event" + item.id] ? 
                    map.notes["event" + item.id] :
                    map.notes["people" + item.id] ? 
                    map.notes["people" + item.id] : [];
    });
    
    g_MapItems.forEach(function(item) {    
        // Set the parents and the children/sublevels
        setParents(item.id, item.parent_id);
    });
    
    // Archive the subs for later use
    g_ArchiveItems = filterMapItems('level', 2);
    
    // Now remove the other levels from the official map items array
    g_MapItems = filterMapItems('level', 1);
    
    // Remove the duplicates
    g_MapItems = removeDuplicates(g_MapItems);
    
    g_MapItems = sortMapItems(g_MapItems);
    
    return g_MapItems;
}

function setSubMapItems(id) {
    var ancestor = getMapItem(id);
    
    var parent = {
        id: parseInt(ancestor.id, 10),
        name: ancestor.name,
        meaning_name: ancestor.hasOwnProperty('meaning_name') ? ancestor.meaning_name : null,
        descr: ancestor.hasOwnProperty('descr') ? ancestor.descr : null,
        aka: ancestor.hasOwnProperty('aka') ? ancestor.aka : null,
        gender: ancestor.hasOwnProperty('gender') ? ancestor.gender : null,
        date: ancestor.hasOwnProperty('date') ? ancestor.date : null,
        length: ancestor.hasOwnProperty('length') ? ancestor.length : null,
        book_start_id: ancestor.hasOwnProperty('book_start_id') ? ancestor.book_start_id : null, 
        book_start_chap: ancestor.hasOwnProperty('book_start_chap') ? ancestor.book_start_chap : null,
        book_start_vers: ancestor.hasOwnProperty('book_start_vers') ? ancestor.book_start_vers : null,
        book_end_id: ancestor.hasOwnProperty('book_end_id') ? ancestor.book_end_id : null,
        book_end_chap: ancestor.hasOwnProperty('book_end_chap') ? ancestor.book_end_chap : null,
        book_end_vers: ancestor.hasOwnProperty('book_end_vers') ? ancestor.book_end_vers : null,
        parent_id: "-1",
        gen: 0,
        gen_index: 0,
        level: 2,
        notes: ancestor.hasOwnProperty("notes") ? ancestor.notes : [],
        root: true,
        parents: [],
        children: Array.from(ancestor.subChildren),
        subChildren: Array.from(ancestor.subChildren)
    };
    
    g_SubMapItems = [parent];
    
    // The children of the item
    var children = Array.from(parent.children);
    var newChildren = null;
    
    while (children.length > 0) {
        // The child to currently work with
        var childId = children.shift();
        var childItem = getArchiveItem(childId);
        
        // Add this child to the list
        g_SubMapItems.push(childItem);
        
        // Find the next generation
        var newChildren = childItem.children;
        if (newChildren.length > 0) {
            children = children.concat(newChildren);
        }
    }
    
    // Remove the duplicates
    g_SubMapItems = removeDuplicates(g_SubMapItems);
    
    g_SubMapItems = sortMapItems(g_SubMapItems);
    
    return g_SubMapItems;
}

function getMapItems() {
    return g_Options.sub ? g_SubMapItems : g_MapItems;
}

function removeDuplicates(mapItems) {
    // Remove the duplicates
    return mapItems.reduce(function(mapItems, mapItem) {
        
        // Get the duplicates
        var dupl = mapItems.filter(item => item.id === mapItem.id);
        
        if (dupl.length > 0) {
            // There are duplicutes, get the index of it
            var idx = mapItems.indexOf(dupl[0]);

            // Get the element with the highest generation
            // Make sure to copy it's (sub)children/(sub)parents
            var mapItem = dupl.reduce(function(item, newItem) {
                item.children = item.children.concat(newItem.children)
                        .filter((value, index, self) => self.indexOf(value) === index);
                item.parents  = item.parents.concat(newItem.parents)
                        .filter((value, index, self) => self.indexOf(value) === index);
                item.subChildren = item.subChildren.concat(newItem.subChildren)
                        .filter((value, index, self) => self.indexOf(value) === index);
                item.subParents = item.subParents.concat(newItem.subParents)
                        .filter((value, index, self) => self.indexOf(value) === index);
                item.gen = Math.max(item.gen, newItem.gen);
                
                return item;
            }, mapItem);
            
            // Remove the duplicate from the array
            mapItems.splice(idx, 1);
        }
        
        // Add it to the end of the mapItems array
        mapItems.push(mapItem);
        
        // Return the array for the new round
        return mapItems;
    }, []);
}

function sortMapItems(mapItems) {
    // Max generation
    var maxGen = Math.max(...mapItems.map(item => item.gen));
    
    // Make sure parents and children have the highest generation possible 
    // for the best readability in case of timelines
    if ((g_Options.type === TYPE_TIMELINE) || (mapItems[0].id === "-999")) {
        // In this case it's a timeline, let's go by generation
        for (var i = 0; i < maxGen; i++) {
            // Get all the parents of this generation
            var parents = filterMapItems('gen', i);
            
            parents.forEach(function(mapItem) {
                if (mapItem.children.length !== 0) {
                    var lowestGenChild = mapItem.children.reduce(function(lowestGen, childIdx) {
                        var child = getMapItem(childIdx);
                        if (lowestGen === -1) {
                            return child.gen;
                        } else {
                            return child.gen < lowestGen ? child.gen : lowestGen;
                        }
                    }, -1);
                    
                    mapItem.gen = lowestGenChild - 1;
                } else if (mapItem.parent_id !== "-1") {
                    mapItem.gen = getMapItem(mapItem.parent_id).gen + 1;
                }
            });
        }
        
        // In this case it's a timeline, let's go by generation
        for (var i = 0; i < maxGen; i++) {
            // Get all the children of this generation
            var children = filterMapItems('gen', i);
            
            children.forEach(function(mapItem) {
                if (mapItem.parents.length !== 0) {
                    var highestGenParent = mapItem.parents.reduce(function(highestGen, parentIdx) {
                        var parent = getMapItem(parentIdx);
                        if (highestGen === -1) {
                            return parent.gen;
                        } else {
                            return parent.gen > highestGen ? parent.gen : highestGen;
                        }
                    }, -1);
                    
                    mapItem.gen = highestGenParent + 1;
                }
            });
        }
    }
    
    // Lets go per generation
    for (var i = 0; i < maxGen; i++) {
        // Per generation, check the gen_index of the parents and sort by that
        var items = filterMapItems('gen', i).sort(function(a, b) {
            return a.gen_index - b.gen_index;
        });
        
        // Now get the children
        var items = items.reduce(function (array, item) {
            var children = item.children.map(child => getMapItem(child));           
            return array.concat(
                children.filter((child) => child.gen === (item.gen + 1))
            );
    
        }, []);
        
        // Now set the gen indexes in that order
        items.forEach((child, index) => child.gen_index = index);
    }
    
    // Reorder the mapItems by generation and gen index
    return mapItems.sort(function(a, b) {
        if ((a.gen > b.gen) || (a.gen === b.gen && a.gen_index > b.gen_index)) {
            return 1;
        }
        if ((a.gen < b.gen) || (a.gen === b.gen && a.gen_index < b.gen_index)) {
            return -1;
        }
        return 0;
    });
}

function calcMapItems(options = new Object()) {
    
    // The size of the items
    if(!options || !options.hasOwnProperty('item_width')) 
        options.item_width = options.type === TYPE_FAMILYTREE ? 100 : 300;
    if(!options || !options.hasOwnProperty('item_height')) 
        options.item_height = 50;
    
    // The distance between the items
    if(!options || !options.hasOwnProperty('hori_dist')) 
        options.hori_dist = 25;
    if(!options || !options.hasOwnProperty('vert_dist')) 
        options.vert_dist = 30;
    
    // The global settings used
    g_Options = {
        "length": {X: options.item_width, Y: options.item_height},
        "dist": {X: options.hori_dist, Y: options.vert_dist},
        "type": options.type,
        "sub": g_Options.sub
    };
    
    g_ClashedItems = [];
    
    getMapItems().forEach(function(item) { 
        
        item.width = options.item_width;
        item.height = options.item_height;
        
        item[DEPTH_COORD[g_Options.type]] = calcDepth(item);
        item[OFFSET_COORD[g_Options.type]] = calcOffset(item);
    
        // We calculated its coordinates
        item.calculated = true;
    });
    
    // Sort the array by ancestors, since those need to be moved
    sortByAncestor();
    
    // Try to solve all the clashes we found, 
    // then get the heighest X and Y values 
    // and use it to shift the entire thing
    g_ClashedItems.forEach(item => solveClash(item));
    getMapItems().forEach(item => getOffsets(item));
    getMapItems().forEach(item => setOffsets(item));
    
    return getMapItems();
    
}

function setParents(id, parent_id) {

    // Set the parent of this child
    var children = filterMapItems('id', id);
    
    // There might be duplicates
    children.forEach(function(child) {
        if (!child.hasOwnProperty('parents'))
            child.parents = [];
        if (!child.hasOwnProperty('children'))
            child.children = [];
        
        // SubParents.Children = When a child has a lower level then it's parent
        if (!child.hasOwnProperty('subParents'))
            child.subParents = [];
        if (!child.hasOwnProperty('subChildren'))
            child.subChildren = [];

        // Set the child of this parent
        var parents = filterMapItems('id', parent_id);
        
        // There might be duplicates
        parents.forEach(function(parent) {
            // Set the parents
            if (!child.parents.includes(parent_id) && parent_id !== "-1") {
                if (child.level === parent.level) {
                    // We are the child of this parent
                    child.parents.push(parent_id);
                } else if (child.level < parent.level) {
                    // Keep going up until the parent is found with a higher level and use that as the new parent_id
                    child.parent_id = getSubParent(child.level, parent);
                    if (!child.parents.includes(child.parent_id) && child.parent_id !== "-1") {
                        child.parents.push(child.parent_id);
                    }
                    
                    // Work with the new parent
                    parent = getMapItem(child.parent_id);
                } else {
                    // We are a sub of this parent, don't add it as a child
                    child.parents.push(parent_id);
                }
            }

            if (parent) {
                if (!parent.hasOwnProperty('parents'))
                    parent.parents = [];
                if (!parent.hasOwnProperty('children'))
                    parent.children = [];
                if (!parent.hasOwnProperty('subParents'))
                    parent.subParents = [];
                if (!parent.hasOwnProperty('subChildren'))
                    parent.subChildren = [];

                // Set the children
                if (!parent.children.includes(id)) {
                    if (parent.level === child.level) {
                        // We are the parent of this child
                        parent.children.push(id);
                    } else if (parent.level < child.level) {
                        // This child is a sub of this parent, add it as a sub
                        parent.subChildren.push(id);
                    } else {
                        // This parent is a sub and the child is not actually
                        // a child. 
                    }
                }
            }
        });
    });
    
}

function getChildrenByParentId(id) {
    // Get the children of this item
    // First get all the items with this id as parent
    var items = filterMapItems('parent_id', id);
    var children = items.map(item => item.id);
    
    // Return the (valid) children
    return children.filter(item => item !== "-1");
}

function getMapItem(id) {
    // The order kinda depends on whether we are in sub more or not
    var searchItems = g_Options.sub && g_SubMapItems ? 
            g_SubMapItems.concat(g_MapItems) : 
            g_MapItems.concat(g_SubMapItems);
    
    var items = searchItems.filter(function(item) {
        return item !== null ? item.id === id : false;
    });
    return items.length > 0 ? items[0] : null;
}

function getArchiveItem(id) {    
    var items = g_ArchiveItems.filter(function(item) {
        return item !== null ? item.id === id : false;
    });
    return items.length > 0 ? items[0] : null;
}

function getLeftGenSibling(id) {
    // Get the item we want to siblings of on the left
    var item = getMapItem(id);
    
    // The items on the same generation on the left (lower genIndex)
    var items = filterMapItems('gen', item.gen).filter(genSibling => genSibling.gen_index < item.gen_index);
    
    // Return the most right sibling
    return items[items.length - 1]; 
}

function getRightGenSiblings(id) {
    // Get the item we want to siblings of on the right
    var item = getMapItem(id);
    
    // The items on the same generation on the right (higher genIndex)
    var items = filterMapItems('gen', item.gen).filter(genSibling => genSibling.gen_index >= item.gen_index);
    
    // Only get the ids of these items
    var siblings = items.map(item => item.id);
    
    // Return these items
    return siblings; 
}

function getGenSiblings(id) {
    // Get the item we want to siblings of
    var item = getMapItem(id);
    
    // The items on the same generation
    var items = filterMapItems('gen', item.gen);
    
    // Only get the ids of these items
    var siblings = items.map(item => item.id);
    
    // Return these items
    return siblings; 
}

function getAncestors(id) {
    // The parents of the item
    var parents = [id];
    var ancestors = [];
    
    while (parents.length > 0) {
        var parentId = parents.shift();
        var parentItem = getMapItem(parentId);
        
        var newParent = parentItem.parent_id;
        if (newParent !== "-1") {
            ancestors.push(newParent);
            parents.push(newParent);
        }
    }
    
    return ancestors;
}

function getCommonAncestor(leftId, rightId) {
    
    var left = getAncestors(leftId);
    var right = getAncestors(rightId);
            
    // Get the common ancestor
    var commonAncestor = -1;
    left.forEach(function(item) {
        // Is this ancestor of the left side of the clash also on the 
        // right side of the clash?
        var ancestor = right.indexOf(item);
        if ((ancestor !== -1) && (commonAncestor === -1)) {
            commonAncestor = item;
        }
    });
    
    // Get the first child of this ancestor on the right side of the clash
    var rightAncestor = -1;
    right.forEach(function(item) {
        // Is this ancestor of the left side of the clash also on the 
        // right side of the clash?
        var ancestor = getChildrenByParentId(commonAncestor).indexOf(item);
        if ((ancestor !== -1) && (rightAncestor === -1)) {
            rightAncestor = item;
        }
    });
    
    return rightAncestor !== -1 ? getMapItem(rightAncestor) : getMapItem(rightId);
}

function moveCommonAncestor(offset, parent) {    
    // Start offsetting the parent and everything on the right
    var items = getRightGenSiblings(parent.id);
    
    while (items.length > 0) {
        // The ids of the items
        var id = items.shift();
        
        // The actual items themselves
        var item = getMapItem(id);
        item[OFFSET_COORD[g_Options.type]] = item[OFFSET_COORD[g_Options.type]] + offset;
        
        // Get the children as well (only the calculated ones)
        items = items.concat(getChildrenByParentId(item.id));
    }
    
    // Now offset the parents on the right as well until we've reached the 
    // true ancestor, unless none of these have children..
    // Right side generation siblings don't always need to be moved..
    // Only those who have children and those on the right of these
    var ancestors = getAncestors(parent.id);
    
    var siblingParents = [];
    var trueAncestor = false;
    ancestors.forEach(function(id) {
        // As long as we haven't found the true ancestor yet
        if (trueAncestor === false) {
            // The siblings of these ancestors
            var siblings = getRightGenSiblings(id);

            // Only move when any has children
            var child = false;        
            var newParents = [];

            siblings.forEach(function(sibling) {
                // Not the actual ancestor, just its siblings
                if (!ancestors.includes(sibling)) {
                    // The actual items themselves
                    var item = getMapItem(sibling);

                    // Check if they have children
                    var children = getChildrenByParentId(item.id);
                    if (children.length > 0) {
                        // Only update it when this item has children
                        // Otherwise just leave it be
                        child = true;
                    }

                    if (child || (siblingParents.indexOf(item.parent_id) !== -1)) {
                        // This person has children, put them in the newParents
                        // And set their offset
                        item[OFFSET_COORD[g_Options.type]] = item[OFFSET_COORD[g_Options.type]] + offset;
                        newParents.push(sibling);
                    }
                }
            });
        
            if (getGenSiblings(id).length === 1) {
                // No siblings to work with, 
                // meaning that we reached the true ancestor
                trueAncestor = true;
            }
        
            siblingParents = newParents;
        }
    });
}

function filterMapItems(prop, value) {
    return getMapItems().filter(function(item) {
        return ["parents", "children"].includes(prop) ? (item[prop].includes(value)) : (item[prop] === value);
    });
}

function getSubParent(level, parent) {
    while (parent.level !== level) {
        parent = getMapItem(parent.parent_id);
    }
    return parent.id;
}

function getSubParents(level) {
    // Find every parent with subchildren
    return getMapItems().filter(function(item) {
        return item.subChildren.length > 0 && item.level === level;
    });
}

function itemHasSubChildren(item) {
    return item.hasOwnProperty("subChildren") && g_Options.sub === false ? item.subChildren.length > 0 : false;
}

function calcDepth(item) {    
    var cDepth = 0;
    
    // The depth depends on the parents
    if(item.parents.length) {
        // Get the highest generation parent
        var parent = item.parents.reduce(function(parent1, idx) {
            var parent2 = getMapItem(idx);
            
            return (parent1.gen < parent2.gen) ? parent2 : parent1;
        }, getMapItem(item.parents[0]));
        
        // Get the parent depth coordinate, add the height to it 
        // and the standard vertical offset
        cDepth = parent[DEPTH_COORD[g_Options.type]] + g_Options.length[DEPTH_COORD[g_Options.type]] + g_Options.dist[DEPTH_COORD[g_Options.type]];
    }
    return cDepth;
}

function calcOffset(item) {
    var cOffset = 0;
    
    // The offset depends on the parent
    if(item.parent_id !== "-1") {
        var parent = getMapItem(item.parent_id);
        
        // Get the average offset coordinate of the parents
        if (g_Options.type === TYPE_FAMILYTREE) {
            var avgOffset = parent[OFFSET_COORD[g_Options.type]];
        } else {
            var parentOffsets = [];
            
            // Get all the parents for this child 
            // a.k.a search for every child with this id
            var avgOffset = filterMapItems("children", item.id).reduce(function(carry, parent) {
                carry += parseInt(parent[OFFSET_COORD[g_Options.type]], 10);
                parentOffsets.push(parent[OFFSET_COORD[g_Options.type]]);
                return carry;
            }, 0);
            
            if (parentOffsets.length > 0) {
                // Parents directly above us
                var avgOffset = avgOffset / parentOffsets.length;
            } else {
                // No parents directly above us?
                var parent = getMapItem(item.parent_id);
                avgOffset = parent[OFFSET_COORD[g_Options.type]];
            }
        }
        
        // Number of children of parent
        if (parent.children.length % 2) {  // odd
            var middle = ((parent.children.length + 1) / 2) - 1;
            var index = parent.children.indexOf(item.id);

            if (index === middle) {
                // Are we in the middle? 
                // Then just use parents offset coordinate
                cOffset = avgOffset;
            } else if (index > middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents offset coordinate
                var offset = index - middle;
                cOffset = avgOffset + offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents X coordinate
                var offset = middle - index;
                cOffset = avgOffset - offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            }
        } else { // even
            var middle = parent.children.length / 2;
            var index = parent.children.indexOf(item.id);
            if (index >= middle) {
                // Are we on the right side of the middle?
                // Place the block on the right side of parents offset coordinate
                var offset = index - middle;
                cOffset = (avgOffset + ((g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]) / 2)) + 
                        offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            } else {
                // Are we on the left side of the middle?
                // Place the block on the left side of parents offset coordinate
                var offset = middle - index;
                cOffset = (avgOffset + ((g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]) / 2)) - 
                        offset*(g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]]);
            }
        }
    }
    
    // Does this offset coordinate cause an overlap with the left generation sibling?
    var sibling = getLeftGenSibling(item.id);

    if (sibling) {
        // The distance needed between left and right
        var offset = (sibling[OFFSET_COORD[g_Options.type]] + (g_Options.length[OFFSET_COORD[g_Options.type]]) + g_Options.dist[OFFSET_COORD[g_Options.type]]) - cOffset; 
        if (offset > 0) { 
            var ancestor = getCommonAncestor(sibling.id, item.id);
            
            // Save it to solve it later
            g_ClashedItems.push({
                right: item.id,
                left: sibling.id,
                ancestor: ancestor.id
            });
        }
    }
    
    return cOffset;
}

function sortByAncestor() {
    g_ClashedItems.sort(function(left, right) {
        // Get the gens
        var genL = getMapItem(left.ancestor).gen;
        var genR = getMapItem(right.ancestor).gen;
        
        // Get the gen indexes
        var indexL = getMapItem(left.ancestor).gen_index;
        var indexR = getMapItem(right.ancestor).gen_index;
        
        // Sort by generation (desc) and then by gen index (asc)
        if (genL !== genR) {
            return genR - genL;
        } else if (genL === genR) {
            return indexL - indexR;
        }
    });
}

function solveClash(item) {
    // Get the items that are clashing
    var left = getMapItem(item.left);
    var right = getMapItem(item.right);
    
    // Make sure the clash is still present
    var offset = (left[OFFSET_COORD[g_Options.type]] + (g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]])) - right[OFFSET_COORD[g_Options.type]];
    if (offset > 0) {
        // Step 1: Find a common ancestor, and get the child on the 
        // right side of the clash
        var ancestor = getMapItem(item.ancestor);

        // Step 2: Per child of the ancester, move child and siblings to the right
        moveCommonAncestor(offset, ancestor);

        // Step 3: Check again
        // The distance needed between left and right
        var new_offset = (left[OFFSET_COORD[g_Options.type]] + (g_Options.length[OFFSET_COORD[g_Options.type]] + g_Options.dist[OFFSET_COORD[g_Options.type]])) - right[OFFSET_COORD[g_Options.type]];
        if (new_offset > 0) {
            // Something's not right.. We've just moved right,
			// and right is still not far enough..
//            console.log("There is an overlap detected! Again.." + "(offset: " + offset + ", new offset: " + new_offset + ")");
//            console.log("Left: ");
//            console.log(left);
//            console.log("Right: ");
//            console.log(right);
//            console.log("Ancestor: ");
//            console.log(ancestor);
        }
    }
}

function getOffsets(item) {
    g_Offsets.width_min = Math.min(item.X, g_Offsets.width_min);
    g_Offsets.width_max = Math.max(item.X, g_Offsets.width_max);
    g_Offsets.height_min = Math.min(item.Y, g_Offsets.height_min);
    g_Offsets.height_max = Math.max(item.Y, g_Offsets.height_max);
}

function setOffsets(item) {
    item.X = item.X - g_Offsets.width_min;
    item.Y = item.Y - g_Offsets.height_min;
}

function calcPolyLineCoords(items) {
    var child = items.child;
    var parent = items.parent;

        if (g_Options.type === TYPE_FAMILYTREE) {
            var coords = [
                [parent.X + parent.width / 2, 
                 parent.Y + parent.height], 
                [parent.X + parent.width / 2, 
                 parent.Y + parent.height + g_Options.dist.Y / 3], 
                [child.X + child.width / 2, 
                 child.Y - g_Options.dist.Y / 3], 
                [child.X + child.width / 2, 
                 child.Y]
            ];
        } else {
            coords = [
                [parent.X + parent.width, 
                 parent.Y + parent.height / 2], 
                [parent.X + parent.width + g_Options.dist.X / 3, 
                 parent.Y + parent.height / 2], 
                [child.X - g_Options.dist.X / 3, 
                 child.Y + child.height / 2], 
                [child.X, 
                 child.Y + child.height / 2]
            ];
        }
        
        return coords;
}
