<template>
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
                        <button v-show="isSelectReadonly === false" @click="$emit('addEcrReasonRowsEvent')" type="button" class="btn btn-primary btn-sm mb-2" style="float: right !important;"><i class="fas fa-plus"></i> Add Reason</button>
                    </div>
                    <div class="col-12 overflow-auto" style="height: 300px;">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col" style="width: 60%;">Description of Change</th>
                                <th scope="col" style="width: 60%;">Reason of Change</th>
                                <th scope="col" v-show="isSelectReadonly === false">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr v-for="(frmEcrReasonRow, index) in frmEcrReasonRows" :key="frmEcrReasonRow.index">
                                    <td>
                                        {{index+1}}
                                    </td>
                                    <td>
                                        <Multiselect
                                            v-model="frmEcrReasonRow.descriptionOfChange"
                                            :options="optDescriptionOfChange"
                                            placeholder="Select an option"
                                            :searchable="true"
                                            :close-on-select="true"
                                            :disabled="isSelectReadonly"
                                        />
                                    </td>
                                    <td>
                                        <Multiselect
                                            v-model="frmEcrReasonRow.reasonOfChange"
                                            :close-on-select="true"
                                            :searchable="true"
                                            :options="optReasonOfChange"
                                            placeholder="Select an option"
                                            :disabled="isSelectReadonly"
                                        />
                                    </td>
                                    <td>
                                        <button v-show="isSelectReadonly === false" @click="$emit('removeEcrReasonRowsEvent')" class="btn btn-danger btn-sm" type="button" data-item-process="add">
                                            <font-awesome-icon class="nav-icon" icon="fas fa-trash" />
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
</template>

<script setup>
    import {defineProps, ref, onMounted} from 'vue'
     const props = defineProps({
        frmEcrReasonRows: {
            required: true,
        },
        optDescriptionOfChange: {
            required: true,
        },
        optReasonOfChange: {
            required: true,
        },
        isSelectReadonly: {
            // required: true,
        },

    })
</script>
