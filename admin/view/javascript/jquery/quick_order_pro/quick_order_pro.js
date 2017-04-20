$(document).ready(function() {
						   
	var hash = getHash();
	
	if (hash.hasOwnProperty('tab') && $('#tab-' + hash.tab).length) {
		$("a[href='#tab-" + hash.tab + "']").trigger('click');
	}
	
	$('.slider').live('change', function(event){
		var parent = $(this).closest('.slider-group.parent');
		$(this).closest('tr').next('tr').find('.slider-content:first').slideToggle('fast', function(){
			var icon = ($(this).is(':visible')) ? '&#9650;' : '&#9660;';
			$('.status', parent).html(icon);
		});
	})

});

function getHash() {
	
	var hash = window.location.hash;
	var data = {};
	
	if (hash) {
		
		hash =  hash.substring(1); // remove #
		var hashVars = hash.split('&');
		
		for (var i = 0; i <= (hashVars.length); i++) {
			
			if (hashVars[i]) {
				
				var hashVarsPair = hashVars[i].split('=');
				
				if (hashVarsPair[0]) {
					data[hashVarsPair[0]] = hashVarsPair[1];
				}
			}
		}
		
		
	}
	
	return data;
}