<?php
session_start();
// បើមានការចុចដូរភាសា ឱ្យកត់ត្រាចូល Session
if(isset($_GET['lang'])){
    if($_GET['lang'] == 'kh') {
        $_SESSION['lang'] = 'kh';
    } else {
        $_SESSION['lang'] = 'en';
    }
}
// ចុចដូរហើយ ឱ្យវាត្រឡប់ទៅទំព័រដើមវិញ
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>