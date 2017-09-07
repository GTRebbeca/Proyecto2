<!DOCTYPE html>
<html>
<head>
<?php
session_start();
?>
<meta charset='utf-8' />
<link rel='stylesheet' href='../lib/cupertino/jquery-ui.min.css' />
<link href='../fullcalendar.min.css' rel='stylesheet' />
<link href='../fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='../lib/moment.min.js'></script>
<script src='../lib/jquery.min.js'></script>
<script src='../fullcalendar.min.js'></script>
<!--Idiomas que quiero (se pueden agregar más o todos <script src='fullcalendar/locale-all.js'></script>-->
<script src='../locale/es.js'></script>
<script src='../locale/ar.js'></script>
<script src='../locale/de.js'></script>
<script src='../locale/fr.js'></script>
<script src='../locale/it.js'></script>
<script src='../locale/ja.js'></script>
<script src='../locale/pt.js'></script>
<script src='../locale/ru.js'></script>
<script src='../locale/zh-cn.js'></script>
<div class="popup" style="display:none; position:fixed; top:25%; left:25%; background-color:white;">
  <input class"title" type="text" size="26" />
  <a href="#" onclick="return false" class="submitFrom">submit</a>&emsp;
  <a href="#" onclick="return false" class="exit">cancel</a>
</div>
<script>

  $(document).ready(function() {
    var initialLocaleCode = 'es';

    $('#calendar').fullCalendar({
      themeButtonIcons: true,
      themeButtonIcons: {
          prev: 'circle-triangle-w',
          next: 'circle-triangle-e',
          prevYear: 'seek-prev',
          nextYear: 'seek-next'
      },
      customButtons: {  //botones personalizados
          botonSig: {
              text: 'Siguiente/Ir',
              themeIcon: 'seek-next',
              click: function() {
                  $('#calendar').fullCalendar('changeView', 'agendaDay');  //te amnda al dia seleccioando (default hoy)
              }
          }
        },
      theme: true,
      header: {
        left: 'prev, today next',
        center: 'title',
        right: 'month, /*botonSig*/ agendaWeek agendaDay'
      },
      views: {
            month: { // name of view
                //titleFormat: 'YYYY, MM, DD'
                // other view-specific options here
            },
            agendaDay: {
              timeFormat: 'H:mm' //no sirve?
                // options apply to basicDay and agendaDay views
                
            }
        },

      fixedWeekCount: false,   //hace que tenga semanas dependiendo del mes, (default es que siempre sean 6 semanas)
      firstDay: 1,  //hace que el primer dia del calendario sea el lunes( se puede cambiar a domingo con 0)
      //weekNumbers: true,     //numero de semana del año
      businessHours: [ // specifica las horas de trabajo
          {
              dow: [ 1, 2, 3, 4 ], // Monday, Tuesday, Wednesday, Thu
              start: '10:00', // 
              end: '18:00' // 6pm
          },
          {
              dow: [ 5 ], //  Friday
              start: '10:00', // 
              end: '17:00' // 
          }
      ],
      allDaySlot: false, //quitamos el allday de la parte de arriba
      slotDuration: '00:15:00',  //eventos cada 15 minutos
      slotLabelInterval: '00:15',  //se ve el label cada 15 minutos 
      nowIndicator: true,    //enseña una linea en la hora actual
      //se puede configurar height/aspect ratio talvez para después
      minTime: "10:00:00",   //mostar hora minima y maxima de trabajo
          maxTime: "18:00:00",
      //defaultDate: '2017-05-12',   //no se especifica entonces pone la fecha de hoy
      locale: initialLocaleCode,    //inicialmente en idioma español
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectHelper: true,
      /*eventSources: [

            // your event source
            {
                url: '/myfeed.php',
                type: 'POST',
                data: {
                    custom_param1: 'something',
                    custom_param2: 'somethingelse'
                },
                error: function() {
                    alert('there was an error while fetching events!');
                },
                color: 'yellow',   // a non-ajax option
                textColor: 'black' // a non-ajax option
            }

            // any other sources...

        ],*/

  eventDrop: function ( event, delta, revertFunc, jsEvent, ui, view ) {
       alert(event.title + " se movio a " + event.start.format());

          if (!confirm("Estas seguro del cambio?")) {
              revertFunc();
          }
       },

      eventClick: function(calEvent, jsEvent, view) {   //pasa cuando das click a un evento

            alert('Aqui va lo de contacto fecha hora duracion cuenta motivo descripcion: ' + calEvent.title);
            alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
            alert('View: ' + view.name);
            var moment = $('#calendar').fullCalendar('getDate');
            alert(moment.format("DD/MM/YY"));  //"dddd (d) DDD - D/MM/YY"
            // change the border color just for fun
            $(this).css('border-color', 'red');
        },
        eventMouseover: function(calEvent,jsEvent, view){
          $(this).css('border-color', 'green');
          $(this).css('border-width', '1px');
        },
        eventMouseout: function(calEvent,jsEvent, view){
          $(this).css('border-color', 'transparent');
        },

      select: function(start, end, jsEvent, view) { //popup para detalle
        if (view.name == 'agendaDay'){
          var title = prompt('Cuenta:');
          var eventData;
          if (title) {
              eventData = {
              title: title,
              start: start,
              end: end
            };
            $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
          }
          $('#calendar').fullCalendar('unselect');
          //$('#calendar').fullCalendar('changeView', 'agendaDay'); //cuando doy click me cambia a view de dia (puede haber otra bandera de cuenta seleccionada)
          //pero lo esta haciendo en day y month solo deberia ser en month y en day que haga otra cosa
        }
        
      },
      /*unselectAuto: true,
unselect: function(date, jsEvent, view){
    alert('quiero deseleccionar lo verde');
    $(date).css('border-color', 'transparent');
},*/
      dayClick: function(date, jsEvent, view) {
          //$('#calendar').fullCalendar('unselect');
                $('#calendar').fullCalendar('gotoDate', date);    //selecciona el dia
                $('.fc-day').each(function () {
              $(this).removeClass("fc-state-highlight");
          });
          //$("td[data-date=" + date.format() + "]").addClass("fc-state-highlight");  
        },

      editable: true,
      eventLimit: true, // allow "more" link when too many events
      //$('#calendar').fullCalendar({ events: "/myfeed.php" });
      events: [
        /*{
          title: 'All Day Event',
          start: '2017-09-01'
        },
        {
          title: 'Long Event',
          start: '2017-09-07',
          end: '2017-09-10'
        },
        {
          id: 999,
          title: 'Repeating Event',
          start: '2017-09-09T16:00:00'
        },
        {
          title: 'Conference',
          start: '2017-09-11',
          end: '2017-09-13'
        },
        {
          title: 'Meeting',
          start: '2017-09-12T10:30:00',
          end: '2017-09-12T12:30:00'
        },

        {
          title: 'Birthday Party',
          start: '2017-09-13T07:00:00'
        },
        {   //importante para mandar a otras
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2017-08-28'
        }*/
      ]
    });
    

    // build the locale selector's options
    $.each($.fullCalendar.locales, function(localeCode) {
      $('#locale-selector').append(
        $('<option/>')
          .attr('value', localeCode)
          .prop('selected', localeCode == initialLocaleCode)
          .text(localeCode)
      );
    });

    // when the selected option changes, dynamically change the calendar option
    $('#locale-selector').on('change', function() {
      if (this.value) {
        $('#calendar').fullCalendar('option', 'locale', this.value);
      }
    });


  });

</script>
<style>

  body {
    /*margin: 40px 10px;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;*/
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;


  }

  #top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;

    font-size: 12px;

  }

  #calendar {
    width: 60%;
    padding: 0 0px;
    float:right;
    margin-right: 5%;
    margin-top: 2%;

  }

