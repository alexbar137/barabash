<?php
    class Email extends Model
    {
        private $headers;
        
        public function __construct()
        {
            $this->headers = 'Content-type: text/html; charset=UTF-8'."\r\n";
            $this->headers .= "From: ".ADMIN."\r\n";
            $this->headers .= "Reply-To: ".ADMIN."\r\n";
        }
        
        public function UserCreated($user_id)
        {
            require_once "models/model_user.php";
            $to = ADMIN;
            $user = UserModel::read($user_id);
            $user_name = $user->user_name;
            $subj = "Новый пользователь $user_name";
            $msg = 
                "
                <html>
                <head>
                <title>Новый пользователь $user_name</title>
                </head>
                <body>
                <h3>Новый пользователь на сайте</h3>
                <p>На сайте зарегистрировался пользователь $user_name</p>                
                </body>
                </html>
                
                ";
                
            mail($to, $subj, $msg, $this->headers);
        }
        
        public function AdminEmail($user_id, $msg)
        {
            require_once "models/model_user.php";
            $user = UserModel::read($user_id);
            $to = $user->email;
            $name = $user->name;
            $subj = "Сообщение от администратора";
            $msg = nl2br($msg);
            $msg = 
                "
                <html>
                <head>
                <title>Вы получили сообщение от администратора</title>
                </head>
                <body>
                <p>Здравствуйте, <b>$name</b>!</p>
                <p>".$msg."</p>
                <p>С уважением,<br/>администратор сайта</p>               
                </body>
                </html>
                
                ";
                
            mail($to, $subj, $msg, $this->headers);
        }
        
        public function AdminEmailMult ($msg)
        {
            require_once "model_user.php";
            $users = UserModel::all();
            foreach ($users as $user)
            {
                $this->AdminEmail($user['id'], $msg);
            }
        }
    }
?>