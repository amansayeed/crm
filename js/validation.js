function characterKeyDownWithNumbers(e)
{
    if (e.ctrlKey || e.altKey || (65<=e.keyCode && e.keyCode<=90) || (47<e.keyCode && e.keyCode<58 && e.shiftKey==false) || (95<e.keyCode && e.keyCode<106) || (e.keyCode==8) || (e.keyCode==32) || (e.keyCode==9) || (e.keyCode>34 && e.keyCode<40) || (e.keyCode==46)) {
        return true;
    } else {
        return false;
    }
}

function numberWithoutDecimalforkeydown(e)
{
	if (e.ctrlKey || e.altKey || (47<e.keyCode && e.keyCode<58 && e.shiftKey==false) || (95<e.keyCode && e.keyCode<106)
		|| (e.keyCode==8) || (e.keyCode==9) || (e.keyCode>34 && e.keyCode<40) || (e.keyCode==46)) {
		return true;
	} else {
		return false;
	}
}

function requestDemoAds() {
	$flag = 0;
	if($('#name').val() == '') {
		$('#name').addClass('red_border');
		$flag = 1;
	}else {
		$('#name').removeClass('red_border');
	}
	if($('#email').val() == '') {
		$('#email').addClass('red_border');
		$flag = 1;
	}else {
		$('#email').removeClass('red_border');
	}
	if($('#phone').val() == '') {
		$('#phone').addClass('red_border');
		$flag = 1;
	}else {
		$('#phone').removeClass('red_border');
	}
	if($('#requirements').val() == '') {
		$('#requirements').addClass('red_border');
		$flag = 1;
	}else {
		$('#requirements').removeClass('red_border');
	}
	if($('#captcha_entered').val() == '') {
		$('#captcha_entered').addClass('red_border');
		$flag = 1;
	}else {
		$('#captcha_entered').removeClass('red_border');
	}
	if($flag != 0) {
		$('#errorBox').html('Fill the mandatory fields ...');
		$('#errorBox').css('display','block');
		return false;
	} else {
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var address = $('#email').val();
		if(reg.test(address) == false) 
		{
			$('#errorBox').html('Invalid email address ...');
			$('#errorBox').css('display','block');
			$('#email').addClass('red_border');
			$flag = 1;
			return false; 	   
		} else if($("#phone").val().length != 10) {
			$('#errorBox').html('Phone number should be 10 digit ...');
			$('#errorBox').css('display','block');
			$('#phone').addClass('red_border');
			$flag = 1;
			return false;
		} else if($("#captcha_entered").val() != $("#captcha_total").val()) {
			$('#errorBox').html('Please check the capcha value ...');
			$('#errorBox').css('display','block');
			$('#captcha_entered').addClass('red_border');
			$flag = 1;
			return false;
		}
		if($flag == 0) {
			$('#errorBox').css('display','none');
			$.post('sendmail.php', {name:$('#name').val(), email:$('#email').val(), phone:$('#phone').val(), requirements:$('#requirements').val(), captcha_entered:$('#captcha_entered').val(), captcha_total:$("#captcha_total").val(), contactForm:"YES" },	function(data){
				if(data == 'success') {
					alert("Your enquiry has been mailed successfully.");
					$('form').clearForm();
				} else if(data == 'captcha') {
					alert("please check the captcha value.");
				} else {
					alert("Your enquiry send failed, Please try again.");
				}
			});
		}
		return false;
	}
	return false;
}

$.fn.clearForm = function() {
  return this.each(function() {
    var type = this.type, tag = this.tagName.toLowerCase();
    if (tag == 'form')
      return $(':input',this).clearForm();
    if (type == 'text' || type == 'password' || tag == 'textarea')
      this.value = '';
    else if (type == 'checkbox' || type == 'radio')
      this.checked = false;
    else if (tag == 'select')
      this.selectedIndex = -1;
  });
};