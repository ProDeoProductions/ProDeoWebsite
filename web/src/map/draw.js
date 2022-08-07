
/* global g_MapItems, g_Options, ALIGNMENT_VERTICAL, get_settings, onBeforeZoom, onBeforePan */

// The global variable for the SVG where everything will be drawn in
var g_svg = null;

function setSVG(svg) {
    
    if (svg)
        g_svg = svg;
    
    return g_svg !== null;
}

function drawControlButtons(map) {    
    // The height and width of the SVG parent
    var div = $("#map_div").parent();
    div.append(`<div style="position: absolute; top: 0; right: 0; padding: inherit;" class="btn-group">
                    <button class="btn btn-primary" onclick="onZoomFit()" title="Zoom to fit"><i class="fa fa-expand" aria-hidden="true"></i></button>
                    <button class="btn btn-primary" onclick="onZoomReset()" title="Reset zoom"><i class="fa fa-compress" aria-hidden="true"></i></button>
                    <button class="btn btn-primary" onclick="onDownload('` + map.name + `')" title="Download familytree"><i class="fa fa-download" aria-hidden="true"></i></button>
                    <button class="btn btn-primary" title="More information"><i class="fa fa-info-circle" aria-hidden="true"></i></button>
                </div>`);
}
    
function drawMapItems() {
    
    // Set the background of the entire thing
    g_svg.addClass('bg-light');

    // The root parent
    var group = g_svg.group({id: "map"});    
    g_MapItems.forEach(function(item) {
        drawLink(group, item);
        drawItem(group, item);
    });
    
    // TODO:
//    g_svg.text(dict["familytree.link"]).attr("id", "tooltip").hide();
    group.element("title").words("Click to go to details page");
}

function drawItem(group, item) {
    
    // The link to the object
    if (g_Options.align === ALIGNMENT_VERTICAL) {
        var link = group.link(setParameters("peoples/people/" + item.id));
        link.target('_blank');
    
        // Draw the rectangle
        link.rect(item.x_length, 
                  item.y_length)
                .fill(["-1", "0"].includes(item.gender) ? 'lightgrey' : (item.gender === "1" ? 'lightblue' : 'pink'))
                .stroke('black')
                .radius(10, 10)
                .move(item.X, item.Y)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });

        //Insert the text
        link.text(item.name)
                .font({size: 20})
                .center(item.X + item.x_length / 2, 
                        item.Y + item.y_length / 2)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });
    } else {
        // Turn it all counter clock wise
        var link = group.link(setParameters("events/event/" + get_settings["id"]));
        link.target('_blank');
    
        // Draw the rectangle
        link.rect(item.y_length, 
                  item.x_length)
                .fill(["-1", "0"].includes(item.gender) ? 'lightgrey' : (item.gender === "1" ? 'lightblue' : 'pink'))
                .stroke('black')
                .radius(10, 10)
                .move(item.Y, item.X)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });

        //Insert the text
        link.text(item.name)
                .font({size: 20})
                .center(item.Y + item.y_length / 2, 
                        item.X + item.x_length / 2)

    //    // When the mouse hovers over the link
    //    .mouseover(function() {
    //        SVG("#tooltip").show().move(item.X, item.Y);
    //    })
    //    
    //    // When the mouse no longer hovers over the link
    //    .mouseout(function() {
    //        SVG("#tooltip").hide();
    //    });
    }
    
}

function drawLink(group, child) {
    if (child.root !== true) {
        child.parents.forEach(function (parent_id) {
            var parent = getMapItem(parent_id);

            if (g_Options.align === ALIGNMENT_VERTICAL) {
                group.polyline([
                            [parent.X + parent.x_length / 2, 
                             parent.Y + parent.y_length], 
                            [parent.X + parent.x_length / 2, 
                             parent.Y + parent.y_length + g_Options.y_dist / 3], 
                            [child.X + child.x_length / 2, 
                             child.Y - g_Options.y_dist / 3], 
                            [child.X + child.x_length / 2, 
                             child.Y]])
                    .fill('none')
                    .stroke({ color: ["-1", "0"].includes(parent.gender) ? 'lightgrey' : (parent.gender === "1" ? 'lightblue' : 'pink'),
                              width: 4, linecap: 'round', linejoin: 'round' });
            } else {
                group.polyline([
                            [parent.Y + parent.y_length, 
                             parent.X + parent.x_length / 2], 
                            [parent.Y + parent.y_length + g_Options.y_dist / 3, 
                             parent.X + parent.x_length / 2], 
                            [child.Y - g_Options.y_dist / 3, 
                             child.X + child.x_length / 2], 
                            [child.Y, 
                             child.X + child.x_length / 2]])
                    .fill('none')
                    .stroke({ color: ["-1", "0"].includes(parent.gender) ? 'lightgrey' : (parent.gender === "1" ? 'lightblue' : 'pink'),
                              width: 4, linecap: 'round', linejoin: 'round' });
                 
            }
        });
    }
}


