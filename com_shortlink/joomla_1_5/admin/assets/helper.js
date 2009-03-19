var onMoveHelperFile = function(path, file, info) {
	var url = "index.php?option=com_shortlink&format=raw&task=rename";
	url += "&target_path="+path.value;
	url += "&target_file="+file.value;
	
	ajax=new Ajax(url,{
		method:'get',
		onRequest:function(){info.setHTML('please wait...')},
		onComplete:function(response){
			info.setHTML('done!');
			alert('text '+response);
		}
	});
	
	ajax.request();
};
