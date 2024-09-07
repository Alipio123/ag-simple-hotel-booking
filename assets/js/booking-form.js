jQuery(document).ready(function($) {
    // Initialize datepickers for check-in and check-out dates
    $('#checkin_date, #checkout_date').datepicker({
        dateFormat: 'yy-mm-dd', // Adjust the date format as needed
        minDate: 0, // Disable past dates
        changeMonth: true,
        changeYear: true,
        onSelect: function(selectedDate) {
            if (this.id === 'checkin_date') {
                var checkinDate = $('#checkin_date').datepicker('getDate');
                $('#checkout_date').datepicker('option', 'minDate', checkinDate);
            }
        }
    });
});