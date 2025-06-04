import IndexComponent from '../../js/pages/IndexComponent.vue'
import Unauthorized from '../../js/pages/Unauthorized.vue'
import Dashboard from '../../js/pages/Dashboard.vue'
import Ecr from '../../js/pages/Ecr.vue'
import Man from '../../js/pages/Man.vue'
import Material from '../../js/pages/Material.vue'
import Machine from '../../js/pages/Machine.vue'
import Method from '../../js/pages/Method.vue'
import Environment from '../../js/pages/Environment.vue'
import UserMaster from '../../js/pages/UserMaster.vue'
import DropdownMaster from '../../js/pages/DropdownMaster.vue'

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
            {
                path: 'ecr',
                name: 'Ecr',
                component: Ecr,
            },
            {
                path: 'man',
                name: 'Man',
                component: Man,
            },
            {
                path: 'material',
                name: 'Material',
                component: Material,
            },
            {
                path: 'machine',
                name: 'Machine',
                component: Machine,
            },
            {
                path: 'machine',
                name: 'Machine',
                component: Machine,
            },
            {
                path: 'method',
                name: 'Method',
                component: Method,
            },
            {
                path: 'environment',
                name: 'Environment',
                component: Environment,
            },
            {
                path: 'user_master',
                name: 'UserMaster',
                component: UserMaster,
            },
            {
                path: 'dropdown_master',
                name: 'DropdownMaster',
                component: DropdownMaster,
            },
        ]
    }
];
