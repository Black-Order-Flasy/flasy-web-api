@props(['item'])


<form id="volunteerForm" method="POST" action="{{ route('volunteer.verify', $item) }}">
    @csrf
    <input id="input" name="input" type="hidden" value="">
    <button type="button" class="btn btn-xs btn-danger btn-flat show_confirm text-success" data-toggle="tooltip" title='verify' onclick="submitForm('verify')">
        <i class="fa-solid fa-check"></i> Verify
    </button>
    |
    <button type="button" class="btn btn-xs btn-danger btn-flat show_confirm text-error" data-toggle="tooltip" title='decline' onclick="submitForm('decline')">
        <i class="fa-solid fa-x"></i> Decline
    </button>
</form>

<script>
    function submitForm(action) {
        document.getElementById('input').value = action;
        document.getElementById('volunteerForm').submit();
    }
</script>
