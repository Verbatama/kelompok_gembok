<template>
    <div class="space-y-6">

        <!-- Warning -->
        <div v-if="!genieacsConfigured"
            class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-yellow-800">
            <i class="fa-solid fa-triangle-exclamation"></i>

            <span>
                GenieACS belum dikonfigurasi.
                Silakan konfigurasi terlebih dahulu di
                <a href="/admin/genieacs/configuration" class="font-medium underline">
                    halaman Configuration
                </a>.
            </span>
        </div>
        <template v-else>
            :

            <!-- Card -->

            <div class="rounded-xl border border-gray-100 bg-white shadow-sm">

                <!-- Header -->
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="flex items-center gap-2 font-semibold text-gray-700">
                            <i class="fa-solid fa-router text-gray-400"></i>
                            Device List
                        </span>

                        <span class="ml-2 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                            Total [{{ deviceStats.total }}]
                        </span>
                        <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-600">
                            Online [{{ deviceStats.online }}]
                        </span>
                        <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-500">
                            Offline [{{ deviceStats.offline }}]
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">

                        <!-- Bulk actions -->
                        <div v-if="hasSelection" class="flex items-center gap-2">
                            <button @click="showBulkAddTagModal"
                                class="rounded-lg bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-700"
                                title="Add Tag to Selected Devices">
                                <i class="fa-solid fa-tag mr-1"></i>Add Tag
                            </button>
                            <button @click="showBulkUntagModal"
                                class="rounded-lg bg-yellow-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-yellow-600"
                                title="Remove Tag from Selected Devices">
                                <i class="fa-solid fa-tags mr-1"></i>Untag
                            </button>
                            <button @click="showBulkDeleteModal"
                                class="rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700"
                                title="Delete Selected Devices">
                                <i class="fa-solid fa-trash mr-1"></i>Delete
                            </button>
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600">
                                {{ selectedCount }} selected
                            </span>
                        </div>

                        <button @click="toggleTagsColumn"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="fa-solid fa-tags mr-1"></i>
                            {{ tagsColumnVisible ? "Hide Tags" : "Show Tags" }}
                        </button>

                        <button @click="loadDevices(false)"
                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fa-solid fa-arrows-rotate mr-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <div class="p-5">

                    <!-- Tabs -->
                    <div class="mb-4 flex flex-wrap gap-1 border-b border-gray-100">
                        <button v-for="tab in [
                            { key: 'onu', label: 'ONU', icon: 'fa-wifi', count: deviceTypeCounts.onu },
                            { key: 'odp', label: 'ODP', icon: 'fa-cube', count: deviceTypeCounts.odp },
                            { key: 'odc', label: 'ODC', icon: 'fa-box', count: deviceTypeCounts.odc },
                            { key: 'olt', label: 'OLT', icon: 'fa-tower-broadcast', count: deviceTypeCounts.olt },
                            { key: 'server', label: 'Server', icon: 'fa-server', count: deviceTypeCounts.server },
                        ]" :key="tab.key" @click="filterByType(tab.key)"
                            class="flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition"
                            :class="currentFilterType === tab.key
                                ? 'border-blue-600 text-blue-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700'">
                            <i class="fa-solid" :class="tab.icon"></i>
                            {{ tab.label }}
                            <span class="rounded-full px-2 py-0.5 text-xs font-semibold"
                                :class="currentFilterType === tab.key ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'">
                                {{ tab.count }}
                            </span>
                        </button>
                    </div>

                    <!-- Search + pagination controls -->
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-1 min-w-[240px] max-w-sm items-center gap-2">
                            <div class="relative flex-1">
                                <i
                                    class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input v-model="searchTerm" type="text" :placeholder="searchPlaceholder"
                                    class="w-full rounded-lg border border-gray-200 py-2 pl-9 pr-3 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                            <button @click="clearSearch"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-500 hover:bg-gray-50">
                                <i class="fa-solid fa-xmark mr-1"></i>Clear
                            </button>
                        </div>

                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <label>Show:</label>
                            <select :value="itemsPerPage" @change="changeItemsPerPage($event.target.value)"
                                class="rounded-lg border border-gray-200 px-2 py-1.5 text-sm focus:border-blue-400 focus:outline-none">
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="0">All</option>
                            </select>
                            <span>per page</span>
                        </div>

                        <span class="text-sm text-gray-500">
                            {{ loadingInitial ? "Loading..." : (currentFilterType === 'onu' ? paginationRangeLabel :
                                `Showing ${infraItems.length} item${infraItems.length !== 1 ? 's' : ''}`) }}
                        </span>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="min-w-full whitespace-nowrap text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                <tr v-if="currentFilterType === 'onu'">
                                    <th class="p-3 text-left">
                                        <input type="checkbox" v-model="selectAllChecked">
                                    </th>
                                    <th class="p-3 text-left">SN</th>
                                    <th class="p-3 text-left">MAC</th>
                                    <th v-for="col in tableColumns" v-show="col.visible === undefined || col.visible"
                                        :key="col.key"
                                        class="cursor-pointer select-none p-3 text-left hover:text-gray-700"
                                        @click="sortTable(col.key)">
                                        {{ col.label }}
                                        <i class="fa-solid ml-1 text-[10px]" :class="currentSortColumn === col.key
                                            ? (currentSortDirection === 'asc' ? 'fa-chevron-up' : 'fa-chevron-down')
                                            : 'fa-sort text-gray-300'"></i>
                                    </th>
                                    <th class="p-3 text-left">Action</th>
                                </tr>

                                <tr v-else>
                                    <th class="p-3 text-left">Name</th>
                                    <th class="p-3 text-left">Type</th>
                                    <th class="p-3 text-left">Latitude</th>
                                    <th class="p-3 text-left">Longitude</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                <!-- Loading state -->
                                <tr v-if="loadingInitial">
                                    <td :colspan="currentFilterType === 'onu' ? 12 : 6"
                                        class="p-8 text-center text-gray-400">
                                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ loadingMessage }}
                                    </td>
                                </tr>

                                <!-- Error state -->
                                <tr v-else-if="errorMessage">
                                    <td :colspan="currentFilterType === 'onu' ? 12 : 6"
                                        class="p-8 text-center text-red-500">
                                        {{ errorMessage }}
                                    </td>
                                </tr>

                                <!-- ONU rows -->
                                <template v-else-if="currentFilterType === 'onu'">
                                    <tr v-if="paginatedOnuDevices.length === 0">
                                        <td colspan="12" class="p-8 text-center text-gray-400">No devices found</td>
                                    </tr>

                                    <tr v-for="device in paginatedOnuDevices" :key="device.serial_number"
                                        class="hover:bg-gray-50">
                                        <td class="p-3">
                                            <input type="checkbox" :checked="selectedDeviceIds.has(device.device_id)"
                                                @change="toggleDeviceSelected(device.device_id)">
                                        </td>

                                        <td class="p-3">
                                            <a :href="'/admin/genieacs/devices/' + encodeURIComponent(device.device_id)"
                                                class="font-medium text-blue-600 hover:underline">
                                                {{ device.serial_number }}
                                            </a>
                                        </td>

                                        <td class="p-3 text-gray-600">{{ device.mac_address }}</td>
                                        <td class="p-3 text-gray-600">{{ device.product_class || 'N/A' }}</td>

                                        <td class="p-3">
                                            <a v-if="extractIP(device.ip_tr069) !== 'N/A'"
                                                :href="'http://' + extractIP(device.ip_tr069)" target="_blank"
                                                rel="noopener noreferrer" class="text-blue-600 hover:underline">
                                                {{ extractIP(device.ip_tr069) }}
                                            </a>
                                            <span v-else class="text-gray-400">N/A</span>
                                        </td>

                                        <td class="p-3 text-gray-600">{{ device.wifi_ssid || '-' }}</td>
                                        <td class="p-3 text-gray-600">{{ device.pppoe_username || 'N/A' }}</td>

                                        <td class="p-3">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-medium text-white"
                                                :class="rxBadgeClass(device.rx_power)">
                                                {{ device.rx_power || 'N/A' }}<template v-if="device.rx_power">
                                                    dBm</template>
                                            </span>
                                        </td>

                                        <td class="p-3 text-gray-600">{{ device.temperature }}°C</td>

                                        <td class="p-3 text-center">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-medium"
                                                :class="device.connected_devices_count > 0 ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500'">
                                                {{ device.connected_devices_count || 0 }}
                                            </span>
                                        </td>

                                        <td class="p-3">
                                            <span v-if="device.status === 'online'"
                                                class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-600">
                                                ON [{{ device.ping || '-' }}ms]
                                            </span>
                                            <span v-else
                                                class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-500">
                                                OFF [-]
                                            </span>
                                        </td>

                                        <td class="p-3" v-show="tagsColumnVisible">
                                            <template v-if="device.tags && device.tags.length">
                                                <span v-for="tag in device.tags" :key="tag"
                                                    class="mr-1 rounded-full bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-600">
                                                    {{ tag }}
                                                </span>
                                            </template>
                                            <span v-else class="text-gray-300">-</span>
                                        </td>

                                        <td class="p-3">
                                            <button v-if="getMapInfo(device).inMap"
                                                @click="() => window.open(mapUrlFor(device), '_blank')"
                                                class="mr-1 rounded-lg border border-green-200 bg-green-50 px-2.5 py-1.5 text-green-600 hover:bg-green-100"
                                                title="View on Map">
                                                <i class="fa-solid fa-map"></i>
                                            </button>
                                            <button v-else @click="showNotInMapAlert(device.serial_number)"
                                                class="mr-1 rounded-lg border border-gray-200 px-2.5 py-1.5 text-gray-400 hover:bg-gray-50"
                                                title="Not Registered in Map">
                                                <i class="fa-solid fa-map"></i>
                                            </button>
                                            <button @click="summon(device.device_id)"
                                                class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-gray-500 hover:bg-gray-50 hover:text-blue-600"
                                                title="Summon Device">
                                                <i class="fa-solid fa-bolt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                                <!-- Infra rows (ODP/ODC/OLT/Server) -->
                                <template v-else>
                                    <tr v-if="infraItems.length === 0">
                                        <td colspan="6" class="p-8 text-center text-gray-400">No items found</td>
                                    </tr>

                                    <tr v-for="item in infraItems" :key="item.id" class="hover:bg-gray-50">
                                        <td class="p-3 text-gray-700">
                                            {{ currentFilterType === 'olt' ? `${item.name} (${item.server_name})` :
                                                item.name }}
                                        </td>
                                        <td class="p-3">
                                            <span
                                                class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600">
                                                {{ currentFilterType.toUpperCase() }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-gray-600">{{ formatCoord(item.latitude) }}</td>
                                        <td class="p-3 text-gray-600">{{ formatCoord(item.longitude) }}</td>
                                        <td class="p-3">
                                            <span v-if="item.status === 'online'"
                                                class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-600">
                                                Online
                                            </span>
                                            <span v-else-if="item.status === 'offline'"
                                                class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-500">
                                                Offline
                                            </span>
                                            <span v-else
                                                class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-500">
                                                Unknown
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <button
                                                @click="() => window.open('/map.php?focus_type=server&focus_id=' + item.id, '_blank')"
                                                class="rounded-lg border border-green-200 bg-green-50 px-2.5 py-1.5 text-green-600 hover:bg-green-100"
                                                title="View on Map">
                                                <i class="fa-solid fa-map"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="currentFilterType === 'onu' && itemsPerPage > 0 && totalPages > 1"
                        class="mt-4 flex items-center justify-center gap-1">
                        <button :disabled="currentPage <= 1" @click="goToPage(1)"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                            <i class="fa-solid fa-angles-left mr-1"></i>First
                        </button>
                        <button :disabled="currentPage <= 1" @click="goToPage(currentPage - 1)"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                            <i class="fa-solid fa-chevron-left mr-1"></i>Prev
                        </button>
                        <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-600">
                            Page {{ currentPage }} of {{ totalPages }}
                        </span>
                        <button :disabled="currentPage >= totalPages" @click="goToPage(currentPage + 1)"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                            Next<i class="fa-solid fa-chevron-right ml-1"></i>
                        </button>
                        <button :disabled="currentPage >= totalPages" @click="goToPage(totalPages)"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40">
                            Last<i class="fa-solid fa-angles-right ml-1"></i>
                        </button>
                    </div>

                </div>
            </div>
        </template>


        <!-- SUMMON MODAL -->
        <div v-if="confirmDevice"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 text-center shadow-xl">
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 text-yellow-500">
                    <i class="fa-solid fa-bolt text-xl"></i>
                </div>

                <h2 class="mt-4 text-lg font-semibold text-gray-800">Summon Device?</h2>
                <p class="mt-2 text-sm text-gray-500">{{ confirmDevice }}</p>

                <div class="mt-6 flex gap-3">
                    <button @click="confirmDevice = null"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button :disabled="summoning" @click="confirmSummon"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white disabled:opacity-60">

                        <span v-if="summoning">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            Mengirim...
                        </span>

                        <span v-else>
                            Summon
                        </span>

                    </button>
                </div>
            </div>
        </div>


        <!-- Not In Map Modal -->
        <div v-if="modals.notInMap"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

            <div class="w-[380px] rounded-2xl bg-white p-6 text-center shadow-xl">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <i class="fa-solid fa-map text-xl"></i>
                </div>

                <h2 class="mt-4 text-lg font-semibold text-gray-800">
                    Belum Terdaftar di Map
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Device
                    <span class="font-medium text-gray-700">
                        {{ notInMapSerial }}
                    </span>
                    belum didaftarkan ke peta. silahkan Tambahkan ke peta
                </p>

                <div class="mt-6 flex gap-3">

                    <button @click="modals.notInMap = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Tutup
                    </button>

                    <a href="/admin/genieacs/map"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700">

                        <i class="fa-solid fa-map-location-dot mr-2"></i>
                        Buka Map

                    </a>

                </div>
            </div>

        </div>

        <!-- Bulk Add Tag Modal -->

        <div v-if="modals.bulkAddTag"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Add Tag</h2>
                <p class="mt-1 text-sm text-gray-500">Menambahkan tag ke {{ selectedCount }} device terpilih.</p>
                <input v-model="newTagName" type="text" placeholder="Nama tag"
                    class="mt-4 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <div class="mt-6 flex gap-3">
                    <button @click="modals.bulkAddTag = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmBulkAddTag"
                        class="flex-1 rounded-lg bg-green-600 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                        Tambahkan
                    </button>
                </div>
            </div>
        </div>


        <!-- Bulk Untag Modal -->

        <div v-if="modals.bulkUntag"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Remove Tag</h2>
                <p class="mt-1 text-sm text-gray-500">Menghapus tag dari {{ selectedCount }} device terpilih.</p>
                <input v-model="removeTagName" type="text" placeholder="Nama tag yang dihapus"
                    class="mt-4 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <div class="mt-6 flex gap-3">
                    <button @click="modals.bulkUntag = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmBulkUntag"
                        class="flex-1 rounded-lg bg-yellow-500 py-2.5 text-sm font-medium text-white hover:bg-yellow-600">
                        Hapus Tag
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Delete Modal -->
        <div v-if="modals.bulkDelete"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 text-center shadow-xl">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
                    <i class="fa-solid fa-trash text-xl"></i>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-gray-800">Hapus {{ selectedCount }} Device?</h2>
                <p class="mt-2 text-sm text-gray-500">Tindakan ini tidak bisa dibatalkan.</p>
                <div class="mt-6 flex gap-3">
                    <button @click="modals.bulkDelete = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmBulkDelete"
                        class="flex-1 rounded-lg bg-red-600 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- Toast -->

        <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200"
            leave-from-class="opacity-100" leave-to-class="opacity-0">

            <div v-if="toast.show" class="fixed top-5 right-5 z-[9999] rounded-lg px-4 py-3 text-white shadow-lg"
                :class="{
                    'bg-green-600': toast.type === 'success',
                    'bg-red-600': toast.type === 'danger',
                    'bg-blue-600': toast.type === 'info'
                }">

                {{ toast.message }}

            </div>

        </Transition>
    </div>
