import IndexComponent from '../../js/pages/IndexComponent.vue'
import Unauthorized from '../../js/pages/Unauthorized.vue'
import Dashboard from '../../js/pages/Dashboard.vue'

console.log('routes');
export default [
    {
        path: '/',
        component: Dashboard,
        children: [
            {
                path: '/',
                component: Dashboard,
            },
        ]
    },
];
