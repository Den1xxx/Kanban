<?php
if (isset($_POST['content'])) {
	file_put_contents('saved.txt',$_POST['content']);
	die($_POST['content']);
}
if (isset($_POST['delete_data'])) {
	if ($_POST['delete_data']=='123131') {
		if (is_file('saved.txt')) unlink('saved.txt');
	}
	die('324');
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kanban!!!</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
  <style>
  body {
    min-width: 520px;
  }
  
  .column {
    width: 272px;
    float: left;
    padding-bottom: 30px;
	margin:10px;
	color: white;
	background: #55acee;
	box-shadow: 0 5px 0 #3C93D5;
	border-radius:5px;
  }
  
  .column h1{
	font-size:1.5em;
  	padding:5px;
	margin:0 5px;
  }
  
  .portlet {
    margin: 0.5em 1em 1em 0.5em;
    padding: 0.3em;
	border-radius:3px;
	box-shadow: 0 4px 0 #888;
	/* box-shadow: 3px 3px 4px 2px #777; */
  }
  
  .portlet-header {
    padding: 0.2em 0.3em;
    margin-bottom: 0.5em;
    position: relative;
	font-size:0.8em;
	box-shadow: 0 2px 0 #777;
  }

  .portlet-toggle {
    position: absolute;
    top: 50%;
    right: 16px;
    margin-top: -8px;
	z-index:10;
  }
  .portlet-close {
    position: absolute;
    top: 50%;
    right: 0;
    margin-top: -8px;
	z-index:10;
  }
/* 
  .portlet-toggle, .portlet-close {
	  float:right;
	  margin:1px;
  } 
*/ 

  .portlet-content {
    padding: 0.4em;
  }
 
  .portlet-placeholder {
    border: 1px dotted black;
    margin: 0 1em 1em 0;
    height: 50px;
  }
  .ui-button {
	box-shadow: 0 2px 0 #777; 
  }
  


.bg-ex-gradient{
  background: #1C649B;
  background: -webkit-radial-gradient(circle farthest-side at center center, #5C8DB3 0%, #1C649B 100%);
  background: -moz-radial-gradient(circle farthest-side at center center, #5C8DB3 0%, #1C649B 100%);
  background: radial-gradient(circle farthest-side at center center, #5C8DB3 0%, #1C649B 100%);
}
     


  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <script>
$(function() {
	$( ".column" ).sortable({
		connectWith: ".column",
		handle: ".portlet-header",
		cancel: ".portlet-toggle",
		placeholder: "portlet-placeholder ui-corner-all"
	});

	//add portlet
	$( "#add_portlet" ).on( "click", function() {

		var now = new Date();
		
		$("#column_1").append(
		'<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">' +
		'<div class="portlet-header ui-widget-header ui-corner-all"><span class="ui-icon ui-icon-minusthick portlet-toggle" onClick="toggle_portlet(this)"></span>' + 
		now.toUTCString() + 
		'<span class="ui-button-icon ui-icon ui-icon-closethick portlet-close" onClick="$($(this).parents().get(1)).remove();"></span></div>' +
		'<div class="portlet-content" contenteditable="true">Описание</div>' +
		'</div>'
		);
		
	});
	//end add portlet
	
});
  
function toggle_portlet(item) {
	var icon = $( item );
	icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
	icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();	
} 
  
function save_data() {
	$.post("index.php", {content:$('#main').html()})
	.done(function(data) {
		if (data==$('#main').html()) {
			alert('Сохранено!');
		}
	});
}  
function delete_data() {
	if (confirm("Очистить всю доску?")) {
	  	$.post("index.php", {delete_data:123131})
		.done(function(data) {
		if (data==324) {
			alert('Удалено!');
			location.reload(); 
			return false;
		}
	});
	  
	} else {
	  alert('Отменено');
	}
}
/*!
 * jQuery UI Touch Punch 0.2.3
 *
 * Copyright 2011–2014, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
(function ($) {

  // Detect touch support
  $.support.touch = 'ontouchend' in document;

  // Ignore browsers without touch support
  if (!$.support.touch) {
    return;
  }

  var mouseProto = $.ui.mouse.prototype,
      _mouseInit = mouseProto._mouseInit,
      _mouseDestroy = mouseProto._mouseDestroy,
      touchHandled;

  /**
   * Simulate a mouse event based on a corresponding touch event
   * @param {Object} event A touch event
   * @param {String} simulatedType The corresponding mouse event
   */
  function simulateMouseEvent (event, simulatedType) {

    // Ignore multi-touch events
    if (event.originalEvent.touches.length > 1) {
      return;
    }

    event.preventDefault();

    var touch = event.originalEvent.changedTouches[0],
        simulatedEvent = document.createEvent('MouseEvents');
    
    // Initialize the simulated mouse event using the touch event's coordinates
    simulatedEvent.initMouseEvent(
      simulatedType,    // type
      true,             // bubbles                    
      true,             // cancelable                 
      window,           // view                       
      1,                // detail                     
      touch.screenX,    // screenX                    
      touch.screenY,    // screenY                    
      touch.clientX,    // clientX                    
      touch.clientY,    // clientY                    
      false,            // ctrlKey                    
      false,            // altKey                     
      false,            // shiftKey                   
      false,            // metaKey                    
      0,                // button                     
      null              // relatedTarget              
    );

    // Dispatch the simulated event to the target element
    event.target.dispatchEvent(simulatedEvent);
  }

  /**
   * Handle the jQuery UI widget's touchstart events
   * @param {Object} event The widget element's touchstart event
   */
  mouseProto._touchStart = function (event) {

    var self = this;

    // Ignore the event if another widget is already being handled
    if (touchHandled || !self._mouseCapture(event.originalEvent.changedTouches[0])) {
      return;
    }

    // Set the flag to prevent other widgets from inheriting the touch event
    touchHandled = true;

    // Track movement to determine if interaction was a click
    self._touchMoved = false;

    // Simulate the mouseover event
    simulateMouseEvent(event, 'mouseover');

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');

    // Simulate the mousedown event
    simulateMouseEvent(event, 'mousedown');
  };

  /**
   * Handle the jQuery UI widget's touchmove events
   * @param {Object} event The document's touchmove event
   */
  mouseProto._touchMove = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Interaction was not a click
    this._touchMoved = true;

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');
  };

  /**
   * Handle the jQuery UI widget's touchend events
   * @param {Object} event The document's touchend event
   */
  mouseProto._touchEnd = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Simulate the mouseup event
    simulateMouseEvent(event, 'mouseup');

    // Simulate the mouseout event
    simulateMouseEvent(event, 'mouseout');

    // If the touch interaction did not move, it should trigger a click
    if (!this._touchMoved) {

      // Simulate the click event
      simulateMouseEvent(event, 'click');
    }

    // Unset the flag to allow other widgets to inherit the touch event
    touchHandled = false;
  };

  /**
   * A duck punch of the $.ui.mouse _mouseInit method to support touch events.
   * This method extends the widget with bound touch event handlers that
   * translate touch events to mouse events and pass them to the widget's
   * original mouse event handling methods.
   */
  mouseProto._mouseInit = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element.bind({
      touchstart: $.proxy(self, '_touchStart'),
      touchmove: $.proxy(self, '_touchMove'),
      touchend: $.proxy(self, '_touchEnd')
    });

    // Call the original $.ui.mouse init method
    _mouseInit.call(self);
  };

  /**
   * Remove the touch event handlers
   */
  mouseProto._mouseDestroy = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element.unbind({
      touchstart: $.proxy(self, '_touchStart'),
      touchmove: $.proxy(self, '_touchMove'),
      touchend: $.proxy(self, '_touchEnd')
    });

    // Call the original $.ui.mouse destroy method
    _mouseDestroy.call(self);
  };

})(jQuery);
$( ".column" ).draggable();
  </script>
</head>
<body class="bg-ex-gradient">
<button class="ui-button ui-widget ui-corner-all" id="add_portlet">Новая задача</button>
<button class="ui-button ui-widget ui-corner-all" onClick="save_data();">Сохранить доску</button>
<button class="ui-button ui-widget ui-corner-all" onClick="location.reload(); return false;">Обновить доску</button>
<button class="ui-button ui-widget ui-corner-all" onClick="delete_data();">Удалить всё</button>
<div id="main" >
<?php
if (is_file('saved.txt')) {
	$html = file_get_contents('saved.txt');
} else { 
$html =  
'	<div class="column" id="column_1">
	 <h1 contentEditable="true">Сделать</h1>
	</div>
	 
	<div class="column" id="column_2">
	  <h1 contentEditable="true">В работе</h1>
	</div>
	 
	<div class="column" id="column_3">
	  <h1 contentEditable="true">На проверке</h1>
	</div>

	<div class="column" id="column_4">
	  <h1 contentEditable="true">Сделано</h1> 
	</div>';
}
echo $html;	
?>
</div>
<footer > 
 <!--
 <a href="https://jqueryui.com/sortable/#portlets">Source</a>
 -->
</footer>
</body>
</html>