$("tr.tron").mouseover(function(){
	$(this).find("td").css("backgroundColor", "#F1FAFC");
});
$("tr.tron").mouseout(function(){
	$(this).find("td").css("backgroundColor", "#FFF");
});