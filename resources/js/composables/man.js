import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMan(){
    const frmMan = ref ({
        firstAssign : '',
        longInterval : '',
        pdMaterial : '',
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
        ],
        optResult : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"OK","label":"OK"},
            {"value":"NG","label":"NG"},
        ],
        optJudgment : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"PASSED","label":"PASSED"},
            {"value":"FAILED","label":"FAILED"},
        ],
        optCheck : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"C","label":"√"},
            {"value":"X","label":"X"},
        ],
        optConditions : [
            {"value":"","label":"-Select an option-"},
            {"value":"N/A","label":"N/A"},
            {"value":"R","label":"REQUIRED"},
            {"value":"NR","label":"NOT REQUIRED"},
        ]
    })

    return {
        frmMan,
        manVar,
    };
}
