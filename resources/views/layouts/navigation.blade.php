<nav x-data="{ open: false }" style="background:#161b22; border-bottom:1px solid rgba(255,255,255,0.08); position:sticky; top:0; z-index:50;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div style="display:flex; justify-content:space-between; align-items:center; height:56px;">

            {{-- Logo + Nav Links --}}
            <div style="display:flex; align-items:center; gap:2rem;">
                <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:0.6rem; text-decoration:none;">
                    <div style="width:32px;height:32px;background:#16a34a;border-radius:9px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 2px rgba(22,163,74,0.25);">
                        <i data-lucide="calendar" style="width:16px;height:16px;color:#fff;"></i>
                    </div>
                    <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:#e6edf3;letter-spacing:-0.02em;">EMS</span>
                </a>

                <div class="hidden sm:flex" style="align-items:center; gap:0.25rem;">
                    @php $active = 'color:#4ade80; background:rgba(74,222,128,0.08); border-radius:7px;'; $normal = 'color:rgba(230,237,243,0.5);'; @endphp

                    <a href="{{ route('dashboard') }}"
                       style="padding:0.4rem 0.85rem; font-size:0.875rem; font-weight:500; text-decoration:none; border-radius:7px; transition:color 0.15s, background 0.15s;
                              {{ request()->routeIs('dashboard') ? $active : $normal }}">
                        Dashboard
                    </a>
                    <a href="{{ route('events.index') }}"
                       style="padding:0.4rem 0.85rem; font-size:0.875rem; font-weight:500; text-decoration:none; border-radius:7px; transition:color 0.15s, background 0.15s;
                              {{ request()->routeIs('events.*') ? $active : $normal }}">
                        Events
                    </a>
                    <a href="{{ route('events.calendar') }}"
                       style="padding:0.4rem 0.85rem; font-size:0.875rem; font-weight:500; text-decoration:none; border-radius:7px; transition:color 0.15s, background 0.15s;
                              {{ request()->routeIs('events.calendar') ? $active : $normal }}">
                        Calendar
                    </a>
                    <a href="{{ route('registrations.index') }}"
                       style="padding:0.4rem 0.85rem; font-size:0.875rem; font-weight:500; text-decoration:none; border-radius:7px; transition:color 0.15s, background 0.15s;
                              {{ request()->routeIs('registrations.*') ? $active : $normal }}">
                        Registrations
                    </a>
                    @auth
                        @can('admin-only-cms')
                            <a href="{{ route('users.index') }}"
                               style="padding:0.4rem 0.85rem; font-size:0.875rem; font-weight:500; text-decoration:none; border-radius:7px; transition:color 0.15s, background 0.15s;
                                      {{ request()->routeIs('users.*') ? $active : $normal }}">
                                Users
                            </a>
                        @endcan
                    @endauth
                </div>
            </div>

            {{-- Right: Notifications + User --}}
            <div class="hidden sm:flex" style="align-items:center; gap:0.5rem;">
                @auth
                    @php
                        $user = Auth::user();
                        $pendingCount = $user->registrations()->where('status','pending')->count();
                    @endphp

                    {{-- Bell --}}
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <button style="position:relative;padding:0.45rem;background:transparent;border:1px solid rgba(255,255,255,0.08);border-radius:8px;cursor:pointer;color:rgba(230,237,243,0.5);transition:background 0.15s,color 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.06)';this.style.color='#e6edf3'" onmouseout="this.style.background='transparent';this.style.color='rgba(230,237,243,0.5)'">
                                <i data-lucide="bell" style="width:18px;height:18px;display:block;"></i>
                                @if($pendingCount > 0)
                                    <span style="position:absolute;top:-3px;right:-3px;width:16px;height:16px;background:#ef4444;color:#fff;font-size:0.65rem;font-weight:700;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ $pendingCount }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div style="background:#1c2333;border:1px solid rgba(255,255,255,0.1);border-radius:10px;overflow:hidden;min-width:200px;">
                                <div style="padding:0.6rem 1rem;font-size:0.7rem;font-weight:600;color:rgba(230,237,243,0.35);text-transform:uppercase;letter-spacing:0.08em;border-bottom:1px solid rgba(255,255,255,0.06);">Notifications</div>
                                @if($pendingCount > 0)
                                    <a href="{{ route('registrations.index') }}" style="display:block;padding:0.65rem 1rem;font-size:0.85rem;color:#fbbf24;text-decoration:none;">⏳ {{ $pendingCount }} pending registration(s)</a>
                                @else
                                    <div style="padding:0.75rem 1rem;font-size:0.85rem;color:rgba(230,237,243,0.4);">No new notifications</div>
                                @endif
                            </div>
                        </x-slot>
                    </x-dropdown>

                    {{-- User menu --}}
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <button style="display:flex;align-items:center;gap:0.5rem;padding:0.35rem 0.65rem;background:transparent;border:1px solid rgba(255,255,255,0.08);border-radius:8px;cursor:pointer;transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.06)'" onmouseout="this.style.background='transparent'">
                                <div style="width:28px;height:28px;background:rgba(22,163,74,0.2);border-radius:7px;display:flex;align-items:center;justify-content:center;color:#4ade80;font-size:0.8rem;font-weight:700;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span style="font-size:0.85rem;font-weight:500;color:rgba(230,237,243,0.75);">{{ explode(' ', $user->name)[0] }}</span>
                                <i data-lucide="chevron-down" style="width:14px;height:14px;color:rgba(230,237,243,0.35);"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div style="background:#1c2333;border:1px solid rgba(255,255,255,0.1);border-radius:10px;overflow:hidden;min-width:200px;">
                                <div style="padding:0.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.06);">
                                    <p style="font-size:0.875rem;font-weight:600;color:#e6edf3;margin:0;">{{ $user->name }}</p>
                                    <p style="font-size:0.75rem;color:rgba(230,237,243,0.4);margin:0.1rem 0 0;">{{ $user->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:0.5rem;padding:0.65rem 1rem;font-size:0.85rem;color:rgba(230,237,243,0.6);text-decoration:none;" onmouseover="this.style.background='rgba(255,255,255,0.04)';this.style.color='#e6edf3'" onmouseout="this.style.background='transparent';this.style.color='rgba(230,237,243,0.6)'">
                                    <i data-lucide="user" style="width:14px;height:14px;"></i> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" onclick="this.closest('form').submit()" style="display:flex;align-items:center;gap:0.5rem;padding:0.65rem 1rem;font-size:0.85rem;color:rgba(248,113,113,0.8);width:100%;background:transparent;border:none;cursor:pointer;text-align:left;" onmouseover="this.style.background='rgba(255,255,255,0.04)';this.style.color='#f87171'" onmouseout="this.style.background='transparent';this.style.color='rgba(248,113,113,0.8)'">
                                        <i data-lucide="log-out" style="width:14px;height:14px;"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <div class="sm:hidden">
                <button @click="open = !open" style="padding:0.45rem;background:transparent;border:1px solid rgba(255,255,255,0.08);border-radius:8px;cursor:pointer;color:rgba(230,237,243,0.6);">
                    <i data-lucide="menu" style="width:20px;height:20px;display:block;"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden" style="border-top:1px solid rgba(255,255,255,0.06);padding:0.75rem 1rem 1rem;">
        <div style="display:flex;flex-direction:column;gap:0.25rem;">
            <a href="{{ route('dashboard') }}" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:rgba(230,237,243,0.6);text-decoration:none;border-radius:8px;">Dashboard</a>
            <a href="{{ route('events.index') }}" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:rgba(230,237,243,0.6);text-decoration:none;border-radius:8px;">Events</a>
            <a href="{{ route('events.calendar') }}" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:rgba(230,237,243,0.6);text-decoration:none;border-radius:8px;">Calendar</a>
            <a href="{{ route('registrations.index') }}" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:rgba(230,237,243,0.6);text-decoration:none;border-radius:8px;">Registrations</a>
            @auth
                @can('admin-only-cms')
                    <a href="{{ route('users.index') }}" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:rgba(230,237,243,0.6);text-decoration:none;border-radius:8px;">Users</a>
                @endcan
                <div style="margin-top:0.5rem;padding-top:0.75rem;border-top:1px solid rgba(255,255,255,0.06);">
                    @php $user = Auth::user(); @endphp
                    <p style="font-size:0.8rem;color:rgba(230,237,243,0.35);padding:0 0.85rem 0.5rem;">{{ $user->name }} · {{ $user->email }}</p>
                    <a href="{{ route('profile.edit') }}" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:rgba(230,237,243,0.6);text-decoration:none;border-radius:8px;display:block;">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="padding:0.6rem 0.85rem;font-size:0.875rem;color:#f87171;background:transparent;border:none;cursor:pointer;display:block;width:100%;text-align:left;border-radius:8px;">Log Out</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>