let isLoggedIn = false;

function init() { updateAuthUI(); }
function updateAuthUI() {
    const authUser = document.getElementById('authUser');
    const guestUser = document.getElementById('guestUser');
    if (isLoggedIn) {
        authUser.style.display = 'block';
        guestUser.style.display = 'none';
    } else {
        authUser.style.display = 'none';
        guestUser.style.display = 'block';
    }
}
function toggleDropdown(e) {
    e.preventDefault();
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
    setTimeout(() => { document.addEventListener('click', closeDropdown); }, 0);
}
function closeDropdown(e) {
    const dropdown = document.getElementById('userDropdown');
    if (!e.target.closest('.user-menu')) {
        dropdown.classList.remove('show');
        document.removeEventListener('click', closeDropdown);
    }
}
function logout() {
    isLoggedIn = false;
    updateAuthUI();
    document.getElementById('userDropdown').classList.remove('show');
}
function toggleMenu() {
    const menu = document.getElementById('navMenu');
    menu.classList.toggle('open');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'l') { isLoggedIn = !isLoggedIn; updateAuthUI(); }
});
init();