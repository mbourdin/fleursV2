var baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
var addresses=null;
function updateProductQuantity(id)
{   quantity=document.getElementById("productInput"+id).value;
    $.ajax({
        url: baseurl + "user/sale/updateQuantity/product/" + id+"/"+quantity,
        type: "PUT",
        success: onSuccessProduct
    });

}
function updateServiceQuantity(id)
{   quantity=document.getElementById("serviceInput"+id).value;
    $.ajax({
        url: baseurl + "user/sale/updateQuantity/service/" + id+"/"+quantity,
        type: "PUT",
        success: onSuccessService
    });
}
function updateOfferQuantity(id)
{   quantity=document.getElementById("offerInput"+id).value;
    $.ajax({
        url: baseurl + "user/sale/updateQuantity/offer/" + id+"/"+quantity,
        type: "PUT",
        success: onSuccessOffer
    });
}
function submitform()
{
    document.forms["form"].submit();
}

function isEmpty(id)
{   console.log(id);
    val=document.getElementById(id);
    return val.value===undefined || val.value==="" || val.value===-1;
}

function buttonEnabler()
{   button=document.getElementById("submitbutton");
    if
    (
        !isEmpty("recipient")
        &&
        !isEmpty("contact")
        &&
        (    (
                !document.getElementById("radioNew").checked
                &&
                document.getElementById("addressId").value!==undefined

            )
            ||
            (   document.getElementById("radioNew").checked
                &&
                !isEmpty("number")
                &&
                !isEmpty("roadname")
                &&
                !isEmpty("cityOptions")

            )
        )
        &&
        (
        document.getElementById("onlinepay").checked
        ||
        document.getElementById("totalprice").value<5000
        )
    )
    {
        button.disabled = false;
        console.log("enable")
    }
    else
    {   console.log("disable");
        button.disabled=true;
    }
    //console.log("cityOptions : " + isEmpty("cityOptions"))
}


function requestCities()
{   postcode=document.getElementById("postalcode").value;
    clearCityList();
    //TODO requete vers la liste des villes (api la poste ou equivalent)
    // puis traitement pour inclure cette liste dans le options
    $.ajax({
        url: 'https://geo.api.gouv.fr/communes?codePostal=' + postcode + '&fields=nom,code&format=json&geometry=centre',
        type: 'get',
        dataType: 'json',
        contentType: 'application/json',

        success: onSuccessCityList,
        error: onError
    });

}

function onSuccessCityList(result){

        //TODO parse la liste pour la rajouter aux options
        {   for(city of result)
        {   opt=new Option(city.code+" "+city.nom,city.code);
            opt.setAttribute("data-nom",city.nom);
        opt.id="insee"+city.code;
        $("#cityOptions").append(opt);
        //console.log(city);
    }
    }
    //console.log(result);
}

function onError(result)
{
    alert(result);
}

function clearCityList()
{   console.log("clearCityList()");
    $('#cityOptions').children('option:not(:first)').remove();

}

function getOwnAddress()
{   document.getElementById("addressId").value=undefined;
    $.ajax({
    url: baseurl+"user/address/getOwn",
    type: 'get',
    dataType: 'json',
    contentType: 'application/json',

    success: onSuccessOwnAddress,
    error: onError
});

}
function getUsedAddresses()
{   disableform(true);
    document.getElementById("addressId").value=undefined;
    if(addresses==null)
    {
    $.ajax({
    url: baseurl+"user/address/getUsed",
    type: 'get',
    dataType: 'json',
    contentType: 'application/json',

    success: onSuccessUsedAddresses,
    error: onError

    })
    }
};

function addressToString(address)
{    if (address.numberaddition===null)
    {numberaddition="";}
    else
    {numberaddition=address.numberaddition;}
    return address.number+" "+
    numberaddition+" "+
    address.roadtype+" "+
    address.roadname+" "+
    address.postalcode;
}

