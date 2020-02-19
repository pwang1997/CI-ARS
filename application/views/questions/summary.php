<!-- Load d3.js -->
<!-- <script src="https://d3js.org/d3.v4.min.js"></script> -->
<script src="https://d3js.org/d3.v4.min.js"></script>

<h3><?= $title; ?></h3>
<!-- Create a div where the graph will take place -->
<div id="chart"></div>
<!-- <svg width="960" height="500"></svg> -->
<?php //print_r($question); 
?>

<script>
    $(document).ready(() => {
        var interval = 1000;
        function fetchData() {
            $.ajax({
                url: <?php echo "'" . base_url() . "questions/get_answered_question_instance/" . $question_instance_id . "'"; ?>,
                type: "POST",
                dataType: "JSON",
                data: {},
                success: function(response) {
                    // //get dataset from database
                    $('#chart').empty();
                    dataset = [];
                    frequency = [];

                    choices = <?php echo $question['choices']; ?>;
                    for (i = 0; i < choices.length; i++) {
                        frequency.push(0);
                    }
                    for (i = 0; i < response.dataset.length; i++) {
                        element = response.dataset[i]
                        element = element.replace("[", "").replace("]", "").split("'").join("").split(",")
                        dataset.push(element)
                    }
                    //get frequency of all choices
                    for (i = 0; i < dataset.length; i++) {
                        for (j = 0; j < dataset[i].length; j++) {
                            temp_index = choices.indexOf(dataset[i][j])
                            frequency[temp_index]++;
                        }
                    }
                    var url = <?php echo "'" . base_url() . "questions/get_answered_question_instance/" . $question_instance_id . "'"; ?>;
                    console.log(`choices: ${choices}`)
                    console.log(`frequency: ${frequency}`)
                    var data = [];
                    //building json 
                    for (var c in choices) {
                        temp_var = {};
                        temp_var['choice'] = choices[c]
                        temp_var['freq'] = frequency[c]

                        data.push(temp_var)

                    }
                    data1 = data;
                    data = JSON.stringify(data);
                    //graph bar chart
                    let margin = {
                            top: 35,
                            right: 145,
                            bottom: 35,
                            left: 45
                        },
                        width = 700 - margin.left - margin.right,
                        height = 400 - margin.top - margin.bottom;

                    // scale to ordinal because x axis is not numerical
                    var x = d3.scaleBand().rangeRound([0, width]).padding(0.1);

                    //scale to numerical value by height
                    var y = d3.scaleLinear().range([height, 0]);

                    var chart = d3.select("#chart").append("svg")
                        .attr("width", width + margin.left + margin.right)
                        .attr("height", height + margin.top + margin.bottom)
                        .append("g")
                        .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                        .attr("fill", "#002145");
                    var xAxis = d3.axisBottom(x); //orient bottom because x-axis will appear below the bars

                    var yAxis = d3.axisLeft(y);

                    // d3.json("coucou.json", function(error, data1) {
                    x.domain(data1.map(function(d) {
                        return d.choice
                    }));
                    y.domain([0, d3.max(data1, function(d) {
                        // console.log(d.freq);
                        return d.freq
                    })]);

                    var bar = chart.selectAll("g")
                        .data(data1)
                        .enter();

                    bar.append("rect")
                        .attr("y", function(d) {
                            // console.log(d)
                            return y(d.freq);
                        })
                        .attr("x", function(d, i) {
                            return x(d.choice);
                        })
                        .attr("height", function(d) {
                            return height - y(d.freq);
                        })
                        .attr("width", x.bandwidth()); //set width base on range on ordinal data

                    bar.append("text")
                        .attr("y", function(d) {
                            return y(d.freq) - 15;
                        })
                        .attr("x", function(d, i) {
                            return x(d.choice);
                        })
                        .attr("dy", ".75em")
                        .text(function(d) {
                            return d.freq;
                        });

                    chart.append("g")
                        .attr("class", "x axis")
                        .attr("transform", "translate(0," + height + ")")
                        .call(xAxis);

                    chart.append("g")
                        .attr("class", "y axis")
                        .call(yAxis)
                        .append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", 6)
                        .attr("dy", ".71em")
                        .style("text-anchor", "end")
                        .text("responses");
                    // });
                },
                fail: function(response) {
                    alert("failed to fetch dataset")
                },
                complete: function() {
                    setTimeout(fetchData, interval);
                }
            }); //end of ajax
        }
        setTimeout(fetchData(), interval);

    })
</script>