<!-- Edit Inventory Modal -->
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity opacity-0" 
            aria-hidden="true"
            id="editModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="editModalContent">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="mb-8 border-b dark:border-gray-700 pb-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="modal-title">Edit Item</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update the item details in your inventory.</p>
                        </div>

                        <form id="edit-form" method="POST" class="space-y-8">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Basic Information
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <label for="edit-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                                        <input type="text" name="name" id="edit-name" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Serial Number -->
                                    <div class="space-y-2">
                                        <label for="edit-serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Serial Number</label>
                                        <input type="text" name="serial_number" id="edit-serial_number"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Brand -->
                                    <div class="space-y-2">
                                        <label for="edit-brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                                        <input type="text" name="brand" id="edit-brand"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Model -->
                                    <div class="space-y-2">
                                        <label for="edit-model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                        <input type="text" name="model" id="edit-model"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Description -->
                                    <div class="md:col-span-2 space-y-2">
                                        <label for="edit-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                        <textarea name="description" id="edit-description" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock and Price Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Stock and Price Information
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Quantity -->
                                    <div class="space-y-2">
                                        <label for="edit-quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                        <input type="number" name="quantity" id="edit-quantity" min="0" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Reorder Point -->
                                    <div class="space-y-2">
                                        <label for="edit-reorder_point" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reorder Point</label>
                                        <input type="number" name="reorder_point" id="edit-reorder_point" min="0" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="space-y-2">
                                        <label for="edit-unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                            </div>
                                            <input type="number" name="unit_price" id="edit-unit_price" min="0" step="0.01" required
                                                class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <!-- Selling Price -->
                                    <div class="space-y-2">
                                        <label for="edit-selling_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selling Price</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                            </div>
                                            <input type="number" name="selling_price" id="edit-selling_price" min="0" step="0.01" required
                                                class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" form="edit-form"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                    Update Item
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div> 