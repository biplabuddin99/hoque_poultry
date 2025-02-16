<ul class="menu">
    <li class="sidebar-item">
        <a href="{{route(currentUser().'.dashboard')}}" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>{{__('dashboard') }}</span>
        </a>
    </li>

    {{-- <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-calculator"></i><span>{{__('Accounts')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1"><a href="{{route(currentUser().'.master.index')}}" >{{__('Master Head')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.sub_head.index')}}" >{{__('Sub Head')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.child_one.index')}}" >{{__('Child One')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.child_two.index')}}" >{{__('Child Two')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.navigate.index')}}">{{__('Navigate View')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.incomeStatement')}}">{{__('Income Statement')}}</a></li>

            <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'>{{__('Voucher')}}</a>
                <ul class="submenu">
                    <li class="py-1"><a href="{{route(currentUser().'.credit.index')}}">{{__('Credit Voucher')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.debit.index')}}">{{__('Debit Voucher')}}</a></li>
                    <li class="py-1"><a href="{{route(currentUser().'.journal.index')}}">{{__('Journal Voucher')}}</a></li>
                </ul>
            </li>
		</ul>

    </li> --}}
     {{--  <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-gear-fill"></i><span>{{__('Employee Settings')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1"><a href="{{route(currentUser().'.designation.index')}}" >{{__('Designation list')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.employee.index')}}" >{{__('Employee list')}}</a></li>
            <li class="py-1"><a href="{{route(currentUser().'.emLeave.index')}}" >{{__('Employee Leave list')}}</a></li>
		</ul>
    </li>  --}}
    {{-- <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-boxes"></i><span>{{__('DO')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.docontroll.create')}}" >{{__('New Do')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.do_selected_create')}}" >{{__('Selected Do')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.docontroll.index')}}" >{{__('Do List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.doreceive')}}" >{{__('Do Receive')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.do.receivelist')}}" >{{__('Do Receive List')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.undeliverd')}}" >{{__('Undeliverd')}}</a></li>
		</ul>
    </li> --}}
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-boxes"></i><span>{{__('Sales')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.customer.index')}}">{{__('Customers')}}</a></li>
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.create')}}" >{{__('New Sales')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.index')}}" >{{__('Sales List')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.selectedCreate')}}" >{{__('New Sales')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.selectedIndex')}}" >{{__('Sales List')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.salesClosing')}}" >{{__('Sales Closing')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.salesClosingList')}}" >{{__('Sales Closing List')}}</a></li> --}}
            {{--  <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales.edit',1)}}" >{{__('Sales Return')}}</a></li>  --}}
		</ul>
    </li>
    {{-- <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-card-checklist"></i><span>{{__('Check Report')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.check_list')}}" >{{__('Check list')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.check_list_bank')}}" >{{__('Bank')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.check_list_cash')}}" >{{__('Cash')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.check_list_due')}}" >{{__('Dishonor Check')}}</a></li>
		</ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-card-checklist"></i><span>{{__('Check Detail')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.checkDetail.index')}}" >{{__('Check list')}}</a></li>
		</ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-card-checklist"></i><span>{{__('Report')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.preport')}}" >{{__('Purchase Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_summary_report')}}" >{{__('Sales Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_report')}}" >{{__('Sales closing Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sreport')}}" >{{__('Stock Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.shopdue')}}" >{{__('Shop Due Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.srreport')}}" >{{__('SR Report')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.srreportProduct')}}" >{{__('SR Report(Product)')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.cashCollection')}}" >{{__('Cash Collection')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.damageProductList')}}" >{{__('Damage Product')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_expense')}}" >{{__('Sales Expense')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.sales_commission')}}" >{{__('Sales Commission')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.dsr_salary')}}" >{{__('DSR Salary')}}</a></li>
		</ul>
    </li>
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'><i class="bi bi-boxes"></i><span>{{__('Products')}}</span>
        </a>
        <ul class="submenu">
             <li class="py-1 submenu-item"><a href="{{route(currentUser().'.category.index')}}" >{{__('Category')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.group.index')}}" >{{__('Group')}}</a></li>
             <li class="py-1 submenu-item"><a href="{{route(currentUser().'.batch.index')}}" >{{__('Batch')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.product.index')}}" >{{__('Product')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.product_price')}}" >{{__('Product-price')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.returnproduct.index')}}" >{{__('Return Product')}}</a></li>
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.get_return_closing_index')}}" >{{__('Return Closing List')}}</a></li>
		</ul>
    </li> --}}
    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-gear-fill"></i>
            <span>{{__('Settings')}}</span>
        </a>
        <ul class="submenu">
            <li class="py-1 submenu-item"><a href="{{route(currentUser().'.company.index')}}">{{__('Company Details')}}</a></li>
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.supplier.index')}}">{{__('Distributor')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.area.index')}}">{{__('Area')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.users.index')}}">{{__('Users')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.werehouse.index')}}">{{__('Werehouse')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.bill.index')}}">{{__('Bill Term')}}</a></li> --}}
            {{--  <li class="py-1 submenu-item"><a href="{{route(currentUser().'.customer.index')}}">{{__('Customers')}}</a></li>  --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.shop.index')}}">{{__('Shop')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.shopbalance.index')}}">{{__('Shop Due List')}}</a></li> --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.collect_index')}}">{{__('Collection List')}}</a></li> --}}
            {{--  <li class="py-1 submenu-item"><a href="{{route(currentUser().'.checkCollection.index')}}">{{__('Collect Check')}}</a></li>  --}}
            {{-- <li class="py-1 submenu-item"><a href="{{route(currentUser().'.unitstyle.index')}}">{{__('Unit Style')}}</a></li> --}}

            {{--  <li class="submenu-item sidebar-item has-sub"><a href="#" class='sidebar-link'> {{__('Unit')}}</a>
                <ul class="submenu">
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.unitstyle.index')}}">{{__('Unit Style')}}</a></li>
                    <li class="py-1 submenu-item"><a href="{{route(currentUser().'.unit.index')}}">{{__('Unit')}}</a></li>
                </ul>
            </li>  --}}

		</ul>
    </li>

</ul>
