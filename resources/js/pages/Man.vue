<template>
    <div class="container-fluid px-4">
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Man
                    </h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Man</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <ModalComponent icon="fa-user" modalDialog="modal-dialog modal-lg" title="ECR" @add-event="" ref="modalSaveEcr">
    <template #body>
            <div class="row">
                <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <input type="hidden" class="form-control form-control" aria-describedby="addon-wrapping">
                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Category:</span>
                        <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                            <option value="" selected disabled>N/A</option>
                        </select>
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer Name:</span>
                        <input type="text" class="form-control form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Part Name:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Product Line:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Section:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Internal / External:</span>
                        <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                            <option value="" selected disabled>N/A</option>
                        </select>
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Part Number:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Device Name:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Customer EC No. (If any):</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                    <div class="input-group flex-nowrap mb-2 input-group-sm">
                        <span class="input-group-text" id="addon-wrapping">Date of Request:</span>
                        <input type="text" class="form-control" aria-describedby="addon-wrapping">
                    </div>
                </div>
            </div>
            <!-- Description of Change / Reason for Change -->
            <div class="card mb-2">
                    <h5 class="mb-0">
                        <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                            Description of Change / Reason for Change
                        </button>
                    </h5>
                <div id="collapse1" class="collapse" data-bs-parent="#accordionMain">
                        <div class="card-body shadow">
                            <div class="row">
                <div class="col-12">
                    <button @click="addRowSaveDocuments"type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Reason</button>
                </div>
                <div class="col-12 overflow-auto">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col" style="width: 30%;">Description of Change</th>
                            <th scope="col" style="width: 30%;">Reason of Change</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr >
                                <td>
                                   1
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                        <option value="" selected disabled>N/A</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" aria-describedby="addon-wrapping">
                                        <option value="" selected disabled>N/A</option>
                                    </select>
                                    <!-- <MultiselectElement
                                        v-model="rowSaveDocument.approverName"
                                        :close-on-select="true"
                                        :searchable="true"
                                        :options="edocsVar.optApproverName"
                                    /> -->
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                        <li class="fa fa-trash"></li>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                        </div>
                </div>
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
    import ModalComponent from '../../js/components/ModalComponent.vue';
    
</script>


