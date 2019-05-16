list=document.getElementById("productList");
baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
function associate(serviceId,productId)
{   let quantity=document.getElementById("productNumber").value;
    $.ajax({
        url : baseurl+"admin/service/associateProduct/"+serviceId+"/"+productId+"/"+quantity,
        type : "PUT",
        success : onSuccessAssociate
    });
}

function dissociate(serviceId,productId)
{
    $.ajax({
        url : baseurl+"admin/service/dissociateProduct/"+serviceId+"/"+productId,
        type : "PUT",
        success : onSuccessDissociate
    });
}
function onSuccessDissociate(result) {
    document.getElementById("productId"+result).remove();

}
function onSuccessAssociate(result) {
    content=JSON.parse(result);
    line=document.getElementById("productId"+content.product.id);
    if(line===null) {
        line = document.createElement("LI");
        var textnode = document.createTextNode(document.getElementById("productNumber").value + " " + content.product.name);
    }line.setAttribute("id","productId"+content.product.id);
    line.appendChild(textnode);
    list.appendChild(line);


}
function add(serviceId)
{
    productId=document.getElementById("products").value;
    associate(serviceId,productId);
}
function remove(serviceId)
{   productId=document.getElementById("products").value;
    dissociate(serviceId,productId);

}