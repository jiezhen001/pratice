<?php ob_start(); ?>
<html>
<form action="login.php" method="post">
    管理员<input type="text" name="admin">
    密码  <input type="password" name="password">
    <input type="submit" name="submit" value="登录">
</form>
</html>

<?php

include_once 'connect.php';
if(isset($_POST['submit'])) {               //收到表单提交 判断用户 密码是否正确
checkPwd($con,addslashes($_POST['admin']),addslashes($_POST['password']));
}


 function checkPwd($con,$admin,$password){
     $sql = "select `admin`,`password` from `admin` where `admin`= '{$admin}'";
     $result = $con->query($sql);
     $num = $con->affected_rows;
     $pwd = $result->fetch_assoc()['password'];

     if ($num!==1) {
         echo "<script>alert('用户名不正确');</script>";
     } elseif ($password !== $pwd) {
         echo "<script>alert('密码不正确');</script>";
     } else {
         session_start();             // 生成SESSION
         $_SESSION['login']=true;
         $_SESSION['admin']=$admin;
         echo "<script> 
                 alert('登录成功');
                window.location.href='./admin.php';        //跳转到admin页面
                 </script>";
     }
 }


