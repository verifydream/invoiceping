<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    invoice: Object,
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

const markSent = () => router.post(route('invoices.mark-sent', props.invoice.id));
const markPaid = () => router.post(route('invoices.mark-paid', props.invoice.id));
const toggleReminder = () => router.post(route('invoices.toggle-reminder', props.invoice.id));
const deleteInvoice = () => {
    if (confirm('Delete this invoice?')) {
        router.delete(route('invoices.destroy', props.invoice.id));
    }
};
</script>

<template>
    <Head :title="'Invoice - ' + invoice.client_name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoice Detail</h2>
                <div class="space-x-2">
                    <Link :href="route('invoices.edit', invoice.id)" class="text-sm text-indigo-600 hover:underline">Edit</Link>
                    <Link :href="route('invoices.index')" class="text-sm text-gray-600 hover:underline">Back</Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <span :class="statusColor(invoice.status)" class="px-3 py-1 rounded-full text-sm font-medium capitalize">{{ invoice.status }}</span>
                        <span class="text-xs text-gray-500">Created {{ new Date(invoice.created_at).toLocaleDateString('id-ID') }}</span>
                    </div>

                    <!-- Client Info -->
                    <div>
                        <div class="text-sm text-gray-500">Client</div>
                        <div class="font-semibold text-lg">{{ invoice.client_name }}</div>
                        <div class="text-sm text-gray-600">{{ invoice.client_email }}</div>
                    </div>

                    <!-- Amount -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-500">Amount</div>
                        <div class="text-2xl font-bold text-indigo-600">{{ formatCurrency(invoice.amount) }}</div>
                    </div>

                    <!-- Description -->
                    <div v-if="invoice.description">
                        <div class="text-sm text-gray-500">Description</div>
                        <div class="text-gray-700">{{ invoice.description }}</div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-gray-500">Due Date</div>
                            <div class="font-medium">{{ invoice.due_date }}</div>
                        </div>
                        <div v-if="invoice.sent_at">
                            <div class="text-sm text-gray-500">Sent At</div>
                            <div class="font-medium">{{ new Date(invoice.sent_at).toLocaleDateString('id-ID') }}</div>
                        </div>
                    </div>

                    <!-- Reminder -->
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4">
                        <div>
                            <div class="text-sm font-medium">Auto-Reminder</div>
                            <div class="text-xs text-gray-500">
                                <span v-if="invoice.reminder_enabled">Active ({{ invoice.reminder_count }} sent)</span>
                                <span v-else>Disabled</span>
                            </div>
                        </div>
                        <button @click="toggleReminder" :class="invoice.reminder_enabled ? 'bg-green-500' : 'bg-gray-300'" class="relative inline-flex h-6 w-11 items-center rounded-full transition">
                            <span :class="invoice.reminder_enabled ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform bg-white rounded-full transition" />
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3 pt-4 border-t">
                        <button v-if="invoice.status === 'draft'" @click="markSent" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Mark as Sent</button>
                        <button v-if="invoice.status !== 'paid'" @click="markPaid" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">Mark as Paid</button>
                        <button @click="deleteInvoice" class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm ml-auto">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
