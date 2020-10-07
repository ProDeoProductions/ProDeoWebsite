<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "search";
    require 'layout/template.php'; 
?>

<script>
    function onLoadSearch() {
        
        // Change the title text of the select element
//        $("#default").html(dict_Search["Category"]);
//        $("#table").attr("", "");
    
//    // Set back all the data that was entered for searching
//    <?php // if ((filter_input(INPUT_GET, 'submitSearch') !== null)) { ?>
//        var SelectElement = document.getElementById("table");
//        SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'table'); ?>";
//        SelectElement.onchange();
//    <?php // } ?>
        
        // Actual content of the page itself 
        // This is defined in the corresponding php page
        $("#content").append(
            $("<div/>").addClass("contents_left col-md-3 px-0").attr("id", "search_bar").append(
                $("<h1/>").html(dict_Search["Options"])
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
                                .html(dict_Search["Category"]) //dict_Search["busy"])
                        ).append(
                            $("<option>")
                                .attr("value", "books")
                                .html(dict_NavBar["Books"])
                        ).append(
                            $("<option>")
                                .attr("value", "events")
                                .html(dict_NavBar["Events"])
                        ).append(
                            $("<option>")
                                .attr("value", "peoples")
                                .html(dict_NavBar["Peoples"])
                        ).append(
                            $("<option>")
                                .attr("value", "locations")
                                .html(dict_NavBar["Locations"])
                        ).append(
                            $("<option>")
                                .attr("value", "specials")
                                .html(dict_NavBar["Specials"])
                        ).append(
                            $("<option>")
                                .attr("value", "all")
                                .html(dict_Search["All"])
                        )
                )
            )
        ).append(
            // This is where the items will be displayed
            $("<div/>").addClass("contents_right col-md-9 px-0").attr("id", "search_results")
                // When no search is performed yet
                .html(dict_Search["default_search"])
        );

        onSearch();
    }
    
    // The function that is executed, when the select box for the type of item has changed values
    function selectTableOptions(sel) {
        // Get the selected values
        var value = sel[0].options[sel[0].selectedIndex].value;
        var form = sel[0].parentElement;

        // Remove all existing options and start fresh
        $(".added").remove();
        $(".added_app_div").remove();

        $(form).append(addInput("search", dict_PeoplesParams["name"], value));

        switch(value) {
            case "peoples":
                $(form).append(    
                    // Meaning Name
                    addInput("meaning_name", dict_PeoplesParams["meaning_name"], value)
                ).append(
                    // TODO: Linking tables
                    addInput("name_changes", dict_Links["a.k.a"], value)
                ).append(
                    // Name Father
                    addInput("father", dict_PeoplesParams["father_id"], value)
                ).append(
                    // Name Mother
                    addInput("mother", dict_PeoplesParams["mother_id"], value)
                ).append(
                    // Gender
//                    $("<input/>")
//                        .attr("type", "text")
//                        .attr("name", "mother")
//                        .attr("placeholder", dict_PeoplesParams["mother_id"])
//                        .attr("value", get_settings.hasOwnProperty("mother") && get_settings["table"] === "peoples" ? 
//                                            get_settings["mother"] : 
//                                            "")
//                        .addClass("added")
                );
//            // Gender
//            Input = addSelect("gender", 
//                                [<?php // echo $gender_select_values; ?>], 
//                                [<?php // echo $gender_select_names; ?>], 
//                                "<?php // echo $dict_PeoplesParams["gender"]; ?>");
//            <?php // if ((filter_input(INPUT_GET, 'gender') !== null) and (filter_input(INPUT_GET, 'table') == "peoples")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'gender');?>";
//            <?php // } ?>
//            form.appendChild(Input);
//
//            // Tribe
//            Input = addSelect("tribe", 
//                                [<?php // echo $tribe_select_values; ?>], 
//                                [<?php // echo $tribe_select_names; ?>], 
//                                "<?php // echo $dict_PeoplesParams["tribe"]; ?>");
//            <?php // if ((filter_input(INPUT_GET, 'tribe') !== null) and (filter_input(INPUT_GET, 'table') == "peoples")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'tribe');?>";
//            <?php // } ?>
//            form.appendChild(Input);
//
//            // First appearance
//            Input = addAppearance("book_start", "<?php // echo $dict_PeoplesParams["book_start_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, 'table') == "peoples")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, 'table') == "peoples")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_chap');?>";
//            <?php // } ?>
//
//            // Last appearance
//            Input = addAppearance("book_end", "<?php // echo $dict_PeoplesParams["book_end_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, 'table') == "peoples")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, 'table') == "peoples")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_chap');?>";
//            <?php // } ?>
            break;

            case "locations":
//            // Meaning name
                    addInput("meaning_name", dict_LocationsParams["meaning_name"], value)

//            // TODO: Linking tables
                    addInput("name_changes", dict_Links["a.k.a"], value)
//
//            // Type of Location
//            Input = addSelect("type", 
//                                [<?php // echo $locations_select_values; ?>], 
//                                [<?php // echo $locations_select_names; ?>], 
//                                "<?php // echo $dict_LocationsParams["type"]; ?>");
//            <?php // if ((filter_input(INPUT_GET, 'type') !== null) and (filter_input(INPUT_GET, 'table') == "locations")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'type');?>";
//            <?php // } ?>
//            form.appendChild(Input);
//
//            // TODO: Linking tables
//            // Founder
                    addInput("founder", dict_Links["to_people"], value)
//
//            // Destroyer
                    addInput("founder", dict_Links["to_people"], value)
//
//            // First appearance
//            Input = addAppearance("book_start", "<?php // echo $dict_LocationsParams["book_start_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, 'table') == "locations")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, 'table') == "locations")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_chap');?>";
//            <?php // } ?>
//
//            // Last appearance
//            Input = addAppearance("book_end", "<?php // echo $dict_LocationsParams["book_end_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, 'table') == "locations")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, 'table') == "locations")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_chap');?>";
//            <?php // } ?>
            break;

            case "specials":
//            // Meaning Name
                    addInput("meaning_name", $dict_SpecialsParams["meaning_name"], value)
//
//            // Type of Special
//            Input = addSelect("type", 
//                                [<?php // echo $specials_select_values; ?>], 
//                                [<?php // echo $specials_select_names; ?>], 
//                                "<?php // echo $dict_SpecialsParams["type"]; ?>");
//            <?php // if ((filter_input(INPUT_GET, 'type') !== null) and (filter_input(INPUT_GET, 'table') == "specials")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'type');?>";
//            <?php // } ?>
//            form.appendChild(Input);
//
//            // First appearance
//            Input = addAppearance("book_start", "<?php // echo $dict_SpecialsParams["book_start_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, 'table') == "specials")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, 'table') == "specials")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_chap');?>";
//            <?php // } ?>
//
//            // Last appearance
//            Input = addAppearance("book_end", "<?php // echo $dict_SpecialsParams["book_end_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, 'table') == "specials")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, 'table') == "specials")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_chap');?>";
//            <?php // } ?>
            break;

            case "events":
//            // TODO: Linking tables
//            // Previous
//    //        Input = addInput("text", "Previous", "<?php // echo $dict_EventsParams["Previous"]; ?>");
//            <?php // if (isset(filter_input(INPUT_GET, 'Previous')) and (filter_input(INPUT_GET, 'table') == "events")) { ?>
//                 Pre-fill this property when it is set,
//                 and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'Previous');?>";
//            <?php // } ?>
//    //        form.appendChild(Input);
//
//            // Location
//    //        Input = addInput("text", "Locations", "<?php // echo $dict_EventsParams["Locations"]; ?>");
//            <?php // if (isset(filter_input(INPUT_GET, 'Locations')) and (filter_input(INPUT_GET, 'table') == "events")) { ?>
//                 Pre-fill this property when it is set,
//                 and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'Locations');?>";
//            <?php // } ?>
//    //        form.appendChild(Input);
//
//            // People
//    //        Input = addInput("text", "Peoples", "<?php // echo $dict_EventsParams["Peoples"]; ?>");
//            <?php // if (isset(filter_input(INPUT_GET, 'Peoples')) and (filter_input(INPUT_GET, 'table') == "events")) { ?>
//                 Pre-fill this property when it is set,
//                 and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'Peoples');?>";
//            <?php // } ?>
//    //        form.appendChild(Input);
//
//            // Special
//    //        Input = addInput("text", "Specials", "<?php // echo $dict_EventsParams["Specials"]; ?>");
//            <?php // if (isset(filter_input(INPUT_GET, 'Specials')) and (filter_input(INPUT_GET, 'table') == "events")) { ?>
//                 Pre-fill this property when it is set,
//                 and when the table is the same of for the previous search
//                Input.value = "<?php // echo filter_input(INPUT_GET, 'Specials');?>";
//            <?php // } ?>
//    //        form.appendChild(Input);
//
//            // First appearance
//            Input = addAppearance("book_start", "<?php // echo $dict_EventsParams["book_start_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, 'table') == "events")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, 'table') == "events")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_start_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_start_chap');?>";
//            <?php // } ?>
//
//            // Last appearance
//            Input = addAppearance("book_end", "<?php // echo $dict_EventsParams["book_end_vers"]; ?>");
//            form.appendChild(Input);
//            <?php // if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, 'table') == "events")) { ?>
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_book");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_book');?>";
//                SelectElement.onchange();
//            <?php // } 
//            if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, 'table') == "events")) { ?>////
//                // Pre-fill this property when it is set,
//                // and when the table is the same of for the previous search
//                SelectElement = document.getElementById("book_end_chap");
//                SelectElement.value = "<?php // echo filter_input(INPUT_GET, 'book_end_chap');?>";
//            <?php // } ?>
            break;
        }
//
//        // The button to start the actual search
//        var SubmitButton = document.createElement("input");
//        SubmitButton.id = "submit";
//        SubmitButton.name = "submitSearch";
//        SubmitButton.type = "submit";
//        SubmitButton.className = "added";
//        SubmitButton.value = "<?php // echo $dict_Search["Search"]; ?>";
//        form.appendChild(SubmitButton);
//
//        <?php // if ((filter_input(INPUT_GET, 'submitSearch') !== null)) { ?>
//            // Function to remove all selected search options
//            var RemoveOptions = document.createElement("a");
//            RemoveOptions.innerHTML = "<?php // echo $dict_Search["Remove"];?>";
//            RemoveOptions.href = "search.php";
//            RemoveOptions.className = "added";
//            // No border please..
//            RemoveOptions.style.borderWidth = "0px";
//            form.appendChild(RemoveOptions);
        <?php // } ?>
    }
    
    function onSearch() {
        //    if (filter_input(INPUT_GET, 'submitSearch') !== null) {
//        // Generating search results
//        $options = "";
//
//        if ((filter_input(INPUT_GET, 'meaning_name') !== null) and (filter_input(INPUT_GET, "meaning_name") != "")) {
//            $options = $options." AND meaning_name LIKE '%".filter_input(INPUT_GET, "meaning_name")."%'";
//        }
//
//        // TODO: Linking tables
////        if (isset(filter_input(INPUT_GET, 'NameChanges']) and (filter_input(INPUT_GET, "NameChanges"] != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "NameChanges"]);
////            foreach ($multoptions as $value) {
////                $options = $options." AND NameChanges LIKE '%".$value."%'";
////            }
////        }
////
////        if (isset(filter_input(INPUT_GET, 'Father']) and (filter_input(INPUT_GET, "Father"] != "")) {
////            $options = $options." AND Father LIKE '%".filter_input(INPUT_GET, "Father"]."%'";
////        }
////
////        if (isset(filter_input(INPUT_GET, 'Mother']) and (filter_input(INPUT_GET, "Mother"] != "")) {
////            $options = $options." AND Mother LIKE '%".filter_input(INPUT_GET, "Mother"]."%'";
////        }
//
//        if ((filter_input(INPUT_GET, 'gender') !== null) and (filter_input(INPUT_GET, "gender") != "")) {
//            if (filter_input(INPUT_GET, "gender") != 0) {
//                $options = $options." AND gender = '%".filter_input(INPUT_GET, "gender")."%'";
//            }
//        }
//
//        if ((filter_input(INPUT_GET, 'tribe') !== null) and (filter_input(INPUT_GET, "tribe") != "")) {
//            if (filter_input(INPUT_GET, "tribe") != 0) {
//                $options = $options." AND tribe = '%".filter_input(INPUT_GET, "tribe")."%'";
//            }
//        }
//
//        if ((filter_input(INPUT_GET, 'type') !== null) and (filter_input(INPUT_GET, "type") != "")) {
//            if (filter_input(INPUT_GET, "type") != 0) {
//                $options = $options." AND type = '%".filter_input(INPUT_GET, "type")."%'";
//            }
//        }
//
//        // TODO: Linking yable
////        if (isset(filter_input(INPUT_GET, 'Founder')) and (filter_input(INPUT_GET, "Founder") != "")) {
////            $options = $options." AND Founder LIKE '%".filter_input(INPUT_GET, "Founder")."%'";
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Destroyer')) and (filter_input(INPUT_GET, "Destroyer") != "")) {
////            $options = $options." AND Destroyer LIKE '%".filter_input(INPUT_GET, "Destroyer")."%'";
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Previous')) and (filter_input(INPUT_GET, "Previous") != "")) {
////            $options = $options." AND Previous LIKE '%".filter_input(INPUT_GET, "Previous")."%'";
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Locations')) and (filter_input(INPUT_GET, "Locations") != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "Locations"));
////            foreach ($multoptions as $value) {
////                $options = $options." AND Locations LIKE '%".$value."%'";
////            }
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Peoples')) and (filter_input(INPUT_GET, "Peoples") != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "Peoples"));
////            foreach ($multoptions as $value) {
////                $options = $options." AND Peoples LIKE '%".$value."%'";
////            }
////        }
////        
////        if (isset(filter_input(INPUT_GET, 'Specials')) and (filter_input(INPUT_GET, "Specials") != "")) {
////            $multoptions = explode(";", filter_input(INPUT_GET, "Specials"));
////            foreach ($multoptions as $value) {
////                $options = $options." AND Specials LIKE '%".$value."%'";
////            }
////        }
//        
//        if ((filter_input(INPUT_GET, 'book_start_book') !== null) and (filter_input(INPUT_GET, "book_start_book") != "")) {
//            $options = $options." AND book_start_id >= '".filter_input(INPUT_GET, "book_start_book")."'";
//            
//            if ((filter_input(INPUT_GET, 'book_start_chap') !== null) and (filter_input(INPUT_GET, "book_start_chap") != "")) {
//                $options = $options." AND book_start_chap >= '".filter_input(INPUT_GET, "book_start_chap")."'";
//            }
//        }
//        
//        if ((filter_input(INPUT_GET, 'book_end_book') !== null) and (filter_input(INPUT_GET, "book_end_book") != "")) {
//            $options = $options." AND book_end_id >= '".filter_input(INPUT_GET, "book_end_book")."'";
//            
//            if ((filter_input(INPUT_GET, 'book_end_chap') !== null) and (filter_input(INPUT_GET, "book_end_chap") != "")) {
//                $options = $options." AND book_end_chap >= '".filter_input(INPUT_GET, "book_end_chap")."'";
//            }
//        }
//        
//        // If all types are chosen, make some shortcuts at the top of the search results
//        if (filter_input(INPUT_GET, 'table') == "all") {
//            PrettyPrint('        <center> ');
//            PrettyPrint('            '.$dict_Search["Show"].
//                                    "<a href='#search_peoples'>".$dict_NavBar["Peoples"]."</a> | ".
//                                    "<a href='#search_locations'>".$dict_NavBar["Locations"]."</a> | ".
//                                    "<a href='#search_specials'>".$dict_NavBar["Specials"]."</a> | ".
//                                    "<a href='#search_books'>".$dict_NavBar["Books"]."</a> | ".
//                                    "<a href='#search_events'>".$dict_NavBar["Events"]."</a>");
//            PrettyPrint('        </center> ');
//            PrettyPrint('');
//        }
//    
//        if ((filter_input(INPUT_GET, 'table') == "peoples") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_peoples"> ');
//            // Search Peoples database
//            SearchItems(filter_input(INPUT_GET, 'search'), "peoples", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "locations") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_locations"> ');
//            // Search Locations database
//            SearchItems(filter_input(INPUT_GET, 'search'), "locations", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "specials") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_specials"> ');
//            // Search Specials database
//            SearchItems(filter_input(INPUT_GET, 'search'), "specials", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "books") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_books"> ');
//            // Search Books database
//            SearchItems(filter_input(INPUT_GET, 'search'), "books", $options);
//            PrettyPrint('        </div> ');
//        }
//
//        if ((filter_input(INPUT_GET, 'table') == "events") ||
//            (filter_input(INPUT_GET, 'table') == "all")) {
//            PrettyPrint('        <div id="search_events"> ');
//            // Search Events database
//            SearchItems(filter_input(INPUT_GET, 'search'), "events", $options);
//            PrettyPrint('        </div> ');
//        }
//    }
//    PrettyPrint('    </div> ');
//    PrettyPrint('</div> ');
    }
    
    // Function to speed up the process of adding Form elements
    function addInput(name, placeholder, table) {
        return $("<input/>")
                    .attr("type", "text")
                    .attr("name", name)
                    .attr("placeholder", placeholder)
                    .attr("value", get_settings.hasOwnProperty(name) && get_settings["table"] === table ? 
                                        get_settings[name] : 
                                        "")
                    .addClass("added")
    }

    // Function to speed up the process of adding Form elements
    function addSelect(name, values, strings, placeholder) {
        var element = $("<select/>")
                .attr("name", name)
                .addClass("added")
                .append(
                    $("<option/>")
                        .attr("value", "")
                        .attr("disabled", "true")
                        .attr("selected", "true")
                        .html(dict_Settings["default"] + " voor " + placeholder)
                );

        for (var i = 0; i < values.length; i++) {
            element.append($("<option/>").attr("value", values[i]).html(strings[i]));
        }

        return element;
    }

