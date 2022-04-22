function dinamicos(){
	//url es la url del archivo javascript externo que se quiere cargar
    var funciones = document.createElement("script");
    funciones.type = "text/javascript";
    funciones.src = 'js/funciones.js';
   	document.body.appendChild(funciones);
}

function isScrolledIntoView(elem){
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function minHeightCardImage(){
	$(".cardImage.autoResize").each(function(){
		$(this).css({'min-height':$(this).closest( ".cardContainer" ).height()+'px'});
		// console.log($(this).closest( ".cardContainer" ).height());
	});
}

$(document).ready(function(){
		minHeightCardImage();

	    $('select').material_select();
	
	    $.datepicker.regional['es'] = {
	        closeText: 'Cerrar',
	        prevText: '<Ant',
	        nextText: 'Sig>',
	        currentText: 'Hoy',
	        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
	        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
	        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
	        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
	        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
	        weekHeader: 'Sm',
	        dateFormat: 'dd/mm/yy',
	        firstDay: 1,
	        isRTL: false,
	        showMonthAfterYear: false,
	        yearSuffix: ''
	    };

		/******* DESPLAZAMIENTO SUAVE ***********/
	    $('a.softScroll').click(function(){
	        $('html, body').animate({
	            scrollTop: $( $.attr(this, 'href') ).offset().top
	        }, 800);
	        return false;
	    });
	   	$("a.btnRedondoFixed").click(function(){
			$('html, body').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	    /******* FIN DESPLAZAMIENTO SUAVE ***********/


        $(".niceScroll").niceScroll({
            touchbehavior: true,
            cursorcolor: "#999"
        });

});
$( window ).resize(function() {
	minHeightCardImage();
})
$(document).ready(function(){
	$('.subMenu').mouseover(function(event){
		if ($(document).width()>768){
			$(".subMenuPanel").attr("opened",'false').hide();
			$(this).attr("opened",'true');
			$($(this).attr('subMenuPanel')).show();
			$("#menu").addClass('menuAzul');
			$("#headerLogoBlanco").removeClass('oculto');
			$("#headerLogo").addClass('oculto');
		}
		event.stopPropagation();
	});

	$('.subMenu').click(function(){
		$('.subMenu').removeClass('active');
		$(this).addClass('active');
	});

	$(".subMenuPanel,.subMenu").click(function(event){
		// event.preventDefault();
		event.stopPropagation();
	});
	$(".subMenuPanel").mouseover(function(event){
		event.stopPropagation();
	});
	$(document).click(function(){
		$(".subMenuPanel").removeClass('open').hide();
		$('.subMenu').removeClass('active');
		if ($("#menu").hasClass('menuAzul')){
			$("#menu").removeClass('menuAzul');
			$("#headerLogoBlanco").addClass('oculto');
			$("#headerLogo").removeClass('oculto');
		}
	});

	$(document).mouseover(function(){
		$(".subMenuPanel").removeClass('open').hide();
	});
	
	
	var posYMenu=$("#menu").offset().top;
	var scrollDoc=$(window).scrollTop();
	if (scrollDoc>posYMenu){
		if ($("a.btnRedondoFixed").css('display')=='none'){
			$("a.btnRedondoFixed").fadeIn('fast');
		}
	}


	$(".buscador").hover(BuscadorHoverOn,BuscadorHoverOff);
});

	function BuscadorHoverOn() {
		$(".field").fadeIn(400);
		$(".buscador").animate({ width:'220px' },400);
	}

	function BuscadorHoverOff() {
		$(".field").fadeOut(400);
		$(".buscador ").animate({ width:'28px'},400);
	}


function basename(path) {
	return path.replace(/.*\/|\.[^.]*$/g, "" );
}

$(document).scroll(function(){

	// ************************* MOSTRAR / OCULTAR MENU / btnScrollUp SEGUN SCROLL ***************************
	var posYMenu=$("#menu").offset().top;
	var scrollDoc=$(window).scrollTop();
	if (scrollDoc>80){
		$("#menu").addClass("sombra").addClass("fixed").removeClass('menuTransparente');
		if (!$("#menu").hasClass('menuAzul')){
			$("#headerLogoBlanco").addClass('oculto');
			$("#headerLogo").removeClass('oculto');
		}
		if ($("a.btnRedondoFixed").css('display')=='none'){
			$("a.btnRedondoFixed").fadeIn('fast');
		}
	}else if(scrollDoc<80){
		$("#menu").removeClass("sombra").removeClass("fixed").addClass('menuTransparente');	
		// $("#headerLogoBlanco").removeClass('oculto');
		// $("#headerLogo").addClass('oculto');
		if (!$("#menu").hasClass('menuAzul')){
			$("#headerLogoBlanco").removeClass('oculto');
			$("#headerLogo").addClass('oculto');
		}else{

		}
		if ($("a.btnRedondoFixed").css('display')=='block'){
			$("a.btnRedondoFixed:visible").fadeOut('fast');
		}
	}

	// *********************** FIN MOSTRAR / OCULTAR MENU SEGUN SCROLL *************************
	/************************** EFECTO PARALLAX ****************************/
	
	$(".parallax").css({
		'background-position-y': function(index, value) {
			return (-1*($(this).offset().top - $(document).scrollTop())/10*5.5+100);
		}
	});
	
	/************************ FIN EFECTO PARALLAX **************************/


});
