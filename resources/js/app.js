import './bootstrap';

import { createApp } from 'vue'
import App from './views/layouts/App.vue'

// Router
import router  from './router'

createApp(App)
    .use(router)
    .mount("#app")