</template>

<script setup>

import { ref, reactive, computed, watch, onMounted, onUnmounted, nextTick } from "vue";
const token = localStorage.getItem('jwt_token');


const API_BASE = "http://163.223.104.166:8881";

async function fetchAPI(endpoint, options = {}) {
    try {
        const response = await fetch(`${API_BASE}${endpoint}`, {
            headers: {
                "Content-Type": "application/json",
                'Authorization': `Bearer ${token}`
            },
            ...options,
        });

        const result = await response.json().catch(() => null);

        if (!response.ok) {
            return { success: false, message: result?.message || `HTTP ${response.status}` };
        }

        return result ?? { success: false, message: "Response tidak valid" };
    } catch (error) {
        console.error(error);
        return { success: false, message: error.message || "Terjadi kesalahan" };
    }
}



const genieacsConfigured = ref(false)

async function loadConfiguration() {
    const result = await fetchAPI('/api/get-genieacs-config.php')

    if (result?.success) {
        genieacsConfigured.value = result.configured
    }
}

function formatCoord(value) {
    const num = parseFloat(value);
    return isNaN(num) ? "-" : num.toFixed(6);
}


// State (dulu global di devices-state.js)


const allDevices = ref([]);
const allMapItems = ref([]);

const loadingInitial = ref(false);
const loadingMessage = ref("");
const errorMessage = ref("");

