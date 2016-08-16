<?php

/**
 * The MIT License
 *
 * Copyright (c) 2016, Coding Matters, Inc. (Gab Amba <gamba@gabbydgab.com>)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace CodingMatters\Student;

/**
 * Zend Expressive Config Provider
 */
final class ConfigProvider
{
    public function __invoke()
    {
        return $this->getConfig();
    }

    public function getConfig()
    {
        return [
            'dependencies'          => $this->getServiceConfig(),
            'routes'                => $this->getRouteConfig()
        ];
    }

    public function getServiceConfig()
    {
        return [
            'invokables'    => [],
            'factories'     => [              
                Controller\MasterListController::class  => Factory\Controller\MasterListControllerFactory::class,
                Repository\MasterListRepository::class  => Factory\Repository\MasterListRepositoryFactory::class,
            ],
            'delegator'     => []
        ];
    }

    public function getRouteConfig()
    {
        return [
            [
                'name'          => 'students',
                'path'          => '/students[/:uuid]',
                'options'       => [
                    'constraints' => [
                        'uuid' => '[a-zA-Z0-9_-]*',
                    ]
                ],
                'middleware'    => [
                    Controller\MasterListController::class
                ]
            ]
        ];
    }
}
