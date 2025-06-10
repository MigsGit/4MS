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
import useFetch from '../../js/composables/utils/useFetch';
const { axiosFetchData } = useFetch(); // Call  the useFetch function

function checkIfSessionExist(to, from, next) {
    let apiParams;
    axiosFetchData(apiParams,'api/check_session',function(response){
        if(response.data === 1){
            next();
        }else{
            return window.location.href = '/RapidX';
        }
    });
}
export default [
    {
        path: '/4M',
        component: '4M',
        beforeEnter: checkIfSessionExist,
        components: {
            default: IndexComponent,
            dashboard: Dashboard,
        },
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                beforeEnter: checkIfSessionExist,
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
