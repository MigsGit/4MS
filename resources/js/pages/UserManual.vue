<template>
    <div class="container-fluid px-4">
        <h4 class="mt-4">User Manual</h4>
        <div class="card mt-5"  style="width: 100%;">
            <div class="card-body overflow-auto">
                <div class="container-fluid px-4">
                    <div class="row justify-content-between">
                        <div class="col-md-2 mt-3">
                            <button type="button" @click="btnUserManual" class="btn btn-outline-danger btn-sm mb-2">
                                <font-awesome-icon class="nav-icon" icon="fa-file-pdf"/>&nbsp; View User Manual</button>
                        </div>
                        <div class="col-md-2 mt-3">
                            <button type="button" @click="btnProcess" class="btn btn-outline-primary btn-sm mb-2">
                                <font-awesome-icon class="nav-icon" icon="fa-file-powerpoint" />&nbsp; View ECR/4M1E Process</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref , onMounted,reactive, toRef} from 'vue';
    const btnUserManual = () => {
        window.open('./storage/app/public/user_manual/ecr_4m_user_manual.pdf', '_blank');
    }
    const btnProcess = () => {
        window.open('./storage/app/public/user_manual/ecr_4m_process.pptx', '_blank');
    }
</script>