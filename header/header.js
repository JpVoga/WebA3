const createAccountButton = document.getElementById("createAccountButton");
const loginButton = document.getElementById("loginButton");
const logoutButton = document.getElementById("logoutButton");
const searchInput = document.getElementById("searchInput");


// Main program start

if (createAccountButton != null)
{
    createAccountButton.onclick = () =>
    {
        window.open("../createAccount/createAccount.php", "_self");
    };
}

if (loginButton != null)
{
    loginButton.onclick = () =>
    {
        window.open("../login/login.php", "_self");
    };
}

if (logoutButton != null)
{
    logoutButton.onclick = () =>
    {
        window.open("../logoutRedirect/logoutRedirect.php", "_self");
    };
}