const currentFilterType = ref("onu"); // onu | odp | odc | olt | server
const searchTerm = ref("");

const currentSortColumn = ref(null);
const currentSortDirection = ref("asc");

const currentPage = ref(1);
const itemsPerPage = ref(50); // 0 = tampilkan semua
const totalDevices = ref(0);

const tagsColumnVisible = ref(true);

const mapStatusMap = ref({}); // { [serial_number]: { inMap, itemType, itemId } }

const selectedDeviceIds = ref(new Set());

const confirmDevice = ref(null)
const summoning = ref(false)
let autoRefreshTimer = null;
let savedScrollPosition = 0;

// Toast (ganti alert / showToast global)
const toastState = reactive({
    visible: false,
    message: "",
    type: "success", // success | danger | warning | error
});

// Modal visibility (ganti bootstrap.Modal getElementById)
const modals = reactive({
    summon: false,
    notInMap: false,
    bulkAddTag: false,
    bulkUntag: false,
    bulkDelete: false,
});

const notInMapSerial = ref("");
const newTagName = ref("");
const removeTagName = ref("");


// Helpers



function showLoading() {
    loadingInitial.value = true;
}

function hideLoading() {
    loadingInitial.value = false;
}

function extractIP(ipString) {
    if (!ipString || ipString === "N/A") return "N/A";
    const match = ipString.match(/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/);
    return match ? match[1] : "N/A";
}


