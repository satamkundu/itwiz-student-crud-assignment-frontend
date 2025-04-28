<script>
    $(document).ready(function() {
        const token = localStorage.getItem('token');
        if (token) {
            window.location.href = "/students";            
        }
    });
</script>
