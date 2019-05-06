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


    recipientIsEmpty=( document.getElementById("recipient").value ===undefined || document.getElementById("recipient").value==="" );
    numeroIsEmpty=(document.getElementById("number").value==="" || document.getElementById("number").value<0);

    if
    (
        !isEmpty("recipient")
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