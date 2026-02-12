// Dark Mode Toggle - Hansen Educacional
(function() {
    var saved = localStorage.getItem('hansen_theme');
    if (saved === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
})();

function toggleDarkMode() {
    var current = document.documentElement.getAttribute('data-theme');
    var next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('hansen_theme', next);
    updateToggleIcon();
}

function updateToggleIcon() {
    var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    var btns = document.querySelectorAll('.dark-mode-toggle');
    btns.forEach(function(btn) {
        btn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        btn.title = isDark ? 'Modo Claro' : 'Modo Escuro';
    });
}

document.addEventListener('DOMContentLoaded', updateToggleIcon);
