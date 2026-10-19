/* ============================================================
   NO MÁS UNOS — Selector de tema claro/oscuro
   Se carga en <head> ANTES del CSS para evitar parpadeo (FOUC).
   ============================================================ */
(function () {
  var saved = localStorage.getItem('nmu-theme');
  var theme = saved || 'light';
  document.documentElement.setAttribute('data-theme', theme);
})();

function nmuToggleTheme() {
  var html = document.documentElement;
  var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', next);
  localStorage.setItem('nmu-theme', next);
  nmuSyncToggleButtons(next);
}

function nmuSyncToggleButtons(theme) {
  var isDark = theme === 'dark';

  // Round icon-only buttons (nav bars, cards)
  document.querySelectorAll('.theme-toggle').forEach(function (btn) {
    btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    btn.innerHTML = isDark ? '☀️' : '🌙';
  });

  // Sidebar-style rows with a separate icon + label
  document.querySelectorAll('.theme-toggle-row').forEach(function (row) {
    row.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    var icon = row.querySelector('.theme-toggle-icon');
    var label = row.querySelector('.theme-toggle-label');
    if (icon) icon.textContent = isDark ? '☀️' : '🌙';
    if (label) label.textContent = isDark ? 'Modo claro' : 'Modo oscuro';
  });
}

document.addEventListener('DOMContentLoaded', function () {
  nmuSyncToggleButtons(document.documentElement.getAttribute('data-theme'));
});
