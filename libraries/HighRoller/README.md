# HighRoller - Object-oriented PHP Wrapper for the Highcharts JavaScript Library

HighRoller gets Highcharts up and running in your PHP project fast.

* HighRoller [Home Page](http://highroller.io)
* Gravity [Home Page](http://gravity.com)

## Features

* Compatible with Highcharts v2.1.6 (and higher)
* Supports all default Highchart JS chart types:
  * Area
  * AreaSpline
  * Bar
  * Column
  * Line
  * Pie
  * Scatter
  * Spline
* Supports jquery and mootools
* Support for setting the location of your Highcharts library and theme
* Includes Auto-stepping for xAxis labels
* Supports Highcharts formatters using native JS

## Examples

### Hello HighRoller

A most humble line chart...

    <?php
    require_once('/HighRoller/HighRoller.php');
    require_once('/HighRoller/HighRollerSeriesData.php');
    require_once('/HighRoller/HighRollerLineChart.php');

    $chartData = array(5324, 7534, 6234, 7234, 8251, 10324);

    $linechart = new HighRollerLineChart();
    $linechart->chart->renderTo = 'linechart';
    $linechart->title->text = 'Line Chart';

    $series1 = new HighRollerSeriesData();
    $series1->addName('myData')->addData($chartData);

    $linechart->addSeries($series1);
    ?>

    <head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <!-- HighRoller: set the location of Highcharts library -->
    <?php echo HighRoller::setHighChartsLocation("/highcharts/highcharts.js");?>
    </head>

    <body>
    <div id="linechart"></div>

    <script type="text/javascript">
      <?php echo $linechart->renderChart();?>
    </script>
    </body>

#### ...or, render with mootools...

    <?php echo $linechart->renderChart('mootools');?>

### Extremely Customized Multi-series Line Chart

A very customised example using dates for xAxis labels, autostep, a custom Highcharts theme and native javascript formatters.

    <?php
    require_once('/HighRoller/HighRoller.php');
    require_once('/HighRoller/HighRollerSeriesData.php');
    require_once('/HighRoller/HighRollerLineChart.php');

    for($i = 0; $i <= 50; $i++){
      $chartData[0][] = rand(4000,8000);
      $chartData[1][] = rand(5000,15000);
      $chartData[2][] = rand(250,4000);
      $categories[$i] = 'Label-' . $i;
    }

    $series1 = new HighRollerSeriesData();
    $series1->addName('Finance')->addColor('#ff9900')->addData($chartData[0]);
    $series2 = new HighRollerSeriesData();
    $series2->addName('Google')->addColor('#0099ff')->addData($chartData[1]);
    $series3 = new HighRollerSeriesData();
    $series3->addName('Apple')->addColor('#00cc00')->addData($chartData[2]);
    $linechart = new HighRollerLineChart();
    $linechart->title->text = "Most Popular Topics";
    $linechart->title->align = "left";
    $linechart->title->floating = true;
    $linechart->title->style->font = '18px Metrophobic, Arial, sans-serif';
    $linechart->title->style->color = '#0099ff';
    $linechart->title->x = 20;
    $linechart->title->y = 20;
    $linechart->chart->renderTo = 'linechart';
    $linechart->chart->width = 500;
    $linechart->chart->height = 300;
    $linechart->chart->marginTop = 60;
    $linechart->chart->marginLeft = 90;
    $linechart->chart->marginRight = 30;
    $linechart->chart->marginBottom = 110;
    $linechart->chart->spacingRight = 10;
    $linechart->chart->spacingBottom = 15;
    $linechart->chart->spacingLeft = 0;
    $linechart->chart->backgroundColor->linearGradient = array(0,0,0,300);
    $linechart->chart->backgroundColor->stops = array(array(0,'rgb(217, 217, 217)'),array(1,'rgb(255, 255, 255)'));
    $linechart->chart->alignTicks = false;
    $linechart->legend->enabled = true;
    $linechart->legend->layout = 'horizontal';
    $linechart->legend->align = 'center';
    $linechart->legend->verticalAlign = 'bottom';
    $linechart->legend->itemStyle = array('color' => '#222');
    $linechart->legend->backgroundColor->linearGradient = array(0,0,0,25);
    $linechart->legend->backgroundColor->stops = array(array(0,'rgb(217, 217, 217)'),array(1,'rgb(255, 255, 255)'));
    $linechart->tooltip->formatter = new HighRollerFormatter(); // TOOLTIP FORMATTER
    $linechart->tooltip->backgroundColor->linearGradient = array(0,0,0,50);
    $linechart->tooltip->backgroundColor->stops = array(array(0,'rgb(217, 217, 217)'),array(1,'rgb(255, 255, 255)'));
    $linechart->plotOptions->line->pointStart = strtotime('-30 day') * 1000;
    $linechart->plotOptions->line->pointInterval = 24 * 3600 * 1000; // one day
    $linechart->xAxis->type = 'datetime';
    $linechart->xAxis->tickInterval = $linechart->plotOptions->line->pointInterval;
    $linechart->xAxis->startOnTick = true;
    $linechart->xAxis->tickmarkPlacement = 'on';
    $linechart->xAxis->tickLength = 10;
    $linechart->xAxis->minorTickLength = 5;
    $linechart->xAxis->labels->align = 'right';
    $linechart->xAxis->labels->step = 2;
    $linechart->xAxis->labels->rotation = -35;
    $linechart->xAxis->labels->x = 5;
    $linechart->xAxis->labels->y = 20;
    $linechart->xAxis->dataLabels->formatter = new HighRollerFormatter();
    $linechart->yAxis->labels->formatter = new HighRollerFormatter();
    $linechart->yAxis->min = 0;
    $linechart->yAxis->maxPadding = 0.2;
    $linechart->yAxis->endOnTick = true;
    $linechart->yAxis->minorGridLineWidth = 0;
    $linechart->yAxis->minorTickInterval = 'auto';
    $linechart->yAxis->minorTickLength = 1;
    $linechart->yAxis->tickLength = 2;
    $linechart->yAxis->minorTickWidth = 1;
    $linechart->yAxis->title->text = 'Pageviews';
    $linechart->yAxis->title->align = 'high';
    $linechart->yAxis->title->style->font = '14px Metrophobic, Arial, sans-serif';
    $linechart->yAxis->title->rotation = 0;
    $linechart->yAxis->title->x = 60  ;
    $linechart->yAxis->title->y = -10;
    $linechart->yAxis->plotLines = array( array('color' => '#808080', 'width' => 1, 'value' => 0 ));
    $linechart->addSeries($series1);
    $linechart->addSeries($series2);
    $linechart->addSeries($series3);
    $linechart->enableAutoStep();
    ?>

    <head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

    <!-- HighRoller: set the location of Highcharts library -->
    <?php echo HighRoller::setHighChartsLocation("/highcharts/highcharts.js");?>
    <?php echo HighRoller::setHighChartsThemeLocation("/highcharts/themes/highroller.js");?>
    </head>

    <body>
    <div id="linechart"></div>
    <script type="text/javascript">

      // example of how to define a tooltip formatter in a highcharts chart using using highroller
      var myChartOptions = <?php echo $linechart->getChartOptionsObject()?>

      // define your own formatter for tooltip
      myChartOptions.tooltip.formatter = function() {
        return '<b>' + this.series.name + '</b><br/>' +
            Highcharts.dateFormat('%b %e', this.x) + ': ' + Highcharts.numberFormat(this.y, 0, ',') + ' views';
      };

      // define your own formatter for xAxis.labels
      myChartOptions.xAxis.labels.formatter = function() {
        var newDate = new Date(this.value);
        return Highcharts.dateFormat('%b %e', this.value);
      };

      // define your own formatter for yAxis.labels
      myChartOptions.yAxis.labels.formatter = function() {
        return Highcharts.numberFormat(this.value, '', ',');
      };

      $(document).ready(function(){
        <?php echo $linechart->renderChartOptionsObject('myChartOptions')?>
      });

    </script>
    </body>

#### Customization Summary
* Title: text, style, color, font, alignment
* Legend: enabled, custom background color with gradient
* Chart: height, width, margin, spacing, background color with gradient, borders, etc.
* ToolTip: style, background color and gradient, formatter, etc.
* xAxis: datetime format, various tick properties (tickInterval for date, etc.), labels, formatter, etc.
* yAxis: customized title, min/max, tick properties, dataLabel rotation, placement and a custom plotLine object properties
* PlotOptions: pointStart, pointIntervals, radius, dataLabel fonts, placement, alignment, etc.
* Series Data: customized line colors
* HighRoller: enableAutoStep() method for auto-stepping the xAxis labels
* Native JS Formatters: Tooltip, xAxis labels and yAxis labels

## Dependencies

* PHP 5.3.x
* Highcharts 2.1.6 (which has it's own dependencies, like jQuery or MooTools)

## Installation

Installation is easy:

* Clone or download this project to your local machine
* To use HighRoller, decompress the .zip from within the /releases project folder
* Drop the entire HighRoller folder created by the .zip into your favorite project or framework
* Set an include path to the HighRoller folder
* What the...what are you waiting for? Go!

## Roll your own!

Building and testing your own HighRoller release is a snap:

* Get - Clone or download this project to your local PHP-enabled dev workstation
* Config - Create a new vhost in your local web server and set the document root to the /test folder
* Dev - Modify the contents of the /src directory to your needs/liking
* Build - Execute the build.sh script to create your release (i.e. ./build.sh 1.0.1)
  * NOTE: executing the build.sh script with the same version overwrites that release.
* Test - Point your browser to that new vhost and test/verify your changes using the mini-site.
* Release - Everything looks good?  Sweet!  Install your own HighRoller!

#### NOTE HighRoller's on-board Test Suite requires a web server running locally with a virtual host setup for this project.

## Licensing

HighRoller is licensed by Gravity.com under the Apache 2.0 license, see the LICENSE file for more details.

## About HighRoller

HighRoller was conceived in the summer of 2011.  Some comrades and I had already been integrating Highcharts into a
PHP project for about 6 months and found ourselves duplicating code, juggling/overwriting theme settings and wrestling
with data and dates.

It became clear that a wrapper would eliminate all of these problems.  And so, HighRoller was born.

If you find HighRoller useful or have issues please drop me a line, I'd love to hear how you're using it or what features
should be improved.

HighRoller was open sourced by Gravity.com in 2011

* Lead Digger:      John McLaughlin
* Contact:          [@jmaclabs](https://twitter.com/#!/JMACLABS)
* Contributors:     Jim Plush [@jimplush](https://twitter.com/#!/jimplush) and John Kurkowski [@Bluu](https://twitter.com/#!/Bluu)

### HighRoller begs you to please wager responsibly.

## Important Note About Licensing for Highcharts

HighRoller is licensed by Gravity.com.  HighRoller comes with a copy of Highcharts.

Highcharts is licensed by Highsoft Solutions AS and can be obtained here:

[http://www.highcharts.com/products/highcharts] (http://www.highcharts.com/products/highcharts).

Highcharts is licensed for free for any personal or non-profit projects under the [Creative Commons Attribution-NonCommercial
3.0 License] (http://creativecommons.org/licenses/by-nc/3.0/).

[See the license and pricing details directly on the Highcharts.com site for more details.] (http://www.highcharts.com/license)


