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
                        :columns="ecrUserMasterColumns"
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
                                <!-- <th>Action</th> -->
                                <th>Status</th>
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
    import ModalComponent from '../components/ModalComponent.vue';
    import useSettings from '../composables/settings.js';
    import useForm from '../../js/composables/utils/useForm.js'
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    DataTable.use(DataTablesCore);
    const ecrUserMasterColumns = [
        // { data: 'get_action',  orderable: false, searchable: false },
        { data: 'get_status'},
        { data: 'name'},
        { data: 'email'},
        { data: 'get_departments'}
    ];
</script>
<style lang="scss" scoped>

</style>

