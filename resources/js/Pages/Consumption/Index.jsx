import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React, { useState } from 'react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

// import NewRequestModal from '../components/NewRequest';
import NewRequestModal from '@/components/NewRequestModal';
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
    XCircle,
    Plus,
} from 'lucide-react';

export default function Index({auth, items}) {
    const [showNewRequestModal, setShowNewRequestModal] = useState(false);

    // Mock data
    const consumptionRequests = [
        {
            id: 1,
            date: '2025-01-08',
            status: 'pending',
            item: { name: 'Printer Paper' },
            quantity: 2,
            reason: 'Monthly supply',
            approved_by: null,
            approved_at: null
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
                    Consumption
                </h2>
            }
        >
            <Head title="Consumption" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 max-w-7xl mx-auto space-y-6">
                            <div className="flex justify-between items-center">
                                <h1 className="text-3xl font-bold">Consumption Requests</h1>
                                <Button onClick={() => setShowNewRequestModal(true)} className="text-white">
                                    <Plus className="h-4 w-4 mr-2" />
                                    New Request
                                </Button>
                            </div>

                            {showNewRequestModal && (
                                <NewRequestModal
                                    isShow={showNewRequestModal}
                                    onClose={() => setShowNewRequestModal(false)}
                                    items={items}
                                />
                            )}

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
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