</style>
    <style type="text/css">
#cssmenu {
  padding: 0;
  margin: 0;
  border: 0;
}
#lista {

 float: left;
  width: 25%;
  margin-top: 2%;
  margin-left: 5%;
  height: 800px;
  overflow-y: scroll;
  background: #373A47;
}


#cssmenu ul,
#cssmenu ul li,
#cssmenu ul ul {
  list-style: none;
  margin: 0;
  padding: 0;
}
#cssmenu ul {
  position: relative;
   

}
#cssmenu ul li {
  min-height: 1px;
  line-height: 1em;
  vertical-align: middle;
  position: relative;
}
#cssmenu ul li.hover,
#cssmenu ul li:hover {
  position: relative;
  cursor: default;
}
#cssmenu ul ul {
  visibility: hidden;
  position: absolute;
  top: 100%;
  left: 0px;
  z-index: 598;
  width: 100%;
}
#cssmenu ul ul li {
  float: none;
}
#cssmenu ul ul ul {
  top: -2px;
  right: 0;
}
#cssmenu ul li:hover > ul {
  visibility: visible;
}
#cssmenu ul ul {
  top: 1px;
  left: 99%;
}

#cssmenu ul ul {
  margin-top: 1px;
}
#cssmenu ul ul li {
  font-weight: normal;
}
/* Custom CSS Styles */
#cssmenu {
  width: 100%;
  background: #373A47;
  zoom: 1;
  font-size: 90%;
  font-family: Vegur, 'PT Sans', Verdana, Sans-serif;
  font-weight: 400;
 

}
#cssmenu:before {
  content: '';
  display: block;
}
#cssmenu:after {
  content: '';
  display: table;
  clear: both;
}
#cssmenu a {
  display: block;
  padding: 15px 20px;
  color: #ffffff;
  text-decoration: none;

  /*text-transform: uppercase;*/
}
#cssmenu > ul {
  width: 100%;
}

