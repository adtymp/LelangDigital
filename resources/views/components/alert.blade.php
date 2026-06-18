<div
    x-data="{
        notifications: [],

        show(type, message) {

            let id = Date.now()

            this.notifications.push({
                id,
                type,
                message,
                visible: true
            })

            setTimeout(() => {
                this.remove(id)
            }, 4000)
        },

        remove(id) {
            let notif = this.notifications.find(n => n.id === id)

            if (notif) notif.visible = false

            setTimeout(() => {
                this.notifications = this.notifications.filter(n => n.id !== id)
            }, 300)
        }
    }"

    x-init="
        @if(session('success'))
            show('success', '{{ session('success') }}')
        @endif

        @if(session('error'))
            show('error', '{{ session('error') }}')
        @endif

        @if($errors->any())
        show('error', '{{ $errors->first() }}')
        @endif
    "

    @toast.window="show($event.detail.type, $event.detail.message)"

    class="fixed top-4 right-4 z-[9999] space-y-3 w-full max-w-sm">

    <template x-for="item in notifications" :key="item.id">

        <div
            x-show="item.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 translate-y-2"

            class="relative overflow-hidden rounded-2xl border shadow-xl backdrop-blur-sm p-4 pr-10"

            :class="{
                'bg-emerald-50 border-emerald-200 text-emerald-700': item.type === 'success',
                'bg-red-50 border-red-200 text-red-700': item.type === 'error'
            }">

            <div class="flex items-start gap-3">

                <div class="mt-0.5">

                    <template x-if="item.type === 'success'">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    </template>

                    <template x-if="item.type === 'error'">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    </template>

                </div>

                <div class="flex-1">
                    <p class="font-semibold"
                        x-text="item.type === 'success' ? 'Berhasil' : 'Terjadi Kesalahan'">
                    </p>

                    <p class="text-sm mt-1 opacity-90" x-text="item.message"></p>
                </div>

            </div>

            <button
                @click="remove(item.id)"
                class="absolute top-3 right-3 opacity-60 hover:opacity-100 transition">

                ✕
            </button>

        </div>

    </template>
</div>