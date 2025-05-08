import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMan(){
    const frmMan = ref ({
        firstAssign : 'N/A',
        longInterval : 'N/A',
        change : 'N/A',
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
    const manVar = reactive({
        optUserMaster:[],
        optYesNo : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"YES","label":"YES"},
            {"value":"NO","label":"NO"},
        ]
    })

    return {
        frmMan,
        manVar,
    };
}
