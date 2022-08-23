<!DOCTYPE html>
<html>   
<?php 
    if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    require 'src/tools/base.php';

    $dropdown = "";
    // Get the dropdown menu that needs to have it's button activated
    if (in_array($id, ['books', 'events', 'peoples', 'locations', 'specials', 'search'])) {
        $dropdown = "database";
    }
    
    // The theme that is used for this page
    switch($id) {
        case 'home':
        case 'search':
        case 'settings':
            $theme = "purple";
            break;
        
        case 'books':
        case 'aboutus':
            $theme = "pink";
            break;
        
        case 'events':
        case 'timeline':
            $theme = "orange";
            break;
        
        case 'peoples':
        case 'familytree':
            $theme = "red";
            break;
        
        case 'locations':
        case 'worldmap':
            $theme = "blue";
            break;
        
        case 'specials':
        case 'contact':
            $theme = "green";
            break;
        
        default:
            $theme = "purple";
            break;
    }
?>
    
    
    <head>
        <!-- Name shown on the tab -->
        <title><?php echo $dict["globals.prodeo_database"] ?></title>
        
        <!-- Some extra information used for viewing -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- The style sheets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="/css/theme_<?php echo $theme; ?>.css">
<?php if (in_array($id, ["search"])) { ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" type="text/css">
        <link rel="stylesheet" href="/css/slider_<?php echo $theme; ?>.css">
<?php } ?>
        <!-- Include stylesheet -->
        <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
      
        <!-- Fav icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="/../favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/../favicon-16x16.png">

        <!-- External Javascript files -->
<?php require "page/import.php"; ?>
        
        
        <script>

            window.onload = function() {
                // Set some default stuff
                <?php echo "onLoad".ucfirst($id)."()"; ?>;
            };
        </script>
    </head>
    
    <body class="d-flex flex-column min-vh-100">
<?php require "page/navigation.php"; ?>
        
        <!-- Actual content of the page itself 
            This is defined in the corresponding php page -->
        <div id="content" class="py-5" style="background-color: hsl(0, 100%, 99%)">
        </div>
        
<?php require "page/footer.php"; ?>
    </body>
</html>