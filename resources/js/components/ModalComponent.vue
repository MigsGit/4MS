<template>
    <div class="modal fade" ref="modalRef" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div :class="modalDialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <font-awesome-icon :icon="icon"/>&nbsp<h5 class="modal-title" id="staticBackdropLabel"> {{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="$emit('addEvent')" id="formSystemDevelopment" @click="$emit('clickEvent')">
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {defineProps, ref, onMounted} from 'vue'

    const props = defineProps({
        modalDialog: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            default: '',
        },
    })

    //Parent Modal Reference
    //Required to inistialized the modal to the child components
    const modalRef = ref(null);
    let modalInstance = null;

    onMounted(() => {
        console.log(modalRef.value);

        modalInstance = new Modal(modalRef.value);
    });

    const showModal = () => modalInstance?.show();
    const hideModal = () => modalInstance?.hide();

    // Expose modal functions and ref to the parent
    defineExpose({ showModal, hideModal, modalRef });
</script>

<style lang="scss" scoped>


</style>
