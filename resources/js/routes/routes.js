import IndexComponent from '../../js/pages/IndexComponent.vue'
import Unauthorized from '../../js/pages/Unauthorized.vue'
import Dashboard from '../../js/pages/Dashboard.vue'

console.log('routes');
export default [
    {
        path: '/4M',
        component: '4M',
        components: {
            default: IndexComponent,
            dashboard: Dashboard,
        },
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: Dashboard,
            },
        ]
    }
];
