<!-- Hamburger + Offcanvas trigger (csak mobil/tablet) -->
<nav class="navbar bg-light d-md-none">
  <div class="container-fluid">
    <button class="btn btn-outline-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      ☰ Menü
    </button>
  </div>
</nav>

<!-- Sidebar Offcanvas -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarMenuLabel">Menü</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Bezárás"></button>
  </div>
  <div class="offcanvas-body">
    {include file='layouts/sidebar_inner.tpl'}
  </div>
</div>

<!-- Fix sidebar desktopra -->
<aside class="d-none d-md-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; height: 100vh; position: fixed;">
  {include file='layouts/sidebar_inner.tpl'}
</aside>
