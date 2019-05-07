baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
function submitban(id) {
    checkboxstatus=document.getElementById("checkbox"+id).checked;
    console.log(checkboxstatus);
    if (checkboxstatus){ban(id)}
    else unban(id);
}
function ban(id)
{
    console.log(baseurl);

    $.ajax({
        url : baseurl+"admin/ban/"+id,
        type : "PUT"
    });

}
function unban(id)
{
    $.ajax({
        url : baseurl+"admin/unban/"+id,
        type : "PUT"
    });
}
function submitdelete(id) {
    checkboxstatus=document.getElementById("checkboxdelete"+id).checked;
    console.log(checkboxstatus);
    if (checkboxstatus){deleteuser(id)}
    else undelete(id);
}
function deleteuser(id)
{
    $.ajax({
        url : baseurl+"admin/delete/"+id,
        type : "PUT"
    });
}
function undelete(id)
{
    $.ajax({
        url : baseurl+"admin/undelete/"+id,
        type : "PUT"
    });
}
function submitrights(id)
{   rights=document.getElementById("rights"+id).value;
    $.ajax({
        url : baseurl+"admin/setUserRights/"+id+"/"+rights,
        type : "PUT"
    });
}