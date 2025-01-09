import React, { useState, useRef, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { X, Plus, Trash2 } from 'lucide-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

const NewVoucherModal = ({ isShow, onClose }) => {
    const [voucherItems, setVoucherItems] = useState([{
        description: '',
        amount: '',
        remarks: ''
    }]);

    const { data, setData, post, processing, errors } = useForm({
        purpose: '',
        voucher_items: [],
        total_amount: 0
    });

    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.key === "Escape") {
                onClose();
            }
        };
        window.addEventListener("keydown", handleKeyDown);
        return () => window.removeEventListener("keydown", handleKeyDown);
    }, []);

    const addVoucherItem = () => {
        setVoucherItems([...voucherItems, {
            description: '',
            amount: '',
            remarks: ''
        }]);
    };

    const removeVoucherItem = (index) => {
        const newItems = voucherItems.filter((_, i) => i !== index);
        setVoucherItems(newItems);
    };

    const updateVoucherItem = (index, field, value) => {
        const newItems = [...voucherItems];
        newItems[index][field] = value;
        setVoucherItems(newItems);

        // Update total amount when amount fields change
        if (field === 'amount') {
            const total = newItems.reduce((sum, item) =>
                sum + (parseFloat(item.amount) || 0), 0
            );
            setData('total_amount', total);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setData('voucher_items', voucherItems);

        post(route("vouchers.store"), {
        });
    };

    return (
<AuthenticatedLayout header={'New Voucher'}>
<div className='p-14 '>

                <div className="p-6 bg-white rounded-lg">

                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                Purpose
                            </label>
                            <textarea
                                value={data.purpose}
                                onChange={(e) => setData('purpose', e.target.value)}
                                className="w-full px-3 py-2 text-sm border dark:bg-gray-100 text-black rounded-lg focus:outline-none focus:ring-2 transition-all duration-200"
                                rows={3}
                            />
                            {errors.purpose && <p className="mt-1 text-sm text-red-500">{errors.purpose}</p>}
                        </div>

                        <div className="space-y-4">
                            <div className="flex justify-between items-center">
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white">Voucher Items</h3>
                                <button
                                    type="button"
                                    onClick={addVoucherItem}
                                    className="flex items-center px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg"
                                >
                                    <Plus className="w-4 h-4 mr-1" />
                                    Add Item
                                </button>
                            </div>

                            {voucherItems.map((item, index) => (
                                <div key={index} className="p-4 border rounded-lg space-y-3">
                                    <div className="flex justify-between">
                                        <h4 className="text-sm font-medium text-gray-700 dark:text-white">Item {index + 1}</h4>
                                        {voucherItems.length > 1 && (
                                            <button
                                                type="button"
                                                onClick={() => removeVoucherItem(index)}
                                                className="text-red-500 hover:text-red-700"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        )}
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                                Description
                                            </label>
                                            <input
                                                type="text"
                                                value={item.description}
                                                onChange={(e) => updateVoucherItem(index, 'description', e.target.value)}
                                                className="w-full px-3 py-2 text-sm border dark:bg-gray-100 text-black rounded-lg"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                                Amount
                                            </label>
                                            <input
                                                type="number"
                                                step="0.01"
                                                value={item.amount}
                                                onChange={(e) => updateVoucherItem(index, 'amount', e.target.value)}
                                                className="w-full px-3 py-2 text-sm border dark:bg-gray-100 text-black rounded-lg"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                                            Remarks
                                        </label>
                                        <textarea
                                            value={item.remarks}
                                            onChange={(e) => updateVoucherItem(index, 'remarks', e.target.value)}
                                            className="w-full px-3 py-2 text-sm border dark:bg-gray-100 text-black rounded-lg"
                                            rows={2}
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="flex justify-between items-center pt-4 border-t">
                            <div className="text-lg font-semibold">
                                Total Amount: ${data.total_amount.toFixed(2)}
                            </div>
                            <div className="flex gap-3">
                                <button
                                    type="button"
                                    onClick={onClose}
                                    className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
                                >
                                    {processing ? "Processing..." : "Create Voucher"}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
</div>
    </AuthenticatedLayout>
    );
};

export default NewVoucherModal;
