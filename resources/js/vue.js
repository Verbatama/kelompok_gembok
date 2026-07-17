// lazy loading
import { createApp } from 'vue'
const components = {
    GenieDashPage: () => import('./components/GenieDashPage.vue'),
    ConfigurationPage: () => import('./components/ConfigurationPage.vue'),
    DevicesPage: () => import('./components/DevicesPage.vue'),
    DevicesDetailPage: () => import('./components/DevicesDetailPage.vue'),
}

document.querySelectorAll('[data-vue]').forEach(async (el) => {
    const componentName = el.dataset.vue

    if (components[componentName]) {
        const module = await components[componentName]()
        createApp(module.default, {
            ...el.dataset,
        }).mount(el)
    }
})