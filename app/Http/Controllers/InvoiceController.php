<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = $request->user()
            ->invoices()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => $request->user()->invoices()->count(),
            'outstanding' => $request->user()->invoices()->whereIn('status', ['sent', 'overdue'])->sum('amount'),
            'overdue' => $request->user()->invoices()->where('status', 'overdue')->count(),
            'paid' => $request->user()->invoices()->where('status', 'paid')->count(),
        ];

        return Inertia::render('Invoice/Index', [
            'invoices' => $invoices,
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        return Inertia::render('Invoice/Create');
    }

    public function store(Request $request)
    {
        $limit = config('services.freemium.limit', 30);
        $count = $request->user()->invoices()->count();

        if ($count >= $limit) {
            return back()->withErrors([
                'client_name' => "Free plan limit reached ({$limit} invoices). Upgrade to Pro for unlimited.",
            ]);
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:0|max:9999999999.99',
            'currency' => 'required|string|in:IDR,USD',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date|after:today',
        ]);

        $invoice = $request->user()->invoices()->create([
            ...$validated,
            'status' => 'draft',
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);

        return Inertia::render('Invoice/Show', [
            'invoice' => $invoice,
        ]);
    }

    public function edit(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);

        return Inertia::render('Invoice/Edit', [
            'invoice' => $invoice,
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:0|max:9999999999.99',
            'currency' => 'required|string|in:IDR,USD',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted.');
    }

    public function markSent(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);
        $invoice->markAsSent();

        return back()->with('success', 'Invoice marked as sent.');
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);
        $invoice->markAsPaid();

        return back()->with('success', 'Invoice marked as paid.');
    }

    public function toggleReminder(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($request, $invoice);
        $invoice->update(['reminder_enabled' => !$invoice->reminder_enabled]);

        return back()->with('success', 'Reminder ' . ($invoice->reminder_enabled ? 'enabled' : 'disabled') . '.');
    }

    private function authorizeInvoice(Request $request, Invoice $invoice): void
    {
        abort_if($invoice->user_id !== $request->user()->id, 403);
    }
}
