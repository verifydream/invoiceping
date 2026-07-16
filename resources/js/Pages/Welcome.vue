<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="InvoicePing - Never Lose a Payment Again" />

    <div class="min-h-screen bg-white">
        <!-- Nav -->
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="text-xl font-bold text-indigo-600">💰 InvoicePing</div>
            <div class="space-x-4">
                <Link v-if="canLogin" :href="route('login')" class="text-gray-600 hover:text-gray-900">Login</Link>
                <Link v-if="canRegister" :href="route('register')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">Get Started Free</Link>
            </div>
        </nav>

        <!-- Hero -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-6">
                Jangan Lagi Kehilangan<br>Pembayaran karena Lupa
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                InvoicePing membantu freelancer dan agency kecil mengingatkan follow-up invoice secara otomatis. 
                Tidak perlu software accounting yang rumit.
            </p>
            <Link v-if="canRegister" :href="route('register')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg text-lg font-medium">
                Mulai Gratis →
            </Link>
            <p class="text-sm text-gray-500 mt-4">Gratis untuk 30 invoice. Tanpa kartu kredit.</p>
        </div>

        <!-- Features -->
        <div class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Cukup Tiga Hal</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                        <div class="text-4xl mb-4">📝</div>
                        <h3 class="text-xl font-semibold mb-2">Catat Invoice</h3>
                        <p class="text-gray-600">Masukkan detail invoice dan client. Selesai dalam 30 detik.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                        <div class="text-4xl mb-4">⏰</div>
                        <h3 class="text-xl font-semibold mb-2">Otomatis Follow-up</h3>
                        <p class="text-gray-600">Email reminder terkirim otomatis di H+14, H+21, H+30.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                        <div class="text-4xl mb-4">💰</div>
                        <h3 class="text-xl font-semibold mb-2">Dibayar Lebih Cepat</h3>
                        <p class="text-gray-600">Klien tidak lupa bayar. Cashflow lancar.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Proof -->
        <div class="py-16">
            <div class="max-w-3xl mx-auto text-center px-4">
                <blockquote class="text-xl italic text-gray-700">
                    "InvoicePing membantu saya follow-up invoice tanpa harus ingat-ingat. 
                    Sekarang klien saya lebih cepat bayar!"
                </blockquote>
                <p class="mt-4 text-gray-500">— Freelance Developer</p>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-indigo-600 py-16">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl font-bold text-white mb-4">Siap Mulai?</h2>
                <p class="text-indigo-100 mb-8">Buat akun gratis dalam 30 detik. Tanpa kartu kredit.</p>
                <Link v-if="canRegister" :href="route('register')" class="bg-white hover:bg-gray-100 text-indigo-600 px-8 py-3 rounded-lg text-lg font-medium">
                    Daftar Gratis Sekarang
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-8 text-center text-gray-500 text-sm">
            <p>&copy; {{ new Date().getFullYear() }} InvoicePing. Dibuat dengan ❤️ untuk freelancer Indonesia.</p>
        </footer>
    </div>
</template>
