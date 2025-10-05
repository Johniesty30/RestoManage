<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="m-2 p-2 bg-slate-100 rounded">
                <div class="space-y-8 divide-y divide-gray-200 w-1/2 mt-10">
                    {{-- DIUBAH DARI admin.orders.store MENJADI staff.orders.store --}}
                    <form method="POST" action="{{ route('staff.orders.store') }}" id="order-form">
                        @csrf
                        <div class="sm:col-span-6">
                            <label for="table_id" class="block text-sm font-medium text-gray-700"> Table </label>
                            <div class="mt-1">
                                <select id="table_id" name="table_id"
                                    class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3 text-base leading-normal transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('table_id') border-red-500 @enderror">
                                    @foreach ($tables as $table)
                                        <option value="{{ $table->id }}">{{ $table->table_number}} ({{$table->capacity}} Guests)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-6 mt-4">
                             <label for="staff_id" class="block text-sm font-medium text-gray-700"> Waiter </label>
                             <div class="mt-1">
                                <select id="staff_id" name="staff_id" class="block w-full appearance-none bg-white border border-gray-400 rounded-md py-2 px-3">
                                     @foreach ($waiters as $waiter)
                                         <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                                     @endforeach
                                </select>
                             </div>
                        </div>
                        
                        <div class="sm:col-span-6 mt-4">
                            <h3 class="text-lg font-medium">Menu Items</h3>
                            <div id="menu-items-container" class="mt-2">
                                <!-- Menu item rows will be added here -->
                            </div>
                            <button type="button" id="add-item-btn" class="mt-2 px-4 py-2 bg-green-500 text-white rounded">Add Menu Item</button>
                        </div>

                        <div class="mt-6 p-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('menu-items-container');
            const addItemBtn = document.getElementById('add-item-btn');
            const menuItems = @json($menuItems);
            let itemIndex = 0;

            addItemBtn.addEventListener('click', () => {
                const itemRow = document.createElement('div');
                itemRow.classList.add('flex', 'items-center', 'space-x-2', 'mb-2');
                
                const select = document.createElement('select');
                select.name = `items[${itemIndex}][menu_item_id]`;
                select.classList.add('form-select', 'w-1/2', 'rounded-md');
                menuItems.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name + ' - Rp ' + item.price;
                    select.appendChild(option);
                });

                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.name = `items[${itemIndex}][quantity]`;
                quantityInput.min = '1';
                quantityInput.value = '1';
                quantityInput.classList.add('form-input', 'w-1/4', 'rounded-md');
                
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Remove';
                removeBtn.type = 'button';
                removeBtn.classList.add('px-2', 'py-1', 'bg-red-500', 'text-white', 'rounded');
                removeBtn.addEventListener('click', () => {
                    itemRow.remove();
                });
                
                itemRow.appendChild(select);
                itemRow.appendChild(quantityInput);
                itemRow.appendChild(removeBtn);
                container.appendChild(itemRow);
                
                itemIndex++;
            });
        });
    </script>
</x-admin-layout>
