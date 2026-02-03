<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        لوحة التحكم
                    </x-nav-link>
                    @can('view shipments')
                        <x-nav-link :href="route('shipments.index')" :active="request()->routeIs('shipments.index')">
                            قائمة الشحنات
                        </x-nav-link>
                    @endcan
                    @can('manage shipments')
                        <x-nav-link :href="route('shipments.create')" :active="request()->routeIs('shipments.create')">
                            إدخال شحنة
                        </x-nav-link>
                    @endcan
                    @can('view customs')
                        <x-nav-link :href="route('customs.index')" :active="request()->routeIs('customs.*')">
                            البيانات الجمركية
                        </x-nav-link>
                    @endcan
                    @can('view reports')
                        <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            التقارير
                        </x-nav-link>
                    @endcan

                    <!-- User Management Dropdown -->
                    @if(auth()->user()->can('manage users') || auth()->user()->can('manage roles') || auth()->user()->can('manage permissions'))
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>إدارة المستخدمين</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
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
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>الإعدادات</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
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
                                    الأقسام
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.sections.index')">
                                    الشعب
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.shipment-types.index')">
                                    أنواع الشحنات
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.shipment-statuses.index')">
                                    حالات الشحنات
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.shipments.index')">
                                    الشحنات
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.documents.index')">
                                    المستندات
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
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
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
                        <x-responsive-nav-link :href="route('shipments.index')"
                            :active="request()->routeIs('shipments.index')">
                            قائمة الشحنات
                        </x-responsive-nav-link>
                    @endcan
                    @can('manage shipments')
                        <x-responsive-nav-link :href="route('shipments.create')"
                            :active="request()->routeIs('shipments.create')">
                            إدخال شحنة
                        </x-responsive-nav-link>
                    @endcan
                    @can('view customs')
                        <x-responsive-nav-link :href="route('customs.index')" :active="request()->routeIs('customs.*')">
                            البيانات الجمركية
                        </x-responsive-nav-link>
                    @endcan

                    @can('view reports')
                        <x-responsive-nav-link :href="route('admin.reports.index')"
                            :active="request()->routeIs('admin.reports.*')">
                            التقارير
                        </x-responsive-nav-link>
                    @endcan

                    @if(auth()->user()->can('manage users') || auth()->user()->can('manage roles') || auth()->user()->can('manage permissions'))
                        <div class="border-t border-gray-200 my-2"></div>
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

                    <div class="border-t border-gray-200 my-2"></div>
                    <div class="px-4 py-2 text-xs text-gray-400">الإعدادات</div>

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
                        الأقسام
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.sections.index')"
                        :active="request()->routeIs('admin.sections.*')">
                        الشعب
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.shipment-types.index')"
                        :active="request()->routeIs('admin.shipment-types.*')">
                        أنواع الشحنات
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
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
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