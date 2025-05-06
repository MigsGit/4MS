<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">Man</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Man</li>
                    </ol>
                    <div class="table-responsive">
                    <!-- id="dataTable" -->
                    <!-- <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    </table> -->
                    <DataTable
                        width="100%" cellspacing="0"
                        class="table table-bordered mt-2"
                        ref="tblEcrByStatus"
                        :columns="columns"
                        ajax="api/load_ecr_by_status?status=AP"
                        :options="{
                            serverSide: true, //Serverside true will load the network
                            columnDefs:[
                                // {orderable:false,target:[0]}
                            ]
                        }"
                    >
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Status</th>
                                <th>ECR Ctrl No.</th>
                                <th>Category</th>
                                <th>Internal or External</th>
                                <th>Customer Name</th>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Device Name</th>
                                <th>Product Line</th>
                                <th>Section</th>
                                <th>Customer Ec. No</th>
                                <th>Date Of Request</th>
                            </tr>
                        </thead>
                    </DataTable>
                </div>
                </div>
            </div>
        </div>
    </div>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="ECR" @add-event="" ref="modalSaveEcr">
    <template #body>
            <div class="row">

            </div>
            <!-- Description of Change / Reason for Change -->
            <div class="card mb-2">

            </div>
            <!-- QA Dispositions -->
        </template>
        <template #footer>
            <button type="button" id= "closeBtn" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success btn-sm"><li class="fas fa-save"></li> Save</button>
        </template>
    </ModalComponent>
</template>

<script setup>
    import {ref , onMounted,reactive, toRef} from 'vue';
    import ModalComponent from '../../js/components/ModalComponent.vue';
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
    window.DataTable = DataTable.use(DataTablesCore)

    const tblEcrByStatus = ref(null);

    const columns = [
        {   data: 'get_actions',
            orderable: false,
            searchable: false,
            createdCell(cell){
                let btnGetEcrId = cell.querySelector('#btnGetEcrId');
                if(btnGetEcrId != null){
                    btnGetEcrId.addEventListener('click',function(){
                        let ecrId = this.getAttribute('ecr-id');

                    });
                }
            }
        } ,
        {   data: 'status'} ,
        {   data: 'ecr_no'} ,
        {   data: 'category'} ,
        {   data: 'internal_external'} ,
        {   data: 'customer_name'} ,
        {   data: 'part_no'} ,
        {   data: 'part_name'} ,
        {   data: 'device_name'} ,
        {   data: 'product_line'} ,
        {   data: 'section'} ,
        {   data: 'customer_ec_no'} ,
        {   data: 'date_of_request'} ,
    ];

    onMounted( async ()=>{
        // tblEcrByStatus.value.dt.ajax.url("api/load_ecr_by_status?status="+'AP').draw()
    })
</script>


