<div
    x-data="toastNotification()"
    x-on:notify.window="show($event.detail)"
    class="fixed top-4 right-0 z-[9999] space-y-3 w-full max-w-sm">

    <template x-for="item in notifications" :key="item.id">

        <div
            x-show="item.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-5"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-5"
            class="bg-white border border-gray-200 shadow-xl rounded-2xl p-4 flex gap-3">

            {{-- ICON --}}
            <div class="shrink-0">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    💬
                </div>
            </div>

            {{-- CONTENT --}}
            <div class="flex-1 min-w-0">

                <div class="flex items-start justify-between gap-2">

                    <div>
                        <p class="text-sm font-semibold text-gray-800"
                            x-text="item.title"></p>

                        <p class="text-sm text-gray-500 mt-1 line-clamp-2"
                            x-text="item.message"></p>
                    </div>

                    <button
                        @click="remove(item.id)"
                        class="text-gray-400 hover:text-gray-600 text-sm">
                        ✕
                    </button>

                </div>

            </div>
        </div>

    </template>
</div>

<script>
    function toastNotification() {
        return {
            notifications: [],

            show(data) {

                const id = Date.now();

                this.notifications.push({
                    id,
                    title: data.title || 'Notifikasi',
                    message: data.message || '',
                    visible: true
                });

                setTimeout(() => {
                    this.remove(id);
                }, 5000);
            },

            remove(id) {
                const notif = this.notifications.find(n => n.id === id);

                if (!notif) return;

                notif.visible = false;

                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 300);
            }
        }
    }
</script>