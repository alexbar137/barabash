<?php
	$message = $this->display['input']['messages'];
    $users = $this->display['input']['users'];
?>
<h1>Сообщение: <?php echo $message['message']->subject; ?></h1>
<?php
	function message($input, $users) {
    	$message = $input['message'];
        $replies = $input['replies'];
        $nesting = $input['nesting']*75;
        $sender = $users[$message->from_name];
        $receiver = $users[$message->to_name];
    	
?>
        <div class='msg' style="margin-left: <?php echo $nesting; ?>px" message_id="<?php echo $message->id; ?>">
            <!--User Avatar-->
            <img src="<?php echo $sender->small_image ;?>" alt="<?php echo $sender->user_name; ?>" title="<?php echo $sender->user_name; ?>" class="user-avatar"/>

            <div class="msg-header">

            <table>

            <!--From-->
            <tr>
            <td><b>От:</b></td>
            <td><b><?php echo $sender->user_name; ?></b></td>
            </tr>
            <!--Subject-->
            <tr>
            <td><b>Тема:</b></td>
            <td><b><div class="msg-text"><?php echo $message->subject; ?></div></b></td> 
            </tr>

            <!--Sent date&time-->
            <tr>
            <td><b>Отправлено:</b> </td>
            <td><b><div class="msg-text"><?php echo $message->send_date;?> в <?php echo $message->send_time;?></div></b></td>
            </tr>

            </table>
            </div>

            <!--Body text-->
            <p><?php echo $message->body; ?></p>
            <span class="reply-link"><b>Ответить</b></span>
        </div>
<?php
		if (!empty($replies))
        {
        	foreach($replies as $reply)
            {
            	message($reply, $users);
            }
        }
    }
    message($message, $users);
    
    
?>
