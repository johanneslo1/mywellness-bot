<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\SearchRequest;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\MyWellnessRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SearchRequestWasSuccessfulNotification;

class CheckSearchRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search-requests:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    public function handle(MyWellnessRepository $myWellnessRepository)
    {
        SearchRequest::query()
            ->where('active', true)
            ->where(fn (Builder $query) => $query
                ->whereDate('last_check_at', '<', now()->subHour())
                ->orWhereNull('last_check_at')
            )
            ->where(fn (Builder $query) => $query
                ->whereDate('ends_at', '>', now())
                ->orWhereNull('ends_at')
            )
            ->get()
            ->each(function (SearchRequest $searchRequest) use ($myWellnessRepository) {
                $searchRequest->last_check_at = now();


                $days = $myWellnessRepository->getFullyAvailableDaysOfNextThreeMonths($searchRequest);


                // Prüfe ob einer der Tage innerhalb der nächsten Woche liegt.
                $daysInCommingWeek = $days
                    ->filter(fn($item) => in_array($item['date']->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY, Carbon::SUNDAY]))
//                    ->filter(fn($item) => $item['date']->isBetween(now(), now()->addWeeks(2)))
                    ->map(fn($item) => [
                        'date' => $item['date'],
                        'status' => $item['status'],
                        'url' => 'https://buchen.mywellness.de/#/booking?' . preg_replace('/%5B[0-9]+%5D/simU', '', http_build_query($searchRequest->params))
                    ])->values();

                ray($daysInCommingWeek);

                if($daysInCommingWeek->isNotEmpty()) {
                    Notification::send($searchRequest, new SearchRequestWasSuccessfulNotification($daysInCommingWeek));

                }

                $searchRequest->save();
            });


        return Command::SUCCESS;
    }


}
