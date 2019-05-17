let departement;
let departementChanged=false;
let port=":8000";
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
)
function clearCityList()
{   console.log("clearCityList()");
    $('#cityOptions').children('option:not(:first)').remove();

}
function changeDepartement()
{

    departementChanged=true;

}
function onSuccessDepList(result)
{   //TODO parse la liste pour la rajouter aux options
    changeDepartement();
    for(dep of result)
    {
        opt=new Option(dep.code+" "+dep.nom,dep.code)
        $("#departement").append(opt)
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
{

}