/* global dict_Events, dict_NavBar, session_settings, dict_Search */

function setMaps (parent) {
    
    // The title of the list
    $("#item_info").append(
        $("<p/>").html(dict_Events["map_event"])
    );
    
    // The contents of the list
    var ItemList = $("<ul/>").appendTo($("#item_info"));
    
    // TODO:
//    var ItemListIDs = getMaps(session_settings["id"]);
    var ItemListIDs = [];
    
    if (ItemListIDs.length > 0) {
        // For every map that this item is included in
        for (var i = 0; i < ItemListIDs.length; i++) {

            // Put the list item in the list of maps
            ItemList.append(
                $("<li/>").append(
                    // Put the link in a list item
                    $("<a/>").html(dict_NavBar["Timeline"] + (Number(ItemListIDs[i]) + 1))
                        .attr("id", ItemListIDs[i] + "," + session_settings["id"])
                        .click(function() {
                            goToPage("timeline.php", "", this.id);
                        })
                )
            );
        }
    } else {
        // If this item is not in a known map
        // Show a message
        ItemList.append($("<li/>").html(dict_Search["NoResults"]));
    }
}

