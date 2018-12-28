<?php ob_start(); ?>
<html>
<head>
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"> </script>
    <script src="./admin.js"> </script>
</head>

<?php
session_start();
header("Content-Type:text/html;charset=utf-8");
include_once 'connect.php';
$con->query('set names utf8');

if (isset($_SESSION['login']) && $_SESSION['login']==true){
}else{
    header("Location:show.php");
}
$admin=$_SESSION['admin'];

$sql_pageNum = "SELECT `id` ,`parent_id` FROM `message` where `parent_id`=0 ";
$con->query($sql_pageNum);
$num = $con->affected_rows; //记录总条数
$pageSize = 5; //每页记录数
$endPage = ceil($num/$pageSize); //总页数
$pageNum = isset($_GET['pageNum']) ? $_GET['pageNum'] : 1;  //当前页设置


show($con,$pageNum,$pageSize);

if ($pageNum==$endPage){
    reply();
}

if (isset($_POST['message'])){
    insert($con,$_POST['message'],$_POST['nickname'],$_POST['parent_id']);
}

if (isset($_GET['id'])){
    delete($con,$_GET['id']);

}

function show($con,$pageNum,$pageSize)
{
    $p = ($pageNum - 1) * 5;
    $sql1 = "select `content`,`id`,`createtime`,`nickname` from `message` where `parent_id`=0 limit  {$p} ,{$pageSize} ";
    $result1 = $con->query($sql1);

    while ($row1 = $result1->fetch_assoc()) {
        echo $row1['nickname'] . ":" . $row1['content'], "\n", date('Y-m-d H:i', $row1['createtime']);
        echo "<button name='button' onclick='del({$row1['id']})'>删除</button>"."<br>";
        $sql2 = "select `content`,`id`,`createtime`,`nickname` from `message` where `parent_id`={$row1['id']}";
        $result2 = $con->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
            echo $row2['nickname'] . "回复" . $row1['nickname'] . "：“" . $row2['content'] . "”" . "\n",
                date('Y-m-d H:i', $row2['createtime']);
            echo "<button name='button' onclick='del({$row2['id']})' >删除</button>"."<br>";


        }

        reply($row1['id']);
        echo "<hr>";
    }


}


function reply($id=0)
{

    echo      "<form   id='$id' method='post' hidden >
              <input type='text' name='message'>
               <input type='text' name='nickname' value='管理员' hidden>
               <input type='text' name='parent_id' value={$id} hidden>
               <input type='submit' value='发送' onclick='insert({$id})'>
               </form>";
    echo       "<button  onclick='showform($id)'>回复</button>";
}

function insert($con,$content,$nickname,$id){
    $time=time();
    $sql="insert into `message`(`parent_id`,`content`,`nickname`,`createtime`)
     values ({$id},'{$content}','{$nickname}',{$time})";
    if($con->query($sql)){
        header("Location:admin.php");
    }else{
        echo $con->error;
    }
}

function delete($con,$id){

    $sql="delete from `message` where `id`={$id} ";   //根据id删除记录
    $con->query($sql);
   $sqlc="delete from `message` where `parent_id`={$id}";  //如果id是留言发起者的id  该留言下的所有记录删除
    $con->query($sqlc);


}
print_r($_SESSION['id']);
echo session_id();


?>


    <div>
        <a href='?pageNum=1'>首页</a>
        <a href='?pageNum=<?php echo $pageNum==1?1:($pageNum-1)?>'>上一页</a>
        <a ><?php echo $pageNum ?> </a>
<a href='?pageNum=<?php echo $pageNum==$endPage?$endPage:($pageNum+1)?>'>下一页</a>
<a href='?pageNum=<?php echo $endPage?>'>尾页</a>
</div>

</html>

