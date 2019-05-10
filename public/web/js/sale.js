var baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
var addresses=null;
function upQte(id)
{   console.log(id);
    document.forms[id].submit();
}
console.log("up");
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