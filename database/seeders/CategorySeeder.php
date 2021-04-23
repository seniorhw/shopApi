<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => '电子数码',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '手机',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '华为',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                            [
                                'name' => '小米',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                        ]
                    ],
                    [
                        'name' => '电脑',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '联想',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                            [
                                'name' => '戴尔',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => '服装衣帽',
                'group' => 'goods',
                'pid' => 0,
                'level' => 1,
                'children' => [
                    [
                        'name' => '男装',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '海澜之家',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                            [
                                'name' => 'Nike',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                        ]
                    ],
                    [
                        'name' => '女装',
                        'group' => 'goods',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => '欧时力',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                            [
                                'name' => 'Only',
                                'group' => 'goods',
                                'level' => 3,
                            ],
                        ]
                    ],
                ]
            ],
        ];

        //填充到数据库
        foreach ($categories as $one){
            $l1_tmp = $one;
            unset($l1_tmp['children']);
            $l1_model = Category::create($l1_tmp);
            foreach ($one['children'] as $two){
                $l2_tmp = $two;
                unset($l2_tmp['children']);
                $l2_tmp['pid'] = $l1_model->id;
                $l2_model = Category::create($l2_tmp);
                $l2_model->childs()->createMany($two['children']);
            }

        }
        //清除缓存
        forget_cache_category();
    }
}
