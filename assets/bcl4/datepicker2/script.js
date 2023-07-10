BclDatePicker2 =
{
    init : function()
    {
        document.querySelectorAll('.datetimepickers').forEach(function(elm){
            BclDatePicker2.initDatePickerComponent(elm);
        });
        $('body').on('change.datetimepicker', '.datetimepickers', function(){
            BclDatePicker2.onchange(this);
        });
    },
    initDatePickerComponent(self)
    {
        const eventId = 'change.datetimepicker';
        let options = {
            format: self.dataset.format,
            //Serve ad evitare l'autocompilazione con la data odierna se il campo è vuoto.
            useCurrent: false
        };
        if (self.dataset.widgetPositioning) {
            /*options['widgetPositioning'] = {
                vertical : self.dataset.widgetPositioning,
                horizontal: 'auto'
            };*/
        }
        let minDate = self.dataset.min;
        if (typeof minDate !== 'undefined') {
            if (minDate.charAt(0) === '#') {
                $(minDate).on(eventId, function (e) {
                    $(self).data("DateTimePicker").minDate(e.date);
                });
            } else {
                options['minDate'] = new Date(minDate);
            }
        }
        let maxDate = self.dataset.max;
        if (typeof maxDate !== 'undefined') {
            if (maxDate.charAt(0) === '#') {
                $(maxDate).on(eventId, function (e) {
                    $(self).data("DateTimePicker").maxDate(e.date);
                });
            } else {
                options['maxDate'] = new Date(maxDate);
            }
        }
        console.log(options);
        $(self).datetimepicker(options);
    },
    onchange : function(self)
    {
        if ($(self).hasClass('change-execute')) {
            Osynapsy.action.execute(self);
        }
        if (self.getAttribute('onchange')) {
            eval(self.getAttribute('onchange'));
        }
        console.log('datachange');
    }
};

if (window.Osynapsy){
    Osynapsy.plugin.register('BclDatePicker2',function(){
        BclDatePicker2.init();
    });
}