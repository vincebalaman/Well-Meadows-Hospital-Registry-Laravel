@php
    $navBaseClasses = 'flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-200';
    $navActiveClasses = 'bg-cyan-100 text-cyan-900 shadow-[0_12px_30px_-18px_rgba(14,116,144,0.35)]';
    $navInactiveClasses = 'text-slate-700 hover:bg-slate-100 hover:text-slate-900';
@endphp

<nav x-data="{ open: false }" class="app-sidebar">
    <div class="flex items-center justify-between px-5 py-4 lg:px-6 lg:py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700">
                <img src="/images/wellmeadows-logo.png" alt="Well Meadows Hospital Logo" class="block h-7 w-7" />
            </div>
            <div>
                <p class="text-[0.65rem] font-semibold uppercase tracking-[0.35em] text-cyan-700">Well Meadows</p>
                <p class="text-base font-semibold text-slate-900">Hospital Registry</p>
            </div>
        </a>

        <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 p-2 text-slate-700 lg:hidden">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:block">
        <div class="px-5 pb-5 lg:px-6">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                <p class="text-[0.65rem] uppercase tracking-[0.35em] text-slate-500">Signed in as</p>
                <p class="mt-3 text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                <p class="text-sm text-slate-600">{{ Auth::user()->email }}</p>
                <p class="mt-3 inline-flex rounded-full bg-cyan-100 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-[0.28em] text-cyan-800">
                    {{ ucfirst(Auth::user()->role) }}
                </p>
            </div>
        </div>

        <div class="px-3 pb-4 lg:px-4">
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('dashboard') ? $navActiveClasses : $navInactiveClasses }}">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">⌂</span>
                    <span>{{ __('Dashboard') }}</span>
                </a>

                @if(auth()->user()->isPatient())
                    @if(auth()->user()->patient)
                        @if(auth()->user()?->patient?->id)
                            <a href="{{ route('patients.comprehensiveRecord', auth()->user()->patient->id) }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patients.comprehensiveRecord') ? $navActiveClasses : $navInactiveClasses }}">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">⟡</span>
                                <span>{{ __('My Comprehensive Record') }}</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('patients.create') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patients.create') ? $navActiveClasses : $navInactiveClasses }}">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">+</span>
                            <span>{{ __('Complete Registration') }}</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('patients.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patients.index') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">◌</span>
                        <span>{{ __('Patient Directory') }}</span>
                    </a>
                @endif

                <a href="{{ route('patientbillings.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('patientbillings.*') ? $navActiveClasses : $navInactiveClasses }}">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">₿</span>
                    <span>{{ __('Patient Billings') }}</span>
                </a>

                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'staff']))
                    <a href="{{ route('inpatientstays.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('inpatientstays.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">✚</span>
                        <span>{{ __('Inpatient Stays') }}</span>
                    </a>
                    <a href="{{ route('beds.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('beds.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">□</span>
                        <span>{{ __('Beds') }}</span>
                    </a>
                    @if(auth()->check() && in_array(auth()->user()->role, ['admin']))
                        <a href="{{ route('staffs.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('staffs.*') ? $navActiveClasses : $navInactiveClasses }}">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">☻</span>
                            <span>{{ __('Staff') }}</span>
                        </a>
                    @endif
                    <a href="{{ route('staff-assignments.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('staff-assignments.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">✦</span>
                        <span>{{ __('Staff Assignments') }}</span>
                    </a>
                    <a href="{{ route('staff-allocations.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('staff-allocations.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">⇄</span>
                        <span>{{ __('Staff Allocations') }}</span>
                    </a>
                    <a href="{{ route('wards.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('wards.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">⌂</span>
                        <span>{{ __('Wards') }}</span>
                    </a>
                    <a href="{{ route('appointments.index') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('appointments.*') ? $navActiveClasses : $navInactiveClasses }}">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">✦</span>
                        <span>{{ __('Appointments') }}</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="border-t border-slate-200 px-3 pb-4 pt-4 lg:px-4">
            <a href="{{ route('profile.edit') }}" class="{{ $navBaseClasses }} {{ request()->routeIs('profile.edit') ? $navActiveClasses : $navInactiveClasses }}">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-cyan-700">⚙</span>
                <span>{{ __('Profile') }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="{{ $navBaseClasses }} w-full text-left text-slate-700 hover:bg-rose-50 hover:text-rose-700">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white text-sm text-rose-600">↩</span>
                    <span>{{ __('Log Out') }}</span>
                </button>
            </form>
        </div>
    </div>
</nav>
