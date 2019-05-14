list=document.getElementById("typeList")
baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
function associate(productId,typeId)
{
    $.ajax({
        url : baseurl+"admin/product/associateType/"+productId+"/"+typeId,
        type : "PUT",
        success : onSuccessAssociate
    });
}

function dissociate(productId,typeId)
{
    $.ajax({
        url : baseurl+"admin/product/dissociateType/"+productId+"/"+typeId,
        type : "PUT",
        success : onSuccessDissociate
    });
}
function onSuccessDissociate(result) {
    document.getElementById("typeId"+result).remove();

}
function onSuccessAssociate(result) {
    obj=JSON.parse(result)
    line=document.getElementById("typeId"+obj.id);
    if(line===null)
    {   line=document.createElement("LI");
        var textnode=document.createTextNode(obj.name);
        line.setAttribute("id","typeId"+obj.id);
        line.appendChild(textnode);
        list.appendChild(line);

    }
}
function add(productId)
{
    typeId=document.getElementById("productTypes").value;
    associate(productId,typeId);
}
function remove(productId)
{   typeId=document.getElementById("productTypes").value;
    dissociate(productId,typeId);

}