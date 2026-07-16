<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    invoice: Object,
});

const form = useForm({
    client_name: props.invoice.client_name,
    client_email: props.invoice.client_email,
    amount: props.invoice.amount,
    currency: props.invoice.currency,
    description: props.invoice.description || '',
    due_date: props.invoice.due_date,
});

const submit = () => {
    form.put(route('invoices.update', props.invoice.id));
};
</script>

<template>
    <Head title="Edit Invoice" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Invoice</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="client_name" value="Client Name" />
                            <TextInput id="client_name" v-model="form.client_name" type="text" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.client_name" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="client_email" value="Client Email" />
                            <TextInput id="client_email" v-model="form.client_email" type="email" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.client_email" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="amount" value="Amount" />
                                <TextInput id="amount" v-model="form.amount" type="number" step="0.01" min="0" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.amount" class="mt-2" />
                            </div>
                            <div>
                                <InputLabel for="currency" value="Currency" />
                                <select id="currency" v-model="form.currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="IDR">IDR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="description" value="Description (optional)" />
                            <textarea id="description" v-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="due_date" value="Due Date" />
                            <TextInput id="due_date" v-model="form.due_date" type="date" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.due_date" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <Link :href="route('invoices.show', invoice.id)" class="text-gray-600 hover:text-gray-900 text-sm">Cancel</Link>
                            <PrimaryButton :disabled="form.processing">Update Invoice</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
