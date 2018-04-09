<?php

namespace App\Console\Commands;

use DB;
use App\Models\Group;
use Illuminate\Console\Command;

class ImportGroupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        DB::transaction(function () {
            $this->createGroups();
            return $this->linkGroups();
        });
    }

    public function createGroups()
    {
        DB::connection('old')->table('groups')->orderBy('id')
        ->chunk(200, function ($items) {
            $items->each(function ($item) {
                $group = new Group();
                $group->name = $item->name;
                $group->image_type = $item->img_type;
                $group->image_colors = $item->img_color;
                $group->is_visible = $item->inc;
                $group->save();
            });
        });
    }

    public function linkGroups()
    {
        DB::connection('old')->table('groups')->orderBy('id')
        ->chunk(200, function ($items) {
            $items->each(function ($item) {
                $group = Group::where('name', $item->name)
                ->where('image_type', $item->img_type)
                ->first();

                $parentItem = DB::connection('old')->table('groups')
                ->where('id', $item->parent_id)
                ->first();

                if ($parentItem) {
                    $parent = Group::where('name', $parentItem->name)
                    ->where('image_type', $parentItem->img_type)
                    ->first();

                    $group->parents()->syncWithoutDetaching($parent->id);
                }

                if ($item->type == 'sit') {
                    $sit = DB::connection('old')->table('situations')->orderBy('id')
                    ->where('group_id', $item->id)
                    ->chunk(200, function ($sits) use ($group) {
                        $sits->each(function ($sit) use ($group) {
                            $leafgroup = new Group();
                            $leafgroup->name = $group->name;
                            $leafgroup->image_type = $sit->img_type;
                            $leafgroup->image_colors = $sit->img_color;
                            $leafgroup->is_leaf = true;
                            $leafgroup->save();
                            $group->children()->syncWithoutDetaching($leafgroup->id);

                            DB::connection('old')->table('algs')
                            ->where('sit_id', $sit->id)
                            ->get()
                            ->each(function ($alg) use ($leafgroup) {
                                $leafgroup->algs()->create([
                                    'alg' => $alg->alg,
                                    'is_confirmed' => $alg->stat,
                                ]);
                            });
                        });
                    });
                }
            });
        });
    }
}
