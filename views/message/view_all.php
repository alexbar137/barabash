<h1>Личные сообщения</h1>
<?php
	$messages = $this->display['input']['messages'];
	$users = $this->display['input']['users'];
    foreach($messages as $message)
    {
    	$to_id = $message->to_name;
        $recepient = $users[$to_id];
        $from_id = $message->from_name;
        $sender = $users[$from_id];
?>
<div class='msg'>
<a href="<?php echo URL."/message/show/".$message->id; ?>">
	<!--User Avatar-->
    <a href="<?php echo URL."/user/show/".$from_id; ?>" target="_blanc"><img src="<?php echo $sender->small_image ;?>" alt="<?php echo $sender->user_name; ?>" title="<?php echo $sender->user_name; ?>" class="user-avatar"/></a>
    
    <div class="msg-header">
    
    <table>
    
    <!--From-->
    <tr>
    <td><b>От:</b></td>
    <td><b><a href="<?php echo URL."/user/show/".$from_id; ?>" target="_blanc"><?php echo $sender->user_name; ?></a></b></td>
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
    <!--Shortened body text-->
    <p><a href="<?php echo URL."/message/show/".$message->id; ?>"><?php echo $message->body; ?></a></p>
    </div>

</a>
</div>
<?php
	}
?>
