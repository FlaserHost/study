$(document).ready(function(){
	let rowNumber = 0;

	$(document).on("click", ".rowBtn, #addBtn", function(){
		sessionStorage.property = $(this).data("property");
		let hiddenItemId = $(this).data("itemid");
		let itemId = 0;
		rowNumber = $(this).data("btnnumber");

		if(sessionStorage.property === "Добавление")
		{
			$(".input").val('');
			$(".modalArea").css("display", "flex");
			$(".modalNameArea > span").html(sessionStorage.property);
			$("#intoModalBtnMain").html('Добавить').attr("data-property", 'Добавление');
			$(".label:first-child").css("display", "block");
			$("input[name=itemAmount]").css("display", "block");
		}
		else if(sessionStorage.property === "Редактирование")
		{
			$(".modalArea").css("display", "flex");
			$(".modalNameArea > span").html(sessionStorage.property);
			$("#intoModalBtnMain").html('Редактировать').attr("data-property", 'Редактирование');
			$(".label:first-child").css("display", "none");
			$("input[name=itemAmount]").css("display", "none");
			$("#hiddenItemId").val(hiddenItemId);

			itemId = $(this).data("itemid");

			$.ajax({
				url: '../actions/itemForEdit.php',
				type: 'POST',
				dataType: 'JSON',
				data: ({
					itemId: itemId
				}),
				success: function(data){
					console.log(data.editStatus);
					$("#Name").val(data.editName);
					$(`#cat_${data.editCategory}`).prop('selected', true);
					$("#Author").val(data.editAuthor);
					$("#Publish").val(data.editPublish);
					$(`#status_${data.editCurrentStatus}`).prop('selected', true);
					$(`#class_${data.editCurrentClass}`).prop('selected', true);
				}
			});
		}
		else if(sessionStorage.property === "Удаление")
		{
			let answer = confirm("Точно удалить запись?");

			if(answer)
			{
				itemId = $(this).data("itemid");

				$.ajax({
					url: '../actions/deleteItems.php',
					type: 'POST',
					data: ({
						itemId: itemId
					}),
					success: function(){
						$(`#row_${rowNumber}`).remove();
						let rowsLeft = $(".numberCell").length;
						let rowStart = 2;

						for(let i = 1; i <= rowsLeft; i++)
						{
							$(`tr:nth-child(${rowStart}) > .numberCell`).html(i);
							rowStart++;
						}

						sessionStorage.removeItem("property");
					},
				});
			}
			else
			{
				return 0;
			}
		}
	});

	$(".closeCross").click(function(){
		$(".modalArea").css("display", "none");
		$(".input").val('');
		sessionStorage.removeItem("property");
	});

	$(".modalArea").on("click", "#intoModalBtnMain", function(e){
		e.preventDefault();
		let property = $(this).data("property");
		let dataCollection = $("#modalInteractiveForm").serialize();
		
		if(sessionStorage.property === "Редактирование")
		{
			$.ajax({
				url: '../actions/editItems.php',
				type: 'POST',
				dataType: 'JSON',
				data: dataCollection,
				success: function(data){
					console.log(data.updStatus);
					$(`#nameItemCell_${rowNumber}`).html(data.newName);
					$(`#categoryItemCell_${rowNumber}`).html(data.newCategory);
					$(`#authorItemCell_${rowNumber}`).html(data.newAuthor);
					$(`#publishItemCell_${rowNumber}`).html(data.newPublish);
					$(`#statusItemCell_${rowNumber}`).html(data.newStatus);
					$(`#classItemCell_${rowNumber}`).html(data.newClass);
					$(".actionStatusNotice").css({
						"background": "lightgreen",
						"opacity": "1"
					});
					$(".actionStatusNotice > span").html(data.updStatus);

					setTimeout(()=>{
						$(".actionStatusNotice").css("opacity", "0");
					}, 1500);
				},
				error: function(data){
					console.log(data);
					$(".actionStatusNotice").css({
						"background": "brown",
						"color": "yellow",
						"opacity": "1"
					});
					$(".actionStatusNotice > span").html("Упс... Ошибка. Что-то пошло не так");

					setTimeout(()=>{
						$(".actionStatusNotice").css("opacity", "0");
					}, 1500);
				},
			});
		}
		else if(sessionStorage.property === "Добавление")
		{
			$.ajax({
				url: '../actions/insertItems.php',
				type: 'POST',
				dataType: 'JSON',
				data: dataCollection,
				success: function(data){
					console.log(data.insertStatus);
					let newRowNumber = $("tr").length;
					let IDCorrect = data.insertedAmount;
					let IDCorrecting = data.insertedItemId;
					for(let i = 0; i < data.insertedAmount; i++)
					{
						IDCorrecting = data.insertedItemId;
						IDCorrecting -= IDCorrect - 1 - i;
						$("#itemsTable tbody").append(`
							<tr id="row_${newRowNumber}">
								<td class="numberCell" id="numberCell_${newRowNumber}">${newRowNumber}</td>
								<td id="nameItemCell_${newRowNumber}">${data.insertedItemName}</td>
								<td id="categoryItemCell_${newRowNumber}">${data.insertedItemCategory}</td>
								<td id="authorItemCell_${newRowNumber}">${data.insertedItemAuthor}</td>
								<td id="publishItemCell_${newRowNumber}">${data.insertedItemPublish}</td>
								<td id="statusItemCell_${newRowNumber}">${data.insertedItemStatus}</td>
								<td id="classItemCell_${newRowNumber}">${data.insertedItemClass}</td>
								<td>
									<button class="rowBtn" id="editBtn_${newRowNumber}" data-btnNumber="${newRowNumber}" data-itemId="${IDCorrecting}" data-property="Редактирование">Редактировать</button>
								</td>
								<td>
									<button class="rowBtn" id="delBtn_${newRowNumber}" data-btnNumber="${newRowNumber}" data-itemId="${IDCorrecting}" data-property="Удаление">Удалить</button>
								</td>
							</tr>
						`);
						newRowNumber++;
					}

					$(".actionStatusNotice").css({
						"background": "lightgreen",
						"opacity": "1"
					});
					$(".actionStatusNotice > span").html(data.insertStatus);

					setTimeout(()=>{
						$(".actionStatusNotice").css("opacity", "0");
					}, 1500);
				},
				error: function(data){
					alert(data);
				},
			});
		}
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
});