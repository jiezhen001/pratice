<?php ob_start(); ?>
<html>
<head>
    <title>留言板</title>
<meta content="text/html" charset="UTF-8">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="show.js"></script>

</head>
    <body>

       <div>
    <a href='./login.php'> 管理员登陆 </a>
       </div>

<?php
header("Content-Type:text/html;charset=utf-8");
include_once 'connect.php';
$con->query('set names utf8');


$sql_pageNum = "SELECT `parent_id` FROM `message` where `parent_id`=0 ";  //根据留言发起者进行留言数统计
$con->query($sql_pageNum);
$num = $con->affected_rows;  //记录总条数
$pageSize = 5;   //设定每页记录数
$endPage = ceil($num/$pageSize);    //得出总页数
$pageNum = isset($_GET['pageNum']) ? $_GET['pageNum'] : 1;   //当前页


  show($con,$pageNum,$pageSize);
  function show($con,$pageNum,$pageSize)
  {
      $p = ($pageNum - 1) * 5;
      $sql1 = "select `content`,`id`,`createtime`,`nickname` from `message` where `parent_id`=0 limit  {$p} ,{$pageSize} ";
      $result1 = $con->query($sql1);

      while ($row1 = $result1->fetch_assoc()) {
          echo $row1['nickname'] . ":" . $row1['content'], "\n", date('Y-m-d H:i', $row1['createtime']) . "<br>";
          $sql_cid = "select `content`,`id`,`createtime`,`nickname` from `message` where `parent_id`={$row1['id']}";
          $result2 = $con->query($sql_cid);
          while ($row2 = $result2->fetch_assoc()) {
              echo $row2['nickname'] . "回复" . $row1['nickname'] . "：“" . $row2['content'] . "”" . "\n",
                  date('Y-m-d H:i', $row2['createtime']) . "<br>";
          }
          reply($row1['id']);            //每个留言的发起者下方会有一个回复按钮  点击可以对发起者的留言进行回复
          echo "<hr>";
      }


  }


       if ($pageNum==$endPage){          // 判断是否在留言页末尾   并选择是否添加回复按钮
           reply();
       }

    if (isset($_POST['message'])){                       // 判断是否有表单信息提交 有则插入数据库
       insert($con,$_POST['message'],$_POST['nickname'],$_POST['parent_id']);
   }

  /* if (isset($_POST['name'])){                          //有待改进
       search($_POST['name']);
   }*/



     function reply($id=0)                    //点击回复按钮 会调用此函数  输出表单 （此时不可见）
     {

         echo "<form  id='$id'  method='post' hidden >
               <input type='text' name='message'>
               <input type='text' name='nickname' >
               <input type='text' name='parent_id' value={$id} hidden>
               <input type='submit' value='发送' onclick='insert({$id})'>
               </form>";
         echo "<button  onclick='showform($id)'>回复</button>";   //根据id 加载需要回复的表单

     }


    function insert($con,$content,$nickname,$id){
        $time = time();
        $sql  = "insert into `message`(`parent_id`,`content`,`nickname`,`createtime`)
                  values ({$id},'{$content}','{$nickname}',{$time})";
        if  ($con->query($sql)){
             header("Location:show.php");
             exit();
        }else{
            echo $con->error;
        }
    }

    /*function search($search){       //有待改进模块
     return $search;
    }*/
    ?>
    

    <div>
        <a href="?pageNum=1">首页</a>
        <a href="?pageNum=<?php echo $pageNum==1?1:($pageNum-1)?>">上一页</a>
        <a ><?php echo $pageNum ?> </a>
        <a href="?pageNum=<?php echo $pageNum==$endPage?$endPage:($pageNum+1)?>">下一页</a>
        <a href="?pageNum=<?php echo $endPage?>">尾页</a>
    </div>
    </body>
</html>

