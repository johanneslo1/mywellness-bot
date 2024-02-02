import {watch} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {useToast} from 'primevue/usetoast';

export function useToastWatcher() {

    watch(() => usePage().props.flash, (flash) => {
        console.log(flash)
        if (flash) {
            const toast = useToast();
            if (flash.success) {
                toast.add({ severity: 'success', summary: 'Erfolgreich!', detail: flash.success, life: 3000 });
            } else if (flash.error) {
                toast.add({ severity: 'error', summary: 'Fehler!', detail: flash.error, life: 3000 });
            } else if (flash.info) {
                toast.add({ severity: 'info', summary: 'Info!', detail: flash.info, life: 3000 });
            }
        }
    }, {immediate: true,});

}