#cssmenu > ul > li > a {
  border-right: 0px solid #1b9bff;
  color: #ffffff;
}
#cssmenu > ul > li > a:hover {
  color: #ffffff;
}
#cssmenu > ul > li.active a {
  background: #29ABE1;
}
#cssmenu > ul > li a:hover,
#cssmenu > ul > li:hover a {
  background: #29ABE1;
}
#cssmenu > ul > li a:focus,
#cssmenu > ul > li:focus a {
  background: #29ABE1;
}
#cssmenu > ul > li a:active,
#cssmenu > ul > li:active a {
  background: #29ABE1;
}
.estilo {
  text-align: center;
  background: #29ABE1;
  font-size: 100%;
}

#cssmenu li {
  position: relative;
}
#cssmenu ul li.has-sub > a:after {
  position: absolute;
  top: 50%;
  right: 15px;
  margin-top: -6px;
}

#cssmenu.align-right {
  float: center;
}
#cssmenu.align-right li {
  text-align: center;
}
#cssmenu.align-right ul li.has-sub > a:before {
  position: absolute;
  top: 50%;
  
  margin-top: -6px;
}

    </style>
</head>
<body> 


<?php


$access_token = $_SESSION['access_token'];
$instance_url = $_SESSION['instance_url'];

$query = "SELECT Id, Name FROM Account";

$url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Authorization: OAuth $access_token"));


$json_response = curl_exec($curl);
curl_close($curl);

$response = json_decode($json_response, true);
$total_size = $response['totalSize'];

//echo "$total_size record(s) returned<br/><br/>";

//echo "<br/>";
//echo $json_response;
//echo "<br/>";
//for($i=0; $i<$total_size; $i++){
   //$r = $response['records'][$i]["Name"];
    //echo $r;    
//}

?>
 
            <Script type="text/javascript">

                    function Cuenta(obj1){
                        var obj2 = String(obj1);
                        document.getElementById("cuenta").value = obj2.substring(44);   
                        //document.getElementById("di").style.backgroundColor ="#29ABE1";
                            //alert(obj2.substring(41));

                    }

                    function leerJSON(){ 
                    alert('<?php echo $json_response; ?>');  
                    var json2 = JSON.parse('<?php echo $json_response; ?>'); 
                        document.write("<div id=\"lista\"><nav class=\"menu\" id=\"cssmenu\"><ul><li class=\"has-sub\"><a class=\"estilo\">CUENTAS</a></li>");          
                        for (var i=0; i<json2.totalSize; i++) {
                            //var obj1 = obj2.toString();    
                             document.write("<li class=\"has-sub\"><a href=\"#"+ json2.records[i].Id +"\" onclick=\"Cuenta(this)\"  id=\"di\">"+json2.records[i].Name+"</a></li>");
                        }        
                               document.write("</nav></div></ul>");
                    }  
                        leerJSON(); 
            </Script>
                 



 <!--   Locales:
    <select id='locale-selector'></select>-->

 

  <div id='calendar'>   <form id="pizza">
                    <input type="Text" Id="cuenta" value="" disabled="true" />
                    </form></div>

</body>   
   
</html>       