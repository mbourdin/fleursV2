list=document.getElementById("productList");
baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
function associate(offerId,productId,quantity)
{   quantity=document.getElementById("productNumber").value;
    $.ajax({
        url : baseurl+"admin/offer/associateProduct/"+offerId+"/"+productId+"/"+quantity,
        type : "PUT",
        success : onSuccessAssociate
    });
}

function dissociate(offerId,productId)
{
    $.ajax({
        url : baseurl+"admin/offer/dissociateProduct/"+offerId+"/"+productId,
        type : "PUT",
        success : onSuccessDissociate
    });
}
function onSuccessDissociate(result) {
    document.getElementById("productId"+result).remove();

}
function onSuccessAssociate(result) {
    product=JSON.parse(result);
    line=document.getElementById("productId"+product.id);
    if(line===null)
    {   line=document.createElement("LI");
        var textnode=document.createTextNode(product.name);
        line.setAttribute("id","typeId"+product.id);
        line.appendChild(textnode);
        list.appendChild(line);

    }
}
function add(offerId)
{
    productId=document.getElementById("products").value;
    associate(offerId,productId);
}
function remove(offerId)
{   productId=document.getElementById("products").value;
    dissociate(offerId,productId);

}