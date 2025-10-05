
<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="m-2 p-2 bg-slate-100 rounded">
                <div class="space-y-8 divide-y divide-gray-200 w-1/2 mt-10">
                    <form method="POST" action="{{ route('staff.orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="sm:col-span-6">
                            <label for="table_id" class="block text-sm font-medium text-gray-700"> Table </label>
                            <div class="mt-1">
                                <select id="table_id" name="table_id"
                                    class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    @foreach ($tables as $table)
                                        <option value="{{ $table->id }}" @selected($order->table_id == $table->id)>
                                            {{ $table->table_number}} ({{$table->capacity}} Guests)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-6 mt-4">
                            <label for="staff_id" class="block text-sm font-medium text-gray-700"> Waiter </label>
                            <div class="mt-1">
                                <select id="staff_id" name="staff_id" class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3">
                                    @foreach ($waiters as $waiter)
                                        <option value="{{ $waiter->id }}" @selected($order->staff_id == $waiter->id)>
                                            {{ $waiter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-6 mt-4">
                            <label for="status" class="block text-sm font-medium text-gray-700"> Status </label>
                            <div class="mt-1">
                                <select id="status" name="status" class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3">
                                    <option value="pending" @selected($order->status == 'pending')>Pending</option>
                                    <option value="paid" @selected($order->status == 'paid')>Paid</option>
                                    <option value="cancelled" @selected($order->status == 'cancelled')>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 p-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>