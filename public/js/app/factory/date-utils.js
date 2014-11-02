app.factory('DateUtils', function() {
    return {
        sqlDateTimeToLocaleFormat: function(string) {
            return moment(string, 'YYYY-MM-DD HH:mm:ss').format('D MMMM YYYY');
        }
    };
});