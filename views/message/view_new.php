<div id="new-message">
<form action="/message/add_message" method="POST" onsubmit="return validate_new_message();">
	<td><select id="receiver" name="receiver" size='1' class='new-message-control'>
    <option selected>Выберите получателя</option>
    	<?php
        	foreach($this->display['users'] as $user)
            {
            	if($user->id != $_SESSION['user_id'])
                {
        ?>
        	<option value="<?php echo $user->id;?>"><?php echo $user->user_name; ?></option>
        <?php
        		}
        	}
        ?>
    </select></td>
    <br/>
    <input type="text" id="subject" name="subject" placeholder="Введите тему" class='new-message-control'/>
    <br/>
    <textarea id="body" name="body" placeholder="Введите текст сообщения" class='new-message-control' rows="10"></textarea>
    <input type="submit" id="new-msg-send" name="new-msg-send" class="new-msg-btn" value="Отправить"/>
    <a href="/message/all"><input type="button" id="cance" name="cancel" class="new-msg-btn" value="Отмена" /></a>
</form>
</div>