function onSuccessUsedAddresses(result)
{   i=0;
    var numberaddition;
    for(address of result)
    {
        opt=new Option(addressToString(address),i);
        $("#addressSelector").append(opt);
        i++;
    }
    addresses=result;

}

function onSuccessOwnAddress(result)
{   setAddress(result);
    document.getElementById("addressId").value=result.id;
    disableform(true);
    document.getElementById("ownAddress").value=addressToString(result);
}

function setAddress(address)
{
    document.getElementById("number").value=address.number;
    document.getElementById("numberaddition").value=address.numberaddition;
    document.getElementById("roadtype").value=address.roadtype;
    document.getElementById("roadname").value=address.roadname;
    document.getElementById("additionaladdress").value=address.additionaladdress;
    document.getElementById("postalcode").value=address.postalcode;
    //document.getElementById("cityOptions").value=address.cityId;
    document.getElementById("cityOptions").value=undefined;

}
function setSelectedAddress()
{
    index=document.getElementById("addressSelector").value;
    setAddress(addresses[index]);
    document.getElementById("addressId").value=addresses[index].id;
}
function disableform(boolvalue) {
    if (boolvalue)
    {
        document.getElementById("number").setAttribute("disabled", true);
    document.getElementById("numberaddition").setAttribute("disabled", true);
    document.getElementById("roadtype").setAttribute("disabled", true);
    document.getElementById("roadname").setAttribute("disabled", true);
    document.getElementById("additionaladdress").setAttribute("disabled", true);
    document.getElementById("postalcode").setAttribute("disabled", true);
    document.getElementById("cityOptions").setAttribute("disabled", true);
    }
    else{
    document.getElementById("number").removeAttribute("disabled");
        document.getElementById("number").removeAttribute("disabled");
        document.getElementById("numberaddition").removeAttribute("disabled");
        document.getElementById("roadtype").removeAttribute("disabled");
        document.getElementById("roadname").removeAttribute("disabled");
        document.getElementById("additionaladdress").removeAttribute("disabled");
        document.getElementById("postalcode").removeAttribute("disabled");
        document.getElementById("cityOptions").removeAttribute("disabled");
    }

    buttonEnabler()
}
function addProduct(id)
{    $.ajax({
    url : baseurl+"user/sale/addProduct/"+id,
    type : "PUT",
    success : onSuccessProduct
});


}
function rmProduct(id) {
    $.ajax({
        url: baseurl + "user/sale/rmProduct/" + id,
        type: "PUT",
        success: onSuccessProduct
    });
}
function delProduct(id) {
    $.ajax({
        url: baseurl + "user/sale/delProduct/" + id,
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
    document.getElementById("productTotal" + content.product.id).textContent = content.quantity * content.product.price / 100.0;

    }
    updateTotal(content.sale.priceTmp);
}
function addService(id)
{    $.ajax({
    url : baseurl+"user/sale/addService/"+id,
    type : "PUT",
    success : onSuccessService
});


}
function rmService(id) {
    $.ajax({
        url: baseurl + "user/sale/rmService/" + id,
        type: "PUT",
        success: onSuccessService
    });
}
function delService(id) {
    $.ajax({
        url: baseurl + "user/sale/delService/" + id,
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
        document.getElementById("serviceTotal" + content.service.id).textContent = content.quantity * content.service.price / 100.0;

    }
    updateTotal(content.sale.priceTmp);
}

function addOffer(id)
{    $.ajax({
    url : baseurl+"user/sale/addOffer/"+id,
    type : "PUT",
    success : onSuccessOffer
});


}
function rmOffer(id) {
    $.ajax({
        url: baseurl + "user/sale/rmOffer/" + id,
        type: "PUT",
        success: onSuccessOffer
    });
}
function delOffer(id) {
    $.ajax({
        url: baseurl + "user/sale/delOffer/" + id,
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
        document.getElementById("offerTotal" + content.offer.id).textContent = content.quantity * unitprice;

    }
    updateTotal(content.sale.priceTmp);
}
function updateTotal(total)
{   document.getElementById("totalprice").textContent=total/100.0;

}

