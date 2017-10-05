<?php 

function GetListOfItems($table) {
	global $Search;
	global $conn;
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	if (!isset($_GET["sort"])) {
		$sort = "app";
	} else {
		$sort = $_GET["sort"];
	}
				
	switch($sort) {
		case 'alp':
		// Get new SQL array of items
		$sortBy = 'Name ASC';
		break;
		
		case 'r-alp':
		// Get new SQL array of items
		$sortBy = 'Name DESC';
		break;
		
		case 'r-app':
		// Get new SQL array of items
		$sortBy = 'ID DESC';
		break;
		
		default:
		// Get new SQL array of items
		$sortBy = 'ID ASC';
	}
	
	$sql = "SELECT ID,Name FROM ".$table." ORDER BY ".$sortBy." LIMIT ".($page_nr*100).",".(($page_nr+1)*100);
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Search["NoResults"]);
	}
	else {
		echo "<table>";
		while ($name = $result->fetch_array()) {
			echo "<tr>";
			echo "<td>";
			echo "<a href='".$table.".php".AddParams($page_nr, $name['ID'], $sort)."'>".$name['Name']."</a>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}

function GetNumberOfItems($table) {
	global $Search;
	global $conn;
	
	if (!isset($_GET["page"])) {
		$page_nr = 0;
	} else {
		$page_nr = $_GET["page"];
	}
	
	$sql = "SELECT ID,Name FROM ".$table." WHERE ID>=".($page_nr*100)." LIMIT 100";
	$result = $conn->query($sql);
	
	return $result->num_rows;
}

function GetItemInfo($table, $ID) {
	global $Search;
	global $conn;
	
	$sql = "SELECT * FROM ".$table." WHERE ID=".$ID;
	$result = $conn->query($sql);
	$item = NULL;
	
	if (!$result) {
		$Error = array("Name" => $Search["NoResults"]);
		return $Error;
	}
	else {
		$item = $result->fetch_assoc();
	}
	
	return $item;
}

function SearchItems($text, $table) {
	global $Search;
	global $NavBar;
	global $conn;
	
	$dictName = ucfirst($table)."Params";
	global $$dictName;
	$dict = $$dictName;
	
	$text = $conn->real_escape_string($text);
	
	$sql = "SELECT * FROM ".$table." WHERE name LIKE '%".$text."%'";
	$result = $conn->query($sql);
	
	if (!$result) {
		echo($Search["NoResults"]."<br />");
	}
	else {
		$num_res = $result->num_rows;
		
		echo "<a name='".$table."'><h1>".$NavBar[ucfirst($table)].":</h1><br /></a>";
		echo $num_res.$Search['Results']."\"".$text."\":<br />";
		
		if ($num_res > 0) {
			echo "<table>";
			if (in_array($table, Array("peoples", "locations", "specials"))) {
				echo "<tr>";
				echo "<td>";
					echo $dict['Name'];
				echo "</td>";
				echo "<td>";
					echo $dict['FirstAppearance'];
				echo "</td>";
				echo "<td>";
					echo $dict['LastAppearance'];
				echo "</td>";
				echo "</tr>";
			} else if ($table == "events") {
				echo "<tr>";
				echo "<td>";
					echo $dict['Name'];
				echo "</td>";
				echo "<td>";
					echo $dict['BibleVerses'];		
				echo "</td>";
				echo "</tr>";
			} else {
				echo "<tr>";
				echo "<td>";
					echo $dict['Name'];
				echo "</td>";
				echo "</tr>";
			}
			
			while ($item = $result->fetch_array()) {
				echo "<tr>";
					echo "<td>";
						echo "<a href='".$table.".php".AddParam(-1, $item['ID'], -2)."'>".$item['Name']."</a>";
					echo "</td>";
				
				if (in_array($table, Array("peoples", "locations", "specials"))) {
					echo "<td>";
						echo $item['FirstAppearance'];
					echo "</td>";
					echo "<td>";
						echo $item['LastAppearance'];
					echo "</td>";
				echo "</tr>";
				} else if ($table == "events") {
					echo "<td>";
						echo $item['BibleVerses'];		
					echo "</td>";
				}
				
				echo "</tr>";
			}
			echo "</table>";
		}
	}
}

if (isset($item_type)) { ?>

<script>

	function CheckButtons() {
		var ButtonPrev = document.getElementById("button_left");
		var ButtonNext = document.getElementById("button_right");
		
		// Check if this is page 0. If so, disable to prev button..	
		<?php			
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			if (!isset($_GET["sort"])) {
				$sort = 'app';
			} else {
				$sort = $_GET["sort"];
			}
			
			echo "var PageNr = ".$page_nr.";";
			echo "var NrOfItems = ".GetNumberOfItems($item_type).";";
			echo "var SortType = '".$sort."';";
		?>
		
		if (PageNr == 0) {
			ButtonPrev.disabled = true;
		} else {
			ButtonPrev.disabled = false;
		}
		if (NrOfItems < 100) {
			ButtonNext.disabled = true;
		} else {
			ButtonNext.disabled = false;
		}

		// Set the height of the left div, to the height of the right div
		var ContentsR = document.getElementsByClassName("contents_right")[0];
		var ContentsL = document.getElementsByClassName("contents_left")[0];
		
		ContentsL.setAttribute("style", "height: " + ContentsR.offsetHeight + "px");
	}
	
	function PrevPage() {		
		<?php
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			echo "var PageNr = ".$page_nr.";";
		?>
		
		if (PageNr == 1) {
			// The page parameter should now be removed
			oldHref = window.location.href;
			newHref = removeURLParameter(oldHref, "page");
			window.location.href = newHref;
		} else if (PageNr > 1) {
			// The page parameter only has to be updated
			oldHref = window.location.href;
			newHref = updateURLParameter(oldHref, "page", PageNr - 1);
			window.location.href = newHref;
		}
	}
	
	function NextPage() {
		<?php
			if (!isset($_GET["page"])) {
				$page_nr = 0;
			} else {
				$page_nr = $_GET["page"];
			}
			
			echo "var PageNr = ".$page_nr." + 1;";
		?>
		
		oldHref = window.location.href;
		newHref = updateURLParameter(oldHref, "page", PageNr);
		window.location.href = newHref;
	}
	
	function SortOnAlphabet() {		
		Button = document.getElementById("button_alp");
	
		// The sort parameter only has to be updated
		oldHref = window.location.href;
		<?php if (isset($_GET["sort"]) && ($_GET["sort"]) == "alp") { ?>
			newHref = updateURLParameter(oldHref, "sort", "r-alp");
			Button.innerHTML = "A-Z";
		<?php } else { ?>
			newHref = updateURLParameter(oldHref, "sort", "alp");
			Button.innerHTML = "Z-A";
		<?php } ?>
		
		newHref = removeURLParameter(newHref, "page");
		window.location.href = newHref;
				
		return;
	}
	
	function SortOnAppearance() {
		Button = document.getElementById("button_app");
	
		// The sort parameter only has to be updated
		oldHref = window.location.href;
		<?php if (isset($_GET["sort"]) && ($_GET["sort"]) == "r-app") { ?>
			newHref = removeURLParameter(oldHref, "sort");
			Button.innerHTML = "Gen-Op";
		<?php } else { ?>
			newHref = updateURLParameter(oldHref, "sort", "r-app");
			Button.innerHTML = "Op-Gen";
		<?php } ?>
		
		newHref = removeURLParameter(newHref, "page");
		window.location.href = newHref;
				
		return;
	}
</script>

<?php } ?>