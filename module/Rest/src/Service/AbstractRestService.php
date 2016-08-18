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

namespace CodingMatters\Rest\Service;

use CodingMatters\Rest\Mapper\Database\DatabaseMapperInterface;
use CodingMatters\Rest\Entity\EntityPrototype;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Psr\Log\InvalidArgumentException;

abstract class AbstractRestService
{
    /**
     * @var DatabaseMapperInterface
     */
    protected $mapper;

    /**
     * @var AbstractResultSet
     */
    protected $resultSet;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string
     */
    protected $primary_key = '';

    /**
     *
     * @param DatabaseMapperInterface $mapper
     */
    public function __construct(DatabaseMapperInterface $mapper, HydratingResultSet $resultSet)
    {
        $this->resultSet    = $resultSet;
        $this->mapper       = $mapper;
    }

    public function fetchAll()
    {
        if (!isset($this->table)) {
            throw new InvalidArgumentException('Table schema not set.');
        }

        $select = new Select($this->table);
        return $this->selectData($select);
    }

    public function fetchById($id)
    {
        if (!isset($this->table)) {
            throw new InvalidArgumentException('Table schema not set.');
        }

        if (!isset($this->primary_key)) {
            throw new InvalidArgumentException('Primary key not set.');
        }

        $sqlObject = new Select($this->table);
        $sqlObject->where(["{$this->primary_key} = ?" => $id]);

        return $this->selectData($sqlObject);
    }

    public function enlist(array $data)
    {
        $sqlObject = new Insert($this->table);
        $sqlObject->values($data);
        $this->mapper->insert($sqlObject);

        $hydrator = $this->resultSet->getHydrator();
        $object = $this->resultSet->getObjectPrototype();
        $hydrator->hydrate($data, $object);

        return $object;
    }

    public function update(EntityPrototype $entity)
    {
        if (!isset($this->primary_key)) {
            throw new InvalidArgumentException('Primary key not set.');
        }

        $sqlObject = new Update($this->table);
        $sqlObject->set($entity->toArray());
        $sqlObject->where(["{$this->primary_key} = ?" => $entity->getId()]);

        return $this->mapper->update($sqlObject);
    }

    public function delete(EntityPrototype $entity)
    {
        if (!isset($this->primary_key)) {
            throw new InvalidArgumentException('Primary key not set.');
        }

        $sqlObject = new Delete($this->table);        
        $sqlObject->where(["{$this->primary_key} = ?" => $entity->getId()]);

        return $this->mapper->delete($sqlObject);
    }

    protected function selectData(Select $sqlObject)
    {
        $data = $this->mapper->select($sqlObject);
        return $this->resultSet->initialize($data);
    }
}
