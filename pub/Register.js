function validateRegister()
{
    const email = document.getElementById('email');
    const name = document.getElementById('name');
    const password = document.getElementById('psw');
    const reppsw = document.getElementById('pswrepeat')

    if (name.value === "") {
        window.alert("Please enter a name");
        return false;
    }

    if (email.value === "") {
        window.alert("Please enter an email address");
        return false;
    }

    if (validator.isEmail(email.value) === false) {
        window.alert("Please enter a valid email address");
        return false;
    }

    if (password.value == "") {
        window.alert("Please input a password");
        return false;
    }

    if (password.value.length < 5) {
        window.alert("Please input a longer password");
        return false;
    }

    if (password.value.length > 20) {
        window.alert("Please input a shorter password");
        return false;
    }

    if (password.value !== reppsw.value) {
        window.alert("The password repeat field must match the password");
        return false;
    }

    return true;
}