//function addAppearance(name, placeholder) {
//    // The entire div that contains the drop downs and text bar
//    // These are used to create the number that corresponds to
//    // a bible verse
//    var appearance = document.createElement("div");
//    appearance.className = "added_app_div";
//    
//    // The title, to let the user know whether
//    // it's the first or last appearance we are
//    // filling in
//    var elementTitle = document.createElement("p");
//    elementTitle.innerHTML = placeholder;
//    elementTitle.className = "added_app_text";
//    appearance.appendChild(elementTitle);
//    
//    // The dropdown list containing all the books
//    var elementBook = document.createElement("select");
//    elementBook.name = name + "_book";
//    elementBook.id = name + "_book";
//    elementBook.app = name;
//    elementBook.setAttribute("onchange", "selectBookOptions(this)");
//    elementBook.className = "added_app_select";
//    
//    // Bible book that can be chosen
//    var option = document.createElement("option");
//    option.value = "";
//    option.disabled = "true";
//    option.selected = "true";
//    option.innerHTML = "<?php // echo $dict_Search["bible_book"]; ?>";
//    elementBook.appendChild(option);
//    
//    // List of options from the database, to get names in correct language.
//    <?php 
//        $listLength = GetNumberOfItems("books"); 
//        
//        // The different numbers
//        echo "var values = [";
//        for ($id = 0; $id < $listLength; $id++) {
//            echo $id.", ";
//        }
//        echo "];\r\n";
//        
//        // The different names
//        echo "var strings = [";
//        for ($id = 0; $id < $listLength; $id++) {
//            $item = GetItemInfo("books", $id);
//            echo "'".$item['name']."', ";
//        }
//        echo "];\r\n";
//        
//        // The different amount of chapters
//        echo "var chapters = [";
//        for ($id = 0; $id < $listLength; $id++) {
//            $item = GetItemInfo("books", $id);
//            echo $item['num_chapters'].", ";
//        }
//        echo "];\r\n";
    
    ?>//
