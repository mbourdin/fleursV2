var baseurl=window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/";
var cityTable=document.getElementById("cityTable");
//chargement de la liste des departements
$(document).ready(
    function () {
                $.ajax({
                url: 'https://geo.api.gouv.fr/departements?fields=nom,code',
                type: 'get',
                dataType: 'json',
                contentType: 'application/json',
                success: onSuccessDepList,
                error: onError
            });
    }
);
function clearCityList()
{   console.log("clearCityList()");
    $('#cityOptions').children('option:not(:first)').remove();

}
function onSuccessDepList(result)
{   //TODO parse la liste pour la rajouter aux options
    for(dep of result)
    {
        opt=new Option(dep.code+" "+dep.nom,dep.code);
        $("#departement").append(opt);
    }
    //console.log(result);
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
function requestCities()
{    departement=document.getElementById("departement").value;
    clearCityList();
    //TODO requete vers la liste des villes (api la poste ou equivalent)
    // puis traitement pour inclure cette liste dans le options
    $.ajax({
        url: 'https://geo.api.gouv.fr/communes?codeDepartement=' + departement + '&fields=nom,code&format=json&geometry=centre',
        type: 'get',
        dataType: 'json',
        contentType: 'application/json',

        success: onSuccessCityList,
        error: onError
    });

}

function addActive()
{   id=document.getElementById("cityOptions").value;
    $.ajax({
        url: baseurl+"admin/city/addActive",
        type: "POST",
        data : {"inseeId": id},
        success: onSuccessAdd,
        error: onError
    });
}
function onSuccessAdd(result)
{   addline(result);
    console.log("successAdd");
}
function addline(result)
{   city=JSON.parse(result);
    if(document.getElementById("ligne"+city.id)!=null)
    {   return;
    }
    var active="inactive";
    if(city.active)
    {
        active="active";
    }
    var tr=cityTable.insertRow();
    tr.setAttribute("id","ligne"+city.id);
    td1=tr.insertCell();
    var txt = document.createTextNode(city.id);
    td1.appendChild(txt);
    td2= tr.insertCell();
    txt = document.createTextNode(city.name);
    td2.appendChild(txt);
    td3= tr.insertCell();
    txt = document.createTextNode(city.inseeid);
    td3.appendChild(txt);
    td4= tr.insertCell();
    td4.setAttribute("id","active"+city.id);
    txt = document.createTextNode(active);
    td4.appendChild(txt);
    td5= tr.insertCell();
    var btn = document.createElement('button');

    btn.className = "btn btn-success";
    btn.textContent = "activer";
    btn.addEventListener("click",function(){enable(city.id);});
    td5.appendChild(btn);
    var btn2 = document.createElement('button');

    btn2.textContent = "desactiver";
    btn2.className = "btn btn-secondary";
    btn2.addEventListener("click",function (){disable(city.id);});
    td5.appendChild(btn2);
    var btn3 = document.createElement('button');

    btn3.textContent = "supprimer";
    btn3.className = "btn btn-danger";
    btn3.addEventListener("click",function(){deleteCity(city.id);});
    td5.appendChild(btn3);

}
function enable(id) {
    //console.log("enable("+id+")");
    $.ajax({
        url: baseurl+"admin/city/activate/"+id,
        type: "PUT",
        success: onSuccessEnable,
        error: onError
    });
}
function onSuccessEnable(result)
{   td=document.getElementById("active"+result);
    td.textContent="active"

}
function disable(id){
    $.ajax({
        url: baseurl+"admin/city/deactivate/"+id,
        type: "PUT",
        success: onSuccessDisable,
        error: onError
    });
    console.log("disable("+id+")");
}
function onSuccessDisable(result)
{   td=document.getElementById("active"+result);
    td.textContent="inactive"
}
function deleteCity(id) {
    $.ajax({
        url: baseurl+"admin/city/delete/"+id,
        type: "DELETE",
        success: onSuccessDelete,
        error: onError
    });
    //console.log("delete("+id+")");
}
function onSuccessDelete(result)
{   document.getElementById("ligne"+result).remove();
}