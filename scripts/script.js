$(function(){

         

	$('#my_form').on('submit', function(e){
		e.preventDefault();
		var $that = $(this),
	          formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму
		
		$.ajax({
			url: $that.attr('action'),
			type: $that.attr('method'),
			contentType: false, // важно - убираем форматирование данных по умолчанию
			processData: false, // важно - убираем преобразование строк по умолчанию
			data: formData,
			success: function(response){
				if(response){
                                    if(/\<\>|\(/.test(response)){
                                        $("#thumbnail").attr("src", 'img/gon.jpg');
                                        }
                                    else{
                                            $("#thumbnail").attr("src", response);
                                            $('#source').val(response);
                                            //$('#respa').text(response);
                                            $("#thumbnail").imgAreaSelect({hide: true});
                                            }
				}
			}
		});
	});    
    $("#thumbnail").imgAreaSelect({handles: true, keys: { arrows: 15, ctrl: 5, shift: 'resize' }, onSelectChange: preview});
    
    function preview (img, selection) {

			$("#x1").val(selection.x1);
			$("#y1").val(selection.y1);
			$("#x2").val(selection.x2);
			$("#y2").val(selection.y2);
			$("#w").val(selection.width);
			$("#h").val(selection.height);
			
		}
        
        $("#butSave").click(function () {
                            var image = $('#source').val();
			var x1 = $("#x1").val();
			var y1 = $("#y1").val();
			var x2 = $("#x2").val();
			var y2 = $("#y2").val();
			var w = $("#w").val();
		        var h = $("#h").val();
			$.ajax ({
				url: "worker_crop.php",
				type: "POST",
				data: {image: image, x1: x1, y1: y1, w: w, h: h},
				success: function (response) {
                                    $('#response').html(response);
                                    }
	});
    });
});

function equalHeightWidth (a, b, c){
	var a = $(a);
	var b = $(b);
if(a.height() > b.height())	{
	b.height(a.height());
}
else{
	a.height(b.height());
}
if(c === true){
	if(a.width()>b.width()){
		b.width(a.width());
	}
	else{
		a.width(b.width());
	}
}
}