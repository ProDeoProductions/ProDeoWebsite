<!DOCTYPE html>
<html>   
<?php 
    // This needs to be started at the very beginning
    session_start();
    
    // Initializing some variables to be used by the template
    require "src/template/init.php";
?>
    <head>
        <!-- Name shown on the tab -->
        <title><?= $dict["globals.prodeo_database"] ?></title>

        <!-- Some extra information used for viewing -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- External Javascript and script files -->
<?php require "src/template/imports.php"; ?>

    </head>
    
    <!-- A few global variables are stored in the body, to make sure it's
        available all throughout the code -->
    <body class="d-flex flex-column min-vh-100"
          data-base-url="<?= setParameters(""); ?>"
        <!-- Navigation bar on top of the page -->
<?php require "src/template/navigation.php"; ?>
    
        <!-- The content of this page, 
            This is done using templates and will not always have correct indentation or spacing -->
        <?php require "src/template/content.php"; ?>
    
        
        <!-- The footer of this page -->
<?php require "src/template/footer.php"; ?>
        
        <!-- Javascript for dynamic content 
            (content that changes while using this page) -->
<?php
    // Check if this file exists
    $page_script = "src/scripts/{$page_id}.php";
    if (is_file($page_script)) {
        // If this file exists, require it
        require $page_script; 
    }
?>
       
    </body>
</html>