// Table header definition (ganti generateTableHeader)


const onuColumns = [
    { key: "product_class", label: "Tipe", sortable: true },
    { key: "ip", label: "IP", sortable: true },
    { key: "ssid", label: "SSID", sortable: true },
    { key: "pppoe_username", label: "PPPoE", sortable: true },
    { key: "rx_power", label: "Rx", sortable: true },
    { key: "temperature", label: "Temp", sortable: true },
    { key: "connected_clients", label: "Client", sortable: true },
    { key: "status", label: "Status", sortable: true },
    { key: "tags", label: "Tags", sortable: true, visible: tagsColumnVisible },
];

const infraColumns = [
    { key: "name", label: "Name" },
    { key: "type", label: "Type" },
    { key: "latitude", label: "Latitude" },
    { key: "longitude", label: "Longitude" },
    { key: "status", label: "Status" },
];

const tableColumns = computed(() =>
    currentFilterType.value === "onu" ? onuColumns : infraColumns
);

const searchPlaceholder = computed(() =>
    currentFilterType.value === "onu"
        ? "Search by Serial Number, MAC Address, or Tags..."
        : "Search by Name..."
);

// Load devices (progressive chunk loading)


async function loadDevices(isAutoRefresh = false) {
    if (isAutoRefresh) {
        savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    }

    errorMessage.value = "";

    if (!isAutoRefresh) {
        showLoading();
        loadingMessage.value = "Loading devices...";
    }

    let loaded = [];
    let skip = 0;
    const chunkSize = 100;
    let hasMore = true;

    try {
        const [firstChunk, mapCountsResult, mapItemsResult] = await Promise.all([
            fetchAPI(`/api/get-devices.php?limit=${chunkSize}&skip=${skip}`),
            fetchAPI("/api/get-map-counts.php"),
            fetchAPI("/api/map-get-items.php"),
        ]);

        if (!firstChunk || !firstChunk.success) {
            throw new Error(firstChunk?.message || "Failed to load devices");
        }

        loaded = firstChunk.devices || [];
        hasMore = firstChunk.hasMore;
        skip += chunkSize;

        if (mapItemsResult && mapItemsResult.success) {
            allMapItems.value = mapItemsResult.items || [];
        }

        if (!isAutoRefresh) {
            loadingMessage.value = `Loading devices... (${loaded.length} loaded)`;
        }

        while (hasMore) {
            const chunk = await fetchAPI(`/api/get-devices.php?limit=${chunkSize}&skip=${skip}`);
            if (!chunk || !chunk.success) break;

            loaded = loaded.concat(chunk.devices || []);
            hasMore = chunk.hasMore;
            skip += chunkSize;

            if (!isAutoRefresh) {
                loadingMessage.value = `Loading devices... (${loaded.length} loaded)`;
            }
        }

        allDevices.value = loaded;

        if (!isAutoRefresh) {
            currentSortColumn.value = null;
            currentSortDirection.value = "asc";
        }

        if (mapCountsResult && mapCountsResult.success) {
            deviceTypeCounts.value = {
                onu: allDevices.value.length,
                odp: mapCountsResult.counts?.odp || 0,
                odc: mapCountsResult.counts?.odc || 0,
                olt: mapCountsResult.counts?.olt || 0,
                server: mapCountsResult.counts?.server || 0,
            };
        } else {
            deviceTypeCounts.value = {
                onu: allDevices.value.length,
                odp: 0,
                odc: 0,
                olt: 0,
                server: 0,
            };
        }

        if (isAutoRefresh) {
            await nextTick();
            if (savedScrollPosition > 0) {
                setTimeout(() => window.scrollTo(0, savedScrollPosition), 100);
            }
        }
    } catch (error) {
        console.error("Error loading devices:", error);
        errorMessage.value = "Failed to load devices: " + error.message;
        allDevices.value = [];
    } finally {
        if (!isAutoRefresh) hideLoading();
    }
}

