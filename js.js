function printForm(){
    var divToPrint = document.getElementById('classFormView');
    var popUpWin = window.open('', '_blank', 'width=300', 'height=300');
    popUpWin.document.open();
    popUpWin.document.write('<html><body onload="window.print();"' + divToPrint.innerHTML + '</body><html>');
    popUpWin.window.close();
}

function validateForm() {
    var roll_number = document.getElementsById('roll_number').innerHTML;
    var email = document.getElementsById('email').innerHTML;
    var telephone = document.getElementsById('telephone').innerHTML;
    var pass1 = document.getElementsById('pass1').innerHTML;
    var pass2 = document.getElementsById('pass2').innerHTML;
    var d = new Date();
    var f = 
    
}

