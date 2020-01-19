function arrived()
{
    $.ajax({
        url:"/arrived",
        method:"POST",
        data:{arrived: true},
        success:function () {
            window.location.href = "/loginRedirect";
        }
    })
}
$("#arrived").click(function () {
    arrived();
});