var left = 2;
var right = 0;

function slide_left(){
	if(right != 0)
	{
		$("#markup_slider").animate({left: '+=130'}, 'slow');
		
		left += 1;
		right -= 1;
	}
}

function slide_right(){
	if(left != 0)
	{
		$("#markup_slider").animate({left: '-=130'}, 'slow');
		
		right += 1;
		left -= 1;
	}
}