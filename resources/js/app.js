require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import '@fortawesome/fontawesome-free/js/all.js';
import 'tw-elements';
import VueChartkick from 'vue-chartkick'
import 'chartkick/chart.js'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(VueChartkick)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
