baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
function associate(productId,typeId)
{
    $.ajax({
        url : baseurl+"admin/product/associateType/"+productId+"/"+typeId,
        type : "PUT"
    });
}

function dissociate(productId,typeId)
{
    $.ajax({
        url : baseurl+"admin/product/dissociateType/"+productId+"/"+typeId,
        type : "PUT"
    });
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