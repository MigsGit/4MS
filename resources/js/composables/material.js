import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMaterial(){
    const materialVar = reactive({
        materialSupplier : [],
        materialColor : [],
    });
    const frmMaterial = ref ({
        ecrsId : 'N/A',
        materialId : 'N/A',
        pdMaterial : 'N/A',
        msds : 'N/A',
        icp : 'N/A',
        gp : 'N/A',
        qoutation : 'N/A',
        remarks : 'N/A',
        materialSupplier : 'N/A',
        materialColor : 'N/A',
        rohs : 'N/A',
        materialSample : 'N/A',
        coc : 'N/A',
    });
    return {
        materialVar,
        frmMaterial,
    };
}
