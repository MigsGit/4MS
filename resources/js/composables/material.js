import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMaterial(){
    const frmMaterial = ref ({
        firstAssign : '',
        longInterval : '',
        change : '',
        processName : '',
        workingTime : '',
        trainer : '',
        qcInspectorOperator : '',
        trainerSampleSize : '',
        trainerResult : '',
        lqcSupervisor : '',
        lqcSampleSize : '',
        lqcResult : '',
        processChangeFactor : '',
    });
    return {
        frmMaterial,
    };
}
