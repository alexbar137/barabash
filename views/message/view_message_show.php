<div class='msg' style="margin-left: %%NESTING%%px" data-code="%%MESSAGE_ID%%">
            <!--User Avatar-->
            <a href="%%SENDER_PROFILE%%" target="_blanc">
            <img src="%%SENDER_IMAGE%%" alt="%%SENDER_NAME%%" title="%%SENDER_NAME%%" class="user-avatar"/>
            </a>

            <div class="msg-header">

            <table>

            <!--From-->
            <tr>
            <td><b>От:</b></td>
            <td><b><a href="%%SENDER_PROFILE%%" target="_blanc" class="msg-sender" data-sender="%%SENDER_ID%%">%%SENDER_NAME%%</a></b></td>
            </tr>
            <!--From-->
            <tr>
            <td><b>Кому:</b></td>
            <td><b><a href="%%RECEIVER_PROFILE%%" target="_blanc" class="msg-sender" data-receiver="%%RECEIVER_ID%%">%%RECEIVER_NAME%%</a></b></td>
            </tr>
            <!--Subject-->
            <tr>
            <td><b>Тема:</b></td>
            <td><b><div class="msg-subj" class="msg-text" data-subj="%%SUBJECT%%">%%SUBJECT%%</div></b></td> 
            </tr>

            <!--Sent date&time-->
            <tr>
            <td><b>Отправлено:</b> </td>
            <td><b><div class="msg-text">%%SEND_DATE%% в %%SEND_TIME%%</div></b></td>
            </tr>

            </table>
            </div>

            <!--Body text-->
			<div class="body-txt"><p>%%BODY%%</p></div>
            %%REPLY%%
            %%EDIT%%
        </div>