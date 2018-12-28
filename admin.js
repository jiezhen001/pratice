

    function showform(id){
        var x=document.getElementById(id);
        x.removeAttribute("hidden");
    }

function del(id) {
    $.ajax({
        type: "GET",
        url: "./admin.php?action=del&id="+id ,
        success: function () {
            alert('删除成功');
            window.location.href='./admin.php';
        },
        error : function() {
            alert('删除失败');
        }
    });
}
