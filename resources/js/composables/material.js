import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMaterial(){
    const materialVar = ref({
        optDropdownSetting : '',
    });
    const frmMaterial = ref ({
        ecrsId : '',
        materialId : '',
        pdMaterial : '',
        msds : '',
        icp : '',
        gp : '',
        qoutation : '',
        remarks : '',
        materialSupplier : '',
        materialColor : '',
        rohs : '',
        materialSample : '',
        coc : '',
    });
    return {
        materialVar,
        frmMaterial,
    };
}
