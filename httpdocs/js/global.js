$(document).ready(function() {

	/* 
	* attach a function to open all links with a class of "external" in a new window 
	* this avoids the use of target="_blank" and allows us to validate as XHTML strict
	*/
	$("a[rel^='external']").click( function() {
		window.open(this.href);
		return false;
	});


	/*
	* prevent ie from flickering with css backgrounds
	*/
	try {
		document.execCommand('BackgroundImageCache', false, true);
	} catch(e) {} 

	/*
	* default search text
	*/
	$("#searchBox").focus(function(){
		if ($(this).val() == $(this)[0].title){
			$(this).val("");
		}
	});

	$("#searchBox").blur(function(){
		if ($(this).val() == ""){
			$(this).val($(this)[0].title);
		}
	});

	$("#searchBox").blur();

	/*
	* default search text (Shopify)
	*/
	$("#q").focus(function(){
		if ($(this).val() == $(this)[0].title){
			$(this).val("");
		}
	});

	$("#q").blur(function(){
		if ($(this).val() == ""){
			$(this).val($(this)[0].title);
		}
	});

	$("#q").blur();
	
	/*
	* default search text
	*/
	$("#resourceSearchBox").focus(function(){
		if ($(this).val() == $(this)[0].title){
			$(this).val("");
		}
	});

	$("#resourceSearchBox").blur(function(){
		if ($(this).val() == ""){
			$(this).val($(this)[0].title);
		}
	});

	$("#resourceSearchBox").blur();
	
	$('#printerIcon').click(function(){
		window.print();
	});
	
	$('.resource-print-window').click(function(){
		window.print();
	});

    $("#main-nav-about-wsc").mouseover(function(){
        $('#megaAbout').show();
    }).mouseout(function(){
        $('#megaAbout').hide();
    });

    $("#main-nav-academics").mouseover(function(){
        $('#megaAcademics').show();
    }).mouseout(function(){
        $('#megaAcademics').hide();
    });

    $("#main-nav-admissions").mouseover(function(){
        $('#megaAdmissions').show();
    }).mouseout(function(){
        $('#megaAdmissions').hide();
    });

    $("#main-nav-news-and-events").mouseover(function(){
        $('#megaNews').show();
    }).mouseout(function(){
        $('#megaNews').hide();
    });

    $("#main-nav-resources").mouseover(function(){
        $('#megaResources').show();
    }).mouseout(function(){
        $('#megaResources').hide();
    });

    $("#main-nav-wsc-gateway").mouseover(function(){
        $('#megaGateway').show();
    }).mouseout(function(){
        $('#megaGateway').hide();
    });


    
    
    
});
