import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMachine(){
    const machineVar = reactive({

        prdnAssessedBy : [],
        prdnCheckedBy : [],

        prAssessedBy : [],
        prCheckedBy : [],

        ppcAssessedBy : [],
        ppcCheckedBy : [],

        emsAssessedBy : [],
        emsCheckedBy : [],

        qcAssessedBy : [],
        qcCheckedBy : [],

        proEnggAssessedBy : [],
        proEnggCheckedBy : [],

        mainEnggAssessedBy : [],
        mainEnggCheckedBy : [],

        enggAssessedBy : [],
        enggCheckedBy : [],

        qaAssessedBy : [],
        qaCheckedBy : [],

    });
    const frmMachine = ref ({
        ecrsId : 'N/A',
    });
    return {
        machineVar,
        frmMachine,
    };
}