const deviceTypeCounts = ref({ onu: 0, odp: 0, odc: 0, olt: 0, server: 0 });


// Filtering (ONU by search) — computed, ganti filterDevices()

const searchedOnuDevices = computed(() => {
    const term = searchTerm.value.toLowerCase().trim();
    if (term === "") return allDevices.value;

    return allDevices.value.filter((device) => {
        const serialNumber = (device.serial_number || "").toLowerCase();
        const macAddress = (device.mac_address || "").toLowerCase();

        let tagsMatch = false;
        if (Array.isArray(device.tags) && device.tags.length > 0) {
            tagsMatch = device.tags.some((tag) => tag.toLowerCase().includes(term));
        }

        return serialNumber.includes(term) || macAddress.includes(term) || tagsMatch;
    });
});


// Sorting — ganti applySorting() / sortTable()


function applySorting(devices, column, direction) {
    const sorted = [...devices];

    sorted.sort((a, b) => {
        let valueA, valueB;

        switch (column) {
            case "product_class":
                valueA = (a.product_class || "").toLowerCase();
                valueB = (b.product_class || "").toLowerCase();
                break;
            case "ip": {
                let ipA = extractIP(a.ip_tr069);
                let ipB = extractIP(b.ip_tr069);
                valueA = ipA === "N/A" ? "" : ipA.split(".").map((n) => n.padStart(3, "0")).join(".");
                valueB = ipB === "N/A" ? "" : ipB.split(".").map((n) => n.padStart(3, "0")).join(".");
                break;
            }
            case "ssid":
                valueA = (a.wifi_ssid || "").toLowerCase();
                valueB = (b.wifi_ssid || "").toLowerCase();
                break;
            case "pppoe_username":
                valueA = (a.pppoe_username || "").toLowerCase();
                valueB = (b.pppoe_username || "").toLowerCase();
                break;
            case "rx_power":
                valueA = parseFloat(a.rx_power) || -999;
                valueB = parseFloat(b.rx_power) || -999;
                break;
            case "temperature":
                valueA = parseFloat(a.temperature) || -999;
                valueB = parseFloat(b.temperature) || -999;
                break;
            case "connected_clients":
                valueA = parseInt(a.connected_devices_count) || 0;
                valueB = parseInt(b.connected_devices_count) || 0;
                break;
            case "status":
                valueA = a.status || "";
                valueB = b.status || "";
                break;
            case "tags":
                valueA = Array.isArray(a.tags) && a.tags.length > 0 ? a.tags.join(", ").toLowerCase() : "zzz";
                valueB = Array.isArray(b.tags) && b.tags.length > 0 ? b.tags.join(", ").toLowerCase() : "zzz";
                break;
            default:
                return 0;
        }

        let comparison = 0;
        if (valueA > valueB) comparison = 1;
        if (valueA < valueB) comparison = -1;

        return direction === "asc" ? comparison : -comparison;
    });

    return sorted;
}

