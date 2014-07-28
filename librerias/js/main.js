// Funcion que controla que no sean mas de 3 caracteres en el input buscar 
// Para el datapicker, es una funcion de jquery ui 

$(document).ready(function(){
         $(".carga").hide();
});

// Le da color a los dias.
$(document).ready(function() {
	$('.dia:contains("Sábado")').addClass("label label-info");
	$('.dia:contains("Domingo")').addClass("label label-important");
});




$(document).ready(function(){
        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });

        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
 
});

$(document).ready(function(){
    $( "#datepicker" ).datepicker({
		dateFormat: 'dd-mm-yy',
		changeMonth: true,
		changeYear: true
    });
  });
  
$(function() {
    $( "#datepicker2" ).datepicker({	dateFormat: 'dd-mm-yy',
									changeMonth: true,
									changeYear: true});
  });
  

			$(document).ready(function() {
				 $('#example').dataTable( {
				"sPaginationType": "full_numbers",
				 "oLanguage": {
			           "sLengthMenu": 'Mostrar <select>'+
			             '<option value="10">10</option>'+
			             '<option value="25">25</option>'+
			             '<option value="50">50</option>'+
			             '<option value="100">100</option>'+
			             '<option value="-1" selected>Todos</option>'+
			             '</select> entradas'
			         }
			} );
			} );

function imprSelec(muestra){
	//oculto paginadores y busqueda
	$("#example_length").hide();
	$("#example_filter").hide();
	$("#example_info").hide();
	$("#example_paginate").hide();
	
	var ficha=document.getElementById(muestra);
	var ventimp=window.open(' ','popimpr');ventimp.document.write(ficha.innerHTML);
	ventimp.document.close();
	ventimp.print();
	ventimp.close();
	
	//oculto paginadores y busqueda
	$("#example_length").show();
	$("#example_filter").show();
	$("#example_info").show();
	$("#example_paginate").show();
}

$(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "clip",
        duration: 1000
      }
    });
 
    $( "#opener" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
  });



function control(){
		var cliente = buscar.cliente.value;
			if (cliente.length < 3){
			alert('Debes escribir por lo menos 3 caracteres')
			buscar.cliente.focus()
			return false 
			}

		}
function controlDNi(){
		var cliente = conciliados.dninput.value;
			if (cliente.length < 3){
			alert('Debes escribir por lo menos 3 caracteres')
			conciliados.dninput.focus()
			return false 
			}

		}	
function controlCBU(){
		var CBU = ente.CBU.value;
		var Cuit = ente.Cuit.value;
			if (CBU.length < 22){
			alert('El CBU debe tener 22 números')
			ente.CBU.focus()
			return false 
			}else if(Cuit.length < 11){
			alert('El Cuit debe tener 11 números')
			ente.Cuit.focus()
			return false 
			}

		}		
		
// Funcion solo permite ingresar numeros, controla el ascii ingresado 
function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 33 && (charCode < 48 || charCode > 57))
            return false;
 
         return true;
      }		
	


// Funcion esconder o mostrar un div mediante un href, utilizado para ver facturas 

$(document).ready(function(){
        $(".slidingDiv").hide();
        $(".show_hide").show();
 
    $('.show_hide').click(function(){
    $(".slidingDiv").slideToggle();
    });
 
});


$(document).ready(function(){
 
        $(".slidingDiv2").hide();
        $(".show_hide2").show();
 
    $('.show_hide2').click(function(){
    $(".slidingDiv2").slideToggle();
    });
 
});



// Funcion para abrir y cerrar ventana 


