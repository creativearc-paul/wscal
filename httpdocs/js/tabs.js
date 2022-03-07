$(document).ready(function() {

    /*
    *  tabs
    */
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab
    $(".tab_content:first").show(); //Show first tab content

    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content
        var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
        $(activeTab).fadeIn("fast"); //Fade in the active ID content
        return false;
    });  

    $("a.switchTab").click(function() {
        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content
        $(activeTab).fadeIn("fast"); //Fade in the active ID content
        return false;
    });
    
});