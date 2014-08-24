<h1>Добавление статьи</h1>
<form name="Add_article" action="<?php echo URL;?>/article/add" method="POST" onsubmit="return validate_article();">
    <table>
        <tr>
            <td>
                <input name="article_title" type="text" style="width: 400px" autofocus required placeholder="Название статьи"/>
            </td>
        </tr>
        <tr>
            <td>
                <select name="article_category" style="width: 405px" required>
                    <option value="None">Выберите категорию</option>
                    <option value="Software">ПО</option>
                    <option value="Hardware">Оборудование</option>
                    <option value="Cloud">Облачные вычисления</option>
                    <option value="Games">Игры</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <textarea name="article_short_desc" width="75px" rows="3" maxlength="255" style="width: 400px" placeholder="Краткое описание" required></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <textarea name="article_content" placeholder="Содержание статьи" rows="15" style="width: 400px" required></textarea>
            </td>
        </tr>
        <tr>
            <td align="center">
                <input type="submit" value="Отправить"/>
                <a href="<?php echo URL; ?>"><input type="button" value="Отмена"/></a>
            </td>
        </tr>
    </table>
</form>
