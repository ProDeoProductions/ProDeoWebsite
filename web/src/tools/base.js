/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global get_settings, dict */

function setParameters(url) {
    var newUrl = url;
    
    // Get languague from window.location.url
    if (get_settings.hasOwnProperty("lang") && get_settings["lang"]) {
        newUrl = "/" + get_settings["lang"] + (url[0] === "/" ? "" : "/") + url;
    }
    
    return newUrl;
}

function setLanguage(language, base, uri) {    
    // Reload the page in the correct language
    // Check whether the URI already starts with a language
    if (get_settings.hasOwnProperty("lang") && get_settings["lang"]) {
        // The URI already starts with a language
        // Remove the language part
        uri = uri.substr(3);
    }
        
    // And update the language
    get_settings["lang"] = language;
    
    // No reload the page
    window.location.href = base + setParameters(uri);
}

function toUpperCaseFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function getLinkToItem(type, id, text, classes="", panTo="") {
    // If any other classes are inserted
    if (typeof classes === "undefined" || classes === "") {
        classes = "font-weight-bold";
    }
    
    var to_table = type;
    var to_item = to_table.substr(0, to_table.length - 1);
    if (["familytree", "timeline"].includes(type)) {
        to_item = "map";
    }
    
    var link = setParameters(to_table + (id !== "-1" ? ("/" + to_item + "/" + id) : ""));
    if (text === "self") {
        text = link.substr(get_settings["lang"] ? 4 : 1);
    }
    if (text === "Global") {
        text = dict["timeline.global"];
    }
    
    if (id === null) {
        link = '#';
    }
    
    if (panTo !== "") {
        link += '?panTo=' + panTo;
    }
    
    if ((type === "worldmap") && id !== "-1") {
        // Use a function to link to the item
        return '<a href="javascript: void(0)" onclick="getLinkToMap(' + id + ')"' + 
            'class="' + classes + '">' + 
                text + 
        '</a>';        
    } else {
        // Use an actual hyhperlink to the item
        return '<a href="' + link + '" ' + 
            (type === "worldmap" ? 'data-toggle="tooltip" title="' + dict["items.details.worldmap"] + '"' : "") + 
            'class="' + classes + '">' + 
                text + 
        '</a>';
    }
}

function getGenderString(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["gender.unknown"];
            break;
            
        case 1:
        case "1":
            str = dict["gender.male"];
            break;
            
        case 2:
        case "2":
            str = dict["gender.female"];
            break;
    }
    
    return str;
}

function getGenderColor(int) {
    var color = "";
    
    switch(int) {
        case -1:
        case "-1":
        case 0:
        case "0":
            color = "lightgrey";
            break;
            
        case 1:
        case "1":
            color = "lightblue";
            break;
            
        case 2:
        case "2":
            color = "pink";
            break;
    }
    
    return color;
}

function getLengthString(int) {
    var str = "";
    
    // Different types of length
    switch(int) {
        case 0:
        case "0":
            // Around or less than an hour
            str = dict["length.hour"];;
            break;
            
        case 1:
        case "1":
            // Around or less than a day
            str = dict["length.day"];
            break;
            
        case 2:
        case "2":
            // Around or less than a week
            str = dict["length.week"];
            break
            
        case 3:
        case "3":
            // Around or less than a month
            str = dict["length.month"];
            break
            
        case 4:
        case "4":
            // Around or less than quarter of a year
            str = dict["length.quarter"];
            break
            
        case 5:
        case "5":
            // Around or less than half a year
            str = dict["length.half"];
            break
            
        case 6:
        case "6":
            // Around or less than a year
            str = dict["length.year"];
            break
            
        case 7:
        case "7":
            // Around or less than a decade
            str = dict["length.decade"];
            break
            
        case 8:
        case "8":
            // Around or less than a century
            str = dict["length.century"];
            break
            
        case 9:
        case "9":
            // Around or less than a millenium
            str = dict["length.millennium"];
            break
            
        case 10:
        case "10":
            // More than a millenium
            str = dict["length.more"];
            break
            
        case -1:
        case "-1":
        default:
            // unknown/Other
            str = dict["length.unknown"];
            break 
    }
    
    return str;
}

