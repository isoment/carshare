// Check if user logged in
export function isLoggedIn() {
    return localStorage.getItem("isLoggedIn") == 'true';
}

// Set login to true
export function logIn() {
    localStorage.setItem("isLoggedIn", true);
}

// Set login to false
export function logOut() {
    localStorage.setItem("isLoggedIn", false);
}