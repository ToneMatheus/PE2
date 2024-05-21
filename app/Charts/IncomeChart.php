<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;


class IncomeChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($labels, $values, $startDate, $endDate)
    {
        return $this->chart->lineChart()->addData('Monthly Income', $values)->setLabels($labels)
        ->setMarkers()->setStroke(3, [], 'smooth')->setTitle('Gross Income')->setSubtitle($startDate.'  -  '.$endDate);
    }
}