function abrirVentana(url) {
    window.open(url, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=800, height=600");
};

function cerrarse(){ 
opener.location.reload();
window.close() 
} 


// Deteccion del navegador -->

function comprobarnavegador() {
        /* Variables para cada navegador, la funcion indexof() si no encuentra la cadena devuelve -1, 
        las variables se quedaran sin valor si la funcion indexof() no ha encontrado la cadena. */
        var is_safari = navigator.userAgent.toLowerCase().indexOf('safari/') > -1;
        var is_chrome= navigator.userAgent.toLowerCase().indexOf('chrome/') > -1;
        var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox/') > -1;
        var is_ie = navigator.userAgent.toLowerCase().indexOf('msie ') > -1;
 
        /* Detectando  si es Safari, vereis que en esta condicion preguntaremos por chrome ademas, esto es porque el 
        la cadena de texto userAgent de Safari es un poco especial y muy parecida a chrome debido a que los dos navegadores
        usan webkit. */
 
        if (is_safari && !is_chrome ) {
 
            /* Buscamos la cadena 'Version' para obtener su posicion en la cadena de texto, para ello
            utilizaremos la funcion, tolowercase() e indexof() que explicamos anteriormente */
            //var posicion = navigator.userAgent.toLowerCase().indexOf('Version/');
 
            /* Una vez que tenemos la posición de la cadena de texto que indica la version capturamos la
            subcadena con substring(), como son 4 caracteres los obtendremos de 9 al 12 que es donde 
            acaba la palabra 'version'. Tambien podraimos obtener la version con navigator.appVersion, pero
            la gran mayoria de las veces no es la version correcta. */
            //var ver_safari = navigator.userAgent.toLowerCase().substring(posicion+9, posicion+12);
 
            // Convertimos la cadena de texto a float y mostramos la version y el navegador
            //ver_safari = parseFloat(ver_safari);
            //alert('Su navegador es Safari, Version: ' + ver_safari);
        }
 
        //Detectando si es Chrome
        if (is_chrome ) {
            //var posicion = navigator.userAgent.toLowerCase().indexOf('chrome/');
            //var ver_chrome = navigator.userAgent.toLowerCase().substring(posicion+7, posicion+11);
            //Comprobar version
            //ver_chrome = parseFloat(ver_chrome);
            //alert('Su navegador es Google Chrome, Version: ' + ver_chrome);
        }
 
        //Detectando si es Firefox
        if (is_firefox ) {
            //var posicion = navigator.userAgent.toLowerCase().lastIndexOf('firefox/');
            //var ver_firefox = navigator.userAgent.toLowerCase().substring(posicion+8, posicion+12);
            //Comprobar version
            //ver_firefox = parseFloat(ver_firefox); 
            //alert('Su navegador es Firefox, Version: ' + ver_firefox);
        }
 
        //Detectando Cualquier version de IE
        if (is_ie ) {
            var posicion = navigator.userAgent.toLowerCase().lastIndexOf('msie ');
            var ver_ie = navigator.userAgent.toLowerCase().substring(posicion+5, posicion+8);
            //Comprobar version
            ver_chrome = parseFloat(ver_ie);
            alert('Está usando Internet Explorer, la aplicación no funciona correctamente con este navegador: ' + ver_ie);
        }
    }
 
//Llamamos al funcion que comprueba el nagedaor al cargarse la página
window.onload = comprobarnavegador();


function processFiles(files) {
var file = files[0];

var reader = new FileReader();

reader.onload = function (e) {
// Cuando éste evento se dispara, los datos están ya disponibles.
// Se trata de copiarlos a una área <div> en la página.
var output = document.getElementById("fileOutput"); 
output.textContent = e.target.result;
};
reader.readAsText(file);
}


// Mostrar ocultar clase


function toggleError(button) { 
    if ( button.className === 'visible' ) {
        // HIDE ERROR
        button.className = '';
    } else {
        // SHOW ERROR
        button.className = 'visible';
    }
}



$(function() {
		$('#tabs_block').tabs({ fxFade: true });
	});


// Input ranger


function printValue(sliderID, textbox) {
            var x = document.getElementById(textbox);
            var y = document.getElementById(sliderID);
            x.value = y.value;
        }

        window.onload = function() { 
									printValue('slider1', 'rangeValue1'); 
									printValue('slider2', 'rangeValue2'); 
									printValue('slider3', 'rangeValue3'); 
									printValue('slider4', 'rangeValue4');
									}










var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"><head><meta charset="UTF-8"></meta><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()


