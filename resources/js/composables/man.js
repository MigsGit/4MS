import { ref, inject,reactive,nextTick,toRef } from 'vue'
import useFetch from './utils/useFetch';

export default function useMan(){
    const frmMan = ref ({});
    const manVar = reactive({
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
