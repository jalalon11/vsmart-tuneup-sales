<!-- View Inventory Modal -->
<div id="viewModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity opacity-0" 
            aria-hidden="true"
            id="viewModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="viewModalContent">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="mb-8 border-b dark:border-gray-700 pb-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="modal-title">Item Details</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View detailed information about this inventory item.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Basic Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Basic Information
                                </h2>
                                <dl class="grid grid-cols-1 gap-6">
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-name"></dd>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Brand</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-brand"></dd>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Model</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-model"></dd>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Serial Number</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-serial"></dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Stock and Price Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Stock and Price Information
                                </h2>
                                <dl class="grid grid-cols-1 gap-6">
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stock</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-quantity"></dd>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Price</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-unit-price"></dd>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Selling Price</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900 dark:text-white" id="view-item-selling-price"></dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                    Description
                                </h2>
                                <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow-sm">
                                    <p class="text-gray-900 dark:text-white" id="view-item-description"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeViewModal()"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm transition duration-150">
                    Close
                </button>
            </div>
        </div>
    </div>
</div> 