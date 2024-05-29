function submitForm() {
    var category = document.getElementById('category-select').value;
    var year = document.getElementById('year-select').value;
    var driver = document.getElementById('driver-select').value;
    $.ajax({
        type: 'POST',
        url: '../wp-content/plugins/statsracing/includes/shortcodes/dataf1/test.php', 
        data: {
            category: category,
            year: year,
            driver: driver
        },
        success: function(response) {
            console.log(response);
        },
        error: function(error) {
            console.error(error);
        }
    });
}
