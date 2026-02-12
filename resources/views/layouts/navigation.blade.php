<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        @if(!empty($appSetting?->logo_url))
                            <img src="{{ $appSetting->logo_url }}" alt="Logo" class="h-9 w-9 object-contain rounded">
                        @else
                            <x-application-logo class="block w-auto h-9 text-gray-800 fill-current" />
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        لوحة التحكم
                    </x-nav-link>
                    @can('view shipments')
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 bg-white rounded-md border border-transparent transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                        <div>الشحنات</div>
                                        <div class="ms-1">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('shipments.index')">
                                        الشحن الدولي البحري
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('road-shipments.index')">
                                        الشحن الدولي البري
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('local-shipments.index')">
                                        الشحن المحلي البري
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcan
                    @can('view customs data')
                        <x-nav-link :href="route('customs.index')" :active="request()->routeIs('customs.*')">
                            البيانات الجمركية
                        </x-nav-link>
                    @endcan
                    @can('view reports')
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 bg-white rounded-md border border-transparent transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                        <div>التقارير</div>
                                        <div class="ms-1">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.reports.shipments')">
                                        تقرير الشحنات (بحر/جو)
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.reports.land_shipping')">
                                        تقرير الشحنات البرية  الدولية
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.reports.local_customs')">
                                        تقرير الشحنات المحلية
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.reports.summary')">
                                        التقرير المجمّع
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcan

                    <!-- User Management Dropdown -->
                    @if(auth()->user()->can('manage users') || auth()->user()->can('manage roles') || auth()->user()->can('manage permissions'))
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 bg-white rounded-md border border-transparent transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                        <div>إدارة المستخدمين</div>
                                        <div class="ms-1">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @can('manage users')
                                        <x-dropdown-link :href="route('admin.users.index')">
                                            المستخدمون
                                        </x-dropdown-link>
                                    @endcan
                                    @can('manage roles')
                                        <x-dropdown-link :href="route('admin.roles.index')">
                                            الأدوار
                                        </x-dropdown-link>
                                    @endcan
                                    @can('manage permissions')
                                        <x-dropdown-link :href="route('admin.permissions.index')">
                                            الصلاحيات
                                        </x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 bg-white rounded-md border border-transparent transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                    <div>الإعدادات</div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.shipping-lines.index')">
                                    الخطوط الملاحية
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.customs-ports.index')">
                                    المنافذ الجمركية
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.ship-groups.index')">
                                    مجموعات الشحن
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.departments.index')">
                                    الشركات
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.sections.index')">
                                    الأقسام
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.shipment-types.index')">
                                    أنواع الشحنات
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.shipment-statuses.index')">
                                    حالات الشحنات
                                </x-dropdown-link>
                         
                                <x-dropdown-link :href="route('admin.documents.index')">
                                    المستندات
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('admin.shipment-stages.index')">
                                    مراحل الشحن
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.warehouses.index')">
                                    المخازن
                                </x-dropdown-link>


                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium leading-4 text-gray-700 bg-white rounded-full border border-slate-200 transition duration-150 ease-in-out hover:border-indigo-300 focus:outline-none">
                                    <img src="{{ Auth::user()->avatar_url }}" alt="avatar" class="h-8 w-8 rounded-full border border-slate-100">
                                    <div class="text-left">
                                        <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-slate-500">الملف الشخصي</div>
                                    </div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current text-slate-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if(Auth::user()->is_system)
                                    <x-dropdown-link :href="route('admin.site-settings.edit')">
                                        إعدادات النظام
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="flex items-center -me-2 sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex justify-center items-center p-2 text-gray-400 rounded-md transition duration-150 ease-in-out hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                            <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        لوحة التحكم
                    </x-responsive-nav-link>
                    @can('view shipments')
                        <div class="my-2 border-t border-gray-200"></div>
                        <div class="px-4 py-2 text-xs text-gray-400">الشحنات</div>
                        <x-responsive-nav-link :href="route('shipments.index')"
                            :active="request()->routeIs('shipments.index')">
                            الشحن الدولي البحري
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('road-shipments.index')"
                            :active="request()->routeIs('road-shipments.*')">
                            الشحن الدولي البري
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('local-shipments.index')"
                            :active="request()->routeIs('local-shipments.*')">
                            الشحن المحلي البري
                        </x-responsive-nav-link>
                    @endcan
                    @can('view customs data')
                        <x-responsive-nav-link :href="route('customs.index')" :active="request()->routeIs('customs.*')">
                            البيانات الجمركية
                        </x-responsive-nav-link>
                    @endcan
                    @can('view reports')
                        <div class="my-2 border-t border-gray-200"></div>
                        <div class="px-4 py-2 text-xs text-gray-400">التقارير</div>
                        <x-responsive-nav-link :href="route('admin.reports.shipments')"
                            :active="request()->routeIs('admin.reports.shipments')">
                            تقرير الشحنات (بحر/جو)
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.reports.land_shipping')"
                            :active="request()->routeIs('admin.reports.land_shipping')">
                            تقرير الشحنات البرية  الدولية
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.reports.local_customs')"
                            :active="request()->routeIs('admin.reports.local_customs')">
                            تقرير الشحنات المحلية
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.reports.summary')"
                            :active="request()->routeIs('admin.reports.summary')">
                            التقرير المجمّع
                        </x-responsive-nav-link>
                    @endcan

                    @if(auth()->user()->can('manage users') || auth()->user()->can('manage roles') || auth()->user()->can('manage permissions'))
                        <div class="my-2 border-t border-gray-200"></div>
                        <div class="px-4 py-2 text-xs text-gray-400">إدارة المستخدمين</div>

                        @can('manage users')
                            <x-responsive-nav-link :href="route('admin.users.index')"
                                :active="request()->routeIs('admin.users.*')">
                                المستخدمون
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage roles')
                            <x-responsive-nav-link :href="route('admin.roles.index')"
                                :active="request()->routeIs('admin.roles.*')">
                                الأدوار
                            </x-responsive-nav-link>
                        @endcan
                        @can('manage permissions')
                            <x-responsive-nav-link :href="route('admin.permissions.index')"
                                :active="request()->routeIs('admin.permissions.*')">
                                الصلاحيات
                            </x-responsive-nav-link>
                        @endcan
                    @endif

                    <div class="my-2 border-t border-gray-200"></div>
                    <div class="px-4 py-2 text-xs text-gray-400">الإعدادات</div>

                    @if(Auth::user()->is_system)
                        <x-responsive-nav-link :href="route('admin.site-settings.edit')"
                            :active="request()->routeIs('admin.site-settings.edit')">
                            إعدادات النظام
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('admin.shipping-lines.index')"
                        :active="request()->routeIs('admin.shipping-lines.*')">
                        الخطوط الملاحية
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.customs-ports.index')"
                        :active="request()->routeIs('admin.customs-ports.*')">
                        المنافذ الجمركية
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.ship-groups.index')"
                        :active="request()->routeIs('admin.ship-groups.*')">
                        مجموعات السفن
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.departments.index')"
                        :active="request()->routeIs('admin.departments.*')">
                        الشركات
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.sections.index')"
                        :active="request()->routeIs('admin.sections.*')">
                        الأقسام
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.shipment-stages.index')"
                        :active="request()->routeIs('admin.shipment-stages.*')">
                        مراحل الشحن
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.warehouses.index')"
                        :active="request()->routeIs('admin.warehouses.*')">
                        المخازن
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.shipment-statuses.index')"
                        :active="request()->routeIs('admin.shipment-statuses.*')">
                        حالات الشحنات
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.shipments.index')"
                        :active="request()->routeIs('admin.shipments.*')">
                        الشحنات
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.documents.index')"
                        :active="request()->routeIs('admin.documents.*')">
                        المستندات
                    </x-responsive-nav-link>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4 flex items-center gap-3">
                        <img src="{{ Auth::user()->avatar_url }}" alt="avatar" class="h-10 w-10 rounded-full border border-slate-200">
                        <div>
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
</nav>
