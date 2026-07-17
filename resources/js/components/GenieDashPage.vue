<script setup>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
const props = defineProps({
    configured: {
        type: Boolean,
        default: true
    }
});


const stats = ref({
    total: 0,
    online: 0,
    offline: 0,
    uptime: 0
});

const devices = ref([]);
const loading = ref(false);
const confirmDevice = ref(null);
const notMapDevice = ref(null);
const deviceChart = ref(null);
const uplinkChart = ref(null);
let chartDevice = null;
let chartUplink = null;
let dashboardLoading = false;
let uplinkLoading = false;
let deviceLoading = false;
const summoning = ref(false);




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

async function loadStats() {

    if (dashboardLoading)
        return
    dashboardLoading = true;
    const data = await fetchAPI(
        '/api/dashboard-stats.php'
    );
    if (data.success) {
        stats.value = {

            ...data.stats,

            uptime: data.stats.total
                ?
                Math.round(
                    data.stats.online /
                    data.stats.total *
                    100
                ) : 0
        };

        updateDeviceChart();

    }


    dashboardLoading = false;

}

async function loadUplink() {

    if (uplinkLoading)
        return;
    uplinkLoading = true;
    const data = await fetchAPI(
        '/api/uplink-stats.php'
    );
    if (data.success) {

        updateUplinkChart(
            data.data
        );

    }
    uplinkLoading = false;

}


async function loadDevices() {
    if (deviceLoading)
        return

    deviceLoading = true;


    const data = await fetchAPI(
        '/api/recent-devices.php'
    );
    if (data.success) {

        devices.value = data.devices
    }
    deviceLoading = false;
}


function updateDeviceChart() {
    if (chartDevice)
        chartDevice.destroy();
    chartDevice = new Chart(
        deviceChart.value,
        {
            type: 'doughnut',


            data: {

                labels: [
                    "Online",
                    "Offline"
                ],
                datasets: [{

                    data: [
                        stats.value.online,
                        stats.value.offline
                    ],
                    backgroundColor: [
                        "#16a34a",
                        "#dc2626"
                    ],
                    borderWidth: 2

                }]

            },


            options: {
                responsive: true,
                plugins: {

                    legend: {
                        position: 'bottom'
                    },


                    title: {
                        display: true,
                        text:
                            'Device Status Distribution'
                    }

                }

            }

        }

    );


}
function updateUplinkChart(data) {


    if (chartUplink)
        chartUplink.destroy();
    chartUplink = new Chart(

        uplinkChart.value,

        {
            type: 'doughnut',
            data: {
                labels: [

                    "Excellent",
                    "Good",
                    "Fair",
                    "Poor",
                    "No Signal"

                ],
                datasets: [{
                    data: [

                        data.excellent,
                        data.good,
                        data.fair,
                        data.poor,
                        data.no_signal

                    ],
                    backgroundColor: [

                        "#16a34a",
                        "#2563eb",
                        "#eab308",
                        "#dc2626",
                        "#9ca3af",
                    ]

                }]
            },


            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text:
                            'PON Signal Distribution'
                    }
                }
            }

        }
    );
}
function extractIP(ip) {
    if (!ip || ip === "N/A")
        return null;
    return ip.match(
        /\d+\.\d+\.\d+\.\d+/
    )?.[0];
}
function rxColor(rx) {
    const value = parseFloat(rx)
    if (isNaN(value))
        return "bg-gray-100 text-gray-600";

    if (value > -20)
        return "bg-green-100 text-green-700";

    if (value >= -23)
        return "bg-yellow-100 text-yellow-700";

    return "bg-red-100 text-red-700";
}

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

onMounted(() => {
    loadStats();
    loadUplink();
    loadDevices();
    setInterval(() => {
        loadStats();
        loadUplink();
        loadDevices();



    }, 30000);



});

</script>

