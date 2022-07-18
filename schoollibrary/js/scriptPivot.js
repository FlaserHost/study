$(document).ready(function(){
	$(document).on("click", ".showDetails", function(e){
		e.preventDefault();
		$("#pivotData tbody tr:not(:first-child)").remove();
		$(".datailsModal").css("display", "flex");

		let property = $(this).data("property");
		let linkName = $(this).data("linkname");
		let tr = '';
		let trClose = '</tr>';

		$.ajax({
			url: '../actions/showDetails.php',
			type: 'POST',
			dataType: 'JSON',
			data: ({
				bookName: property,
				bookStatus: linkName
			}),
			success: function(data){
				for(let i = 1; i <= data[0]; i++)
				{
					if(data[i].bookStatus === 'Выдано')
					{
						tr = `<tr class="red" rowId="${i}">`;
					}
					else
					{
						tr = `<tr rowId="${i}">`;
					}

					$("#pivotData > tbody").append(`
						${tr}
							<td>${i}</td>
							<td>${data[i].bookName}</td>
							<td>${data[i].bookCategory}</td>
							<td>${data[i].bookAuthor}</td>
							<td>${data[i].bookPublish}</td>
							<td>${data[i].bookStatus}</td>
							<td>${data[i].bookInClass}</td>
							<td>${data[i].bookDisciple}</td>
						${trClose}
					`);
				}
			},
			error: function(data){
				alert(`Ошибка: ${data}`);
			}
		});
	});

	$(".closeCross").click(function(){
		$(".datailsModal").css("display", "none");
	});

	$(".showPanelArrow").click(function(){
		let targetItem = $(this).parent();
		let panelstatus = targetItem.data("panel");

		if(panelstatus === 'Панель скрыта')
		{
			targetItem.css("transform", "translateX(100%)");
			targetItem.data("panel", "Панель показана");
		}
		else if(panelstatus === 'Панель показана')
		{
			targetItem.css("transform", "translateX(0)");
			targetItem.data("panel", "Панель скрыта");
		}
	});

	$("#classes").click(function(){
		$(".classModal").css("display", "flex");

		$.ajax({
			url: '../actions/showClasses.php',
			dataType: 'JSON',
			success: function(data){
				for(let i = 1; i <= data[0]; i++)
				{
					$("#classSelector").append(`
						<option id="class_${data[i].classId}" value="${data[i].classId}">${data[i].getClass}</option>
					`);
				}
			},
			error: function(data){
				alert(`Произошла ошибка: ${data}`);
			}
		});
	});

	$(".classDataPlace").on("change", "#classSelector", function(){
		$("#disciplesTable tr:not(:first-child)").remove();
		let classId = $(this).val();

		$.ajax({
			url: '../actions/showDisciples.php',
			type: 'POST',
			dataType: 'JSON',
			data: ({
				classId: classId
			}),
			success: function(data){
				for(let i = 1; i <= data[0]; i++)
				{
					$("#disciplesTable > tbody").append(`
						<tr>
							<td>${data[i].discipleClass}</td>
							<td>${data[i].discipleFIO}</td>
							<td>${data[i].discipleAge}</td>
						</tr>
					`);
				}
			},
			error: function(data){
				alert(`Произошла ошибка: ${data}`);
			}
		});
	});

	$(".closeCrossClasses").click(function(){
		$(".classModal").css("display", "none");
		$("#disciplesTable tr:not(:first-child)").remove();
	});
});