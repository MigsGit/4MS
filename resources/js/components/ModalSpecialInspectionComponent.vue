<template>
   <div class="row">
        <div class="input-group flex-nowrap mb-2 input-group-sm">
            <span class="input-group-text" id="addon-wrapping">ECR Id:</span>
            <input v-model="frmSpecialInspection.ecrsId" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
        </div>
        <div class="input-group flex-nowrap mb-2 input-group-sm">
            <span class="input-group-text" id="addon-wrapping">Special Inspection Id:</span>
            <input  v-model="frmSpecialInspection.specialInspectionsId"  type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
        </div>
        <div class="col-sm-6">
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Product Detail:</span>
                <input v-model="frmSpecialInspection.productDetail" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Lot Qty:</span>
                <input v-model="frmSpecialInspection.lotQty" type="number" class="form-control form-control-lg" aria-describedby="addon-wrapping">
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Mod:</span>
                <input v-model="frmSpecialInspection.mod" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Inspection Date:</span>
                <input v-model="frmSpecialInspection.inspectionDate" type="date" class="form-control form-control-lg" aria-describedby="addon-wrapping">
            </div>

            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Remarks:</span>
                <textarea v-model="frmSpecialInspection.remarks" class="form-control form-control-lg" aria-describedby="addon-wrapping">
                </textarea>
            </div>
            </div>
        <div class="col-sm-6">
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Inspector:</span>
                <Multiselect
                    v-model="frmSpecialInspection.inspector"
                    :options="commonVar.optUserMaster"
                    placeholder="Select an option"
                    :searchable="true"
                    :close-on-select="true"
                />
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Samples:</span>
                <input v-model="frmSpecialInspection.samples" type="text" class="form-control form-control-lg" aria-describedby="addon-wrapping">
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Mod Qty:</span>
                <input v-model="frmSpecialInspection.modQty" type="int" class="form-control form-control-lg" aria-describedby="addon-wrapping">
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">Judgement:</span>
                <Multiselect
                    v-model="frmSpecialInspection.judgement"
                    :options="commonVar.optJudgment"
                    placeholder="Select an option"
                    :searchable="true"
                    :close-on-select="true"
                />
            </div>
            <div class="input-group flex-nowrap mb-2 input-group-sm">
                <span class="input-group-text" id="addon-wrapping">LQC Section Head:</span>
                <Multiselect
                    v-model="frmSpecialInspection.lqcSectionHead"
                    :options="commonVar.optUserMaster"
                    placeholder="Select an option"
                    :searchable="true"
                    :close-on-select="true"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
    import {defineProps} from 'vue'

    const props = defineProps({
        frmSpecialInspection: {
            required: true,
        },
        commonVar: {
            required: true,
        },
    })

</script>

