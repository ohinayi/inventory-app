import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React, { useState } from 'react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import NewRequestModal from '../components/NewRequestModal';

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
    Trash2
} from 'lucide-react';

export default function Dashboard({auth, items, consumptionRequests}) {
    // console.log(items)
    const [activeTab, setActiveTab] = useState('requests');
    const [showNewRequestModal, setShowNewRequestModal] = useState(false);
    const [showNewVoucherModal, setShowNewVoucherModal] = useState(false);

    // Mock data
    // const consumptionRequests = [
    //     {
    //         id: 1,
    //         date: '2025-01-08',
    //         status: 'pending',
    //         item: { name: 'Printer Paper' },
    //         quantity: 2,
    //         reason: 'Monthly supply',
    //         approved_by: null,
    //         approved_at: null
    //     }
    // ];

    const vouchers = [
        {
            id: 1,
            voucher_number: 'V-2025-001',
            date: '2025-01-08',
            status: 'pending',
            purpose: 'Office supplies reimbursement',
            total_amount: 150.00,
            items: [
                { description: 'Printer Paper', amount: 50.00, remarks: 'For office use' },
                { description: 'Ink Cartridges', amount: 100.00, remarks: 'HP Printer' }
            ]
        }
    ];

    const getStatusIcon = (status) => {
        switch (status) {
            case 'pending':
                return <Clock className="h-4 w-4 text-yellow-500" />;
            case 'approved':
            case 'completed':
                return <CheckCircle2 className="h-4 w-4 text-green-500" />;
            case 'rejected':
                return <XCircle className="h-4 w-4 text-red-500" />;
            default:
                return null;
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 max-w-7xl mx-auto space-y-6">
                            <div className="flex justify-between items-center">
                                <h1 className="text-3xl font-bold">user Dashboard</h1>
                                <div className="space-x-2">
                                    <Button onClick={() => setShowNewRequestModal(true)} className='text-white'>
                                        <Plus className="h-4 w-4 mr-2" />
                                        New Request
                                    </Button>
                                    <Button variant="outline" onClick={() => setShowNewVoucherModal(true)}>
                                        <Plus className="h-4 w-4 mr-2" />
                                        New Voucher
                                    </Button>
                                </div>
                            </div>

                            {showNewRequestModal && (
                                <NewRequestModal
                                    isShow={showNewRequestModal}
                                    onClose={() => setShowNewRequestModal(false)}
                                    items={items}
                                />
                            )}

                            <Tabs defaultValue="requests" className="w-full">
                                <TabsList>
                                    <TabsTrigger value="requests" className="flex items-center">
                                        <Package2 className="h-4 w-4 mr-2" />
                                        Consumption Requests
                                    </TabsTrigger>
                                    <TabsTrigger value="vouchers" className="flex items-center">
                                        <Receipt className="h-4 w-4 mr-2" />
                                        Vouchers
                                    </TabsTrigger>
                                </TabsList>

                                <TabsContent value="requests">
                                    <Card>
                                        <CardHeader>
                                            <CardTitle>Recent Consumption Requests</CardTitle>
                                        </CardHeader>
                                        <CardContent>
                                            <Table>
                                                <TableHeader>
                                                    <TableRow>
                                                        <TableHead>Date</TableHead>
                                                        <TableHead>Item</TableHead>
                                                        <TableHead>Quantity</TableHead>
                                                        <TableHead>Reason</TableHead>
                                                        <TableHead>Status</TableHead>
                                                        <TableHead>Actions</TableHead>
                                                    </TableRow>
                                                </TableHeader>
                                                <TableBody>
                                                    {consumptionRequests.map((request) => (
                                                        <TableRow key={request.id}>
                                                            <TableCell>{request.date}</TableCell>
                                                            <TableCell>{request.item.name}</TableCell>
                                                            <TableCell>{request.quantity}</TableCell>
                                                            <TableCell>{request.reason}</TableCell>
                                                            <TableCell className="flex items-center">
                                                                {getStatusIcon(request.status)}
                                                                <span className="ml-2 capitalize">{request.status}</span>
                                                            </TableCell>
                                                            <TableCell>
                                                                <Button variant="ghost" size="sm">
                                                                    View Details
                                                                </Button>
                                                            </TableCell>
                                                        </TableRow>
                                                    ))}
                                                </TableBody>
                                            </Table>
                                        </CardContent>
                                    </Card>
                                </TabsContent>

                                <TabsContent value="vouchers">
                                    <Card>
                                        <CardHeader>
                                            <CardTitle>Recent Vouchers</CardTitle>
                                        </CardHeader>
                                        <CardContent>
                                            <Table>
                                                <TableHeader>
                                                    <TableRow>
                                                        <TableHead>Voucher #</TableHead>
                                                        <TableHead>Date</TableHead>
                                                        <TableHead>Purpose</TableHead>
                                                        <TableHead>Amount</TableHead>
                                                        <TableHead>Status</TableHead>
                                                        <TableHead>Actions</TableHead>
                                                    </TableRow>
                                                </TableHeader>
                                                <TableBody>
                                                    {vouchers.map((voucher) => (
                                                        <TableRow key={voucher.id}>
                                                            <TableCell>{voucher.voucher_number}</TableCell>
                                                            <TableCell>{voucher.date}</TableCell>
                                                            <TableCell>{voucher.purpose}</TableCell>
                                                            <TableCell>${voucher.total_amount.toFixed(2)}</TableCell>
                                                            <TableCell className="flex items-center">
                                                                {getStatusIcon(voucher.status)}
                                                                <span className="ml-2 capitalize">{voucher.status}</span>
                                                            </TableCell>
                                                            <TableCell>
                                                                <Button variant="ghost" size="sm">
                                                                    View Details
                                                                </Button>
                                                            </TableCell>
                                                        </TableRow>
                                                    ))}
                                                </TableBody>
                                            </Table>
                                        </CardContent>
                                    </Card>
                                </TabsContent>
                            </Tabs>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
