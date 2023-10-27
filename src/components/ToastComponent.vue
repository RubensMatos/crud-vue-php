<script>
import { toast } from 'vue3-toastify';

export default {
    methods: {
        showSuccessToast() {
            toast.success("Notificação de sucesso!", {
                position: "top-right",
            });
        },
    },
};
</script>