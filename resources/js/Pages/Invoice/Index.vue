<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    invoices: Object,
    stats: Object,
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
};

const statusColor = (status) => {
    const colors = {
        draft: 'bg-gray-100 text-gray-800',
        sent: 'bg-blue-100 text-blue-800',
        paid: 'bg-green-100 text-green-800',
        overdue: 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const deleteInvoice = (id) => {
    if (confirm('Delete this invoice?')) {
        router.delete(route('invoices.destroy', id));
    }
};

const markSent = (id) => {
    router.post(route('invoices.mark-sent', id));
};

const markPaid = (id) => {
    router.post(route('invoices.mark-paid', id));
};
</script>

<template>
    <Head title="Invoices" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoices</h2>
                <Link :href="route('invoices.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    + New Invoice
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-xs text-gray-500">Total</div>
                        <div class="text-lg font-bold">{{ stats.total }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-xs text-gray-500">Outstanding</div>
                        <div class="text-lg font-bold text-blue-600">{{ formatCurrency(stats.outstanding) }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-xs text-gray-500">Overdue</div>
                        <div class="text-lg font-bold text-red-600">{{ stats.overdue }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-xs text-gray-500">Paid</div>
                        <div class="text-lg font-bold text-green-600">{{ stats.paid }}</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Client</th>
                                <th class="px-6 py-3">Amount</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Due Date</th>
                                <th class="px-6 py-3">Reminder</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="invoices.data.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">No invoices found.</td>
                            </tr>
                            <tr v-for="inv in invoices.data" :key="inv.id" class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ inv.client_name }}</div>
                                    <div class="text-xs text-gray-500">{{ inv.client_email }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium">{{ formatCurrency(inv.amount) }}</td>
                                <td class="px-6 py-4">
                                    <span :class="statusColor(inv.status)" class="px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">{{ inv.status }}</span>
                                </td>
                                <td class="px-6 py-4">{{ inv.due_date }}</td>
                                <td class="px-6 py-4">
                                    <span v-if="inv.reminder_enabled" class="text-green-600 text-xs">✓ On</span>
                                    <span v-else class="text-gray-400 text-xs">Off</span>
                                </td>
                                <td class="px-6 py-4 space-x-1">
                                    <Link :href="route('invoices.show', inv.id)" class="text-indigo-600 hover:underline text-xs">View</Link>
                                    <button v-if="inv.status === 'draft'" @click="markSent(inv.id)" class="text-blue-600 hover:underline text-xs">Send</button>
                                    <button v-if="inv.status !== 'paid'" @click="markPaid(inv.id)" class="text-green-600 hover:underline text-xs">Paid</button>
                                    <button @click="deleteInvoice(inv.id)" class="text-red-600 hover:underline text-xs">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div v-if="invoices.last_page > 1" class="p-4 border-t flex justify-center space-x-2">
                        <Link v-for="page in invoices.last_page" :key="page" :href="invoices.path + '?page=' + page"
                              :class="page === invoices.current_page ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700'"
                              class="px-3 py-1 rounded text-sm">{{ page }}</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
