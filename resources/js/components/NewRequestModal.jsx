import React, { useState, useRef, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import {
  Package2,
  Receipt,
  Clock,
  CheckCircle2,
  XCircle,
  Plus,
  Trash2,
  X
} from 'lucide-react';

const NewRequestModal = ({ isShow, onClose, items }) => {
    const { data, setData, post, processing, errors } = useForm({
        item_id: '',
        quantity: '',
        reason: '',
    });

    const [loading, setLoading] = useState(false);
    const modalRef = useRef(null);

    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.key === "Escape") {
                onClose();
            }
        };
        window.addEventListener("keydown", handleKeyDown);
        return () => window.removeEventListener("keydown", handleKeyDown);
    }, []);

    const handleOutsideClick = (e) => {
        if (modalRef.current && !modalRef.current.contains(e.target)) {
            onClose();
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        post(route("consumption-requests.store"), {
            onSuccess: () => {
                setLoading(false);
                onClose();
            },
            onError: () => setLoading(false),
        });
    };

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4"
             onClick={handleOutsideClick}>
            <div className="bg-white w-full max-w-2xl dark:bg-gray-800 rounded-xl shadow-2xl relative transform transition-all duration-300 ease-in-out max-h-[90vh] overflow-y-auto"
                 ref={modalRef}>
                <div className="p-4">
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                            Create New Request
                        </h2>
                        <button onClick={onClose}
                                className="p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                            <X className="w-5 h-5 text-gray-500" />
                        </button>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-3">
                        <div className="flex flex-col">
                            <label htmlFor="item_id" className="block text-sm dark:text-white font-medium text-gray-700 mb-1">
                                Item
                            </label>
                            <select
                                id="item_id"
                                value={data.item_id}
                                onChange={(e) => setData("item_id", e.target.value)}
                                className={`w-full text-black px-3 py-2 text-sm border dark:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 transition-all duration-200 ${
                                    errors.item_id ? "border-red-500 focus:ring-red-200" : "border-gray-300 focus:ring-blue-200"
                                }`}
                            >
                                <option value="">Select Item</option>
                                {items?.map((item) => (
                                    <option key={item.id} value={item.id} disabled={item.quantity<1}>
                                        {item.name}
                                    </option>
                                ))}
                            </select>
                            {errors.item_id && <p className="mt-1 text-sm text-red-500">{errors.item_id}</p>}
                        </div>

                        <div className="flex flex-col">
                            <label htmlFor="quantity" className="block text-sm font-medium text-gray-700 mb-1 dark:text-white">
                                Quantity
                            </label>
                            <input
                                type="number"
                                id="quantity"
                                value={data.quantity}
                                onChange={(e) => setData("quantity", e.target.value)}
                                className={`w-full px-3 py-2 text-sm border dark:bg-gray-100 text-black rounded-lg focus:outline-none focus:ring-2 transition-all duration-200 ${
                                    errors.quantity ? "border-red-500 focus:ring-red-200" : "border-gray-300 focus:ring-blue-200"
                                }`}
                                min="1"
                            />
                            {errors.quantity && <p className="mt-1 text-sm text-red-500">{errors.quantity}</p>}
                        </div>



                        <div className="flex justify-end gap-2 mt-4">
                            <button
                                type="button"
                                onClick={onClose}
                                className="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors duration-200"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                disabled={processing || loading}
                                className={`px-3 py-1 text-xs font-medium text-white rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all duration-200 ${
                                    processing || loading
                                        ? "bg-gray-400 cursor-not-allowed"
                                        : "bg-blue-600 hover:bg-blue-700"
                                }`}
                            >
                                {processing || loading ? "Processing..." : "Create"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default NewRequestModal;
