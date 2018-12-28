function showform(id){
    var x=document.getElementById(id);
    x.removeAttribute("hidden");
}

/*
function search(){
    var x=$("#search").val();
    $.ajax({
        type: "POST",//方法
        url: "./show.php" ,//
        data: "name="+x,

        success: function (search) {
            alert(search);
        },
        error : function() {
            alert('查询失败');
        }
    });
}*/
