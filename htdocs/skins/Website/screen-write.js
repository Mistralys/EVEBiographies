var ScreenWrite = 
{
	ChangeValue:function(storageEl)
	{
		var value = storageEl.val();
		
		$('#'+storageEl.attr('thumbs-container')+' .thumb').removeClass('selected');
		$('#'+storageEl.attr('thumbs-container')+' .thumb[data-value="'+value+'"]').addClass('selected');
	},

	SelectThumb:function(thumbEl)
	{
		var target = $('#'+thumbEl.attr('data-target'));
		var value = thumbEl.attr('data-value');
		
		target.val(value).change();
	}
};