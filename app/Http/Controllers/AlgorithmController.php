<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function Laravel\Prompts\search;

class AlgorithmController extends Controller
{
    const CASES = [
        [
            'neededContainer' => 3,
            'listings' => [
                [
                    'name' => 'Container renter A',
                    'container' => 1,
                    'totalCost' => 1,
                ],
                [
                    'name' => 'Container renter B',
                    'container' => 2,
                    'totalCost' => 1,
                ],
                [
                    'name' => 'Container renter C',
                    'container' => 3,
                    'totalCost' => 3,
                ],
            ]
        ],
        [
            'neededContainer' => 10,
            'listings' => [
                [
                    'name' => 'Container renter A',
                    'container' => 5,
                    'totalCost' => 5,
                ],
                [
                    'name' => 'Container renter B',
                    'container' => 2,
                    'totalCost' => 10,
                ],
                [
                    'name' => 'Container renter C',
                    'container' => 2,
                    'totalCost' => 3,
                ],
            ]
        ],
        [
            'neededContainer' => 10,
            'listings' => [
                [
                    'name' => 'Container renter A',
                    'container' => 5,
                    'totalCost' => 5,
                ],
                [
                    'name' => 'Container renter B',
                    'container' => 2,
                    'totalCost' => 10,
                ],
                [
                    'name' => 'Container renter C',
                    'container' => 10,
                    'totalCost' => 3,
                ],
            ]
        ]
    ];
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cases = self::CASES;
        $testcases = [];
        foreach($cases as $case) {
            $testcases[] = json_encode($case, JSON_PRETTY_PRINT);
        }
        $data = [];
        foreach ($cases as $case) {
            $data[] = $this->findClosestCost($case);
        }
        return view('algorithm', compact('data', 'testcases'));
    }
    private function findClosestCost($case)
    {
        //find all available case without consider cost
        $result = [];
        $len = count($case['listings']);
        for($i = 0; $i < 2**$len; $i++) {
            $sign = str_pad(decbin($i), $len, 0, STR_PAD_LEFT);
            $signExplode = str_split($sign,1);

            $arrFromBin = [];
            foreach($signExplode as $key => $e) {

                if ($e == "1") {
                    $arrFromBin[] = $case['listings'][$key];
                }
            }

            if (!empty($arrFromBin)) {
                list($cost, $container) = $this->getTotalCostAndContainer($arrFromBin);
                $result[] = [
                    'data' => $arrFromBin,
                    'container_needed' => $case['neededContainer'],
                    'container_actual' => $container,
                    'container_cost' => $cost,
                ];
            }
        }
        //find the closest case priority highest enough container then closest cost
        $current = $result[0];
        for($i = 1; $i < count($result); $i++){
            if (
                abs($current['container_needed'] - $current['container_actual']) >
                abs($result[$i]['container_needed'] - $result[$i]['container_actual'])
            ) {
                $current = $result[$i];
            }
            if (
                (abs($current['container_needed'] - $current['container_actual']) ==
                abs($result[$i]['container_needed'] - $result[$i]['container_actual'])) &&
                $current['container_cost'] > $result[$i]['container_cost']
            ) {
                $current = $result[$i];
            }
        }
        return $current;
    }
    private function getTotalCostAndContainer($options)
    {
        $cost = 0;
        $current_container = 0;
        foreach($options as $option)
        {
            $cost += $option['totalCost'];
            $current_container += $option['container'];
        }
        return [$cost, $current_container];
    }


}
