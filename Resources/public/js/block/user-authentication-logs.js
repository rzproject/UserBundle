function UserAuthBlock(options) {
    this.graph = options.graph;
    this.tab = options.tab;
    this.list  = options.list;
    this.init();
}

UserAuthBlock.prototype = {

    init: function() {
        if(jQuery(this.graph.id).length > 0) {
            this.generateChart();
        }

        if(jQuery(this.list.id).length > 0) {
            this.generateSparklink(this);
        }
    },

    generateSparklink: function(obj) {
        var sparkline_user_auth_log = null;
        jQuery('.'+this.tab.id).on('shown.bs.tab', function(e){
            if(sparkline_user_auth_log === null) {
                console.log('here');
                sparkline_user_auth_log = jQuery(obj.list.id).sparkline('html', {type: 'pie', height: '20px', width: '20px', sliceColors: [obj.list.dataU.color, obj.list.dataM.color, obj.list.dataF.color]});
            }
        });
    },
    generateChart:  function() {
        var total = this.graph.total;
        var user_authentication_logs_chart = c3.generate({
            bindto: this.graph.id,
            data: {
                x : 'x',
                json: this.graph.data,
                type: 'bar',
                names: {
                    dataU: this.graph.dataU.label,
                    dataM: this.graph.dataM.label,
                    dataF: this.graph.dataF.label
                },
                groups: [
                    ['dataU', 'dataM', 'dataF', 'total']
                ],
                colors: {
                    dataU: this.graph.dataU.color,
                    dataM: this.graph.dataM.color,
                    dataF: this.graph.dataF.color
                },
            },
            axis: {
                x: {
                    type: 'category',
                        tick: {
                        fit: true
                    },
                    label: this.graph.axis.x.label
                },
                y: {
                    tick: {
                        format: d3.format('d')
                    },
                    label: this.graph.axis.y.label
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
