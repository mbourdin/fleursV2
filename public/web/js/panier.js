var baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
function updateProductQuantity(id)
{   quantity=document.getElementById("productInput"+id).value;
    $.ajax({
        url: baseurl + "panier/updateQuantity/product/" + id+"/"+quantity,
        type: "PUT",
        success: onSuccessProduct
    });

}
function updateServiceQuantity(id)
{   quantity=document.getElementById("serviceInput"+id).value;
    $.ajax({
        url: baseurl + "panier/updateQuantity/service/" + id+"/"+quantity,
        type: "PUT",
        success: onSuccessService
    });
}
function updateOfferQuantity(id)
{   quantity=document.getElementById("offerInput"+id).value;
    $.ajax({
        url: baseurl + "panier/updateQuantity/offer/" + id+"/"+quantity,
        type: "PUT",
        success: onSuccessOffer
    });
}
function onError(result)
{
    alert(result);
}

function addProduct(id)
{    $.ajax({
    url : baseurl+"panier/rest/addProduct/"+id,
    type : "PUT",
    success : onSuccessProduct
});


}
function rmProduct(id) {
    $.ajax({
        url: baseurl + "panier/rest/rmProduct/" + id,
        type: "PUT",
        success: onSuccessProduct
    });
}
function delProduct(id) {
    $.ajax({
        url: baseurl + "panier/rest/delProduct/" + id,
        type: "DELETE",
        success: onSuccessProduct
    });
}
function onSuccessProduct(result) {
    content = JSON.parse(result);

    if (content.quantity == 0) {
        document.getElementById("productLine" + content.product.id).remove();

    } else {

        //set quantity
        document.getElementById("productInput" + content.product.id).value = content.quantity;
        //set total product
        document.getElementById("productTotal" + content.product.id).textContent = (content.quantity * content.product.price / 100).toFixed(2);

    }
    updateTotal(content.sale.priceTmp);
}
function addService(id)
{    $.ajax({
    url : baseurl+"panier/rest/addService/"+id,
    type : "PUT",
    success : onSuccessService
});


}
function rmService(id) {
    $.ajax({
        url: baseurl + "panier/rest/rmService/" + id,
        type: "PUT",
        success: onSuccessService
    });
}
function delService(id) {
    $.ajax({
        url: baseurl + "panier/rest/delService/" + id,
        type: "DELETE",
        success: onSuccessService
    });
}

function onSuccessService(result) {
    content = JSON.parse(result);

    if (content.quantity == 0) {
        document.getElementById("serviceLine" + content.service.id).remove();

    } else {

        //set quantity
        document.getElementById("serviceInput" + content.service.id).value = content.quantity;
        //set total product
        document.getElementById("serviceTotal" + content.service.id).textContent = (content.quantity * content.service.price / 100).toFixed(2);

    }
    updateTotal(content.sale.priceTmp);
}

function addOffer(id)
{    $.ajax({
    url : baseurl+"panier/rest/addOffer/"+id,
    type : "PUT",
    success : onSuccessOffer
});


}
function rmOffer(id) {
    $.ajax({
        url: baseurl + "panier/rest/rmOffer/" + id,
        type: "PUT",
        success: onSuccessOffer
    });
}
function delOffer(id) {
    $.ajax({
        url: baseurl + "panier/rest/delOffer/" + id,
        type: "DELETE",
        success: onSuccessOffer
    });
}

function onSuccessOffer(result) {
    content = JSON.parse(result);

    if (content.quantity == 0) {
        document.getElementById("offerLine" + content.offer.id).remove();

    } else {
        unitprice=document.getElementById("offerUnitPrice"+content.offer.id).textContent;
        //set quantity
        document.getElementById("offerInput" + content.offer.id).value = content.quantity;
        //set total product
        document.getElementById("offerTotal" + content.offer.id).textContent = (content.quantity * unitprice).toFixed(2);

    }
    updateTotal(content.sale.priceTmp);
}
function updateTotal(total)
{   document.getElementById("totalprice").textContent=(total/100).toFixed(2);
}
