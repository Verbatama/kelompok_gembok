    <script setup>
    import { reactive, ref, onMounted } from "vue";

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

    const loading = ref(false);

    async function loadTelegramConfiguration() {
        const result = await fetchAPI("/api/test-telegram.php");

        if (result.success) {
            Object.assign(telegramForm, result.data);
        }
    }

    async function loadGenieACSConfiguration() {
        const result = await fetchAPI("/api/test-genieacs.php");

        if (result.success) {
            Object.assign(genieacsForm, result.data);
        }
    }

    async function loadMikrotikConfiguration() {
        const result = await fetchAPI("/api/test-mikrotik.php");

        if (result.success) {
            Object.assign(mikrotikForm, result.data);
        }
    }

    onMounted(() => {
        loadTelegramConfiguration();
        loadGenieACSConfiguration()
        loadMikrotikConfiguration()
    });
  
    // Forms


    const passwordForm = reactive({
        current_password: "",
        new_username: "",
        new_password: "",
        confirm_password: "",
    });

    const genieacsForm = reactive({
        host: "",
        port: 7557,
        username: "",
        password: "",
    });

    const mikrotikForm = reactive({
        host: "",
        port: 8728,
        username: "",
        password: "",
    });

    const telegramForm = reactive({
        bot_token: "",
        chat_id: "",
    });


    // Helpers
  
    function toast(message) {
        alert(message); // ganti nanti dengan SweetAlert / Toastify
    }


    // Password
   

    async function changePassword() {
        if (
            passwordForm.new_password &&
            passwordForm.new_password !== passwordForm.confirm_password
        ) {
            toast("Konfirmasi password tidak sama");
            return;
        }

        if (
            !passwordForm.new_password &&
            passwordForm.confirm_password
        ) {
            toast("Isi password baru terlebih dahulu");
            return;
        }

        const result = await fetchAPI(
            "/api/update-password.php",
            passwordForm
        );

        if (result.success) {
            toast(result.message);

            Object.assign(passwordForm, {
                current_password: "",
                new_username: "",
                new_password: "",
                confirm_password: "",
            });
        } else {
            toast(result.message || "Gagal mengupdate kredensial");
        }
    }

 
    // Test Connection
 

    async function testGenieACS() {
        const result = await fetchAPI(
            "/api/test-genieacs.php",
            {
                method: "POST",
                body: JSON.stringify(genieacsForm)
            }
        );

        if (result.success) {
            toast(result.message);
        } else {
            toast(result.message || "Koneksi gagal");
        }
    }

    async function testMikroTik() {
        const result = await fetchAPI(
            "/api/test-mikrotik.php",
            {
                method: "POST",
                body: JSON.stringify(mikrotikForm)
            }
        );

        toast(result.message);
    }

    async function testTelegram() {
        const result = await fetchAPI(
            "/api/test-telegram.php",
            {
                method: "POST",
                body: JSON.stringify(telegramForm)
            }
        );

        toast(result.message);
    }

    // Save Configuration
  

    async function saveGenieACS() {
        const result = await fetchAPI(
            "/api/save-genieacs.php",
            {
                method: "POST",
                body: JSON.stringify(genieacsForm)
            }
        );

        if (result.success) {
            toast(result.message);
        } else {
            toast(result.message || "Gagal menyimpan konfigurasi");
        }
    }

    async function saveMikroTik() {
        const result = await fetchAPI(
            "/api/save-mikrotik.php",
            {
                method: "POST",
                body: JSON.stringify(genieacsForm)
            }
        );

        if (result.success) {
            toast(result.message);
        } else {
            toast(result.message || "Gagal menyimpan konfigurasi");
        }
    }

    async function saveTelegram() {
        const result = await fetchAPI(
            "/api/save-telegram.php",
            {
                method: "POST",
                body: JSON.stringify(genieacsForm)
            }
        );

        if (result.success) {
            toast(result.message);
        } else {
            toast(result.message || "Gagal menyimpan konfigurasi");
        }
    }
</script>
    <template>
        <div class="space-y-6">

          

            <!-- GenieACS -->
        
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <div class="rounded-lg bg-purple-50 p-2.5 text-purple-600">
                        <i class="fa-solid fa-server text-lg  text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">GenieACS</h3>
                        <p class="text-sm text-gray-400">Konfigurasi koneksi ke server ACS</p>
                    </div>
                </div>

                <form @submit.prevent="testGenieACS" class="space-y-4">

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Host</label>
                            <input v-model="genieacsForm.host" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Port</label>
                            <input v-model="genieacsForm.port" type="number"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Username</label>
                            <input v-model="genieacsForm.username" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Password</label>
                            <input v-model="genieacsForm.password" type="password"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-1">
                        <button
                            class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="loading">
                            Test Connection
                        </button>

                        <button type="button"
                            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                            @click="saveGenieACS" :disabled="loading">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>

  
            <!-- MikroTik -->

            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <div class="rounded-lg bg-orange-50 p-2.5 text-orange-500">
                        <i class="fa-solid fa-network-wired text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">MikroTik</h3>
                        <p class="text-sm text-gray-400">Konfigurasi koneksi ke router MikroTik</p>
                    </div>
                </div>

                <form @submit.prevent="testMikroTik" class="space-y-4">

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Host</label>
                            <input v-model="mikrotikForm.host" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Port</label>
                            <input v-model="mikrotikForm.port" type="number"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Username</label>
                            <input v-model="mikrotikForm.username" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Password</label>
                            <input v-model="mikrotikForm.password" type="password"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-1">
                        <button
                            class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="loading">
                            Test Connection
                        </button>

                        <button type="button"
                            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                            @click="saveMikroTik" :disabled="loading">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>


            <!-- Telegram -->
        
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <div class="rounded-lg bg-sky-50 p-2.5 text-sky-500">
                        <i class="fa-brands fa-telegram text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Telegram</h3>
                        <p class="text-sm text-gray-400">Notifikasi via bot Telegram</p>
                    </div>
                </div>

                <form @submit.prevent="testTelegram" class="space-y-4">

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Bot Token</label>
                            <input v-model="telegramForm.bot_token" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-600">Chat ID</label>
                            <input v-model="telegramForm.chat_id" type="text"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100 text-gray-900"
                                required>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-1">
                        <button
                            class="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="loading">
                            Test Connection
                        </button>

                        <button type="button"
                            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                            @click="saveTelegram" :disabled="loading">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </template>