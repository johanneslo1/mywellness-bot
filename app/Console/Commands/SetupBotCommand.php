<?php

namespace App\Console\Commands;

use App\Models\SearchRequest;
use App\Repositories\MyWellnessRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $myWellnessRepository = app(MyWellnessRepository::class);

        $this->info('Welcome to the setup script!');
        $this->line('This script will help you to configure the bot for your needs.');
        $this->line('First, you need to configure the filters for the free times.');

        $this->line('Loading available filters...');

        $filters = $myWellnessRepository->getAvailableFilters();

        $this->info('Filters loaded successfully!');

        $filterPreferences = [];

        $filtersOfTypeSelect = array_filter($filters, fn ($i) => array_key_exists('input_type', $i) && $i['input_type'] === 'select');

        foreach ($filtersOfTypeSelect as $filter) {
            $title = $filter['title'];
            $options = array_map(fn ($i) => $i['value'], $filter['options']);
            $multiple = (bool) $filter['allow_multiple_values'];

            $this->info('Please look at the following options. You have to select an option by its value.');

            $this->table([
                'Title', 'Value',
            ], array_map(fn ($i) => [$i['title'], $i['value']], $filter['options']));

            $choice = $this->choice($title, $options, 0, null, $multiple);

            $filterPreferences[$filter['name']] = $choice;
        }

        $this->info('Thank you! You have configured the all required filters.');

        $searchRequest = SearchRequest::create([
            'params' => $filterPreferences,
            'email'  => null,
            'active' => true,
        ]);

        if ($this->confirm('Do you want to receive notifications via e-mail?')) {
            $this->info('Please enter your e-mail address so that you can receive the free times');
            $email = $this->ask('What is your e-mail address?');
            $email = Str::of($email)->trim()->lower();

            $searchRequest->update([
                'email' => $email,
            ]);

            $this->info('Thank you!');
        }

        if ($this->confirm('Do you want to receive notifications via telegram?')) {
            $this->line('How to create a Telegram Bot: https://core.telegram.org/bots#how-do-i-create-a-bot');

            $token = $this->ask('Please enter a Telegram Bot Token:');

            $searchRequest->update([
                'telegram_token' => Str::of($token)->trim(),
            ]);

            $this->info('Thank you!');
        }

        return Command::SUCCESS;
    }
}
