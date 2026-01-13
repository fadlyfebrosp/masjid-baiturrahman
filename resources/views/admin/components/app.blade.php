<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title')</title>

<link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
<script src="https://cdn.tailwindcss.com"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script defer src="https://unpkg.com/alpinejs@3/dist/cdn.min.js"></script>

<style>
.no-scrollbar::-webkit-scrollbar{display:none}
.no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
</style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
<div
    x-data="sidebarState()"
    x-init="init()"
    @toggle-sidebar.window="toggle()"
    @close-sidebar.window="close()"
    class="flex h-screen overflow-hidden"
>

    {{-- SIDEBAR --}}
    <aside
        class="fixed inset-y-0 left-0 w-64 bg-green-700 text-white z-50
               transform transition-transform duration-300
               lg:static lg:translate-x-0 lg:z-0"
        :class="open ? 'translate-x-0' : '-translate-x-full'"
    >
        @include('admin.components.sidebar')
    </aside>

    {{-- OVERLAY (MOBILE ONLY) --}}
    <div
        x-show="open && !isDesktop"
        @click="close()"
        x-transition.opacity
        class="fixed inset-0 bg-black/30 z-40 lg:hidden"
    ></div>

    {{-- MAIN AREA --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- NAVBAR --}}
        @include('admin.components.navbar')

        {{-- CONTENT --}}
        <main class="flex-1 p-6 overflow-y-auto no-scrollbar">
            @yield('content')
        </main>

        @include('admin.components.footer')

    </div>

</div>

<script>
function sidebarState(){
    return {
        open: false,

        get isDesktop(){
            return window.innerWidth >= 1024
        },

        init(){
            // desktop: sidebar selalu tampil
            this.open = this.isDesktop

            window.addEventListener('resize', () => {
                this.open = this.isDesktop
            })
        },

        toggle(){
            // HANYA MOBILE
            if (!this.isDesktop) {
                this.open = !this.open
            }
        },

        close(){
            if (!this.isDesktop) {
                this.open = false
            }
        }
    }
}
</script>
</body>
</html>
