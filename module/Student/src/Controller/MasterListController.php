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

//use CodingMatters\Student\Repository\MasterListRepository;
use CodingMatters\Student\Repository\RestRepository;
use CodingMatters\Rest\Controller\AbstractRestController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use CodingMatters\Student\Entity\StudentEntity;

final class MasterListController extends AbstractRestController
{
    /**
     * @var RestRepository
     */
    private $repository;

    /**
     * @param RestRepository $repository
     */
    public function __construct(RestRepository $repository)
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

        // Return error message if data is missing
        if ($student->count() === 0) {
            $code       = 404;
            $status     = 'Not Found';
            $message    = sprintf("Student with id '%s' not found.", $id);

            return $this->createResponse($this->formatMessage($code, $status, $message), $code);
        }

        // Else return $object->toArray()
        return $this->createResponse($student->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function getList(Request $request, Response $response, callable $out = null)
    {
        $student = $this->repository->fetchAll();
        return $this->createResponse(['students' => $student->toArray()]);
    }

    public function create(Request $request, Response $response, callable $out = null)
    {
        $data   = $request->getParsedBody();

        try {
            $output = $this->repository->enlist($data);

            $code   = 200;
            $status = 'OK';
            $message = sprintf("Student with id '%s' is successfully added.", $data['student_id']);
        } catch (\Exception $ex) {
            $code = 403;
            $status = 'Forbidden';
            $message = sprintf("Student with id '%s' has existing record.", $data['student_id']);
        }

        return $this->createResponse($this->formatMessage($code, $status, $message), $code);
    }

    public function update(Request $request, Response $response, callable $out = null)
    {
        $id = $request->getAttribute(self::IDENTIFIER_NAME);
        $data = $request->getParsedBody();
        var_dump($data);
        exit;
        $result = $this->repository->fetchById($id);

        if ($result->count() > 0) {
            $hydrator   = $result->getHydrator();
            $object     = $$result->getObjectPrototype();
            $hydrator->hydrate($data, $object);
            var_dump($object);
            exit;
            $result = $this->repository->update($entity->current());
            var_dump($request);
            exit;
        }

        return $this->createResponse($this->formatMessage($code, $status, $message), $code);
    }
}