function sortTable(column) {
    if (currentSortColumn.value === column) {
        currentSortDirection.value = currentSortDirection.value === "asc" ? "desc" : "asc";
    } else {
        currentSortColumn.value = column;
        currentSortDirection.value = "asc";
    }
    currentPage.value = 1;
}


// Infrastructure items (ganti renderMapItems)


const infraItems = computed(() => {
    const type = currentFilterType.value;
    if (type === "onu") return [];

    let items = [];

    if (type === "olt") {
        allMapItems.value.forEach((item) => {
            if (item.item_type === "server" && item.properties?.olt_link) {
                items.push({
                    id: item.id,
                    name: item.properties.olt_link || "OLT",
                    item_type: "olt",
                    latitude: item.latitude,
                    longitude: item.longitude,
                    status: item.status,
                    server_name: item.name,
                });
            }
        });
    } else {
        items = allMapItems.value.filter((item) => item.item_type === type);
    }

    const term = searchTerm.value.toLowerCase().trim();
    if (term === "") return items;

    return items.filter((item) => {
        const name = (item.name || "").toLowerCase();
        const serverName = (item.server_name || "").toLowerCase();
        return name.includes(term) || serverName.includes(term);
    });
});


// Devices to render: filtered + sorted (before pagination)


const processedOnuDevices = computed(() => {
    let devices = searchedOnuDevices.value;
    if (currentSortColumn.value) {
        devices = applySorting(devices, currentSortColumn.value, currentSortDirection.value);
    }
    return devices;
});


// Pagination — ganti updatePaginationUI / goToPage / changeItemsPerPage


const totalPages = computed(() => {
    if (itemsPerPage.value === 0) return 1;
    return Math.max(1, Math.ceil(processedOnuDevices.value.length / itemsPerPage.value));
});

const paginatedOnuDevices = computed(() => {
    if (currentFilterType.value !== "onu") return [];
    if (itemsPerPage.value === 0) return processedOnuDevices.value;

    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return processedOnuDevices.value.slice(start, end);
});

const paginationRangeLabel = computed(() => {
    const total = processedOnuDevices.value.length;
    if (itemsPerPage.value > 0 && total > itemsPerPage.value) {
        const start = (currentPage.value - 1) * itemsPerPage.value + 1;
        const end = Math.min(currentPage.value * itemsPerPage.value, total);
        return `Showing ${start}-${end} of ${total} item${total !== 1 ? "s" : ""}`;
    }
    return `Showing ${total} item${total !== 1 ? "s" : ""}`;
});

function goToPage(page) {
    if (page < 1 || page > totalPages.value) return;
    if (page === currentPage.value) return;
    currentPage.value = page;
}

