import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React, { useState } from 'react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Clock,
    CheckCircle2,
    DollarSign,
    Plus,
} from 'lucide-react';
import { Inertia } from '@inertiajs/inertia';

export default function Index({ auth, vouchers }) {
    const [showNewVoucherModal, setShowNewVoucherModal] = useState(false);

    const getStatusIcon = (status) => {
        switch (status) {
            case 'pending':
                return <Clock className="h-4 w-4 text-yellow-500" />;
            case 'approved':
                return <CheckCircle2 className="h-4 w-4 text-green-500" />;
            case 'paid':
                return <DollarSign className="h-4 w-4 text-blue-500" />;
            default:
                return null;
        }
    };

    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    };

    const formatAmount = (amount) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Vouchers
                </h2>
            }
        >
            <Head title="Vouchers" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 max-w-7xl mx-auto space-y-6">
                            <div className="flex justify-between items-center">
                                <h1 className="text-3xl font-bold">Payment Vouchers</h1>
                                <Button onClick={() => Inertia.visit('vouchers/create')} className="text-white">
                                    <Plus className="h-4 w-4 mr-2" />
                                    New Voucher
                                </Button>
                            </div>

                            {showNewVoucherModal && (
                                <NewVoucherModal
                                    isShow={showNewVoucherModal}
                                    onClose={() => setShowNewVoucherModal(false)}
                                />
                            )}

                            <Card>
                                <CardHeader>
                                    <CardTitle>Recent Vouchers</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead>Voucher No.</TableHead>
                                                <TableHead>Date</TableHead>
                                                <TableHead>Purpose</TableHead>
                                                <TableHead>Amount</TableHead>
                                                <TableHead>Status</TableHead>
                                                <TableHead>Processed By</TableHead>
                                                <TableHead>Actions</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {vouchers?.map((voucher) => (
                                                <TableRow key={voucher.id}>
                                                    <TableCell className="font-medium">
                                                        {voucher.voucher_number}
                                                    </TableCell>
                                                    <TableCell>{formatDate(voucher.created_at)}</TableCell>
                                                    <TableCell className="max-w-xs truncate">
                                                        {voucher.purpose}
                                                    </TableCell>
                                                    <TableCell className="font-medium">
                                                        {formatAmount(voucher.total_amount)}
                                                    </TableCell>
                                                    <TableCell>
                                                        <div className="flex items-center gap-2">
                                                            {getStatusIcon(voucher.status)}
                                                            <span className="capitalize">{voucher.status}</span>
                                                        </div>
                                                    </TableCell>
                                                    <TableCell>
                                                        {voucher.processed_by?.name || '-'}
                                                    </TableCell>
                                                    <TableCell>
                                                        <div className="flex gap-2">
                                                            <Button
                                                                variant="ghost"
                                                                size="sm"
                                                                onClick={() => router.visit(route('vouchers.show', voucher.id))}
                                                            >
                                                                View
                                                            </Button>
                                                            {voucher.status === 'pending' && auth.user.id === voucher.user_id && (
                                                                <Button
                                                                    variant="destructive"
                                                                    size="sm"
                                                                    onClick={() => handleDelete(voucher.id)}
                                                                >
                                                                    Delete
                                                                </Button>
                                                            )}
                                                        </div>
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                            {!vouchers?.length && (
                                                <TableRow>
                                                    <TableCell colSpan={7} className="text-center py-6 text-gray-500">
                                                        No vouchers found
                                                    </TableCell>
                                                </TableRow>
                                            )}
                                        </TableBody>
                                    </Table>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
