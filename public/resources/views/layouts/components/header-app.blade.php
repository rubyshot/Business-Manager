        <div class="header py-4">
          <div class="container">
            <div class="d-flex">
              <a class="header-brand" href="{{ url('dashboard') }}">
                <img src="{{ $system_icon }}" class="header-brand-img" alt="{{ config('system.name') }}">
                {{ config('system.name') }}
              </a>
              <div class="nav-item d-none d-md-flex">
                <i class="material-icons mr-1 text-muted" style="font-size: 18px">access_time</i><span onclick="document.location.href = '{{ url('profile#localization') }}';" class="text-muted" id="app_clock"></span>
              </div>
<script>
updateClock();
function updateClock() {
  var now = moment().tz('{{ auth()->user()->getTimezone() }}');
<?php if (auth()->user()->getUserTimeFormat() == 'g:i a') { ?>
  $('#app_clock').html('<span style="font-size: 21px">' + now.format('h:mm') + '</span> ' + now.format('a') + '');
<?php } else if (auth()->user()->getUserTimeFormat() == 'g:i A') { ?>
  $('#app_clock').html('<span style="font-size: 21px">' + now.format('h:mm') + '</span> ' + now.format('A') + '');
<?php } else { ?>
  $('#app_clock').html('<span style="font-size: 21px">' + now.format('HH:mm') + '</span>');
<?php } ?>

  setTimeout(updateClock, 1000);
}
</script>
              <div class="d-flex order-lg-2 ml-auto">
<?php if (auth()->user()->notifications()->count() > 0) { ?>
                <div class="dropdown d-none d-md-flex">
                  <a class="nav-link icon" data-toggle="dropdown" data-target="#">
                    <i class="fe fe-bell"></i>
<?php if (auth()->user()->unreadNotifications()->count() > 0) { ?>
                    <span class="nav-unread"></span>
<?php } ?>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
<?php
foreach (auth()->user()->unreadNotifications()->take(10)->get() as $notification) {
  $created_by = \App\User::find($notification->data['created_by']);
  $avatar = '';
  if ($created_by !== null) {
    $avatar = (string) $created_by->getAvatar();
  }

  $link = 'javascript:void(0);';
  switch ($notification->data['type']) {
    case 'project'; $link = url('projects/view/' . \Platform\Controllers\Core\Secure::array2string(['project_id' => $notification->data['project_id']])); break;
    case 'project-task'; $link = url('projects/view/' . \Platform\Controllers\Core\Secure::array2string(['project_id' => $notification->data['project_id']]) . '?task=' . $notification->data['task_id'] . '#tasks'); break;
    case 'project-comment'; $link = url('projects/view/' . \Platform\Controllers\Core\Secure::array2string(['project_id' => $notification->data['project_id']]) . '#comments'); break;
    case 'project-proposition'; $link = url('projects/view/' . \Platform\Controllers\Core\Secure::array2string(['project_id' => $notification->data['project_id']]) . '#proposition'); break;
  }
?>
                    <a href="{{ $link }}" class="dropdown-item d-flex">
                      <span class="avatar mr-3 align-self-center" style="background-image: url({{ $avatar }})"></span>
                      <div>
                        {!! str_limit($notification->data['title'], 42) !!}
                        <div class="small text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans() }}</div>
                      </div>
                    </a>
<?php } ?>
<?php if (auth()->user()->unreadNotifications()->count() > 0) { ?>
                    <div class="dropdown-divider"></div>
<?php } ?>
                    <a href="{{ url('notifications') }}" class="dropdown-item text-center text-muted-dark">{{ trans('g.view_notifications') }}</a>
                  </div>
                </div>
<?php } ?>

<?php if (count(config('system.available_languages')) > 1) { ?>
            <div class="dropdown-divider"></div>
                <div class="dropdown d-flex">
                  <a class="nav-link icon" data-toggle="dropdown" data-target="#">
                    <i class="material-icons">language</i> <span class="ml-1 d-none d-lg-block">{{ strtoupper(auth()->user()->getLanguage()) }}</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
<?php
foreach (config('system.available_languages') as $code => $language) {
  $selected = (auth()->user()->getLanguage() == $code) ? true : false;
?>
                    <a href="?set_lang={{ $code }}" class="py-2 dropdown-item d-flex<?php if ($selected) echo ' active'; ?>">{{ $language }}</a>
<?php } ?>
                  </div>
                </div>
<?php } ?>


                <div class="dropdown">
                  <a href="javascript:void(0);" class="nav-link pr-0 leading-none" data-toggle="dropdown" data-target="#">
                    <span class="avatar" style="background-image: url('{{ auth()->user()->getAvatar() }}')"></span>
                    <span class="ml-2 d-none d-lg-block">
                      <span class="text-default">{{ auth()->user()->name }}</span>
                      <small class="text-muted d-block mt-1">{{ auth()->user()->email }}</small>
                    </span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
<?php if (auth()->user()->can('access-profile')) { ?>
                    <a class="dropdown-item" href="{{ url('profile') }}">
                      <i class="dropdown-icon fe fe-user"></i> {{ trans('g.profile') }}
                    </a>
<?php } ?>
<?php if (auth()->user()->can('access-settings')) { ?>
                    <a class="dropdown-item" href="{{ url('settings') }}">
                      <i class="dropdown-icon fe fe-settings"></i> {{ trans('g.settings') }}
                    </a>
<?php } ?>
<?php if (auth()->user()->hasRole(\App\Role::find(1))) { ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ url('uploads') }}">
                      <i class="dropdown-icon fe fe-folder"></i> {{ trans('g.uploads') }}
                    </a>
