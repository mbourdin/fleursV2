$(document).ready(function(){
    $("button").click(function(event){
        $(this).removeClass("btn-primary").addClass("btn-success");
        console.log("click");
    });
});