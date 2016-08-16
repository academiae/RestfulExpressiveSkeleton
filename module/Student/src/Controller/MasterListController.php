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

namespace CodingMatters\Student\Controller;

use CodingMatters\Student\Repository\MasterListRepository;
use CodingMatters\Rest\Controller\AbstractRestController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;

final class MasterListController extends AbstractRestController
{
    /**
     * @var MasterListRepository
     */
    private $repository;

    /**
     * @param MasterListRepository $repository
     */
    public function __construct(MasterListRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function get(Request $request, Response $response, callable $out = null)
    {
        $id = $request->getAttribute(self::IDENTIFIER_NAME);
        $student = $this->repository->fetchById($id);

        if ($student->count() > 0) {
            return $this->createResponse($student->toArray());
        }

        // Throw error message if id is missing
        $status         = 404;
        $notFound       = 'Not Found';
        $error_message  = sprintf("Student with id '%s' {$notFound}.", $id);
        $data = [
            'error'     => $this->responsePhraseToCode($notFound),
            'message'   => $error_message,
            'status'    => $status
        ];

        return $this->createResponse($data, $status);
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $out
     * @return JsonResponse
     */
    public function getList(Request $request, Response $response, callable $out = null)
    {
        $student = $this->repository->fetchAll();
        return $this->createResponse(['students' => $student->toArray()]);
    }
}
