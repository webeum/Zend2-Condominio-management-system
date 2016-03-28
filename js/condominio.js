$(document).ready(function() 
{
		$(function()
	{
	    var config = {
	        toolbar:
	        [
	          ['Source'],
		      ['Cut','Copy','Paste','PasteText','-','SpellChecker', 'Scayt'],
		      ['Undo','Redo','Find','Replace','-','SelectAll','RemoveFormat'],
		      ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		      ['Format','Font','FontSize'],
		      ['NumberedList','BulletedList','-','Blockquote'],
		      ['Image','HorizontalRule','Smiley','SpecialChar'],
		      ['Link','Unlink','Anchor','LinkToNode', 'LinkToMenu'],      
		      ['TextColor','BGColor'],
		      ['Maximize', 'ShowBlocks']
	        ]
	    };
	 
	    $('.jquery_ckeditor').ckeditor(config);
	});

	header_width = document.getElementById('header').offsetWidth;
    menu_height = document.getElementById('menu').offsetHeight;
    body_height = document.getElementById('body').offsetHeight;

    //alert(header_width);

	if(header_width >= 980 && body_height > menu_height)
	{
		document.getElementById('menu').style.height = body_height + 80 + "px";
	}
	//menu_height = document.getElementById('menu').offsetHeight;
	//alert(menu_height);
});