function changeItemsPerPage(value) {
    itemsPerPage.value = parseInt(value);
    currentPage.value = 1;
}


// Device stats (ganti updateDeviceStats)


const deviceStats = computed(() => {
    const devices = currentFilterType.value === "onu" ? allDevices.value : [];
    const total = devices.length;
    const online = devices.filter((d) => d.status === "online").length;
    return { total, online, offline: total - online };
});

const showStats = computed(() => currentFilterType.value === "onu");


// Map status batch fetch (ganti bagian batch di renderDevices)


watch(paginatedOnuDevices, async (devices) => {
    if (!devices.length) return;

    const serialNumbers = devices.map((d) => d.serial_number);

    try {
        const result = await fetchAPI("/api/get-onu-location-batch.php", {
            method: "POST",
            body: JSON.stringify({ serial_numbers: serialNumbers }),
        });

        const next = {};

        if (result?.success && result.locations) {
            Object.keys(result.locations).forEach((serial) => {
                const location = result.locations[serial];
                next[serial] = {
                    inMap: location.found || false,
                    itemType: location.item_type || "onu",
                    itemId: location.onu?.id || location.server?.id || null,
                };
            });
        } else {
            devices.forEach((d) => {
                next[d.serial_number] = { inMap: false, itemType: "onu", itemId: null };
            });
        }

        mapStatusMap.value = next;
    } catch (error) {
        console.error("Batch map status fetch failed:", error);
        const fallback = {};
        devices.forEach((d) => {
            fallback[d.serial_number] = { inMap: false, itemType: "onu", itemId: null };
        });
        mapStatusMap.value = fallback;
    }
}, { immediate: true });

function getMapInfo(device) {
    return mapStatusMap.value[device.serial_number] || { inMap: false, itemType: "onu", itemId: null };
}

function mapUrlFor(device) {
    const info = getMapInfo(device);
    if (info.itemType === "mikrotik") {
        return `/map.php?focus_type=server&focus_id=${info.itemId}`;
    }
    return `/map.php?focus_type=onu&focus_serial=${encodeURIComponent(device.serial_number)}`;
}

function rxBadgeClass(rxPowerRaw) {
    const rxPower = parseFloat(rxPowerRaw);
    if (isNaN(rxPower) || rxPower === -999) return "bg-secondary";
    if (rxPower > -20.0) return "bg-success";
    if (rxPower >= -23.0) return "bg-warning";
    return "bg-danger";
}


// Filter by type (tab click) — ganti filterByType()

function filterByType(type) {
    currentFilterType.value = type;
    searchTerm.value = "";
    currentSortColumn.value = null;
    currentSortDirection.value = "asc";
    currentPage.value = 1;
}

function clearSearch() {
    searchTerm.value = "";
    currentPage.value = 1;
}

watch(searchTerm, () => {
    currentPage.value = 1;
});


// Tags column toggle


function toggleTagsColumn() {
    tagsColumnVisible.value = !tagsColumnVisible.value;
}


// Summon device (single)


function summon(id) {
    confirmDevice.value = id;
}
async function confirmSummon() {
    if (!confirmDevice.value) return;

    summoning.value = true;

    try {
        const result = await fetchAPI(
            "/api/summon-device.php",
            {
                method: "POST",
                body: JSON.stringify({
                    device_id: confirmDevice.value
                })
            }
        );
        console.log(result);
        if (result.success) {
            showToast("Device berhasil di-summon", "success");
            confirmDevice.value = null;
        } else {
            showToast(result.message || "Gagal summon device", "danger");
        }

    } finally {
        summoning.value = false;
    }
}
const toast = ref({
    show: false,
    type: "success",
    message: ""
});

function showToast(message, type = "success") {
    toast.value = {
        show: true,
        type,
        message
    };

    setTimeout(() => {
        toast.value.show = false;
    }, 3000);
}

function showNotInMapAlert(serialNumber) {
    notInMapSerial.value = serialNumber;
    modals.notInMap = true;
}


// Bulk selection


const selectAllChecked = computed({
    get() {
        const ids = paginatedOnuDevices.value.map((d) => d.device_id);
        return ids.length > 0 && ids.every((id) => selectedDeviceIds.value.has(id));
    },
    set(value) {
        const ids = paginatedOnuDevices.value.map((d) => d.device_id);
        if (value) {
            ids.forEach((id) => selectedDeviceIds.value.add(id));
        } else {
            ids.forEach((id) => selectedDeviceIds.value.delete(id));
        }
        // trigger reactivity for Set
        selectedDeviceIds.value = new Set(selectedDeviceIds.value);
    },
});

function toggleDeviceSelected(deviceId) {
    if (selectedDeviceIds.value.has(deviceId)) {
        selectedDeviceIds.value.delete(deviceId);
    } else {
        selectedDeviceIds.value.add(deviceId);
    }
    selectedDeviceIds.value = new Set(selectedDeviceIds.value);
}

