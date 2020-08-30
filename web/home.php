<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "home";
    require "layout/template.php";
?>

<script>
    async function onLoadHome() {
        
        await getItemFromDatabase("blog").then(function(blogs) {
            
            // Put the table in the div
            var blogTable = $("<table></table>").appendTo("#content");
            
            for (var blogIdx in blogs) {
                var blogObject = blogs[blogIdx];
                
                // Add a table row to the table
                // Add table data to the row
                // The blog will be inserted in this element
                var TableData = $("<td></td>")
                                    .appendTo($("<tr></tr>")
                                        .appendTo(blogTable));
                
                // Title of the blog
                $("<h1></h1>")
                        .appendTo(TableData)        // Parent
                        .html(blogObject["title"]); // Text
                
                // Text of the blog
                var blogText = $("<pre></pre>")
                        .appendTo(TableData)                    // Parent
                        .attr("id", "blog" + blogObject["id"])  // ID
                        .addClass("blog_pre")                   // Class
                        .html(blogObject["text"]);              // Text
                
                if (blogText.outerHeight() > 75) {
                    // If the length of this blog text exceeds the maximum allowed,
                    // which means that it has more than 5 lines of text, the lines
                    // after those 5 allowed lines will be hidden. To prevent
                    // a single blog to take too much space

                    // Add a link and some preview text with "click here"
                    // If the link is clicked, the function _expandBlog will be executed.
                    // This function will show the rest of the blog that is currently
                    // hidden by default.
                    $("<a></a>")
                            .appendTo(TableData)
                            .attr("id", "link" + blogText.attr("id"))
                            .attr("href", "javascript:_expandBlog('" + blogText.attr("id") + "')")
                            .addClass("blog_link")
                            .html(dict_Home["link_blog"] + "...");
                }
                
                // The blog date and user
                $("<p></p>")
                        .appendTo(TableData)
                        .addClass("blog_date")
                        .html([blogObject["date"], 
                               dict_Home['user_blog'], 
                               blogObject["user"]].join(" "));
            }

        }, console.log);
    }
</script>