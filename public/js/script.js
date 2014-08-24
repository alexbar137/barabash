var SITE_URL = "http://localhost/barabash";

$(document).ready(function() {
	//Main menu
	$('#header_items li').hover(function() {
		$(this).children('ul').slideToggle("fast");
	})
	$('#header_items li').mouseleave(function() {
		$(this).children('ul').slideUp("fast");
	})
	
})


//Check user name and password
function check_login() {
	var input_user_name = $("input[name='user_name']").val();
	var input_pass = $("input[name='pass']").val();
	$.ajax({
		url: SITE_URL + "/auth/check_login",
		async: false,
		type: "POST",
		data: {user_name: input_user_name, pass: input_pass},
		success: function(msg) {
            console.log(msg);
			if (msg != 1) {
				$('#error_space').text("Неверное имя пользователя или пароль");
			}
			
			else {
				window.location = SITE_URL;
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
		  alert(textStatus + " " + errorThrown);
		}
	});
}


//Data validation block

//Create error messages
function create_message(name, position, text) {
		$("#" + name).remove();
		$(position).
			parent("td").
				after("<div id='" + name + "' class='message'></div>");
		$("#" + name).text(text);
}

//Check in users' array if user_name exists
function user_exists() {
	var check_name = true;
	var input_user_name = $("input[name=user_name]").val();
	$.ajax({
		url: SITE_URL + "/user/user_exists",
		async: false,
		type: "POST",
		data: {user_name: input_user_name},
		success: function(msg) {
			if (msg == 1) 
			{
				create_message('name_occup', 'input[name=user_name]', 'Это имя пользователя уже занято');
				check_name = false;
			} else
			{
				$('#name_occup').remove();
			};
		},
		error: function(jqXHR, textStatus, errorThrown) {
		  alert(textStatus + " " + errorThrown);
		}
	});
	return check_name;
}

//Check password and confirm password
function pass_match() {
	var check_pass = true;
	$("#pass_empty").remove();
	$("#confirm_pass_empty").remove();
	$("#pass").remove();
	var pass = $('input[name=pass]').val();
	var confirm_pass = $('input[name=confirm_pass]').val();
	//Check if password is empty
	if (pass == "") {
		create_message('pass_empty', 'input[name=pass]', 'Введите пароль');
		check_pass = false;
	}
	//Check if confirm password is empty
	if (confirm_pass == "") {
		create_message('confirm_pass_empty', 'input[name=confirm_pass]', 'Подтвердите пароль');
		check_pass = false;
	}
	
	//Check if passwords match;
	if ((confirm_pass != "") && (pass != "") && (pass != confirm_pass))	{
		create_message('pass', 'input[name=confirm_pass]', 'Пароли не совпадают');
		check_pass = false;
	}
	return check_pass;
}

//Check if data can be submitted
function register_validate_data() {
	var user = user_exists();
	var pass = pass_match();
	if (user && pass) {
		return true;
	}
	else {
		return false;
	}
}

//Validate user name
function validate_name(name, position) {
	if(/[^a-zа-я\._]/i.test(name)) {
		create_message('error', position, "Это поле может содержать только буквы, точки и символы подчеркивания");
		return false;
	}
	return true;
}

//Validate email correctness
function validate_email(email, position) {
	if (!(/.+@.+\..+/i.test(email))) {
		create_message('error', position, "Проверьте адрес электронной почты");
		return false;
	}
	return true;
}

function validate_age(age, position) {
	if (isNaN(age)) {
		create_message('error', position, "Это поле может содержать только цифры");
		return false;
	}
	else if (age < 0 || age > 150) {
		create_message('error', position, "Введите действительный возраст");
		return false;
	}
	return true;
}


//Check if profile data can be updated
function edit_validate_data() {
	$('.message').remove();
	var first_name = $('input[name=first_name]').val();
	var middle_name = $('input[name=middle_name]').val();
	var last_name = $('input[name=last_name]').val();
	var first_check = validate_name(first_name, 'input[name=first_name]');
	var middle_check = validate_name(middle_name, 'input[name=middle_name]');
	var last_check = validate_name(last_name, 'input[name=last_name]');
	var email = $('input[name=email]').val();
	var email_check = validate_email(email, 'input[name=email]');
	var age = $('input[name=age]').val();
	var age_check = validate_age(age, 'input[name=age]');
	
	return (first_check && middle_check && last_check && email_check && age_check);
}

function validate_article() {
    $('.message').remove();
    var check = true;
    if($('input[name=article_title]').val() == "")
    {
        create_message('title_error', 'input[name=article_title]', "Введите заголовок");
        check = false;
    }
    if($('select[name=article_category]').val() == "None")
    {
        create_message('category_error', 'select[name=article_category]', "Выберите категорию");
        check = false;
    }
        if($('textarea[name=article_short_desc]').val() == "")
    {
        create_message('desc_error', 'textarea[name=article_shord_desc]', "Введите краткое описание");
        check = false;
    }
        if($('textarea[name=article_content]').val() == "")
    {
        create_message('content_error', 'textarea[name=article_content]', "Введите содержимое статьи");
        check = false;
    }
    
    return check;
}

