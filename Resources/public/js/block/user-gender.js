function UserGenderBlock(options) {
    this.graph = options.graph;
    this.tab = options.tab;
    this.list  = options.list;
    this.init();
}

UserGenderBlock.prototype = {

    init: function() {
        if(jQuery(this.graph.id).length > 0) {
            this.generateChart();
        }

        if(jQuery(this.list.id).length > 0) {
            this.generateSparklink(this);
        }
    },

    generateSparklink: function(obj) {
        var sparkline_user_gender = null;
        jQuery('.'+this.tab.id).on('shown.bs.tab', function(e){
            if(sparkline_user_gender === null) {
                sparkline_user_gender = jQuery(obj.list.id).sparkline('html', {type: 'pie', height: '20px', width: '20px', sliceColors: [obj.list.dataGender.color, obj.list.dataTotal.color]});
            }
        });
    },
    generateChart:  function() {
        var user_gender_chart = c3.generate({
            bindto: this.graph.id,
            data: {
                json:  this.graph.data,
            type : 'pie',
                colors: {
                    'unknown': this.graph.colors.unknown,
                    'male':    this.graph.colors.male,
                    'female':  this.graph.colors.female
                },
            },
        });
    }
}
