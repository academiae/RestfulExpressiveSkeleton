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

namespace CodingMatters\Rest\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractRestController implements MiddlewareInterface
{
    const IDENTIFIER_NAME = 'uuid';

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse | Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $id = $request->getAttribute(static::IDENTIFIER_NAME);

        switch (strtoupper($request->getMethod())) {
            case 'GET':
                return isset($id)
                    ? $this->get($request, $response, $out)
                    : $this->getList($request, $response, $out);
            case 'POST':
                return $this->create($request, $response, $out);
            case 'PUT':
                return $this->update($request, $response, $out);
            case 'DELETE':
                return isset($id)
                    ? $this->delete($request, $response, $out)
                    : $this->deleteList($request, $response, $out);
            case 'HEAD':
                return $this->head($request, $response, $out);
            case 'OPTIONS':
                return $this->options($request, $response, $out);
            case 'PATCH':
                return $this->patch($request, $response, $out);
            default:
                return $out($request, $response);
        }
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function get(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function getList(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function create(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function update(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function delete(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function deleteList(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function head(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function options(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return JsonResponse
     */
    public function patch(Request $request, Response $response, callable $out = null)
    {
        return $this->createResponse(['content' => 'Method not allowed'], 405);
    }

    /**
     * Composes the response to a Json formated data
     *
     * @param array $data
     * @param integer $status
     * @return JsonResponse
     */
    final protected function createResponse(array $data, int $status = 200)
    {
        return new JsonResponse($data, $status);
    }

    /**
     *
     * @param string $responsePhrase
     * @return string
     */
    final protected function responsePhraseToCode(string $responsePhrase)
    {
        return strtoupper(str_replace(' ', '_', $responsePhrase));
    }
}
