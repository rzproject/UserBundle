function UserAgeDemographicsBlock(options) {
    this.pie = options.pie;
    this.bar = options.bar;
    this.tab = options.tab;
    this.list  = options.list;
    this.init();
}

UserAgeDemographicsBlock.prototype = {

    init: function() {
        if(jQuery(this.pie.id).length > 0) {
            this.generatePieChart();
        }

        if(jQuery(this.bar.id).length > 0) {
            this.generateBarChart(this);
        }

        if(jQuery(this.list.id).length > 0) {
            this.generateSparklink(this);
        }
    },

    generateSparklink: function(obj) {
        var sparkline_user_age_demographics = null;
        jQuery('.'+this.tab.id).on('shown.bs.tab', function(e){
            if(sparkline_user_age_demographics === null) {
                sparkline_user_age_demographics = jQuery(obj.list.id).sparkline('html', {type: 'pie', height: '20px', width: '20px', sliceColors: [obj.list.dataGender.color, obj.list.dataTotal.color]});
            }
        });
    },
    generatePieChart:  function() {
        var user_age_demographics_pie_chart = c3.generate({
            bindto: this.pie.id,
            data: {
                json:  this.pie.data,
                type : 'pie',
                colors: this.pie.colors
            },
        });
    },

    generateBarChart:  function() {
        var total = this.bar.total;
        var user_age_demographics_bar_chart = c3.generate({
            bindto: this.bar.id,
            data: {
                x : 'x',
                json:   this.bar.data,
                type:   'bar',
                names:  this.bar.names,
                groups: this.bar.groups,
                colors: this.bar.colors
            },
            axis: {
                rotated: true,
                x: {
                    type: 'category',
                    tick: {
                        fit: true
                    },
                    label: this.bar.axis.x.label
                },
                y: {
                    tick: {
                        format: d3.format('d')
                    },
                    label: this.bar.axis.y.label
                }
            },
            tooltip: {
                contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
                    var $$ = this, config = $$.config,
                        titleFormat = config.tooltip_format_title || defaultTitleFormat,
                        nameFormat = config.tooltip_format_name || function (name) { return name; },
                        valueFormat = config.tooltip_format_value || defaultValueFormat,
                        text, i, title, value, name, bgcolor,
                        orderAsc = $$.isOrderAsc();

                    if (config.data_groups.length === 0) {
                        d.sort(function(a, b){
                            var v1 = a ? a.value : null, v2 = b ? b.value : null;
                            return orderAsc ? v1 - v2 : v2 - v1;
                        });
                    } else {
                        var ids = $$.orderTargets($$.data.targets).map(function (i) {
                            return i.id;
                        });
                        d.sort(function(a, b) {
                            var v1 = a ? a.value : null, v2 = b ? b.value : null;
                            if (v1 > 0 && v2 > 0) {
                                v1 = a ? ids.indexOf(a.id) : null;
                                v2 = b ? ids.indexOf(b.id) : null;
                            }
                            return orderAsc ? v1 - v2 : v2 - v1;
                        });
                    }

                    var sum = 0;

                    for (i = 0; i < d.length; i++) {
                        if (! (d[i] && (d[i].value || d[i].value === 0))) { continue; }

                        if (! text) {
                            title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                            text = "<table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
                        }

                        value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index, d);
                        sum += d[i].value;
                        if (value !== undefined) {
                            // Skip elements when their name is set to null
                            if (d[i].name === null) { continue; }
                            name = nameFormat(d[i].name, d[i].ratio, d[i].id, d[i].index);
                            bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                            text += "<tr class='" + $$.CLASS.tooltipName + "-" + $$.getTargetSelectorSuffix(d[i].id) + "'>";
                            text += "<td class='name'><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
                            text += "<td class='value'>" + value + "</td>";
                            text += "</tr>";
                        }
                    }
                    text += "<tr class='" + $$.CLASS.tooltipName + "-total'>";
                    text += "<td class='name'><b>"+total.label+"</b></td>";
                    text += "<td class='value'>" + sum + "</td>";
                    text += "</tr>";
                    return text + "</table>";
                }
            }
        });
    }

}
