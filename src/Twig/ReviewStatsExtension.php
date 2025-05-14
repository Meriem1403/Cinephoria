<?php
// src/Twig/ReviewStatsExtension.php
namespace App\Twig;

use App\Repository\ReviewRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ReviewStatsExtension extends AbstractExtension
{
    public function __construct(
        private ReviewRepository      $reviewRepo,
        private ChartBuilderInterface $chartBuilder
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('latest_reviews',    [$this, 'getLatestReviews']),
            new TwigFunction('top_voted_reviews', [$this, 'getTopVotedReviews']),
            new TwigFunction('reviews_chart',     [$this, 'createReviewsChart']),
        ];
    }

    public function getLatestReviews(): array
    {
        return $this->reviewRepo->findBy([], ['createdAt' => 'DESC'], 10);
    }

    public function getTopVotedReviews(): array
    {
        return $this->reviewRepo
            ->createQueryBuilder('r')
            ->orderBy('r.rating', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function createReviewsChart(): Chart
    {
        $end    = new \DateTimeImmutable('today');
        $start  = $end->sub(new \DateInterval('P6D'));
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->add(new \DateInterval('P1D')));

        // Initialise tous les jours à zéro
        $counts = [];
        foreach ($period as $date) {
            $counts[$date->format('Y-m-d')] = 0;
        }

        $rows = $this->reviewRepo->createQueryBuilder('r')
            ->select("SUBSTRING(r.createdAt, 1, 10) AS day, COUNT(r.id) AS cnt")
            ->where('r.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $start->setTime(0, 0, 0))
            ->setParameter('end',   $end->setTime(23, 59, 59))
            ->groupBy('day')
            ->orderBy('day', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($rows as $row) {
            $counts[$row['day']] = (int) $row['cnt'];
        }

        // Construction du Chart.js
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels'   => array_keys($counts),
            'datasets' => [[
                'label'           => 'Reviews per day',
                'data'            => array_values($counts),
                'fill'            => true,
                'borderColor'     => '#0D99FF',
                'backgroundColor' => 'rgba(13, 153, 255, 0.2)',
                'borderWidth'     => 2,
                'pointRadius'     => 4,
                'pointBackgroundColor' => '#0D99FF',
                'pointBorderColor'     => '#fff',
                'pointBorderWidth'     => 2,
            ]],
        ]);

        $chart->setOptions([
            'maintainAspectRatio' => false,
            'responsive'          => true,
            'plugins'             => [
                'legend' => [
                    'display'  => true,
                    'position' => 'top',
                    'align'    => 'center',
                    'labels'   => [
                        'usePointStyle' => true,
                        'pointStyle'    => 'circle',
                        'boxWidth'      => 8,
                        'padding'       => 20,
                        'color'         => '#333',   // couleur du texte de légende
                        'font'          => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0,0,0,0.75)',
                    'titleFont'       => ['weight' => 'bold'],
                    'bodyFont'        => ['size' => 14],
                    'padding'         => 10,
                    'cornerRadius'    => 4,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => ['display' => false],
                    'ticks'=> ['padding' => 10, 'color' => '#555'],
                ],
                'y' => [
                    'grid' => [
                        'color'      => 'rgba(200,200,200,0.2)',
                        'borderDash' => [5,5],
                    ],
                    'ticks'=> ['padding' => 10, 'color' => '#555'],
                ],
            ],
        ]);

        return $chart;
    }
}