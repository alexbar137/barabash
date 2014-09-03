<h2>Вы точно хотите удалить пользователя <?php echo $this->display['user_name'];?>?</h2>
<table>
	<tr>
		<td>
			<a href="<?php echo URL;?>/user/delete_do/<?php echo $this->display['id'];?>"><input type="button" name="Yes" value="Да"></a>
		</td>
		<td>
		</td>
		<td>
			<a href="<?php echo URL;?>/user/all"><input type="button" name="No" value="Нет"></a>
		</td>
	</tr>
</table>