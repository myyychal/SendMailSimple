function showAndHideDiv(divElem1, divElem2, divElem3) {
    document.getElementById(divElem2).style.display = 'none';
    document.getElementById(divElem3).style.display = 'none';
    if (document.getElementById(divElem1).style.display != 'none') {
        document.getElementById(divElem1).style.display = 'none';
    } else {
        document.getElementById(divElem1).style.display = 'block'
    }
}

function reloadShowAndHideDiv(divElem1, divElem2, divElem3) {
    document.getElementById(divElem1).style.display = 'block';
    document.getElementById(divElem2).style.display = 'none';
    document.getElementById(divElem3).style.display = 'none';
}

function hideAll(divElem1, divElem2, divElem3) {
    document.getElementById(divElem1).style.display = 'none';
    document.getElementById(divElem2).style.display = 'none';
    document.getElementById(divElem3).style.display = 'none';
}

function _add_more() {
    var txt = document.createElement('input');
    txt.type = "file";
    txt.name = "item_file[]";
    var br = document.createElement('br');
    document.getElementById("files").appendChild(txt);
    document.getElementById("files").appendChild(br);
}

function getSelectedPerson() {
    var e = document.getElementById("selectPerson");
    var strUser = e.options[e.selectedIndex].value;
    return strUser;
}