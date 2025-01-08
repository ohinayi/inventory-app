import React, { useState } from 'react';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
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
  Package2,
  Receipt,
  Clock,
  CheckCircle2,
  XCircle,
  Plus
} from 'lucide-react';

const Dashboard = () => {
  const [activeTab, setActiveTab] = useState('requests');

  // Mock data - replace with actual API calls
  const consumptionRequests = [
    {
      id: 1,
      date: '2025-01-08',
      status: 'pending',
      items: [
        { name: 'Printer Paper', quantity: 2 },
        { name: 'Ink Cartridge', quantity: 1 }
      ]
    },
    {
      id: 2,
      date: '2025-01-07',
      status: 'approved',
      items: [
        { name: 'Stapler', quantity: 1 }
      ]
    }
  ];

  const vouchers = [
    {
      id: 1,
      date: '2025-01-08',
      amount: 150.00,
      status: 'pending',
      description: 'Office supplies reimbursement'
    },
    {
      id: 2,
      date: '2025-01-06',
      amount: 75.50,
      status: 'processed',
      description: 'Transportation expense'
    }
  ];

  const getStatusIcon = (status) => {
    switch (status) {
      case 'pending':
        return <Clock className="h-4 w-4 text-yellow-500" />;
      case 'approved':
      case 'processed':
        return <CheckCircle2 className="h-4 w-4 text-green-500" />;
      case 'rejected':
        return <XCircle className="h-4 w-4 text-red-500" />;
      default:
        return null;
    }
  };

  return (
    <div className="p-6 max-w-7xl mx-auto space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold">Employee Dashboard</h1>
        <div className="space-x-2">
          <Button>
            <Plus className="h-4 w-4 mr-2" />
            New Request
          </Button>
          <Button variant="outline">
            <Plus className="h-4 w-4 mr-2" />
            New Voucher
          </Button>
        </div>
      </div>

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
                    <TableHead>Items</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {consumptionRequests.map((request) => (
                    <TableRow key={request.id}>
                      <TableCell>{request.date}</TableCell>
                      <TableCell>
                        <ul className="list-disc list-inside">
                          {request.items.map((item, index) => (
                            <li key={index}>
                              {item.name} x{item.quantity}
                            </li>
                          ))}
                        </ul>
                      </TableCell>
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
                    <TableHead>Date</TableHead>
                    <TableHead>Description</TableHead>
                    <TableHead>Amount</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {vouchers.map((voucher) => (
                    <TableRow key={voucher.id}>
                      <TableCell>{voucher.date}</TableCell>
                      <TableCell>{voucher.description}</TableCell>
                      <TableCell>${voucher.amount.toFixed(2)}</TableCell>
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
  );
};

export default Dashboard;
