<ul class="nav nav-pills nav-stacked">
  <li role="presentation" {{{ (Request::is('admin/users*') ? 'class=active' : '') }}} >
      <a href="{{URL::route('admin.users.index')}}">Users</a>
  </li>
  <li role="presentation" {{{ (Request::is('admin/categories*') ? 'class=active' : '') }}}>
      <a href="{{URL::route('admin.categories.index')}}">Category Manage</a>
  </li>
  <li role="presentation" {{{ (Request::is('admin/menuitems*') ? 'class=active' : '') }}}>
      <a href="{{URL::route('admin.menuitems.index')}}">Products</a>
  </li>
  <li role="presentation">
      <a href="{{URL::route('admin.getLogout')}}"><i>Quit</i></a>
  </li>
</ul>