const selectedCount = computed(() => selectedDeviceIds.value.size);
const hasSelection = computed(() => selectedCount.value > 0);

function getSelectedDeviceIds() {
    return Array.from(selectedDeviceIds.value);
}

function clearSelection() {
    selectedDeviceIds.value = new Set();
}


// Bulk Add Tag


function showBulkAddTagModal() {
    newTagName.value = "";
    modals.bulkAddTag = true;
}

async function confirmBulkAddTag() {
    const selectedIds = getSelectedDeviceIds();
    const tagName = newTagName.value.trim();

    if (!tagName) {
        showToast("Please enter a tag name", "warning");
        return;
    }

    modals.bulkAddTag = false;
    showLoading();

    const result = await fetchAPI("/api/bulk-tag.php", {
        method: "POST",
        body: JSON.stringify({ action: "add", device_ids: selectedIds, tag: tagName }),
    });

    hideLoading();

    if (result?.debug) console.table(result.debug);

    if (result?.success) {
        showToast(`Tag "${tagName}" added to ${result.success_count || selectedIds.length} device(s)`, "success");

        if (result.fail_count > 0) {
            console.warn("Some devices failed:", result.errors, result.debug);
            showToast(`Warning: ${result.fail_count} device(s) failed`, "warning");
        }

        await loadDevices();
        clearSelection();
    } else {
        console.error("Add tag failed:", result);
        showToast(result?.message || "Failed to add tags", "error");
    }
}


// Bulk Untag


function showBulkUntagModal() {
    removeTagName.value = "";
    modals.bulkUntag = true;
}

async function confirmBulkUntag() {
    const selectedIds = getSelectedDeviceIds();
    const tagName = removeTagName.value.trim();

    if (!tagName) {
        showToast("Please enter a tag name to remove", "warning");
        return;
    }

    modals.bulkUntag = false;
    showLoading();

    const result = await fetchAPI("/api/bulk-tag.php", {
        method: "POST",
        body: JSON.stringify({ action: "remove", device_ids: selectedIds, tag: tagName }),
    });

    hideLoading();

    if (result?.debug) console.table(result.debug);

    if (result?.success) {
        showToast(`Tag "${tagName}" removed from ${result.success_count || selectedIds.length} device(s)`, "success");

        if (result.fail_count > 0) {
            console.warn("Some devices failed:", result.errors, result.debug);
            showToast(`Warning: ${result.fail_count} device(s) failed`, "warning");
        }

        await loadDevices();
        clearSelection();
    } else {
        console.error("Untag failed:", result);
        showToast(result?.message || "Failed to remove tags", "error");
    }
}

// Bulk Delete


function showBulkDeleteModal() {
    modals.bulkDelete = true;
}

async function confirmBulkDelete() {
    const selectedIds = getSelectedDeviceIds();

    modals.bulkDelete = false;
    showLoading();

    const result = await fetchAPI("/api/bulk-delete-devices.php", {
        method: "POST",
        body: JSON.stringify({ device_ids: selectedIds }),
    });

    hideLoading();

    if (result?.success) {
        showToast(`${selectedIds.length} device(s) deleted successfully`, "success");
        await loadDevices();
        clearSelection();
    } else {
        showToast(result?.message || "Failed to delete devices", "error");
    }
}

// Keyboard shortcuts (Left/Right for pagination)

function handleKeydown(e) {
    const tag = e.target.tagName;
    if (tag === "INPUT" || tag === "TEXTAREA" || tag === "SELECT") return;

    if (e.key === "ArrowLeft" && currentPage.value > 1) {
        goToPage(currentPage.value - 1);
    } else if (e.key === "ArrowRight" && currentPage.value < totalPages.value) {
        goToPage(currentPage.value + 1);
    }
}


// Lifecycle: auto-refresh + visibility handling

function startAutoRefresh() {
    if (!autoRefreshTimer) {
        autoRefreshTimer = setInterval(() => loadDevices(true), 60000);
    }
}

function stopAutoRefresh() {
    if (autoRefreshTimer) {
        clearInterval(autoRefreshTimer);
        autoRefreshTimer = null;
    }
}

function handleVisibilityChange() {
    if (document.hidden) {
        stopAutoRefresh();
    } else {
        startAutoRefresh();
    }
}

const refreshDevices = () => {
    if (!document.hidden) {
        loadDevices(false);
    }
};

let interval = null;
onMounted(async () => {

    await loadConfiguration();

    refreshDevices();

    interval = setInterval(refreshDevices, 30000);

    document.addEventListener(
        "visibilitychange",
        refreshDevices
    );

});


onUnmounted(() => {

    clearInterval(interval);

    document.removeEventListener(
        "visibilitychange",
        refreshDevices
    );

});


</script>