//    
//    // Creating the dropdown list
//    for (var i = 0; i < values.length; i++) {
//        var option = document.createElement("option");
//        option.value = values[i];
//        option.chapters = chapters[i];
//        option.innerHTML = strings[i];
//        
//        elementBook.appendChild(option);
//    }
//    appearance.appendChild(elementBook);
//    
//    
//    // The dropdown list containing all the chapters of a book
//    var elementChap = document.createElement("select");
//    elementChap.name = name + "_chap";
//    elementChap.id = name + "_chap";
//    elementChap.className = "added_app_select";
//    
//    // Chapter that can be chosen (currently no available options
//    // Will be filled in when a book is chosen
//    var option = document.createElement("option");
//    option.value = "";
//    option.disabled = "true";
//    option.selected = "true";
//    option.innerHTML = "<?php // echo $dict_Search["bible_chap"]; ?>";
//    elementChap.appendChild(option);
//    
//    // The div for the dropdown menus
//    appearance.appendChild(elementChap);
//    
//    return appearance;
//}
//
// This function is executed when the book for first/last appearance has changed
// It updates the dropdown with the number of chapters
//function selectBookOptions(sel) {
    
//    // Which dropdown are we currently accessing?
//    var name = sel.app;
//    
//    // Which book has been chosen?
//    var Option = sel.options[sel.selectedIndex];
//    
//    // How many chapters does this book have?
//    var Chapters = Option.chapters;
//    
//    // Remove the old chapters of the dropdown menu
//    ChapDropDown = document.getElementById(name + "_chap");
//    numChapsPrev = ChapDropDown.childElementCount;
//    
//    for (var i = 1; i < numChapsPrev; i++) {
//        ChapDropDown.removeChild(ChapDropDown.lastChild);
//    }
//    
//    // Creating the dropdown list
//    for (var i = 0; i < Chapters; i++) {
//        var option = document.createElement("option");
//        option.value = i + 1;
//        option.innerHTML = i + 1;
//        
//        ChapDropDown.appendChild(option);
//    }
//}
</script>

