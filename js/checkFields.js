function checkMailFields() {
    var emailString = document.getElementById("email").value;
    var ccEmailString = document.getElementById("ccEmail").value;
    var bccEmailString = document.getElementById("bccEmail").value;
    if (!emailString) {
        document.getElementById("emailErr").innerHTML = "You should specify receiver email.";
        if (!ccEmailString) {
            document.getElementById("ccEmailErr").innerHTML = "You should specify receiver email.";
            if (!bccEmailString) {
                document.getElementById("bccEmailErr").innerHTML = "You should specify receiver email.";
                return false;
            }
        }
    } else {
        var subjectString = document.getElementById("subject").value;
        if (!subjectString) {
            var r = confirm("Do you want to send mail without subject?");
            if (r == true) {
                var messageString = document.getElementById("message").value;
                if (!messageString) {
                    r = confirm("Do you want to send mail without content?");
                    if (r == true) {
                        return true;
                    } else {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
    }
}

function checkProjectFields() {
    var nameString = document.getElementById("newName").value;
    if (!nameString) {
        document.getElementById("errMsg").innerHTML = "You must specify new project's name.";
        return false;
    } else {
        return true;
    }
}

function checkGroupFields(msg) {
    if (msg == 'create') {
        var nameString = document.getElementById("newName").value;
        if (!nameString) {
            document.getElementById("errMsg").innerHTML = "You must specify new group's name.";
            return false;
        } else {
            return true;
        }
    } else if (msg == 'edit') {
        var nameString = document.getElementById("newName").value;
        if (!nameString) {
            document.getElementById("errMsg2").innerHTML = "You must specify new group's name.";
            return false;
        } else {
            return true;
        }
    }
}

function checkPersonFields(msg) {
    if (msg == 'create') {
        var nameString = document.getElementById("newEmail").value;
        if (!nameString) {
            document.getElementById("errMsg").innerHTML = "You must specify new person's email address.";
            return false;
        } else {
            return true;
        }
    } else if (msg == 'edit') {
        var nameString = document.getElementById("editEmail").value;
        if (!nameString) {
            document.getElementById("errMsg2").innerHTML = "You must specify person's email address.";
            return false;
        } else {
            return true;
        }
    }
}

function checkUserFields() {
    var nameString = document.getElementById("newUsername").value;
    var passwdString = document.getElementById("newPassword").value;

    if (!nameString && !passwdString) {
        document.getElementById("errMsg").innerHTML = "You must specify new user's login and password.";
        return false;
    } else {
        if (!passwdString) {
            document.getElementById("errMsg").innerHTML = "You must specify new user's password.";
            return false;
        } else if (!nameString) {
            document.getElementById("errMsg").innerHTML = "You must specify new user's login.";
            return false;
        } else {
            return true;
        }
    }
}