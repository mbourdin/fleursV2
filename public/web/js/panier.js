currentDomain=window.location.origin;
function updateProductQuantity(classe,id)
{   quantity=document.getElementById(classe+"input").value;
    getstring=currentDomain+"/panier/updateQuantity/"+classe+"/"+id+"/"+quantity;
    $.get(getstring,null,window.location.reload());
    console.log(getstring);
}
