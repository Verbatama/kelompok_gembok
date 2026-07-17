<template>
    <div class="space-y-6">
        <div v-if="!loadConfiguration()"
            class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-yellow-800">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>
                GenieACS belum dikonfigurasi. Silakan konfigurasi terlebih dahulu di
                <a href="/configuration.php" class="font-medium underline">halaman Configuration</a>.
            </span>
        </div>
        <template v-else>

            <!-- Action buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="/admin/genieacs/devices"
                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-left mr-1"></i>Back to Devices
                </a>
                <button @click="summonDevice"
                    class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                    <i class="fa-solid fa-bolt mr-1"></i>Summon Device
                </button>
                <button @click="showAddTagModal"
                    class="rounded-lg bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-700">
                    <i class="fa-solid fa-tag mr-1"></i>Add Tag
                </button>
                <button @click="showRemoveTagModal"
                    class="rounded-lg bg-yellow-500 px-3 py-1.5 text-sm font-medium text-white hover:bg-yellow-600">
                    <i class="fa-solid fa-tags mr-1"></i>Remove Tag
                </button>
                <button @click="loadDeviceDetail(false)"
                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-arrows-rotate mr-1"></i>Refresh
                </button>
            </div>

            <!-- Card -->
            <div class="rounded-xl border border-gray-100 bg-white shadow-sm">

                <!-- Header -->
                <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 px-5 py-4">
                    <span class="flex items-center gap-2 font-semibold text-gray-700">
                        <i class="fa-solid fa-router text-gray-400"></i>
                        Device Details
                    </span>
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                        {{ device ? device.serial_number : "Loading..." }}
                    </span>
                    <span v-for="tag in deviceTags" :key="tag"
                        class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-600">
                        {{ tag }}
                    </span>
                </div>

                <div class="p-5">

                    <!-- Loading state -->
                    <div v-if="loadingInitial" class="py-16 text-center text-gray-400">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ loadingMessageText }}
                    </div>

                    <!-- Error state -->
                    <div v-else-if="errorMessage" class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600">
                        {{ errorMessage }}
                    </div>

                    <template v-else-if="device">

                        <!-- Tabs -->
                        <div class="mb-4 flex flex-wrap gap-1 border-b border-gray-100">
                            <button v-for="tab in [
                                { key: 'overview', label: 'Overview', icon: 'fa-circle-info' },
                                { key: 'topology', label: 'Topology Location', icon: 'fa-diagram-project' },
                                { key: 'wan', label: 'WAN Connections', icon: 'fa-globe', count: wanList.length },
                                { key: 'dhcp', label: 'DHCP Server', icon: 'fa-router' },
                                { key: 'devices', label: 'Connected Devices', icon: 'fa-network-wired', count: connectedDevicesList.length },
                            ]" :key="tab.key" @click="activeTab = tab.key"
                                class="flex items-center gap-2 border-b-2 px-4 py-2.5 text-sm font-medium transition"
                                :class="activeTab === tab.key
                                    ? 'border-blue-600 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700'">
                                <i class="fa-solid" :class="tab.icon"></i>
                                {{ tab.label }}
                                <span v-if="tab.count !== undefined"
                                    class="rounded-full px-2 py-0.5 text-xs font-semibold"
                                    :class="activeTab === tab.key ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'">
                                    {{ tab.count }}
                                </span>
                            </button>
                        </div>

                        <!-- ========================= -->
                        <!-- Tab: Overview -->
                        <!-- ========================= -->
                        <div v-if="activeTab === 'overview'" class="grid gap-5 md:grid-cols-2">

                            <!-- Basic Info -->
                            <div>
                                <h4 class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-circle-info text-gray-400"></i>Basic Information
                                </h4>
                                <table class="w-full overflow-hidden rounded-lg border border-gray-100 text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="w-2/5 bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                Device ID</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.device_id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Serial
                                                Number</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.serial_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">MAC Address
                                            </th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.mac_address }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Last Inform
                                            </th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.last_inform }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Status</th>
                                            <td class="p-2.5">
                                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                                    :class="overviewBasicInfo.status === 'online' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'">
                                                    {{ overviewBasicInfo.status }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                Manufacturer</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.manufacturer }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Product
                                                Class</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.product_class }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">OUI</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewBasicInfo.oui }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Hardware / Software + Optical -->
                            <div>
                                <h4 class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-microchip text-gray-400"></i>Hardware/Software
                                </h4>
                                <table class="w-full overflow-hidden rounded-lg border border-gray-100 text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="w-2/5 bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                Hardware Version</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewHardware.hardware_version }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Software
                                                Version</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewHardware.software_version }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Uptime</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewHardware.uptime }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h4 class="mb-2 mt-4 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-tower-broadcast text-gray-400"></i>Optical Information
                                </h4>
                                <table class="w-full overflow-hidden rounded-lg border border-gray-100 text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="w-2/5 bg-gray-50 p-2.5 text-left font-medium text-gray-500">Rx
                                                Power</th>
                                            <td class="p-2.5 text-gray-700">{{ overviewHardware.rx_power }} dBm</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Temperature
                                            </th>
                                            <td class="p-2.5 text-gray-700">{{ overviewHardware.temperature }}°C</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Network Information -->
                            <div class="md:col-span-2">
                                <h4 class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-ethernet text-gray-400"></i>Network Information
                                </h4>
                                <table class="w-full overflow-hidden rounded-lg border border-gray-100 text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="w-1/5 bg-gray-50 p-2.5 text-left font-medium text-gray-500">IP
                                                TR069</th>
                                            <td class="p-2.5">
                                                <a v-if="ipLinkData(extractIP(device.ip_tr069)).clickable"
                                                    :href="ipLinkData(extractIP(device.ip_tr069)).href" target="_blank"
                                                    class="text-blue-600 hover:underline">
                                                    {{ extractIP(device.ip_tr069) }}
                                                </a>
                                                <span v-else class="text-gray-700">{{ extractIP(device.ip_tr069)
                                                }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">WiFi SSID
                                            </th>
                                            <td class="p-2.5 text-gray-700">
                                                {{ device.wifi_ssid }}
                                                <button @click="openEditWiFiModal"
                                                    class="ml-2 rounded-lg bg-yellow-500 px-2.5 py-1 text-xs font-medium text-white hover:bg-yellow-600">
                                                    <i class="fa-solid fa-pen mr-1"></i>Edit WiFi
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">WiFi
                                                Password</th>
                                            <td class="p-2.5 text-gray-700">
                                                <span>{{ passwordVisible.wifi ? device.wifi_password : "********"
                                                }}</span>
                                                <button @click="togglePassword"
                                                    class="ml-2 text-gray-400 hover:text-gray-600">
                                                    <i class="fa-solid"
                                                        :class="passwordVisible.wifi ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Full TR069
                                                URL</th>
                                            <td class="p-2.5 text-xs text-gray-500">{{ device.ip_tr069 }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h4 class="mb-2 mt-4 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-lock text-gray-400"></i>Admin Web Access
                                    <button v-if="needsAdminCredentials" @click="summonForAdminCredentials"
                                        :disabled="fetchingCredentials"
                                        class="rounded-lg bg-yellow-500 px-2.5 py-1 text-xs font-medium text-white hover:bg-yellow-600 disabled:cursor-not-allowed disabled:opacity-50">
                                        <i class="fa-solid fa-bolt mr-1"></i>Get Credentials
                                    </button>
                                </h4>

                                <div v-if="credentialsStatusVisible"
                                    class="mb-2 flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm text-blue-700">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>{{ credentialsStatusText }}</span>
                                </div>

                                <table class="w-full overflow-hidden rounded-lg border border-gray-100 text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="w-1/5 bg-gray-50 p-2.5 text-left font-medium text-gray-500">Super
                                                Admin User</th>
                                            <td class="p-2.5">
                                                <code class="text-gray-700">{{ device.admin_user || "N/A" }}</code>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Super Admin
                                                Password</th>
                                            <td class="p-2.5 text-gray-700">
                                                <code>{{ passwordVisible.admin ? (device.admin_password || "N/A") : "********" }}</code>
                                                <button @click="toggleAdminPassword"
                                                    class="ml-2 text-gray-400 hover:text-gray-600">
                                                    <i class="fa-solid"
                                                        :class="passwordVisible.admin ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Telecom
                                                Password</th>
                                            <td class="p-2.5 text-gray-700">
                                                <code>{{ passwordVisible.telecom ? (device.telecom_password || "N/A") : "********" }}</code>
                                                <button @click="toggleTelecomPassword"
                                                    class="ml-2 text-gray-400 hover:text-gray-600">
                                                    <i class="fa-solid"
                                                        :class="passwordVisible.telecom ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div v-if="needsAdminCredentials"
                                    class="mt-2 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                                    <i class="fa-solid fa-circle-info mr-1"></i>
                                    <strong>Admin credentials belum tersedia.</strong><br>
                                    Klik tombol <strong>"Get Credentials"</strong> untuk mengambil username dan password
                                    dari device.<br>
                                    ⏱️ <em>Proses membutuhkan waktu ~20 detik (otomatis summon 2x untuk device
                                        baru)</em>
                                </div>
                            </div>
                        </div>

                        <div v-else-if="activeTab === 'topology'">

                            <div v-if="topologyData.state === 'unavailable'"
                                class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                                <i class="fa-solid fa-circle-info mr-1"></i>Unable to load topology location
                            </div>

                            <div v-else-if="topologyData.state === 'not_in_map'"
                                class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                                <i class="fa-solid fa-circle-info mr-1"></i>
                                <strong>Topology Location:</strong> This device has not been added to the network map
                                yet.
                                <a href="/map.php" class="font-medium underline">Add to map</a>
                            </div>

                            <template v-else>
                                <!-- Hierarchy path -->
                                <div v-if="topologyData.hierarchyPath.length" class="mb-4">
                                    <h4 class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fa-solid fa-diagram-project text-gray-400"></i>Hierarchy Path
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-2 rounded-lg bg-gray-50 p-3">
                                        <template v-for="(node, idx) in topologyData.hierarchyPath" :key="idx">
                                            <span
                                                class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-gray-600 shadow-sm">
                                                {{ node.label }}
                                            </span>
                                            <i v-if="idx < topologyData.hierarchyPath.length - 1"
                                                class="fa-solid fa-arrow-right text-xs text-gray-300"></i>
                                        </template>
                                    </div>
                                </div>

                                <table class="w-full overflow-hidden rounded-lg border border-gray-100 text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr v-if="topologyData.odp">
                                            <th class="w-1/4 bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                <i class="fa-solid fa-cube mr-1 text-gray-400"></i>ODP
                                            </th>
                                            <td class="p-2.5">
                                                <strong class="text-gray-700">{{ topologyData.odp.name }}</strong>
                                                <a :href="topologyData.odp.mapUrl" target="_blank"
                                                    class="ml-2 rounded-lg border border-blue-200 px-2.5 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50">
                                                    <i class="fa-solid fa-map mr-1"></i>View on Map
                                                </a>
                                            </td>
                                        </tr>
                                        <tr v-if="topologyData.port">
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                <i class="fa-solid fa-plug mr-1 text-gray-400"></i>Port Number
                                            </th>
                                            <td class="p-2.5">
                                                <span
                                                    class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-600">Port
                                                    {{
                                                        topologyData.port }}</span>
                                            </td>
                                        </tr>
                                        <tr v-if="topologyData.odc">
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                <i class="fa-solid fa-building mr-1 text-gray-400"></i>ODC
                                            </th>
                                            <td class="p-2.5">
                                                <strong class="text-gray-700">{{ topologyData.odc.name }}</strong>
                                                <a :href="topologyData.odc.mapUrl" target="_blank"
                                                    class="ml-2 rounded-lg border border-yellow-200 px-2.5 py-1 text-xs font-medium text-yellow-600 hover:bg-yellow-50">
                                                    <i class="fa-solid fa-map mr-1"></i>View on Map
                                                </a>
                                            </td>
                                        </tr>
                                        <tr v-if="topologyData.olt">
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                <i class="fa-solid fa-server mr-1 text-gray-400"></i>OLT
                                            </th>
                                            <td class="p-2.5"><strong class="text-gray-700">{{ topologyData.olt
                                            }}</strong></td>
                                        </tr>
                                        <tr v-if="topologyData.coordinates">
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i>Coordinates
                                            </th>
                                            <td class="p-2.5">
                                                <code class="text-gray-700">{{ topologyData.coordinates.lat }}, {{ topologyData.coordinates.lng
                                                }}</code>
                                                <a :href="topologyData.coordinates.googleMapsUrl" target="_blank"
                                                    class="ml-2 rounded-lg border border-green-200 px-2.5 py-1 text-xs font-medium text-green-600 hover:bg-green-50">
                                                    <i class="fa-solid fa-earth-americas mr-1"></i>Google Maps
                                                </a>
                                                <a :href="topologyData.coordinates.networkMapUrl" target="_blank"
                                                    class="ml-1 rounded-lg border border-blue-200 px-2.5 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50">
                                                    <i class="fa-solid fa-map mr-1"></i>Network Map
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>

                   
                        <!-- Tab: WAN Connections -->
               
                        <div v-else-if="activeTab === 'wan'">
                            <div class="mb-3 flex items-center justify-between">
                                <h4 class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-globe text-gray-400"></i>WAN Connection Details
                                </h4>
                                <button @click="openAddWANModal"
                                    class="rounded-lg bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-700">
                                    <i class="fa-solid fa-plus mr-1"></i>Add WAN Connection
                                </button>
                            </div>

                            <div v-if="wanList.length === 0"
                                class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                                <i class="fa-solid fa-circle-info mr-1"></i>No WAN connections configured on this
                                device.
                            </div>

                            <div v-for="wan in wanList" :key="wan.name" class="mb-4 rounded-xl border border-gray-100">
                                <div
                                    class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 bg-gray-50 px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <strong class="text-gray-700">{{ wan.name }}</strong>
                                        <span
                                            class="rounded-full bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-600">{{
                                                wan.type }}</span>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                            :class="wan.statusOnline ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'">
                                            {{ wan.statusOnline ? "Connected" : "Disconnected" }}
                                        </span>
                                        <span v-if="wan.isBridge"
                                            class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">Bridge
                                            Mode</span>
                                        <span v-if="wan.isTR069"
                                            class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-500">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>TR069
                                        </span>
                                    </div>
                                    <div class="flex gap-2">
                                        <button @click="openEditWANModal(wan)"
                                            class="rounded-lg bg-yellow-500 px-2.5 py-1.5 text-xs font-medium text-white hover:bg-yellow-600">
                                            <i class="fa-solid fa-pen mr-1"></i>Edit
                                        </button>
                                        <button @click="openDeleteWANModal(wan)"
                                            class="rounded-lg bg-red-600 px-2.5 py-1.5 text-xs font-medium text-white hover:bg-red-700">
                                            <i class="fa-solid fa-trash mr-1"></i>Delete
                                        </button>
                                    </div>
                                </div>

                                <table class="w-full text-sm">
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="w-1/4 bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                Connection Type</th>
                                            <td class="p-2.5 text-gray-700">{{ wan.connection_type }}</td>
                                        </tr>
                                        <tr v-if="wan.vlanId">
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">VLAN ID
                                            </th>
                                            <td class="p-2.5 text-gray-700">{{ wan.vlanId }}</td>
                                        </tr>
                                        <tr v-if="wan.binding && wan.binding !== 'N/A'">
                                            <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Bound to
                                            </th>
                                            <td class="p-2.5">
                                                <span
                                                    class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600">{{
                                                        wan.binding }}</span>
                                            </td>
                                        </tr>
                                        <template v-if="!wan.isBridge">
                                            <tr
                                                v-if="wan.externalIpLink.display !== 'N/A' && wan.externalIpLink.display !== '0.0.0.0'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                    External IP</th>
                                                <td class="p-2.5">
                                                    <a v-if="wan.externalIpLink.clickable"
                                                        :href="wan.externalIpLink.href" target="_blank"
                                                        class="text-blue-600 hover:underline">
                                                        {{ wan.externalIpLink.display }}
                                                    </a>
                                                    <span v-else class="text-gray-700">{{ wan.externalIpLink.display
                                                    }}</span>
                                                </td>
                                            </tr>
                                            <tr
                                                v-if="wan.gatewayLink.display !== 'N/A' && wan.gatewayLink.display !== '0.0.0.0'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Gateway
                                                </th>
                                                <td class="p-2.5">
                                                    <a v-if="wan.gatewayLink.clickable" :href="wan.gatewayLink.href"
                                                        target="_blank" class="text-blue-600 hover:underline">
                                                        {{ wan.gatewayLink.display }}
                                                    </a>
                                                    <span v-else class="text-gray-700">{{ wan.gatewayLink.display
                                                    }}</span>
                                                </td>
                                            </tr>
                                            <tr v-if="wan.subnet_mask && wan.subnet_mask !== 'N/A'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Subnet
                                                    Mask</th>
                                                <td class="p-2.5 text-gray-700">{{ wan.subnet_mask }}</td>
                                            </tr>
                                            <tr v-if="wan.dns_servers && wan.dns_servers !== 'N/A'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">DNS
                                                    Servers</th>
                                                <td class="p-2.5 text-gray-700">{{ wan.dns_servers }}</td>
                                            </tr>
                                            <tr
                                                v-if="wan.mac_address && wan.mac_address !== 'N/A' && wan.mac_address !== '00:00:00:00:00:00'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">MAC
                                                    Address</th>
                                                <td class="p-2.5 text-gray-700">{{ wan.mac_address }}</td>
                                            </tr>
                                            <tr v-if="wan.type === 'PPPoE' && wan.username && wan.username !== 'N/A'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                    Username</th>
                                                <td class="p-2.5 text-gray-700">{{ wan.username }}</td>
                                            </tr>
                                            <tr
                                                v-if="wan.type === 'PPPoE' && wan.last_error && wan.last_error !== 'N/A'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Last
                                                    Error</th>
                                                <td class="p-2.5 text-gray-700">{{ wan.last_error }}</td>
                                            </tr>
                                            <tr
                                                v-if="wan.type === 'IP' && wan.addressing_type && wan.addressing_type !== 'N/A'">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                    Addressing Type</th>
                                                <td class="p-2.5 text-gray-700">{{ wan.addressing_type }}</td>
                                            </tr>
                                            <tr v-if="wan.uptimeFormatted">
                                                <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">Uptime
                                                </th>
                                                <td class="p-2.5 text-gray-700">{{ wan.uptimeFormatted }}</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: DHCP Server -->
                      
                        <div v-else-if="activeTab === 'dhcp'">
                            <div v-if="!dhcpData" class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                                <i class="fa-solid fa-circle-info mr-1"></i>DHCP server not supported on this device
                            </div>

                            <template v-else>
                                <div class="mb-3 flex items-center justify-between">
                                    <h4 class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <i class="fa-solid fa-router text-gray-400"></i>DHCP Server Configuration
                                    </h4>
                                    <button @click="openEditDHCPModal(dhcpData.raw)"
                                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                                        <i class="fa-solid fa-pen mr-1"></i>Edit DHCP
                                    </button>
                                </div>

                                <div class="rounded-xl border border-gray-100">
                                    <div class="flex items-center gap-2 border-b border-gray-100 bg-gray-50 px-4 py-3">
                                        <strong class="text-gray-700">DHCP Server Status</strong>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                            :class="dhcpData.enabled ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'">
                                            {{ dhcpData.enabled ? "Enabled" : "Disabled" }}
                                        </span>
                                    </div>

                                    <table class="w-full text-sm">
                                        <tbody class="divide-y divide-gray-100">
                                            <tr>
                                                <th class="w-1/4 bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                    DHCP Server</th>
                                                <td class="p-2.5 text-gray-700">{{ dhcpData.enabled ? "Enabled" :
                                                    "Disabled" }}</td>
                                            </tr>
                                            <template v-if="dhcpData.enabled">
                                                <tr>
                                                    <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">IP
                                                        Address Pool Start</th>
                                                    <td class="p-2.5 text-gray-700">{{ dhcpData.min_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">IP
                                                        Address Pool End</th>
                                                    <td class="p-2.5 text-gray-700">{{ dhcpData.max_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                        Subnet Mask</th>
                                                    <td class="p-2.5 text-gray-700">{{ dhcpData.subnet_mask }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                        Default Gateway</th>
                                                    <td class="p-2.5">
                                                        <a v-if="dhcpData.gatewayLink.clickable"
                                                            :href="dhcpData.gatewayLink.href" target="_blank"
                                                            class="text-blue-600 hover:underline">
                                                            {{ dhcpData.gatewayLink.display }}
                                                        </a>
                                                        <span v-else class="text-gray-700">{{
                                                            dhcpData.gatewayLink.display }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">DNS
                                                        Servers</th>
                                                    <td class="p-2.5 text-gray-700">{{ dhcpData.dns_servers }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-gray-50 p-2.5 text-left font-medium text-gray-500">
                                                        Lease Time</th>
                                                    <td class="p-2.5 text-gray-700">{{ dhcpData.lease_time }}</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </div>

                       
                        <!-- Tab: Connected Devices -->
                     
                        <div v-else-if="activeTab === 'devices'">
                            <h4 class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <i class="fa-solid fa-network-wired text-gray-400"></i>Connected Devices
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600">{{
                                    connectedDevicesList.length }}</span>
                            </h4>

                            <p class="mb-3 text-xs text-gray-400">
                                <i class="fa-solid fa-router mr-1"></i>{{ hotspotStatusText }}
                            </p>

                            <div v-if="connectedDevicesList.length === 0"
                                class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-700">
                                <i class="fa-solid fa-circle-info mr-1"></i>No devices currently connected to this ONU
                            </div>

                            <div v-else class="overflow-x-auto rounded-lg border border-gray-100">
                                <table class="min-w-full whitespace-nowrap text-sm">
                                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                        <tr>
                                            <th class="p-3 text-center">#</th>
                                            <th class="p-3 text-left">Device Name</th>
                                            <th class="p-3 text-left">IP Address</th>
                                            <th class="p-3 text-left">MAC Address</th>
                                            <th class="p-3 text-center">Connection</th>
                                            <th class="p-3 text-center">Status</th>
                                            <th class="p-3 text-left">Hotspot User</th>
                                            <th class="p-3 text-left">Traffic (RX / TX)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <tr v-for="cd in connectedDevicesList" :key="cd.mac_address"
                                            class="hover:bg-gray-50">
                                            <td class="p-3 text-center text-gray-500">{{ cd.index }}</td>
                                            <td class="p-3">
                                                <strong class="text-gray-700">{{ cd.displayName }}</strong>
                                                <div v-if="cd.showHostnameHint" class="text-xs text-gray-400">Hostname:
                                                    {{ cd.hostname }}</div>
                                            </td>
                                            <td class="p-3">
                                                <a v-if="cd.ipLink.clickable" :href="cd.ipLink.href" target="_blank"
                                                    class="text-blue-600 hover:underline">
                                                    {{ cd.ipLink.display }}
                                                </a>
                                                <span v-else class="text-gray-500">{{ cd.ipLink.display }}</span>
                                            </td>
                                            <td class="p-3 text-gray-600"><code>{{ cd.mac_address }}</code></td>
                                            <td class="p-3 text-center">
                                                <span class="rounded-full px-2.5 py-1 text-xs font-medium"
                                                    :class="cd.interface_type === 'WiFi' ? 'bg-sky-50 text-sky-600' : 'bg-green-50 text-green-600'">
                                                    <i class="fa-solid"
                                                        :class="cd.interface_type === 'WiFi' ? 'fa-wifi' : 'fa-ethernet'"></i>
                                                    {{ cd.interface_type }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-center">
                                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                                    :class="cd.active ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'">
                                                    {{ cd.active ? "Active" : "Inactive" }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-gray-600">
                                                <strong v-if="cd.hotspotUsername">{{ cd.hotspotUsername }}</strong>
                                                <span v-else class="text-gray-300">-</span>
                                            </td>
                                            <td class="p-3">
                                                <template v-if="cd.trafficRx">
                                                    <span class="text-green-600">↓ {{ cd.trafficRx }}</span> /
                                                    <span class="text-red-500">↑ {{ cd.trafficTx }}</span>
                                                </template>
                                                <span v-else class="text-gray-300">-</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </template>
                </div>
            </div>
        </template>

    
        <!-- Summon Modal -->
       
        <div v-if="modals.summon"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 text-center shadow-xl">
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 text-yellow-500">
                    <i class="fa-solid fa-bolt text-xl"></i>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-gray-800">Summon Device?</h2>
                <p class="mt-2 text-sm text-gray-500">{{ deviceId }}</p>
                <div class="mt-6 flex gap-3">
                    <button @click="modals.summon = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmSummon"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Summon
                    </button>
                </div>
            </div>
        </div>

        <!-- Add Tag Modal -->
        
        <div v-if="modals.addTag"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Add Tag</h2>
                <input v-model="newTagName" type="text" placeholder="Nama tag"
                    class="mt-4 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <div class="mt-6 flex gap-3">
                    <button @click="modals.addTag = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="addTagToDevice"
                        class="flex-1 rounded-lg bg-green-600 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                        Tambahkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Remove Tag Modal -->
      
        <div v-if="modals.removeTag"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[380px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Remove Tag</h2>

                <div v-if="deviceTags.length" class="mt-4 flex flex-wrap gap-2">
                    <button v-for="tag in deviceTags" :key="tag" @click="removeTagFromDevice(tag)"
                        class="rounded-lg border border-yellow-300 px-3 py-1.5 text-sm font-medium text-yellow-600 hover:bg-yellow-50">
                        <i class="fa-solid fa-xmark mr-1"></i>{{ tag }}
                    </button>
                </div>
                <p v-else class="mt-4 text-sm text-gray-400">Device ini belum memiliki tag.</p>

                <button @click="modals.removeTag = false"
                    class="mt-6 w-full rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                    Tutup
                </button>
            </div>
        </div>


        <!-- Edit WiFi Modal -->
 
        <div v-if="modals.editWiFi"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[420px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Edit WiFi Configuration</h2>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">SSID</label>
                        <input v-model="wifiForm.ssid" type="text" maxlength="32"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">Security Mode</label>
                        <select v-model="wifiForm.security_mode"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                            <option value="None">Open (No Password)</option>
                            <option value="WPA2">WPA2</option>
                            <option value="WPA3">WPA3</option>
                            <option value="WPA2WPA3">WPA2/WPA3</option>
                        </select>
                    </div>

                    <div v-if="wifiPasswordFieldVisible">
                        <label class="mb-1 block text-sm font-medium text-gray-600">Password</label>
                        <div class="relative">
                            <input v-model="wifiForm.password" :type="editPasswordVisible ? 'text' : 'password'"
                                minlength="8" maxlength="63"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 pr-9 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <button @click="toggleEditPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                type="button">
                                <i class="fa-solid" :class="editPasswordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button @click="modals.editWiFi = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmUpdateWiFi"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit WAN Modal -->
 
        <div v-if="modals.editWAN"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[420px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Edit WAN Connection</h2>

                <div v-if="wanEditForm.isTR069" class="mt-3 rounded-lg bg-red-50 px-3 py-2 text-xs text-red-600">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>Ini adalah koneksi TR069 — berhati-hatilah saat
                    mengubah.
                </div>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <input v-model="wanEditForm.enable" type="checkbox" id="wan-edit-enable">
                        <label for="wan-edit-enable" class="text-sm text-gray-600">Enable Connection</label>
                    </div>

                    <div v-if="wanEditForm.connection_type === 'ppp'">
                        <label class="mb-1 block text-sm font-medium text-gray-600">Username</label>
                        <input v-model="wanEditForm.username" type="text"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div v-if="wanEditForm.connection_type === 'ppp'">
                        <label class="mb-1 block text-sm font-medium text-gray-600">Password (kosongkan jika tidak
                            diubah)</label>
                        <input v-model="wanEditForm.password" type="password"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">VLAN ID</label>
                        <input v-model="wanEditForm.vlan_id" type="number"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div class="flex items-center gap-2">
                        <input v-model="wanEditForm.nat_enabled" type="checkbox" id="wan-edit-nat">
                        <label for="wan-edit-nat" class="text-sm text-gray-600">NAT Enabled</label>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button @click="modals.editWAN = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmUpdateWAN"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

      
        <!-- Add WAN Modal -->
  
        <div v-if="modals.addWAN"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[420px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Add WAN Connection</h2>

                <div class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Connection Index</label>
                            <input v-model="wanAddForm.connection_index" type="number"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Connection Type</label>
                            <select v-model="wanAddForm.connection_type"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                                <option value="ip">IP (Routed/Bridge)</option>
                                <option value="ppp">PPPoE</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-600">Connection Name</label>
                        <input v-model="wanAddForm.name" type="text" placeholder="cth: 2_INTERNET_B_VID_20"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <template v-if="wanAddIsPPP">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Username</label>
                            <input v-model="wanAddForm.username" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Password</label>
                            <input v-model="wanAddForm.password" type="password"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                    </template>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">VLAN ID</label>
                            <input v-model="wanAddForm.vlan_id" type="number"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Service List</label>
                            <select v-model="wanAddForm.service_list"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none">
                                <option value="INTERNET">INTERNET</option>
                                <option value="TR069">TR069</option>
                                <option value="VOIP">VOIP</option>
                                <option value="CUSTOM">Custom...</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="wanAddForm.service_list === 'CUSTOM'">
                        <label class="mb-1 block text-sm font-medium text-gray-600">Custom Service List</label>
                        <input v-model="wanAddForm.service_list_custom" type="text"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>

                    <div class="flex items-center gap-2">
                        <input v-model="wanAddForm.nat_enabled" type="checkbox" id="wan-add-nat">
                        <label for="wan-add-nat" class="text-sm text-gray-600">NAT Enabled</label>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button @click="modals.addWAN = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmAddWAN"
                        class="flex-1 rounded-lg bg-green-600 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                        Tambahkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete WAN Modal -->
  
        <div v-if="modals.deleteWAN"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[400px] rounded-2xl bg-white p-6 text-center shadow-xl">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500">
                    <i class="fa-solid fa-trash text-xl"></i>
                </div>
                <h2 class="mt-4 text-lg font-semibold text-gray-800">Hapus WAN Connection?</h2>
                <p class="mt-2 text-sm text-gray-500">{{ currentWANDelete?.name }}</p>

                <div v-if="wanDeleteIsTR069" class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-left text-xs text-red-600">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    Ini koneksi <strong>TR069</strong> ({{ currentWANDelete?.service_list || "N/A" }}). Menghapusnya
                    bisa
                    memutus koneksi manajemen device.
                    <label class="mt-2 flex items-center gap-2">
                        <input v-model="wanDeleteTR069Confirmed" type="checkbox">
                        <span>Saya paham risikonya dan tetap ingin menghapus</span>
                    </label>
                </div>
                <div v-else class="mt-4 rounded-lg bg-yellow-50 px-4 py-3 text-left text-xs text-yellow-700">
                    Tindakan ini tidak bisa dibatalkan.
                </div>

                <div class="mt-6 flex gap-3">
                    <button @click="modals.deleteWAN = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmDeleteWAN"
                        class="flex-1 rounded-lg bg-red-600 py-2.5 text-sm font-medium text-white hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>


        <!-- Edit DHCP Modal -->
   
        <div v-if="modals.editDHCP"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[420px] rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-800">Edit DHCP Configuration</h2>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <input v-model="dhcpForm.enable" type="checkbox" id="dhcp-enable">
                        <label for="dhcp-enable" class="text-sm text-gray-600">Enable DHCP Server</label>
                    </div>

                    <template v-if="dhcpForm.enable">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-600">Pool Start</label>
                                <input v-model="dhcpForm.min_address" type="text"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-600">Pool End</label>
                                <input v-model="dhcpForm.max_address" type="text"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Subnet Mask</label>
                            <input v-model="dhcpForm.subnet_mask" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Default Gateway</label>
                            <input v-model="dhcpForm.gateway" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">DNS Servers</label>
                            <input v-model="dhcpForm.dns_servers" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Lease Time (detik)</label>
                            <input v-model="dhcpForm.lease_time" type="number"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                    </template>
                </div>

                <div class="mt-6 flex gap-3">
                    <button @click="modals.editDHCP = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="confirmUpdateDHCP"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

   
        <!-- Toast -->

        <transition name="fade">
            <div v-if="toastState.visible"
                class="fixed bottom-6 right-6 z-50 rounded-xl px-4 py-3 text-sm font-medium text-white shadow-lg"
                :class="{
                    'bg-green-600': toastState.type === 'success',
                    'bg-red-600': toastState.type === 'danger' || toastState.type === 'error',
                    'bg-yellow-500': toastState.type === 'warning',
                    'bg-blue-600': toastState.type === 'info',
                }">
                {{ toastState.message }}
            </div>
        </transition>

    </div>
</template>

<script setup>
/**
 * DeviceDetail.vue (script setup)
 * Vue 3 equivalent of device-detail.js
 *
 * Catatan penting soal pendekatan konversi:
 * - Semua fungsi render*() yang tadinya membangun string HTML lewat innerHTML
 *   di sini diganti jadi *computed* yang mengembalikan DATA terstruktur
 *   (bukan HTML string). Template Vue nanti tinggal v-for / v-if terhadap
 *   data ini. Ini best-practice Vue: jangan bangun HTML manual di JS.
 * - Semua modal (WiFi, WAN edit/add/delete, DHCP, Tag) diganti jadi
 *   reactive boolean di object `modals`, sama seperti pola sebelumnya.
 * - Hotspot traffic polling per-device tetap pakai setInterval, tapi hasilnya
 *   disimpan ke reactive object `hotspotByMac` alih-alih memodifikasi
 *   elemen DOM (`.hotspot-user[data-mac]`) secara langsung.
 */

import { ref, reactive, computed, watch, onMounted, onUnmounted } from "vue";


// Config

const token = window.APP.jwt;
const API_BASE = "http://163.223.104.166:8881";


const props = defineProps({
    deviceId: {
        type: [String, Number],
        default: "",
    },
});

// Ambil device ID dari prop route, dengan fallback ke global/query lama.
const deviceId = ref(
    String(
        props.deviceId ||
        (typeof window !== "undefined"
            ? window.DEVICE_ID || new URLSearchParams(window.location.search).get("id") || ""
            : "")
    )
);

// Sama seperti halaman devices — di-set inline oleh PHP (isGenieACSConfigured())

const genieacsConfigured = ref(false)

async function loadConfiguration() {
    const result = await fetchAPI('/api/get-genieacs-config.php')

    if (result?.success) {
        genieacsConfigured.value = result.configured
    }
}

// =====================================================
// Core state
// =====================================================

const device = ref(null);
const locationResult = ref(null);

const loadingInitial = ref(false);
const loadingMessageText = ref("");
const errorMessage = ref("");

const activeTab = ref("overview"); // overview | topology | wan | dhcp | devices

let autoRefreshTimer = null;
let savedScrollPosition = 0;

// Toast
const toastState = reactive({ visible: false, message: "", type: "success" });

function showToast(message, type = "success", duration = 3000) {
    toastState.message = message;
    toastState.type = type;
    toastState.visible = true;
    setTimeout(() => (toastState.visible = false), duration);
}

function showLoading(message = "Loading...") {
    loadingInitial.value = true;
    loadingMessageText.value = message;
}

function hideLoading() {
    loadingInitial.value = false;
}

// =====================================================
// fetchAPI helper
// =====================================================

async function fetchAPI(endpoint, options = {}) {
    try {
        const response = await fetch(`${API_BASE}${endpoint}`, {
            headers: { "Content-Type": "application/json",  'Authorization': `Bearer ${token}` },
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

// =====================================================
// Formatting helpers
// =====================================================

function extractIP(ipString) {
    if (!ipString || ipString === "N/A") return "N/A";
    const match = ipString.match(/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/);
    return match ? match[1] : "N/A";
}

// Ganti makeIPClickable(): dulu mengembalikan string <a>, sekarang kembalikan
// data {display, clickable, href} — template yang menentukan render <a> atau <span>.
function ipLinkData(ip) {
    if (!ip || ip === "N/A" || ip === "0.0.0.0") {
        return { display: ip, clickable: false, href: null };
    }
    const ipRegex = /^(\d{1,3}\.){3}\d{1,3}$/;
    if (!ipRegex.test(ip)) {
        return { display: ip, clickable: false, href: null };
    }
    return { display: ip, clickable: true, href: `http://${ip}` };
}

function formatUptime(seconds) {
    const s = parseInt(seconds) || 0;
    if (s <= 0) return "N/A";
    const days = Math.floor(s / 86400);
    const hours = Math.floor((s % 86400) / 3600);
    const minutes = Math.floor((s % 3600) / 60);
    const parts = [];
    if (days > 0) parts.push(`${days}d`);
    if (hours > 0) parts.push(`${hours}h`);
    if (minutes > 0) parts.push(`${minutes}m`);
    return parts.length ? parts.join(" ") : "< 1m";
}

function formatBitrate(bps) {
    if (bps === 0 || isNaN(bps)) return "0 bps";
    const units = ["bps", "Kbps", "Mbps", "Gbps"];
    let value = Math.abs(bps);
    let unitIndex = 0;
    while (value >= 1024 && unitIndex < units.length - 1) {
        value /= 1024;
        unitIndex++;
    }
    return value.toFixed(2) + " " + units[unitIndex];
}

// =====================================================
// Load device detail (ganti loadDeviceDetail())
// =====================================================

async function loadDeviceDetail(isAutoRefresh = false) {
    // Skip auto-refresh kalau hotspot monitoring sedang aktif (sama seperti asli)
    if (isAutoRefresh && hotspotMonitoringActive.value) {
        console.debug("[AUTO-REFRESH] Skipping device detail refresh while hotspot monitoring is active");
        return;
    }

    if (isAutoRefresh) {
        savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    } else {
        savedScrollPosition = 0;
        showLoading("Loading device detail...");
    }

    const result = await fetchAPI("/api/get-device-detail.php?device_id=" + encodeURIComponent(deviceId.value));

    if (result && result.success) {
        device.value = result.device;

        const locResult = await fetchAPI(
            "/api/get-onu-location.php?serial_number=" + encodeURIComponent(result.device.serial_number)
        );
        locationResult.value = locResult;

        if (!isAutoRefresh) hideLoading();

        // Restore scroll setelah reload (auto-refresh atau setelah Get Credentials)
        const credentialsScrollPos = sessionStorage.getItem("credentialsScrollPosition");
        if (credentialsScrollPos) {
            setTimeout(() => {
                window.scrollTo(0, parseInt(credentialsScrollPos));
                sessionStorage.removeItem("credentialsScrollPosition");
            }, 50);
        } else if (isAutoRefresh && savedScrollPosition > 0) {
            setTimeout(() => window.scrollTo(0, savedScrollPosition), 50);
        }

        // Kalau hotspot monitoring aktif sebelum refresh, restart (dengan delay, sama seperti asli)
        if (hotspotMonitoringActive.value) {
            setTimeout(() => {
                console.log("[AUTO-REFRESH] Restarting hotspot monitoring (3s delay)...");
                hotspotStatusText.value = "Waiting for next update...";
                hotspotTrafficInterval = setInterval(fetchHotspotTraffic, 5000);
            }, 3000);
        }
    } else {
        errorMessage.value = "Failed to load device details";
        if (!isAutoRefresh) hideLoading();
    }
}

// =====================================================
// Computed: Overview tab data (ganti pembuatan HTML overview)
// =====================================================

const overviewBasicInfo = computed(() => {
    if (!device.value) return null;
    const d = device.value;
    return {
        device_id: d.device_id,
        serial_number: d.serial_number,
        mac_address: d.mac_address,
        last_inform: d.last_inform,
        status: d.status,
        manufacturer: d.manufacturer,
        product_class: d.product_class,
        oui: d.oui,
    };
});

const overviewHardware = computed(() => {
    if (!device.value) return null;
    const d = device.value;
    return {
        hardware_version: d.hardware_version,
        software_version: d.software_version,
        uptime: formatUptime(d.uptime),
        rx_power: d.rx_power,
        temperature: d.temperature,
    };
});

const needsAdminCredentials = computed(() => {
    const d = device.value;
    if (!d) return false;
    return !d.admin_user || d.admin_user === "N/A" || d.admin_user === "";
});

// =====================================================
// Password visibility toggles (ganti toggle*Password())
// =====================================================

const passwordVisible = reactive({
    wifi: false,
    admin: false,
    telecom: false,
});

function togglePassword() {
    passwordVisible.wifi = !passwordVisible.wifi;
}
function toggleAdminPassword() {
    passwordVisible.admin = !passwordVisible.admin;
}
function toggleTelecomPassword() {
    passwordVisible.telecom = !passwordVisible.telecom;
}

// =====================================================
// Topology / Location tab (ganti renderTopologyLocationTab)
// =====================================================

const topologyData = computed(() => {
    const result = locationResult.value;

    if (!result || !result.success) {
        return { state: "unavailable" };
    }

    if (!result.location || !result.location.found) {
        return { state: "not_in_map" };
    }

    const loc = result.location;

    const hierarchyPath = [];
    if (loc.server?.name) hierarchyPath.push({ label: loc.server.name, kind: "server" });
    if (loc.olt?.name) hierarchyPath.push({ label: loc.olt.name, kind: "olt" });
    if (loc.odc?.name) hierarchyPath.push({ label: loc.odc.name, kind: "odc" });
    if (loc.odp?.name) hierarchyPath.push({ label: loc.odp.name, kind: "odp" });
    if (loc.onu?.name) hierarchyPath.push({ label: loc.onu.name, kind: "onu" });

    let coordinates = null;
    if (loc.onu?.lat && loc.onu?.lng) {
        const lat = parseFloat(loc.onu.lat);
        const lng = parseFloat(loc.onu.lng);
        if (lat !== 0 && lng !== 0) {
            coordinates = {
                lat: lat.toFixed(6),
                lng: lng.toFixed(6),
                googleMapsUrl: `https://www.google.com/maps?q=${lat},${lng}`,
                networkMapUrl: `/map.php?focus_type=onu&focus_id=${loc.onu.id}`,
            };
        }
    }

    return {
        state: "found",
        hierarchyPath,
        odp: loc.odp?.name ? { name: loc.odp.name, mapUrl: `/map.php?focus_type=odp&focus_id=${loc.odp.id}` } : null,
        port: loc.onu?.port && loc.onu.port !== "N/A" ? loc.onu.port : null,
        odc: loc.odc?.name ? { name: loc.odc.name, mapUrl: `/map.php?focus_type=odc&focus_id=${loc.odc.id}` } : null,
        olt: loc.olt?.name || null,
        coordinates,
    };
});

// =====================================================
// WAN tab (ganti renderWANDetailsTab)
// =====================================================

const wanList = computed(() => {
    const wanDetails = device.value?.wan_details || [];

    return wanDetails.map((wan) => {
        const isBridge = !!(wan.connection_type && /bridge|bridged/i.test(wan.connection_type));
        const vlanMatch = wan.name?.match(/VID[_-]?(\d+)/i);
        const vlanId = vlanMatch ? vlanMatch[1] : null;
        const isTR069 =
            (wan.service_list && /tr069|cwmp/i.test(wan.service_list)) ||
            (wan.name && /tr069|cwmp/i.test(wan.name));

        return {
            ...wan,
            isBridge,
            vlanId,
            isTR069,
            statusOnline: wan.status === "Connected",
            externalIpLink: ipLinkData(wan.external_ip),
            gatewayLink: ipLinkData(wan.gateway),
            uptimeFormatted: wan.uptime && wan.uptime !== "0" ? formatUptime(wan.uptime) : null,
        };
    });
});

// =====================================================
// DHCP tab (ganti renderDHCPServerTab)
// =====================================================

const dhcpData = computed(() => {
    const d = device.value?.dhcp_server;
    if (!d) return null;

    const enabled = d.enabled === true || d.enabled === "true";

    return {
        enabled,
        min_address: d.min_address,
        max_address: d.max_address || "N/A",
        subnet_mask: d.subnet_mask || "N/A",
        gatewayLink: ipLinkData(d.gateway || "N/A"),
        dns_servers: d.dns_servers || "N/A",
        lease_time: d.lease_time ? formatUptime(d.lease_time) : "N/A",
        raw: d,
    };
});

// =====================================================
// Connected devices tab (ganti renderConnectedDevicesTab)
// =====================================================

const connectedDevicesList = computed(() => {
    const list = device.value?.connected_devices || [];

    return list.map((cd, index) => {
        const hotspot = hotspotByMac[cd.mac_address] || { found: false, username: null, rxFormatted: "-", txFormatted: "-" };

        return {
            index: index + 1,
            displayName: cd.vendor || cd.hostname || "Unknown Device",
            showHostnameHint: cd.hostname && cd.vendor && cd.hostname !== cd.vendor,
            hostname: cd.hostname,
            ipLink: ipLinkData(cd.ip_address),
            mac_address: cd.mac_address,
            interface_type: cd.interface_type,
            active: cd.active,
            hotspotUsername: hotspot.found ? hotspot.username : null,
            trafficRx: hotspot.found ? hotspot.rxFormatted : null,
            trafficTx: hotspot.found ? hotspot.txFormatted : null,
        };
    });
});

// =====================================================
// Summon device (modal)
// =====================================================

const modals = reactive({
    summon: false,
    editWiFi: false,
    editWAN: false,
    addWAN: false,
    deleteWAN: false,
    editDHCP: false,
    addTag: false,
    removeTag: false,
});

function summonDevice() {
    modals.summon = true;
}

async function confirmSummon() {
    modals.summon = false;
    showLoading();

    const result = await fetchAPI("/api/summon-device.php", {
        method: "POST",
        body: JSON.stringify({ device_id: deviceId.value }),
    });

    hideLoading();

    if (result?.success) {
        showToast("🚀 Device summon berhasil! Menunggu device response...", "success");

        let countdown = 15;
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdown > 0) showToast(`⏳ Auto-refresh dalam ${countdown} detik...`, "info");
        }, 1000);

        setTimeout(() => {
            clearInterval(countdownInterval);
            showToast("🔄 Refreshing device data...", "info");
            loadDeviceDetail();
        }, 15000);
    } else {
        showToast(result?.message || "Gagal summon device", "danger");
    }
}

// =====================================================
// Summon for admin credentials
// =====================================================

const fetchingCredentials = ref(false);
const credentialsStatusVisible = ref(false);
const credentialsStatusText = ref("");

async function summonForAdminCredentials() {
    fetchingCredentials.value = true;
    credentialsStatusVisible.value = true;
    credentialsStatusText.value = "Summoning device...";

    const result = await fetchAPI("/api/summon-device.php", {
        method: "POST",
        body: JSON.stringify({ device_id: deviceId.value }),
    });

    if (result?.success) {
        showToast("Device summon berhasil, mengambil credentials...", "success", 5000);

        let countdown = 10;
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdown > 0) credentialsStatusText.value = `Menunggu credentials dari device (${countdown}s)...`;
        }, 1000);

        setTimeout(() => {
            clearInterval(countdownInterval);
            credentialsStatusText.value = "Refreshing device data...";

            const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
            sessionStorage.setItem("credentialsScrollPosition", currentScrollPosition);

            loadDeviceDetail();
        }, 10000);
    } else {
        credentialsStatusVisible.value = false;
        fetchingCredentials.value = false;
        showToast(result?.message || "Gagal summon device", "danger", 5000);
    }
}

// =====================================================
// WiFi edit modal
// =====================================================

const wifiForm = reactive({
    device_id: "",
    ssid: "",
    security_mode: "WPA2",
    password: "",
    wlan_index: 1,
});

const editPasswordVisible = ref(false);

function openEditWiFiModal() {
    if (!device.value) return;
    wifiForm.device_id = device.value.device_id;
    wifiForm.ssid = device.value.wifi_ssid;
    wifiForm.password = device.value.wifi_password;
    wifiForm.wlan_index = 1;
    modals.editWiFi = true;
}

function toggleEditPassword() {
    editPasswordVisible.value = !editPasswordVisible.value;
}

// Dipanggil saat security_mode berubah di form (ganti togglePasswordField())
const wifiPasswordFieldVisible = computed(() => wifiForm.security_mode !== "None");

async function confirmUpdateWiFi() {
    const ssid = wifiForm.ssid.trim();

    if (ssid.length < 1 || ssid.length > 32) {
        showToast("WiFi SSID harus antara 1-32 karakter", "danger");
        return;
    }

    if (wifiForm.security_mode !== "None") {
        if (wifiForm.password.length < 8 || wifiForm.password.length > 63) {
            showToast("WiFi Password harus antara 8-63 karakter", "danger");
            return;
        }
    }

    modals.editWiFi = false;
    showLoading("Updating WiFi configuration...");

    const requestData = {
        device_id: wifiForm.device_id,
        wifi_ssid: ssid,
        security_mode: wifiForm.security_mode,
        wlan_index: parseInt(wifiForm.wlan_index),
    };

    if (wifiForm.security_mode !== "None") {
        requestData.wifi_password = wifiForm.password;
    }

    const result = await fetchAPI("/api/update-wifi-config.php", {
        method: "POST",
        body: JSON.stringify(requestData),
    });

    hideLoading();

    if (result?.success) {
        showToast(result.message || "WiFi configuration updated successfully!", "success");
        setTimeout(() => loadDeviceDetail(), 2000);
    } else {
        showToast(result?.message || "Failed to update WiFi configuration", "danger");
    }
}

// =====================================================
// WAN: Edit modal
// =====================================================

const wanEditForm = reactive({
    connection_index: null,
    connection_type: "ip", // ip | ppp
    name: "",
    enable: true,
    username: "",
    password: "",
    nat_enabled: true,
    vlan_id: "",
    isTR069: false,
});

function openEditWANModal(wanData) {
    wanEditForm.connection_index = wanData.connection_index || null;
    wanEditForm.connection_type = wanData.type === "PPPoE" ? "ppp" : "ip";
    wanEditForm.name = wanData.name || "";
    wanEditForm.enable = wanData.status === "Connected";
    wanEditForm.username = wanData.type === "PPPoE" ? wanData.username || "" : "";
    wanEditForm.password = "";
    wanEditForm.nat_enabled = wanData.nat_enabled !== undefined ? !!wanData.nat_enabled : true;

    const vlanMatch = wanData.name?.match(/VID[_-]?(\d+)/i);
    wanEditForm.vlan_id = vlanMatch ? vlanMatch[1] : "";

    wanEditForm.isTR069 =
        (wanData.service_list && /tr069|cwmp/i.test(wanData.service_list)) ||
        (wanData.name && /tr069|cwmp/i.test(wanData.name));

    modals.editWAN = true;
}

async function confirmUpdateWAN() {
    const parameters = {
        Enable: wanEditForm.enable,
        NATEnabled: wanEditForm.nat_enabled,
    };

    if (wanEditForm.connection_type === "ppp" && wanEditForm.username) {
        parameters.Username = wanEditForm.username;
        if (wanEditForm.password) parameters.Password = wanEditForm.password;
    }

    if (wanEditForm.vlan_id) {
        parameters["X_CT-COM_VLANID"] = parseInt(wanEditForm.vlan_id);
    }

    modals.editWAN = false;
    showLoading("Updating WAN configuration...");

    const result = await fetchAPI("/api/update-wan-config.php", {
        method: "POST",
        body: JSON.stringify({
            device_id: deviceId.value,
            connection_index: parseInt(wanEditForm.connection_index),
            connection_type: wanEditForm.connection_type,
            parameters,
        }),
    });

    hideLoading();

    if (result?.success) {
        showToast(result.message || "WAN configuration updated successfully!", "success");
        setTimeout(() => loadDeviceDetail(), 2000);
    } else {
        showToast(result?.message || "Failed to update WAN configuration", "danger");
    }
}

// =====================================================
// WAN: Add modal
// =====================================================

const wanAddForm = reactive({
    connection_index: null,
    connection_type: "ip", // ip | ppp
    name: "",
    username: "",
    password: "",
    service_list: "INTERNET",
    service_list_custom: "",
    vlan_id: null,
    nat_enabled: true,
});

function openAddWANModal() {
    Object.assign(wanAddForm, {
        connection_index: null,
        connection_type: "ip",
        name: "",
        username: "",
        password: "",
        service_list: "INTERNET",
        service_list_custom: "",
        vlan_id: null,
        nat_enabled: true,
    });
    modals.addWAN = true;
}

// dipakai template untuk show/hide field username/password
const wanAddIsPPP = computed(() => wanAddForm.connection_type === "ppp");

async function confirmAddWAN() {
    const name = wanAddForm.name.trim();

    if (wanAddIsPPP.value) {
        if (!wanAddForm.username.trim() || !wanAddForm.password) {
            showToast("Username and password are required for PPPoE connections", "danger");
            return;
        }
    }

    const parameters = {
        Enable: true,
        ConnectionType: "IP_Routed",
        NATEnabled: wanAddForm.nat_enabled,
        "X_CT-COM_VLANID": parseInt(wanAddForm.vlan_id),
        "X_CT-COM_ServiceList":
            wanAddForm.service_list === "CUSTOM" ? wanAddForm.service_list_custom : wanAddForm.service_list,
    };

    if (wanAddIsPPP.value) {
        parameters.Username = wanAddForm.username.trim();
        parameters.Password = wanAddForm.password;
    }

    modals.addWAN = false;
    showLoading("Creating WAN connection...");

    const result = await fetchAPI("/api/add-wan-config.php", {
        method: "POST",
        body: JSON.stringify({
            device_id: deviceId.value,
            connection_index: parseInt(wanAddForm.connection_index),
            connection_type: wanAddForm.connection_type,
            name,
            parameters,
        }),
    });

    hideLoading();

    if (result?.success) {
        showToast(result.message || "WAN connection created successfully!", "success");
        setTimeout(() => loadDeviceDetail(), 2000);
    } else {
        showToast(result?.message || "Failed to create WAN connection", "danger");
    }
}

// =====================================================
// WAN: Delete modal
// =====================================================

const currentWANDelete = ref(null);
const wanDeleteTR069Confirmed = ref(false);

const wanDeleteIsTR069 = computed(() => {
    const wan = currentWANDelete.value;
    if (!wan) return false;
    return (wan.service_list && /tr069|cwmp/i.test(wan.service_list)) || (wan.name && /tr069|cwmp/i.test(wan.name));
});

function openDeleteWANModal(wanData) {
    currentWANDelete.value = wanData;
    wanDeleteTR069Confirmed.value = false;
    modals.deleteWAN = true;
}

async function confirmDeleteWAN() {
    const wan = currentWANDelete.value;
    if (!wan) return;

    if (wanDeleteIsTR069.value && !wanDeleteTR069Confirmed.value) {
        showToast("Please confirm that you understand the risks before deleting TR069 connection", "warning");
        return;
    }

    modals.deleteWAN = false;
    showLoading("Deleting WAN connection...");

    const result = await fetchAPI("/api/delete-wan-config.php", {
        method: "POST",
        body: JSON.stringify({
            device_id: deviceId.value,
            connection_index: wan.connection_index,
            connection_type: wan.type === "PPPoE" ? "ppp" : "ip",
            connection_name: wan.name,
            service_list: wan.service_list || "",
            confirm_tr069_delete: wanDeleteIsTR069.value,
        }),
    });

    hideLoading();

    if (result?.success) {
        showToast(result.message || "WAN connection deleted successfully!", "success");
        setTimeout(() => loadDeviceDetail(), 2000);
    } else if (result?.requires_confirmation) {
        showToast("TR069 connection deletion blocked - please confirm deletion", "warning");
    } else {
        showToast(result?.message || "Failed to delete WAN connection", "danger");
    }

    currentWANDelete.value = null;
}

// =====================================================
// DHCP edit modal
// =====================================================

const dhcpForm = reactive({
    enable: false,
    min_address: "",
    max_address: "",
    subnet_mask: "",
    gateway: "",
    dns_servers: "",
    lease_time: "",
});

function openEditDHCPModal(dhcpRaw) {
    const enabled = dhcpRaw.enabled === true || dhcpRaw.enabled === "true";
    Object.assign(dhcpForm, {
        enable: enabled,
        min_address: dhcpRaw.min_address || "",
        max_address: dhcpRaw.max_address || "",
        subnet_mask: dhcpRaw.subnet_mask || "",
        gateway: dhcpRaw.gateway || "",
        dns_servers: dhcpRaw.dns_servers || "",
        lease_time: dhcpRaw.lease_time || "",
    });
    modals.editDHCP = true;
}

// Ganti toggleDHCPFields() — tinggal v-if="dhcpForm.enable" di template
async function confirmUpdateDHCP() {
    const parameters = { DHCPServerEnable: dhcpForm.enable };

    if (dhcpForm.enable) {
        parameters.MinAddress = dhcpForm.min_address.trim();
        parameters.MaxAddress = dhcpForm.max_address.trim();
        parameters.SubnetMask = dhcpForm.subnet_mask.trim();
        parameters.IPRouters = dhcpForm.gateway.trim();
        parameters.DNSServers = dhcpForm.dns_servers.trim();

        const leaseTime = String(dhcpForm.lease_time).trim();
        if (leaseTime) parameters.DHCPLeaseTime = parseInt(leaseTime);
    }

    modals.editDHCP = false;
    showLoading("Updating DHCP configuration...");

    const result = await fetchAPI("/api/update-dhcp-config.php", {
        method: "POST",
        body: JSON.stringify({ device_id: deviceId.value, parameters }),
    });

    hideLoading();

    if (result?.success) {
        showToast(result.message || "DHCP configuration updated successfully!", "success");
        setTimeout(() => loadDeviceDetail(), 2000);
    } else {
        showToast(result?.message || "Failed to update DHCP configuration", "danger");
    }
}

// =====================================================
// Tags management
// =====================================================

const deviceTags = computed(() => device.value?.tags || []);

const newTagName = ref("");

function showAddTagModal() {
    newTagName.value = "";
    modals.addTag = true;
}

async function addTagToDevice() {
    const tagName = newTagName.value.trim();
    if (!tagName) {
        showToast("Please enter a tag name", "warning");
        return;
    }

    const result = await fetchAPI("/api/bulk-tag.php", {
        method: "POST",
        body: JSON.stringify({ action: "add", device_ids: [deviceId.value], tag: tagName }),
    });

    if (result?.success) {
        showToast(`Tag "${tagName}" added successfully`, "success");
        modals.addTag = false;
        loadDeviceDetail();
    } else {
        showToast(result?.message || "Failed to add tag", "danger");
    }
}

function showRemoveTagModal() {
    // deviceTags computed sudah reaktif ke device.value.tags, tidak perlu fetch ulang
    modals.removeTag = true;
}

async function removeTagFromDevice(tagName) {
    const result = await fetchAPI("/api/bulk-tag.php", {
        method: "POST",
        body: JSON.stringify({ action: "remove", device_ids: [deviceId.value], tag: tagName }),
    });

    if (result?.success) {
        showToast(`Tag "${tagName}" removed successfully`, "success");
        modals.removeTag = false;
        loadDeviceDetail();
    } else {
        showToast(result?.message || "Failed to remove tag", "danger");
    }
}

// =====================================================
// Hotspot traffic monitoring
// =====================================================

let hotspotTrafficInterval = null;
let previousBytesData = {};
const hotspotMonitoringActive = ref(false);
let hotspotFetchInProgress = false;
let hotspotAbortController = null;

const hotspotStatusText = ref("Hotspot monitoring will start automatically...");
const hotspotByMac = reactive({}); // { [mac]: { found, username, rxFormatted, txFormatted } }

async function fetchHotspotTraffic() {
    if (hotspotFetchInProgress) return;

    const macAddresses = (device.value?.connected_devices || []).map((d) => d.mac_address);
    if (macAddresses.length === 0) return;

    hotspotFetchInProgress = true;
    hotspotAbortController = new AbortController();
    hotspotStatusText.value = "Updating...";

    try {
        const result = await fetchAPI("/api/get-hotspot-traffic.php", {
            method: "POST",
            body: JSON.stringify({ mac_addresses: macAddresses }),
            signal: hotspotAbortController.signal,
        });

        if (result?.success && result.data) {
            updateHotspotTrafficDisplay(result.data, result.timestamp);

            const now = new Date();
            const timeStr = now.toLocaleTimeString("id-ID", { hour: "2-digit", minute: "2-digit", second: "2-digit" });
            const cacheInfo = result.from_cache ? ` [cached ${result.cache_age}s]` : "";
            hotspotStatusText.value = `✓ ${timeStr}${cacheInfo}`;
        } else if (result?.error === "timeout") {
            hotspotStatusText.value = "Timeout - retrying...";
        } else {
            hotspotStatusText.value = result?.message || "MikroTik Offline";
        }
    } catch (error) {
        if (error.name !== "AbortError") {
            console.error("[HOTSPOT] Fetch failed:", error.message);
        }
        hotspotStatusText.value = "Connection error - showing last known data";
    } finally {
        hotspotFetchInProgress = false;
        hotspotAbortController = null;
    }
}

function updateHotspotTrafficDisplay(data, timestamp) {
    Object.keys(data).forEach((mac) => {
        const userInfo = data[mac];

        if (!userInfo.found) {
            hotspotByMac[mac] = { found: false, username: null, rxFormatted: "N/A", txFormatted: "N/A" };
            return;
        }

        const currentBytesIn = userInfo.bytes_in || 0;
        const currentBytesOut = userInfo.bytes_out || 0;

        let rxRate = 0;
        let txRate = 0;

        const prev = previousBytesData[mac];
        if (prev) {
            const timeDiff = timestamp - prev.timestamp;
            if (timeDiff > 0) {
                const bytesDiffIn = Math.max(0, currentBytesIn - prev.bytes_in);
                const bytesDiffOut = Math.max(0, currentBytesOut - prev.bytes_out);
                const rawRxRate = (bytesDiffIn / timeDiff) * 8;
                const rawTxRate = (bytesDiffOut / timeDiff) * 8;

                const smoothingFactor = 0.3;
                if (prev.rxRate !== undefined) {
                    rxRate = smoothingFactor * rawRxRate + (1 - smoothingFactor) * prev.rxRate;
                    txRate = smoothingFactor * rawTxRate + (1 - smoothingFactor) * prev.txRate;
                } else {
                    rxRate = rawRxRate;
                    txRate = rawTxRate;
                }
            } else {
                rxRate = prev.rxRate || 0;
                txRate = prev.txRate || 0;
            }
        }

        previousBytesData[mac] = {
            bytes_in: currentBytesIn,
            bytes_out: currentBytesOut,
            timestamp,
            rxRate,
            txRate,
        };

        hotspotByMac[mac] = {
            found: true,
            username: userInfo.username,
            rxFormatted: formatBitrate(rxRate),
            txFormatted: formatBitrate(txRate),
        };
    });
}

function startHotspotTrafficMonitoring() {
    if (!device.value?.connected_devices?.length) return;

    hotspotStatusText.value = "Starting...";

    if (hotspotTrafficInterval) clearInterval(hotspotTrafficInterval);

    hotspotMonitoringActive.value = true;

    fetchHotspotTraffic();
    hotspotTrafficInterval = setInterval(fetchHotspotTraffic, 5000);
}

function stopHotspotTrafficMonitoring() {
    hotspotStatusText.value = "Monitoring stopped";

    if (hotspotTrafficInterval) {
        clearInterval(hotspotTrafficInterval);
        hotspotTrafficInterval = null;
    }

    if (hotspotAbortController) {
        hotspotAbortController.abort();
        hotspotAbortController = null;
    }

    hotspotFetchInProgress = false;
    previousBytesData = {};
    hotspotMonitoringActive.value = false;
}

// Ganti event listener 'shown.bs.tab' — di Vue tinggal watch activeTab
watch(activeTab, (newTab, oldTab) => {
    if (newTab === "devices") {
        setTimeout(() => startHotspotTrafficMonitoring(), 200);
    } else if (oldTab === "devices" && hotspotTrafficInterval) {
        stopHotspotTrafficMonitoring();
    }
});

// =====================================================
// Lifecycle
// =====================================================

onMounted(() => {
    loadDeviceDetail(); // initial load
    autoRefreshTimer = setInterval(() => loadDeviceDetail(true), 30000);
});

onUnmounted(() => {
    if (autoRefreshTimer) clearInterval(autoRefreshTimer);
    stopHotspotTrafficMonitoring();
});

// Catatan: tidak perlu defineExpose — semua ref/computed/function di atas
// otomatis tersedia di <template> jika berada dalam satu Single File Component.
</script>