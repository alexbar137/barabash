var SITE_URL = "http://miracle-number.codio.io:3000";

$(document).ready(function() {
	//Main menu
	$('#header_items li').hover(function() {
    	$(this).stop();
		$(this).children('ul').slideToggle("fast");
	})
	$('#header_items li').mouseleave(function() {
    	$(this).stop();
		$(this).children('ul').slideUp("fast");
	})
    
    //Display link to change avatar
    $('#avatar-wrapper').hover(
        function() {
            $('#changeAvatar').fadeIn("slow", function() {});
        },
        function() {
            $('#changeAvatar').fadeOut("slow", function() {});
        }
    );
    
    
    //Create reply textarea
    $('.reply-link').click(function(){
    	reply($(this));
    });
    
    
    //Create edit textarea
    $('.edit-link').click(function(){
    	edit($(this));
    });    
})

//Show edit text area
function edit(input) {
    input.hide();
    var main_message = input.parent();
    var message_content = main_message.find('.body-txt').text();
    input.parent().append('<div id="edit"></div>');
    $('#edit').append('<textarea name="edit-txt" id="edit-txt" class="edit-text" class="display: none" cols=60 rows=10></textarea>');
    $('#edit-txt').val(message_content);
    $('#edit').append('<input type="button" name="send_edit" id="send_edit" value="Отправить"/>');
    $('#edit').append('<input type="button" name="cancel" id="edit_cancel" value="Отмена";"/>');
    main_message.find('.body-txt').hide();
    
    $('#edit_cancel').on('click', function(event) {
    	event.stopPropagation();
        event.preventDefault();
    	location.reload();
	});
    
    $('#send_edit').on('click', function(event) {
    	event.stopPropagation();
        event.preventDefault();
        var message = $(this).parents(':eq(1)');
        var message_id = message.data('code');
        var output = {
        	message_id: message_id,
            body: $('#edit-txt').val()
        };
        
        send_edit(output);
    });
    
    $('.edit-link').off('click');
}

//Send edit and reload page if send is successfull
function send_edit(input) {
	$.ajax({
    	url: SITE_URL + "/message/edit",
        async: true,
        type: "POST",
        data: 
        {
        	message_id: input.message_id, 
            body: input.body,
        },
        success: function() {
        	location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
		  alert(textStatus + " " + errorThrown);
		}
    });
}


//Show reply text area
function reply(input) {
    input.hide();
    var message_id = input.parent('.msg').attr('message_id');
    input.parent().append('<div id="reply"></div>');
    $('#reply').append('<textarea name="reply-txt" id="reply-txt" class="reply-text" class="display: none" cols=60 rows=10></textarea>');
    $('#reply').append('<input type="button" name="send_reply" id="send_reply" value="Отправить"/>');
    $('#reply').append('<input type="button" name="cancel" id="reply_cancel" value="Отмена";"/>');
    
    $('#reply_cancel').on('click', function(event) {
    	event.stopPropagation();
        event.preventDefault();
    	location.reload();
	});
    
    $('#send_reply').on('click', function(event) {
    	event.stopPropagation();
        event.preventDefault();
        var message = $(this).parents(':eq(1)');
        var output = {
        	message: message,
        	in_reply_to: message.data('code'),
            subject: message.find('.msg-subj').data('subj'),
            receiver: message.find('.msg-sender').data('sender'),
            body: $('#reply-txt').val()
        };
        
        send_reply(output);
    });
    
    $('.reply-link').off('click');
}

//Send reply and reload page if send is successfull
function send_reply(input) {
	$.ajax({
    	url: SITE_URL + "/message/add_reply",
        async: true,
        type: "POST",
        data: 
        {
        	subject: input.subject, 
            body: input.body, 
            receiver: input.receiver,
            in_reply_to: input.in_reply_to,
            nesting: input.nesting
        },
        success: function() {
        	location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
		  alert(textStatus + " " + errorThrown);
		}
    });
}

//Check user name and password
function check_login() {
	var input_user_name = $("input[name='user_name']").val();
	var input_pass = $("input[name='pass']").val();
	$.ajax({
		url: SITE_URL + "/auth/check_login",
		async: true,
		type: "POST",
		data: {user_name: input_user_name, pass: input_pass},
		success: function(msg) {
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
				after("<div id='" + name + "' class='message'></div>");
		$("#" + name).text(text);
}

//Check in users' array if user_name exists
function user_exists() {
	var check_name = true;
	var input_user_name = $("input[name=user_name]").val();
    if(!input_user_name)
    {
        create_message('name_missing', 'input[name=user_name]', 'Введите имя пользователя');
        return false;
    }
    $('#name_missing').remove();
	$.ajax({
		url: SITE_URL + "/user/user_exists",
		async: true,
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


//Check if account with the same email exists
function email_exists() {
	var check_email = true;
	var input_email = $("input[name=email]").val();
    if(!input_email)
    {
        create_message('email_missing', 'input[name=email]', 'Введите адрес электронной почты');
        return false;
    }
    $('#email_missing').remove();
	$.ajax({
		url: SITE_URL + "/user/email_exists",
		async: true,
		type: "POST",
		data: {email: input_email},
		success: function(msg) {
			if (msg == 1) 
			{
				create_message('email_occup', 'input[name=email]', 'Учетная запись с таким адресом электронной почты уже существует.');
				check_email = false;
			} else
			{
				$('#email_occup').remove();
			};
		},
		error: function(jqXHR, textStatus, errorThrown) {
		  alert(textStatus + " " + errorThrown);
		}
	});
	return check_email;
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
    var email = email_exists();
	if (user && pass && email) {
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

function validate_new_message() {
	$receiver = $('#receiver').val();
    console.log($receiver);
    alert("Stop");
	if(isNaN($('#receiver').val()))
    	{
    	create_message('no_receiver', '#receiver', "Выберите получателя");
        return false;
        }
    return true;
}
