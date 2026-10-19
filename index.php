<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}
$nombre = htmlspecialchars($_SESSION['nombre']);
$correo = htmlspecialchars($_SESSION['correo']);
$iniciales = strtoupper(substr($_SESSION['nombre'], 0, 2));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>No Más Unos — <?= $nombre ?></title>
  <script src="assets/js/theme.js"></script>
  <link rel="stylesheet" href="styles.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --purple:#5B4BF5; --purple-lt:#EAE7FF; --coral:#FF5C57;
      --ink:#0D0D14; --bg-page:#E9E6FA; --muted:#6B6882; --border-soft:#C7BEEE;
      --mint:#00C9A7; --amber:#ffc047; --sky:#5bb8ff; --violet:#a78bff;
    }
    body { font-family:'Inter',sans-serif!important; background:var(--bg-page)!important; color:var(--ink)!important; }

    /* Sidebar */
    .sidebar { background:linear-gradient(180deg,#0D0D14 0%,#131120 100%)!important; border-right:1px solid rgba(255,255,255,0.07)!important; box-shadow:4px 0 24px rgba(13,13,20,.08); }
    .brand-name { font-family:'Syne',sans-serif!important; font-weight:800!important; }
    .nav-item.active .nav-btn, .nav-btn:hover { background:var(--purple)!important; color:#fff!important; border-radius:10px; box-shadow:0 4px 14px rgba(91,75,245,.35); }
    .user-avatar { background:var(--coral)!important; color:#fff!important; font-family:'Syne',sans-serif!important; font-weight:800!important; }
    .sidebar .nav-label,.sidebar .nav-icon,.sidebar .brand-name,.sidebar .brand-sub,.sidebar .user-name,.sidebar .user-role { color:#eef0f7!important; }
    .sidebar .nav-btn { color:#8892a4!important; }
    .sidebar .nav-item.active .nav-btn,.sidebar .nav-btn:hover { color:#fff!important; }
    .sidebar__logo { box-shadow:0 6px 18px rgba(91,75,245,.3); }

    /* Topbar */
    .topbar { background:rgba(246,245,255,.85)!important; backdrop-filter:blur(14px) saturate(1.4)!important; border-bottom:1px solid var(--border-soft)!important; }
    .topbar__title { font-family:'Syne',sans-serif!important; font-weight:700!important; color:var(--ink)!important; }

    /* Cards */
    .stat-card,.dash-card,.task-item,.calendar { border:1.5px solid var(--border-soft)!important; border-radius:16px!important; background:#fff!important; box-shadow:0 2px 6px rgba(13,13,20,.05); }
    .stat-card--lime { background:#DFF6EA!important; border-color:rgba(91,75,245,.35)!important; }
    .stat-card--coral { background:#FFE3E1!important; border-color:rgba(255,95,95,.35)!important; }
    .stat-card--sky { background:#E1EFFF!important; border-color:rgba(91,184,255,.4)!important; }
    .stat-card--violet { background:var(--purple-lt)!important; border-color:rgba(167,139,255,.4)!important; }
    .dash-card--clickable { cursor:pointer; }
    .stat-card__value { font-family:'Syne',sans-serif!important; font-weight:800!important; color:var(--ink)!important; }

    /* Calendario dinámico */
    .cal-day { background:#fff!important; border-color:var(--border-soft)!important; }
    .cal-day:hover { background:#EAE7FF!important; }
    .cal-day__num { color:#0D0D14!important; font-weight:600!important; }
    .cal-day--today .cal-day__num { background:#5B4BF5!important; color:#fff!important; }
    .cal-head { background:#F6F5FF!important; }
    .cal-head th,.cal-head div { color:#6B6882!important; }
    .section,.section-header,.main-content { background:var(--bg-page)!important; color:var(--ink)!important; }
    .main-content p,.main-content td,.main-content th,.section-title,.section-subtitle,.dash-card__title { color:var(--ink)!important; }
    .upcoming-item { background:#F6F5FF!important; border:1px solid var(--border-soft)!important; border-radius:12px!important; transition:transform .18s ease, border-color .18s ease; }
    .upcoming-item:hover { transform:translateX(3px); border-color:var(--purple)!important; }
    .upcoming-item--urgent { border-left:3px solid #FF5C57!important; }
    .upcoming-item--soon { border-left:3px solid #ffc047!important; }
    .btn-primary,.btn--primary { background:var(--purple)!important; color:#fff!important; border-radius:99px!important; font-family:'Syne',sans-serif!important; font-weight:700!important; box-shadow:0 4px 14px rgba(91,75,245,.28); }
    .btn-primary:hover,.btn--primary:hover { box-shadow:0 6px 20px rgba(91,75,245,.4); }
    .nav-badge { background:var(--coral)!important; color:#fff!important; }
    .logo-icon { color:var(--purple)!important; }

    /* ══════════════ TOPBAR: búsqueda, notificaciones, usuario ══════════════ */
    .topbar__search {
      flex:1; max-width:480px; display:flex; align-items:center; gap:.5rem;
      background:#fff; border:1.5px solid var(--border-soft); border-radius:99px;
      padding:.5rem .95rem; margin:0 1rem; position:relative;
      transition:border-color .15s;
    }
    .topbar__search:focus-within { border-color:var(--purple); box-shadow:0 0 0 3px rgba(91,75,245,.12); }
    .topbar__search-icon { color:var(--muted); font-size:.9rem; flex-shrink:0; }
    .topbar__search input {
      border:none; outline:none; background:transparent; width:100%;
      font-size:.88rem; font-family:'Inter',sans-serif; color:var(--ink);
    }
    .topbar__search input::placeholder { color:var(--muted); }
    .topbar__search-results {
      display:none; position:absolute; top:calc(100% + 8px); left:0; right:0;
      background:#fff; border:1px solid var(--border-soft); border-radius:14px;
      box-shadow:0 16px 40px rgba(13,13,20,.16); max-height:340px; overflow-y:auto;
      z-index:200; padding:.4rem;
    }
    .topbar__search-results.open { display:block; }
    .search-result {
      display:flex; align-items:center; gap:.65rem; padding:.55rem .65rem;
      border-radius:10px; cursor:pointer; transition:background .12s;
    }
    .search-result:hover { background:var(--purple-lt); }
    .search-result__icon {
      width:30px; height:30px; border-radius:8px; flex-shrink:0;
      display:flex; align-items:center; justify-content:center; font-size:.85rem;
      background:var(--purple-lt); color:var(--purple);
    }
    .search-result__text { min-width:0; flex:1; }
    .search-result__name { font-size:.84rem; font-weight:600; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .search-result__meta { font-size:.72rem; color:var(--muted); }
    .search-empty { padding:1rem .65rem; text-align:center; color:var(--muted); font-size:.82rem; }

    .topbar__actions { display:flex; align-items:center; gap:.6rem; margin-left:auto; position:relative; }

    .topbar__iconbtn {
      position:relative; width:38px; height:38px; flex-shrink:0;
      border-radius:50%; border:1.5px solid var(--border-soft); background:#fff;
      display:flex; align-items:center; justify-content:center; cursor:pointer;
      font-size:16px; color:var(--ink); transition:border-color .15s, transform .15s;
    }
    .topbar__iconbtn:hover { border-color:var(--purple); transform:translateY(-1px); }
    .topbar__badge {
      position:absolute; top:-4px; right:-4px; min-width:16px; height:16px; padding:0 3px;
      border-radius:99px; background:var(--coral); color:#fff;
      font-size:.62rem; font-weight:700; font-family:'Inter',sans-serif;
      display:flex; align-items:center; justify-content:center; border:2px solid #fff;
    }

    .topbar__dropdown {
      display:none; position:absolute; top:calc(100% + 10px); right:0; width:300px;
      background:#fff; border:1px solid var(--border-soft); border-radius:14px;
      box-shadow:0 16px 40px rgba(13,13,20,.16); z-index:200; overflow:hidden;
    }
    .topbar__dropdown.open { display:block; }
    .dropdown__header {
      padding:.85rem 1rem; font-family:'Syne',sans-serif; font-weight:700; font-size:.85rem;
      color:var(--ink); border-bottom:1px solid var(--border-soft);
    }
    .notif-item { display:flex; gap:.65rem; padding:.7rem 1rem; border-bottom:1px solid var(--border-soft); cursor:pointer; transition:background .12s; }
    .notif-item:last-child { border-bottom:none; }
    .notif-item:hover { background:var(--purple-lt); }
    .notif-item__dot { width:8px; height:8px; border-radius:50%; background:var(--coral); flex-shrink:0; margin-top:.4rem; }
    .notif-item__text p { font-size:.82rem; color:var(--ink); margin-bottom:.15rem; line-height:1.3; }
    .notif-item__text span { font-size:.72rem; color:var(--muted); }
    .notif-empty { padding:1.4rem 1rem; text-align:center; color:var(--muted); font-size:.82rem; }

    .topbar__user-pill {
      display:flex; align-items:center; gap:.55rem; padding:.3rem .7rem .3rem .3rem;
      border-radius:99px; border:1.5px solid var(--border-soft); background:#fff;
      cursor:pointer; transition:border-color .15s;
    }
    .topbar__user-pill:hover { border-color:var(--purple); }
    .topbar__user-pill .user-avatar { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.78rem; }
    .topbar__user-pill-text { display:flex; flex-direction:column; line-height:1.15; text-align:left; }
    .topbar__user-pill-text strong { font-size:.82rem; color:var(--ink); font-family:'Syne',sans-serif; }
    .topbar__user-pill-text span { font-size:.7rem; color:var(--muted); }
    .topbar__user-pill .chevron { color:var(--muted); font-size:.7rem; margin-left:.2rem; }
    .user-dropdown-item {
      display:flex; align-items:center; gap:.6rem; width:100%; padding:.75rem 1rem;
      background:none; border:none; text-align:left; cursor:pointer;
      font-size:.85rem; color:var(--ink); font-family:'Inter',sans-serif;
      transition:background .12s;
    }
    .user-dropdown-item:hover { background:var(--purple-lt); }
    .user-dropdown-item--danger { color:#ef4444; }
    .user-dropdown-item--danger:hover { background:#FFF1F0; }

    @media (max-width:760px){
      .topbar__search { display:none; }
    }

    /* ══════════════ SEGUNDA FILA DEL DASHBOARD: progreso + actividad ══════════════ */
    .dashboard-grid-2 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-top:1.25rem; }
    @media (max-width:1100px){ .dashboard-grid-2 { grid-template-columns:1fr 1fr; } }
    @media (max-width:700px){ .dashboard-grid-2 { grid-template-columns:1fr; } }

    .progress-ring-wrap { display:flex; flex-direction:column; align-items:center; text-align:center; padding:.5rem 0 .25rem; }
    .progress-ring-wrap svg { transform:rotate(-90deg); }
    .progress-ring-track { fill:none; stroke:var(--border-soft); stroke-width:11; }
    .progress-ring-value { fill:none; stroke:var(--purple); stroke-width:11; stroke-linecap:round; transition:stroke-dashoffset .8s cubic-bezier(.4,0,.2,1); }
    .progress-ring-center { font-family:'Syne',sans-serif; font-weight:800; font-size:1.7rem; fill:var(--ink); }
    .progress-ring-label { font-size:.78rem; color:var(--muted); margin-top:-.4rem; }
    .progress-ring-msg { font-size:.82rem; color:var(--ink); font-weight:600; margin-top:.9rem; }
    .progress-ring-sub { font-size:.76rem; color:var(--muted); margin-top:.15rem; }

    .activity-feed { display:flex; flex-direction:column; gap:.15rem; }
    .activity-item { display:flex; align-items:flex-start; gap:.75rem; padding:.6rem .1rem; border-bottom:1px solid var(--border-soft); }
    .activity-item:last-child { border-bottom:none; }
    .activity-item__icon {
      width:32px; height:32px; border-radius:9px; flex-shrink:0;
      display:flex; align-items:center; justify-content:center; font-size:.9rem;
    }
    .activity-item__icon--done { background:#ECFDF5; color:#10b981; }
    .activity-item__icon--new  { background:var(--purple-lt); color:var(--purple); }
    .activity-item__icon--nota { background:#EFF6FF; color:#3b82f6; }
    .activity-item__text p { font-size:.85rem; color:var(--ink); margin-bottom:.1rem; }
    .activity-item__text strong { font-weight:600; }
    .activity-item__text span { font-size:.72rem; color:var(--muted); }
    .activity-empty { color:var(--muted); font-size:.85rem; padding:.75rem 0; }

    /* ══════════════ MENSAJES (recordatorios personales) ══════════════ */
    .mensaje-item {
      display:flex; align-items:flex-start; gap:.75rem;
      padding:.85rem 1rem; border:1px solid var(--border-soft); border-radius:12px;
      background:#fff; transition:border-color .15s;
    }
    .mensaje-item--unread { border-left:3px solid var(--purple); }
    .mensaje-item__icon { font-size:1.1rem; flex-shrink:0; margin-top:.1rem; }
    .mensaje-item__body { flex:1; min-width:0; }
    .mensaje-item__title { font-weight:700; font-size:.9rem; color:var(--ink); font-family:'Syne',sans-serif; }
    .mensaje-item__content { font-size:.83rem; color:var(--muted); margin-top:.2rem; line-height:1.4; }
    .mensaje-item__time { font-size:.7rem; color:var(--muted); margin-top:.4rem; display:block; }
    .mensaje-item__actions { display:flex; gap:.35rem; flex-shrink:0; }

    /* ══════════════ DASHBOARD: mini-calendario, compromisos, cursos, mensajes ══════════════ */
    .dashboard-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-top:1.25rem; }
    @media (max-width:1100px){ .dashboard-grid-3 { grid-template-columns:1fr 1fr; } }
    @media (max-width:700px){ .dashboard-grid-3 { grid-template-columns:1fr; } }

    .mini-cal__nav { display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem; }
    .mini-cal__nav button { background:none; border:1.5px solid var(--border-soft); border-radius:8px; width:26px; height:26px; cursor:pointer; color:var(--ink); font-size:.85rem; }
    .mini-cal__nav button:hover { border-color:var(--purple); color:var(--purple); }
    .mini-cal__nav span { font-family:'Syne',sans-serif; font-weight:700; font-size:.82rem; color:var(--ink); }
    .mini-cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; }
    .mini-cal-head { font-size:.62rem; color:var(--muted); text-align:center; font-weight:600; padding-bottom:.2rem; }
    .mini-cal-day {
      aspect-ratio:1; display:flex; flex-direction:column; align-items:center; justify-content:center;
      border-radius:8px; font-size:.72rem; color:var(--ink); cursor:pointer; position:relative;
      transition:background .12s;
    }
    .mini-cal-day:hover { background:var(--purple-lt); }
    .mini-cal-day--empty { cursor:default; }
    .mini-cal-day--empty:hover { background:none; }
    .mini-cal-day--today { background:var(--purple); color:#fff; font-weight:700; }
    .mini-cal-dot { width:4px; height:4px; border-radius:50%; background:var(--coral); position:absolute; bottom:3px; }
    .mini-cal-day--today .mini-cal-dot { background:#fff; }

    .compromiso-item { padding:.7rem .85rem; border-radius:10px; background:var(--purple-lt); margin-bottom:.55rem; border-left:3px solid var(--purple); }
    .compromiso-item:last-child { margin-bottom:0; }
    .compromiso-item--urgent { border-left-color:var(--coral); background:#FFF1F0; }
    .compromiso-item--soon { border-left-color:var(--amber); background:#FFF8E8; }
    .compromiso-item__top { display:flex; align-items:center; justify-content:space-between; margin-bottom:.15rem; }
    .compromiso-item__type { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.03em; color:var(--muted); }
    .compromiso-item__date { font-size:.68rem; font-weight:700; color:var(--purple); }
    .compromiso-item--urgent .compromiso-item__date { color:var(--coral); }
    .compromiso-item__name { font-size:.85rem; font-weight:600; color:var(--ink); }
    .compromiso-item__meta { font-size:.74rem; color:var(--muted); margin-top:.1rem; }

    .cursos-activos-item { margin-bottom:.85rem; }
    .cursos-activos-item:last-child { margin-bottom:0; }
    .cursos-activos-item__top { display:flex; justify-content:space-between; font-size:.82rem; margin-bottom:.35rem; }
    .cursos-activos-item__name { font-weight:600; color:var(--ink); }
    .cursos-activos-item__pct { font-weight:700; color:var(--purple); }
    .cursos-activos-item__track { height:7px; background:var(--border-soft); border-radius:99px; overflow:hidden; }
    .cursos-activos-item__fill { height:100%; background:var(--purple); border-radius:99px; transition:width .6s ease; }

    .mensaje-mini { display:flex; gap:.6rem; padding:.55rem 0; border-bottom:1px solid var(--border-soft); }
    .mensaje-mini:last-child { border-bottom:none; }
    .mensaje-mini__dot { width:8px; height:8px; border-radius:50%; background:var(--purple); flex-shrink:0; margin-top:.4rem; }
    .mensaje-mini__dot--read { background:var(--border-soft); }
    .mensaje-mini__text p { font-size:.83rem; font-weight:600; color:var(--ink); }
    .mensaje-mini__text span { font-size:.72rem; color:var(--muted); }

    /* Firma visual: resplandor suave detrás del saludo del panel general */
    #section-dashboard .section-header { position:relative; }
    #section-dashboard .section-header::before {
      content:''; position:absolute; top:-60px; left:-40px;
      width:340px; height:220px; z-index:-1; pointer-events:none;
      background:
        radial-gradient(circle at 20% 30%, rgba(91,75,245,.16), transparent 60%),
        radial-gradient(circle at 75% 60%, rgba(255,92,87,.12), transparent 55%);
      filter:blur(10px);
    }


    /* Modal para agregar tarea/nota */
    .modal-overlay {
      display:none; position:fixed; inset:0;
      background:rgba(0,0,0,.45); z-index:1000;
      align-items:center; justify-content:center;
    }
    .modal-overlay.open { display:flex; }
    .modal-box {
      background:#fff; border-radius:20px; padding:2rem;
      width:90%; max-width:480px; max-height:90vh; overflow-y:auto;
      border:1px solid var(--border-soft);
      box-shadow:0 20px 60px rgba(0,0,0,.2);
    }
    .modal-box h3 { font-family:'Syne',sans-serif; font-size:1.1rem; font-weight:700; margin-bottom:1.25rem; }
    .modal-box .form-group { margin-bottom:.85rem; }
    .modal-box .form-label { display:block; font-size:.78rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:.3rem; }
    .modal-box .form-input { width:100%; padding:.6rem .85rem; border:1.5px solid var(--border-soft); border-radius:10px; font-size:.9rem; color:var(--ink); font-family:'Inter',sans-serif; outline:none; transition:border-color .2s; }
    .modal-box .form-input:focus { border-color:var(--purple); box-shadow:0 0 0 3px rgba(91,75,245,.12); }
    .modal-actions { display:flex; gap:.75rem; margin-top:1.25rem; }
    .btn-cancel { padding:.6rem 1.2rem; border:1.5px solid var(--border-soft); border-radius:99px; background:transparent; color:var(--muted); font-size:.88rem; cursor:pointer; font-family:'Inter',sans-serif; }
    .btn-cancel:hover { border-color:var(--purple); color:var(--purple); }

    /* Loading spinner */
    .spinner { display:inline-block; width:18px; height:18px; border:2px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; vertical-align:middle; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* Badge de estado en dashboard */
    #dash-pendientes-count, #dash-semana-count { font-family:'Syne',sans-serif; font-weight:800; }

    /* Tareas vacías */
    .empty-col { text-align:center; padding:2rem 1rem; color:var(--muted); font-size:.85rem; }
    .empty-col span { display:block; font-size:2rem; margin-bottom:.5rem; }

    /* Notas modal */
    .notas-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }

    /* Tag de materia en tarea */
    .task-tag-custom { font-size:.68rem; font-weight:700; padding:.18rem .5rem; border-radius:4px; background:var(--purple-lt); color:var(--purple); text-transform:uppercase; letter-spacing:.04em; }

    /* Resaltado en calendario al pasar el ratón */
    .cal-day.selected { background:#EAE7FF!important; border:1.5px solid var(--purple)!important; }

    /* Indicador de carga global */
    #loading-bar { display:none; position:fixed; top:0; left:0; right:0; height:3px; background:var(--purple); z-index:9999; animation:loadbar 1.2s ease-in-out infinite; }
    @keyframes loadbar { 0%{opacity:1;width:0%} 50%{width:70%} 100%{opacity:0;width:100%} }

    a.nav-btn { display:flex; align-items:center; gap:10px; width:100%; padding:10px 14px; border:none; background:transparent; border-radius:10px; cursor:pointer; text-decoration:none; color:inherit; font-size:14px; font-family:inherit; transition:background .2s; }
    a.nav-btn:hover { background:rgba(255,255,255,.08); }

    /* Promedio en dashboard */
    #dash-promedio { font-family:'Syne',sans-serif; font-weight:800; }

    /* ══════════════ DUVICITO — Overlay de bienvenida ══════════════ */
    .duvicito-overlay {
      display:none; position:fixed; inset:0; z-index:2000;
      background:rgba(13,13,20,.62); backdrop-filter:blur(4px);
      align-items:center; justify-content:center; padding:1.5rem;
    }
    .duvicito-overlay.show { display:flex; }
    .duvicito-card {
      background:linear-gradient(135deg,#fff 55%, var(--purple-lt) 100%);
      border-radius:24px; max-width:560px; width:100%;
      display:flex; gap:1.5rem; align-items:center;
      padding:2rem; position:relative;
      box-shadow:0 30px 80px rgba(13,13,20,.4);
      animation:duvicitoPop .45s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes duvicitoPop { from{transform:scale(.85);opacity:0;} to{transform:scale(1);opacity:1;} }
    .duvicito-close {
      position:absolute; top:12px; right:14px; background:none; border:none;
      font-size:18px; cursor:pointer; color:var(--muted); line-height:1;
    }
    .duvicito-close:hover { color:var(--coral); }
    .duvicito-img-wrap {
      flex-shrink:0; width:140px; height:140px; border-radius:22px;
      overflow:hidden; background:#0D0D14;
      box-shadow:0 12px 30px rgba(91,75,245,.35);
    }
    .duvicito-img { width:100%; height:100%; object-fit:cover; display:block; }
    .duvicito-bubble { flex:1; min-width:0; }
    .duvicito-greeting {
      font-family:'Syne',sans-serif; font-weight:800; font-size:1.15rem;
      color:var(--ink); margin-bottom:.4rem; line-height:1.35;
    }
    .duvicito-question { font-size:.88rem; color:var(--muted); margin-bottom:1rem; }
    .duvicito-form { display:flex; gap:.5rem; margin-bottom:.7rem; }
    .duvicito-form input { flex:1; }
    .duvicito-skip { background:none; border:none; color:var(--muted); font-size:.76rem; cursor:pointer; text-decoration:underline; padding:0; }
    .duvicito-skip:hover { color:var(--purple); }
    @media (max-width:520px) {
      .duvicito-card { flex-direction:column; text-align:center; padding:1.75rem 1.5rem; }
      .duvicito-form { flex-direction:column; }
    }

    /* ══════════════ GRÁFICO DE PROMEDIOS — Barras personalizables ══════════════ */
    .chart-card__header { margin-bottom:.25rem; }
    .chart-card__header p { font-size:.85rem; color:var(--muted); margin-top:.25rem; }
    .bar-chart {
      display:flex; align-items:flex-end; gap:1.5rem;
      min-height:250px; padding:2rem 1rem .5rem;
      overflow-x:auto;
    }
    .bar-col { display:flex; flex-direction:column; align-items:center; gap:.5rem; min-width:64px; position:relative; }
    .bar-gear {
      background:var(--purple-lt); border:none; border-radius:50%;
      width:26px; height:26px; cursor:pointer; font-size:12px;
      display:flex; align-items:center; justify-content:center;
      transition:background .2s, transform .2s;
    }
    .bar-gear:hover { background:var(--purple); transform:scale(1.08); }
    .bar-panel {
      display:none; position:absolute; top:32px; left:50%; transform:translateX(-50%);
      background:#fff; border:1px solid var(--border-soft); border-radius:14px;
      padding:.85rem; box-shadow:0 12px 30px rgba(13,13,20,.18); z-index:30; width:165px;
    }
    .bar-panel.open { display:flex; flex-direction:column; gap:.55rem; }
    .bar-panel label { font-size:.66rem; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.04em; }
    .bar-panel input[type="color"] { width:100%; height:32px; border:none; border-radius:8px; cursor:pointer; padding:0; }
    .bar-panel select {
      width:100%; padding:.4rem .5rem; border-radius:8px; border:1.5px solid var(--border-soft);
      font-size:.78rem; font-family:'Inter',sans-serif; color:var(--ink);
    }
    .bar-track {
      width:46px; height:170px; background:#F6F5FF; border-radius:10px 10px 4px 4px;
      display:flex; align-items:flex-end; overflow:hidden; border:1px solid var(--border-soft);
    }
    .bar-fill { width:100%; border-radius:8px 8px 0 0; transition:height .6s cubic-bezier(.4,0,.2,1); min-height:4px; }
    .bar-value { font-family:'Syne',sans-serif; font-weight:800; font-size:.85rem; color:var(--ink); }
    .bar-label { font-size:.72rem; color:var(--muted); text-align:center; max-width:72px; line-height:1.2; }

    /* Botón de tema (redondo, usado en topbar móvil) */
    .theme-toggle {
      width:36px; height:36px; flex-shrink:0; margin-left:auto;
      border-radius:50%; border:1.5px solid var(--border-soft);
      background:#fff; color:var(--ink);
      display:flex; align-items:center; justify-content:center;
      font-size:15px; cursor:pointer; padding:0;
      transition:transform .18s ease, border-color .18s ease;
    }
    .theme-toggle:hover { transform:translateY(-2px) rotate(8deg); border-color:var(--purple); }

    /* ══════════════════════════════════════════════════════════
       TEMA OSCURO
       ══════════════════════════════════════════════════════════ */
    [data-theme="dark"] {
      --purple:#8B7CFF; --purple-lt:#241F3D; --coral:#FF6F68;
      --ink:#F1F0FA; --bg-page:#0B0A14; --muted:#A6A2C4; --border-soft:#423A66;
      --mint:#2FD9B9; --amber:#ffcc66; --sky:#79c4ff; --violet:#b9a6ff;
      color-scheme: dark;
    }
    [data-theme="dark"] body { background:var(--bg-page)!important; color:var(--ink)!important; }
    [data-theme="dark"] .sidebar { background:linear-gradient(180deg,#0A0912 0%,#100E1C 100%)!important; border-right:1px solid rgba(255,255,255,.06)!important; }
    [data-theme="dark"] .theme-toggle { background:#1B1830!important; border-color:var(--border-soft)!important; color:var(--ink)!important; }

    [data-theme="dark"] .topbar { background:rgba(16,14,28,.82)!important; border-bottom:1px solid var(--border-soft)!important; }

    [data-theme="dark"] .stat-card,[data-theme="dark"] .dash-card,[data-theme="dark"] .task-item,[data-theme="dark"] .calendar { border:1.5px solid var(--border-soft)!important; background:#181528!important; box-shadow:0 2px 6px rgba(0,0,0,.3); }
    [data-theme="dark"] .stat-card--lime { background:#132A22!important; border-color:rgba(139,124,255,.4)!important; }
    [data-theme="dark"] .stat-card--coral { background:#2E1518!important; border-color:rgba(255,111,104,.4)!important; }
    [data-theme="dark"] .stat-card--sky { background:#132234!important; border-color:rgba(121,196,255,.4)!important; }
    [data-theme="dark"] .stat-card--violet { background:var(--purple-lt)!important; border-color:rgba(185,166,255,.4)!important; }
    [data-theme="dark"] #section-dashboard .section-header::before {
      background:
        radial-gradient(circle at 20% 30%, rgba(139,124,255,.22), transparent 60%),
        radial-gradient(circle at 75% 60%, rgba(255,111,104,.16), transparent 55%);
    }

    [data-theme="dark"] .cal-day { background:#181528!important; border-color:var(--border-soft)!important; color:var(--ink); }
    [data-theme="dark"] .cal-day:hover { background:var(--purple-lt)!important; }
    [data-theme="dark"] .cal-day__num { color:var(--ink)!important; }
    [data-theme="dark"] .cal-day--today .cal-day__num { background:var(--purple)!important; color:#fff!important; }
    [data-theme="dark"] .cal-day.selected { background:var(--purple-lt)!important; border:1.5px solid var(--purple)!important; }
    [data-theme="dark"] .cal-head { background:#181528!important; }
    [data-theme="dark"] .cal-head th,[data-theme="dark"] .cal-head div { color:var(--muted)!important; }

    [data-theme="dark"] .section,[data-theme="dark"] .section-header,[data-theme="dark"] .main-content { background:var(--bg-page)!important; color:var(--ink)!important; }
    [data-theme="dark"] .main-content p,[data-theme="dark"] .main-content td,[data-theme="dark"] .main-content th,[data-theme="dark"] .section-title,[data-theme="dark"] .section-subtitle,[data-theme="dark"] .dash-card__title { color:var(--ink)!important; }
    [data-theme="dark"] .upcoming-item { background:#181528!important; border:1px solid var(--border-soft)!important; }

    [data-theme="dark"] .modal-box { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .modal-box .form-input { border-color:var(--border-soft)!important; color:var(--ink); background:#100E1C; }
    [data-theme="dark"] .bar-track { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .bar-panel { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .bar-panel select { border-color:var(--border-soft)!important; background:#100E1C; color:var(--ink); }
    [data-theme="dark"] .duvicito-card { background:linear-gradient(135deg,#181528 55%, var(--purple-lt) 100%)!important; }
    [data-theme="dark"] .duvicito-greeting { color:var(--ink); }

    /* ══════════════ TEMA OSCURO: topbar extra + progreso + actividad ══════════════ */
    [data-theme="dark"] .topbar__search { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .topbar__search-results { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .search-result:hover { background:var(--purple-lt)!important; }
    [data-theme="dark"] .topbar__iconbtn { background:#181528!important; border-color:var(--border-soft)!important; color:var(--ink)!important; }
    [data-theme="dark"] .topbar__badge { border-color:#181528!important; }
    [data-theme="dark"] .topbar__dropdown { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .notif-item { border-color:var(--border-soft)!important; }
    [data-theme="dark"] .notif-item:hover { background:var(--purple-lt)!important; }
    [data-theme="dark"] .topbar__user-pill { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .user-dropdown-item:hover { background:var(--purple-lt)!important; }
    [data-theme="dark"] .user-dropdown-item--danger:hover { background:#2E1518!important; }
    [data-theme="dark"] .progress-ring-track { stroke:var(--border-soft)!important; }
    [data-theme="dark"] .activity-item { border-color:var(--border-soft)!important; }
    [data-theme="dark"] .activity-item__icon--done { background:#132A22!important; color:#2FD9B9!important; }
    [data-theme="dark"] .activity-item__icon--nota { background:#132234!important; color:#79c4ff!important; }
    [data-theme="dark"] .mensaje-item { background:#181528!important; border-color:var(--border-soft)!important; }
    [data-theme="dark"] .mini-cal-day:hover { background:var(--purple-lt)!important; }
    [data-theme="dark"] .compromiso-item { background:var(--purple-lt)!important; }
    [data-theme="dark"] .compromiso-item--urgent { background:#2E1518!important; }
    [data-theme="dark"] .compromiso-item--soon { background:#332812!important; }
    [data-theme="dark"] .mensaje-mini { border-color:var(--border-soft)!important; }
  </style>
</head>
<body>

<div id="loading-bar"></div>

<!-- ════════════════════════════════════════════════
     DUVICITO — Bienvenida / Onboarding
════════════════════════════════════════════════ -->
<div class="duvicito-overlay" id="duvicito-overlay">
  <div class="duvicito-card">
    <button class="duvicito-close" onclick="cerrarDuvicito()" aria-label="Cerrar">✕</button>
    <div class="duvicito-img-wrap">
      <img src="assets/img/duvicito-mascota.jpeg" alt="Duvicito, la mascota de No Más Unos" class="duvicito-img"/>
    </div>
    <div class="duvicito-bubble">
      <p class="duvicito-greeting">¡Hola! Soy Duvicito 👋<br>y te ayudaré a organizarte.</p>
      <p class="duvicito-question">¿Cómo te gustaría llamarme?</p>
      <div class="duvicito-form">
        <input type="text" id="duvicito-nombre-input" class="form-input" placeholder="Ej: Duvi, Notitas..." maxlength="20"/>
        <button class="btn btn--primary" onclick="guardarNombreDuvicito()">¡Listo! →</button>
      </div>
      <button class="duvicito-skip" onclick="cerrarDuvicito()">Tal vez luego</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════ -->
<aside class="sidebar" id="sidebar">
<div class="sidebar__brand">

    <div class="sidebar__logo">
        <img src="assets/img/logo.jpeg" class="logo-img" alt="Logo">
    </div>

    <div class="sidebar__brand-text">
        <span class="brand-name">No Más Unos</span>
        <span class="brand-sub">SENA TIC</span>
    </div>

</div>
  <nav class="sidebar__nav" role="navigation" aria-label="Menú principal">
    <ul class="nav-list">
      <li class="nav-item active" data-section="dashboard">
        <button class="nav-btn" onclick="showSection('dashboard')"><span class="nav-icon">⊞</span><span class="nav-label">Panel General</span></button>
      </li>
      <li class="nav-item" data-section="calendario">
        <button class="nav-btn" onclick="showSection('calendario')"><span class="nav-icon">◫</span><span class="nav-label">Calendario</span></button>
      </li>
      <li class="nav-item" data-section="tareas">
        <button class="nav-btn" onclick="showSection('tareas')"><span class="nav-icon">◈</span><span class="nav-label">Tareas</span></button>
      </li>
      <li class="nav-item" data-section="proyectos">
        <button class="nav-btn" onclick="showSection('proyectos')"><span class="nav-icon">◆</span><span class="nav-label">Proyectos</span></button>
      </li>
      <li class="nav-item" data-section="notas">
        <button class="nav-btn" onclick="showSection('notas')"><span class="nav-icon">◎</span><span class="nav-label">Mis Notas</span></button>
      </li>
      <li class="nav-item" data-section="mensajes">
        <button class="nav-btn" onclick="showSection('mensajes')"><span class="nav-icon">✉</span><span class="nav-label">Mensajes</span><span class="nav-badge" id="mensajes-badge" style="display:none;">0</span></button>
      </li>
      <li class="nav-item" data-section="horario">
        <button class="nav-btn" onclick="showSection('horario')"><span class="nav-icon">▤</span><span class="nav-label">Horario</span></button>
      </li>
      <li class="nav-item" data-section="estadisticas">
        <button class="nav-btn" onclick="showSection('estadisticas')"><span class="nav-icon">📊</span><span class="nav-label">Estadísticas</span></button>
      </li>
    </ul>
  </nav>
  <button type="button" class="sidebar__user" id="sidebar-user-btn" onclick="showSection('perfil')">
    <div class="user-avatar" id="sidebar-avatar"><?= $iniciales ?></div>
    <div class="user-info">
      <span class="user-name"><?= $nombre ?></span>
      <span class="user-role">Ver perfil</span>
    </div>
  </button>
  <ul class="nav-list" style="padding:.5rem .75rem;">
    <li><button type="button" class="nav-btn theme-toggle-row" onclick="nmuToggleTheme()" aria-pressed="false">
      <span class="nav-icon theme-toggle-icon">🌙</span><span class="nav-label theme-toggle-label">Modo oscuro</span>
    </button></li>
    <li><a href="cerrar_sesion.php" class="nav-btn" style="color:#ef4444!important;">
      <span class="nav-icon">⏻</span><span class="nav-label">Cerrar sesión</span>
    </a></li>
  </ul>
</aside>

<!-- ════════════════════════════════════════════════
     TOPBAR
════════════════════════════════════════════════ -->
<header class="topbar">
  <button class="topbar__menu-btn" onclick="toggleSidebar()" aria-label="Abrir menú">
    <span></span><span></span><span></span>
  </button>
  <div class="topbar__title" id="topbar-title">Panel General</div>

  <div class="topbar__search" id="topbar-search-wrap">
    <span class="topbar__search-icon">🔍</span>
    <input type="text" id="topbar-search" placeholder="Buscar tareas, notas, materias…" autocomplete="off">
    <div class="topbar__search-results" id="topbar-search-results"></div>
  </div>

  <div class="topbar__actions">
    <button type="button" class="topbar__iconbtn" id="notif-btn" aria-label="Notificaciones" onclick="toggleNotifDropdown(event)">
      🔔
      <span class="topbar__badge" id="notif-badge" style="display:none;">0</span>
    </button>
    <div class="topbar__dropdown" id="notif-dropdown">
      <div class="dropdown__header">Notificaciones</div>
      <div id="notif-list"></div>
    </div>

    <button type="button" class="topbar__user-pill" id="user-pill-btn" onclick="toggleUserDropdown(event)">
      <div class="user-avatar" style="width:32px;height:32px;font-size:.78rem;"><?= $iniciales ?></div>
      <div class="topbar__user-pill-text">
        <strong><?= $nombre ?></strong>
        <span>Estudiante</span>
      </div>
      <span class="chevron">▾</span>
    </button>
    <div class="topbar__dropdown" id="user-dropdown" style="width:200px;">
      <button type="button" class="user-dropdown-item" onclick="showSection('perfil'); closeAllDropdowns();"><span>👤</span> Ver perfil</button>
      <button type="button" class="user-dropdown-item" onclick="nmuToggleTheme(); closeAllDropdowns();"><span>🌙</span> Cambiar tema</button>
      <a href="cerrar_sesion.php" class="user-dropdown-item user-dropdown-item--danger"><span>⏻</span> Cerrar sesión</a>
    </div>

    <button type="button" class="theme-toggle" onclick="nmuToggleTheme()" aria-label="Cambiar tema claro/oscuro" aria-pressed="false" title="Cambiar tema">🌙</button>
  </div>
</header>

<!-- ════════════════════════════════════════════════
     MAIN
════════════════════════════════════════════════ -->
<main class="main-content" id="main-content">

  <!-- ══════════════ DASHBOARD ══════════════ -->
  <section class="section active" id="section-dashboard">
    <div class="section-header">
      <h1 class="section-title">Bienvenido, <em style="font-style:normal;color:var(--purple)"><?= $nombre ?></em> 👋</h1>
      <p class="section-subtitle">Aquí está tu resumen académico del día.</p>
    </div>

    <div class="stats-cards">
      <article class="stat-card stat-card--lime" onclick="showSection('tareas')" role="button" tabindex="0" title="Ir a Tareas">
        <span class="stat-card__icon">◈</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="dash-pendientes-count">—</span>
          <span class="stat-card__label">Tareas pendientes</span>
        </div>
      </article>
      <article class="stat-card stat-card--coral" onclick="showSection('calendario')" role="button" tabindex="0" title="Ir a Calendario">
        <span class="stat-card__icon">◫</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="dash-semana-count">—</span>
          <span class="stat-card__label">Entregas esta semana</span>
        </div>
      </article>
      <article class="stat-card stat-card--sky" onclick="showSection('notas')" role="button" tabindex="0" title="Ir a Mis Notas">
        <span class="stat-card__icon">◎</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="dash-materias-count">—</span>
          <span class="stat-card__label">Materias con notas</span>
        </div>
      </article>
      <article class="stat-card stat-card--violet" onclick="showSection('notas')" role="button" tabindex="0" title="Ir a Mis Notas">
        <span class="stat-card__icon">⭐</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="dash-promedio">—</span>
          <span class="stat-card__label">Promedio general</span>
        </div>
      </article>
    </div>

    <div class="dashboard-grid">
      <div class="dash-card dash-card--clickable" onclick="showSection('tareas')" role="button" tabindex="0" title="Ir a Tareas">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Próximas entregas</h2>
          <button class="dash-card__link" onclick="event.stopPropagation();showSection('tareas')">Ver todas →</button>
        </div>
        <ul class="upcoming-list" id="dash-upcoming">
          <li style="color:var(--muted);font-size:.85rem;padding:.5rem 0;">Cargando tareas…</li>
        </ul>
      </div>
      <div class="dash-card dash-card--clickable" onclick="showSection('notas')" role="button" tabindex="0" title="Ir a Mis Notas">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Notas por materia</h2>
          <button class="dash-card__link" onclick="event.stopPropagation();showSection('notas')">Ver detalle →</button>
        </div>
        <ul class="grade-summary-list" id="dash-grades">
          <li style="color:var(--muted);font-size:.85rem;padding:.5rem 0;">Cargando notas…</li>
        </ul>
      </div>
    </div>

    <div class="dashboard-grid-3">
      <div class="dash-card">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Calendario</h2>
          <button class="dash-card__link" onclick="showSection('calendario')">Ver todo →</button>
        </div>
        <div class="mini-cal__nav">
          <button type="button" onclick="event.stopPropagation();miniCalPrev()">‹</button>
          <span id="mini-cal-label">—</span>
          <button type="button" onclick="event.stopPropagation();miniCalNext()">›</button>
        </div>
        <div class="mini-cal-grid" id="mini-cal-grid"></div>
      </div>

      <div class="dash-card dash-card--clickable" onclick="showSection('calendario')" role="button" tabindex="0" title="Ir a Calendario">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Próximos compromisos</h2>
          <button class="dash-card__link" onclick="event.stopPropagation();showSection('calendario')">Ver agenda →</button>
        </div>
        <div id="dash-compromisos">
          <p style="color:var(--muted);font-size:.85rem;">Cargando…</p>
        </div>
      </div>

      <div class="dash-card dash-card--clickable" onclick="showSection('estadisticas')" role="button" tabindex="0" title="Ir a Estadísticas">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Cursos activos</h2>
          <button class="dash-card__link" onclick="event.stopPropagation();showSection('estadisticas')">Ver todos →</button>
        </div>
        <div id="dash-cursos-activos">
          <p style="color:var(--muted);font-size:.85rem;">Cargando…</p>
        </div>
      </div>
    </div>

    <div class="dashboard-grid-2">
      <div class="dash-card dash-card--clickable" onclick="showSection('estadisticas')" role="button" tabindex="0" title="Ir a Estadísticas">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Progreso general</h2>
          <button class="dash-card__link" onclick="event.stopPropagation();showSection('estadisticas')">Ver estadísticas →</button>
        </div>
        <div class="progress-ring-wrap">
          <svg width="150" height="150" viewBox="0 0 150 150">
            <circle class="progress-ring-track" cx="75" cy="75" r="62"/>
            <circle class="progress-ring-value" id="progress-ring-circle" cx="75" cy="75" r="62"
                    stroke-dasharray="389.6" stroke-dashoffset="389.6"/>
            <text x="75" y="70" text-anchor="middle" class="progress-ring-center" id="progress-ring-pct" style="transform:rotate(90deg);transform-origin:75px 75px;">0%</text>
            <text x="75" y="88" text-anchor="middle" class="progress-ring-label" style="transform:rotate(90deg);transform-origin:75px 75px;fill:var(--muted);font-family:Inter,sans-serif;font-size:.62rem;">Completado</text>
          </svg>
          <p class="progress-ring-msg" id="progress-ring-msg">¡Vas muy bien!</p>
          <p class="progress-ring-sub" id="progress-ring-sub">Sigue así con tu excelente trabajo.</p>
        </div>
      </div>

      <div class="dash-card">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Actividad reciente</h2>
        </div>
        <div class="activity-feed" id="dash-activity">
          <p class="activity-empty">Cargando actividad…</p>
        </div>
      </div>

      <div class="dash-card">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Mensajes recientes</h2>
          <button class="dash-card__link" onclick="showSection('mensajes')">Ver todos →</button>
        </div>
        <div id="dash-mensajes-recientes">
          <p style="color:var(--muted);font-size:.85rem;">Cargando…</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════ CALENDARIO ══════════════ -->
  <section class="section" id="section-calendario">
    <div class="section-header">
      <h1 class="section-title">Calendario</h1>
      <p class="section-subtitle">Tus actividades y fechas de entrega. Todo actualizado en tiempo real.</p>
    </div>

    <div class="cal-controls">
      <div class="cal-nav">
        <button class="cal-nav__btn" id="prev-month" onclick="calPrev()">‹</button>
        <h2 class="cal-nav__title" id="cal-month-label">—</h2>
        <button class="cal-nav__btn" id="next-month" onclick="calNext()">›</button>
      </div>
      <button class="btn btn--primary" onclick="abrirModalTarea()" style="padding:.5rem 1.2rem;font-size:.88rem;">+ Nueva actividad</button>
    </div>

    <div class="cal-layout">
      <div class="calendar" id="calendar-grid">
        <!-- Generado por JS -->
      </div>
      <aside class="cal-sidebar">
        <h3 class="cal-sidebar__title" id="cal-day-title">Selecciona un día</h3>
        <div id="cal-day-events" style="display:flex;flex-direction:column;gap:.6rem;margin-bottom:1rem;">
          <p style="font-size:.85rem;color:var(--muted);">Haz clic en un día del calendario para ver sus actividades.</p>
        </div>
        <div class="cal-legend">
          <h4 class="cal-legend__title">Leyenda</h4>
          <div class="legend-item"><span class="legend-dot legend-dot--red"></span> Alta prioridad</div>
          <div class="legend-item"><span class="legend-dot" style="background:var(--amber)"></span> Media prioridad</div>
          <div class="legend-item"><span class="legend-dot legend-dot--lime"></span> Baja / completada</div>
        </div>
      </aside>
    </div>
  </section>

  <!-- ══════════════ TAREAS ══════════════ -->
  <section class="section" id="section-tareas">
    <div class="section-header">
      <h1 class="section-title">Organizador de Tareas</h1>
      <p class="section-subtitle">Crea y rastrea tus compromisos académicos. Los datos se guardan automáticamente.</p>
    </div>

    <div class="task-form-card" style="margin-bottom:1.5rem;">
      <h2 class="task-form-card__title">+ Nueva tarea</h2>
      <div class="task-form-grid">
        <div class="form-group">
          <label class="form-label" for="t-nombre">Nombre de la tarea</label>
          <input class="form-input" type="text" id="t-nombre" placeholder="Ej: Entregar diagrama ER"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="t-materia">Materia</label>
          <input class="form-input" type="text" id="t-materia" placeholder="Ej: Bases de Datos"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="t-fecha">Fecha límite</label>
          <input class="form-input" type="date" id="t-fecha"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="t-prioridad">Prioridad</label>
          <select class="form-input" id="t-prioridad">
            <option value="alta">🔴 Alta</option>
            <option value="media" selected>🟡 Media</option>
            <option value="baja">🟢 Baja</option>
          </select>
        </div>
      </div>
      <div class="form-group" style="margin-top:.5rem;">
        <label class="form-label" for="t-desc">Descripción (opcional)</label>
        <textarea class="form-input form-textarea" id="t-desc" placeholder="Detalles adicionales…"></textarea>
      </div>
      <button class="btn btn--primary" id="btn-crear-tarea" onclick="crearTarea()">Crear tarea</button>
    </div>

    <div class="kanban">
      <div class="kanban-col" id="col-pendiente">
        <div class="kanban-col__header kanban-col__header--red">
          <span>Pendiente</span><span class="kanban-count" id="count-pendiente">0</span>
        </div>
        <div class="kanban-col__body" id="tasks-pendiente">
          <div class="empty-col"><span>📋</span>Sin tareas pendientes</div>
        </div>
      </div>
      <div class="kanban-col" id="col-progreso">
        <div class="kanban-col__header kanban-col__header--amber">
          <span>En Progreso</span><span class="kanban-count" id="count-progreso">0</span>
        </div>
        <div class="kanban-col__body" id="tasks-progreso">
          <div class="empty-col"><span>🚧</span>Nada en progreso</div>
        </div>
      </div>
      <div class="kanban-col" id="col-done">
        <div class="kanban-col__header kanban-col__header--green">
          <span>Completada</span><span class="kanban-count" id="count-done">0</span>
        </div>
        <div class="kanban-col__body" id="tasks-done">
          <div class="empty-col"><span>✅</span>Sin tareas completadas</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════ PROYECTOS ══════════════ -->
  <section class="section" id="section-proyectos">
    <div class="section-header">
      <h1 class="section-title">Proyectos</h1>
      <p class="section-subtitle">Trabajos de mayor alcance: proyectos finales, entregas grupales y trabajos de varias semanas.</p>
    </div>

    <div class="task-form-card" style="margin-bottom:1.5rem;">
      <h2 class="task-form-card__title">+ Nuevo proyecto</h2>
      <div class="task-form-grid">
        <div class="form-group">
          <label class="form-label" for="proy-nombre">Nombre del proyecto</label>
          <input class="form-input" type="text" id="proy-nombre" placeholder="Ej: Sistema de inventario"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="proy-materia">Materia</label>
          <input class="form-input" type="text" id="proy-materia" placeholder="Ej: Ingeniería de Software"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="proy-fecha">Fecha de entrega</label>
          <input class="form-input" type="date" id="proy-fecha"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="proy-prioridad">Prioridad</label>
          <select class="form-input" id="proy-prioridad">
            <option value="alta">🔴 Alta</option>
            <option value="media" selected>🟡 Media</option>
            <option value="baja">🟢 Baja</option>
          </select>
        </div>
      </div>
      <div class="form-group" style="margin-top:.5rem;">
        <label class="form-label" for="proy-desc">Descripción / entregables (opcional)</label>
        <textarea class="form-input form-textarea" id="proy-desc" placeholder="Alcance, integrantes, requisitos…"></textarea>
      </div>
      <button class="btn btn--primary" id="btn-crear-proyecto" onclick="crearProyecto()">Crear proyecto</button>
    </div>

    <div class="kanban">
      <div class="kanban-col" id="col-p-planificacion">
        <div class="kanban-col__header kanban-col__header--red">
          <span>Planificación</span><span class="kanban-count" id="count-p-planificacion">0</span>
        </div>
        <div class="kanban-col__body" id="proy-planificacion">
          <div class="empty-col"><span>🗂️</span>Sin proyectos en planificación</div>
        </div>
      </div>
      <div class="kanban-col" id="col-p-progreso">
        <div class="kanban-col__header kanban-col__header--amber">
          <span>En Progreso</span><span class="kanban-count" id="count-p-progreso">0</span>
        </div>
        <div class="kanban-col__body" id="proy-progreso">
          <div class="empty-col"><span>🚧</span>Nada en progreso</div>
        </div>
      </div>
      <div class="kanban-col" id="col-p-completado">
        <div class="kanban-col__header kanban-col__header--green">
          <span>Completado</span><span class="kanban-count" id="count-p-completado">0</span>
        </div>
        <div class="kanban-col__body" id="proy-completado">
          <div class="empty-col"><span>✅</span>Sin proyectos completados</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════ NOTAS ══════════════ -->
  <section class="section" id="section-notas">
    <div class="section-header">
      <h1 class="section-title">Mis Notas</h1>
      <p class="section-subtitle">Registra tus calificaciones por materia. El promedio se calcula automáticamente.</p>
    </div>

    <div class="task-form-card" style="margin-bottom:1.5rem;">
      <h2 class="task-form-card__title">Agregar nota</h2>
      <div class="task-form-grid">
        <div class="form-group">
          <label class="form-label" for="n-materia">Materia</label>
          <input class="form-input" type="text" id="n-materia" placeholder="Ej: Bases de Datos"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="n-descripcion">Descripción</label>
          <input class="form-input" type="text" id="n-descripcion" placeholder="Ej: Taller 2, Quiz de repaso…"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="n-tipo">Tipo</label>
          <select class="form-input" id="n-tipo" onchange="toggleTipoNota()">
            <option value="nota">Nota (número)</option>
            <option value="firma">Firma del profesor</option>
          </select>
        </div>
        <div class="form-group" id="n-valor-wrap">
          <label class="form-label" for="n-valor">Nota</label>
          <input class="form-input" type="number" id="n-valor" min="0" max="5" step="0.1" placeholder="0.0"/>
        </div>
      </div>
      <button class="btn btn--primary" id="btn-guardar-nota" onclick="guardarNota()">Guardar</button>
    </div>

    <!-- Gráfico de promedios por materia -->
    <div class="table-card" style="margin-bottom:1.5rem;">
      <div class="chart-card__header">
        <h2 class="table-card__title">Promedios por materia</h2>
        <p>Compara tu rendimiento de un vistazo (solo se promedian las notas numéricas). Haz clic en 🎨 sobre cada barra para cambiar su color o textura.</p>
      </div>
      <div class="bar-chart" id="bar-chart">
        <!-- Generado por JS -->
      </div>
    </div>

    <!-- Tabla de notas -->
    <div class="table-card">
      <h2 class="table-card__title">Tabla de notas</h2>
      <div class="table-wrapper">
        <table class="grades-table" id="tabla-notas">
          <thead>
            <tr>
              <th>Materia</th>
              <th>Descripción</th>
              <th>Tipo</th>
              <th>Nota / Estado</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="notas-tbody">
            <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:2rem;">Cargando notas…</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ══════════════ MENSAJES (notas/recordatorios personales) ══════════════ -->
  <section class="section" id="section-mensajes">
    <div class="section-header">
      <h1 class="section-title">Mensajes</h1>
      <p class="section-subtitle">Recordatorios y notas rápidas que te dejas a ti mismo. Nada se te escapa.</p>
    </div>

    <div class="task-form-card" style="margin-bottom:1.5rem;">
      <h2 class="task-form-card__title">+ Nuevo mensaje</h2>
      <div class="form-group">
        <label class="form-label" for="m-titulo">Título</label>
        <input class="form-input" type="text" id="m-titulo" placeholder="Ej: Llamar al profesor de Cálculo"/>
      </div>
      <div class="form-group" style="margin-top:.5rem;">
        <label class="form-label" for="m-contenido">Nota (opcional)</label>
        <textarea class="form-input form-textarea" id="m-contenido" placeholder="Detalles…"></textarea>
      </div>
      <button class="btn btn--primary" id="btn-crear-mensaje" onclick="crearMensaje()">Guardar mensaje</button>
    </div>

    <div class="dash-card">
      <div class="dash-card__header">
        <h2 class="dash-card__title">Tus mensajes</h2>
      </div>
      <div id="mensajes-list" style="display:flex;flex-direction:column;gap:.6rem;">
        <p style="color:var(--muted);font-size:.85rem;">Cargando mensajes…</p>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════
       HORARIO
  ════════════════════════════════════════════════ -->
  <section class="section" id="section-horario">
    <div class="section-header">
      <h1 class="section-title">Horario</h1>
      <p class="section-subtitle">Organiza tu semana hora por hora. Se guarda automáticamente en este dispositivo.</p>
    </div>

    <div class="table-card">
      <div class="table-card__header-row">
        <h2 class="table-card__title">Mi horario semanal</h2>
        <button class="btn btn--primary" type="button" onclick="agregarFilaHorario()">+ Agregar hora</button>
      </div>

      <div class="table-wrapper">
        <table class="grades-table horario-table" id="tabla-horario">
          <thead>
            <tr>
              <th class="horario-th-hora">Hora</th>
              <th>Lunes</th>
              <th>Martes</th>
              <th>Miércoles</th>
              <th>Jueves</th>
              <th>Viernes</th>
              <th>Sábado</th>
              <th>Domingo</th>
              <th class="horario-th-accion"></th>
            </tr>
          </thead>
          <tbody id="horario-tbody">
            <!-- Generado por JS -->
          </tbody>
        </table>
      </div>

      <p class="horario-empty-hint" id="horario-empty-hint" style="display:none;">
        Aún no tienes horas agregadas. Haz clic en <strong>+ Agregar hora</strong> para empezar.
      </p>
    </div>
  </section>

  <!-- ══════════════ ESTADÍSTICAS ══════════════ -->
  <section class="section" id="section-estadisticas">
    <div class="section-header">
      <h1 class="section-title">Estadísticas</h1>
      <p class="section-subtitle">Tu progreso en números, calculado en tiempo real a partir de tus tareas, proyectos y notas.</p>
    </div>

    <div class="stats-cards">
      <article class="stat-card stat-card--lime">
        <span class="stat-card__icon">◈</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="est-tareas-pct">—</span>
          <span class="stat-card__label">Tareas completadas</span>
        </div>
      </article>
      <article class="stat-card stat-card--coral">
        <span class="stat-card__icon">◆</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="est-proyectos-pct">—</span>
          <span class="stat-card__label">Proyectos completados</span>
        </div>
      </article>
      <article class="stat-card stat-card--sky">
        <span class="stat-card__icon">⭐</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="est-promedio">—</span>
          <span class="stat-card__label">Promedio general</span>
        </div>
      </article>
      <article class="stat-card stat-card--violet">
        <span class="stat-card__icon">🔥</span>
        <div class="stat-card__body">
          <span class="stat-card__value" id="est-racha">—</span>
          <span class="stat-card__label">Tareas completadas (7 días)</span>
        </div>
      </article>
    </div>

    <div class="dashboard-grid">
      <div class="dash-card">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Actividad de la semana</h2>
        </div>
        <p style="font-size:.85rem;color:var(--muted);margin-bottom:.5rem;">Tareas completadas por día, últimos 7 días.</p>
        <div class="bar-chart" id="est-bar-chart" style="gap:1rem;"></div>
      </div>
      <div class="dash-card">
        <div class="dash-card__header">
          <h2 class="dash-card__title">Progreso por materia</h2>
        </div>
        <ul class="grade-summary-list" id="est-materias-list">
          <li style="color:var(--muted);font-size:.85rem;padding:.5rem 0;">Calculando…</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════
       PERFIL
  ════════════════════════════════════════════════ -->
  <section class="section" id="section-perfil">
    <div class="section-header">
      <h1 class="section-title">Mi Perfil</h1>
      <p class="section-subtitle">Esta información se guarda en tu cuenta y es visible solo para ti.</p>
    </div>

    <div class="task-form-card" style="margin-bottom:1.5rem;">
      <div class="perfil-avatar-row">
        <div class="perfil-avatar" id="perfil-avatar-preview"><?= $iniciales ?></div>
        <div class="perfil-avatar-actions">
          <button type="button" class="btn btn--primary" onclick="document.getElementById('perfil-foto-input').click()">Cambiar foto</button>
          <p class="perfil-avatar-hint">JPG, PNG o WEBP. Máximo 3MB.</p>
          <input type="file" id="perfil-foto-input" accept="image/png, image/jpeg, image/webp" style="display:none;" onchange="subirFotoPerfil(this.files[0])">
        </div>
      </div>
    </div>

    <div class="task-form-card">
      <h2 class="task-form-card__title">Información personal</h2>
      <div class="perfil-form-grid">
        <div class="form-group">
          <label class="form-label" for="p-nombre">Nombre completo</label>
          <input class="form-input" type="text" id="p-nombre" placeholder="Tu nombre"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="p-edad">Edad</label>
          <input class="form-input" type="number" id="p-edad" min="10" max="100" placeholder="Ej: 17"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="p-colegio">Colegio / Institución</label>
          <input class="form-input" type="text" id="p-colegio" placeholder="Ej: IE Simón Bolívar"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="p-grado">Grado / Semestre</label>
          <input class="form-input" type="text" id="p-grado" placeholder="Ej: 11° o Semestre 3"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="p-telefono">Teléfono</label>
          <input class="form-input" type="tel" id="p-telefono" placeholder="Ej: 3001234567"/>
        </div>
        <div class="form-group">
          <label class="form-label" for="p-correo">Correo</label>
          <input class="form-input" type="email" id="p-correo" disabled title="El correo no se puede cambiar aquí"/>
        </div>
      </div>
      <div class="form-group" style="max-width:520px;">
        <label class="form-label" for="p-bio">Sobre mí</label>
        <textarea class="form-input form-textarea" id="p-bio" maxlength="200" placeholder="Una frase corta sobre ti (opcional)"></textarea>
      </div>
      <button class="btn btn--primary" id="btn-guardar-perfil" onclick="guardarPerfil()">Guardar cambios</button>
    </div>
  </section>

</main>


<div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
<div class="toast" id="toast" role="alert" aria-live="polite"></div>

<!-- ════════════════════════════════════════════════
     JAVASCRIPT — Toda la lógica dinámica
════════════════════════════════════════════════ -->
<script>
// ─────────────────────────────────────────────────────────────
// ESTADO GLOBAL
// ─────────────────────────────────────────────────────────────
let TAREAS = [];      // Array de tareas del usuario
let NOTAS  = [];      // Array de notas del usuario
let CAL_Y  = new Date().getFullYear();
let CAL_M  = new Date().getMonth();  // 0-11
let CAL_SEL_DAY = null;

const MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

// ─────────────────────────────────────────────────────────────
// NAVEGACIÓN
// ─────────────────────────────────────────────────────────────
const sectionTitles = { dashboard:'Panel General', calendario:'Calendario', tareas:'Tareas', proyectos:'Proyectos', notas:'Mis Notas', mensajes:'Mensajes', horario:'Horario', estadisticas:'Estadísticas', perfil:'Mi Perfil' };

function showSection(id) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
  const target = document.getElementById('section-' + id);
  if (target) target.classList.add('active');
  const nav = document.querySelector('.nav-item[data-section="' + id + '"]');
  if (nav) nav.classList.add('active');
  document.getElementById('topbar-title').textContent = sectionTitles[id] || id;
  if (id === 'estadisticas') renderEstadisticas();
  if (window.innerWidth < 768) {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('show');
  }
}

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('sidebar-overlay').classList.toggle('show');
}

// ─────────────────────────────────────────────────────────────
// TOAST
// ─────────────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3200);
}

// ─────────────────────────────────────────────────────────────
// LOADING BAR
// ─────────────────────────────────────────────────────────────
function showLoader() { document.getElementById('loading-bar').style.display='block'; }
function hideLoader() { document.getElementById('loading-bar').style.display='none'; }

// ─────────────────────────────────────────────────────────────
// FETCH HELPER
// ─────────────────────────────────────────────────────────────
async function apiPost(url, body) {
  showLoader();
  const fd = new FormData();
  for (const k in body) fd.append(k, body[k]);
  const r = await fetch(url, { method:'POST', body:fd });
  hideLoader();
  return r.json();
}

// ─────────────────────────────────────────────────────────────
// DUVICITO — Bienvenida / Onboarding
// ─────────────────────────────────────────────────────────────
function initDuvicito() {
  if (!localStorage.getItem('duvicito_visto')) {
    document.getElementById('duvicito-overlay').classList.add('show');
    document.getElementById('duvicito-nombre-input').focus();
  }
}

function guardarNombreDuvicito() {
  const input = document.getElementById('duvicito-nombre-input');
  const nombre = input.value.trim() || 'Duvicito';
  localStorage.setItem('duvicito_nombre', nombre);
  localStorage.setItem('duvicito_visto', '1');
  cerrarDuvicito();
  showToast('🎉 ¡Listo! Ahora me llamo ' + nombre + '.');
}

function cerrarDuvicito() {
  localStorage.setItem('duvicito_visto', '1');
  document.getElementById('duvicito-overlay').classList.remove('show');
}

document.getElementById('duvicito-nombre-input')?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') guardarNombreDuvicito();
});

// ─────────────────────────────────────────────────────────────
// TAREAS — Cargar desde servidor
// ─────────────────────────────────────────────────────────────
async function cargarTareas() {
  const data = await apiPost('api_tareas.php', { action:'listar' });
  if (!data.ok) return;
  TAREAS = data.tareas;
  renderKanban();
  renderCalendario();
  updateDashboard();
}

// ─────────────────────────────────────────────────────────────
// TAREAS — Crear nueva
// ─────────────────────────────────────────────────────────────
async function crearTarea() {
  const nombre    = document.getElementById('t-nombre').value.trim();
  const materia   = document.getElementById('t-materia').value.trim();
  const fecha     = document.getElementById('t-fecha').value;
  const prioridad = document.getElementById('t-prioridad').value;
  const desc      = document.getElementById('t-desc').value.trim();

  if (!nombre) { showToast('⚠ Escribe el nombre de la tarea.'); return; }

  const btn = document.getElementById('btn-crear-tarea');
  btn.innerHTML = '<span class="spinner"></span> Guardando…';
  btn.disabled = true;

  const data = await apiPost('api_tareas.php', { action:'crear', nombre, materia, fecha, prioridad, descripcion:desc });

  btn.innerHTML = 'Crear tarea';
  btn.disabled = false;

  if (data.ok) {
    document.getElementById('t-nombre').value = '';
    document.getElementById('t-materia').value = '';
    document.getElementById('t-fecha').value = '';
    document.getElementById('t-desc').value = '';
    showToast('✅ Tarea "' + nombre + '" creada.');
    await cargarTareas();
  } else {
    showToast('❌ Error: ' + data.msg);
  }
}

// ─────────────────────────────────────────────────────────────
// TAREAS — Cambiar estado
// ─────────────────────────────────────────────────────────────
async function cambiarEstado(id, estado) {
  await apiPost('api_tareas.php', { action:'estado', id, estado });
  await cargarTareas();
  showToast(estado === 'done' ? '🎉 ¡Tarea completada!' : estado === 'progreso' ? '▶ En progreso.' : '↩ Movida a pendiente.');
}

// ─────────────────────────────────────────────────────────────
// TAREAS — Eliminar
// ─────────────────────────────────────────────────────────────
async function eliminarTarea(id) {
  if (!confirm('¿Eliminar esta tarea?')) return;
  await apiPost('api_tareas.php', { action:'eliminar', id });
  await cargarTareas();
  showToast('🗑 Tarea eliminada.');
}

// ─────────────────────────────────────────────────────────────
// KANBAN — Renderizar columnas
// ─────────────────────────────────────────────────────────────
function renderKanban() {
  const cols = { pendiente:[], progreso:[], done:[] };
  TAREAS.forEach(t => { if (cols[t.estado]) cols[t.estado].push(t); });

  for (const estado in cols) {
    const body = document.getElementById('tasks-' + estado);
    const count = document.getElementById('count-' + estado);
    count.textContent = cols[estado].length;

    if (cols[estado].length === 0) {
      const empty = { pendiente:'📋 Sin tareas pendientes', progreso:'🚧 Nada en progreso', done:'✅ Sin completadas' };
      body.innerHTML = '<div class="empty-col"><span>' + empty[estado].split(' ')[0] + '</span>' + empty[estado].slice(2) + '</div>';
      continue;
    }

    body.innerHTML = cols[estado].map(t => {
      const priColor = t.prioridad === 'alta' ? 'var(--coral)' : t.prioridad === 'media' ? 'var(--amber)' : 'var(--mint)';
      const priLabel = { alta:'🔴 Alta', media:'🟡 Media', baja:'🟢 Baja' }[t.prioridad] || t.prioridad;
      const fechaStr = t.fecha_limite ? '📅 ' + t.fecha_limite : '';
      const acciones = estado !== 'done'
        ? `<button class="task-btn" title="En progreso" onclick="cambiarEstado(${t.id},'progreso')">▶</button>
           <button class="task-btn task-btn--done" title="Completar" onclick="cambiarEstado(${t.id},'done')">✓</button>`
        : `<button class="task-btn" title="Mover a pendiente" onclick="cambiarEstado(${t.id},'pendiente')" style="font-size:.9rem">↩</button>`;
      return `
        <article class="task-card ${estado==='done'?'task-card--done':''} ${t.prioridad==='alta'?'task-card--high':''}" style="${estado!=='done'?'border-left:3px solid '+priColor:''}">
          <div class="task-card__top">
            <span class="task-tag-custom">${t.materia||'General'}</span>
            <span class="task-priority task-priority--${t.prioridad}">${priLabel}</span>
          </div>
          <h3 class="task-card__name">${t.nombre}</h3>
          ${t.descripcion ? `<p class="task-card__desc">${t.descripcion}</p>` : ''}
          <div class="task-card__footer">
            <span class="task-card__due">${fechaStr}</span>
            <div class="task-card__actions">
              ${acciones}
              <button class="task-btn" title="Eliminar" onclick="eliminarTarea(${t.id})" style="color:var(--coral)">✕</button>
            </div>
          </div>
        </article>`;
    }).join('');
  }
}

// ─────────────────────────────────────────────────────────────
// PROYECTOS — Cargar / Crear / Cambiar estado / Eliminar / Render
// ─────────────────────────────────────────────────────────────
let PROYECTOS = [];

async function cargarProyectos() {
  const data = await apiPost('api_proyectos.php', { action:'listar' });
  if (!data.ok) return;
  PROYECTOS = data.proyectos;
  renderKanbanProyectos();
  updateDashboard();
}

async function crearProyecto() {
  const nombre    = document.getElementById('proy-nombre').value.trim();
  const materia   = document.getElementById('proy-materia').value.trim();
  const fecha     = document.getElementById('proy-fecha').value;
  const prioridad = document.getElementById('proy-prioridad').value;
  const desc      = document.getElementById('proy-desc').value.trim();

  if (!nombre) { showToast('⚠ Escribe el nombre del proyecto.'); return; }

  const btn = document.getElementById('btn-crear-proyecto');
  btn.innerHTML = '<span class="spinner"></span> Guardando…';
  btn.disabled = true;

  const data = await apiPost('api_proyectos.php', { action:'crear', nombre, materia, fecha, prioridad, descripcion:desc });

  btn.innerHTML = 'Crear proyecto';
  btn.disabled = false;

  if (data.ok) {
    document.getElementById('proy-nombre').value = '';
    document.getElementById('proy-materia').value = '';
    document.getElementById('proy-fecha').value = '';
    document.getElementById('proy-desc').value = '';
    showToast('✅ Proyecto "' + nombre + '" creado.');
    await cargarProyectos();
  } else {
    showToast('❌ Error: ' + data.msg);
  }
}

async function cambiarEstadoProyecto(id, estado) {
  await apiPost('api_proyectos.php', { action:'estado', id, estado });
  await cargarProyectos();
  showToast(estado === 'completado' ? '🎉 ¡Proyecto completado!' : estado === 'progreso' ? '▶ En progreso.' : '↩ Movido a planificación.');
}

async function eliminarProyecto(id) {
  if (!confirm('¿Eliminar este proyecto?')) return;
  await apiPost('api_proyectos.php', { action:'eliminar', id });
  await cargarProyectos();
  showToast('🗑 Proyecto eliminado.');
}

function renderKanbanProyectos() {
  const cols = { planificacion:[], progreso:[], completado:[] };
  PROYECTOS.forEach(p => { if (cols[p.estado]) cols[p.estado].push(p); });

  for (const estado in cols) {
    const body = document.getElementById('proy-' + estado);
    const count = document.getElementById('count-p-' + estado);
    count.textContent = cols[estado].length;

    if (cols[estado].length === 0) {
      const empty = { planificacion:'🗂️ Sin proyectos en planificación', progreso:'🚧 Nada en progreso', completado:'✅ Sin proyectos completados' };
      body.innerHTML = '<div class="empty-col"><span>' + empty[estado].split(' ')[0] + '</span>' + empty[estado].slice(2) + '</div>';
      continue;
    }

    body.innerHTML = cols[estado].map(p => {
      const priColor = p.prioridad === 'alta' ? 'var(--coral)' : p.prioridad === 'media' ? 'var(--amber)' : 'var(--mint)';
      const priLabel = { alta:'🔴 Alta', media:'🟡 Media', baja:'🟢 Baja' }[p.prioridad] || p.prioridad;
      const fechaStr = p.fecha_entrega ? '📅 ' + p.fecha_entrega : '';
      const acciones = estado !== 'completado'
        ? `<button class="task-btn" title="En progreso" onclick="cambiarEstadoProyecto(${p.id},'progreso')">▶</button>
           <button class="task-btn task-btn--done" title="Completar" onclick="cambiarEstadoProyecto(${p.id},'completado')">✓</button>`
        : `<button class="task-btn" title="Mover a planificación" onclick="cambiarEstadoProyecto(${p.id},'planificacion')" style="font-size:.9rem">↩</button>`;
      return `
        <article class="task-card ${estado==='completado'?'task-card--done':''} ${p.prioridad==='alta'?'task-card--high':''}" style="${estado!=='completado'?'border-left:3px solid '+priColor:''}">
          <div class="task-card__top">
            <span class="task-tag-custom">${p.materia||'General'}</span>
            <span class="task-priority task-priority--${p.prioridad}">${priLabel}</span>
          </div>
          <h3 class="task-card__name">${p.nombre}</h3>
          ${p.descripcion ? `<p class="task-card__desc">${p.descripcion}</p>` : ''}
          <div class="task-card__footer">
            <span class="task-card__due">${fechaStr}</span>
            <div class="task-card__actions">
              ${acciones}
              <button class="task-btn" title="Eliminar" onclick="eliminarProyecto(${p.id})" style="color:var(--coral)">✕</button>
            </div>
          </div>
        </article>`;
    }).join('');
  }
}

// ─────────────────────────────────────────────────────────────
// MENSAJES — Cargar / Crear / Marcar leído / Eliminar / Render
// ─────────────────────────────────────────────────────────────
let MENSAJES = [];

async function cargarMensajes() {
  const data = await apiPost('api_mensajes.php', { action:'listar' });
  if (!data.ok) return;
  MENSAJES = data.mensajes;
  renderMensajes();
  updateDashboard();
}

async function crearMensaje() {
  const titulo    = document.getElementById('m-titulo').value.trim();
  const contenido = document.getElementById('m-contenido').value.trim();

  if (!titulo) { showToast('⚠ Escribe un título para el mensaje.'); return; }

  const btn = document.getElementById('btn-crear-mensaje');
  btn.innerHTML = '<span class="spinner"></span> Guardando…';
  btn.disabled = true;

  const data = await apiPost('api_mensajes.php', { action:'crear', titulo, contenido });

  btn.innerHTML = 'Guardar mensaje';
  btn.disabled = false;

  if (data.ok) {
    document.getElementById('m-titulo').value = '';
    document.getElementById('m-contenido').value = '';
    showToast('✅ Mensaje guardado.');
    await cargarMensajes();
  } else {
    showToast('❌ Error: ' + data.msg);
  }
}

async function toggleLeidoMensaje(id) {
  await apiPost('api_mensajes.php', { action:'leido', id });
  await cargarMensajes();
}

async function eliminarMensaje(id) {
  if (!confirm('¿Eliminar este mensaje?')) return;
  await apiPost('api_mensajes.php', { action:'eliminar', id });
  await cargarMensajes();
  showToast('🗑 Mensaje eliminado.');
}

function renderMensajes() {
  const list = document.getElementById('mensajes-list');
  const badge = document.getElementById('mensajes-badge');
  if (!list) return;

  const noLeidos = MENSAJES.filter(m => m.leido == 0).length;
  if (badge) {
    badge.style.display = noLeidos > 0 ? 'inline-flex' : 'none';
    badge.textContent = noLeidos > 9 ? '9+' : noLeidos;
  }

  if (MENSAJES.length === 0) {
    list.innerHTML = '<p style="color:var(--muted);font-size:.85rem;">Aún no tienes mensajes. Escríbete el primero arriba ↑</p>';
    return;
  }

  list.innerHTML = MENSAJES.map(m => `
    <div class="mensaje-item ${m.leido == 0 ? 'mensaje-item--unread' : ''}">
      <span class="mensaje-item__icon">${m.leido == 0 ? '📩' : '📨'}</span>
      <div class="mensaje-item__body">
        <div class="mensaje-item__title">${m.titulo}</div>
        ${m.contenido ? `<div class="mensaje-item__content">${m.contenido}</div>` : ''}
        <span class="mensaje-item__time">${timeAgo(m.creado_en)}</span>
      </div>
      <div class="mensaje-item__actions">
        <button class="task-btn" title="${m.leido == 0 ? 'Marcar leído' : 'Marcar no leído'}" onclick="toggleLeidoMensaje(${m.id})">${m.leido == 0 ? '✓' : '↺'}</button>
        <button class="task-btn" title="Eliminar" onclick="eliminarMensaje(${m.id})" style="color:var(--coral)">✕</button>
      </div>
    </div>
  `).join('');
}

// ─────────────────────────────────────────────────────────────
// ESTADÍSTICAS — Todo calculado en el navegador a partir de
// TAREAS, PROYECTOS y NOTAS ya cargadas (sin llamadas nuevas al backend)
// ─────────────────────────────────────────────────────────────
function renderEstadisticas() {
  // Tareas
  const totalTareas = TAREAS.length;
  const tareasDone  = TAREAS.filter(t => t.estado === 'done').length;
  document.getElementById('est-tareas-pct').textContent =
    totalTareas === 0 ? '—' : Math.round((tareasDone/totalTareas)*100) + '%';

  // Proyectos
  const totalProy = PROYECTOS.length;
  const proyDone  = PROYECTOS.filter(p => p.estado === 'completado').length;
  document.getElementById('est-proyectos-pct').textContent =
    totalProy === 0 ? '—' : Math.round((proyDone/totalProy)*100) + '%';

  // Promedio general (solo notas numéricas)
  const notasNum = NOTAS.filter(n => n.tipo === 'nota');
  document.getElementById('est-promedio').textContent = notasNum.length === 0
    ? '—'
    : (notasNum.reduce((s,n) => s + parseFloat(n.valor), 0) / notasNum.length).toFixed(1);

  // Racha: tareas completadas en los últimos 7 días (según fecha_limite, aproximado)
  const hoy = new Date(); hoy.setHours(0,0,0,0);
  const hace7 = new Date(hoy); hace7.setDate(hoy.getDate()-6);
  const porDia = [0,0,0,0,0,0,0]; // hace7 → hoy
  TAREAS.filter(t => t.estado === 'done' && t.fecha_limite).forEach(t => {
    const d = new Date(t.fecha_limite + 'T00:00:00');
    const diff = Math.round((d - hace7) / 86400000);
    if (diff >= 0 && diff <= 6) porDia[diff]++;
  });
  const totalSemana = porDia.reduce((a,b)=>a+b,0);
  document.getElementById('est-racha').textContent = totalSemana;

  // Barras de la semana (reutiliza las clases .bar-track / .bar-fill ya existentes)
  const dias = ['D','L','M','X','J','V','S'];
  const maxDia = Math.max(1, ...porDia);
  const chart = document.getElementById('est-bar-chart');
  chart.innerHTML = porDia.map((val, i) => {
    const d = new Date(hace7); d.setDate(hace7.getDate()+i);
    const pct = Math.max(4, (val / maxDia) * 100);
    return `
      <div class="bar-col">
        <span class="bar-value">${val}</span>
        <div class="bar-track"><div class="bar-fill" style="height:${pct}%;background:var(--purple);"></div></div>
        <span class="bar-label">${dias[d.getDay()]}</span>
      </div>`;
  }).join('');

  // Progreso por materia: % de tareas completadas, agrupado por materia
  const porMateria = {};
  TAREAS.forEach(t => {
    const mat = t.materia || 'General';
    if (!porMateria[mat]) porMateria[mat] = { total:0, done:0 };
    porMateria[mat].total++;
    if (t.estado === 'done') porMateria[mat].done++;
  });
  const materias = Object.keys(porMateria);
  const listEl = document.getElementById('est-materias-list');
  if (materias.length === 0) {
    listEl.innerHTML = '<li style="color:var(--muted);font-size:.85rem;padding:.5rem 0;">Agrega tareas con materia para ver tu progreso aquí.</li>';
  } else {
    listEl.innerHTML = materias.map(mat => {
      const { total, done } = porMateria[mat];
      const pct = Math.round((done/total)*100);
      const cls = pct>=80?'great':pct>=50?'good':pct>=25?'warn':'bad';
      return `<li class="grade-row">
        <span class="grade-row__subject">${mat}</span>
        <div class="grade-row__bar-wrap"><div class="grade-row__bar" style="--pct:${pct}%"></div></div>
        <span class="grade-row__score grade-row__score--${cls}">${pct}%</span>
      </li>`;
    }).join('');
  }
}

// ─────────────────────────────────────────────────────────────
// NOTAS — Cargar
// ─────────────────────────────────────────────────────────────
async function cargarNotas() {
  const data = await apiPost('api_notas.php', { action:'listar' });
  if (!data.ok) return;
  NOTAS = data.notas;
  renderTablaNotas();
  renderGraficoNotas();
  updateDashboard();
}

// ─────────────────────────────────────────────────────────────
// NOTAS — Mostrar/ocultar el campo de valor según el tipo
// ─────────────────────────────────────────────────────────────
function toggleTipoNota() {
  const esNota = document.getElementById('n-tipo').value === 'nota';
  document.getElementById('n-valor-wrap').style.display = esNota ? '' : 'none';
}

// ─────────────────────────────────────────────────────────────
// NOTAS — Guardar
// ─────────────────────────────────────────────────────────────
async function guardarNota() {
  const materia     = document.getElementById('n-materia').value.trim();
  const descripcion = document.getElementById('n-descripcion').value.trim();
  const tipo         = document.getElementById('n-tipo').value;
  const valor         = document.getElementById('n-valor').value;

  if (!materia) { showToast('⚠ Escribe el nombre de la materia.'); return; }
  if (tipo === 'nota' && valor === '') { showToast('⚠ Escribe la nota (0.0 a 5.0).'); return; }

  const btn = document.getElementById('btn-guardar-nota');
  btn.innerHTML = '<span class="spinner"></span> Guardando…';
  btn.disabled = true;

  const data = await apiPost('api_notas.php', { action:'guardar', materia, descripcion, tipo, valor });

  btn.innerHTML = 'Guardar';
  btn.disabled = false;

  if (data.ok) {
    document.getElementById('n-materia').value = '';
    document.getElementById('n-descripcion').value = '';
    document.getElementById('n-valor').value = '';
    showToast('✅ Guardado en "' + materia + '".');
    await cargarNotas();
  } else {
    showToast('❌ Error: ' + data.msg);
  }
}

async function eliminarNota(id) {
  if (!confirm('¿Eliminar esta entrada?')) return;
  const data = await apiPost('api_notas.php', { action:'eliminar', id });
  if (data.ok) { showToast('🗑 Eliminado.'); await cargarNotas(); }
  else showToast('❌ Error: ' + data.msg);
}

async function alternarFirma(id) {
  const data = await apiPost('api_notas.php', { action:'firmar', id });
  if (data.ok) await cargarNotas();
  else showToast('❌ Error: ' + data.msg);
}

// ─────────────────────────────────────────────────────────────
// NOTAS — Renderizar tabla
// ─────────────────────────────────────────────────────────────
function renderTablaNotas() {
  const tbody = document.getElementById('notas-tbody');
  if (NOTAS.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--muted);padding:2rem;">Aún no has registrado nada. Usa el formulario de arriba. 👆</td></tr>';
    return;
  }
  tbody.innerHTML = NOTAS.map(n => {
    let colValor, colTipo;
    if (n.tipo === 'firma') {
      colTipo = 'Firma';
      colValor = n.firmado == 1
        ? `<span class="badge badge--ok">✓ Firmado</span>`
        : `<span class="badge badge--bad">Pendiente</span>`;
      colValor += ` <button onclick="alternarFirma(${n.id})" style="background:none;border:none;cursor:pointer;color:var(--purple);font-size:.8rem;margin-left:.4rem;">Marcar ${n.firmado == 1 ? 'pendiente' : 'firmado'}</button>`;
    } else {
      const v = parseFloat(n.valor);
      const cls = v >= 4.5 ? 'score-great' : v >= 3.5 ? 'score-good' : v >= 3.0 ? 'score-warn' : 'score-bad';
      colTipo = 'Nota';
      colValor = `<span class="${cls}"><strong>${v.toFixed(1)}</strong></span>`;
    }
    return `<tr>
      <td><strong>${n.materia}</strong></td>
      <td>${n.descripcion ? n.descripcion : '<span style="color:var(--muted);">—</span>'}</td>
      <td>${colTipo}</td>
      <td>${colValor}</td>
      <td><button onclick="eliminarNota(${n.id})" style="background:none;border:none;cursor:pointer;color:var(--coral);font-size:.85rem;">✕ Eliminar</button></td>
    </tr>`;
  }).join('');
}

// ─────────────────────────────────────────────────────────────
// GRÁFICO DE PROMEDIOS — Barras personalizables
// ─────────────────────────────────────────────────────────────
const BAR_PALETTE = ['#5B4BF5','#FF5C57','#00C9A7','#5bb8ff','#a78bff','#ffc047'];

function cargarEstilosBarras() {
  try { return JSON.parse(localStorage.getItem('notas_estilos') || '{}'); }
  catch(e) { return {}; }
}
function guardarEstilosBarras(obj) {
  localStorage.setItem('notas_estilos', JSON.stringify(obj));
}

// Genera el background CSS (color + textura) para una barra
function fondoBarra(color, patron) {
  const enc = s => encodeURIComponent(s);
  switch (patron) {
    case 'rayas':
      return {
        backgroundColor: color,
        backgroundImage: 'repeating-linear-gradient(45deg, rgba(255,255,255,.4) 0, rgba(255,255,255,.4) 6px, transparent 6px, transparent 14px)'
      };
    case 'espiral':
      return {
        backgroundColor: color,
        backgroundImage: 'repeating-conic-gradient(from 0deg, rgba(255,255,255,.45) 0deg 10deg, transparent 10deg 24deg)',
        backgroundSize: '24px 24px'
      };
    case 'corazones': {
      const heart = `<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20'><path d='M10 16.5 C10 16.5 2.5 11.8 2.5 6.9 C2.5 3.9 5 2.5 7.3 3.6 C8.5 4.2 9.5 5.2 10 6.2 C10.5 5.2 11.5 4.2 12.7 3.6 C15 2.5 17.5 3.9 17.5 6.9 C17.5 11.8 10 16.5 10 16.5 Z' fill='rgba(255,255,255,.55)'/></svg>`;
      return {
        backgroundColor: color,
        backgroundImage: `url("data:image/svg+xml,${enc(heart)}")`,
        backgroundSize: '20px 20px'
      };
    }
    case 'lunares':
      return {
        backgroundColor: color,
        backgroundImage: 'radial-gradient(rgba(255,255,255,.5) 3px, transparent 3.5px)',
        backgroundSize: '14px 14px'
      };
    default: // solido
      return { backgroundColor: color, backgroundImage: 'none' };
  }
}

function aplicarEstiloBarra(id, color, patron) {
  const el = document.getElementById('bar-fill-' + id);
  if (!el) return;
  const f = fondoBarra(color, patron);
  el.style.backgroundColor = f.backgroundColor;
  el.style.backgroundImage = f.backgroundImage;
  el.style.backgroundSize = f.backgroundSize || '';
}

function actualizarEstiloBarra(id, materia, campo, valor) {
  const estilos = cargarEstilosBarras();
  if (!estilos[materia]) estilos[materia] = {};
  estilos[materia][campo] = valor;
  guardarEstilosBarras(estilos);

  const color = estilos[materia].color || BAR_PALETTE[0];
  const patron = estilos[materia].patron || 'solido';
  aplicarEstiloBarra(id, color, patron);
}

function toggleBarPanel(id) {
  document.querySelectorAll('.bar-panel').forEach(p => {
    if (p.id !== 'bar-panel-' + id) p.classList.remove('open');
  });
  document.getElementById('bar-panel-' + id)?.classList.toggle('open');
}

document.addEventListener('click', (e) => {
  if (!e.target.closest('.bar-col')) {
    document.querySelectorAll('.bar-panel').forEach(p => p.classList.remove('open'));
  }
});

function renderGraficoNotas() {
  const cont = document.getElementById('bar-chart');
  if (!cont) return;

  // Solo promediamos las entradas de tipo 'nota', agrupadas por materia
  const porMateria = {};
  NOTAS.filter(n => n.tipo === 'nota').forEach(n => {
    if (!porMateria[n.materia]) porMateria[n.materia] = [];
    porMateria[n.materia].push(parseFloat(n.valor));
  });
  const materias = Object.keys(porMateria);

  if (materias.length === 0) {
    cont.innerHTML = '<p style="color:var(--muted);font-size:.85rem;padding:1rem;">Agrega notas numéricas para ver tu gráfico de promedios. 📊</p>';
    return;
  }

  const estilos = cargarEstilosBarras();
  const patrones = [
    { v:'solido',    label:'Sólido' },
    { v:'rayas',     label:'Rayas' },
    { v:'espiral',   label:'Espiral' },
    { v:'corazones', label:'Corazones' },
    { v:'lunares',   label:'Lunares' }
  ];

  cont.innerHTML = materias.map((materia, idx) => {
    const valores = porMateria[materia];
    const prom = valores.reduce((a,b) => a+b, 0) / valores.length;
    const pct = Math.max(4, Math.min(100, (prom/5)*100)).toFixed(0);
    const est = estilos[materia] || {};
    const color = est.color || BAR_PALETTE[idx % BAR_PALETTE.length];
    const patron = est.patron || 'solido';
    const f = fondoBarra(color, patron);
    const bg = `background-color:${f.backgroundColor};background-image:${f.backgroundImage};${f.backgroundSize ? 'background-size:'+f.backgroundSize+';' : ''}`;
    const materiaAttr = materia.replace(/'/g, "\\'");

    return `
      <div class="bar-col">
        <button class="bar-gear" title="Personalizar barra" onclick="toggleBarPanel(${idx})">🎨</button>
        <div class="bar-panel" id="bar-panel-${idx}">
          <label>Color</label>
          <input type="color" value="${color}" oninput="actualizarEstiloBarra(${idx}, '${materiaAttr}', 'color', this.value)"/>
          <label>Textura</label>
          <select onchange="actualizarEstiloBarra(${idx}, '${materiaAttr}', 'patron', this.value)">
            ${patrones.map(p => `<option value="${p.v}" ${p.v===patron?'selected':''}>${p.label}</option>`).join('')}
          </select>
        </div>
        <div class="bar-track">
          <div class="bar-fill" id="bar-fill-${idx}" style="height:${pct}%;${bg}"></div>
        </div>
        <span class="bar-value">${prom.toFixed(1)}</span>
        <span class="bar-label">${materia}</span>
      </div>`;
  }).join('');
}

// ─────────────────────────────────────────────────────────────
// DASHBOARD — Actualizar contadores
// ─────────────────────────────────────────────────────────────
function updateDashboard() {
  // Contadores de tareas
  const pendientes = TAREAS.filter(t => t.estado === 'pendiente').length;
  document.getElementById('dash-pendientes-count').textContent = pendientes;

  const hoy = new Date(); hoy.setHours(0,0,0,0);
  const finSemana = new Date(hoy); finSemana.setDate(hoy.getDate()+7);
  const semana = TAREAS.filter(t => {
    if (!t.fecha_limite) return false;
    const d = new Date(t.fecha_limite + 'T00:00:00');
    return d >= hoy && d <= finSemana && t.estado !== 'done';
  }).length;
  document.getElementById('dash-semana-count').textContent = semana;

  // Notas
  const materiasConNota = new Set(NOTAS.map(n => n.materia));
  document.getElementById('dash-materias-count').textContent = materiasConNota.size;

  const notasNumericas = NOTAS.filter(n => n.tipo === 'nota');
  if (notasNumericas.length > 0) {
    const prom = notasNumericas.reduce((s,n) => s + parseFloat(n.valor), 0) / notasNumericas.length;
    document.getElementById('dash-promedio').textContent = prom.toFixed(1);
  } else {
    document.getElementById('dash-promedio').textContent = '—';
  }

  // Upcoming list
  const upcoming = document.getElementById('dash-upcoming');
  const proximas = TAREAS.filter(t => t.fecha_limite && t.estado !== 'done').sort((a,b) => a.fecha_limite.localeCompare(b.fecha_limite)).slice(0,4);
  if (proximas.length === 0) {
    upcoming.innerHTML = '<li style="color:var(--muted);font-size:.85rem;padding:.5rem 0;">Sin tareas próximas. ¡Bien! 🎉</li>';
  } else {
    const hoyStr = hoy.toISOString().split('T')[0];
    upcoming.innerHTML = proximas.map(t => {
      const d = new Date(t.fecha_limite + 'T00:00:00');
      const diff = Math.round((d-hoy)/(1000*60*60*24));
      const label = diff === 0 ? 'Hoy' : diff === 1 ? 'Mañana' : t.fecha_limite;
      const cls = diff <= 0 ? 'urgent' : diff <= 2 ? 'soon' : '';
      return `<li class="upcoming-item upcoming-item--${cls}">
        <div class="upcoming-item__meta">
          <span class="upcoming-item__tag tag--${diff<=0?'red':diff<=2?'amber':'blue'}">${label}</span>
          <span class="upcoming-item__subject">${t.materia||'General'}</span>
        </div>
        <p class="upcoming-item__name">${t.nombre}</p>
        <span class="upcoming-item__time">${t.hora_limite ? t.hora_limite.slice(0,5) : ''}</span>
      </li>`;
    }).join('');
  }

  // Grade summary
  const gradesEl = document.getElementById('dash-grades');
  if (NOTAS.length === 0) {
    gradesEl.innerHTML = '<li style="color:var(--muted);font-size:.85rem;padding:.5rem 0;">Aún no has registrado notas.</li>';
  } else {
    gradesEl.innerHTML = NOTAS.map(n => {
      const prom = ((parseFloat(n.parcial1)+parseFloat(n.parcial2)+parseFloat(n.proyecto)+parseFloat(n.tareas_nota))/4).toFixed(1);
      const pct = Math.min(100, (prom/5)*100);
      const cls = prom>=4.5?'great':prom>=3.5?'good':prom>=3?'warn':'bad';
      return `<li class="grade-row">
        <span class="grade-row__subject">${n.materia}</span>
        <div class="grade-row__bar-wrap"><div class="grade-row__bar" style="--pct:${pct}%"></div></div>
        <span class="grade-row__score grade-row__score--${cls}">${prom}</span>
      </li>`;
    }).join('');
  }

  // Nuevo: anillo de progreso + notificaciones + actividad reciente
  renderProgresoRing();
  updateNotifBell();
  renderActividadReciente();
  renderMiniCalendario();
  renderCompromisos();
  renderCursosActivos();
  renderMensajesRecientes();
}

// ─────────────────────────────────────────────────────────────
// PANEL — Anillo de progreso general (tareas completadas / total)
// ─────────────────────────────────────────────────────────────
function renderProgresoRing() {
  const circle = document.getElementById('progress-ring-circle');
  const pctText = document.getElementById('progress-ring-pct');
  const msg = document.getElementById('progress-ring-msg');
  const sub = document.getElementById('progress-ring-sub');
  if (!circle) return;

  const total = TAREAS.length;
  const completadas = TAREAS.filter(t => t.estado === 'done').length;
  const pct = total === 0 ? 0 : Math.round((completadas / total) * 100);

  const CIRCUMFERENCE = 2 * Math.PI * 62; // r=62
  const offset = CIRCUMFERENCE - (pct / 100) * CIRCUMFERENCE;
  circle.style.strokeDasharray = CIRCUMFERENCE.toFixed(1);
  circle.style.strokeDashoffset = offset.toFixed(1);
  pctText.textContent = pct + '%';

  if (total === 0) {
    msg.textContent = 'Aún no tienes tareas';
    sub.textContent = 'Agrega tu primera tarea para ver tu progreso.';
  } else if (pct >= 80) {
    msg.textContent = '¡Vas muy bien! 🎉';
    sub.textContent = 'Sigue así con tu excelente trabajo.';
  } else if (pct >= 40) {
    msg.textContent = 'Vas por buen camino';
    sub.textContent = `Te quedan ${total - completadas} tareas por completar.`;
  } else {
    msg.textContent = 'Toca ponerse al día';
    sub.textContent = `Tienes ${total - completadas} tareas pendientes.`;
  }
}

// ─────────────────────────────────────────────────────────────
// PANEL — Notificaciones (tareas urgentes: hoy o vencidas)
// ─────────────────────────────────────────────────────────────
function updateNotifBell() {
  const badge = document.getElementById('notif-badge');
  const list  = document.getElementById('notif-list');
  if (!badge || !list) return;

  const hoy = new Date(); hoy.setHours(0,0,0,0);
  const urgentes = TAREAS
    .filter(t => t.estado !== 'done' && t.fecha_limite)
    .map(t => ({ ...t, _d: new Date(t.fecha_limite + 'T00:00:00') }))
    .filter(t => t._d <= hoy)
    .sort((a,b) => a._d - b._d);

  if (urgentes.length === 0) {
    badge.style.display = 'none';
    list.innerHTML = '<p class="notif-empty">No tienes notificaciones nuevas 🎉</p>';
    return;
  }

  badge.style.display = 'flex';
  badge.textContent = urgentes.length > 9 ? '9+' : urgentes.length;
  list.innerHTML = urgentes.slice(0, 6).map(t => {
    const vencida = t._d < hoy;
    const cuando = vencida ? 'Vencida' : 'Vence hoy';
    return `<div class="notif-item" onclick="showSection('tareas'); closeAllDropdowns();">
      <span class="notif-item__dot"></span>
      <div class="notif-item__text">
        <p><strong>${t.nombre}</strong> — ${t.materia || 'General'}</p>
        <span>${cuando}${t.hora_limite ? ' · ' + t.hora_limite.slice(0,5) : ''}</span>
      </div>
    </div>`;
  }).join('');
}

// ─────────────────────────────────────────────────────────────
// PANEL — Actividad reciente (últimas tareas y notas creadas)
// ─────────────────────────────────────────────────────────────
function timeAgo(fechaStr) {
  if (!fechaStr) return '';
  const fecha = new Date(fechaStr.replace(' ', 'T'));
  const diffMs = Date.now() - fecha.getTime();
  const min = Math.floor(diffMs / 60000);
  if (min < 1) return 'justo ahora';
  if (min < 60) return `hace ${min} min`;
  const hrs = Math.floor(min / 60);
  if (hrs < 24) return `hace ${hrs} hora${hrs>1?'s':''}`;
  const dias = Math.floor(hrs / 24);
  if (dias < 7) return `hace ${dias} día${dias>1?'s':''}`;
  return fecha.toLocaleDateString('es-CO', { day:'numeric', month:'short' });
}

function renderActividadReciente() {
  const el = document.getElementById('dash-activity');
  if (!el) return;

  const eventosTareas = TAREAS
    .filter(t => t.creado_en)
    .map(t => ({
      tipo: t.estado === 'done' ? 'done' : 'new',
      icono: t.estado === 'done' ? '✅' : '📌',
      texto: t.estado === 'done'
        ? `Completaste <strong>${t.nombre}</strong>`
        : `Agregaste la tarea <strong>${t.nombre}</strong>`,
      fecha: t.creado_en
    }));

  const eventosNotas = NOTAS
    .filter(n => n.creado_en)
    .map(n => ({
      tipo: 'nota',
      icono: '📊',
      texto: n.tipo === 'firma'
        ? `Registraste una firma en <strong>${n.materia}</strong>`
        : `Registraste una nota en <strong>${n.materia}</strong>`,
      fecha: n.creado_en
    }));

  const eventos = [...eventosTareas, ...eventosNotas]
    .sort((a,b) => new Date(b.fecha.replace(' ','T')) - new Date(a.fecha.replace(' ','T')))
    .slice(0, 6);

  if (eventos.length === 0) {
    el.innerHTML = '<p class="activity-empty">Aún no hay actividad. ¡Crea tu primera tarea o nota!</p>';
    return;
  }

  el.innerHTML = eventos.map(e => `
    <div class="activity-item">
      <div class="activity-item__icon activity-item__icon--${e.tipo}">${e.icono}</div>
      <div class="activity-item__text">
        <p>${e.texto}</p>
        <span>${timeAgo(e.fecha)}</span>
      </div>
    </div>
  `).join('');
}

// ─────────────────────────────────────────────────────────────
// PANEL — Mini calendario (vista compacta del mes actual)
// ─────────────────────────────────────────────────────────────
let MINI_CAL_Y = new Date().getFullYear();
let MINI_CAL_M = new Date().getMonth();

function renderMiniCalendario() {
  const grid = document.getElementById('mini-cal-grid');
  const label = document.getElementById('mini-cal-label');
  if (!grid) return;
  label.textContent = MESES[MINI_CAL_M].slice(0,3) + ' ' + MINI_CAL_Y;

  const primerDia = new Date(MINI_CAL_Y, MINI_CAL_M, 1).getDay();
  const diasEnMes = new Date(MINI_CAL_Y, MINI_CAL_M+1, 0).getDate();
  const hoy = new Date(); hoy.setHours(0,0,0,0);
  const offset = (primerDia === 0 ? 6 : primerDia - 1);

  // Días con tareas o proyectos pendientes
  const diasConEventos = new Set();
  TAREAS.forEach(t => { if (t.fecha_limite && t.estado !== 'done') diasConEventos.add(t.fecha_limite); });
  PROYECTOS.forEach(p => { if (p.fecha_entrega && p.estado !== 'completado') diasConEventos.add(p.fecha_entrega); });

  const heads = ['L','M','X','J','V','S','D'];
  let html = heads.map(d => `<div class="mini-cal-head">${d}</div>`).join('');
  for (let i=0; i<offset; i++) html += `<div class="mini-cal-day mini-cal-day--empty"></div>`;

  for (let d=1; d<=diasEnMes; d++) {
    const fechaStr = MINI_CAL_Y + '-' + String(MINI_CAL_M+1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
    const dObj = new Date(MINI_CAL_Y, MINI_CAL_M, d); dObj.setHours(0,0,0,0);
    const esHoy = dObj.getTime() === hoy.getTime();
    const tieneEvento = diasConEventos.has(fechaStr);
    html += `<div class="mini-cal-day ${esHoy?'mini-cal-day--today':''}" onclick="irACalendario(${MINI_CAL_Y},${MINI_CAL_M})">
      ${d}${tieneEvento ? '<span class="mini-cal-dot"></span>' : ''}
    </div>`;
  }
  grid.innerHTML = html;
}
function miniCalPrev() { MINI_CAL_M--; if(MINI_CAL_M<0){MINI_CAL_M=11;MINI_CAL_Y--;} renderMiniCalendario(); }
function miniCalNext() { MINI_CAL_M++; if(MINI_CAL_M>11){MINI_CAL_M=0;MINI_CAL_Y++;} renderMiniCalendario(); }
function irACalendario(anio, mes) {
  CAL_Y = anio; CAL_M = mes;
  showSection('calendario');
  renderCalendario();
}

// ─────────────────────────────────────────────────────────────
// PANEL — Próximos compromisos (tareas + proyectos combinados)
// ─────────────────────────────────────────────────────────────
function renderCompromisos() {
  const el = document.getElementById('dash-compromisos');
  if (!el) return;

  const hoy = new Date(); hoy.setHours(0,0,0,0);

  const items = [
    ...TAREAS.filter(t => t.fecha_limite && t.estado !== 'done').map(t => ({
      tipo:'Tarea', nombre:t.nombre, materia:t.materia, fecha:t.fecha_limite, hora:t.hora_limite
    })),
    ...PROYECTOS.filter(p => p.fecha_entrega && p.estado !== 'completado').map(p => ({
      tipo:'Proyecto', nombre:p.nombre, materia:p.materia, fecha:p.fecha_entrega, hora:null
    }))
  ].sort((a,b) => a.fecha.localeCompare(b.fecha)).slice(0, 4);

  if (items.length === 0) {
    el.innerHTML = '<p style="color:var(--muted);font-size:.85rem;">Sin compromisos próximos. ¡Vas al día! 🎉</p>';
    return;
  }

  el.innerHTML = items.map(it => {
    const d = new Date(it.fecha + 'T00:00:00');
    const diff = Math.round((d - hoy) / 86400000);
    const cls = diff <= 0 ? 'urgent' : diff <= 2 ? 'soon' : '';
    const label = diff < 0 ? 'Atrasado' : diff === 0 ? 'Hoy' : diff === 1 ? 'Mañana' : it.fecha;
    return `<div class="compromiso-item compromiso-item--${cls}">
      <div class="compromiso-item__top">
        <span class="compromiso-item__type">${it.tipo}</span>
        <span class="compromiso-item__date">${label}</span>
      </div>
      <p class="compromiso-item__name">${it.nombre}</p>
      <p class="compromiso-item__meta">${it.materia||'General'}${it.hora ? ' · '+it.hora.slice(0,5) : ''}</p>
    </div>`;
  }).join('');
}

// ─────────────────────────────────────────────────────────────
// PANEL — Cursos activos (progreso por materia, basado en tareas)
// ─────────────────────────────────────────────────────────────
function renderCursosActivos() {
  const el = document.getElementById('dash-cursos-activos');
  if (!el) return;

  const porMateria = {};
  TAREAS.forEach(t => {
    const mat = t.materia || 'General';
    if (!porMateria[mat]) porMateria[mat] = { total:0, done:0 };
    porMateria[mat].total++;
    if (t.estado === 'done') porMateria[mat].done++;
  });

  const materias = Object.keys(porMateria).slice(0, 5);
  if (materias.length === 0) {
    el.innerHTML = '<p style="color:var(--muted);font-size:.85rem;">Agrega tareas con materia para ver tu progreso aquí.</p>';
    return;
  }

  el.innerHTML = materias.map(mat => {
    const { total, done } = porMateria[mat];
    const pct = Math.round((done/total)*100);
    return `<div class="cursos-activos-item">
      <div class="cursos-activos-item__top">
        <span class="cursos-activos-item__name">${mat}</span>
        <span class="cursos-activos-item__pct">${pct}%</span>
      </div>
      <div class="cursos-activos-item__track"><div class="cursos-activos-item__fill" style="width:${pct}%"></div></div>
    </div>`;
  }).join('');
}

// ─────────────────────────────────────────────────────────────
// PANEL — Mensajes recientes (vista compacta, 3 últimos)
// ─────────────────────────────────────────────────────────────
function renderMensajesRecientes() {
  const el = document.getElementById('dash-mensajes-recientes');
  if (!el) return;

  if (MENSAJES.length === 0) {
    el.innerHTML = '<p style="color:var(--muted);font-size:.85rem;">Aún no tienes mensajes.</p>';
    return;
  }

  el.innerHTML = MENSAJES.slice(0, 3).map(m => `
    <div class="mensaje-mini">
      <span class="mensaje-mini__dot ${m.leido == 1 ? 'mensaje-mini__dot--read' : ''}"></span>
      <div class="mensaje-mini__text">
        <p>${m.titulo}</p>
        <span>${timeAgo(m.creado_en)}</span>
      </div>
    </div>
  `).join('');
}

// ─────────────────────────────────────────────────────────────
// TOPBAR — Búsqueda en vivo (tareas + notas ya cargadas)
// ─────────────────────────────────────────────────────────────
function initTopbarSearch() {
  const input = document.getElementById('topbar-search');
  const results = document.getElementById('topbar-search-results');
  if (!input) return;

  input.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();
    if (q.length < 2) { results.classList.remove('open'); results.innerHTML = ''; return; }

    const matchTareas = TAREAS.filter(t =>
      t.nombre.toLowerCase().includes(q) || (t.materia||'').toLowerCase().includes(q)
    ).slice(0, 5).map(t => ({
      icono:'📌', nombre:t.nombre, meta:`Tarea · ${t.materia||'General'}`, accion:"showSection('tareas')"
    }));

    const matchNotas = NOTAS.filter(n => (n.materia||'').toLowerCase().includes(q))
      .slice(0, 5).map(n => ({
        icono:'📊', nombre:n.materia, meta: n.tipo === 'firma' ? 'Nota · Firma' : `Nota · ${n.valor ?? ''}`, accion:"showSection('notas')"
      }));

    const items = [...matchTareas, ...matchNotas];
    if (items.length === 0) {
      results.innerHTML = '<p class="search-empty">Sin resultados para "' + input.value.trim() + '"</p>';
    } else {
      results.innerHTML = items.map(it => `
        <div class="search-result" onclick="${it.accion}; document.getElementById('topbar-search').value=''; document.getElementById('topbar-search-results').classList.remove('open');">
          <div class="search-result__icon">${it.icono}</div>
          <div class="search-result__text">
            <div class="search-result__name">${it.nombre}</div>
            <div class="search-result__meta">${it.meta}</div>
          </div>
        </div>
      `).join('');
    }
    results.classList.add('open');
  });

  document.addEventListener('click', (e) => {
    if (!document.getElementById('topbar-search-wrap').contains(e.target)) {
      results.classList.remove('open');
    }
  });
}

// ─────────────────────────────────────────────────────────────
// TOPBAR — Dropdowns (notificaciones / usuario)
// ─────────────────────────────────────────────────────────────
function closeAllDropdowns() {
  document.getElementById('notif-dropdown')?.classList.remove('open');
  document.getElementById('user-dropdown')?.classList.remove('open');
}
function toggleNotifDropdown(e) {
  e.stopPropagation();
  const dd = document.getElementById('notif-dropdown');
  const wasOpen = dd.classList.contains('open');
  closeAllDropdowns();
  if (!wasOpen) dd.classList.add('open');
}
function toggleUserDropdown(e) {
  e.stopPropagation();
  const dd = document.getElementById('user-dropdown');
  const wasOpen = dd.classList.contains('open');
  closeAllDropdowns();
  if (!wasOpen) dd.classList.add('open');
}
document.addEventListener('click', closeAllDropdowns);


// ─────────────────────────────────────────────────────────────
function renderCalendario() {
  const grid = document.getElementById('calendar-grid');
  document.getElementById('cal-month-label').textContent = MESES[CAL_M] + ' ' + CAL_Y;

  const primerDia = new Date(CAL_Y, CAL_M, 1).getDay(); // 0=Dom
  const diasEnMes = new Date(CAL_Y, CAL_M+1, 0).getDate();
  const hoy = new Date(); hoy.setHours(0,0,0,0);

  // Cabeceras
  const heads = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
  let html = heads.map(d => `<div class="cal-head">${d}</div>`).join('');

  // Offset: primer día de la semana es lunes (1)
  const offset = (primerDia === 0 ? 6 : primerDia - 1);
  for (let i=0; i<offset; i++) html += `<div class="cal-day cal-day--empty"></div>`;

  // Map de tareas por fecha
  const tareasMap = {};
  TAREAS.forEach(t => {
    if (t.fecha_limite) {
      if (!tareasMap[t.fecha_limite]) tareasMap[t.fecha_limite] = [];
      tareasMap[t.fecha_limite].push(t);
    }
  });

  for (let d=1; d<=diasEnMes; d++) {
    const fechaStr = CAL_Y + '-' + String(CAL_M+1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
    const dObj = new Date(CAL_Y, CAL_M, d); dObj.setHours(0,0,0,0);
    const esHoy = dObj.getTime() === hoy.getTime();
    const tDia = tareasMap[fechaStr] || [];

    const eventosHtml = tDia.slice(0,2).map(t => {
      const color = t.prioridad==='alta' ? '--red' : t.prioridad==='media' ? '--sky' : '--lime';
      return `<div class="cal-event cal-event${color}">${t.nombre.slice(0,14)}${t.nombre.length>14?'…':''}</div>`;
    }).join('') + (tDia.length>2 ? `<div class="cal-event cal-event--lime">+${tDia.length-2} más</div>` : '');

    html += `<div class="cal-day ${esHoy?'cal-day--today':''}" onclick="selDia('${fechaStr}',${d})">
      <span class="cal-day__num">${d}</span>
      ${eventosHtml}
    </div>`;
  }

  grid.innerHTML = html;
}

function selDia(fechaStr, d) {
  CAL_SEL_DAY = fechaStr;
  document.querySelectorAll('.cal-day').forEach(el => el.classList.remove('selected'));
  event.currentTarget.classList.add('selected');

  const tDia = TAREAS.filter(t => t.fecha_limite === fechaStr);
  document.getElementById('cal-day-title').textContent = d + ' de ' + MESES[CAL_M] + ' — ' + tDia.length + ' evento(s)';

  const evBox = document.getElementById('cal-day-events');
  if (tDia.length === 0) {
    evBox.innerHTML = '<p style="font-size:.85rem;color:var(--muted);">Sin actividades este día. <button onclick="abrirModalTarea(\''+fechaStr+'\')" style="background:none;border:none;color:var(--purple);cursor:pointer;font-weight:600;">+ Agregar</button></p>';
  } else {
    evBox.innerHTML = tDia.map(t => {
      const priColor = t.prioridad==='alta'?'var(--coral)':t.prioridad==='media'?'var(--amber)':'var(--mint)';
      return `<div style="background:#F6F5FF;border-left:3px solid ${priColor};border-radius:8px;padding:.6rem .875rem;">
        <p style="font-size:.85rem;font-weight:600;">${t.nombre}</p>
        <p style="font-size:.75rem;color:var(--muted);">${t.materia||'General'}${t.hora_limite?' · '+t.hora_limite.slice(0,5):''}</p>
        <div style="display:flex;gap:.5rem;margin-top:.4rem;">
          ${t.estado!=='done'?`<button onclick="cambiarEstado(${t.id},'done')" style="font-size:.72rem;background:var(--mint);color:#fff;border:none;border-radius:6px;padding:.15rem .5rem;cursor:pointer;">✓ Listo</button>`:'<span style="font-size:.72rem;color:var(--mint);">✅ Completada</span>'}
          <button onclick="eliminarTarea(${t.id})" style="font-size:.72rem;background:none;border:none;color:var(--coral);cursor:pointer;">✕ Eliminar</button>
        </div>
      </div>`;
    }).join('');
  }
}

function calPrev() { CAL_M--; if(CAL_M<0){CAL_M=11;CAL_Y--;} renderCalendario(); }
function calNext() { CAL_M++; if(CAL_M>11){CAL_M=0;CAL_Y++;} renderCalendario(); }

// Modal rápido para agregar tarea desde calendario
function abrirModalTarea(fechaPref) {
  showSection('tareas');
  if (fechaPref) document.getElementById('t-fecha').value = fechaPref;
  document.getElementById('t-nombre').focus();
  showToast('💡 Completa el formulario y haz clic en "Crear tarea".');
}

// ─────────────────────────────────────────────────────────────
// HORARIO (localStorage)
// ─────────────────────────────────────────────────────────────
const HORARIO_KEY = 'nmu_horario';
const DIAS_HORARIO = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
let HORARIO = []; // [{ id, hora, lunes, martes, miercoles, jueves, viernes, sabado, domingo }]

function cargarHorario() {
  try {
    const guardado = localStorage.getItem(HORARIO_KEY);
    HORARIO = guardado ? JSON.parse(guardado) : [];
  } catch (e) {
    console.error('No se pudo leer el horario guardado:', e);
    HORARIO = [];
  }
  if (HORARIO.length === 0) {
    ['07:00', '08:00', '09:00'].forEach(h => HORARIO.push(filaVaciaHorario(h)));
  }
  renderHorario();
}

let _horarioSaveTimeout = null;
function guardarHorario() {
  clearTimeout(_horarioSaveTimeout);
  _horarioSaveTimeout = setTimeout(() => {
    localStorage.setItem(HORARIO_KEY, JSON.stringify(HORARIO));
  }, 250);
}

function filaVaciaHorario(hora) {
  const fila = { id: 'h' + Date.now() + Math.random().toString(16).slice(2), hora: hora || '' };
  DIAS_HORARIO.forEach(d => fila[d] = '');
  return fila;
}

function siguienteHoraSugerida() {
  if (HORARIO.length === 0) return '07:00';
  const ultima = HORARIO[HORARIO.length - 1].hora;
  const match = /^(\d{1,2}):(\d{2})$/.exec((ultima || '').trim());
  if (!match) return '';
  let h = (parseInt(match[1], 10) + 1) % 24;
  return String(h).padStart(2, '0') + ':' + match[2];
}

function agregarFilaHorario() {
  const nueva = filaVaciaHorario(siguienteHoraSugerida());
  HORARIO.push(nueva);
  guardarHorario();
  renderHorario();
  requestAnimationFrame(() => {
    const input = document.querySelector('input[data-id="' + nueva.id + '"][data-campo="hora"]');
    if (input) input.focus();
  });
}

function eliminarFilaHorario(id) {
  if (!confirm('¿Eliminar esta fila del horario?')) return;
  HORARIO = HORARIO.filter(f => f.id !== id);
  guardarHorario();
  renderHorario();
}

function actualizarCeldaHorario(id, campo, valor) {
  const fila = HORARIO.find(f => f.id === id);
  if (!fila) return;
  fila[campo] = valor;
  guardarHorario();
}

function renderHorario() {
  const tbody = document.getElementById('horario-tbody');
  const hint = document.getElementById('horario-empty-hint');
  if (!tbody) return;

  if (HORARIO.length === 0) {
    tbody.innerHTML = '';
    if (hint) hint.style.display = 'block';
    return;
  }
  if (hint) hint.style.display = 'none';

  tbody.innerHTML = HORARIO.map(fila => `
    <tr>
      <td>
        <input type="text" class="horario-input horario-hora-input"
               data-id="${fila.id}" data-campo="hora"
               placeholder="08:00" value="${escaparHorario(fila.hora)}"
               oninput="actualizarCeldaHorario('${fila.id}','hora',this.value)">
      </td>
      ${DIAS_HORARIO.map(dia => `
        <td>
          <input type="text" class="horario-input"
                 data-id="${fila.id}" data-campo="${dia}"
                 placeholder="+ Añadir" value="${escaparHorario(fila[dia])}"
                 oninput="actualizarCeldaHorario('${fila.id}','${dia}',this.value)">
        </td>
      `).join('')}
      <td>
        <button type="button" class="horario-delete-btn" title="Eliminar fila"
                onclick="eliminarFilaHorario('${fila.id}')">✕</button>
      </td>
    </tr>
  `).join('');
}

function escaparHorario(texto) {
  return (texto || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

// ─────────────────────────────────────────────────────────────
// PERFIL
// ─────────────────────────────────────────────────────────────
function avatarHTML(iniciales, foto) {
  return foto
    ? `<img src="uploads/perfil/${foto}?t=${Date.now()}" alt="Foto de perfil" class="user-avatar-img">`
    : iniciales;
}

async function cargarPerfil() {
  const data = await apiPost('api_perfil.php', { action:'obtener' });
  if (!data.ok) { showToast('❌ No se pudo cargar el perfil.'); return; }
  const p = data.perfil;

  document.getElementById('p-nombre').value   = p.nombre || '';
  document.getElementById('p-edad').value     = p.edad ?? '';
  document.getElementById('p-colegio').value  = p.colegio || '';
  document.getElementById('p-grado').value    = p.grado || '';
  document.getElementById('p-telefono').value = p.telefono || '';
  document.getElementById('p-correo').value   = p.correo || '';
  document.getElementById('p-bio').value      = p.bio || '';

  const iniciales = (p.nombre || '??').slice(0,2).toUpperCase();
  document.getElementById('perfil-avatar-preview').innerHTML = avatarHTML(iniciales, p.foto);
  document.getElementById('sidebar-avatar').innerHTML = avatarHTML(iniciales, p.foto);
}

async function guardarPerfil() {
  const nombre   = document.getElementById('p-nombre').value.trim();
  const edad     = document.getElementById('p-edad').value;
  const colegio  = document.getElementById('p-colegio').value.trim();
  const grado    = document.getElementById('p-grado').value.trim();
  const telefono = document.getElementById('p-telefono').value.trim();
  const bio      = document.getElementById('p-bio').value.trim();

  if (!nombre) { showToast('⚠ El nombre no puede quedar vacío.'); return; }

  const btn = document.getElementById('btn-guardar-perfil');
  btn.innerHTML = '<span class="spinner"></span> Guardando…';
  btn.disabled = true;

  const data = await apiPost('api_perfil.php', { action:'guardar', nombre, edad, colegio, grado, telefono, bio });

  btn.innerHTML = 'Guardar cambios';
  btn.disabled = false;

  if (data.ok) {
    document.querySelectorAll('.user-name').forEach(el => el.textContent = nombre);
    showToast('✅ Perfil actualizado.');
  } else {
    showToast('❌ Error: ' + data.msg);
  }
}

async function subirFotoPerfil(archivo) {
  if (!archivo) return;
  if (archivo.size > 3 * 1024 * 1024) { showToast('⚠ La imagen no puede pesar más de 3MB.'); return; }

  const data = await apiPost('api_perfil.php', { action:'foto', foto: archivo });

  if (data.ok) {
    const html = `<img src="uploads/perfil/${data.foto}?t=${Date.now()}" alt="Foto de perfil" class="user-avatar-img">`;
    document.getElementById('perfil-avatar-preview').innerHTML = html;
    document.getElementById('sidebar-avatar').innerHTML = html;
    showToast('✅ Foto actualizada.');
  } else {
    showToast('❌ Error: ' + data.msg);
  }
}

// ─────────────────────────────────────────────────────────────
// INIT
// ─────────────────────────────────────────────────────────────
(async function init() {
  initDuvicito();
  initTopbarSearch();
  await cargarTareas();
  await cargarProyectos();
  await cargarNotas();
  await cargarMensajes();
  cargarHorario();
  await cargarPerfil();
  renderCalendario();
})();
</script>

</body>
</html>
