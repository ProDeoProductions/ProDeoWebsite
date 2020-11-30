<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "search";
    require 'layout/template.php'; 
?>

<script>
    function onLoadSearch() {
                
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        $("#content").append(
            $("<div/>").addClass("contents_left col-md-3 px-0").attr("id", "search_bar").append(
                $("<h1/>").html(dict_Search["options"])
            ).append(
                $("<form/>").attr("method", "get").attr("action", "search.php").append(
                    $("<select/>")
                        .attr("id", "table")
                        .attr("name", "table")
//                            .attr("disabled", "false") //"true")
                        .change(function() {
                            selectTableOptions($(this));
                        })
                        .append(
                            $("<option>")
                                .attr("id", "default")
                                .attr("value", "")
//                                    .attr("disabled", "true")
                                .attr("selected", "true")
                                .html(dict_Search["category"]) //dict_Search["busy"])
                        ).append(
                            $("<option>")
                                .attr("value", "books")
                                .html(dict_NavBar["books"])
                        ).append(
                            $("<option>")
                                .attr("value", "events")
                                .html(dict_NavBar["events"])
                        ).append(
                            $("<option>")
                                .attr("value", "peoples")
                                .html(dict_NavBar["peoples"])
                        ).append(
                            $("<option>")
                                .attr("value", "locations")
                                .html(dict_NavBar["locations"])
                        ).append(
                            $("<option>")
                                .attr("value", "specials")
                                .html(dict_NavBar["specials"])
                        ).append(
                            $("<option>")
                                .attr("value", "all")
                                .html(dict_Search["all"])
                        )
                )
            )
        ).append(
            // This is where the items will be displayed
            $("<div/>")
                    .addClass("contents_right col-md-9 px-0")
                    .attr("id", "search_results")
        );

        // Change the title text of the select element
//        $("#default").html(dict_Search["category"]);
//        $("#table").attr("disabled", "false");
    
        // Set back all the data that was entered for searching
        if (get_settings.hasOwnProperty("submitSearch") && (get_settings["submitSearch"] !== null)) {
            
            var value = get_settings.hasOwnProperty("table") ? get_settings["table"] : null;
            $("#table").attr("value", value).change();
        }

        onSearch();
    }
</script>