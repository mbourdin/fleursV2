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
    return val.value===undefined || val.value==="";
}

function buttonEnabler()
{   button=document.getElementById("submitbutton");


    if
    (
        !isEmpty("recipient")
        &&
        !isEmpty("contact")
        &&
        (    document.getElementById("useownaddress").checked

            ||
            (
                !isEmpty("number")
                &&
                !isEmpty("roadname")
                &&
                !isEmpty("cityOptions")

            )
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

    console.log("useownaddres"+ document.getElementById("useownaddress").value);
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

function onError(result) {console.log("error");
}

function clearCityList()
{   console.log("clearCityList()");
    $('#cityOptions').children('option:not(:first)').remove();

}

