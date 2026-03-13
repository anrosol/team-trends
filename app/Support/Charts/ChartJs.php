<?php

namespace App\Support\Charts;

class ChartJs
{
    public function __construct(
        private array $data,
        private bool $showCount = true
    ) {}

    private function getDataSets()
    {
        $result = [];

        foreach ($this->data as $item) {
            $result[] = [
                'type' => 'line',
                'label' => $item->line['name'],
                'data' => array_column($item->data, 'average'),
                'yAxisID' => 'y',
                'tension' => 0.4,
                'borderColor' => $item->line['color'],
                'backgroundColor' => $item->line['color'],
            ];
        }

        $result[] = [
            'type' => 'line',
            'label' => __('chart_js.surveys'),
            'data' => array_column($this->data[0]->data, 'count'),
            'yAxisID' => 'y1',
            'fill' => true,
            'hidden' => ! $this->showCount,
        ];

        return $result;
    }

    private function getLabels()
    {
        return array_map(function ($item) {
            return $item['date']->userTimezone()->format('Y-m-d');
        }, $this->data[0]->data);
    }

    public function toArray()
    {
        return [
            'data' => [
                'datasets' => $this->getDatasets(),
                'labels' => $this->getLabels(),
            ],
            'options' => [
                'responsive' => true,
                'interaction' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'stacked' => true,
                'scales' => [
                    'x' => [
                        'position' => 'bottom',
                        'title' => [
                            'display' => true,
                            'text' => __('chart_js.period'),
                        ],
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => __('chart_js.average'),
                        ],
                        'min' => 0,
                        'max' => 6,
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'left',
                    ],
                    'y1' => [
                        'title' => [
                            'display' => true,
                            'text' => __('chart_js.surveys'),
                        ],
                        'min' => 0,
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'right',
                        'grid' => [
                            'drawOnChartArea' => false, // Grid lines for one axis only.
                        ],
                        'ticks' => [
                            'precision' => 0,
                        ],
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'labels' => [
                            'usePointStyle' => true,
                        ],
                    ],
                ],
            ],
        ];
    }
}
