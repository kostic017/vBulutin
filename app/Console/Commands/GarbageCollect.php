<?php

namespace App\Console\Commands;

use App\Topic;
use App\ReadTopic;

use Illuminate\Console\Command;

class GarbageCollect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'garbagec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform garbage collection';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = ReadTopic::whereIn('topic_id', Topic::olderTopics()->pluck('id'))->delete();
        \App\Helpers\Logger::log("Removed $count topics from `read_topics` table.");
    }
}
