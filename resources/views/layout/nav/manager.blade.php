

<ul class="menu">
    <li class="sidebar-item">
        <a href="{{route(currentUser().'.dashboard')}}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>{{__('dashboard') }}</span>
        </a>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-boxes"></i><span>{{__('Products')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1"><a href="{{route(currentUser().'.product.index')}}" >{{__('Product List')}}</a></li>
		</ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-boxes"></i><span>{{__('DO Setting')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1"><a href="{{route(currentUser().'.docontroll.create')}}" >{{__('Do')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.docontroll.index')}}" >{{__('Do List')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.doreceive')}}" >{{__('Do Receive')}}</a></li>
		</ul>
    </li>


</ul>