<?php 
//    // This is for all the select elements
//    // Made easy, and manageble in one single place
//    $arrays = [
//            "tribe" => $select_Search_tribes, 
//            "gender" => $select_Search_gender, 
//            "locations" => $select_Search_locations,
//            "specials" => $select_Search_specials,
//    ];
//    
//    foreach($arrays as $name=>$array){
//        // Making the enumeration and the naming in these strings
//        ${$name."_select_values"} = "0";
//        ${$name."_select_names"} = "'".$dict_Search["all"]."'";
//        $loopIdx = 0;
//        
//        foreach($array as $key=>$value) {
//            $loopIdx = $loopIdx + 1;
//            ${$name."_select_values"} = ${$name."_select_values"}.", ".$loopIdx;
//            ${$name."_select_names"} = ${$name."_select_names"}.", '".$value."'";
//        }
//    }

// The function that executes the search, and returns the results
//function SearchItems($text, $table, $options) {
//    global $dict_Search;
//    global $dict_NavBar;
//    global $conn;
//    
//    // The the desired parameters dictionary, depending on the type of search
//    $dictName = "dict_".ucfirst($table)."Params";
//    global $$dictName;
//    $dict = $$dictName;
//    
//    // Remove any newlines or characters
//    $text_escaped = $conn->real_escape_string($text);
//    
//    // Search the database with the chosen string and options
//    $sql = "SELECT * FROM ".$table." WHERE name LIKE '%".$text_escaped."%'".$options;
//    $result = $conn->query($sql);
//    
//    if (!$result) {
//        // If there are no results, show a message
//        PrettyPrint('            '.$dict_Search["NoResults"]."<br />");
//    }
//    else {
//        // If there are results..
//        $num_res = $result->num_rows;
//        
//        // Type of search performed
//        // Show the amount of results found. If it is more than one result, use plural forms
//        PrettyPrint("            <a name='".$table."'><h1>".$dict_NavBar[ucfirst($table)].":</h1><br /></a>");
//        if ($num_res == 1) {
//            PrettyPrint('            '.$num_res.$dict_Search['Result']."\"".$text_escaped."\":<br />");
//        } else {
//            PrettyPrint('            '.$num_res.$dict_Search['Results']."\"".$text_escaped."\":<br />");
//        }
//        
//        // If there are results, draw a table with all the results found
//        if ($num_res > 0) {
//            PrettyPrint("            <table>");
//            if (in_array($table, Array("peoples", "locations", "specials", "events"))) {
//                PrettyPrint("                <tr>");
//                PrettyPrint("                    <td>");
//                PrettyPrint('                        '.$dict['name']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                    <td>");
//                PrettyPrint('                        '.$dict['book_start_vers']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                    <td>");
//                PrettyPrint("                        ".$dict['book_end_vers']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                </tr>");
//            } else {
//                PrettyPrint("                <tr>");
//                PrettyPrint("                    <td>");
//                PrettyPrint("                        ".$dict['name']);
//                PrettyPrint("                    </td>");
//                PrettyPrint("                </tr>");
//            }
//            
//            while ($item = $result->fetch_array()) {
//                PrettyPrint("                <tr>");
//                PrettyPrint("                    <td>");
//                PrettyPrint("                        <a href='".$table.".php".AddParams(-1, $item[substr($table, 0, -1).'_id'], -2)."'>".$item['name']."</a>");
//                PrettyPrint("                    </td>");
//                
//                if (in_array($table, Array("peoples", "locations", "specials", "events"))) {
//                    PrettyPrint("                    <td>");
//                    PrettyPrint("                        ".convertBibleVerseText($item['book_start_id'], $item['book_start_chap'], $item['book_start_vers']));
//                    PrettyPrint("                    </td>");
//                    PrettyPrint("                    <td>");
//                    PrettyPrint("                        ".convertBibleVerseText($item['book_end_id'], $item['book_end_chap'], $item['book_end_vers']));
//                    PrettyPrint("                    </td>");
//                }
//                
//                PrettyPrint("                </tr>");
//            }
//            PrettyPrint("            </table>");
//        }
//    }
//}

?>

<script>






</script>