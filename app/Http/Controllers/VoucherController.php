<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class VoucherController extends Controller
{
    public function index () {
        $vouchers = Voucher::all();
        return Inertia::render('Voucher/Index', ['vouchers' => $vouchers]);

    }

    public function store()
    {
        $request = request();

        try {
            // Step 1: Validate the main voucher data
            $validatedData = $request->validate([
                'purpose' => 'required|string|max:1000',
                'voucher_items' => 'required|array|min:1',
                'voucher_items.*.description' => 'required|string|max:255',
                'voucher_items.*.amount' => 'required|numeric|min:0.01',
                'voucher_items.*.remarks' => 'nullable|string|max:1000',
                'total_amount' => 'required|numeric|min:0.01'
            ]);

            // Step 2: Begin the database transaction
            DB::beginTransaction();

            // Generate the voucher number
            $latestVoucher = Voucher::latest()->first();
            $voucherNumber = 'V-' . str_pad(
                $latestVoucher ? (intval(substr($latestVoucher->voucher_number, 2)) + 1) : 1,
                6,
                '0',
                STR_PAD_LEFT
            );

            // Step 3: Create the voucher
            $voucher = Voucher::create([
                'voucher_number' => $voucherNumber,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'purpose' => $validatedData['purpose'],
                'total_amount' => $validatedData['total_amount'],
            ]);

            // Step 4: Create voucher items
            $voucherItems = collect($validatedData['voucher_items'])->map(function ($item) use ($voucher) {
                return new VoucherItem([
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
            });

            $voucher->voucherItems()->saveMany($voucherItems);

            // Step 5: Verify total amount
            $calculatedTotal = $voucherItems->sum('amount');
            if (abs($calculatedTotal - $validatedData['total_amount']) > 0.01) {
                throw new \Exception('Total amount does not match sum of items.');
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Voucher created successfully.');
        } catch (ValidationException $e) {
            // Catch validation errors
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to create voucher. Please try again.');
        }
    }

    public function create () {
        return Inertia::render('NewVoucher',[]);
    }

    public function update () {

    }
    public function delete () {

    }
}
