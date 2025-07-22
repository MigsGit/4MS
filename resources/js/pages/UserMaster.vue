<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">User Master</h4>
        <div class="card mt-3"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="table-responsive">
                    <!-- id="dataTable" -->
                    <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                    <DataTable
                        width="100%" cellspacing="0"
                        class="table mt-2"
                        ref="tblUserMaster"
                        :columns="userMasterColumns"
                        ajax="api/get_user_master"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                    >
                        <thead>
                            <tr>
                                <th>
                                    <font-awesome-icon class="nav-icon" icon="fa-cogs" />
                                </th>
                                <th>Roles</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                            </tr>
                        </thead>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
     import {
        onMounted,
        ref,
    } from 'vue'
    import Swal from 'sweetalert2';
    import ModalComponent from '../components/ModalComponent.vue';
    import useSettings from '../composables/settings.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';

    DataTable.use(DataTablesCore);
    const { axiosSaveData } = useForm(); // Call the useFetch function

    const tblUserMaster = ref(null);
    const userMasterColumns = [
        { data: 'get_action',
        orderable: false,
            searchable: false,
            createdCell(cell){
                let btnUserMasterDetails = cell.querySelector('#btnUserMasterDetails');
                if(btnUserMasterDetails !=null){
                    btnUserMasterDetails.addEventListener('click',function(){
                        let dataId = this.getAttribute('data-id');
                        Swal.fire({
                            title: 'Confirmation',
                            text: 'Are you sure you want this user to change role?',
                            icon: 'warning',
                            allowOutsideClick: false,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                saveUserApprover(dataId);
                            }
                        })
                    });
                }
            }
         },
        { data: 'get_roles'},
        { data: 'name'},
        { data: 'email'},
        { data: 'get_departments'}
    ];
    const saveUserApprover = async (userId) => {
        let formData = new FormData();
        formData.append('userId',userId)
        axiosSaveData(formData,'api/save_user_approver', (response) =>{
            tblUserMaster.value.dt.draw();
        });
    }
</script>
<style lang="scss" scoped>

</style>