<template>
    <div class="space-y-6">

        <!-- Warning -->
        <div v-if="!configured"
            class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-yellow-800">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>GenieACS belum dikonfigurasi.</span>
        </div>

        <template v-else>

            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                <!-- Total -->
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Devices</p>
                            <h2 class="mt-1 text-3xl font-semibold text-gray-800">{{ stats.total }}</h2>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-3 text-blue-600">
                            <i class="fa-solid fa-ethernet text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Online -->
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Online</p>
                            <h2 class="mt-1 text-3xl font-semibold text-green-600">{{ stats.online }}</h2>
                        </div>
                        <div class="rounded-lg bg-green-50 p-3 text-green-600">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Offline -->
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Offline</p>
                            <h2 class="mt-1 text-3xl font-semibold text-red-500">{{ stats.offline }}</h2>
                        </div>
                        <div class="rounded-lg bg-red-50 p-3 text-red-500">
                            <i class="fa-solid fa-circle-xmark text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Uptime -->
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Avg Uptime</p>
                            <h2 class="mt-1 text-3xl font-semibold text-orange-500">{{ stats.uptime }}%</h2>
                        </div>
                        <div class="rounded-lg bg-orange-50 p-3 text-orange-500">
                            <i class="fa-solid fa-clock text-xl"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- CHART -->
            <div class="grid gap-5 md:grid-cols-2">

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="flex items-center gap-2 font-semibold text-gray-700">
                            <i class="fa-solid fa-chart-pie text-gray-400"></i>
                            Device Overview
                        </h3>
                        <button @click="loadStats"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="fa-solid fa-refresh mr-1"></i>
                            Refresh
                        </button>
                    </div>
                    <div class="mx-auto max-w-sm">
                        <canvas ref="deviceChart"></canvas>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="flex items-center gap-2 font-semibold text-gray-700">
                            <i class="fa-solid fa-signal text-gray-400"></i>
                            Uplink Signal
                        </h3>
                        <button @click="loadUplink"
                            class="rounded-lg border border-gray-200 px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-50">
                            <i class="fa-solid fa-refresh mr-1"></i>
                            Refresh
                        </button>
                    </div>
                    <div class="mx-auto max-w-sm">
                        <canvas ref="uplinkChart"></canvas>
                    </div>
                </div>

            </div>

            <!-- TABLE -->
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-5 py-4 font-semibold text-gray-700">
                    <i class="fa-solid fa-list mr-2 text-gray-400"></i>
                    Recent Device Activity
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="p-3 text-left">SN</th>
                                <th class="p-3 text-left">MAC</th>
                                <th class="p-3 text-left">Type</th>
                                <th class="p-3 text-left">IP</th>
                                <th class="p-3 text-left">SSID</th>
                                <th class="p-3 text-left">PPPoE</th>
                                <th class="p-3 text-left">RX</th>
                                <th class="p-3 text-left">Temp</th>
                                <th class="p-3 text-center">Client</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="device in devices" :key="device.serial_number" class="hover:bg-gray-50">
                                <td class="p-3">
                                    <a class="font-medium text-blue-600 hover:underline"
                                        :href="'/admin/genieacs/devices/' + encodeURIComponent(device.device_id)">
                                        {{ device.serial_number }}
                                    </a>
                                </td>

                                <td class="p-3 text-gray-600">{{ device.mac_address }}</td>
                                <td class="p-3 text-gray-600">{{ device.product_class || 'N/A' }}</td>

                                <td class="p-3">
                                    <a v-if="extractIP(device.ip_tr069)" target="_blank"
                                        class="text-blue-600 hover:underline"
                                        :href="'http://' + extractIP(device.ip_tr069)">
                                        {{ extractIP(device.ip_tr069) }}
                                    </a>
                                    <span v-else class="text-gray-400">N/A</span>
                                </td>

                                <td class="p-3 text-gray-600">{{ device.wifi_ssid || '-' }}</td>
                                <td class="p-3 text-gray-600">{{ device.pppoe_username || 'N/A' }}</td>

                                <td class="p-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="rxColor(device.rx_power)">
                                        {{ device.rx_power || 'N/A' }} dBm
                                    </span>
                                </td>

                                <td class="p-3 text-gray-600">{{ device.temperature || '-' }}°C</td>

                                <td class="p-3 text-center">
                                    <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-600">
                                        {{ device.connected_devices_count || 0 }}
                                    </span>
                                </td>

                                <td class="p-3 text-center">
                                    <span v-if="device.status === 'online'"
                                        class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-600">
                                        ONLINE
                                    </span>
                                    <span v-else
                                        class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-500">
                                        OFFLINE
                                    </span>
                                </td>

                                <td class="p-3 text-center">
                                    <button @click="summon(device.device_id)"
                                        class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-gray-500 hover:bg-gray-50 hover:text-blue-600"
                                        title="Summon Device">
                                        <i class="fa-solid fa-bolt"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="devices.length === 0">
                                <td colspan="11" class="p-8 text-center text-gray-400">
                                    Tidak ada device
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        <!-- ToD0 -->
        <div v-if="showNotInMapModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-[420px] rounded-2xl bg-white p-6 text-center shadow-xl">

                <!-- Icon -->
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-map-location-dot text-2xl"></i>
                </div>

                <!-- Title -->
                <h2 class="mt-4 text-xl font-semibold text-gray-800">
                    ONU Belum Terdaftar
                </h2>

                <!-- Description -->
                <p class="mt-3 text-sm leading-6 text-gray-600">
                    Device dengan Serial Number
                    <span class="font-semibold text-gray-900">
                        {{ notInMapSerial }}
                    </span>
                    belum terdaftar di Network Map.
                </p>

                <p class="mt-2 text-sm text-gray-500">
                    Tambahkan ONU ini ke Network Map terlebih dahulu
                    agar lokasi topologi dapat ditampilkan.
                </p>

                <!-- Buttons -->
                <div class="mt-6 flex gap-3">
                    <button @click="showNotInMapModal = false"
                        class="flex-1 rounded-lg bg-gray-100 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                        Tutup
                    </button>

                    <button @click="window.open('/map.php', '_blank')"
                        class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                        Buka Network Map
                    </button>
                </div>

            </div>
        </div>

    </div>

    <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200"
        leave-from-class="opacity-100" leave-to-class="opacity-0">

        <div v-if="toast.show" class="fixed top-5 right-5 z-[9999] rounded-lg px-4 py-3 text-white shadow-lg" :class="{
            'bg-green-600': toast.type === 'success',
            'bg-red-600': toast.type === 'danger',
            'bg-blue-600': toast.type === 'info'
        }">

            {{ toast.message }}

        </div>

    </Transition>
</template>