function getLengthColor(length) {
    var color = "";
    
    // Different types of length
    switch(length) {
        case 0:
        case "0":
            // Around or less than an hour
            color = "lightGreen";
            break;
            
        case 1:
        case "1":
            // Around or less than a day
            color = "lightCoral";
            break;
            
        case 2:
        case "2":
            // Around or less than a week
            color = "violet";
            break
            
        case 3:
        case "3":
            // Around or less than a month
            color = "lightSkyBlue";
            break
            
        case 4:
        case "4":
            // Around or less than quarter of a year
            color = "aquamarine";
            break
            
        case 5:
        case "5":
            // Around or less than half a year
            color = "lightSalmon";
            break
            
        case 6:
        case "6":
            // Around or less than a year
            color = "burlywood";
            break
            
        case 7:
        case "7":
            // Around or less than a decennium
            color = "orangeRed";
            break
            
        case 8:
        case "8":
            // Around or less than a century
            color = "yellowGreen";
            break
            
        case 9:
        case "9":
            // Around or less than a millenium
            color = "royalBlue";
            break
            
        case 10:
        case "10":
            // More than a millenium
            color = "seaShell";
            break
            
        case -1:
        case "-1":
        default:
            // unknown
            color = "lightgrey";
            break 
    }
    
    return color;
}

function getTribeString(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["tribe.ruben"];
            break;
            
        case 1:
        case "1":
            str = dict["tribe.simeon"];
            break;
            
        case 2:
        case "2":
            str = dict["tribe.levi"];
            break;
            
        case 3:
        case "3":
            str = dict["tribe.juda"];
            break;
            
        case 4:
        case "4":
            str = dict["tribe.dan"];
            break;
            
        case 5:
        case "5":
            str = dict["tribe.naftali"];
            break;
            
        case 6:
        case "6":
            str = dict["tribe.gad"];
            break;
            
        case 7:
        case "7":
            str = dict["tribe.aser"];
            break;
            
        case 8:
        case "8":
            str = dict["tribe.issachar"];
            break;
            
        case 9:
        case "9":
            str = dict["tribe.zebulon"];
            break;
            
        case 10:
        case "10":
            str = dict["tribe.jozef"];
            break;
            
        case 11:
        case "11":
            str = dict["tribe.benjamin"];
            break;
            
        case 12:
        case "12":
            str = dict["tribe.unknown"];
            break;
    }
    
    return str;
}

function getTypeLocationString(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["type.well"];
            break;
            
        case 1:
        case "1":
            str = dict["type.river"];
            break;
            
        case 2:
        case "2":
            str = dict["type.mountain"];
            break;
            
        case 3:
        case "3":
            str = dict["type.valley"];
            break;
            
        case 4:
        case "4":
            str = dict["type.country"];
            break;
            
        case 5:
        case "5":
            str = dict["type.district"];
            break;
            
        case 6:
        case "6":
            str = dict["type.county"];
            break;
            
        case 7:
        case "7":
            str = dict["type.city"];
            break;
            
        case 8:
        case "8":
            str = dict["type.object"];
            break;
            
        case 9:
        case "9":
            str = dict["type.unknown"];
            break;
    }
    
    return str;
}

function getTypeSpecialString(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = dict["type.object"];
            break;
            
        case 1:
        case "1":
            str = dict["type.idol"];
            break;
            
        case 2:
        case "2":
            str = dict["type.grave"];
            break;
            
        case 3:
        case "3":
            str = dict["type.song"];
            break;
            
        case 4:
        case "4":
            str = dict["type.building"];
            break;
            
        case 5:
        case "5":
            str = dict["type.creature"];
            break;
            
        case 6:
        case "6":
            str = dict["type.world"];
            break;
            
        case 7:
        case "7":
            str = dict["type.unknown"];
            break;
    }
    
    return str;
}

function getTypeLinkString(int) {
    var str = "";
    
    switch(int) {
        case 0:
        case "0":
            str = " (" + dict["type.birth"] + ")";
            break;
            
        case 1:
        case "1":
            str = " (" + dict["type.living"] + ")";
            break;
            
        case 2:
        case "2":
            str = " (" + dict["type.death"] + ")";
            break;
            
        case 3:
        case "3":
            str = " (" + dict["type.founder"] + ")";
            break;
            
        case 4:
        case "4":
            str = " (" + dict["type.destroyer"] + ")";
            break;
    }
    
    return str;
}


