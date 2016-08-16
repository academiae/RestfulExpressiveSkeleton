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

namespace CodingMatters\Student\Repository;

use CodingMatters\Rest\Repository\ListRepositoryInterface;
use CodingMatters\Student\Entity\StudentEntity;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\HydratingResultSet;

final class MasterListRepository implements ListRepositoryInterface
{
    /**
     * @var StudentEntity
     */
    private $prototype;

    /**
     * @var AdapterInterface 
     */
    private $dbAdapter;

    /**
     * @var HydratorInterface 
     */
    private $hydrator;

    /**
     * @param StudentEntity $prototype
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, StudentEntity $prototype)
    {
        $this->dbAdapter = $dbAdapter;
        $this->prototype = $prototype;
        $this->hydrator  = $hydrator;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll()
    {
        $sql        = new Sql($this->dbAdapter);
        $select     = $sql->select('students');
        $statement  = $sql->prepareStatementForSqlObject($select);
        $result     = $statement->execute();

        return $this->initializeResult($result);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchById($id)
    {
        $sql       = new Sql($this->dbAdapter);
        $select    = $sql->select('students');
        $select->where(['student_id = ?' => $id]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        return $this->initializeResult($result);
    }

    /**
     * 
     * @param ResultInterface $result
     * @return HydratingResultSet
     */
    protected function initializeResult(ResultInterface $result)
    {
        $resultSet = new HydratingResultSet($this->hydrator, $this->prototype);
        return $resultSet->initialize($result);
    }
}
