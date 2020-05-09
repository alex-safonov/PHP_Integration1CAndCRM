<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTFГЕА-8">
  <title>Document</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
</head>

<body>
  
<form method="post" id="ajax_form" action="" >

	<fieldset>
		<legend>Форма</legend> 
		<p>
		<label for="name">Имя обрабатываемого файла</label>
		<input type="text" name="name" placeholder="Введите значение" /><br>
		</p> 

		<p>
		<label for="name">Стартовая строка</label>
		<input id='string' type="text" name="string" placeholder="Введите значение" /><br>
		</p> 

		<p>
		<label for="name">Количество обрабатываемых строк</label>
		<input id='quantity' type="text" name="quantity" placeholder="Введите значение" /><br>  
		</p> 

		<p>
		<label for="name">Финишная строка</label>
		<input id='end' type="text" name="end" placeholder="Введите значение" /><br>  
		</p> 

		<p>
		<label for="all">Обработать весь файл</label>
		<input id='all' type="checkbox" name="all"> 
		</p>     

		<input type="button" id="btn" value="Отправить" />

	</fieldset>
</form>
<br>

	<div id="result_form"></div> 


<script type="text/javascript">
 
$( document ).ready(function() {
    $("#btn").click(
    function(){
      sendAjaxForm('result_form', 'ajax_form', 'action_ajax_form_json.php');
      return false; 
    }
  );
});
 
function sendAjaxForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url, //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
        result = $.parseJSON(response);

console.log(result);

		  	for (let key in result) {
		    	//for (let key2 in result[key]) {
		      	$('#result_form').append(result[key] + '; ');
		      	//} 
		        //$('#result_form').append('<br>');
		    }  
		        $('#result_form').append('<br>');		    
				// $.each(result, function(){
			  	//   $('#result_form').append(result + '<br>');
			  	// });

			  	//  if(result['next'] == 'on') {

			if( !($("#all").prop("checked"))) { 		

			  	if(+$('#string').val() < +$('#end').val()) { 
					//   var string = +$('#string').val() + 1;
			   		var string = +$('#string').val() + +$('#quantity').val();
			    	$('#string').val(string);

					setTimeout(function() { sendAjaxForm('result_form', 'ajax_form', 'action_ajax_form_json.php') }, 2000);

					 // sendAjaxForm('result_form', 'ajax_form', 'action_ajax_form_json.php');    
			  	}
			  	else {
			  		$('#result_form').append('<pre>Обработка указанных строк закончена.</pre>');
			  	}
			}
			else {
				if(result) { 
					//   var string = +$('#string').val() + 1;
			   		var string = +$('#string').val() + +$('#quantity').val();
			    	$('#string').val(string);

					setTimeout(function() { sendAjaxForm('result_form', 'ajax_form', 'action_ajax_form_json.php') }, 2000);
	
					// sendAjaxForm('result_form', 'ajax_form', 'action_ajax_form_json.php');    
			  	}
			  	else {
			  		$('#result_form').prepend('<pre>Была выбрана опция обработки всего файла.</pre>');
			  		$('#result_form').append('<pre>Весь файл обработан.</pre>');
			  	}
			}  	
		},
	  	error: function(response) { // Данные не отправлены
	    $('#result_form').html('Ошибка. Данные не отправлены.');
	  	}
	});
}

</script>

</body>
</html>