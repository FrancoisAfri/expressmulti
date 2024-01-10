<div class="topnav shadow-lg">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link -toggle arrow-none" href="{{ route('home') }}" id="topnav-dashboard" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fe-airplay mr-1"></i> Dashboard
                        </a>
                    </li>
                    @foreach($modulesAccess as $module)
                        @if(count($module->moduleRibbon) > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="{{ !empty($module->font_awesome) ? $module->font_awesome : 'fe-help-circle' }} mr-1"></i> {{ $module->name }} <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            @foreach($module->moduleRibbon as $ribbon)
                                @if ($ribbon->active === 1)
                                    <a href="/{{ $ribbon->ribbon_path }}" class="dropdown-item"><i class="{{ !empty($ribbon->font_awesome) ? $ribbon->font_awesome : '' }} mr-1"></i> {{ $ribbon->ribbon_name }}</a>
                                @endif
                            @endforeach
                        </div>
                    </li>
                        @endif
                    @endforeach
                </ul> <!-- end navbar-->
            </div> <!-- end .collapsed-->
        </nav>
    </div> <!-- end container-fluid -->
</div> <!-- end topnav-->
