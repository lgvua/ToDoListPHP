function textarea(id) {
const strbrow = 'brow-'+id;
const strplus = 'plus-'+id;
const strminus = 'minus-'+id;
 if(document.getElementById(id).style.display === 'none') {
    document.getElementById(id).style.display = "block";
    document.getElementById(strbrow).style.display = "block";
    document.getElementById(strplus).style.display = "none";
    document.getElementById(strminus).style.display = "inline";
 }
 
 else {
    document.getElementById(id).style.display = "none";
    document.getElementById(strbrow).style.display = "none";
    document.getElementById(strplus).style.display = "inline";
    document.getElementById(strminus).style.display = "none";
 }
}


function headarea(id) {
  const strheado= 'heado-'+id;
  const strheadt= 'headt-'+id;
 if(document.getElementById(strheado).style.display === 'none') {
    document.getElementById(strheado).style.display = "block";
    document.getElementById(strheadt).style.display = "none";
 }
 
 else {
    document.getElementById(strheadt).style.display = "block";
    document.getElementById(strheado).style.display = "none";
 }
}

function show(id) {
    document.getElementById(id).style.display = "block";
}

function hide(id) {
    document.getElementById(id).style.display = "none";
}

function removetidfromurl() {
    const url = new URL(window.location);
    url.searchParams.delete('tid');  
    window.history.replaceState({}, '', url.toString());
}

function confirm(message) {
 swal({
  title: "Are you sure?",
  text: message,
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes",
  cancelButtonText: "No",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm){
  if (isConfirm) {
    return true;
  } else {
    return false;
  }
});
}

function confSubmit(form) {
Swal.fire({
  title: "Êtes-vous sûr ?",
text: "Vous ne pourrez pas revenir en arrière !",
icon: "warning",
customClass: 'swal-wide',
showCancelButton: true,
confirmButtonColor: "#3085d6",
cancelButtonColor: "#d33",
confirmButtonText: "Oui, supprimez la liste !",
cancelButtonText: "Annulez",
}).then((result) => {
  if (result.isConfirmed) {
    form.submit();
  }
});
}

function confSubmite(event,form) {
event.stopPropagation();

Swal.fire({
  title: "Êtes-vous sûr ?",
text: "Vous ne pourrez pas revenir en arrière !",
icon: "warning",
customClass: 'swal-wide',
showCancelButton: true,
confirmButtonColor: "#3085d6",
cancelButtonColor: "#d33",
confirmButtonText: "Oui, supprimez la tâche !",
cancelButtonText: "Annulez",
}).then((result) => {
  if (result.isConfirmed) {
    form.submit();
  }
});
};