<?php } ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ url('logout') }}">
                      <i class="dropdown-icon fe fe-log-out"></i> {{ trans('g.sign_out') }}
                    </a>
                  </div>
                </div>
              </div>
              <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                <span class="header-toggler-icon"></span>
              </a>
            </div>
          </div>
        </div>
        <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
          <div class="container">
            <div class="row align-items-center">
<?php /*
              <div class="col-lg-3 ml-auto">
                <form class="input-icon my-3 my-lg-0">
                  <input type="search" class="form-control header-search" placeholder="Search&hellip;" tabindex="1">
                  <div class="input-icon-addon">
                    <i class="fe fe-search"></i>
                  </div>
                </form>
              </div>
*/ ?>
              <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                  <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link<?php if (\Request::route()->getName() == 'dashboard') echo ' active'; ?>"><i class="material-icons">dashboard</i> {{ trans('g.dashboard') }}</a>
                  </li>
<?php if (auth()->user()->can('list-users') || auth()->user()->can('list-companies')) { ?>
                  <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link<?php if (\Request::route()->getName() == 'users' || \Request::route()->getName() == 'companies') echo ' active'; ?>" data-toggle="dropdown"><i class="material-icons">contacts</i> {{ trans('g.contacts') }}</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
<?php if (auth()->user()->can('list-users')) { ?>
                    <a href="{{ url('users') }}" class="py-2 dropdown-item<?php if (\Request::route()->getName() == 'users') echo ' active'; ?>">{{ trans('g.people') }}</a>
<?php } ?>
<?php if (auth()->user()->can('list-companies')) { ?>
                    <a href="{{ url('companies') }}" class="py-2 dropdown-item<?php if (\Request::route()->getName() == 'companies') echo ' active'; ?>">{{ trans('g.companies') }}</a>
<?php } ?>
                    </div>
                  </li>
<?php } ?>
<?php if (auth()->user()->can('list-projects')) { ?>
                  <li class="nav-item">
                    <a href="{{ url('projects') }}" class="nav-link<?php if (\Request::route()->getName() == 'projects') echo ' active'; ?>"><i class="material-icons">work</i> {{ trans('g.projects') }}</a>
                  </li>
<?php } ?>
<?php if (auth()->user()->can('list-invoices')) { ?>
                  <li class="nav-item">
                    <a href="{{ url('invoices') }}" class="nav-link<?php if (\Request::route()->getName() == 'invoices') echo ' active'; ?>"><i class="material-icons">receipt</i> {{ trans('g.invoices') }}</a>
                  </li>
<?php } ?>
<?php
$modules = \Module::getOrdered();
$active_category = '';
$category_icon  = ['marketing' => 'record_voice_over'];
$addons = [];
foreach ($modules as $module) {
  $header_menu_category = config($module->alias . '.category');
  $header_menu_name = config($module->alias . '.header_menu_name');
  $header_menu_icon = config($module->alias . '.header_menu_icon');
  $route_prefix = config($module->alias . '.route_prefix');
  $role_or_permission = config($module->alias . '.role_or_permission');
  $role_or_permission = is_array($role_or_permission) ? $role_or_permission : explode('|', $role_or_permission);

  if (auth()->user()->hasAnyRole($role_or_permission) || auth()->user()->hasAnyPermission($role_or_permission)) {
    if ($header_menu_name !== null && $header_menu_icon !== null) {
      if (\Request::route()->getName() == $module->getName()) {
        $active = true;
        $active_category = $header_menu_category;
      } else {
        $active = false;
      }
      $addons[$header_menu_category][] = ['active' => $active, 'module_name' => $module->getName(), 'route_prefix' => $route_prefix, 'name' => $header_menu_name, 'icon' => $header_menu_icon];
      /*
?>
                  <li class="nav-item">
                    <a href="{{ url($route_prefix) }}"<?php if (config('app.demo')) { ?> data-toggle="tooltip" title="Separately sold add-on"<?php } ?> class="nav-link<?php if (\Request::route()->getName() == $module->getName()) echo ' active'; ?>"><i class="material-icons">{{ $header_menu_icon }}</i> {{ $header_menu_name }}</a>
                  </li>
<?php
      */
    }
  }
}

if (count($addons) > 0) {
  foreach ($addons as $category => $category_addons) {
?>
                  <li class="nav-item dropdown"<?php if (config('app.demo')) { ?> data-toggle="tooltip"<?php } ?>>
                    <a href="javascript:void(0)" class="nav-link<?php if ($active_category == $category) echo ' active'; ?>" data-toggle="dropdown"><i class="material-icons">{{ $category_icon[$category] }}</i> {{ trans('g.' . $category) }}</a>
                    <div class="dropdown-menu dropdown-menu-arrow">
<?php
    foreach ($category_addons as $addon) {
?>
                    <a href="{{ url($addon['route_prefix']) }}" class="py-2 dropdown-item<?php if ($addon['active']) echo ' active'; ?>"><i class="material-icons mr-1" style="position: relative; top:2px">{{ $addon['icon'] }}</i> {{ $addon['name'] }}</a>
<?php
    }
?>
                    </div>
                  </li>
<?php
  }
}
?>
                </ul>
              </div>
            </div>
          </div>
        </div>